<?php

namespace App\Http\Controllers;

use App\Models\Monitors;
use App\Models\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\Rule;

//Controller for HttP
class HttpMonitoringController extends Controller
{
    // Store a new HTTP monitor entry
    public function store(Request $request)
    {
        $user = auth()->user();
        $user = ($user->hasRole('subuser'))?$user->parentUser:auth()->user();

        Log::info('HTTP Monitor Request: ', $request->all());
        $request->validate([
            'name' => 'required|string',
            'url' => [
                'required',
                'url',
                Rule::unique('monitors')->where(function ($query) use ($user, $request) {
                    return $query->where('user_id', $user->id)
                                ->where('type', 'http');
                }),
            ],
            'email' => 'required|email',
            'retries' => 'required|integer|min:1',
            'interval' => 'required|integer|min:1',
            'telegram_id' => 'nullable|string',
            'telegram_bot_token' => 'nullable|string',
        ]);

        // Create a new HTTP monitor entry
        $monitor = Monitors::create([
            'user_id' => $user->id,
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

        //Record the activity
        activity()
        ->performedOn($monitor)
        ->causedBy(auth()->user())
        ->inLog('http_monitoring') 
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

}