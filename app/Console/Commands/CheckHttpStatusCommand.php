<?php
namespace App\Console\Commands; 

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Monitor;
use App\Models\HttpResponse;

class CheckHttpStatusCommand extends Command
{
    protected $signature = 'monitor:check-http {user_id}';
    protected $description = 'Check the HTTP status of all monitors for a specific user and store responses.';

    public function handle()
    {
        $userId = $this->argument('user_id');
        Log::info("Running HTTP Monitoring for User ID: {$userId}");

        $monitors = Monitor::where('type', 'http')
            ->where('user_id', $userId)
            ->get();

            

        if ($monitors->isEmpty()) {
            Log::info("No monitors found for User ID: {$userId}");
            return;
        }


        // foreach ($monitors as $monitor) {
        //     $this->checkMonitor($monitor->url, $monitor->id); 
        // }


        foreach ($monitors as $monitor) {
            switch ($monitor->type) {
                case 'http':
                    Log::info("Checking HTTP Monitor ID: {$monitor->id}");
                    $this->checkMonitor($monitor->url, $monitor->id, $monitor->retries);
                    break;
            }
        }
        

        Log::info('HTTP Monitoring Command Execution Completed.');
    }


    //     try {
    //         $startTime = microtime(true);
    //         $response = Http::timeout(10)->get($monitor->url);
    //         $endTime = microtime(true);

    //         $status = $response->successful() ? 'UP' : 'DOWN';
    //         $statusCode = $response->status();
    //         $responseTime = round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds

    //         // Store response in http_response table
    //         HttpResponse::create([
    //             'monitor_id' => $monitor->id,
    //             'status' => $status,
    //             'status_code' => $statusCode,
    //             'response_time' => $responseTime,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         Monitor::where('id', $monitor->id)->update([
    //             'last_checked_at' => now(),
    //             'status' => $status,
    //         ]);

    //         Log::info("Monitor ID {$monitor->id} checked: Status {$status}, Code {$statusCode}, Response Time {$responseTime}ms");

    //     } catch (\Exception $e) {
    //         Log::error("Error checking Monitor ID {$monitor->id}: " . $e->getMessage());

    //         // Store failure response
    //         HttpResponse::create([
    //             'monitor_id' => $monitor->id,
    //             'status' => 'DOWN',
    //             'status_code' => 400,
    //             'response_time' => 0,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);


