<?php

namespace App\Jobs;
use App\Models\Incident;
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

//  for notifications
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MonitorAlertNotification;



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

             // storing
             if ($user = auth()->user()) {  
                Notification::send($user, new MonitorAlertNotification($monitor));  
                Log::info("Notification stored for user ID: " . auth()->id());
            }
            
        }
    }

    private function sendTelegramNotification(Monitors $monitor)
    {
        $botToken = $monitor->telegram_bot_token;
        $chatId = $monitor->telegram_id;

        $message = "🚨 Monitor Down Alert!
        \n🔴 URL: {$monitor->url}
        \n🛠 Type: {$monitor->type}
        \n📅 Detected At: " . now()->toDateTimeString();

        
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
    try{
        $this->sendAlert($monitor, $status);
    }catch (\Exception $e) {
        Log::error(''. $e->getMessage());
    }
    $this->createIncident($monitor, $status, 'HTTP');

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
    
        try{
            $this->sendAlert($monitor,$status);
        }catch(\Exception $e){
            Log::error(''. $e->getMessage());
        }

        $this->createIncident($monitor, $status, 'DNS');

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
        $timeout = 5; // Timeout in seconds
    
        // Log the start of the check
        Log::info("Checking port {$monitor->port} on {$monitor->host} with {$retries} retries.");
    
        while ($attempt < $retries) {
            try {
                // Attempt to open the socket connection
                $connection = @fsockopen($monitor->host, $monitor->port, $errno, $errstr, $timeout);
    
                if ($connection) {
                    // Set a timeout for the socket
                    stream_set_timeout($connection, $timeout);
    
                    // Check if the connection is actually successful
                    $status = 'up';
                    $responseTime = round((microtime(true) - $startTime) * 1000, 2); // Convert to ms
                    fclose($connection);
                    break;
                } else {
                    Log::warning("Port check attempt $attempt failed: {$monitor->host}:{$monitor->port} - Error: $errstr ($errno)");
                }
            } catch (\Exception $e) {
                Log::error("Exception during port check attempt $attempt: " . $e->getMessage());
            }
    
            $attempt++;
            if ($attempt < $retries) {
                $waitTime = min(pow(2, $attempt), 5); // Exponential backoff with a max wait of 5s
                Log::info("Waiting {$waitTime} seconds before next attempt.");
                sleep($waitTime);
            }
        }
    
        // Store response in the port_responses table
        try {
            PortResponse::create([
                'monitor_id' => $monitor->id,
                'status' => $status,
                'response_time' => $status === 'up' ? $responseTime : 0
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to store port response: " . $e->getMessage());
        }
    
        // Send alert if necessary
        try {
            $this->sendAlert($monitor, $status);
        } catch (\Exception $e) {
            Log::error("Failed to send alert: " . $e->getMessage());
        }
        $this->createIncident($monitor, $status, 'PORT');
        // Update last_checked_at and status in the monitors table
        try {
            $monitor->update([
                'last_checked_at' => now(),
                'status' => $status
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to update monitor: " . $e->getMessage());
        }
    
        Log::info("Port check completed: {$monitor->host}:{$monitor->port} is $status.");
    
        return $status;
    }
    
    private function checkPing(Monitors $monitor)
    {
        try {
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
    
            // Send alert and create incident
           try{ 
                $this->sendAlert($monitor, $status);
            }catch (\Exception $e){
                Log::error($e->getMessage());
            }

            $this->createIncident($monitor, $status, monitorType: 'PING');

            // Update monitor status
            $monitor->update([
                'last_checked_at' => now(),
                'status' => $status
            ]);
    
            return $status === 'up';
    
        } catch (\Exception $e) {
            // Log the error with details about the monitor and the exception message
            
            // Optionally, send an alert to admins about the issue
            // \Mail::to('admin@example.com')->send(new ErrorOccurredNotification($e));
    
            // Return false or handle accordingly
            return false;
        }
    }
//NEW INCIDENTS
    private function createIncident(Monitors $monitor, string $status, string $monitorType)
    {
       
    // If the status is 'down', we create an incident
    if ($status === 'down') {
        // Check if there's an existing 'down' incident for the same monitor that's still open (no end_timestamp)
        $existingIncident = Incident::where('monitor_id', $monitor->id)
            ->where('status', 'down')  // Looking for incidents that are 'down'
            ->whereNull('end_timestamp')  // Ensure that the incident is still open
            ->first();
        
        // If no existing open incident, create a new one
        if (!$existingIncident) {
            Incident::create([
                'monitor_id' => $monitor->id,
                'status' => 'down',
                'root_cause' => "{$monitorType} Monitoring Failed",  // Log the type of failure (e.g., Ping, DNS, HTTP)
                'start_timestamp' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // If the status is 'up', we check and close any open incidents
    elseif ($status === 'up') {
        // Check for any open incidents (status = 'down' and no end_timestamp)
        $incident = Incident::where('monitor_id', $monitor->id)
            ->where('status', 'down')
            ->whereNull('end_timestamp')  // Ensure it's open (still 'down')
            ->first();

        // If an open incident is found, mark it as resolved
        if ($incident) {
            $incident->update([
                'status' => 'up',
                'end_timestamp' => now(),  // Set the time the monitor came back up
                'updated_at' => now(),
            ]);
        }
    }
}Log::info("Checking Monitor: {$monitor->id} ({$monitor->url})");
    
    public function handle(): void
    {
        try {
            $monitors = Monitors::where('paused', false) // Skip paused monitors
            ->whereRaw('? >= DATE_ADD(last_checked_at, INTERVAL `interval` MINUTE)', [now()])
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
        } catch (\Exception $e) {
            Log::error("MonitorJob failed: " . $e->getMessage());
        }
    }


}
