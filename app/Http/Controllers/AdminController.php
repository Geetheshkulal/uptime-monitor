<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;
use App\Models\Monitors;
use App\Events\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class AdminController extends Controller
{
    /**
     * Display all users in the admin panel with pagination
     */
    public function AdminDashboard(){
        $role = Role::where('name', 'user')->first(); //Get user role from roles table.
    
        $total_user_count = $role->users()->count(); //Count of total number of users
        
        $paid_user_count = $role->users()->where('status', 'paid')->count(); //Number of paid users.
    
        $monitor_count = Monitors::count(); //Total Number of Monitors by all users.
    
        // Total revenue from subscriptions linked to payments
        $total_revenue = Payment::with('subscription')
            ->get()
            ->sum(function ($payment) {
                return $payment->subscription->amount ?? 0;
            });
    
        // user growth data over the past year.
        $now = Carbon::now();
        $oneYearAgo = $now->copy()->subYear()->startOfMonth();
    
        $userCountsByMonth = User::role('user')
            ->where('created_at', '>=', $oneYearAgo)
            ->selectRaw('DATE_FORMAT(created_at, "%b") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at)')
            ->pluck('count', 'month');
    
        // Initialize months
        $allMonths = collect(range(0, 11))->map(function ($i) use ($now) {
            return $now->copy()->subMonths(11 - $i)->format('M');
        });
    
        // Fill missing months with 0
        $finalData = $allMonths->map(function ($month) use ($userCountsByMonth) {
            return $userCountsByMonth[$month] ?? 0;
        });
    
        // Output to use in chart
        $month_labels = $allMonths->toArray(); // ['Apr', 'May', ..., 'Mar']
        $user_data = $finalData->toArray();   // [10, 23, 0, 5, ...]
    
        // PAYMENTS TABLE - Monthly revenue based on subscriptions
        $revenue = Payment::with('subscription')
            ->where('created_at', '>=', $oneYearAgo)
            ->get()
            ->groupBy(function ($payment) {
                return $payment->created_at->format('M');
            })
            ->map(function ($group) {
                return $group->sum(function ($payment) {
                    return $payment->subscription->amount ?? 0;
                });
            });
    
        $monthly_revenue = $allMonths->map(function ($month) use ($revenue) {
            return $revenue[$month] ?? 0;
        })->toArray();
    
        // Count of active users in the last month.
        $thirtyDaysAgo = Carbon::now()->subDays(30);
    
        //Number of active users in last 30 days (users who performed any activity in this interval)
        $activeUserIds = Activity::where('created_at', '>=', $thirtyDaysAgo)
            ->whereHas('causer.roles', function ($query) {
                $query->where('name', 'user'); // Spatie role name
            })
            ->distinct()
            ->pluck('causer_id');
    
        $active_users = $activeUserIds->count();

        // Run shell command to get cpu load percentage.
        $cpuRaw = shell_exec('wmic cpu get loadpercentage /value');
        preg_match('/LoadPercentage=(\d+)/', $cpuRaw, $cpuMatches);
        $cpuPercent = $cpuMatches[1] ?? 'N/A';

    
        return view('pages.admin.AdminDashboard', compact(
            'total_user_count',
            'paid_user_count',
            'monitor_count',
            'total_revenue',
            'month_labels',
            'user_data',
            'monthly_revenue',
            'active_users',
            'cpuPercent'
        ));
    }

   

}
