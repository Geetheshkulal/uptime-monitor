<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Comment;

class TicketController extends Controller
{
    public function TicketsView(){

        $tickets = Ticket::all();
        
        return view('pages.admin.TicketDisplayAdmin', compact('tickets'));
    }

    public function ShowTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $comments = $ticket->comments()->with('user')->latest()->get();

        return view('pages.admin.TicketDetails', compact('ticket', 'comments'));
    }

    public function UpdateTicket(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'status' => 'required|in:open,closed,on hold',
            'priority' => 'required|in:low,medium,high',
            'contact_no' => 'required|string|max:10',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());

        return redirect()->back()->with('success', 'Ticket updated successfully');
    }

    public function CommentStore(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'description' => 'required|string',
        ]);
    
        Comment::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => auth()->id(), // Assuming the logged-in user is adding the comment
            'comment_message' => $request->description,
        ]);
    
        return redirect()->back()->with('success', 'Comment added successfully.');
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
