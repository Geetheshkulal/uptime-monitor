<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    //
    public function DisplayActivity()
    {
        // Fetch all activity logs
        $query = Activity::latest();
        
        $userQuery = User::select('id', 'name');

        if(!auth()->user()->hasRole('superadmin')) {
            $superadminIds = User::role('superadmin')->pluck('id');

            // Exclude logs where causer_id is a superadmin
            $query->whereNotIn('causer_id', $superadminIds);

            $userQuery->whereNotIn('id',$superadminIds );
        }

        $logs = $query->get();

        // Fetch all users with only id and name
        $users = $userQuery->get();

        
        return view('pages.admin.DisplayActivity', compact('logs', 'users'));
    }
}
