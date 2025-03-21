<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\PortResponse;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class PortMonitorController extends Controller
{
    public function PortStore(Request $request)
    {
        
       Log::info('Request data:', $request->all());
        $request->validate([
            'url' => 'required|string',
            'name' => 'required|string',
            'port' => 'required|integer',
            'retries' => 'required|integer|min:1',
            'interval' => 'required|integer|min:1',
            'email' => 'required|string',
        ]);

        $monitor=Monitor::create([
            'name'=>$request->name,
            'status'=>'Null',
            'user_id'=>auth()->id(),
            'url'=>$request->url,
            'type'=>'port',
            'port'=>$request->port,
            'retries' => $request->retries,
            'interval' => $request->interval,
            'email'=> $request->email
        ]);

        Log::info('Monitor created:', $monitor->toArray());

        return redirect()->route('monitoring.dashboard')->with('success', 'Port monitoring added successfully!');
    }

    public function checkPort($host, $port)
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


    public function monitor()
    {

        $monitors = Monitor::where('type', 'port')
                           ->where('user_id', auth()->id())
                           ->get();
    
        foreach ($monitors as $monitor) {
            $result = $this->checkPort($monitor->url, $monitor->port);
    
            PortResponse::create([
                'monitor_id' => $monitor->id,
                'response_time' => $result['status'] === 'Closed' ? 0 : $result['response_time'],
                'status' => $result['status']
         ]);
        }
    }
    
}
