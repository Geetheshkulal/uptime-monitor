<?php

namespace App\Http\Controllers;

use App\Models\Monitors;
use Illuminate\Http\Request;

class StatusPageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user = ($user->hasRole('subuser'))?$user->parentUser:auth()->user();

        $daysToShow = $user->status === 'free' ? 30 : 120; // Determine days based on user status
        
        $monitors = Monitors::with(['user'])
            ->where('user_id', $user->id)
            ->orderBy('status')
            ->orderBy('name')
            ->get()
            ->map(function ($monitor) use ($daysToShow) {
                return $this->getMonitorData($monitor, $daysToShow);
            });
            
            return view('pages.status', [
                'monitors' => $monitors,
                'user' => $user
            ]);
    }

    public function getMonitorData($monitor, $daysToShow)
    {
        // Determine status color and icon
        $monitor->statusColor = $monitor->status == 'up' ? 'success' : 'danger';
        $monitor->statusIcon = $monitor->status == 'up' ? 'check-circle' : 'times-circle';
        
        // Get data for the specified number of days
        $daysData = [];
        $startDate = now()->subDays($daysToShow - 1)->startOfDay(); // Subtract (daysToShow - 1) to get correct number of days
        $endDate = now();
        
        // Initialize all days with default values
        $currentDate = clone $startDate;
        while ($currentDate <= $endDate) {
            $daysData[$currentDate->format('Y-m-d')] = [
                'date' => $currentDate->format('Y-m-d'),
                'total_checks' => 0,
                'success_checks' => 0,
                'uptime_percentage' => 0
            ];
            $currentDate->addDay();
        }
        
        // Get actual data from database
        $modelClass = 'App\\Models\\' . ucfirst($monitor->type) . 'Response';
        if(class_exists($modelClass)) {
            $responses = $modelClass::where('monitor_id', $monitor->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
                ->groupBy(function($item) {
                    return $item->created_at->format('Y-m-d');
                });
            
            // Calculate daily uptime percentage 
            foreach ($responses as $date => $dayResponses) {
                $totalChecks = count($dayResponses);
                $successChecks = $dayResponses->where('status', 'up')->count();
                $uptimePercentage = $totalChecks > 0 ? ($successChecks / $totalChecks) * 100 : 0;
                
                $daysData[$date] = [
                    'date' => $date,
                    'total_checks' => $totalChecks,
                    'success_checks' => $successChecks,
                    'uptime_percentage' => $uptimePercentage
                ];
            }
        }
        
        // Calculate overall uptime percentage
        $monitor->overallUptime = 100;
        $monitor->totalChecks = array_sum(array_column($daysData, 'total_checks'));
        $monitor->successChecks = array_sum(array_column($daysData, 'success_checks'));
        if ($monitor->totalChecks > 0) {
            $monitor->overallUptime = round(($monitor->successChecks / $monitor->totalChecks) * 100, 2);
        }
        
        // Process days data for visualization
        $monitor->daysData = collect($daysData)->map(function ($day) {

            $day['color'] = '#10b981'; 
            if ($day['uptime_percentage'] < 95) {
                $day['color'] = '#f59e0b'; 
            }
            if ($day['uptime_percentage'] < 80) {
                $day['color'] = '#ef4444'; 
            }
            // If no checks that day, show gray
            if ($day['total_checks'] === 0) {
                $day['color'] = '#e2e8f0'; 
            }
            
            $day['height'] = 40;
            $day['formatted_date'] = \Carbon\Carbon::parse($day['date'])->format('M j, Y');
            
            return $day;
        });
        
        return $monitor;
    }
}