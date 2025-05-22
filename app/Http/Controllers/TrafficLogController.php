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

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $from = $request->input('from_date') . ' 00:00:00';
        $to = $request->input('to_date') . ' 23:59:59';

        $query->whereBetween('created_at', [$from, $to]);
    }

    $blocked_ips = BlockedIP::all()->pluck('ip_address')->toArray();

    $trafficLogs = $query->latest()->paginate(10);

    // Preserve all filters in pagination
    $trafficLogs->appends($request->only('search', 'from_date', 'to_date'));

    return view('pages.admin.ViewTrafficLog', compact('trafficLogs', 'blocked_ips'));
}

}
