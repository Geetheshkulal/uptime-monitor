<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Monitor;
use App\Models\PortResponse;

class PortJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    private function checkPort($host, $port, int $monitorId, int $retries = 3)
    
    {
    $attempt = 0;
    $status = 'Closed';
    $responseTime = 0;

    $startTime = microtime(true);

    while ($attempt < $retries) {
        $connection = @fsockopen($host, $port, $errno, $errstr, 5);
        if ($connection) {
            fclose($connection);
            $status = 'Open';
            $responseTime = round((microtime(true) - $startTime) * 1000, 2); // Convert to ms
            break;
        }

        $attempt++;
        sleep(min(pow(2, $attempt), 5)); // Exponential backoff with a max wait of 5s
    }

    // Store response in the port_responses table
    PortResponse::create([
        'monitor_id' => $monitorId,
        'status' => $status,
        'response_time' => $status === 'Open' ? $responseTime : 0
    ]);

    // Update last_checked_at and status in the monitors table
    Monitor::where('id', $monitorId)->update([
        'last_checked_at' => now(),
        'status' => $status
    ]);

    return $status;
}
    public function handle(): void
    {
    $monitors = Monitor::whereRaw('? >= DATE_ADD(last_checked_at, INTERVAL `interval` MINUTE)', [now()])
                ->orWhereNull('last_checked_at')
                ->get();

    foreach ($monitors as $monitor) {
        switch ($monitor->type) {
            case 'port':
                $this->checkPort($monitor->url, $monitor->port, $monitor->id, $monitor->retries);
                break;
        }
    }
}
}
