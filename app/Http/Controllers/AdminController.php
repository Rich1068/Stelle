<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\UserEvent;
use App\Models\EvaluationForm;
use App\Models\CertUser;
use App\Models\EventParticipant;
class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $currentYear = now()->year;

        $totalCreatedEvents = UserEvent::where('user_id', $user)->count();
        $totalCreatedEvalForm = EvaluationForm::where('created_by', $user)->count();
        $totalJoinedEvent = EventParticipant::where('user_id', $user)->count();
        $totalCertReceived = CertUser::where('user_id', $user)->count();

        $monthlyEventsData = UserEvent::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', $currentYear)
        ->where('user_id', $user) // Filter by admin's ID
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Initialize monthly data with 0 for all 12 months
        $monthlyData = array_fill(1, 12, 0);

        foreach ($monthlyEventsData as $event) {
            $monthlyData[$event->month] = $event->total;
        }

        $chartData = [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData),
        ];


        return view('admin.dashboard', compact('user', 'totalCreatedEvents', 'totalCreatedEvalForm', 'totalJoinedEvent', 'totalCertReceived', 'chartData', 'currentYear'));
    }

    public function getAdminCreatedEventsData(Request $request)
    {
        $adminId = Auth::id(); // Get the admin's user ID
        $year = $request->input('year', date('Y')); // Get the year from request or default to the current year

        // Query for events created by the admin per month for the given year
        $eventsCreatedPerMonth = UserEvent::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('user_id', $adminId) // Filter events created by this admin
            ->whereYear('created_at', $year) // Filter by the selected year
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare monthly data with default 0 values
        $monthlyData = array_fill(1, 12, 0); // Initialize all 12 months with 0
        foreach ($eventsCreatedPerMonth as $event) {
            $monthlyData[$event->month] = $event->total;
        }

        // Return the data in JSON format
        return response()->json([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData)
        ]);
    }

    public function getPaginatedParticipantsPerEvent(Request $request)
    {
        $adminId = Auth::user()->id;
        $perPage = 10; // Number of events per page
        $page = $request->input('page', 1); // Current page

        // Fetch events created by admin, along with the count of participants for each event
        $events = UserEvent::with('event') // Fetch the associated event
            ->where('user_id', $adminId)
            ->paginate($perPage, ['*'], 'page', $page);

        $eventNames = [];
        $participantsCount = [];

        foreach ($events as $userEvent) {
            $event = $userEvent->event;
            $eventNames[] = $event->title;

            // Count participants using the EventParticipant relationship
            $participantCount = EventParticipant::where('event_id', $event->id)->where('status_id', 1)->count();
            $participantsCount[] = $participantCount;
        }

        return response()->json([
            'labels' => $eventNames,
            'values' => $participantsCount,
            'current_page' => $events->currentPage(),
            'last_page' => $events->lastPage(),
        ]);
    }




    public function getEvents()
    {
        $events = Event::with('userEvent')->get(); // Fetch events with related user information

        $formattedEvents = [];

        foreach ($events as $event) {
            // Check if the event has a userEvent and if userEvent has a user
            $userEvent = $event->userEvent;
            $user = $userEvent->user ?? null; // Safely access the user, if it exists

            $formattedEvents[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->date . ' ' . $event->start_time, // Combine date and start time
                'end' => $event->date . ' ' . $event->end_time, // Combine date and end time
                'extendedProps' => [
                    'description' => $event->description,
                    'location' => $event->address,
                    'mode' => $event->mode,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    // If the user exists, use their details, otherwise use 'Unknown'
                    'first_name' => $user->first_name ?? 'Unknown',
                    'middle_name' => $user->middle_name ?? '',
                    'last_name' => $user->last_name ?? 'Unknown',
                ],
            ];
        }

        return response()->json($formattedEvents); // Return the formatted events in JSON
    }
}
