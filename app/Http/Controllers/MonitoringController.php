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
}
