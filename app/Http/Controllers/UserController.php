<?php

namespace App\Http\Controllers;

use App\Models\EventParticipant;
use App\Models\CertUser;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $eventsAttendedTotal = EventParticipant::where('user_id', $user)
                                                ->where('status_id', 1)
                                                ->count();
        $totalCertificates = CertUser::where('user_id', $user)
                                    ->count();
        
        $currentYear = now()->year;

        $totalEvalAnswered = Answer::where('user_id', $user)
                            ->distinct('event_form_id') 
                            ->count('event_form_id'); 

        // Eloquent query to get the count of participants who attended (status_id = 1) per month
        $eventsJoinedPerMonth = EventParticipant::selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
                                ->where('user_id', $user)
                                ->where('status_id', 1) // Assuming status_id 1 means "joined"
                                ->whereYear('updated_at', $currentYear)
                                ->groupBy('month')
                                ->orderBy('month')
                                ->get();

        // Prepare data for the chart
        $monthlyData = array_fill(1, 12, 0);

        foreach ($eventsJoinedPerMonth as $event) {
            $monthlyData[$event->month] = $event->total;
        }
    
        $monthlyEventsData = [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData),
        ];


        return view('user.dashboard', compact('user', 'eventsAttendedTotal', 'totalCertificates', 'monthlyEventsData', 'currentYear', 'totalEvalAnswered'));
    }

    public function getEventsData(Request $request)
    {
        $userId = Auth::user()->id;
        $year = $request->input('year', now()->year);

        // Query for events joined per month for the given year
        $eventsJoinedPerMonth = EventParticipant::selectRaw('MONTH(updated_at) as month, COUNT(*) as total')
            ->where('user_id', $userId)
            ->where('status_id', 1)
            ->whereYear('updated_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyData = array_fill(1, 12, 0);
        foreach ($eventsJoinedPerMonth as $event) {
            $monthlyData[$event->month] = $event->total;
        }

        return response()->json([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'values' => array_values($monthlyData)
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

    public function help()
    {
        return view('help');
    }
}
