<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Comment;
use Laravolt\Avatar\Avatar;
use App\Models\User;

use App\Mail\TicketAssignedMail;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
{
    public function TicketsView(){

        $tickets = Ticket::all();

        $TotalTickets = Ticket::count();
        $OpenTickets = Ticket::where('status', 'open')->count();
        $ClosedTickets = Ticket::where('status', 'closed')->count();
        $OnHoldTickets = Ticket::where('status', 'on hold')->count();

        \App\Models\Ticket::where('is_read', false)->update(['is_read' => true]);
        
        return view('pages.admin.TicketDisplayAdmin', compact('tickets','TotalTickets','OpenTickets','ClosedTickets','OnHoldTickets'));
    }

    public function ShowTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $comments = $ticket->comments()->with('user')->orderBy('created_at', 'asc')->get();

        $supportUsers = User::role('support')->get();

        return view('pages.tickets.TicketDetails', compact('ticket', 'comments','supportUsers'));
    }

    public function UpdateTicket(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:50',
            'message' => 'required|string',
            'status' => 'required|in:open,closed,on hold',
            'priority' => 'required|in:low,medium,high',
            'assigned_user_id' => 'nullable|exists:users,id', 
        ], [
            'title.regex' => 'The title must only contain alphabetic characters. Numbers are not allowed.', // Custom error message
        ]);
    

        $ticket = Ticket::findOrFail($id);

        $previousAssignedUserId = $ticket->assigned_user_id;

        $ticket->update([
            'title' => $request->title,
            'message' => $request->message,
            'status' => $request->status,
            'priority' => $request->priority,
            'assigned_user_id' => $request->assigned_user_id, // Update assigned user
        ]);

        // Send email if the assigned user has changed
    if ($previousAssignedUserId !== $request->assigned_user_id && $request->assigned_user_id) {
        $assignedUser = User::find($request->assigned_user_id);
        Mail::to($assignedUser->email)->queue(new TicketAssignedMail($ticket));
    }

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

    public function CommentPageUpdate($id){
        $ticket = Ticket::findOrFail($id);
        $comments = $ticket->comments()->with('user.roles')->orderBy('created_at', 'asc')->get();

    
        $comments->each(function ($comment) {
            // Configure the avatar with color here
            $avatar = new Avatar();
            $comment->user->avatar_url = $avatar
                ->create($comment->user->name)
                ->toBase64();
        });
    
        return response()->json($comments);
    }

    public function ViewTicketsUser()
    {
         $user = auth()->user();
         $tickets = Ticket::where('user_id',$user->id)->get();

         if($user->hasRole('support')){
             $tickets = Ticket::where('assigned_user_id',$user->id)->get();
         }

       
        
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
            'description' => 'required|min:1',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $attachmentPaths = [];
        // if ($request->hasFile('attachments')) {
        //     foreach ($request->file('attachments') as $file) {
        //         $path = $file->store('attachments', 'public');
        //         $attachmentPaths[] = $path;
        //     }
        // }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
               
                $fileName = date('Ymd') . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
    
                $file->move(public_path('storage/attachments'), $fileName);
    
                $attachmentPaths[] = 'storage/attachments/' . $fileName;
            }
        }

        $ticket = Ticket::create([
            'ticket_id'=>'TKT-' . strtoupper(Str::random(10)),
            'title' => $request->subject,
            'message' => $request->description,
            'priority' => $request->priority,
            'attachments' => $attachmentPaths,
            'user_id' => auth()->id(), // If you have user association
        ]);

        return redirect()->route('display.tickets')->with('success', 'Ticket created successfully');
    }

    
    public function DeleteComment($id)
    {
        $ticket = Comment::find($id);

        if (!$ticket) {
            return redirect()->back()->with('error', 'Comment not found.');
        }

        $ticket->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }
}
