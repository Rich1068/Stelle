@extends('layouts.app')

@section('body')
@php
    $today = date('Y-m-d');
@endphp
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            <i class="fas fa-calendar-plus"></i> {{ __('Create Event') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            <i class="fas fa-info-circle"></i> {{ __("Fill out the form below to create a new event.") }}
        </p>
    </header>

    <form method="post" action="{{ route('events.store') }}" class="event-form mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <!-- Title -->
        <div class="event-field">
            <label for="title" class="flex items-center">
                <i class="fas fa-heading mr-2"></i>
                <span class="font-bold">{{ __('Title') }}</span>
            </label>
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full event-input" :value="old('title')" required autofocus autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <!-- Description -->
        <div class="event-field">
            <label for="description" class="flex items-center">
                <i class="fas fa-file-alt mr-2"></i>
                <span class="font-bold">{{ __('Description') }}</span>
            </label>
            <textarea id="description" name="description" class="mt-1 block w-full event-input" required>{{ old('description') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <!-- Date & Time -->
        <div class="event-field event-datetime">
            <div>
                <label for="date" class="flex items-center">
                    <i class="fas fa-calendar-day mr-2"></i>
                    <span class="font-bold">{{ __('Date') }}</span>
                </label>
                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full event-input" :value="old('date')" :min="$today" required />
                <x-input-error class="mt-2" :messages="$errors->get('date')" />
            </div>
            <div>
                <label for="start_time" class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <span class="font-bold">{{ __('Start Time') }}</span>
                </label>
                <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full event-input" :value="old('start_time')" required />
                <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
            </div>
            <div>
                <label for="end_time" class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <span class="font-bold">{{ __('End Time') }}</span>
                </label>
                <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full event-input" :value="old('end_time')" required />
                <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
            </div>
        </div>

<!-- Capacity and Mode Vertically Aligned -->
<div class="event-field-container">
    <!-- Capacity -->
    <div class="event-field capacity-field">
        <label for="capacity" class="flex items-center">
            <i class="fas fa-users mr-2"></i>
            <span class="font-bold">{{ __('Capacity') }}</span>
        </label>
        <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full event-input" :value="old('capacity')" required />
        <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
    </div>

    <!-- Mode -->
    <div class="event-field mode-field">
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


        <!-- Event Banner -->
        <div class="event-field">
            <label for="event_banner" class="flex items-center">
                <i class="fas fa-image mr-2"></i>
                <span class="font-bold">{{ __('Event Banner') }}</span>
            </label>
            <x-text-input id="event_banner" name="event_banner" type="file" class="mt-1 block w-full event-input" accept="image/*" onchange="previewImage(event)" />
            <x-input-error class="mt-2" :messages="$errors->get('event_banner')" />

            <img id="image_preview" class="event-image-preview" />
        </div>

<!-- Submit Button -->
<div class="flex items-center gap-4">
    <x-primary-button id="createEventButton" type="submit" class="profile-edit-save-button" onclick="disableAfterClick(this)">
        <i class="fas fa-users"></i> {{ __('Create Event') }}
    </x-primary-button>
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
function disableAfterClick(button) {
    button.disabled = true;
    button.innerHTML = 'Processing...';
    setTimeout(() => {
        button.disabled = false;
        button.innerHTML = 'Create Event';
    }, 2000);
}
</script>
@endsection
