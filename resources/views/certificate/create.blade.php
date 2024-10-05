@extends('layouts.app')

@section('body')
    <!-- Include Blueprint CSS via Vite -->
    @vite(['resources/css/blueprint.css', 'resources/js/editor.jsx'])

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
        .input-container {
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
        }
        .input-container input {
            width: 50%; /* Adjust width as needed */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>

    <!-- Input for Certificate Name -->
    <div class="input-container">
        <input
            type="text"
            id="certName"
            name="cert_name"
            placeholder="Enter Template Name"
            value="{{ old('cert_name', $certificate->template_name ?? '') }}"
        />
    </div>

    <!-- Create Container for Editor -->
    <div id="container" data-certificate-id="{{ $certificate->id ?? '' }}"></div>
    
@endsection
