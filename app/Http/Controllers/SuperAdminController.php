<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RegisterAdmin;
class SuperAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;

        return view('super_admin.dashboard',compact('user'));
    }

    public function userlist()
    {
        $users = User::all();

        return view('super_admin.userlist', compact('users'));
    }

    public function viewRequestingAdmins()
    {
        $users = RegisterAdmin::all();

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

}
