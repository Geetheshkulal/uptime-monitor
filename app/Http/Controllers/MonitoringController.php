<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function MonitoringDashboard()
{
    return view('pages.MonitoringDashboard');
}

 public function AddMonitoring(){

    return view('pages.Add_Monitor');
 }
   public function MonitoringDisplay()
   {
    return view('pages.Display_Monitoring');

   }

}
