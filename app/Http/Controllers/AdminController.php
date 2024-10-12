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
        $totalCreatedEvents = UserEvent::where('user_id', $user)->count();
        $totalCreatedEvalForm = EvaluationForm::where('created_by', $user)->count();
        $totalJoinedEvent = EventParticipant::where('user_id', $user)->count();
        $totalCertReceived = CertUser::where('user_id', $user)->count();
        return view('admin.dashboard', compact('user', 'totalCreatedEvents', 'totalCreatedEvalForm', 'totalJoinedEvent', 'totalCertReceived'));
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
