<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
        
                $responseBody = $response->json();
        
                if (!($responseBody['success'] ?? false)) {
                    $fail('reCAPTCHA verification failed.');
                }
            }],
        ]);

        
        $request->authenticate();

        /** @var User $user */
        $user = Auth::user(); // Ensure $user is the logged-in user

    if ($user instanceof User) {

        $user->update(['last_login_ip' => $request->ip()]);

        $currentSessionId=session()->getId();

        if($user->session_id && $user->session_id !== $currentSessionId)
        {
            Session::getHandler()->destroy($user->session_id);
        }

        $user->session_id = $currentSessionId;
        $user->save();
    }

    activity()
            ->causedBy($user)
            ->inLog('auth')
            ->event('login')
            ->withProperties([
                'name'       => $user->name,
                'email'      => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('User logged in');


        $request->session()->regenerate();

        if ($request->has('remember')) {
            Cookie::queue('remember_email', $request->email, 1440); // for 1 days
            Cookie::queue('remember_password', $request->password, 1440); 
        } else {
            Cookie::queue(Cookie::forget('remember_email'));
            Cookie::queue(Cookie::forget('remember_password'));
        }

        $redirectRoute=($user->hasRole('superadmin'))?RouteServiceProvider::ADMIN_DASHBOARD:RouteServiceProvider::HOME;

        return redirect()->intended($redirectRoute);
    }

   
    public function destroy(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

    if ($user) {
        activity()
            ->causedBy($user)
            ->inLog('auth')
            ->event('logout')
            ->withProperties([
                'name'       => $user->name,
                'email'      => $user->email,
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logout_at'  => now()
            ])
            ->log('User logged out');
    }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
