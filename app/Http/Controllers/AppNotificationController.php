<?php

namespace App\Http\Controllers;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Monitors;
use App\Events\AdminNotification;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class AppNotificationController extends Controller
{
    /**
     * Display all users in the admin panel with pagination
     */

    public function ViewAppNotification(){

        // Pass latest 5 notifications to view
        $LatestNotifications = DB::table('user_notifications')
        ->latest()
        ->take(5)
        ->get();

        return view('pages.admin.SendAppNotification', compact('LatestNotifications'));
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
