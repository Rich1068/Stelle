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



<a href="{{ route('event.edit') }}" class="btn btn-primary">
    <span>Edit</span>
</a>
@endsection