<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\BlockedIp;

class BlockSuspiciousTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        
        // Check DB for blocked IP
        if (BlockedIp::where('ip_address', $ip)->exists()) {
            // abort(403, 'Your IP has been blocked.');
            return response()->view('errors.403', [
                'message' => 'Your IP has been blocked.'
            ], 403);
        }

        return $next($request);
    }
}
