<?php

namespace App\Jobs;

use App\Models\HttpResponse;
use App\Models\PortResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Monitors;
use App\Models\DnsResponse;
use App\Mail\MonitorDownAlert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use App\Models\PingResponse;

class MonitorJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    private function sendAlert(Monitors $monitor, string $status)
    {
        if ($status === 'down' && ($monitor->status === 'up' || $monitor->status === null)) {

            Mail::to($monitor->email)->send(new MonitorDownAlert($monitor));

            if($monitor->telegram_bot_token && $monitor->telegram_id )
             {
                $this->sendTelegramNotification($monitor);
             }
        }
    }

    private function sendTelegramNotification(Monitors $monitor)
    {
        $botToken = $monitor->telegram_bot_token;
        $chatId = $monitor->telegram_id;

        $message = "🚨 *Monitor Down Alert!*
        \n🔴 *URL:* {$monitor->url}
        \n🛠 *Type:* {$monitor->type}
        \n📅 *Detected At:* " . now()->toDateTimeString();

        
        Http::get("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);
    }


private function checkHttp(Monitors $monitor)
{
    $status = 'down';
    $statusCode = 0;
    $responseTime = 0;
    Log::info("Checking HTTP Monitor: {$monitor->id} ({$monitor->url})");

    for ($attempt = 0; $attempt < $monitor->retries; $attempt++) {
        try {
            $startTime = microtime(true);
            $response = Http::timeout(10)->get($monitor->url);
            $endTime = microtime(true);

            $statusCode = $response->status();
            $responseTime = round(($endTime - $startTime) * 1000, 2);

            Log::info("HTTP Response ({$monitor->id}): Status $statusCode, Time {$responseTime}ms");

            // Determine status based on status code
            if ($response->successful()) {
                $status = 'up';
            } else {
                $status = 'down';
                Log::warning("HTTP Monitor {$monitor->id} returned non-success status: {$statusCode}");
            }

            break; // Exit retry loop on success

        } catch (RequestException $e) {
            Log::error("HTTP RequestException (Monitor ID: {$monitor->id}): " . $e->getMessage());
            $statusCode = $e->response ? $e->response->status() : 0;

            // Handle specific HTTP errors
            if ($statusCode === 403) {
                Log::warning("HTTP Monitor {$monitor->id} returned 403 (Forbidden).");
            }

        } catch (\Exception $e) {
            Log::error("General HTTP Exception (Monitor ID: {$monitor->id}): " . $e->getMessage());

            // Handle timeouts and SSL errors
            if (strpos($e->getMessage(), 'timed out') !== false) {
                $statusCode = 408; // Request Timeout
            } elseif (strpos($e->getMessage(), 'SSL') !== false) {
                $statusCode = 495; // SSL Failure
            }
        }

        // Exponential backoff (max 5s)
        if ($attempt < $monitor->retries - 1) {
            sleep(min(pow(2, $attempt), 5));
        }
    }

    // Store response in the http_response table
    try {
        HttpResponse::create([
            'monitor_id' => $monitor->id,
            'status' => $status,
            'status_code' => $statusCode,
            'response_time' => $responseTime,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    } catch (\Exception $e) {
        Log::error("Failed to insert HTTP response for {$monitor->id}: " . $e->getMessage());
    }

    // Send alert if status is down
    $this->sendAlert($monitor, $status);

    // Update monitor status
    try {
        $monitor->update([
            'last_checked_at' => now(),
            'status' => $status,
        ]);
    } catch (\Exception $e) {
        Log::error("Failed to update monitor status for {$monitor->id}: " . $e->getMessage());
    }
}



    private function checkDnsRecords(Monitors $monitor)
    {
        $parsedDomain = parse_url($monitor->url, PHP_URL_HOST) ?? $monitor->url;
    
        $dnsTypes = [
            'A' => DNS_A,
            'AAAA' => DNS_AAAA,
            'CNAME' => DNS_CNAME,
            'MX' => DNS_MX,
            'NS' => DNS_NS,
            'SOA' => DNS_SOA,
            'TXT' => DNS_TXT,
            'SRV' => DNS_SRV,
        ];
    
        $attempt = 0;
        $records = null;
        $startTime = microtime(true); // Start timing
    
        while ($attempt < $monitor->retries) {
            try {
                $records = @dns_get_record($parsedDomain, $dnsTypes[$monitor->dns_resource_type] ?? DNS_A); // Suppress warnings
                if ($records) {
                    break; // Exit retry loop if records are found
                }
            } catch (\Exception $e) {
               Log::error("DNS check failed for {$monitor->url}: " . $e->getMessage());
                break; // Exit on failure
            }
    
            $attempt++;
            sleep(min(pow(2, $attempt), 5)); // Exponential backoff with a max wait of 5s
        }
    
        $responseTime = round((microtime(true) - $startTime) * 1000, 2); // Convert to ms
        $status = $records ? 'up' : 'down';
    
        // Store response in the dns_responses table
        try {
            DnsResponse::create([
                'monitor_id' => $monitor->id,
                'status' => $status,
                'response_time' => $responseTime
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to insert DNS response for {$monitor->url}: " . $e->getMessage());
        }
    
        $this->sendAlert($monitor,$status);
        // Update last_checked_at in the monitors table
        try {
            $monitor->update([
                'last_checked_at' => now(),
                'status' => $status // Also update the monitor's last status
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update monitor status for {$monitor->url}: " . $e->getMessage());
        }

        
    
        return $records ?: null;
    }

    private function checkPort(Monitors $monitor)
    {
        $attempt = 0;
        $status = 'down';
        $responseTime = 0;
        $startTime = microtime(true);
        $retries = $monitor->retries ?? 3; // Default to 3 retries if not set

        while ($attempt < $retries) {
            $connection = @fsockopen($monitor->host, $monitor->port, $errno, $errstr, 5);
            if ($connection) {
                fclose($connection);
                $status = 'up';
                $responseTime = round((microtime(true) - $startTime) * 1000, 2); // Convert to ms
                break;
            }

            $attempt++;
            sleep(min(pow(2, $attempt), 5)); // Exponential backoff with a max wait of 5s
        }

        
        // Store response in the port_responses table
        PortResponse::create([
            'monitor_id' => $monitor->id,
            'status' => $status,
            'response_time' => $status === 'up' ? $responseTime : 0
        ]);

        $this->sendAlert($monitor, $status);


        // Update last_checked_at and status in the monitors table
        $monitor->update([
            'last_checked_at' => now(),
            'status' => $status
        ]);

        return $status;
    }





    private function checkPing(Monitors $monitor)
    {
        $domain = parse_url($monitor->url, PHP_URL_HOST) ?? $monitor->url;
        $attempt = 0;
        $status = 'down';  
        $responseTime = 0;
        $startTime = microtime(true); 
        $retries = $monitor->retries; // Get retries from DB

        while ($attempt < $retries) {
            $command = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? "ping -n 1 {$domain}" : "ping -c 1 {$domain}";
            exec($command, $output, $resultCode);

            if ($resultCode === 0) { 
                $status = 'up';
                break;
            }

            $attempt++;
            sleep(min(pow(2, $attempt), 5)); // Exponential backoff
        }

        $responseTime = round((microtime(true) - $startTime) * 1000, 2); 

        // Store response
        PingResponse::create([
            'monitor_id' => $monitor->id,
            'status' => $status,
            'response_time' => $responseTime
        ]);


        $this->sendAlert($monitor, $status);
        // Update monitor status
        $monitor->update([
            'last_checked_at' => now(),
            'status' => $status
        ]);

        return $status === 'up';
    }











    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $monitors = Monitors::whereRaw('? >= DATE_ADD(last_checked_at, INTERVAL `interval` MINUTE)', [now()])
                    ->orWhereNull('last_checked_at')
                    ->get();
        
       

        foreach ($monitors as $monitor) {
            switch ($monitor->type) {
                case 'dns':
                    $this->checkDnsRecords($monitor);
                    break;
                case 'ping':
                    $this->checkPing($monitor);
                    break;
                case 'port':
                    $this->checkPort($monitor);
                    break;
                case 'http':
                    $this->checkHttp($monitor);
                    break;
            }
        }
    }




}
