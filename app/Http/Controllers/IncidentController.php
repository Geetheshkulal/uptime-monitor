<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function incidents()
    {
        return view("pages.incidents");
    }
}
