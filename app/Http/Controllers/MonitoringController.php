<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function MonitoringDashboard()
{
    return view('pages.MonitoringDashboard');
}

   public function MonitoringDisplay()
   {
    return view('pages.Display_Monitoring');

   }

}
