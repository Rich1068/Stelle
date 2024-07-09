<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    //view own profile
    public function profile()
    {
        $user = Auth::user(); 
        $user->load('country');
        $countryTable = $user->country;

    
        return view('profile.profile', ['user' => $user, 'countryTable' => $countryTable]); 
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
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
    
        try {
            $user->fill($request->validated());
    
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
    
            $user->save();
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return Redirect::route('profile.edit')->with('error', 'Failed to update profile');
        }
    
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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
