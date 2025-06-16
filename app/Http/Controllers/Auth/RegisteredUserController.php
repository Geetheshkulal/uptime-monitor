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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $ip = request()->ip();
        if ($ip === '127.0.0.1' || $ip === '::1') {
            $ip = '122.179.30.115'; // fallback IP for localhost
        }
    
        $countryCode = null;
        $dialCode = null;
    
        try {
            $response = Http::get("https://ipinfo.io/{$ip}?token=".env('IPINFO_TOKEN')); // Replace with your token
            if ($response->successful()) {
                $countryCode = $response->json()['country'] ?? null;

                $dialingCodes = include(base_path('app/Helpers/CountryDialingCodes.php'));
                Log::info('All Dial Codes', $dialingCodes);

                $dialCode = $dialingCodes[$countryCode] ?? null;

                Log::info("Dial Code Detected", ['dialCode' => $dialCode, 'countryCode' => $countryCode]);
            }
        } catch (\Exception $e) {
            Log::warning("IPInfo API failed: " . $e->getMessage());
        }
    

        return view('auth.register', compact('dialCode','countryCode'));
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
            'country_code' => $request->country_code,
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
