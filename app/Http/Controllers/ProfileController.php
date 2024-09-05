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
use App\Models\User;
use App\Models\Roles;
use App\Models\RegisterAdmin;

class ProfileController extends Controller
{
    //view own profile
    public function profile()
    {
        $user = Auth::user(); 
        $user->load('country');
        $user->load('role');
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
            // Validate the request
            $validatedData = $request->validated();
    
            // Check if a new profile picture is being uploaded
            if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $relativePath = 'storage/images/profile_pictures/' . $filename;
                $oldfile = $user->profile_picture;
    
                // Optionally delete the old profile picture if it exists
                if (!empty($user->profile_picture) && File::exists($oldfile)) {
                    File::delete($oldfile);
                }
    
                // Move the uploaded file to the desired directory
                $file->storeAs('/images/profile_pictures', $filename);
    
                // Update the profile picture path in the validated data
                $validatedData['profile_picture'] = $relativePath;
            } else {
                // Remove profile_picture from validated data if no new file is uploaded
                unset($validatedData['profile_picture']);
            }
    
            // If email is updated, reset email_verified_at
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
    
            // Fill the user with the validated data and save
            $user->fill($validatedData);
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
    //view someones profile
    public function view($id)
    {
        $user = User::findOrFail($id);
        return view('profile.view', compact('user'));
    }
    

    //users to become admins
    public function registerAdmin(Request $request)
    {
        // Insert into the database
        RegisterAdmin::create([
            'user_id' => Auth::user()->id,
            'status_id' => 3, 
        ]);

        // Redirect or return a response
        return back()->with('success', 'You have registered to be an Event Admin, Please wait for approval!');
    }

    public function myCertificates()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Load the certificates relationship to avoid N+1 query problem
        $user->load('certificates');

        // Return the view with the user and their certificates
        return view('profile.mycertificates', compact('user'));
    }

}