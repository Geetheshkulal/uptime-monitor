<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\TrafficLog;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;

class LogTraffic
{
    public function handle(Request $request, Closure $next)
    {
   
    if (!Auth::check()) {
        $agent = new Agent();

        $log = new TrafficLog();
        $log->ip = $request->ip();
        $log->user_agent = $request->userAgent();
        $log->browser = $agent->browser();
        $log->platform = $agent->platform();
        $log->referrer = $request->headers->get('referer');
        $log->url = $request->fullUrl();
        $log->method = $request->method();
        $log->save();

    }

        return $next($request);
       
    }
}
