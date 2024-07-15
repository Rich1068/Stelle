<?php

namespace App\Http\Controllers;
use App\Models\UserEvent;
use App\Models\Event;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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

    public function view($id): View
    {
        $event = Event::findOrFail($id);
        return view('event.event', ['event' => $event]);
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
            'event_banner' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
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
            'event_banner' => $relativePath,
            'mode' => $request->mode
        ]);
        $event->save();

        $userEvent = UserEvent::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
        ]);
        
        

        return redirect()->route('profile.profile')->with('success', 'Event created successfully.');
    }
}
