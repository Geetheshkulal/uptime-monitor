<?php

namespace App\Http\Controllers;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Monitors;
use App\Events\AdminNotification;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class AppNotificationController extends Controller
{
    /**
     * Display all users in the admin panel with pagination
     */

    public function ViewAppNotification(){

        return view('pages.admin.SendAppNotification');
    }

    public function sendNotificationToUsers(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'type' => 'nullable|string'
        ]);
    
        $notification = [
            'message' => $validated['message'],
            'type' => $validated['type'] ?? 'announcement',
            'time' => now()->diffForHumans()
        ];

        // Store notification for all users
        $users = User::all();
        foreach ($users as $user) {
            $user->notify(new GeneralNotification($notification));
        }

        event(new AdminNotification($notification));
    
    
        return back()->with('success', 'Notification sent to all users!');
    }
    

    public function markNotificationsAsRead(Request $request)
    {
       
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['status' => 'success']);
    }

}
