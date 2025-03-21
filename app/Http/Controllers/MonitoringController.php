<?php

namespace App\Http\Controllers;


use App\Models\DnsResponse;
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
          default:
              return collect(); // Return an empty collection if type is unknown
      }

      // Get the latest 10 rows (newest first) and then reverse so oldest is at [0]
      return $query->orderByDesc('created_at')->limit(10)->get()->reverse()->values();
  }

public function MonitoringDashboard()
{
    $totalMonitors = Monitors::count();
    
    // Get all monitors for the logged-in user
    $monitors = Monitors::where('user_id', auth()->id())->get();
    $upCount = Monitors::where('status', 'up')->count();
    $downCount = Monitors::where('status', 'down')->count();


    // Attach latest responses for each monitor
    foreach ($monitors as $monitor) {
        $monitor->latestResponses = $this->getLatestResponsesByType($monitor);
    }

    return view('pages.MonitoringDashboard', compact('monitors', 'totalMonitors','upCount','downCount'));
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

            default:
                $ChartResponses = collect();
    }
    
     return view('pages.DisplayMonitoring', compact('details','ChartResponses','type'));
   }

}
