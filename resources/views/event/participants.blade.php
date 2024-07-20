<!-- resources/views/events/participants.blade.php -->

@extends('layouts.app')

@section('body')
<div class="container">
    <h1>Participants for {{ $event->title }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $participant)
                <tr>
                    <td>{{ $participant->user->first_name }} {{ $participant->user->last_name }}</td>
                    <td>{{ $participant->status->status }}</td>
                    <td>
                        <form action="{{ route('participants.updateStatus', [$eventuser->id, $participant->user_id]) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="status_id" value="1"> <!-- Accepted -->
                            <button type="submit" class="btn btn-success">Accept</button>
                        </form>
                        <form action="{{ route('participants.updateStatus', [$eventuser->id, $participant->user_id]) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <input type="hidden" name="status_id" value="2"> <!-- Declined -->
                            <button type="submit" class="btn btn-danger">Decline</button>
                        </form>
                        </td>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
