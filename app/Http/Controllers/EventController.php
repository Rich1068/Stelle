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
use App\Models\AttendanceLog;
use App\Models\EvaluationForm;
use App\Models\EventEvaluationForm;
use App\Models\EventCertificate;
use App\Events\UserAcceptedToEvent;
use App\Events\UserRemovedFromEvent;
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
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    public function create(): View
    {
        return view('event.create');
    }

    public function list(Request $request)
    {
        $query = Event::query()->withCount([
            'participants as current_participants' => function ($query) {
                $query->where('status_id', 1)
                      ->whereHas('user', function ($userQuery) {
                        $userQuery->withTrashed();
                      });
            }
        ]);
        // Show only ongoing events by default
        if (!$request->has('show_all') || $request->show_all != 'true') {
            $now = Carbon::now();
            $query->where(function ($query) use ($now) {
                $query->where('start_date', '>=', $now->toDateString())
                    ->orWhere(function ($query) use ($now) {
                        $query->where('start_date', '=', $now->toDateString())
                            ->where('end_time', '>=', $now->toTimeString());
                    });
            });
        }

        // Apply date filter if selected
        if ($request->has('date') && $request->date != '') {
            $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
            $query->where(function ($query) use ($selectedDate) {
                $query->whereDate('start_date', '<=', $selectedDate)
                      ->whereDate('end_date', '>=', $selectedDate);
            });
        }

        // Order by start_date, end_date, and start_time
        $query->orderBy('start_date', 'asc')
            ->orderBy('end_date', 'asc')
            ->orderBy('start_time', 'asc');

        $events = $query->paginate(10);
        $hasEvents = $events->count() > 0;
        
        if ($request->ajax()) {
            return response()->json([
                'eventsHtml' => view('event.partials.eventlist', compact('events'))->render(),
                'paginationHtml' => $events->links('vendor.pagination.custom1')->render(),
                'hasEvents' => $hasEvents,
            ]);
        }

        return view('event.eventlist', compact('events'));
    }

    public function myEventlist(Request $request)
    {
        $userId = Auth::id();
    
        // Get event IDs where the user is the creator
        $eventIds = UserEvent::where('user_id', $userId)
                            ->whereHas('user')
                            ->pluck('event_id');
    
        // Start the event query
        $query = Event::whereIn('id', $eventIds)
        ->withCount([
            'participants as current_participants' => function ($query) {
                $query->where('status_id', 1)
                      ->whereHas('user', function ($userQuery) {
                        $userQuery->withTrashed();
                      });
            }
        ]);
    
        // Include deleted events if 'show_deleted' is set to true
        if ($request->has('show_deleted') && $request->show_deleted === 'true') {
            $query = $query->withTrashed();
        }
    
        // Show only ongoing events by default unless 'show_all' is set to true
        if (!$request->has('show_all') || $request->show_all != 'true') {
            $now = Carbon::now();
            $query->where(function ($query) use ($now) {
                $query->where('start_date', '>=', $now->toDateString())
                      ->orWhere(function ($query) use ($now) {
                          $query->where('start_date', '=', $now->toDateString())
                                ->where('end_time', '>=', $now->toTimeString());
                      });
            });
        }
    
        // Apply date filter if selected
        if ($request->has('date') && $request->date != '') {
            $selectedDate = Carbon::parse($request->date)->format('Y-m-d');
            $query->where(function ($query) use ($selectedDate) {
                $query->whereDate('start_date', '<=', $selectedDate)
                      ->whereDate('end_date', '>=', $selectedDate);
            });
        }
        // Order the results by start_date, end_date, and start_time
        $query->orderBy('start_date', 'asc')
              ->orderBy('end_date', 'asc')
              ->orderBy('start_time', 'asc');
    
        $events = $query->paginate(10);
        $hasEvents = $events->count() > 0;
    
        // Check if the request is an AJAX request (for filtering without reloading)
        if ($request->ajax()) {
            return response()->json([
                'eventsHtml' => view('event.partials.myeventlist', compact('events'))->render(),
                'paginationHtml' => $events->links('vendor.pagination.custom1')->render(),
                'hasEvents' => $hasEvents,
            ]);
        }
    
        return view('event.myeventlist', compact('events'));
    }
 //////////////////////////////////////////////////////////   
    public function view($id): View
    {
        $userevent = UserEvent::with(['user' => function ($query) {
            $query->withTrashed(); // Include soft-deleted users
        }])->where('event_id', $id)->first();
        $currentParticipants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1)
            ->count();
        $event = Event::withTrashed()->findOrFail($id);


        $certificate = Certificate::where('event_id', $id)->first();

        //this is for the current user joining the event
        $eventParticipant = EventParticipant::where('event_id', $id)
            ->where('user_id', Auth::user()->id)
            ->whereHas('user', function ($query) {
                $query->withTrashed(); // Include both active and trashed users
            })
            ->first();

        //overall participants
        $participants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1)
            ->whereHas('user', function ($query) {
                $query->withTrashed(); // Include soft-deleted users
            })
            ->with(['user' => function ($query) {
                $query->withTrashed(); // Include soft-deleted users in eager loading
            }])
            ->paginate(10);
        $currentUser = Auth::user()->id;
        $existingForms = EvaluationForm::where('status_id', 1)
            ->where('created_by', Auth::id())
            ->get();
        $hasAnswered = false;

        $pendingParticipantsCount = EventParticipant::where('event_id', $event->id)
        ->where('status_id', 3) // Assuming status_id 3 represents pending status
        ->whereHas('user', function ($query) {
            $query->withTrashed(); // Include both active and trashed users
        })
        ->count();
        $evaluationForm = $event->evaluationForm;
        if ($evaluationForm) {
            $hasAnswered = Answer::where('event_form_id', $evaluationForm->id)
            ->where('user_id', Auth::user()->id)
            ->exists();
        }
        $hasAnswers = false;
        if ($evaluationForm) {
        $hasAnswers = Answer::where('event_form_id', $evaluationForm->id)
                      ->exists();
        }
        //user age chart in the event
        $usersBirthdate = User::withTrashed() // Include soft-deleted users
            ->whereHas('eventParticipant', function ($query) use ($id) {
                $query->where('event_id', $id)->where('status_id', 1);
            })
            ->select('birthdate')
            ->get();

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
        $userGenders = User::withTrashed() // Include soft-deleted users
            ->whereHas('eventParticipant', function ($query) use ($id) {
                $query->where('event_id', $id)->where('status_id', 1);
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
        

        //region and province chart
        // Group participants by the region_id of their associated user, and handle null values
        $regionData = $participants->groupBy(function ($participant) {
            // Safely access region_id and regDesc
            $user = $participant->user;
            return $user && $user->region ? $user->region->regDesc : 'N/A';
        })->map(function ($region) {
            return $region->count();
        });

        // Group participants by the province_id of their associated user, and handle null values
        $provinceData = $participants->groupBy(function ($participant) {
            $user = $participant->user; // Check if the user relationship exists
            return $user && $user->province ? $user->province->provDesc : 'N/A'; // Use provDesc if available, fallback to 'N/A'
        })->map(function ($province) {
            return $province->count(); // Count participants in each province group
        });

        // Prepare the labels and counts for the charts
        $regionLabels = $regionData->keys();  // The region names (including 'N/A')
        $regionCounts = $regionData->values(); // The count of participants per region

        $provinceLabels = $provinceData->keys();  // The province names (including 'N/A')
        $provinceCounts = $provinceData->values(); // The count of participants per province

        //college chart
        $colleges = $participants->map(function ($participant) {
            // Safely check if the user relationship exists
            $user = $participant->user;
        
            // If the user is null, return 'N/A'
            if (!$user || $user->college === null || trim($user->college) === '') {
                return 'N/A'; // Use 'N/A' for participants with no college info
            }
        
            // Normalize the college name
            $college = strtolower(trim($user->college));
            return ucwords($college); // Capitalize first letter of each word
        })->groupBy(function ($college) {
            return $college; // Group by college name
        })->map(function ($group) {
            return $group->count(); // Count the occurrences of each college
        });
        $collegeLabels = $colleges->keys(); 
        $collegeCounts = $colleges->values();

        $attendanceLog = AttendanceLog::where('event_id', $id)
            ->distinct()
            ->pluck('user_id') // Get distinct user IDs of attendees
            ->toArray();

        return view('event.event', [
            'event' => $event,
            'userevent' => $userevent,
            'participant' => $eventParticipant,
            'evaluationForm' => $evaluationForm,
            'existingForms' =>$existingForms,
            'currentParticipants' => $currentParticipants,
            'hasAnswered' => $hasAnswered,
            'hasAnswers' => $hasAnswers,
            'certificate' => $certificate,
            'participants' => $participants,
            'currentUser' =>$currentUser,
            'userAgeData' => $userAgeData,
            'genderLabels' => $genderLabels,
            'genderCounts' => $genderCounts,
            'pendingParticipantsCount' => $pendingParticipantsCount,
            'regionCounts' => $regionCounts,
            'regionLabels' => $regionLabels,
            'provinceLabels'=> $provinceLabels,
            'provinceCounts' => $provinceCounts,
            'collegeLabels'=> $collegeLabels,
            'collegeCounts'=> $collegeCounts,
            'attendanceLog' => $attendanceLog,
        ]);
    }

    public function searchParticipants(Request $request, $id)
    {
        $search = $request->input('search');
        $event = Event::findOrFail($id);
        $userevent = UserEvent::with('user')->where('event_id', $id)->whereHas('user')->firstOrFail();
    
        $participants = EventParticipant::where('event_id', $id)
        ->where('status_id', 1)
        ->whereHas('user', function ($query) use ($search) {
            $query->withTrashed()
                  ->whereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ['%' . strtolower($search) . '%']);
        })
        ->with(['user' => function ($query) {
            $query->withTrashed(); // Include soft-deleted users
        }])
        ->paginate(10);

        return response()->json([
            'html' => view('event.partials.participantlist', compact('participants', 'event', 'userevent'))->render(),
        ]);
    }////////////////////

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'mode' => ['required', 'string', 'in:onsite,virtual'],
            'address' => ['required', 'string', 'max:255'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],
            'capacity' => ['required', 'integer', 'min:1'],
            'event_banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
        ]);
    
        // Add custom validation logic in the after method
        $validator->after(function ($validator) use ($request) {
            if ($request->start_date === $request->end_date) {
                if (strtotime($request->start_time) >= strtotime($request->end_time)) {
                    $validator->errors()->add('end_time', 'End time must be after start time on the same day.');
                }
            }
        });
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
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
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
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
            if ($request->has('remove_event_banner') && $request->input('remove_event_banner') == true) {
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
    
/////////////////////////////////////////
    public function showPendingParticipants($id)
    {
        $eventuser = UserEvent::findOrFail($id);
        $event = Event::findOrFail($id);
        $currentParticipants = EventParticipant::where('event_id', $id)
            ->where('status_id', 1)
            ->count();
        $participants = EventParticipant::where('event_id', $id)
                ->where('status_id', 3)
                ->paginate(10);

        return view('event.pendingparticipants', compact('eventuser', 'participants', 'event', 'currentParticipants'));
    }
    public function searchPendingParticipants(Request $request, $id)
    {
        $search = $request->input('search');
    
        // Retrieve the event
        $event = Event::findOrFail($id);
    
        $participants = EventParticipant::where('event_id', $id)
            ->where('status_id', 3)
            ->whereHas('user', function ($query) use ($search) {
                $query->withTrashed(); // Include soft-deleted users
                if ($search) {
                    $query->whereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ['%' . strtolower($search) . '%']);
                }
            })
            ->paginate(10);
    
        return response()->json([
            'html' => view('event.partials.pendingparticipants', compact('participants', 'event'))->render(),
        ]);
    }
