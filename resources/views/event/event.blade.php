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
</br></br>
    @if($event->evaluationForm)
        <form action="{{ route('evaluation-forms.update', ['id' => $event->id, 'form' => $event->evaluationForm->id]) }}" method="POST">
            @method('PUT')
            @csrf
            <button type="submit" class="btn btn-primary">
                Update Evaluation Form
            </button>
        </form>

            <!-- Separate Form for Activating/Deactivating the Evaluation Form -->
        <form action="{{ route('evaluation-forms.toggle', ['id' => $event->id, 'form' => $event->evaluationForm->id]) }}" method="POST" style="margin-top: 10px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="is_active_toggle">Activate Evaluation Form:</label>
                <input type="checkbox" name="is_active" id="is_active_toggle" onchange="this.form.submit()" {{ $event->evaluationForm->status_id == 1 ? 'checked' : '' }}>
            </div>
        </form>
    @else
        <form action="{{ route('evaluation-forms.store', $event->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Create Evaluation Form</button>
        </form>
    @endif

    <a href="{{ route('certificates.create', $event->id) }}" class="btn btn-primary">Create Certificate</a>
@endif

@if($userevent->user_id != Auth::user()->id)

    @if ($participant && $participant->status_id == 1)
        <p>You have been accepted to this event.</p>
    @elseif ($currentParticipants < $event->capacity && $participant == null)
        <form action="{{ route('event.join', $event->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Join Event</button>
        </form>
    @elseif ($participant && $participant->status_id == 3)
        <button type="button" class="btn btn-secondary" disabled>Pending</button>
    @else
        <button type="button" class="btn btn-secondary" disabled>Closed</button>
    @endif

    @if ($participant && $participant->status_id == 1)
        @if($event->evaluationForm && $event->evaluationForm->status_id == 1)
            @if($hasAnswered)
                <button type="button" class="btn btn-secondary" disabled>Evaluation Form Already Answered</button>
            @else
                <form action="{{ route('evaluation-form.take', ['id' => $event->id, 'form' => $evaluationForm->id]) }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-primary">Take Evaluation</button>
                </form>
            @endif
        @else
            <button type="button" class="btn btn-secondary" disabled>Evaluation Not Yet Available</button>
        @endif
    @endif

@endif

@endsection

