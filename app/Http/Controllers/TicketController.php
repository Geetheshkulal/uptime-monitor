<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function TicketsView(){

        $tickets = Ticket::all();
        
        return view('pages.admin.TicketDisplay', compact('tickets'));
    }
}
