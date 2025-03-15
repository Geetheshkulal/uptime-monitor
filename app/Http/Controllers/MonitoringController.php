<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function MonitoringDashboard()
{
    return view('pages.MonitoringDashboard');
}
}
