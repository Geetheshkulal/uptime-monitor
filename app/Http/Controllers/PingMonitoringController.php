<?php

namespace App\Http\Controllers;

use App\Models\Monitors;
use Illuminate\Http\Request;


//Controller for Ping Monitors.
class PingMonitoringController extends Controller
{
    //store the ping monitor.
    public function store(Request $request)
    {
        $user = auth()->user();
        $user = ($user->hasRole('subuser'))?$user->parentUser:auth()->user();

        $request->validate([
            'name' => 'required|string',
            'url' => 'required',
            'email' => 'required|email',
            'retries' => 'required|integer|min:1',
            'interval' => 'required|integer|min:1',
            'telegram_id' => 'nullable|string',
            'telegram_bot_token' => 'nullable|string',
         
        ]);

        // Save the monitor data to the database
        $monitor = Monitors::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'url' => $request->url,
            'type' => 'ping',
            'port' => null,
            'retries' => $request->retries,
            'dns_resource_type'=>null,
            'interval' => $request->interval,
            'email' => $request->email,
            'telegram_id' => $request->telegram_id,
            'telegram_bot_token' => $request->telegram_bot_token,
            'status' => 'down', 
            // Default to DOWN until the cron job updates it
        ]);
        
        //Log the activity
        activity()
        ->causedBy(auth()->user())
        ->performedOn($monitor)
        ->inLog('ping_monitoring')
        ->event('created')
        ->withProperties([
            'email'=> $request->email,
            'url' => $request->url,
            'type' => $request->type
        ])
        ->log('ping Monitor created');

        return redirect()->route('monitoring.dashboard')->with('success', ucfirst($request->type) . ' monitoring added successfully!');


    }
}