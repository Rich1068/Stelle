@extends('layouts.app')

@section('body')
@php
    $today = date('Y-m-d');
@endphp
<section class="event-form-container">
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
                    <i class="fas fa-calendar-edit"></i> {{ __('Edit Event') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    <i class="fas fa-info-circle"></i> {{ __("Update the details of your event.") }}
                </p>
            </header>
        </div>
        <form method="post" action="{{ route('event.update', $event->id) }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Title -->
        <div class="event-field">
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $event->title)" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <!-- Date & Time -->
        <div class="event-field event-datetime">
            <div class="event-date">
                <x-input-label for="date" :value="__('Date')" />
                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', $event->date)" :min="$today" required />
                <x-input-error class="mt-2" :messages="$errors->get('date')" />
            </div>
            <div class="event-time">
                <x-input-label for="start_time" :value="__('Start Time')" />
                <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" :value="old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i'))" required />
                <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
            </div>
            <div class="event-time">
                <x-input-label for="end_time" :value="__('End Time')" />
                <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i'))" required />
                <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
            </div>
        </div>

        <!-- Description -->
        <div class="event-field">
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description" class="mt-1 block w-full event-input" required>{{ old('description', $event->description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <!-- Address -->
        <div class="event-field">
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $event->address)" required />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- Capacity -->
        <div class="event-field">
            <x-input-label for="capacity" :value="__('Capacity')" />
            <x-text-input id="capacity" name="capacity" type="number" class="mt-1 block w-full" :value="old('capacity', $event->capacity)" required />
            <x-input-error class="mt-2" :messages="$errors->get('capacity')" />
        </div>

        <!-- Mode -->
        <div class="event-field">
            <x-input-label for="mode" :value="__('Mode')" />
            <select id="mode" name="mode" class="block mt-1 w-full event-input">
                <option value="">{{ __('Select Mode') }}</option>
                <option value="onsite" {{ old('mode', $event->mode) == 'onsite' ? 'selected' : '' }}>{{ __('Onsite') }}</option>
                <option value="virtual" {{ old('mode', $event->mode) == 'virtual' ? 'selected' : '' }}>{{ __('Virtual') }}</option>
            </select>
            <x-input-error :messages="$errors->get('mode')" class="mt-2" />
        </div>

        <!-- Event Banner -->
        <div class="event-field">
            <x-input-label for="event_banner" :value="__('Event Banner')" />
            <x-text-input id="event_banner" name="event_banner" type="file" class="mt-1 block w-full" accept="image/*" onchange="previewImage(event)" />
            <x-input-error class="mt-2" :messages="$errors->get('event_banner')" />
            @if($event->event_banner)
                <img id="image_preview" src="{{ asset($event->event_banner) }}" alt="Event Banner" class="mt-4" style="max-width: 75px;">
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
                <i class="fas fa-save"></i> {{ __('Update Event') }}
            </x-primary-button>

            @if (session('status') === 'event-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
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
</script>
@endsection