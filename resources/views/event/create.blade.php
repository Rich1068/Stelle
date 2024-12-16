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
                <div class="col-md-12 mb-2">
                    <div class="event-field">
                        <label for="title" class="flex items-center">
                            <i class="fas fa-heading mr-2"></i>
                            <span class="font-bold">{{ __('Title') }}</span>
                            <span style="color:#ff3333;">*</span>
                        </label>
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full event-input" :value="old('title')" required autofocus autocomplete="off" />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" style="color:#ff3333;"/>
                    </div>
                </div>

                                <!-- Date, Start Time, and End Time in One Column -->
                <div class="col-md-12 mb-2">
                    <div class="row">
                        <!-- Row 1: Start Date and End Date -->
                        <div class="col-md-6 mb-3">
                            <div class="event-field">
                                <label for="start_date" class="flex items-center">
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    <span class="font-bold">{{ __('Start Date') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full event-input" :value="old('start_date')" :min="$today" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" style="color:#ff3333;"/>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="event-field">
                                <label for="end_date" class="flex items-center">
                                    <i class="fas fa-calendar-day mr-2"></i>
                                    <span class="font-bold">{{ __('End Date') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full event-input" :value="old('end_date')" :min="$today" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_date')" style="color:#ff3333;"/>
                            </div>
                        </div>

                        <!-- Row 2: Start Time and End Time -->
                        <div class="col-md-6 mb-3">
                            <div class="event-field">
                                <label for="start_time" class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span class="font-bold">{{ __('Start Time') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <input id="start_time" name="start_time" class="mt-1 block w-full event-input" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_time')" style="color:#ff3333;"/>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="event-field">
                                <label for="end_time" class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span class="font-bold">{{ __('End Time') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <input id="end_time" name="end_time" class="mt-1 block w-full event-input" required />
                                <x-input-error class="mt-2" :messages="$errors->get('end_time')" style="color:#ff3333;"/>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- Description -->
        <div class="col-md-12 mb-2">
            <div class="event-field">
                <label for="description" class="flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    <span class="font-bold">{{ __('Description') }}</span>
                    <span style="color:#ff3333;">*</span>
                </label>
                <textarea id="description" name="description" class="mt-1 block w-full event-input" oninput="autoResize(this)" rows="1" style="overflow:hidden;" required>{{ old('description') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" style="color:#ff3333;"/>
            </div>
        </div>
                <!-- Capacity, Mode, and Address in One Column -->
                <div class="col-md-12 mb-2">
                    <div class="row">

                        <!-- Capacity -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="capacity" class="flex items-center">
                                    <i class="fas fa-users mr-2"></i>
                                    <span class="font-bold">{{ __('Capacity') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full event-input" :value="old('capacity')" min="1" required />
                                <x-input-error class="mt-2" :messages="$errors->get('capacity')" style="color:#ff3333;"/>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="address" class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    <span class="font-bold">{{ __('Address/Link') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full event-input" :value="old('address')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('address')" style="color:#ff3333;"/>
                            </div>
                        </div>

                        <!-- Mode at the End -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="mode" class="flex items-center">
                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                    <span class="font-bold">{{ __('Mode') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <select id="mode" name="mode" class="block mt-1 w-full event-input" required>
                                    <option value="">{{ __('Select Mode') }}</option>
                                    <option value="onsite" {{ old('mode') == 'onsite' ? 'selected' : '' }}>{{ __('Onsite') }}</option>
                                    <option value="virtual" {{ old('mode') == 'virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('mode')" class="mt-2" style="color:#ff3333;"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-2">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <div class="event-field">
                                <label for="event_banner" class="flex items-center">
                                    <i class="fas fa-image mr-2"></i>
                                    <span class="font-bold">{{ __('Event Banner') }}</span>
                                </label>
                                <!-- Hide the original file input -->
                                <input id="event_banner" name="event_banner" type="file" accept="image/*" onchange="previewImage(event)" style="display: none;" />
                                
                                <!-- Custom button to trigger file input -->
                                <button type="button" class="custom-file-button" onclick="document.getElementById('event_banner').click()">
                                    <i class="fas fa-upload"></i> <span class="bold-text">Choose File</span>
                                </button>
                                
                                <x-input-error class="mt-2" :messages="$errors->get('event_banner')" style="color:#ff3333;"/>
                                <img id="image_preview" class="event-image-preview" style="display: none; max-width: 50%; margin-top: 10px;" />
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="organization" class="flex items-center">
                                    <i class="fas fa-building mr-2"></i>
                                    <span class="font-bold">{{ __('Organization') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <select id="organization" name="organization_id" class="block mt-1 w-full event-input" required>
                                    <option value="">{{ __('Select Organization') }}</option>
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>
                                            {{ $organization->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('organization_id')" class="mt-2" style="color:#ff3333;"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 mb-4">
                <x-primary-button id="createEventButton" type="submit" class="btn-primary" onclick="disableButton(this)">
                    <i class="fas fa-save"></i> {{ __('Create Event') }}
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
.custom-bg-white {
    border-radius: 15px; /* Add border radius */
    width: 100%; /* Use 100% width for responsiveness */
    max-width: 1200px; /* Set a max-width to prevent excessive stretching */
    padding: 20px;
    background-color: white; /* Set background color */
    background-color: rgba(255, 255, 255, 0.4) !important; /* Semi-transparent white */
    margin: 0 auto 20px; /* Center the element and add space at the bottom */
}

@media (min-width: 1200px) {
    .custom-bg-white {
        max-width: 1000px; /* Adjust max-width for larger screens */
        padding: 30px; /* Increase padding for larger screens */
    }
}


@media (min-width: 768px) and (max-width: 1199px) {
    .custom-bg-white {
        max-width: 90%;
        padding: 20px;
    }

    .event-form-container {
        margin: 0 auto;
        width: 95%; 
}

/* Media query for tablets and smaller devices */
@media (max-width: 767px) {
    .custom-bg-white {
        max-width: 100%; 
        padding: 15px;
    }

    .event-form-container {
        width: 100%;
        margin: 0; /
    }


}
}
    </style>

<link rel="stylesheet" href="https://kendo.cdn.telerik.com/themes/10.0.1/default/default-ocean-blue.css"/>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2024.4.1112/js/kendo.all.min.js"></script>

<script>
$("#start_time").kendoTimePicker({
    componentType: "modern",
    format: "HH:mm",
    parseFormats: ["HH:mm"],
    interval: {
        hour: 1,
        minute: 5,
    }    
});
$("#end_time").kendoTimePicker({
    componentType: "modern",
    format: "HH:mm",
    parseFormats: ["HH:mm"],
    interval: {
        hour: 1,
        minute: 5,
    }
    
});
function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const preview = document.getElementById('image_preview');
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
     function autoResize(textarea) {
    textarea.style.height = 'auto';  // Reset height to auto to shrink if needed
    textarea.style.height = (textarea.scrollHeight) + 'px';  // Set the height based on the scroll height
}


function disableButton(button) {
    // Disable the button to prevent multiple submissions
    button.disabled = true;

    // Update button text to indicate progress
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

    // Trigger the form submission
    const form = button.closest("form");
    if (form) {
        form.submit();
    }

    // Prevent further events
    return false;
}

document.getElementById('eventForm').addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && event.target.tagName !== 'TEXTAREA') {
        event.preventDefault();
    }
});


</script>
@endsection
