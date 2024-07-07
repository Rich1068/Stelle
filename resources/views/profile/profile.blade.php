@extends('layouts.app')
@section('body')

<h1>Profile</h1>

<h2><img src="{{ asset('assets/images/default.jpg') }}" alt="Profile pic" style="max-width: 200px; max-height: 100px;">{{ $user->name }}</h2>
<h3>About/Bio: <br> @if ($user->description == null) N/A @else {{ $user->description }} @endif </h3><br><br>
<h3><b>Information</b> <br> Email: {{ $user->email }} <br> Contact Number: @if ($user->contact_number == null) N/A @else {{ $user->contact_number }} @endif</h3>

<a href="{{ route('profile.edit') }}" class="btn btn-primary">
    <span>Edit</span>
</a>
@endsection