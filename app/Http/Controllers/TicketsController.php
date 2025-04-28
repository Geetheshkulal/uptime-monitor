<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketsController extends Controller
{
    //
    public function UserViewTickets()
    {
    
        return view('pages.tickets.DisplayTickets',);
    }

    public function RaiseTickets()
    {
    
        return view('pages.tickets.AddTickets');
    }
}
