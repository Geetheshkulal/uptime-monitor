<?php

namespace App\Http\Controllers;

use App\Models\Monitors;
use App\Models\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class HttpMonitoringController extends Controller
{
    // Store a new HTTP monitor entry
    public function store(Request $request)
    {
        Log::info('HTTP Monitor Request: ', $request->all());
        $request->validate([
            'name' => 'required|string',
            'url' => 'required|url',
            'email' => 'required|email',
            'retries' => 'required|integer|min:1',
            'interval' => 'required|integer',
            'telegram_id' => 'nullable|string',
            'telegram_bot_token' => 'nullable|string',
        ]);

        // Create a new HTTP monitor entry
        $monitor = Monitors::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'url' => $request->url,
            'type' => 'http',
            'retries' => $request->retries,
            'interval' => $request->interval,
            'email' => $request->email,
            'telegram_id' => $request->telegram_id,
            'telegram_bot_token' => $request->telegram_bot_token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        activity()
        ->performedOn($monitor)
        ->causedBy(auth()->user())
        ->event('created')
        ->withProperties([
            'user_name' => auth()->user()->name,
            'monitor_name' => $monitor->name,
            'monitor_url' => $monitor->url,
            'monitor_type' => $monitor->type
        ])
        ->log("Created {$monitor->type} monitor");

        Log::info('HTTP Monitor Created: ', $monitor->toArray());

        return redirect()->route('monitoring.dashboard')->with('success', 'HTTP Monitor added successfully');
    }

    // Method to check HTTP status of the monitor's URL
    
    // private function checkHttpStatus(Monitor $monitor)
    // {
    //     $attempts = 0;
    //     $status = 'down';
    //     $statusCode = 0;
    //     $responseTime = 0;
    
    //     while ($attempts < $monitor->retries) {
    //         try {
    //             $startTime = microtime(true);
                
    //             $response = Http::timeout(10)->get($monitor->url);
                
    //             $endTime = microtime(true);
    //             $responseTime = round(($endTime - $startTime) * 1000, 2);
    //             $statusCode = $response->status();
    //             $status = $response->successful() ? 'up' : 'down';
    
    //             Log::info("HTTP Monitoring Attempt #$attempts: ", [
    //                 'monitor_id' => $monitor->id,
    //                 'status' => $status,
    //                 'status_code' => $statusCode,
    //                 'response_time' => $responseTime
    //             ]);
    
    //             if ($response->successful()) {
    //                 break;
    //             }
    //         } catch (\Exception $e) {
    //             Log::error("HTTP Monitoring Error: " . $e->getMessage());
    //             $status = 'down';
    //             $statusCode = 0;
    //             $responseTime = 0;
    //         }
    
    //         $attempts++;
    //         sleep($monitor->interval);
    //     }
    
    //     // Store the response in http_responses table
    //     HttpResponse::create([
    //         'monitor_id' => $monitor->id,
    //         'status' => $status,
    //         'status_code' => $statusCode,
    //         'response_time' => $responseTime,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);
    // }
    
}