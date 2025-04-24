<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone'=>['required', 'digits:10','min:10']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_login_ip' => $request->ip(),
            'phone'=> $request->phone,
        ]);

         // Fire the Registered event to send the verification email
        event(new Registered($user));

        $user->assignRole('user');

        activity()
        ->causedBy($user)
        ->inLog('auth')
        ->event('register')
        ->withProperties([
            'name'        => $user->name,
            'email'       => $user->email,
            'ip'          => $request->ip(),
            'user_agent'  => $request->userAgent()
        ])
        ->log('New user registered');
        

        Auth::login($user);
        $user->session_id = Session::getId();
        $user->save();


        return redirect()->route('verification.notice');
        // return redirect(RouteServiceProvider::HOME);
    }
}
