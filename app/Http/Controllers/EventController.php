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
use App\Models\Region;
use App\Models\Province;
use App\Models\EvaluationForm;
use App\Models\EventEvaluationForm;
use App\Models\EventCertificate;
use App\Events\UserAcceptedToEvent;
use Illuminate\View\View;
use App\Events\UserDeniedFromEvent;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function create(): View
    {
        return view('event.create');
    }

    public function list(Request $request)
    {
        $query = Event::query();
    
        // Check if date filter is applied
        if ($request->has('date') && $request->date != '') {
            // Parse the date input
            $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
            // Filter events occurring on the selected date
            $query->whereDate('date', '=', $selectedDate);
        }
    
        // Check if the user wants to hide old events
        if ($request->has('hide_old') && $request->hide_old == 'true') {
            $now = Carbon::now();
            $query->where(function ($query) use ($now) {
                $query->where('date', '>', $now->toDateString()) // Future events
                      ->orWhere(function ($query) use ($now) {
                          // Events happening today but still ongoing
                          $query->where('date', '=', $now->toDateString())
                                ->where('end_time', '>', $now->toTimeString());
                      });
            });
        }
    
        $query->orderBy('date', 'asc')
              ->orderBy('start_time', 'asc');
    
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
        $eventIds = UserEvent::where('user_id', $userId)
                                ->whereHas('user')
                                ->pluck('event_id');

        // Start the event query
        $query = Event::whereIn('id', $eventIds);

        // Check if a date filter is present in the request
        if ($request->has('date') && $request->date != '') {
            $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
            $query->whereDate('date', '=', $selectedDate);
        }
        $query->orderBy('date', 'asc')->orderBy('start_time', 'asc');

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
        $userevent = UserEvent::with('user')->where('event_id', $id)->whereHas('user')->firstOrFail();
        $currentParticipants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1)
            ->whereHas('user')
            ->count();
        $event = Event::findOrFail($id);


        $certificate = Certificate::where('event_id', $id)->first();

        //this is for the current user joining the event
        $eventParticipant = EventParticipant::where('event_id', $id)
            ->where('user_id', Auth::user()->id)
            ->whereHas('user')
            ->first();

        //overall participants
        $participants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1)
            ->whereHas('user')
            ->get();
        $currentUser = Auth::user()->id;
        $existingForms = EvaluationForm::where('status_id', 1)
            ->where('created_by', Auth::id())
            ->get();
        $hasAnswered = false;

        $pendingParticipantsCount = EventParticipant::where('event_id', $event->id)
        ->where('status_id', 3) // Assuming status_id 3 represents pending status
        ->whereHas('user')
        ->count();
        $evaluationForm = $event->evaluationForm;
        if ($evaluationForm) {
            $questions = Question::where('form_id', $evaluationForm->form_id)->pluck('id');
                
                
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
        //user age chart in the event
        $usersBirthdate = User::whereHas('eventParticipant', function ($query) use ($id) {
            $query->where('event_id', $id);
            })->select('birthdate')->get();

        $userAges = $usersBirthdate->map(function ($user) {
            return $user->birthdate ? Carbon::parse($user->birthdate)->age : 'N/A';
        });

        $ageRanges = [
            'Under 18' => $userAges->filter(function ($age) { return $age !== 'N/A' && $age < 18; })->count(),
            '18-24' => $userAges->filter(function ($age) { return $age !== 'N/A' && $age >= 18 && $age <= 24; })->count(),
            '25-34' => $userAges->filter(function ($age) { return $age !== 'N/A' && $age >= 25 && $age <= 34; })->count(),
            '35-44' => $userAges->filter(function ($age) { return $age !== 'N/A' && $age >= 35 && $age <= 44; })->count(),
            '45-54' => $userAges->filter(function ($age) { return $age !== 'N/A' && $age >= 45 && $age <= 54; })->count(),
            '55+'   => $userAges->filter(function ($age) { return $age !== 'N/A' && $age >= 55; })->count(),
            'N/A'   => $userAges->filter(function ($age) { return $age === 'N/A'; })->count(),
        ];

        $userAgeData = [
            'labels' => array_keys($ageRanges),
            'values' => array_values($ageRanges),
        ];

        //user gender chart in the event
        $userGenders = User::whereHas('eventParticipant', function ($query) use ($id) {
            $query->where('event_id', $id);
        })
        ->select('gender')
        ->get()
        ->groupBy(function ($user) {
            return $user->gender ?? 'N/A'; // Use 'N/A' if gender is null
        })
        ->map(function ($users) {
            return $users->count();
        });

        $genderLabels = $userGenders->keys();  // Extract the gender labels
        $genderCounts = $userGenders->values(); // Extract the gender counts
        

        // Group participants by the region_id of their associated user
        $regionData = $participants->groupBy(function ($participant) {
            return $participant->user->region_id; // Access region_id through the related user
        })->map(function ($region) {
            return $region->count();
        });

        // Group participants by the province_id of their associated user
        $provinceData = $participants->groupBy(function ($participant) {
            return $participant->user->province_id; // Access province_id through the related user
        })->map(function ($province) {
            return $province->count();
        });
        $regions = Region::whereIn('id', $regionData->keys())->pluck('regDesc', 'id');
        $provinces = Province::whereIn('id', $provinceData->keys())->pluck('provDesc', 'id');
        return view('event.event', [
            'event' => $event,
            'userevent' => $userevent,
            'participant' => $eventParticipant,
            'evaluationForm' => $evaluationForm,
            'existingForms' =>$existingForms,
            'currentParticipants' => $currentParticipants,
            'hasAnswered' => $hasAnswered,
            'certificate' => $certificate,
            'participants' => $participants,
            'currentUser' =>$currentUser,
            'userAgeData' => $userAgeData,
            'genderLabels' => $genderLabels,
            'genderCounts' => $genderCounts,
            'pendingParticipantsCount' => $pendingParticipantsCount,
            'regionData' => $regionData,
            'provinceData'=> $provinceData,
            'regions' => $regions,
            'provinces' => $provinces
        ]);
    }


    public function store(Request $request): RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'mode' => ['required', 'string', 'in:onsite,virtual'],
            'address' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'capacity' => ['required', 'integer', 'min:1'],
            'event_banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);

        // Start a database transaction
        DB::beginTransaction();
        try {
            // Handle file upload if present
            $relativePath = null;
            if ($request->hasFile('event_banner')) {
                $file = $request->file('event_banner');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $relativePath = 'storage/images/event_banners/' . $filename;
                // Move the uploaded file to the desired directory
                $file->storeAs('/images/event_banners', $filename);
            }

            // Create the event record
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

            // Create a user-event record
            UserEvent::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('event.view', $event->id)->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if any errors occur
            DB::rollBack();

            // Optionally delete the uploaded file if something goes wrong
            if ($relativePath && Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }

            // Log the error (optional) and redirect back with an error message
            \Log::error('Error creating event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create event. Please try again.');
        }
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
    
        // Start a database transaction
        DB::beginTransaction();
    
        try {
            // Validate the request data
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
                $file->storeAs('/images/event_banners', $filename);
                $validatedData['event_banner'] = $relativePath;
            } else {
                // Remove event_banner from validated data if no new file is uploaded
                unset($validatedData['event_banner']);
            }
    
            // Update the event with validated data
            $event->fill($validatedData);
            $event->save();
    
            // Commit the transaction
            DB::commit();
    
            return back()->with('status', 'event-updated')->with('success', 'Event updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
    
            // Optionally log the error
            \Log::error('Error updating event: ' . $e->getMessage());
    
            // Redirect with an error message
            return Redirect::route('event.edit', ['id' => $id])->with('error', 'Failed to update event');
        }
    }


    //join event
    public function join($id)
    {
        $event = Event::findOrFail($id);

        // Check if the user is already a participant
        $participant = EventParticipant::where('user_id', Auth::user()->id)
            ->where('event_id', $event->id)
            ->whereHas('user')
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
                ->whereHas('user')
                ->paginate(10);

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


        $participant = EventParticipant::where('user_id', $participantId)
                                        ->whereHas('user');
        // Update the participant's status
        $participant->update(['status_id' => $request->status_id]);
        $user = User::findOrFail($participantId);
        $eventDetails = Event::findOrFail($eventId);

        if ($request->status_id == 1) {
            // User accepted
            event(new UserAcceptedToEvent($user, $eventDetails));
        } elseif ($request->status_id == 2) {
            // User denied (assuming status_id 2 means 'denied')
            event(new UserDeniedFromEvent($user, $eventDetails));
        }

        

        return back()->with('success', 'Participant status updated successfully.');

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
