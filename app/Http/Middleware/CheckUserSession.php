<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
class CheckUserSession
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        if ($user) {
            $sessionId = Session::getId();

            Log::debug('Middleware check:', [
                'current_session' => $sessionId,
                'user_session' => $user->session_id
            ]);
            
            // If user's session_id doesn't match current session
            if ($user->session_id !== $sessionId) {
                Auth::logout();
                return redirect('/login')->with('error', 'You have been logged out because your account was accessed from another location.');
            }
        }
        
        return $next($request);
    }
}