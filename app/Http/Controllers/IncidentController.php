<?php

namespace App\Http\Controllers;
use App\Models\Monitor;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function incidents()
    {
        //Fetch all monitors from the database
        $monitors = Monitor::all();
        return view("pages.incidents", compact("monitors"));
    }
}
