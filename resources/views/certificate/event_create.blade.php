@extends('layouts.app')

@section('body')
    <!-- Include Blueprint CSS via Vite -->
    @vite(['resources/css/blueprint.css', 'resources/js/event_editor.jsx'])

    <!-- Set Styles for the Editor -->
    <style>
        body {
            padding: 0;
            margin: 0;
        }
        #container {
            width: 100%;
            height: 100%;
        }
    </style>

    <!-- Create Container for Editor -->
    <div id="container" data-event-id="{{ $event->id }}"></div>

@endsection