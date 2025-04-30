<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function PHPUnit\Framework\isNull;

class PremiumMiddleware
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
        

        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }
        
        // Skip middleware for premium page
        if ($request->routeIs('premium.page')) {
            if($request->user()->status === 'paid') {
                return redirect()->route('monitoring.dashboard')
                    ->with('success', 'You are already a premium user.');
            }
            return $next($request);
        }

        // Check if user has free status
        if ($request->user()->status !== 'paid') {
            return redirect()->route('premium.page')
                ->with('error', 'This feature requires a premium subscription');
        }

        
    }
}