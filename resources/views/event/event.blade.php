@extends('layouts.app')
@section('body')

<h1>Event</h1>

<h2>
    <img src="{{ asset($event->event_banner) }}" alt="Event banner" style="max-width: 200px; max-height: 100px;">  
    {{ $event->title }}
</h2>
<h3><b>About: </b><br> 
    {{ $event->description }}  
</h3><br><br>
<h3>
<b>Information</b> <br> 
Date: {{ $event->date }} <br> 
Mode: {{ $event->mode }} <br> 
Address: {{ $event->address }} <br>
Duration: {{ $event->start_time }} to {{ $event->end_time }} <br>
Capacity: {{ $event->capacity }} <br>


@if($userevent->user_id == Auth::user()->id)
<a href="{{ route('event.edit', $event->id) }}" class="btn btn-primary">
    <span>Edit</span>
</a>

@if($userevent->user_id != Auth::user()->id)
<form action="{{ route('event.join', $event->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-success">Join Event</button>
</form>
@endif
@endif
@endsection