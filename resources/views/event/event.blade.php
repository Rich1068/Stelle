@extends('layouts.app')
@section('body')

<h1>Event</h1>
@if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

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
Capacity: {{$currentParticipants}}/{{ $event->capacity }} <br>
By: {{ $userevent->user->first_name }} {{ $userevent->user->last_name }}<br>
</h3>


@if($userevent->user_id == Auth::user()->id)
<a href="{{ route('event.edit', $event->id) }}" class="btn btn-primary">
    <span>Edit</span>
</a>

<a href="{{ route('events.participants', $event->id) }}" class="btn btn-primary">
    <span>View Participant</span>
</a>
@endif

@if ($currentParticipants < $event->capacity)
    @if($participant == null && $userevent->user_id != Auth::user()->id)
            <form action="{{ route('event.join', $event->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Join Event</button>
            </form>
        @elseif ($participant && $participant->status_id == 3) <!-- Assuming 'Pending' has an id of 3 -->
            <button type="button" class="btn btn-secondary" disabled>Pending</button>
        @elseif ($participant && $participant->status_id == 1) <!-- Assuming 'Accepted' has an id of 1 -->
            <p>You have been accepted to this event.</p>
    @endif
@elseif ($userevent->user_id == Auth::user()->id)
@else
    <button type="button" class="btn btn-secondary" disabled>Closed</button>
@endif

@if($event->evaluationForm)
    <form action="{{ route('evaluation-forms.update', ['id' => $event->id, 'form' => $event->evaluationForm->id]) }}" method="POST">
        @method('PUT')
@else
    <form action="{{ route('evaluation-forms.store', $event->id) }}" method="POST">
@endif
    @csrf
    <button type="submit">
        @if($event->evaluationForm)
            Update Evaluation Form
        @else
            Create Evaluation Form
        @endif
    </button>
</form>
@endsection