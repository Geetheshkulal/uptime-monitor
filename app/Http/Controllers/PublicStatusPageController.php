<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Monitors;
use Illuminate\Support\Str;

class PublicStatusPageController extends Controller
{
    public function show($hash)
    {
        $user = User::where('status_page_hash', $hash)
                  ->where('enable_public_status', true)
                  ->firstOrFail();

        // Get days to show based on user status
        $daysToShow = $user->status === 'free' ? 30 : 120;

        // Get and enrich public monitors
        $monitors = $user->monitors()
                       ->orderBy('status')
                       ->orderBy('name')
                       ->get()
                       ->map(function ($monitor) use ($daysToShow) {
                           return $this->enrichMonitorData($monitor, $daysToShow);
                       });

        return view('public-status', [
            'monitors' => $monitors,
            'user' => $user
        ]);
    }

    protected function enrichMonitorData($monitor, $daysToShow)
    {
        // Determine status color and icon
        $monitor->statusColor = $monitor->status == 'up' ? 'success' : 'danger';
        $monitor->statusIcon = $monitor->status == 'up' ? 'check-circle' : 'times-circle';
        
        // Get data for the specified number of days
        $daysData = [];
        $startDate = now()->subDays($daysToShow - 1)->startOfDay();
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
            // Determine color based on uptime percentage
            $day['color'] = '#10b981'; // green (good)
            if ($day['uptime_percentage'] < 95) {
                $day['color'] = '#f59e0b'; // yellow (warning)
            }
            if ($day['uptime_percentage'] < 80) {
                $day['color'] = '#ef4444'; // red (bad)
            }
            // If no checks that day, show gray
            if ($day['total_checks'] === 0) {
                $day['color'] = '#e2e8f0'; // gray (no data)
            }
            
            $day['height'] = 40;
            $day['formatted_date'] = \Carbon\Carbon::parse($day['date'])->format('M j, Y');
            
            return $day;
        });
        
        return $monitor;
    }
}