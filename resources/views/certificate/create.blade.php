@extends('layouts.app')

@section('body')
    <!-- Include Blueprint CSS (if needed) -->
    <link href="https://unpkg.com/@blueprintjs/core@5/lib/css/blueprint.css" rel="stylesheet" />

    <!-- Add Polotno Bundle (make sure this script is added to the body) -->
    <script src="https://unpkg.com/polotno@2/polotno.bundle.js"></script>

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

    <!-- Initialize the Editor -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const { store } = createPolotnoApp({
                // This is a demo key just for this project
                // Please obtain your own API key from https://polotno.com/cabinet
                key: 'nFA5H9elEytDyPyvKL7T',
                showCredit: true, // Show Polotno credit
                container: document.getElementById('container'),
                // Optionally specify which side panels to show
                // sections: ['photos', 'text', 'elements', 'upload', 'background', 'layers']
            });

            // You can use the full store API available here: https://polotno.com/docs/store-overview
        });
    </script>
@endsection
