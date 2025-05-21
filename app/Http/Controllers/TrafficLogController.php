<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrafficLog;

class TrafficLogController extends Controller
{
    public function TrafficLogView()
    {
        $trafficLogs = TrafficLog::latest()->paginate(10);
        return view('pages.admin.ViewTrafficLog', compact('trafficLogs'));

    }
}
