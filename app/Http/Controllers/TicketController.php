<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            'description' => 'required|min:1',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $attachmentPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                $attachmentPaths[] = $path;
            }
        }

        $ticket = Ticket::create([
            'ticket_id'=>'TICKET-' . strtoupper(Str::random(10)),
            'title' => $request->subject,
            'message' => $request->description,
            'priority' => $request->priority,
            'category' => $request->category,
            'attachments' => $attachmentPaths,
            'user_id' => auth()->id(), // If you have user association
        ]);

        return redirect()->route('display.tickets')->with('success', 'Ticket created successfully');
    }
}
