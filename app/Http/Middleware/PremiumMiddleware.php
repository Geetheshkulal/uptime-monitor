<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
        // Skip middleware for premium page
        if ($request->routeIs('premium.page')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if user has paid status
        if ($request->user()->status !== 'paid') {
            return redirect()->route('premium.page')
                ->with('error', 'This feature requires a premium subscription');
        }

        return $next($request);
    }
}