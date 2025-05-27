<?php

namespace App\Http\Controllers;


use App\Models\Monitors;
use App\Models\PortResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

//Controller for port monitor.
class PortMonitorController extends Controller
{
    //Store port monitors.
    public function PortStore(Request $request)
    {
        
        $user = auth()->user();
        $user = ($user->hasRole('subuser'))?$user->parentUser:auth()->user();

       Log::info('Request data:', $request->all());
        $request->validate([
            'url' => [
                'required',
                'url',
                Rule::unique('monitors')->where(function ($query) use ($user, $request) {
                    return $query->where('user_id', $user->id)
                                ->where('type', 'port');
                }),
            ],
            'name' => 'required|string',
            'port' => 'required|integer|min:1|max:65535',
            'retries' => 'required|integer|min:1',
            'interval' => 'required|integer|min:1',
            'email' => 'required|string',
            'telegram_id' => 'nullable|string',
            'telegram_bot_token' => 'nullable|string',
        ]);

        //Create Monitor
        $monitor=Monitors::create([
            'name'=>$request->name,
            'status'=>null,
            'user_id'=>$user->id,
            'url'=>$request->url,
            'type'=>'port',
            'port'=>$request->port,
            'retries' => $request->retries,
            'interval' => $request->interval,
            'email'=> $request->email,
            'telegram_id' => $request->telegram_id,
            'telegram_bot_token' => $request->telegram_bot_token,
        ]);

        Log::info('Monitor created:', $monitor->toArray());

        //Log activity
        activity()
        ->causedBy(auth()->user())
        ->performedOn($monitor)
        ->inLog('port_monitoring')
        ->event('created')
        ->withProperties([
            'email'=> $request->email,
            'url' => $request->url,
            'type' => $request->port
        ])
        ->log('port Monitor created');

        return redirect()->route('monitoring.dashboard')->with('success', ucfirst($request->type) . ' monitoring added successfully!');
    }

    
    //Function to check if a port is up
    public function checkPort($host, $port)
    {
        $start = microtime(true);
        $connection = @fsockopen($host, $port, $errno, $errstr, 5);
        $end = microtime(true);

        if ($connection) {
            fclose($connection);
            return [
                'status' => 'up',
                'response_time' => round(($end - $start) * 1000) // Convert to ms
            ];
        } else {
            return [
                'status' => 'down',
                'response_time' => 0
            ];
        }
    }


    //Function to fetch and check port monitors
    public function monitor()
    {

        $monitors = Monitors::where('type', 'port')
                           ->where('user_id', auth()->id())
                           ->get();
    
        foreach ($monitors as $monitor) {
            $result = $this->checkPort($monitor->url, $monitor->port);
    
            //create port response
            PortResponse::create([
                'monitor_id' => $monitor->id,
                'response_time' => $result['status'] === 'down' ? 0 : $result['response_time'],
                'status' => $result['status']
         ]);
        }
    }
    
}
