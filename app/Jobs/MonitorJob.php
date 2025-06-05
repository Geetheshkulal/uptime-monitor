<?php

namespace App\Jobs;
use App\Models\Incident;
use App\Models\HttpResponse;
use App\Models\Notification;
use App\Models\PortResponse;
use App\Models\PushSubscription;
use Carbon\Carbon;
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
use App\Mail\FollowUpMail;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Str;
use Illuminate\Support\Facades\Storage;



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
    // Only proceed for down alerts when monitor was previously up
    if ($status !== 'down' || !in_array($monitor->status, ['up', null])) {
        return;
    }

    // Load user relationship if not already loaded
    $monitor->loadMissing('user');
    $user = $monitor->user;

    // Check if we've already sent an alert recently (within last 5 minutes)
    $recentAlert = Notification::where('monitor_id', $monitor->id)
        ->where('created_at', '>=', now()->subMinutes(5))
        ->exists();
        
    if ($recentAlert) {
        Log::info("Skipping duplicate alert for monitor {$monitor->id} - alert already sent recently");
        return;
    }

    // Prepare alert details
    $details = [
        'url' => $monitor->url,
        'type' => $monitor->type,
        'time' => now()->toDateTimeString(),
        'phone' => $user->phone,
    ];

    try {
        // Store details to file for WhatsApp
        Storage::disk('local')->put('whatsapp-details.json', json_encode($details));
        Log::info('whatsapp-details.json saved with alert data.');

        // Run Dusk test in background
        $this->triggerWhatsAppNotification();

        // Generate unique token for notification
        $token = Str::random(32);

        // Create notification record first (acts as a lock)
        $notification = Notification::create([
            'monitor_id' => $monitor->id,
            'status' => 'unread',
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send email alert
        Mail::to($monitor->email)->send(new MonitorDownAlert($monitor, $token));

        // Send Telegram notification if configured
        if ($monitor->telegram_bot_token && $monitor->telegram_id && $monitor->user->status !== 'free') {
            $this->sendTelegramNotification($monitor);
        }

        // Update monitor status to prevent duplicate alerts
        $monitor->update(['status' => 'down']);

    } catch (\Exception $e) {
        Log::error("Failed to send alert for monitor {$monitor->id}: " . $e->getMessage());
        
        // Clean up failed notification if it was created
        if (isset($notification)) {
            try {
                $notification->delete();
            } catch (\Exception $deleteException) {
                Log::error("Failed to clean up notification: " . $deleteException->getMessage());
            }
        }
        
        throw $e; // Re-throw to allow job retry if needed
    } finally {
        // Clean up WhatsApp details file if it exists
        if (Storage::disk('local')->exists('whatsapp-details.json')) {
            Storage::delete('whatsapp-details.json');
        }
    }
}

private function triggerWhatsAppNotification()
{
    try {
        Log::info('Triggering WhatsApp Dusk test via exec...');

        $command = 'php artisan dusk --filter=WhatsAppTest >> storage/logs/dusk.log 2>&1 &';
        $output = [];
        $returnCode = 0;
        
        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            Log::info('WhatsApp Dusk test launched successfully.');
        } else {
            Log::error("WhatsApp Dusk test failed to start. Return code: $returnCode");
            Log::debug('Exec output: ' . implode("\n", $output));
        }
    } catch (\Exception $e) {
        Log::error('Exception while triggering WhatsApp Dusk: ' . $e->getMessage());
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

        $start=microtime(true);
        
        $response=Http::get("https://api.telegram.org/bot{$botToken}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ]);

        $duration=microtime(true)-$start;
        Log::info('Telegram API Response Time: ' . round($duration * 1000, 2) . ' ms');
        Log::info('Telegram API Response: ', $response->json());
    }

    public function SendPwaNotification($userId, $notificationToken = null)
    {
        try {
            $subscriptions = PushSubscription::where('user_id', $userId)->get();

            if ($subscriptions->isEmpty()) {
                Log::info("No PWA subscriptions found for user {$userId}");
                return;
            }

            $webPush = new WebPush([
                'VAPID' => [
                    'subject' => 'mailto:'.env('MAIL_FROM_ADDRESS', 'notifications@example.com'),
                    'publicKey' => env('VAPID_PUBLIC_KEY'),
                    'privateKey' => env('VAPID_PRIVATE_KEY'),
                ]
            ]);

            $payload = json_encode([
                'title' => 'Monitor Alert',
                'body' => 'Your Monitor is still down',
                'icon' => '/logo.png',
                'url' => "/dashboard/" . $notificationToken // Add the URL here
            ]);

            foreach ($subscriptions as $subscription) {
                $pushSubscription = new Subscription(
                    $subscription->endpoint,
                    $subscription->p256dh,
                    $subscription->auth,
                    'aes128gcm'
                );

                $webPush->queueNotification($pushSubscription, $payload);
            }

            $results = $webPush->flush();
            foreach ($results as $report) {
                if (!$report->isSuccess()) {
                    Log::error("PWA notification failed for user {$userId}: " . $report->getReason());
                }
            }

        } catch (\Exception $e) {
            Log::error("PWA notification error for user {$userId}: " . $e->getMessage());
        }
    }
    private function checkHttp(Monitors $monitor)
    {
        $status = 'down';
        $statusCode = 0;
        $responseTime = 0;
        Log::info("Checking HTTP Monitor: {$monitor->id} ({$monitor->url})");

        for ($attempt = 0; $attempt < $monitor->retries; $attempt++) {
            try {
                //Record response time.
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
            Log::error('Error occurreed: '.$e);
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
}

    public function sendFollowUpEmail()
    {
        try {
            $fiveMinutesAgo = Carbon::now()->subMinutes(5);

            $notifications = Notification::where('created_at', '<=', $fiveMinutesAgo)
                ->with('monitor.user')
                ->get();

            foreach ($notifications as $notification) {
                // Send follow-up email only once
                if(!$notification->follow_up_sent){
                    Mail::to($notification->monitor->email)
                        ->send(new FollowUpMail($notification->monitor));
                    $notification->follow_up_sent = true;
                    $notification->save();
                    Log::info("Follow-up email sent to: {$notification->monitor->email}"); 
                }
                
                // Handle PWA notification separately
                switch($notification->status){
                    case 'unread':
                        // Only send PWA notification if it's been at least 5 minutes since last notification
                        $lastNotifiedAt = $notification->last_notified_at ? Carbon::parse($notification->last_notified_at) : null;
                        
                        if (!$lastNotifiedAt || $lastNotifiedAt->diffInMinutes(Carbon::now()) >= 5) {
                            $this->SendPwaNotification($notification->monitor->user_id, $notification->token);
                            $notification->last_notified_at = Carbon::now();
                            $notification->touch();
                            Log::info('PWA Notification Triggered');
                        }
                        break;
                    default:
                        $notification->delete();
                }    
            }

        } catch (\Exception $e) {
            Log::error("sendFollowUpEmail failed: " . $e->getMessage());
        }
    }
    
    public function handle(): void
    {
        try {
            $monitors = Monitors::where('paused', false)
            ->where(function ($query) {
                $query->whereRaw(
                    'DATE_FORMAT(NOW(), "%Y-%m-%d %H:%i") >= DATE_FORMAT(DATE_ADD(last_checked_at, INTERVAL `interval` MINUTE), "%Y-%m-%d %H:%i")'
                )->orWhereNull('last_checked_at');
            })
            ->get();

            Log::info('number of monitors:'.$monitors->count());

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
                $this->sendFollowUpEmail();
            }
        } catch (\Exception $e) {
            Log::error("MonitorJob failed: " . $e->getMessage());
        }
    }
}
