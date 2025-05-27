<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function DisplayActivity()
    {
        // Fetch users for the filter dropdown
        $userQuery = User::select('id', 'name','email','phone');

        if(!auth()->user()->hasRole('superadmin')) {
            $superadminIds = User::role('superadmin')->pluck('id');
            $userQuery->whereNotIn('id', $superadminIds);
        }

        if(auth()->user()->hasRole('user')){
            $subUserIds = User::role('subuser')->where('parent_user_id', auth()->user()->id)->pluck('id');
            $userQuery->whereIn('id', $subUserIds);
        }

        $users = $userQuery->get();
        
        return view('pages.admin.DisplayActivity', compact('users'));
    }

    public function fetchActivityLogs(Request $request)
    {
        // Get parameters from DataTables
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $searchValue = $request->get('search')['value'];
        $orderColumn = $request->get('order')[0]['column'] ?? 0;
        $orderDirection = $request->get('order')[0]['dir'] ?? 'desc';
        $userFilter = $request->get('user_filter');

        // Define column mapping for ordering
        $columns = ['id', 'log_name', 'description', 'event', 'causer_id', 'created_at', 'properties'];
        
        // Build the base query
        $query = Activity::with('causer:id,name')->latest();
        
        // Apply role-based filtering
        if(!auth()->user()->hasRole('superadmin')) {
            $superadminIds = User::role('superadmin')->pluck('id');
            $query->whereNotIn('causer_id', $superadminIds);
        }

        if(auth()->user()->hasRole('user')){
            $subUserIds = User::role('subuser')->where('parent_user_id', auth()->user()->id)->pluck('id');
            $query->whereIn('causer_id', $subUserIds);
        }

        // Apply user filter if provided
        if (!empty($userFilter)) {
            $query->where('causer_id', $userFilter);
        }

        // Apply global search
        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('log_name', 'LIKE', "%{$searchValue}%")
                  ->orWhere('description', 'LIKE', "%{$searchValue}%")
                  ->orWhere('event', 'LIKE', "%{$searchValue}%")
                  ->orWhereHas('causer', function($subQuery) use ($searchValue) {
                      $subQuery->where('name', 'LIKE', "%{$searchValue}%");
                  });
            });
        }

        // Get total count before pagination
        $totalRecords = Activity::count();
        $filteredRecords = $query->count();

        // Apply ordering
        if (isset($columns[$orderColumn])) {
            if ($columns[$orderColumn] === 'causer_id') {
                // Special handling for user name ordering
                $query->join('users', 'activity_log.causer_id', '=', 'users.id')
                      ->orderBy('users.name', $orderDirection)
                      ->select('activity_log.*');
            } else {
                $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        }

        // Apply pagination
        $activities = $query->skip($start)->take($length)->get();

        // Format data for DataTables
        $data = [];
        foreach ($activities as $activity) {
            $data[] = [
                'id' => $activity->id,
                'log_name' => $activity->log_name,
                'description' => $activity->description,
                'event' => $activity->event,
                'causer_name' => $activity->causer?->name ?? 'System',
                'created_at' => $activity->created_at->format('d M Y, h:i A'),
                'properties' => $activity->properties,
                'properties_button' => '<button class="btn btn-success btn-sm" onclick="showPropertiesModal(' . htmlspecialchars(json_encode($activity->properties), ENT_QUOTES, 'UTF-8') . ')">View</button>'
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }
}