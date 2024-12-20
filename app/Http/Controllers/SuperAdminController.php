<?php

namespace App\Http\Controllers;

use App\Models\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Models\RegisterAdmin;
use App\Models\EventParticipant;
use App\Models\Event;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
class SuperAdminController extends Controller
{
    public function index()
    {
        $currentYear = date('Y');
        $user = Auth::user()->id;

        // General statistics
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalCreatedEvents = UserEvent::where('user_id', $user)->count();
        $totalJoinedEvents = EventParticipant::where('user_id', $user)->count();

        //get total per user role
        $userCount = User::where('role_id', '3')->count();
        $adminCount = User::where('role_id', '2')->count();
        $superAdminCount = User::where('role_id', '1')->count();

        //get gender totals
        $maleGender = User::where('gender', 'male')->count();
        $femaleGender = User::where('gender', 'female')->count();
        $unknownGender = User::where('gender', null)->count();

        // Data to pass to the view for the role chart
        $userCountData = [
            'labels' => ['User', 'Admin', 'Super Admin'],
            'values' => [$userCount, $adminCount, $superAdminCount]
        ];
        // get the events per month
        $events = Event::selectRaw('MONTH(start_date) as month, COUNT(*) as total')
        ->whereYear('start_date', $currentYear)
        ->groupBy('month')
        ->get();

        // Prepare data for the chart
        $monthlyData = array_fill(1, 12, 0); // Initialize all months with 0
        foreach ($events as $event) {
            $monthlyData[$event->month] = $event->total;
        }
        //get the labels per month
        $monthlyEventsData = [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData)
        ];
        //get users registering per month
        $monthlyUsersData = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year) // For the current year
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['month'] => $item['count']];
        });
        $monthlyUsers = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyUsers[] = $monthlyUsersData[$i] ?? 0; // Set to 0 if no data for the month
        }
        

        $genderData = [
            'labels' => ['Male', 'Female', 'N/A'],
            'values' => [$maleGender, $femaleGender, $unknownGender]
        ];

        return view('super_admin.dashboard',compact('user', 'totalUsers', 'totalEvents', 'userCountData','totalCreatedEvents','genderData', 'monthlyEventsData', 'currentYear', 'monthlyUsers', 'totalJoinedEvents'));
    }

    public function getEventsData(Request $request)
    {
        // Get the year from the request, or default to the current year
        $year = $request->input('year', date('Y'));

        // Get event data for the specified year
        $events = Event::selectRaw('MONTH(start_date) as month, COUNT(*) as total')
            ->whereYear('start_date', $year)
            ->groupBy('month')
            ->get();

        // Prepare the data for the chart (showing all months, even if empty)
        $monthlyData = array_fill(1, 12, 0); // Initialize all months with 0
        foreach ($events as $event) {
            $monthlyData[$event->month] = $event->total;
        }

        $chartData = [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData)
        ];

        return response()->json($chartData);
    }

    public function getUsersDataByYear(Request $request)
    {
        $year = $request->input('year', now()->year); // Get the year from the request, default to current year

        // Fetch the number of users created each month in the specified year
        $monthlyUsersData = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item['month'] => $item['count']];
            });

        // Fill in months with 0 if no data exists
        $monthlyUsers = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyUsers[] = $monthlyUsersData[$i] ?? 0; // Set to 0 if no data for the month
        }

        return response()->json([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => $monthlyUsers
        ]);
    }

    public function getPaginatedParticipantsPerEvent(Request $request)
    {
        $superadminId = Auth::user()->id;
        $perPage = 10; // Number of events per page
        $page = $request->input('page', 1); // Current page

        // Fetch events created by admin, ignoring soft-deleted ones
        $events = UserEvent::with(['event' => function ($query) {
            $query->whereNull('deleted_at'); // Exclude soft-deleted events
        }])
        ->where('user_id', $superadminId)
        ->paginate($perPage, ['*'], 'page', $page);

        $eventNames = [];
        $participantsCount = [];

        foreach ($events as $userEvent) {
            $event = $userEvent->event;

            // Skip if event is null (soft-deleted)
            if ($event === null) {
                continue;
            }

            $eventNames[] = $event->title;

            // Count participants using the EventParticipant relationship
            $participantCount = EventParticipant::where('event_id', $event->id)
                ->where('status_id', 1)
                ->count();

            $participantsCount[] = $participantCount;
        }

        return response()->json([
            'labels' => $eventNames,
            'values' => $participantsCount,
            'current_page' => $events->currentPage(),
            'last_page' => $events->lastPage(),
        ]);
    }

    public function userlist()
    {
        $users = User::withTrashed()->get();
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
            'password' => ['required', 'confirmed', Rules\Password::defaults() // starts with default rules
            ->min(8)          
            ->mixedCase()     
            ->letters()       
            ->numbers()       
            ->symbols()       
            ->uncompromised()],
        ]);

        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,  // Allow the super admin to choose the role if necessary
        ]);



        // Redirect back to the user list
        return redirect()->route('profile.view', ['id' => $user->id])->with('success', 'User has been created successfully.');
    }
    public function allEventList()
    {
        $events = Event::withTrashed()
            ->withCount(['participants as current_participants' => function ($query) {
                $query->where('status_id', 1); // Only count accepted participants
            }])
            ->with(['userEvent.user' => function ($query) {
                $query->withTrashed(); // Include soft-deleted users
            }])
            ->get();
    
        return view('super_admin.allEventList', compact('events'));
    }


}
