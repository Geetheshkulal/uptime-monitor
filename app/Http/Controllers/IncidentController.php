<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Monitor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function incidents()
    {
        // Get the logged-in user's ID
        $userId = Auth::id();

        // Get the monitor IDs associated with the logged-in user
        $userMonitors = Monitor::where('user_id', $userId)->pluck('id');  // Fetch monitor IDs for the logged-in user

        // Fetch incidents that belong to the logged-in user's monitors only
        $incidents = Incident::with('monitor') // Load incidents with associated monitors
            ->whereIn('monitor_id', $userMonitors) // Filter incidents by the monitor IDs of the logged-in user
            ->get();

        return view('pages.incidents', compact('incidents'));
    }
}
