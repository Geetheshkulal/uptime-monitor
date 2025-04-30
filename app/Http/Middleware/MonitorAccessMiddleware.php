<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

//Give access to only own monitors
class MonitorAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Ensure the user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // Get the requested monitor ID
        $monitorId = $request->route('id');

        // Check if user is superadmin - grant full access if true
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // Check if monitor belongs to the user
        $monitorExists = $user->monitors()->where('id', $monitorId)->exists();

        if (!$monitorExists) {
            return redirect()->route('monitoring.dashboard')
                ->with('error', 'You do not have access to this monitor.');
        }

        // If user is unpaid, only allow access to the first 5 monitors
        if ($user->status === 'free') {
            $allowedMonitorIds = $user->monitors()->orderBy('id')->limit(5)->pluck('id')->toArray();

            if (!in_array($monitorId, $allowedMonitorIds)) {
                return redirect()->route('premium.page')
                    ->with('error', 'Upgrade to premium to access this monitor.');
            }
        }

        return $next($request);
    }
}