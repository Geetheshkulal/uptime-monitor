<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Monitors;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function incidents()
    {
        // Get the logged-in user's ID
        $userId = Auth::id();

        // Get the monitor IDs associated with the logged-in user
        $userMonitors = Monitors::where('user_id', $userId)->pluck('id');  // Fetch monitor IDs for the logged-in user

        // Fetch incidents that belong to the logged-in user's monitors only
        $incidents = Incident::with('monitor') // Load incidents with associated monitors
            ->whereIn('monitor_id', $userMonitors) // Filter incidents by the monitor IDs of the logged-in user
            ->get();
        
        $tempMonitor= Monitors::where('user_id', $userId)->first();

            activity()
            ->performedOn($tempMonitor)
            ->causedBy(auth()->user())
            ->event('visited')
            ->withProperties([
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'page' => 'Incidents Page'
            ])
            ->log('Visited the incidents page');

        return view('pages.incidents', compact('incidents'));
    }
    public function fetchIncidents()
{
    $userId = Auth::id();

    // Get the monitor IDs associated with the logged-in user
    $userMonitors = Monitors::where('user_id', $userId)->pluck('id');

    // Fetch incidents with related monitor data
    $incidents = Incident::with('monitor')
        ->whereIn('monitor_id', $userMonitors)
        ->get();

    return response()->json(['incidents' => $incidents]);
}

}