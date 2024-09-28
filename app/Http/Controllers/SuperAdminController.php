<?php

namespace App\Http\Controllers;

use App\Models\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Models\RegisterAdmin;
use App\Models\Event;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
class SuperAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;

        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalCreatedEvents = UserEvent::where('user_id', $user)->count();
        
        $userCount = User::where('role_id', '3')->count();
        $adminCount = User::where('role_id', '2')->count();
        $superAdminCount = User::where('role_id', '1')->count();

        $maleGender = User::where('gender', 'male')->count();
        $femaleGender = User::where('gender', 'female')->count();
        $unknownGender = User::where('gender', null)->count();

        // Data to pass to the view
        $userCountData = [
            'labels' => ['User', 'Admin', 'Super Admin'],
            'values' => [$userCount, $adminCount, $superAdminCount]
        ];

        $genderData = [
            'labels' => ['Male', 'Female', 'N/A'],
            'values' => [$maleGender, $femaleGender, $unknownGender]
        ];

        return view('super_admin.dashboard',compact('user', 'totalUsers', 'totalEvents', 'userCountData','totalCreatedEvents','genderData'));
    }

    public function userlist()
    {
        $users = User::all();

        return view('super_admin.userlist', compact('users'));
    }

    public function viewRequestingAdmins()
    {
        $users = RegisterAdmin::where('status_id', 3)->get();

        return view('super_admin.requestingAdmins', compact('users'));
    }

    public function handleAdminRequest($id, $action)
    {
        // Find the RegisterAdmin entry
        $registerAdmin = RegisterAdmin::find($id);
        
        if ($registerAdmin) {
            // Check if action is 'accept'
            if ($action === 'accept') {
                $user = User::find($registerAdmin->user_id);
                $user->role_id = 2;
                $user->save();

                // Update the status_id in RegisterAdmin to 1 (accepted)
                $registerAdmin->status_id = 1;
                $registerAdmin->save();
                return redirect()->back()->with('success', 'User accepted as admin');
            }

            // Check if action is 'decline'
            if ($action === 'decline') {
                // Update the status_id in RegisterAdmin to 2 (declined)
                $registerAdmin->status_id = 2;
                $registerAdmin->save();
                return redirect()->back()->with('success', 'User declined');
            }

            // Save the changes
            $registerAdmin->save();
        }

        return redirect()->back()->with('error', 'Invalid action');
    }

    public function usercreate(): View
    {
        return view('super_admin.createuser');
    }

    public function storeuser(Request $request): RedirectResponse
    {
        // Validate the input fields
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,  // Allow the super admin to choose the role if necessary
        ]);

        // Optionally, you could trigger the registered event to send email verification, but this is not needed if you don't want to.
        // event(new Registered($user));

        // Redirect back to the user list
        return redirect()->route('profile.view', ['id' => $user->id])->with('success', 'User has been created successfully.');
    }
    


}
