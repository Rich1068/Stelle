<?php

namespace App\Http\Controllers;
use App\Http\Requests\EventUpdateRequest;
use App\Models\UserEvent;
use App\Models\EventParticipant;
use App\Models\Event;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function create(): View
    {
        return view('event.create');
    }

    public function list()
    {
    $events = Event::paginate(10); // Fetch 10 events per page

    return view('event.eventlist', compact('events'));
    }

    public function myEventlist()
    {
        $userId = Auth::user()->id;

        // Get event IDs where the user is the creator
        $eventIds = UserEvent::where('user_id', $userId)->pluck('event_id');

        // Fetch and paginate events using the retrieved event IDs
        $events = Event::whereIn('id', $eventIds)->paginate(10);

        return view('event.myeventlist', compact('events'));
    }

    public function view($id): View
    {
        $userevent = UserEvent::with('user')->where('event_id', $id)->firstOrFail();
        $currentParticipants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1) // Assuming 'Accepted' has an id of 1
            ->count();
        $event = Event::findOrFail($id);
        $eventParticipant = EventParticipant::where('event_id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();
    
        return view('event.event', [
            'event' => $event,
            'userevent' => $userevent,
            'participant' => $eventParticipant,
            'currentParticipants' => $currentParticipants,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'mode' => ['required', 'string', 'in:onsite,virtual'],
            'address' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'capacity' => ['required', 'integer', 'min:1'],
            'event_banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);
        if ($request->hasFile('event_banner')) {
            $file = $request->file('event_banner');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $relativePath = 'storage/images/event_banners/' . $filename;
            // Move the uploaded file to the desired directory
            $file->storeAs('/images/event_banners', $filename);
        
            // Update the profile picture path
            //$event->event_banner = $relativePath;
        }
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'address' => $request->address,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
            'event_banner' => $relativePath ?? $request->event_banner,
            'mode' => $request->mode
        ]);
        $event->save();

        $userEvent = UserEvent::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
        ]);
        
        

        return redirect()->route('event.view', $event->id)->with('success', 'Event created successfully.');
    }

    //main view of update
    public function edit(Request $request, $id): View
    {
        // Find the event by its ID or fail
        $event = Event::findOrFail($id);

        // Return the edit view with the event data
        return view('event.edit', [
            'event' => $event,
        ]);
    }


    //update event info
    public function update(EventUpdateRequest $request, $id): RedirectResponse
    {
        $event = Event::findOrFail($id);
        // Validation rules
        try {
            $validatedData = $request->validated();

            // Check if the remove event banner checkbox is checked
            if ($request->has('remove_event_banner') && $request->remove_event_banner) {
                $oldfile = $event->event_banner;
                if (!empty($event->event_banner) && File::exists($oldfile)) {
                    File::delete($oldfile);
                }
                $validatedData['event_banner'] = null;
            } elseif ($request->hasFile('event_banner')) {
                // Handle the new event banner file upload
                $file = $request->file('event_banner');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $relativePath = 'storage/images/event_banners/' . $filename;
                $oldfile = $event->event_banner;
                if (!empty($event->event_banner) && File::exists($oldfile)) {
                    File::delete($oldfile);
                }

                // Move the uploaded file to the desired directory
                $path = $file->storeAs('/images/event_banners', $filename);

                // Update the event banner path in the validated data
                $validatedData['event_banner'] = $relativePath;
            } else {
                // Remove event_banner from validated data if no new file is uploaded
                unset($validatedData['event_banner']);
            }

            // Fill and save the event with the validated data
            $event->fill($validatedData);
            $event->save();
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return Redirect::route('event.edit', ['id' => $id])->with('error', 'Failed to update event');
        }

        // Redirect to a route, for example, the event view page
        return back()->with('status', 'event-updated');
    }


    //join event
    public function join($id)
{
    $event = Event::findOrFail($id);

    // Check if the user is already a participant
    $participant = EventParticipant::where('user_id', Auth::user()->id)
        ->where('event_id', $event->id)
        ->first();

    if (!$participant) {
        // Count the number of accepted participants
        $acceptedParticipants = EventParticipant::where('event_id', $event->id)
            ->where('status_id', 1) // Assuming 'Accepted' has an id of 1
            ->count();

        // Check if the event capacity is reached
        if ($acceptedParticipants >= $event->capacity) {
            return redirect()->route('event.view', $event->id)->with('error', 'The event has reached its capacity.');
        }

        // Create new EventParticipant record with status 'Pending'
        EventParticipant::create([
            'user_id' => Auth::user()->id,
            'event_id' => $event->id,
            'participant_status_id' => 3, // Assuming 'Pending' has an id of 3
        ]);

        return redirect()->route('event.view', $event->id)->with('success', 'You have requested to join the event!');
    }

    return redirect()->route('event.view', $event->id)->with('error', 'You have already requested to join this event.');
}


    public function showParticipants($id)
    {
        $eventuser = UserEvent::findOrFail($id);
        $event = Event::findOrFail($id);
        // Check if the logged-in user is the creator of the event
        if ($eventuser->user_id !== Auth::id()) {
            return redirect()->route('unauthorized')->with('error', 'You do not have permission to view this page.');
        }

        $participants = EventParticipant::where('event_id', $id)->get();

        return view('event.participants', compact('eventuser', 'participants', 'event'));
    }

    public function updateParticipantStatus(Request $request, $eventId, $participantId)
    {
        $event = UserEvent::findOrFail($eventId);
        $request->validate([
            'status_id' => ['required', 'string'],
        ]);
        // Check if the logged-in user is the creator of the event
        if ($event->user_id != Auth::id()) {
            return redirect()->route('unauthorized')->with('error', 'You do not have permission to perform this action.');
        }


        $participant = EventParticipant::where('user_id', $participantId);

        // Update the participant's status
        $participant->update(['status_id' => $request->status_id]);

        return back()->with('success', 'Participant status updated successfully.');

    }
}
