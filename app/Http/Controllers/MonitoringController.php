<?php

namespace App\Http\Controllers;


use App\Models\DnsResponse;
use App\Models\HttpResponse;
use App\Models\Monitors;
use App\Models\PingResponse;
use App\Models\PortResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MonitoringController extends Controller
{
  private function getLatestResponsesByType(Monitors $monitor)
  {
      $query = null;

      // Check monitor type and get data from the appropriate tablew
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
      $user = auth()->user();
      
      // Get all monitors for the user
      $monitors = Monitors::where('user_id', $user->id)->get();
  
      // Check if user has more than 5 monitors
      $hasMoreMonitors = $monitors->count() > 5;
  
      // If user is free, limit to 5 monitors
      if ($user->status === 'free') {
          $monitors = $monitors->take(5);
      }
  
      $upCount = $monitors->where('status', 'up')->count();
      $downCount = $monitors->where('status', 'down')->count();
      $totalMonitors = $monitors->count();
      $pausedCount = Monitors::where('user_id',auth()->id())->where('paused', 1)->count();
  
      // Attach latest responses
      foreach ($monitors as $monitor) {
          $monitor->latestResponses = $this->getLatestResponsesByType($monitor);
      }
  
      return view('pages.MonitoringDashboard', compact('monitors', 'totalMonitors', 'upCount', 'downCount', 'hasMoreMonitors','pausedCount'));
  }
  


  public function MonitoringDashboardUpdate(Request $request)
  {
      $user = auth()->user();
      $draw = $request->input('draw');
      $start = $request->input('start');
      $length = $request->input('length');
      $searchValue = $request->input('search.value');
  
      // Base query
      $query = Monitors::where('user_id', $user->id);
  
      if (!empty($searchValue)) {
          $query->where(function ($q) use ($searchValue) {
              $q->where('name', 'like', "%{$searchValue}%")
                ->orWhere('url', 'like', "%{$searchValue}%")
                ->orWhere('type', 'like', "%{$searchValue}%")
                ->orWhere('status', 'like', "%{$searchValue}%");
          });
      }
  
      // Get monitors
      $monitors = $query->get();
  
      // Apply limit for free users
      if ($user->status === 'free') {
          $monitors = $monitors->take(5);
      }
  
      $totalMonitors = $monitors->count();
      $monitors = $monitors->slice($start ?? 0, $length ?? 10);
  
      foreach ($monitors as $monitor) {
          $monitor->latestResponses = $this->getLatestResponsesByType($monitor);
      }
  
      $upCount = $monitors->where('status', 'up')->count();
      $downCount = $monitors->where('status', 'down')->count();
  
      return response()->json([
          'draw' => $draw,
          'recordsTotal' => $totalMonitors,
          'recordsFiltered' => $totalMonitors,
          'data' => $monitors->values(), // Reset keys
          'upCount' => $upCount,
          'downCount' => $downCount,
          'totalMonitors' => $totalMonitors
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

    activity()
    ->performedOn($details)
    ->causedBy(auth()->user())
    ->inLog('monitor_management') 
    ->event('viewed specific monitor')
    ->withProperties([
        'monitor_name' => $details->name,
        'monitor_type' => $type,
        'user_id' => auth()->id(),
        'ip' => request()->ip(),
    ])
    ->log("User viewed {$type} monitor dashboard");
    
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
    
    return response()->json([
        'responses' => $ChartResponses,
        'status' => $details->status,  // your new key-value pair
    ]);
    
   }

   public function MonitorDelete($id)
   {

    $DeleteMonitor=Monitors::findOrFail($id);

    if(!$DeleteMonitor)
    {
        return redirect()->back()->with('error','Monitoring data not found.');
    }

    $DeleteMonitor->delete();

    activity()
    ->performedOn($DeleteMonitor)
    ->causedBy(auth()->user())
    ->inLog('monitor_management') 
    ->event('monitor deleted')
    ->withProperties([
        'monitor_name' => $DeleteMonitor->name,
        'monitor_type' => $DeleteMonitor->type,
        'user_id' => auth()->id(),
        'ip' => request()->ip(),
    ])
    ->log("User deleted {$DeleteMonitor->type} monitor ");

    return redirect()->route('monitoring.dashboard')->with('success', 'Monitoring data deleted successfully.');

   }
   public function pauseMonitor(Request $request, $id)
    {
    $user = auth()->user();

    $monitor = Monitors::findOrFail($id);

    // Toggle the paused status
    $monitor->paused = !$monitor->paused;
    $monitor->save();

    $status = $monitor->paused ? 'paused' : 'resumed';

    activity()
    ->performedOn($monitor)
    ->causedBy(auth()->user())
    ->inLog('monitor_management') 
    ->event($status)
    ->withProperties([
        'name' => $user->name,
        'monitor_id' => $monitor->id,
        'monitor_name' => $monitor->name,
        'status' => $status,
    ])
    ->log("Monitor {$monitor->name} has been {$status}");

    return response()->json([
        'success' => true,
        'message' => "Monitor has been {$status} successfully.",
        'paused' => $monitor->paused,
    ]);
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
        'type' => 'string'
    ]);

    $EditMonitoring=Monitors::findOrFail($id);

    $original = $EditMonitoring->getOriginal(); 

    $EditMonitoring->update($request->all());

    $changes = [
        'old' => [],
        'new' => [],
    ];

    foreach ($request->all() as $key => $value) {
        if (array_key_exists($key, $original) && $original[$key] != $value) {
            $changes['old'][$key] = $original[$key];
            $changes['new'][$key] = $value;
        }
    }

    activity()
        ->performedOn($EditMonitoring)
        ->causedBy(auth()->user())
        ->inLog('monitor_management') 
        ->event('updated monitor')
        ->withProperties($changes)
        ->log('Monitoring details updated');

    return redirect()->back()->with('success', 'Monitoring details updated successfully.');
   }
}
