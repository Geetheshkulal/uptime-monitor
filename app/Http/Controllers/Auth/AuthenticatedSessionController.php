<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\PushSubscription;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
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
        // Check if user is a subuser with parent on free plan
         /** @var User $user */
            $user = Auth::user();
            if ($user->parent_user_id) {
                $parentUser = User::find($user->parent_user_id);
                if ($parentUser && $parentUser->status === 'free') {
                    Auth::logout();
                    return back()->with('error', 'Your account is currently inactive because the parent account is on free plan.');
                }
            }

        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user(); 

     if ($user instanceof User) {

            $user->session_id = Session::getId();
            // $user->last_activity = now();
            $user->last_login_ip = $request->ip();
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


        if ($request->has('remember')) {
            Cookie::queue('remember_email', $request->email, 1440); // for 1 days
            Cookie::queue('remember_password', $request->password, 1440); 
        } else {
            Cookie::queue(Cookie::forget('remember_email'));
            Cookie::queue(Cookie::forget('remember_password'));
        }

        $redirect_route = '';

        switch($user->roles->first()->name){
            case 'superadmin':
                $redirectRoute= RouteServiceProvider::ADMIN_DASHBOARD;
                break;
            case 'support':
                $redirectRoute = '/display/tickets';
                break;
            default:
                $redirectRoute= RouteServiceProvider::HOME;
        }

        Log::info('Session data in login controller:', session()->all());
        return redirect()->intended($redirectRoute)->with('success', 'Login Successfully');;
    }

   
    public function destroy(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

          if ($user instanceof User) {

            $user->update([
                'session_id' => null,
            ]);
        }

        PushSubscription::where('user_id',$user->id)->delete();

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
