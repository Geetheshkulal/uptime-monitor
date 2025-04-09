<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

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
