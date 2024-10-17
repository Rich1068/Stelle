@extends('layouts.app')

@section('body')
@php
    $today = date('Y-m-d');
@endphp
<section class="event-form-container">
    <!-- Success and Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="top-container">
        <h2 class="font-weight-bold mb-0">
            <i class="fas fa-list me-2"></i> <!-- List icon -->
            Edit Event
        </h2>
    </div>
    
    <div class="event-form">
        <!-- Form Start -->
        <form method="post" action="{{ route('event.update', $event->id) }}" enctype="multipart/form-data" id="editEventForm">
            @csrf
            @method('PATCH')

            <div class="custom-bg-white" style="border-radius: 15px; background-color: white; padding-top: 20px;">
                <!-- Title -->
                <div class="col-md-12 mb-4">
                    <div class="event-field">
                        <label for="title" class="flex items-center">
                            <i class="fas fa-heading mr-2"></i>
                            <span class="font-bold">{{ __('Title') }}</span>
                        </label>
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full event-input" :value="old('title', $event->title)" required autofocus autocomplete="off" />
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

                                @php
                                    $eventDate = old('date', $event->date);
                                    // Allow past dates if the event's date is before today, otherwise set the min to today's date
                                    $minDate = $eventDate >= $today ? $today : null;
                                @endphp

                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full event-input" :value="$eventDate" :min="$minDate" required />
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
                                <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full event-input" :value="old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i'))" required />
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
                                <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full event-input" :value="old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i'))" required />
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
        <textarea id="description" name="description" class="mt-1 block w-full event-input" oninput="autoResize(this)" rows="1" style="overflow:hidden;" required>{{ old('description', $event->description) }}</textarea>
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
                                <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full event-input" :value="old('capacity', $event->capacity)" min="1" required />
                                <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="address" class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span class="font-bold">{{ __('Address/Link') }}</span>
                                </label>
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full event-input" :value="old('address', $event->address)" required />
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
                                    <option value="onsite" {{ old('mode', $event->mode) == 'onsite' ? 'selected' : '' }}>{{ __('Onsite') }}</option>
                                    <option value="virtual" {{ old('mode', $event->mode) == 'virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
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
        

        <input id="event_banner" name="event_banner" type="file" accept="image/*" class="mt-1 block w-full event-input" onchange="previewImage(event)" style="display: none;" />

   
        <button type="button" class="custom-file-button" onclick="document.getElementById('event_banner').click()">
            <i class="fas fa-upload"></i> <span class="bold-text">Update Event Banner</span>
        </button>
        <div class="mt-2">
            <label for="remove_event_banner">
                <input type="checkbox" name="remove_event_banner" id="remove_event_banner"> {{ __('Remove Event Banner') }}
            </label>
        </div>
        
        <x-input-error class="mt-2" :messages="$errors->get('event_banner')" />

        <!-- Image preview section for the selected file -->
        <img id="image_preview" class="event-image-preview" style="display: none; max-width: 50%; margin-top: 10px;" />
    </div>
</div>

                <div class="flex items-center gap-4 mb-4">
                    <x-primary-button id="editEventButton" type="submit" class="btn-primary" onclick="disableButton(this)">
                        <i class="fas fa-save"></i> {{ __('Update Event') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
    .custom-file-button {
        background-color: #001e54; /* LightSeaGreen */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .custom-file-button:hover {
        background-color: #004d80;
    }

    .custom-file-button i {
        margin-right: 8px;
    }
    .bold-text {
        font-weight: bold;
    }
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
      function autoResize(textarea) {
        textarea.style.height = 'auto';  // Reset height to auto to shrink if needed
        textarea.style.height = (textarea.scrollHeight) + 'px';  // Set the height based on the scroll height
    }
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('image_preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

function disableAfterClick(button) {
    button.disabled = true;
    button.innerText = 'Updating...';
    button.form.submit();
}
</script>
@endsection
