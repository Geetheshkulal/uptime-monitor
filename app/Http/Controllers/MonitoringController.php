<?php

namespace App\Http\Controllers;


use App\Models\DnsResponse;
use App\Models\HttpResponse;
use App\Models\Monitors;
use App\Models\PingResponse;
use App\Models\PortResponse;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
  private function getLatestResponsesByType(Monitors $monitor)
  {
      $query = null;

      // Check monitor type and get data from the appropriate table
      switch ($monitor->type) {
          case 'port':
              $query = PortResponse::where('monitor_id', $monitor->id);
              break;
          case 'dns':
              $query = DnsResponse::where('monitor_id', $monitor->id);
              break;
          case 'ping':
              $query = PingResponse::where('monitor_id', $monitor->id);
              break;
          case 'http':
              $query = HttpResponse::where('monitor_id', $monitor->id);
              break;
          default:
              return collect(); // Return an empty collection if type is unknown
      }

      // Get the latest 10 rows (newest first) and then reverse so oldest is at [0]
      return $query->orderByDesc('created_at')->limit(10)->get()->reverse()->values();
  }

public function MonitoringDashboard()
{
    
    // Get all monitors for the logged-in user
    $monitors = Monitors::where('user_id', auth()->id())->get();
    $upCount = Monitors::where('user_id',auth()->id())->where('status', 'up')->count();
    $downCount = Monitors::where('user_id',auth()->id())->where('status', 'down')->count();
    $totalMonitors = $monitors->count();


    // Attach latest responses for each monitor
    foreach ($monitors as $monitor) {
        $monitor->latestResponses = $this->getLatestResponsesByType($monitor);
    }

    return view('pages.MonitoringDashboard', compact('monitors', 'totalMonitors','upCount','downCount'));
}


public function MonitoringDashboardUpdate(Request $request)
{
    // Get the draw counter (required by DataTables)
    $draw = $request->input('draw');

    // Get the start and length parameters (for pagination)
    $start = $request->input('start');
    $length = $request->input('length');

    // Get the search term (if any)
    $searchValue = $request->input('search.value');

    // Base query for monitors
    $query = Monitors::where('user_id', auth()->id());

    // Apply search filter
    if (!empty($searchValue)) {
        $query->where(function($q) use ($searchValue) {
            $q->where('name', 'like', '%' . $searchValue . '%')
              ->orWhere('url', 'like', '%' . $searchValue . '%')
              ->orWhere('type', 'like', '%' . $searchValue . '%')
              ->orWhere('status', 'like', '%' . $searchValue . '%');
        });
    }

    // Get the total number of records (without filtering)
    $totalMonitors = $query->count();

    $length = $length ?? 10; // Default limit if null
    $start = $start ?? 0; // Default offset if null

$monitors = $query->limit($length)
                  ->offset($start)
                  ->get();

    // Attach latest responses for each monitor
    foreach ($monitors as $monitor) {
        $monitor->latestResponses = $this->getLatestResponsesByType($monitor);
    }

    // Get the up and down counts
    $upCount = Monitors::where('user_id', auth()->id())->where('status', 'up')->count();
    $downCount = Monitors::where('user_id', auth()->id())->where('status', 'down')->count();

    // Prepare the response
    return response()->json([
        'draw' => $draw, // Required by DataTables
        'recordsTotal' => $totalMonitors, // Total number of records (without filtering)
        'recordsFiltered' => $totalMonitors, // Total number of records after filtering
        'data' => $monitors, // The actual data for the current page
        'upCount' => $upCount, // Additional data for your cards
        'downCount' => $downCount, // Additional data for your cards
        'totalMonitors' => $totalMonitors // Additional data for your cards
    ]);
}

    
 public function AddMonitoring()
 {

    return view('pages.Add_Monitor');
  }
   public function MonitoringDisplay($id, $type)
   {

    $details=Monitors::findOrFail($id);

    switch($type) {

        case 'dns':
            $ChartResponses = DnsResponse::where('monitor_id', $id)
                ->orderBy('created_at', 'asc')
                ->get(['created_at', 'response_time']);
                break;
            
        case 'port':
            $ChartResponses = PortResponse::where('monitor_id', $id)
                ->orderBy('created_at', 'asc')
                ->get(['created_at', 'response_time']);
                break;
        case 'ping':
                  $ChartResponses = PingResponse::where('monitor_id', $id)
                  ->orderBy('created_at', 'asc')
                  ->get(['created_at', 'response_time']);
                  break;

        case 'http':
                $ChartResponses = HttpResponse::where('monitor_id', $id)
                ->orderBy('created_at', 'asc')
                ->get(['created_at', 'response_time']);
                break;

        default:
                $ChartResponses = collect();
    }
    
     return view('pages.DisplayMonitoring', compact('details','ChartResponses','type'));
   }


   public function MonitoringChartUpdate($id, $type)
   {

    $details=Monitors::findOrFail($id);

    switch($type) {

        case 'dns':
            $ChartResponses = DnsResponse::where('monitor_id', $id)
                ->orderBy('created_at', 'asc')
                ->get(['created_at', 'response_time']);
                break;
            
        case 'port':
            $ChartResponses = PortResponse::where('monitor_id', $id)
                ->orderBy('created_at', 'asc')
                ->get(['created_at', 'response_time']);
                break;
        case 'ping':
                  $ChartResponses = PingResponse::where('monitor_id', $id)
                  ->orderBy('created_at', 'asc')
                  ->get(['created_at', 'response_time']);
                  break;

        case 'http':
                $ChartResponses = HttpResponse::where('monitor_id', $id)
                ->orderBy('created_at', 'asc')
                ->get(['created_at', 'response_time']);
                break;

        default:
                $ChartResponses = collect();
    }
    
     return response()->json($ChartResponses);
   }

   public function MonitorDelete($id)
   {

    $DeleteMonitor=Monitors::findOrFail($id);

    if(!$DeleteMonitor)
    {
        return redirect()->back()->with('error','Monitoring data not found.');
    }

    $DeleteMonitor->delete();

    return redirect()->route('monitoring.dashboard')->with('success', 'Monitoring data deleted successfully.');

   }

   public function MonitorEdit(Request $request, $id)
   {
    $request->validate([
        'name'=>'required|string|max:255',
        'url'=>'required|url',
        'retries' => 'required|integer|min:1',
        'interval' => 'required|integer|min:1',
        'email' => 'required|email',
        'port' => 'nullable|integer', 
        'dns_resource_type' => 'nullable|string', 
        'telegram_id' => 'nullable|string',
        'telegram_bot_token' => 'nullable|string',
    ]);

    $EditMonitoring=Monitors::findOrFail($id);

    $EditMonitoring->update($request->all());

    return redirect()->route('monitoring.dashboard')->with('success', 'Monitoring details updated successfully.');
   }
}
