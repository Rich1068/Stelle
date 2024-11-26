@extends('layouts.app')

@section('body')

<div class="top-container">
    <h2 class="font-weight-bold mb-0">
        <i class="fas fa-list me-2"></i> <!-- List icon -->
        Edit Organization
    </h2>
</div>
<section class="event-form-container">
    <div class="event-form">

        <!-- Form Section -->
        <form method="post" action="{{ route('organization.update', $organization->id) }}" enctype="multipart/form-data" id="eventForm">
            @csrf
            @method('POST')

            <div class="custom-bg-white" style="border-radius: 15px; background-color: white; padding-top: 20px;">
                <!-- Name -->
                <div class="col-md-12 mb-4">
                    <div class="event-field">
                        <label for="name" class="flex items-center">
                            <i class="fas fa-heading mr-2"></i>
                            <span class="font-bold">{{ __('Name') }}</span>
                            <span style="color:#ff3333;">*</span>
                        </label>
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full event-input" :value="old('name', $organization->name)" required autofocus autocomplete="off" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                </div>
            <!-- Description -->
            <div class="col-md-12 mb-4">
                <div class="event-field">
                    <label for="description" class="flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>
                        <span class="font-bold">{{ __('Description') }}</span>
                    </label>
                    <textarea id="description" name="description" class="mt-1 block w-full event-input" oninput="autoResize(this)" rows="1" style="overflow:hidden;" >{{ old('description', $organization->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
            </div>
                <!-- Capacity, Mode, and Address in One Column -->
                <div class="col-md-12 mb-4">
                    <div class="row">
                        <!-- Address -->
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="contact_email" class="flex items-center">
                                    <i class="fas fa-solid fa-envelope"></i>
                                    <span class="font-bold">{{ __('Contact Email') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <x-text-input id="contact_email" name="contact_email" type="text" class="mt-1 block w-full event-input" :value="old('contact_email', $organization->contact_email)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('contact_email')" />
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="contact_phone" class="flex items-center">
                                    <i class="fas fa-solid fa-phone"></i>
                                    <span class="font-bold">{{ __('Contact Number') }}</span>
                                    <span style="color:#ff3333;">*</span>
                                </label>
                                <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full event-input" :value="old('contact_phone', $organization->contact_phone)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('contact_phone')" />
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="event-field">
                                <label for="icon" class="flex items-center">
                                    <i class="fas fa-image mr-2"></i>
                                    <span class="font-bold">{{ __('Event Icon') }}</span>
                                </label>
                                <!-- Hide the original file input -->
                                <input id="icon" name="icon" type="file" accept="image/*" onchange="previewImage(event)" style="display: none;" />
                                
                                <!-- Custom button to trigger file input -->
                                <button type="button" class="custom-file-button" onclick="document.getElementById('icon').click()">
                                    <i class="fas fa-upload"></i> <span class="bold-text">Choose File</span>
                                </button>
                                
                                <x-input-error class="mt-2" :messages="$errors->get('icon')" />
                                <img id="image_preview" class="event-image-preview" 
                                    src="{{ $organization->icon ? asset($organization->icon) : '' }}" 
                                    style="display: {{ $organization->icon ? 'block' : 'none' }}; max-width: 50%; margin-top: 10px;" />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 mb-4">
                    <x-primary-button id="createEventButton" type="submit" class="btn-primary"  onclick="disableButton(this)">
                        <i class="fas fa-save"></i> {{ __('Create Organization') }}
                    </x-primary-button>
                </div>
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
        max-width: 100%;
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

<script>

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
