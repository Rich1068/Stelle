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
use App\Models\UserEvent;
use App\Models\EventParticipant;
use App\Models\EvaluationForm;
use App\Models\CertUser;
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
        $attendedEvents = $user->eventParticipant()
            ->with('event')
            ->where('status_id', 1) // Filter by joined events
            ->orderBy('updated_at', 'desc') // Order by most recent join
            ->get()
            ->pluck('event');

        $createdEvents = $user->eventsCreated()->orderBy('created_at', 'desc')->get()->pluck('event');
        Log::info($createdEvents);
        $certificates = CertUser::where('user_id', $user->id)->get();

        
        $totalEventsCreated = UserEvent::where('user_id', $user->id)->count();
        $totalEvaluationFormsCreated = EvaluationForm::where('created_by', $user->id)->count();
        $totalAttendedEvents = EventParticipant::where('user_id', $user->id)->where('status_id', 1)->count();
        $totalCertificates = CertUser::where('user_id', $user->id)->count();

        // Monthly event participation (for the current year)
        $currentYear = now()->year;
        $monthlyParticipation = EventParticipant::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('user_id', $user->id)
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Fill in missing months with 0
        $monthlyParticipationData = array_fill(1, 12, 0);
        foreach ($monthlyParticipation as $month => $total) {
            $monthlyParticipationData[$month] = $total;
        }
        return view('profile.view', compact('user','attendedEvents','createdEvents', 'totalEvaluationFormsCreated','totalEventsCreated', 'totalAttendedEvents', 'totalCertificates', 'monthlyParticipationData', 'certificates'));
    }

    public function updateRole(Request $request, $id)
    {
        // Validate the role input
        $validatedData = $request->validate([
            'role_id' => 'required|in:1,2,3',  // Ensures the selected value is one of the allowed roles
        ]);
    
        // Find the user
        $user = User::findOrFail($id);
    
        // Update the role
        $user->role_id = $validatedData['role_id'];
        $user->save();
        return back()->with('status', 'profile-updated');
    }


    //CHART INFO
    public function getEventsData($id, Request $request)
    {
        $year = $request->input('year', now()->year); // Get the year from the request or default to the current year
        
        // Get the events the user has joined in the given year, grouped by month
        $eventsJoinedPerMonth = EventParticipant::selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
            ->where('user_id', $id) 
            ->where('status_id', 1) 
            ->whereYear('updated_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare the monthly data with default 0 values for all 12 months
        $monthlyData = array_fill(1, 12, 0);
        foreach ($eventsJoinedPerMonth as $event) {
            $monthlyData[$event->month] = $event->total;
        }

        // Return data in JSON format
        return response()->json([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData)
        ]);
    }

    public function getEventsCreatedData(Request $request, $id)
    {
        $year = $request->input('year', now()->year);
        
        // Fetch the events created by the admin in the given year
        $eventsCreated = UserEvent::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('user_id', $id) // Admin's ID
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->get();

        $monthlyData = array_fill(1, 12, 0); // Initialize all months with 0
        foreach ($eventsCreated as $event) {
            $monthlyData[$event->month] = $event->total;
        }

        return response()->json([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData),
        ]);
    }

    public function getEventsJoinedData(Request $request, $id)
    {
        $year = $request->input('year', now()->year);

        // Fetch the events joined by the admin in the given year
        $eventsJoined = EventParticipant::selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
            ->where('user_id', $id) // Admin's ID
            ->where('status_id', 1) // Only confirmed participations
            ->whereYear('updated_at', $year)
            ->groupBy('month')
            ->get();

        $monthlyData = array_fill(1, 12, 0); // Initialize all months with 0
        foreach ($eventsJoined as $event) {
            $monthlyData[$event->month] = $event->total;
        }

        return response()->json([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData),
        ]);
    }
    ///////////////////////////////////////////////////////////////////////////////////////

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