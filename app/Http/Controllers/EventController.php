<?php

namespace App\Http\Controllers;
use App\Http\Requests\EventUpdateRequest;
use App\Models\UserEvent;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Models\User;
use App\Models\Certificate;
use App\Models\Question;
use App\Models\Answer;
use App\Models\CertUser;
use Illuminate\View\View;
use Carbon\Carbon;
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

    public function list(Request $request)
    {
        // Start with the Event query
        $query = Event::query();
    
        // Check if date filter is applied
        if ($request->has('date') && $request->date != '') {
            // Parse the date input
            $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
    
            // Filter events occurring on the selected date
            $query->whereDate('date', '=', $selectedDate);
        }
    
        // Paginate the results (10 per page)
        $events = $query->paginate(10);
    
        // Check if this is an AJAX request (for filtering without reloading the page)
        if ($request->ajax()) {
            return response()->json([
                'eventsHtml' => view('event.partials.eventlist', compact('events'))->render(),
                'paginationHtml' => $events->links('vendor.pagination.custom1')->render(),
            ]);
        }
    
        // Return the regular view with events
        return view('event.eventlist', compact('events'));
    }

    public function myEventlist(Request $request)
    {
        $userId = Auth::id(); // Use Auth::id() to get the currently logged-in user's ID

        // Get event IDs where the user is the creator
        $eventIds = UserEvent::where('user_id', $userId)->pluck('event_id');

        // Start the event query
        $query = Event::whereIn('id', $eventIds);

        // Check if a date filter is present in the request
        if ($request->has('date') && $request->date != '') {
            $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
            $query->whereDate('date', '=', $selectedDate);
        }

        // Paginate the results (10 per page)
        $events = $query->paginate(10);

        // Check if the request is an AJAX request (for filtering without reloading)
        if ($request->ajax()) {
            return response()->json([
                'eventsHtml' => view('event.partials.myeventlist', compact('events'))->render(),
                'paginationHtml' => $events->links('vendor.pagination.custom1')->render(),
            ]);
        }

        return view('event.myeventlist', compact('events'));
    }
    
    public function view($id): View
    {
        $userevent = UserEvent::with('user')->where('event_id', $id)->firstOrFail();
        $currentParticipants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1) // Only accepted will show
            ->count();
        $event = Event::findOrFail($id);
        $certificate = Certificate::where('event_id', $id)->first();
        $evaluationForm = $event->evaluationForm;
        $eventParticipant = EventParticipant::where('event_id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $participants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1)
            ->get();
        $currentUser = Auth::user()->id;

        $hasAnswered = false;
        if ($evaluationForm) {
            $questions = Question::where('form_id', $evaluationForm->id)->pluck('id');
            
            
            foreach ($questions as $question) {
                $answer = Answer::where('question_id', $question)
                    ->where('user_id', Auth::user()->id)
                    ->exists();
                if ($answer) {
                    $hasAnswered = true;
                    break;
                }
            }
        }

        return view('event.event', [
            'event' => $event,
            'userevent' => $userevent,
            'participant' => $eventParticipant,
            'evaluationForm' => $evaluationForm,
            'currentParticipants' => $currentParticipants,
            'hasAnswered' => $hasAnswered,
            'certificate' => $certificate,
            'participants' => $participants,
            'currentUser' =>$currentUser
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
                $validatedData['event_banner'] = $relativePath;
            } else {
                // Remove event_banner from validated data if no new file is uploaded
                unset($validatedData['event_banner']);
            }

            $event->fill($validatedData);
            $event->save();
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return Redirect::route('event.edit', ['id' => $id])->with('error', 'Failed to update event');
        }

        // Redirect to a route, for example, the event view page
        return back()->with('status', 'event-updated')->with('success', 'Event updated successfully');
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
                'status_id' => 3, // Assuming 'Pending' has an id of 3
            ]);

            return redirect()->route('event.view', $event->id)->with('success', 'You have requested to join the event!');
        }

        return redirect()->route('event.view', $event->id)->with('error', 'You have already requested to join this event.');
    }

    public function showPendingParticipants($id)
    {
        $eventuser = UserEvent::findOrFail($id);
        $event = Event::findOrFail($id);
        // Check if the logged-in user is the creator of the event
        if ($eventuser->user_id !== Auth::id()) {
            return redirect()->route('unauthorized')->with('error', 'You do not have permission to view this page.');
        }

        $participants = EventParticipant::where('event_id', $id)
                ->where('status_id', 3)
                ->get();

        return view('event.pendingparticipants', compact('eventuser', 'participants', 'event'));
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
    public function sendCertificates(Request $request, $eventId)
    {
        // Validate the incoming request
        $request->validate([
            'participants' => 'required|array',  // Ensure we have an array of selected participants
            'participants.*' => 'exists:users,id',  // Validate that each participant ID exists in the users table
        ]);

        $userIds = $request->input('participants');  // Get the selected participant IDs

        // Get the template certificate for the event
        $certificate = Certificate::where('event_id', $eventId)->first();

        if (!$certificate) {
            return redirect()->back()->with('error', 'Certificate design not found for this event.');
        }

        // Loop through each selected user and generate their certificate
        foreach ($userIds as $userId) {
            $user = User::findOrFail($userId);  // Get user by ID
            $fullname = $user->first_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name;

            // Load the certificate design JSON and personalize it for this user
            $canvasData = json_decode($certificate->design, true);
            $personalizedCanvas = $this->replacePlaceholdersInDesign($canvasData, $fullname);

            // Convert the modified canvas to a base64 image (use front-end toDataURL)
            $imageData = $request->input('image');  // Personalized image data

            // Save the personalized certificate image
            $personalizedImagePath = $this->savePersonalizedCertificateImage($imageData, $eventId, $fullname);

            // Save the personalized certificate in the 'cert_users' table
            CertUser::create([
                'user_id' => $userId,
                'cert_id' => $certificate->id,
                'cert_path' => $personalizedImagePath,  // Save the personalized certificate path
            ]);
        }

        return redirect()->back()->with('success', 'Personalized certificates generated successfully!');
    }

    public function getParticipants($event_id)
    {
        // Fetch the participants for the given event_id and concatenate user names
        $participants = EventParticipant::where('event_id', $event_id)
            ->with(['user']) // Eager load the related user
            ->get()
            ->map(function ($participant) {
                // Concatenate user's first, middle, and last name
                $fullName = $participant->user->first_name . ' ' .
                            ($participant->user->middle_name ?? '') . ' ' .
                            $participant->user->last_name;
    
                // Return the full name
                return [
                    'full_name' => trim($fullName),
                    'user_id' => $participant->user->id // Include the user_id here
                ];
            });
    
        // Return the participant full names as a JSON response
        return response()->json($participants);
    }









    private function replacePlaceholdersInDesign($canvasData, $userName)
    {
        // Replace the {{name}} placeholder with the actual user's name
        array_walk_recursive($canvasData, function (&$item) use ($userName) {
            $item = str_replace('{{name}}', $userName, $item);
        });

        return $canvasData;
    }

    // Helper function to save personalized certificates
    private function savePersonalizedCertificateImage($imageData, $eventId, $userName)
    {
        // Validate the image data format
    if (strpos($imageData, ',') === false) {
        Log::error('Invalid image data format, missing comma');
        return response()->json(['message' => 'Invalid image data format'], 400);
    }

    // Split the base64 string into two parts
    $imageParts = explode(',', $imageData);

    // Check if there are exactly two parts (data prefix and the base64-encoded image)
    if (count($imageParts) < 2) {
        Log::error('Invalid image data format, less than 2 parts');
        return response()->json(['message' => 'Invalid image data format'], 400);
    }

    // Get the base64-encoded image (the second part)
    $imageBase64 = $imageParts[1];

    // Generate a unique name for the personalized certificate
    $imageName = 'certificate_' . $eventId . '_' . Str::slug($userName) . '.png';
    $relativePath = 'storage/images/certificates/' . $imageName;
    $imagePath = storage_path('app/public/images/certificates/' . $imageName);

    // Save the base64-decoded image to the file system
    try {
        file_put_contents($imagePath, base64_decode($imageBase64));
    } catch (\Exception $e) {
        Log::error('Error saving the image: ' . $e->getMessage());
        return response()->json(['message' => 'Error saving image'], 500);
    }

    // Return the relative path for saving in the database
    return $relativePath;
    }


    public function getCalendarEvents(Request $request)
{
    // Check the request for a filter parameter, defaults to 'all' if not present
    $filter = $request->input('filter', 'all');
    
    // Get the current authenticated user
    $userId = Auth::id();

    // Filter events based on the filter option
    if ($filter === 'own') {
        // Fetch events created by the authenticated user
        $events = Event::with('userEvent')
            ->whereHas('userEvent', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
    } elseif ($filter === 'join') {
        $events = Event::with('participants')
            ->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();
    } else {
        // Fetch all events
        $events = Event::with('userEvent')->get();
    }

    $formattedEvents = [];

    foreach ($events as $event) {
        $userEvent = $event->userEvent;
        $user = $userEvent->user ?? null;

        $formattedEvents[] = [
            'id' => $event->id,
            'title' => $event->title,
            'start' => $event->date . ' ' . $event->start_time,
            'end' => $event->date . ' ' . $event->end_time,
            'extendedProps' => [
                'description' => $event->description,
                'location' => $event->address,
                'mode' => $event->mode,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'first_name' => $user->first_name ?? 'Unknown',
                'middle_name' => $user->middle_name ?? '',
                'last_name' => $user->last_name ?? 'Unknown',
            ],
        ];
    }

    return response()->json($formattedEvents);
}

}
