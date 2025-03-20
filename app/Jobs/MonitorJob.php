<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Monitors;
use App\Models\DnsResponse;
use App\Mail\MonitorDownAlert;
use Illuminate\Support\Facades\Mail;

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
                \Log::error("DNS check failed for {$monitor->url}: " . $e->getMessage());
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
            \Log::error("Failed to insert DNS response for {$monitor->url}: " . $e->getMessage());
        }
    
        $this->sendAlert($monitor,$status);
        // Update last_checked_at in the monitors table
        try {
            $monitor->update([
                'last_checked_at' => now(),
                'status' => $status // Also update the monitor's last status
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to update monitor status for {$monitor->url}: " . $e->getMessage());
        }

        
    
        return $records ?: null;
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
            }
        }
    }




}
