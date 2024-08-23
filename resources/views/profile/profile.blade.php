@extends('layouts.app')
@section('body')

<h1>Profile</h1>

<h2>
    @if($user->profile_picture == null)
        <img src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Profile pic" style="max-width: 200px; max-height: 100px;"> 
    @else 
        <img src="{{ asset($user->profile_picture) }}" alt="Profile pic" style="max-width: 200px; max-height: 100px;"> 
    @endif {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}
</h2>
<h3><b>About/Bio: </b><br> 
@if ($user->description == null) 
    N/A 
@else 
    {{ $user->description }} 
@endif </h3><br><br>
<h3>
<b>Information</b> <br> 
Email: {{ $user->email }} <br> 
Contact Number: @if ($user->contact_number == null) N/A @else {{ $user->contact_number }} @endif 
<br> Country: @if($user->country_id == null) N/A @else {{ $countryTable->countryname }} <img src="{{ asset("storage/images/flags/{$countryTable->code}.png") }}" alt="Profile pic" style="max-width: 50px; max-height: 50px;"> @endif
<br> Gender: @if($user->gender == null) N/A @else {{ $user->gender }} @endif </h3>



<a href="{{route('profile.edit')}}" class="btn btn-primary">
    <span>Edit</span>
</a>
@endsection