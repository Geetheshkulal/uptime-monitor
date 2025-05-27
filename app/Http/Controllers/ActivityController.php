<?php

namespace App\Http\Controllers;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    //
    public function DisplayActivity()
    {
      
        $query = Activity::latest();
        
        $userQuery = User::select('id', 'name');

        if(!auth()->user()->hasRole('superadmin')) {
            $superadminIds = User::role('superadmin')->pluck('id');

            $query->whereNotIn('causer_id', $superadminIds);

            $userQuery->whereNotIn('id',$superadminIds );
        }

        if(auth()->user()->hasRole('user')){
            $subUserIds = User::role('subuser')->where('parent_user_id', auth()->user()->id)->pluck('id');
            $query->whereIn('causer_id', $subUserIds);
            $userQuery->whereIn('id', $subUserIds);
        }

        $logs = $query->get();

        
        $users = $userQuery->get();

        
        return view('pages.admin.DisplayActivity', compact('logs', 'users'));
    }
} 