///////////////////////////////////////////////////////////////
    public function updateParticipantStatus(Request $request, $eventId, $participantId)
    {
        $event = UserEvent::findOrFail($eventId);
        $request->validate([
            'status_id' => ['required', 'string', 'in:1,2'],
        ]);

        $participant = EventParticipant::where('user_id', $participantId)
            ->where('event_id', $eventId)
            ->whereHas('user', function ($query) {
                $query->withTrashed(); // Include trashed users
            })
            ->firstOrFail();
        // Update the participant's status

        $user = User::withTrashed()->findOrFail($participantId);
        $eventDetails = Event::withTrashed()->findOrFail($eventId);
        if ($request->status_id == 1) {
            
            $currentParticipants = EventParticipant::where('event_id', $eventId)
            ->where('status_id', 1) // Only count accepted participants
            ->count();

            if ($currentParticipants >= $eventDetails->capacity) {
                return back()->withErrors(['error' => 'Event capacity is full. Cannot accept more participants.']);
            }
            Log::info($currentParticipants);
            $qrToken = Str::random(10);
            $qrPath = 'storage/images/qr_codes/' . $qrToken . '.png';

            $qrUrl = route('event.qr', ['event' => $eventId, 'token' => $qrToken]);
            QrCode::format('png')->size(300)->margin(2)->generate($qrUrl, public_path($qrPath));
    
            // Save the QR code path to the participant
            $participant->update([
                'status_id' => 1,
                'qr_code' => $qrPath,
            ]);
            // User accepted
            event(new UserAcceptedToEvent($user, $eventDetails));
        } elseif ($request->status_id == 2) {
            $participant->update(['status_id' => 2]);
            // User denied (assuming status_id 2 means 'denied')
            event(new UserDeniedFromEvent($user, $eventDetails));
        }
        
        return back()->with('success', 'Participant status updated successfully.');

    }


    public function removeParticipant($id, $user)
    {
        $participant = EventParticipant::where('event_id', $id)
                        ->where('user_id', $user)
                        ->first();
    
        if ($participant) {
            // Set status_id to 2 to mark as removed
            $participant->status_id = 2;
            $participant->save();

            $user = $participant->user; // Assuming the relationship exists
            $eventDetails = Event::findOrFail($id);
            event(new UserRemovedFromEvent($user, $eventDetails));
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Participant not found.'], 404);
    }

///////////////FOR CERTIFICATE////////////////////////
    public function getParticipants($event_id)
    {
        // Fetch the participants for the given event_id and include soft-deleted users
        $participants = EventParticipant::where('event_id', $event_id)
            ->where('status_id', 1)
            ->whereHas('user', function ($query) {
                $query->withTrashed(); // Include soft-deleted users
            })
            ->with(['user' => function ($query) {
                $query->withTrashed(); // Include soft-deleted users in eager loading
            }])
            ->get()
            ->map(function ($participant) {
                // Concatenate user's first, middle, and last name
                $fullName = $participant->user->first_name . ' ' .
                            ($participant->user->middle_name ?? '') . ' ' .
                            $participant->user->last_name;

                return [
                    'full_name' => trim($fullName), // Actual full name without (DELETED)
                    'display_name' => $participant->user->trashed() 
                        ? trim($fullName) . ' (DELETED)' // Add (DELETED) for display purposes
                        : trim($fullName),
                    'user_id' => $participant->user->id,
                    'is_deleted' => $participant->user->trashed(), // True if soft-deleted
                ];
            });

        // Return the participants as a JSON response
        return response()->json($participants);
    }
/////////////////////////////////////////////////////////////////////


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
            // Fetch events the user has joined
            $events = Event::with('participants')
                ->whereHas('participants', function ($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->where('status_id', 1);
                })->get();
        } else {
            // Fetch all events
            $events = Event::with('userEvent')->get();
        }
    
        $formattedEvents = [];
    
        foreach ($events as $event) {
            $userEvent = $event->userEvent;
            $user = $userEvent->user ?? null;
    
            // Parse start and end datetime using start_date, end_date, start_time, and end_time
            $start = Carbon::parse($event->start_date . ' ' . $event->start_time);
            $end = Carbon::parse($event->end_date . ' ' . $event->end_time);
    
            // Adjust if the end datetime is earlier than the start datetime within a single day event
            if ($event->start_date === $event->end_date && $end->lt($start)) {
                $end = $end->copy()->addDay();
            }
    
            // Format dates for FullCalendar in a compatible format
            $formattedEvents[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $start->toDateTimeString(),
                'end' => $end->toDateTimeString(),
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
    

    public function adminDeactivate($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return redirect()->route('event.myeventlist')->with('success', 'Event soft deleted successfully!');
    }

    public function deactivate($id)
    {
        $event = Event::findOrFail($id);

        $event->delete();

        return redirect()->route('superadmin.eventlist')->with('success', 'Event soft deleted successfully!');
    }

    public function recover($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->restore();

        return redirect()->route('superadmin.eventlist')->with('success', 'Event recovered successfully!');
    }

    //for pending participant checking capacity
    public function checkCapacity($id)
    {
        $event = Event::findOrFail($id);
        $currentParticipants = $event->participants()->where('status_id', 1)->count(); // Adjust based on your logic

        return response()->json([
            'currentParticipants' => $currentParticipants,
            'capacity' => $event->capacity,
        ]);
    }
    //checking if eval form has answers
    public function hasAnswers($id)
    {
        // Check if answers exist for the event
        $event = Event::withTrashed()->findOrFail($id);
        $evaluationForm = $event->evaluationForm;
        if ($evaluationForm) {
            $hasAnswers = Answer::where('event_form_id', $evaluationForm->id)
                          ->exists();
            }

        return response()->json([
            'hasAnswers' => $hasAnswers,
        ]);
    }

    //////////////////////////////QR TESTING//////////////////////
    public function handleQRCode(Request $request, $eventId, $token)
    {
        $event = Event::findOrFail($eventId);
    
        // Validate the QR token and participant
        $participant = EventParticipant::where('event_id', $eventId)
            ->where('qr_code', 'LIKE', "%$token%")
            ->first();
    
    
        if (!$participant) {
            return response()->json(['error' => 'Invalid QR code or participant not found.'], 404);
        }
    
        // Check if the scan is coming from the system
        if ($request->has('system') && $request->query('system') === 'true') {
            // Check if already attended
            // if ($participant->attended_at) {
            //     return response()->json(['error' => 'Participant already marked as attended.'], 400);
            // }
    
            // // Mark attendance
            // $participant->update(['attended_at' => now()]);
    
            // Log attendance
            AttendanceLog::create([
                'event_id' => $eventId,
                'user_id' => $participant->user_id,
                'scanned_at' => now(),
            ]);
    
            return response()->json([
                'success' => true,
                'user' => [
                    'first_name' => $participant->user->first_name,
                    'last_name' => $participant->user->last_name,
                    'email' => $participant->user->email,
                ],
                'scanned_at' => now(),
            ]);
        }
    
        // For normal browser scans, show event information
        return view('event.qr-info', compact('event', 'participant'));
    }

    public function showAttendanceLog($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
    
        // Paginate logs instead of retrieving all
        $logs = AttendanceLog::where('event_id', $id)
            ->with(['user' => function ($query) {
                $query->withTrashed(); // Include users who may have been soft-deleted
            }])
            ->orderBy('scanned_at', 'desc')
            ->get();
    
        return view('event.attendance-log', compact('event', 'logs'));
    }

    public function fetchAttendanceLogs(Request $request, $id)
    {
        $columns = ['id', 'name', 'email', 'scanned_at'];

        $totalRecords = AttendanceLog::where('event_id', $id)->count();

        $query = AttendanceLog::where('event_id', $id)
            ->with(['user' => function ($query) {
                $query->withTrashed(); // Include soft-deleted users
            }]);


        // Apply global search filter
        if (!empty($request->input('search.value'))) {
            $searchValue = $request->input('search.value');
            $query->whereHas('user', function ($q) use ($searchValue) {
                $q->where('first_name', 'LIKE', "%{$searchValue}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchValue}%")
                  ->orWhere('email', 'LIKE', "%{$searchValue}%");
            });
        }

        // Count filtered records before pagination
        $filteredRecords = $query->count();

        // Apply ordering
        if (!empty($request->input('order.0.column'))) {
            $orderBy = $columns[$request->input('order.0.column')];
            $direction = $request->input('order.0.dir', 'asc');
        
            // Handle ordering for related `user` fields
            if ($orderBy === 'name') {
                $query->join('users', 'attendance_logs.user_id', '=', 'users.id')
                      ->orderByRaw("CONCAT(users.first_name, ' ', users.last_name) {$direction}");
            } elseif ($orderBy === 'email') {
                $query->join('users', 'attendance_logs.user_id', '=', 'users.id')
                      ->orderBy("users.email", $direction);
            } else {
                $query->orderBy($orderBy, $direction);
            }
        }

        // Apply pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $logs = $query->skip($start)->take($length)->get();

        // Format data for DataTables
        $data = [];
        foreach ($logs as $index => $log) {
            $isDeleted = $log->user && $log->user->trashed(); // Check if the user is soft-deleted
            $data[] = [
                'name' => $log->user
                    ? $log->user->first_name . ' ' . $log->user->last_name . ($isDeleted ? ' (DELETED)' : '')
                    : 'Unknown',
                'email' => $log->user ? $log->user->email : 'N/A',
                'scanned_at' => $log->scanned_at->timezone('Asia/Manila')->format('Y-m-d h:i:s A'),
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }
}
