<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\TrafficLog;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LogTraffic
{
    public function handle(Request $request, Closure $next)
    {
   
    if (!Auth::check()) {
        $agent = new Agent();
        $ip = $request->ip();
        $isp = 'Unknown ISP'; 

        try{
            $token=env('IPINFO_TOKEN');
            $response=Http::get("https://ipinfo.io/{$ip}?token={$token}");

            if($response->successful()){
                $data=$response->json();
                $isp = $data['org'] ?? 'Unknown ISP'; 
                $country = $data['country'] ?? 'Unknown';
            }      
        }catch(\Exception $e){

        }
            
        $log = new TrafficLog();
        $log->ip = $request->ip();
        $log->user_agent = $request->userAgent();
        $log->isp = $isp;
        $log->country = $country;
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
