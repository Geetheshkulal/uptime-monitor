<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function MonitoringDashboard()
    {
        $totalMonitors = Monitor::count();

        // for dashboard
        $monitors = Monitor::with('latestPortResponse')->where('user_id', auth()->id())->get();
        
        // for Bar symbol
        $Bars = Monitor::with(['latestResponseBar'=>function($query){
            $query->orderBy('created_at', 'desc');
        }])->get();

        return view('pages.MonitoringDashboard', compact('monitors','totalMonitors','Bars'));

    }

    
 public function AddMonitoring()
 {

    return view('pages.Add_Monitor');
  }
   public function MonitoringDisplay()
   {
     return view('pages.DisplayMonitoring');
   }



}
