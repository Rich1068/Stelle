<div class="profile-edit-container-wrapper">
    <section class="profile-edit-section">
        <header class="profile-edit-header">
            <h2 class="profile-edit-title text-lg font-medium text-gray-900">
                <i class="fas fa-user"></i> {{ __('Profile Information') }}
            </h2>

            <p class="profile-edit-description mt-1 text-sm text-gray-600">
                {{ __("Update your account's profile information") }}
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="profile-edit-verification-form">
            @csrf
        </form>

        <form method="post" action="{{ route('superadmin.updateProfile', ['id' => $user->id]) }}" class="profile-edit-form" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="profile-edit-container">
                <!-- First Name -->
                <div class="profile-edit-item">
                    <x-input-label for="first_name" class="profile-edit-label">
                        <i class="fas fa-user"></i> {{ __('First Name:') }}
                    </x-input-label>
                    <x-text-input id="first_name" name="first_name" type="text" class="profile-edit-input" :value="old('first_name', $user->first_name)" required autofocus autocomplete="first_name" placeholder="Enter First Name" />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('first_name')" />
                </div>

                <!-- Middle Name -->
                <div class="profile-edit-item">
                    <x-input-label for="middle_name" class="profile-edit-label">
                        <i class="fas fa-user"></i> {{ __('Middle Name:') }}
                    </x-input-label>
                    <x-text-input id="middle_name" name="middle_name" type="text" class="profile-edit-input" :value="old('middle_name', $user->middle_name)" autofocus autocomplete="middle_name" placeholder="Enter Middle Name" />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('middle_name')" />
                </div>

                <!-- Last Name -->
                <div class="profile-edit-item">
                    <x-input-label for="last_name" class="profile-edit-label">
                        <i class="fas fa-user"></i> {{ __('Last Name:') }}
                    </x-input-label>
                    <x-text-input id="last_name" name="last_name" type="text" class="profile-edit-input" :value="old('last_name', $user->last_name)" required autofocus autocomplete="last_name" placeholder="Enter Last Name" />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('last_name')" />
                </div>

                <!-- Email -->
                <div class="profile-edit-item">
                    <x-input-label for="email" class="profile-edit-label">
                        <i class="fas fa-envelope"></i> {{ __('Email:') }}
                    </x-input-label>
                    <x-text-input id="email" name="email" type="email" class="profile-edit-input" :value="old('email', $user->email)" required autocomplete="username" placeholder="example@example.com" />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('email')" />
                </div>

                <!-- Profile Picture -->
            
                            <div class="profile-edit-item">
                <x-input-label for="profile_picture" class="profile-edit-label">
                    <i class="fas fa-camera"></i> {{ __('Profile Picture:') }}
                </x-input-label>

                <div class="custom-file-upload">
                    <label for="profile_picture" class="custom-file-button">
                        <i class="fas fa-upload"></i> Choose File
                    </label>
                    <input id="profile_picture" name="profile_picture" type="file" class="profile-edit-input hidden-input" accept="image/*" onchange="previewImage(event)" />
                    
                    <!-- File name text below the button -->
                    <span id="file-name" class="file-name">No file chosen</span>
                </div>

                <x-input-error class="profile-edit-error" :messages="$errors->get('profile_picture')" />

                @if ($user->profile_picture)
                    <img id="image_preview" src="{{ asset($user->profile_picture) }}" alt="Profile Picture" class="profile-edit-image-preview">
                @else
                    <img id="image_preview" src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default Profile Picture" class="profile-edit-image-preview">
                @endif
            </div>


            </div>

            <!-- New row for side-by-side display -->
            <div class="profile-edit-row">
                <!-- Gender -->
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="gender" class="profile-edit-label">
                        <i class="fas fa-venus-mars"></i> {{ __('Gender:') }}
                    </x-input-label>
                    <select id="gender" name="gender" class="profile-edit-select">
                        <option value="">{{ __('Select Gender') }}</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="profile-edit-error" />
                </div>

                <!-- Contact Number -->
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="contact_number" class="profile-edit-label">
                        <i class="fas fa-phone"></i> {{ __('Contact Number:') }}
                    </x-input-label>
                    <x-text-input id="contact_number" name="contact_number" type="text" class="profile-edit-input" :value="old('contact_number', $user->contact_number)" autofocus autocomplete="contact_number" placeholder="123-456-7890" />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('contact_number')" />
                </div>

                <!-- Country -->
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="country" class="profile-edit-label">
                        <i class="fas fa-globe"></i> {{ __('Country:') }}
                    </x-input-label>
                    <select id="country" name="country_id" class="profile-edit-select">
                        <option value="">{{ __('Select Country') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->countryname }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('country_id')" class="profile-edit-error" />
                </div>
            </div>

            <!-- Description -->
            <div class="profile-edit-item profile-edit-item-full">
                <x-input-label for="description" class="profile-edit-label">
                    <i class="fas fa-pencil-alt"></i> {{ __('Description:') }}
                </x-input-label>
                <textarea id="description" name="description" class="profile-edit-textarea" autofocus autocomplete="description">{{ old('description', $user->description) }}</textarea>
                <x-input-error class="profile-edit-error" :messages="$errors->get('description')" />
            </div>

            <div class="profile-edit-item profile-edit-item-full">
                <x-input-label for="birthdate" class="profile-edit-label">
                    <i class="fas fa-pencil-alt"></i> {{ __('Birthdate:') }}
                </x-input-label>
                
                <!-- Use input type="date" for date picker -->
                <input id="birthdate" name="birthdate" type="date" class="profile-edit-textarea" 
                    value="{{ old('birthdate', $user->birthdate) }}" 
                    autofocus autocomplete="bday">
                    
                <x-input-error class="profile-edit-error" :messages="$errors->get('birthdate')" />
            </div>

            <div class="profile-edit-item profile-edit-actions">
                <x-primary-button class="profile-edit-save-button">
                    <i class="fas fa-save"></i> {{ __('Save') }}
                </x-primary-button>
            </div>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600 profile-edit-save-message">
                    {{ __('Saved.') }}
                </p>
            @endif
        </form>
    </section>
</div>
<style>
/* Hide the default file input */
.hidden-input {
    display: none;
}

.custom-file-upload {
    display: flex;
    flex-direction: column; /* Stack elements vertically */
    max-width: 80%;
    text-align: center;
}

.custom-file-button {
    display: inline-block;
    padding: 8px 16px;
    background-color: #003d80; /* Dark blue */
    color: #fff;
    border-radius: 15px; /* Rounded corners */
    cursor: pointer;
    font-size: 14px;
    margin-bottom: 5px; /* Space between button and file name text */
}

.custom-file-button:hover {
    background-color: #002b5c; /* Slightly darker shade for hover */
}

.file-name {
    font-size: 14px;
    color: #555;
    text-align: center;
}


    </style>
<script>

document.getElementById('profile_picture').addEventListener('change', function() {
    const fileName = this.files[0] ? this.files[0].name : 'No file chosen';
    document.getElementById('file-name').textContent = fileName;
});


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
