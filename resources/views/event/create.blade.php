@extends('layouts.app')
@section('body')
@php
    $today = date('Y-m-d');
@endphp
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Create Event') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Fill out the form below to create a new event.") }}
        </p>
    </header>

    <form method="post" action="{{ route('events.store') }}" class="mt-6 space-y-6" enctype="multipart/form-data" id="eventForm">
        @csrf
        @method('POST')

        <!-- Title -->
        <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <!-- Description -->
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description" class="mt-1 block w-full" required>{{ old('description') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <!-- Date -->
        <div>
            <x-input-label for="date" :value="__('Date')" />
            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date')" :min="$today" required />
            <x-input-error class="mt-2" :messages="$errors->get('date')" />
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" required />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- Start Time -->
        <div>
            <x-input-label for="start_time" :value="__('Start Time')" />
            <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" :value="old('start_time')" required />
            <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
        </div>

        <!-- End Time -->
        <div>
            <x-input-label for="end_time" :value="__('End Time')" />
            <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time')" required />
            <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
        </div>

        <!-- Capacity -->
        <div>
            <x-input-label for="capacity" :value="__('Capacity')" />
            <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full" :value="old('capacity')" required />
            <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
        </div>

        <div>
            <x-input-label for="mode" :value="__('Mode')" />
            <select id="mode" name="mode" class="block mt-1 w-full">
                <option value="">{{ __('Select Mode') }}</option>
                <option value="onsite" {{ old('mode') == 'onsite' ? 'selected' : '' }}>{{ __('Onsite') }}</option>
                <option value="virtual" {{ old('mode') == 'virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
            </select>
            <x-input-error :messages="$errors->get('mode')" class="mt-2" />
        </div>

        <!-- Event Banner -->
        <div>
            <x-input-label for="event_banner" :value="__('Event Banner')" />
            <x-text-input id="event_banner" name="event_banner" type="file" class="mt-1 block w-full" accept="image/*" onchange="previewImage(event)" />
            <x-input-error class="mt-2" :messages="$errors->get('event_banner')" />

            <img id="image_preview" style="display: none; max-width: 50%; margin-top: 10px;" />
        </div>

        <div class="flex items-center gap-4">
        <button id="createEventButton" type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-blue-900 transition ease-in-out duration-150">
            {{ __('Create Event') }}
        </button>
        </div>
    </form>
</section>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('image_preview');
        output.src = reader.result;
        output.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}
 // JavaScript to prevent multiple submissions
    document.getElementById('eventForm').addEventListener('submit', function(event) {
        var button = document.getElementById('createEventButton');
        if (button.disabled) {
            event.preventDefault(); // Prevent form from submitting
        } else {
            button.disabled = true; // Disable button to prevent multiple clicks
            button.textContent = 'Submitting...'; // Change button text
        }
    });

    // Prevent form submission when pressing Enter key inside text fields
    document.getElementById('eventForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && event.target.tagName === 'INPUT' && event.target.type !== 'submit') {
            event.preventDefault();
        }
    });
</script>
@endsection