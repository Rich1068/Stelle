@extends('layouts.app')

@section('body')
    <div class="container">
        <h1>Your Certificates</h1>

        @if($user->certificates->isEmpty())
            <p>No certificates found.</p>
        @else
            <ul>
                @foreach($user->certificates as $certificate)
                    <img src="{{ asset($certificate->cert_path)}}" style="height:200px; width:200px" > 
                @endforeach
            </ul>
        @endif
    </div>
@endsection