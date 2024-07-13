<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\Country;
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
        $countries = Country::all();
        return view('profile.edit', compact('countries'), [
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

            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $relativePath = 'storage/images/profile_pictures/' . $filename;
                $oldfile = $user->profile_picture;
                // Optionally, delete the old profile picture if it exists
                if (!empty($user->profile_picture)) {
                    
                    if (File::exists($oldfile)) {
                        File::delete($oldfile);
                    }
                }
            
                // Move the uploaded file to the desired directory
                $path = $file->storeAs('/images/profile_pictures', $filename);
            
                // Update the profile picture path
                $user->profile_picture = $relativePath;
            }
            $user->save();
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return Redirect::route('profile.edit')->with('error', 'Failed to update profile');
        }
    
        return back()->with('status', 'profile-updated');
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