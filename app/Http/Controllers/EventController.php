<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function create(): View
    {
        return view('event.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
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
            $oldfile = $event->event_banner;
            // Optionally, delete the old profile picture if it exists
            if (!empty($event->event_banner)) {
                
                if (File::exists($oldfile)) {
                    File::delete($oldfile);
                }
            }
        
            // Move the uploaded file to the desired directory
            $file->storeAs('/images/event_banners', $filename);
        
            // Update the profile picture path
            $user->profile_picture = $relativePath;
        }
        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'address' => $request->address,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'capacity' => $request->capacity,
        ]);
        

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
