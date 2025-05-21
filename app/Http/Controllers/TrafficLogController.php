<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrafficLog;
use App\Models\BlockedIP;


class TrafficLogController extends Controller
{
  public function TrafficLogView(Request $request)
{
    $query = TrafficLog::query();

    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function ($q) use ($search) {
            $q->where('ip', 'like', "%{$search}%")
              ->orWhere('browser', 'like', "%{$search}%")
              ->orWhere('platform', 'like', "%{$search}%")
              ->orWhere('url', 'like', "%{$search}%")
              ->orWhere('method', 'like', "%{$search}%");
        });
    }

    $blocked_ips = BlockedIP::all()->pluck('ip_address')->toArray();

    $trafficLogs = $query->latest()->paginate(10);

    // Keep the search value in pagination links
    $trafficLogs->appends($request->only('search'));

    return view('pages.admin.ViewTrafficLog', compact('trafficLogs','blocked_ips'));
}

}
