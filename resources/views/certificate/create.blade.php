@extends('layouts.app')

@section('body')
    <!-- Include Blueprint CSS via Vite -->
    @vite(['resources/css/blueprint.css'])

    <!-- Set Styles for the Editor -->
    <style>
        body {
            padding: 0;
            margin: 0;
        }
        #container {
            width: 100vw;
            height: 100vh;
        }
    </style>

    <!-- Create Container for Editor -->
    <div id="container"></div>
    <script src="resources/js/editor.jsx" type="module"></script>
    <script>
      window.onload = () => {
        window.createEditor({ container: document.getElementById('container') });
      };
    </script>

    <!-- Initialize the Editor -->
    @vite('resources/js/editor.jsx')
@endsection
