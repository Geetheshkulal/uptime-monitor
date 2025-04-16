<?php

namespace App\Http\Controllers;

use App\Models\Monitors;
use Illuminate\Http\Request;


class PingMonitoringController extends Controller
{
    public function store(Request $request)
    {
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
            'user_id' => auth()->id(),
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