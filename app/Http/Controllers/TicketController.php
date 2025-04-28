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

    public function ViewTicketsUser()
    {
        $tickets = Ticket::all();
        
        return view('pages.tickets.DisplayTickets', compact('tickets'));
    }

    public function RaiseTicketsPage()
    {
        return view('pages.tickets.AddTickets');
    }

    public function StoreTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'priority' => 'required',
            'category' => 'required',
        ]);

       
        return redirect()->route('display.tickets')->with('success', 'Ticket raised successfully');
    }
}
