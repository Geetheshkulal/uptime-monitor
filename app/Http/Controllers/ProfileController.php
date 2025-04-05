<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user(); 

        activity()
        ->causedBy($user)
        ->performedOn($user)
        ->event('viewed')
        ->withProperties([
            'name' => $user->name,
            'email' => $user->email,
        ])
        ->log('Viewed profile edit form');

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $originalData = $user->getOriginal();

        $validated = $request->validated();

        $user->fill($validated);

        // $request->user()->fill($request->validated());

        $changes = [];
        foreach ($validated as $key => $newValue) {
            if ($user->isDirty($key)) {
                $changes[$key] = [
                    'old' => $originalData[$key] ?? null,
                    'new' => $newValue
                ];
            }
        }

        

        $request->user()->save();

        activity()
        ->performedOn($user)
        ->causedBy($user)
        ->event('updated profile')
        ->withProperties(['changes' => $changes])
        ->log('Updated profile');

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        activity()
        ->performedOn($user)
        ->causedBy($user)
        ->event('account deleted')
        ->withProperties([
            'name' => $user->name,
            'email' => $user->email,
        ])
        ->log('Deleted account');

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
