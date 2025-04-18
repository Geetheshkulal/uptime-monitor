<?php

namespace App\Http\Middleware;

use Closure;

//Remove cookies(for tracking poxel)
class DisableCookies
{
    public function handle($request, Closure $next)
    {
        // Disable session for this route
        config(['session.driver' => 'array']);
        
        $response = $next($request);
        
        // Remove all cookies
        foreach (array_keys($response->headers->getCookies()) as $name) {
            $response->headers->removeCookie($name);
        }
        
        // Remove identifying headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Host');
        $response->headers->remove('Ngrok-Agent-Ips');
        
        return $response;
    }
}