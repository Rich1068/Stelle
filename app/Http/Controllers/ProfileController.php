<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\superadminProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\Country;
use App\Models\User;
use App\Models\Event;
use App\Events\UserDeleted;
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
        $attendedEvents = $user->eventParticipant()->with('event')->get()->pluck('event');
        $countryTable = $user->country;
        $event = Event::all();
        return view('profile.profile', ['user' => $user, 'countryTable' => $countryTable, 'attendedEvents' => $attendedEvents, 'event' => $event]); 
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
        
        DB::beginTransaction(); // Start the transaction
        
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
            if ($user->email !== $validatedData['email']) {
                $user->email_verified_at = null;
            }

            // Fill the user with the validated data and save
            $user->fill($validatedData);
            $user->save();

            DB::commit(); // Commit the transaction if everything goes well

        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction if there's an error

            // Log the error or handle it as needed
            return Redirect::route('profile.edit')->with('error', 'Failed to update profile');
        }

        return back()->with('status', 'profile-updated');
    }


    public function superadmin_edit(Request $request, $id): View
    {
        $user = User::findOrFail($id); 
        $countries = Country::all();
    
        // Pass the user and countries data to the view
        return view('super_admin.edit', compact('user', 'countries'));
    }

    public function superadmin_update(superadminProfileUpdateRequest $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        DB::beginTransaction(); // Start the transaction

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
            if ($user->email !== $validatedData['email']) {
                $user->email_verified_at = null;
            }

            // Fill the user with the validated data and save
            $user->fill($validatedData);
            $user->save();

            DB::commit(); // Commit the transaction if all operations were successful

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if any error occurs

            // Log the error or handle it as needed
            return Redirect::route('superadmin.editProfile', ['id' => $id])->with('error', 'Failed to update profile');
        }

        return back()->with('status', 'profile-updated');
    }

    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // Validate the password to confirm user identity before deletion
        $request->validate([
            'password' => ['required', 'current_password'], // current_password validates the entered password
        ]);

        // Perform soft delete
        $user->delete();

        // Optionally, logout the user and invalidate the session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted.');
    }

    public function superadmin_destroy($id): RedirectResponse
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Check if the user is not the super admin themselves to prevent self-deletion (optional)
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Soft delete the user
        $user->delete();

        event(new UserDeleted($user));
        Log::info('account deleted');

        return redirect()->route('super_admin.userlist')->with('status', 'User account has been soft deleted.');
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

        return redirect()->back()->with('success', 'You have registered to be an Admin, Please wait for approval!');
    }

    public function myCertificates()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Load the certificates relationship to avoid N+1 query problem
        $user->load('certificates');

        $user->load('certUser');

        // Return the view with the user and their certificates
        return view('profile.mycertificates', compact('user'));
    }

    public function accountDeleted()
    {
        return view('auth.account-deleted');
    }

}