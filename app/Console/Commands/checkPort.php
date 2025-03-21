<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Monitor;
use App\Models\PortResponse;
use Illuminate\Support\Facades\Log;

class checkPort extends Command
{
    protected $signature = 'app:check-port';
    protected $description = 'Check port status for each monitor';

    public function handle()
    {
        $monitors = Monitor::where('type', 'port')->get();

        foreach ($monitors as $monitor) {
            $retries = $monitor->retries;
            $interval = $monitor->interval;

            Log::info("Starting monitoring for {$monitor->url}:{$monitor->port} with {$retries} retries at {$interval} second interval");

            for ($attempt = 0; $attempt < $retries; $attempt++) {
                $result = $this->checkPort($monitor->url, $monitor->port);

                // Insert result into port_response table
                PortResponse::create([
                    'monitor_id' => $monitor->id,
                    'response_time' => $result['response_time'],
                    'status' => $result['status'],
                ]);

                Log::info("Attempt " . ($attempt + 1) . " for {$monitor->url}:{$monitor->port} - Status: {$result['status']}, Response Time: {$result['response_time']}ms");

                // Stop if the status is 'Open'
                if ($result['status'] === 'Open') {
                    Log::info("Port {$monitor->port} is open. Stopping further checks.");
                    break;
                }

                // Sleep for the defined interval (if not last attempt)
                if ($attempt < $retries - 1) {
                    sleep($interval);
                }
            }
        }

        Log::info('Port monitoring completed.');
    }

    private function checkPort($host, $port)
    {
        $start = microtime(true);
        $connection = @fsockopen($host, $port, $errno, $errstr, 5);
        $end = microtime(true);

        if ($connection) {
            fclose($connection);
            return [
                'status' => 'Open',
                'response_time' => round(($end - $start) * 1000) // Convert to ms
            ];
        } else {
            return [
                'status' => 'Closed',
                'response_time' => 0
            ];
        }
    }
}
