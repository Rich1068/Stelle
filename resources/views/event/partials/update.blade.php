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

    <div class="event-form">
        <!-- Header Section -->
        <div class="event-field header-section">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    <i class="fa fa-calendar-plus"></i> {{ __('Edit Event') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    <i class="fa fa-info-circle"></i> {{ __("Update the details of your event.") }}
                </p>
            </header>
            
        <!-- Form Start -->
        <form method="post" action="{{ route('event.update', $event->id) }}" class="mt-6 space-y-6" enctype="multipart/form-data" id="editEventForm">
            @csrf
            @method('patch')
        </div>


            <!-- Title -->
            <div class="event-field">
                <label for="title" class="flex items-center">
                <i class="fas fa-heading mr-2"></i>
                    <span class="font-bold">{{ __('Title') }}</span>
                </label>
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full event-input" :value="old('title', $event->title)" required autofocus autocomplete="title" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>

            <!-- Date & Time -->
            <div class="event-field event-datetime">
                <div class="event-date">
                    <label for="date" class="flex items-center">
                        <i class="fa fa-calendar-day mr-2"></i>
                        <span class="font-bold">{{ __('Date') }}</span>
                    </label>
                    <x-text-input id="date" name="date" type="date" class="mt-1 block w-full event-input" :value="old('date', $event->date)" :min="$today" required />
                    <x-input-error class="mt-2" :messages="$errors->get('date')" />
                </div>
                <div class="event-time">
                    <label for="start_time" class="flex items-center">
                        <i class="fa fa-clock mr-2"></i>
                        <span class="font-bold">{{ __('Start Time') }}</span>
                    </label>
                    <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full event-input" :value="old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i'))" required />
                    <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                </div>
                <div class="event-time">
                    <label for="end_time" class="flex items-center">
                        <i class="fa fa-clock mr-2"></i>
                        <span class="font-bold">{{ __('End Time') }}</span>
                    </label>
                    <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full event-input" :value="old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i'))" required />
                    <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                </div>
            </div>

            <!-- Description -->
            <div class="event-field">
                <label for="description" class="flex items-center">
                    <i class="fa fa-file-alt mr-2"></i>
                    <span class="font-bold">{{ __('Description') }}</span>
                </label>
                <textarea id="description" name="description" class="mt-1 block w-full event-input" required>{{ old('description', $event->description) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <!-- Address -->
            <div class="event-field">
                <label for="address" class="flex items-center">
                    <i class="fa fa-map-marker-alt mr-2"></i>
                    <span class="font-bold">{{ __('Address') }}</span>
                </label>
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full event-input" :value="old('address', $event->address)" required />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            <!-- Capacity -->
            <div class="event-field">
                <label for="capacity" class="flex items-center">
                    <i class="fa fa-users mr-2"></i>
                    <span class="font-bold">{{ __('Capacity') }}</span>
                </label>
                <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full event-input" :value="old('capacity', $event->capacity)" required />
                <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
            </div>

            <!-- Mode -->
            <div class="event-field">
                <label for="mode" class="flex items-center">
                    <i class="fa fa-tachometer-alt mr-2"></i>
                    <span class="font-bold">{{ __('Mode') }}</span>
                </label>
                <select id="mode" name="mode" class="block mt-1 w-full event-input">
                    <option value="">{{ __('Select Mode') }}</option>
                    <option value="onsite" {{ old('mode', $event->mode) == 'onsite' ? 'selected' : '' }}>{{ __('Onsite') }}</option>
                    <option value="virtual" {{ old('mode', $event->mode) == 'virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
                </select>
                <x-input-error :messages="$errors->get('mode')" class="mt-2" />
            </div>

            <!-- Event Banner -->
            <div class="event-field">
                <label for="event_banner" class="flex items-center">
                    <i class="fa fa-image mr-2"></i>
                    <span class="font-bold">{{ __('Event Banner') }}</span>
                </label>
                <x-text-input id="event_banner" name="event_banner" type="file" class="mt-1 block w-full event-input" accept="image/*" onchange="previewImage(event)" />
                <x-input-error class="mt-2" :messages="$errors->get('event_banner')" />
                @if($event->event_banner)
                    <img id="image_preview" src="{{ asset($event->event_banner) }}" alt="Event Banner" class="event-image-preview mt-4" style="max-width: 50%; margin-top: 10px;">
                    <div class="mt-2">
                        <label for="remove_event_banner">
                            <input type="checkbox" name="remove_event_banner" id="remove_event_banner"> {{ __('Remove Event Banner') }}
                        </label>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-4">
                <x-primary-button id="updateEventButton" type="submit" class="profile-edit-save-button" onclick="disableAfterClick(this)">
                    <i class="fa fa-save"></i> {{ __('Update Event') }}
                </x-primary-button>

                @if (session('status') === 'event-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                        {{ __('Saved.') }}
                    </p>
                @endif
            </div>
        </form>
    </div>
</section>

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

function disableAfterClick(button) {
    button.disabled = true;
    button.innerText = 'Updating...';
    button.form.submit();
}
</script>
@endsection
