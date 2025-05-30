<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


//Controller for user profile
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
        ->inLog('profile_activity')
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


        $changes = [];
        foreach ($validated as $key => $newValue) {
            if ($user->isDirty($key)) {
                $changes[$key] = [
                    'old' => $originalData[$key] ?? null,
                    'new' => $newValue
                ];
            }
        }

        //Update User
        $request->user()->save();

        //Log activity
        activity()
        ->performedOn($user)
        ->causedBy($user)
        ->inLog('profile_activity')
        ->event('updated profile')
        ->withProperties(['changes' => $changes])
        ->log('Updated profile');

        return Redirect::route('profile.edit')->with('success', 'Profile Updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        //Input validation
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($request->user()->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }


        //Log Deletion activity
        activity()
        ->performedOn($user)
        ->causedBy($user)
        ->inLog('profile_activity')
        ->event('account deleted')
        ->withProperties([
            'name' => $user->name,
            'email' => $user->email,
        ])
        ->log('Deleted account');

        //Logout user after deletion
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