    //         Monitor::where('id', $monitor->id)->update([
    //             'last_checked_at' => now(),
    //             'status' => 'DOWN',
    //         ]);
    //     }
    // }




//     private function checkMonitor($monitor)
// {
//     try {
//         $startTime = microtime(true);
//         $response = Http::timeout(10)->get($monitor->url);
//         $endTime = microtime(true);

//         $status = $response->successful() ? 'UP' : 'DOWN';
//         $statusCode = $response->status(); 
//         $responseTime = round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds

       
//     } catch (\Illuminate\Http\Client\RequestException $e) {
//         // Capture the actual HTTP status code from the exception, if available
//         $status = 'DOWN';
//         $statusCode = $e->response ? $e->response->status() : '0'; // If no response, set it to 0
//         $responseTime = 0;


//         if ($statusCode === 403) {
//             $status = $monitor->expected_403 ? 'UP' : 'DOWN';
//         } else {
//             $status = $response->successful() ? 'UP' : 'DOWN';
//         }
        
//         Log::error("Error checking Monitor ID {$monitor->id}: " . $e->getMessage());
//         Log::error("Status Code: {$statusCode}");

//     } catch (\Exception $e) {
//         //  Handle other exceptions (e.g., connection timeout, DNS issues)
//         $status = 'DOWN';
//         $statusCode = 0; // Unknown error
//         $responseTime = 0;

//         Log::error("Error checking Monitor ID {$monitor->id}: " . $e->getMessage());
//     }

//     // Store response in http_response table
//     HttpResponse::create([
//         'monitor_id' => $monitor->id,
//         'status' => $status,
//         'status_code' => $statusCode,
//         'response_time' => $responseTime,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ]);

//     // Update the monitor table with the latest status
//     Monitor::where('id', $monitor->id)->update([
//         'last_checked_at' => now(),
//         'status' => $status,
//     ]);

//     Log::info("Monitor ID {$monitor->id} checked: Status {$status}, Code {$statusCode}, Response Time {$responseTime}ms");
// }


// private function checkMonitor($monitor)
// {
//     // Define variables before the try block
//     $status = 'DOWN';
//     $statusCode = 0;
//     $responseTime = 0;

//     try {
//         $startTime = microtime(true);
//         $response = Http::timeout(10)->get($monitor->url);
//         $endTime = microtime(true);

//         $statusCode = $response->status(); 
//         $responseTime = round(($endTime - $startTime) * 1000, 2); // Convert to milliseconds

//         // Check if status code is 403, then consider it UP
//         if ($statusCode === 403) {
//             $status = 'UP';
//         } else {
//             $status = $response->successful() ? 'UP' : 'DOWN';
//         }

//     } catch (\Illuminate\Http\Client\RequestException $e) {
//         $statusCode = $e->response ? $e->response->status() : 0;

//         if ($statusCode === 403) {
//             $status = 'UP';
//         }

//         Log::error("Error checking Monitor ID {$monitor->id}: " . $e->getMessage());
//         Log::error("Status Code: {$statusCode}");

//     } catch (\Exception $e) 
//     {
//         $statusCode = 0; 
    
//         // Handle timeout errors
//         if (strpos($e->getMessage(), 'timed out') !== false) {
//             $statusCode = 408; // Request Timeout
//         }
        
//         // Handle SSL certificate errors
//         if (strpos($e->getMessage(), 'SSL') !== false) {
//             $statusCode = 495; // Custom SSL failure code
//         }

//         Log::error("Error checking Monitor ID {$monitor->id}: " . $e->getMessage());
//     }

//     // Store response in http_response table
//     HttpResponse::create([
//         'monitor_id' => $monitor->id,
//         'status' => $status,
//         'status_code' => $statusCode,
//         'response_time' => $responseTime,
//         'created_at' => now(),
//         'updated_at' => now(),
//     ]);

//     // Update the monitor table with the latest status
//     Monitor::where('id', $monitor->id)->update([
//         'last_checked_at' => now(),
//         'status' => $status,
//     ]);

//     Log::info("Monitor ID {$monitor->id} checked: Status {$status}, Code {$statusCode}, Response Time {$responseTime}ms");
// }




private function checkMonitor(string $url, int $monitorId, int $retries = 3)
{
    $status = 'DOWN';
    $statusCode = 0;
    $responseTime = 0;

    for ($attempt = 0; $attempt < $retries; $attempt++) {
        try {
            $startTime = microtime(true);
            $response = Http::timeout(10)->get($url);
            $endTime = microtime(true);

            $statusCode = $response->status();
            $responseTime = round(($endTime - $startTime) * 1000, 2);

            if ($statusCode === 403) {
                $status = 'UP';
            } else {
                $status = $response->successful() ? 'UP' : 'DOWN';
            }

            break; // Exit retry loop on success

        } catch (\Illuminate\Http\Client\RequestException $e) {
            $statusCode = $e->response ? $e->response->status() : 0;

            if ($statusCode === 403) {
                $status = 'UP';
            }

            Log::error("HTTP Monitor Error (Monitor ID: {$monitorId}): " . $e->getMessage());

        } catch (\Exception $e) {
            $statusCode = 0;

            if (strpos($e->getMessage(), 'timed out') !== false) {
                $statusCode = 408; // Request Timeout
            }

            if (strpos($e->getMessage(), 'SSL') !== false) {
                $statusCode = 495; // SSL Failure
            }

            Log::error("General HTTP Monitor Error (Monitor ID: {$monitorId}): " . $e->getMessage());
        }

        sleep(min(pow(2, $attempt), 5)); // Exponential backoff (max 5s)
    }

    // Store response in http_response table
    HttpResponse::create([
        'monitor_id' => $monitorId,
        'status' => $status,
        'status_code' => $statusCode,
        'response_time' => $responseTime,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Update monitor status
    Monitor::where('id', $monitorId)->update([
        'last_checked_at' => now(),
        'status' => $status,
    ]);
}
}