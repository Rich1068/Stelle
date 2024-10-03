@extends('layouts.app')

@section('body')
@php
    $today = date('Y-m-d');
@endphp

<div class="top-container">
    <h2 class="font-weight-bold mb-0">
        <i class="fas fa-list me-2"></i> <!-- List icon -->
        Create Event 
    </h2>
</div>
<section class="event-form-container">
    <div class="event-form">

        <!-- Form Section -->
        <form method="post" action="{{ route('events.store') }}" enctype="multipart/form-data" id="eventForm">
            @csrf
            @method('POST')

            <div class="custom-bg-white" style="border-radius: 15px; background-color: white; padding-top: 20px;">
                <!-- Title -->
                <div class="col-md-12 mb-4">
                    <div class="event-field">
                        <label for="title" class="flex items-center">
                            <i class="fas fa-heading mr-2"></i>
                            <span class="font-bold">{{ __('Title') }}</span>
                        </label>
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full event-input" :value="old('title')" required autofocus autocomplete="off" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>
                </div>

                <!-- Date, Start Time, and End Time in One Column -->
                <div class="col-md-12 mb-4">
                    <div class="row">
                        <!-- Date -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="date" class="flex items-center">
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    <span class="font-bold">{{ __('Date') }}</span>
                                </label>
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full event-input" :value="old('date')" :min="$today" required />
                                <x-input-error class="mt-2" :messages="$errors->get('date')" />
                            </div>
                        </div>

                        <!-- Start Time -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="start_time" class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span class="font-bold">{{ __('Start Time') }}</span>
                                </label>
                                <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full event-input" :value="old('start_time')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                            </div>
                        </div>

                        <!-- End Time -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="end_time" class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span class="font-bold">{{ __('End Time') }}</span>
                                </label>
                                <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full event-input" :value="old('end_time')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-12 mb-4">
                    <div class="event-field">
                        <label for="description" class="flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>
                            <span class="font-bold">{{ __('Description') }}</span>
                        </label>
                        <textarea id="description" name="description" class="mt-1 block w-full event-input" required>{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>
                </div>

                <!-- Capacity, Mode, and Address in One Column -->
                <div class="col-md-12 mb-4">
                    <div class="row">

                        <!-- Capacity -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="capacity" class="flex items-center">
                                    <i class="fas fa-users mr-2"></i>
                                    <span class="font-bold">{{ __('Capacity') }}</span>
                                </label>
                                <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full event-input" :value="old('capacity')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="address" class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span class="font-bold">{{ __('Address') }}</span>
                                </label>
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full event-input" :value="old('address')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                            </div>
                        </div>

                        <!-- Mode at the End -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="mode" class="flex items-center">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    <span class="font-bold">{{ __('Mode') }}</span>
                                </label>
                                <select id="mode" name="mode" class="block mt-1 w-full event-input">
                                    <option value="">{{ __('Select Mode') }}</option>
                                    <option value="onsite" {{ old('mode') == 'onsite' ? 'selected' : '' }}>{{ __('Onsite') }}</option>
                                    <option value="virtual" {{ old('mode') == 'virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('mode')" class="mt-2" />
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Event Banner -->
                <div class="col-md-12 mb-4">
                    <div class="event-field">
                        <label for="event_banner" class="flex items-center">
                            <i class="fas fa-image mr-2"></i>
                            <span class="font-bold">{{ __('Event Banner') }}</span>
                        </label>
                        <x-text-input id="event_banner" name="event_banner" type="file" class="mt-1 block w-full event-input" accept="image/*" onchange="previewImage(event)" />
                        <x-input-error class="mt-2" :messages="$errors->get('event_banner')" />
                        <img id="image_preview" class="event-image-preview" style="display: none; max-width: 50%; margin-top: 10px;" />
                    </div>
                </div>

                <div class="flex items-center gap-4 mb-4">
                <x-primary-button id="createEventButton" type="submit" class="btn-primary" onclick="disableButton(this)">
                    <i class="fas fa-save"></i> {{ __('Create Event') }}
                </x-primary-button>
            </div>
            </div>

            <!-- Submit Button -->
          
        </form>
    </div>
</section>

<style>
.custom-bg-white {
    border-radius: 15px; /* Add border radius */
    max-width: 120%;
    padding: 12px;
    align-items: center; /* This is not necessary unless you're using flexbox */
    margin: auto; /* Center the element */
    background-color: white; /* Set background color */
    background-color: rgba(255, 255, 255, 0.4) !important; /* Semi-transparent white */
    margin-bottom: 20px !important;
}

    </style>
<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('image_preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

function disableButton(button) {
    button.disabled = true;
    button.innerText = 'Submitting...';
    button.form.submit();
}

document.getElementById('eventForm').addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
        event.preventDefault();
    }
});
</script>
@endsection
