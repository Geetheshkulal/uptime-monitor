<?php

namespace App\Http\Controllers;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function DisplayActivity()
    {
        return view('pages.admin.DisplayActivity');
    }

    public function UserSearch(Request $request){
        $search = $request->get('q');
        $query = User::select('id', 'name', 'email', 'phone');

        if (!auth()->user()->hasRole('superadmin')) {
            $superadminIds = User::role('superadmin')->pluck('id');
            $query->whereNotIn('id', $superadminIds);
        }

        if (auth()->user()->hasRole('user')) {
            $subUserIds = User::role('subuser')
                ->where('parent_user_id', auth()->id())
                ->pluck('id');
            $query->whereIn('id', $subUserIds);
        }

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });

        $query->orderByRaw("CASE 
            WHEN name LIKE ? THEN 1
            WHEN name LIKE ? THEN 2
            ELSE 3
            END", ["{$search}%", "%{$search}%"]);

        $users = $query->limit(10)->get();

        return response()->json($users->map(function ($user) {
            return [
                'id' => $user->id,
                'text' => "{$user->name} | {$user->email} | {$user->phone}",
            ];
        }));
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

        // search by name email phone
        $searchName = $request->get('search_name');
        $searchEmail = $request->get('search_email');
        $searchPhone = $request->get('search_phone');

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

        // Apply specific search filters
        if (!empty($searchName)) {
            $query->whereHas('causer', function($q) use ($searchName) {
                $q->where('name', 'LIKE', "%{$searchName}%");
            });
        }
        if (!empty($searchEmail)) {
            $query->whereHas('causer', function($q) use ($searchEmail) {
                $q->where('email', 'LIKE', "%{$searchEmail}%");
            });
        } 
        if (!empty($searchPhone)) {
            $query->whereHas('causer', function($q) use ($searchPhone) {
                $q->where('phone', 'LIKE', "%{$searchPhone}%");
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
