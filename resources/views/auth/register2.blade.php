<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stelle</title>
    <link rel="icon" href="{{ asset('images/stelle_icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->
</head>
<body>
    <form method="POST" action="{{ route('register.step2.submit') }}" enctype="multipart/form-data">
        @csrf

        <div class="profile-edit-container">

        <div class="logo-sectionz">
        <img src="{{ asset('images/stellelogo.png') }}" alt="Logo" class="logo-image" />
    </div>

    <!-- Divider Section -->
    <div class="dividerz">
        <span class="divider-text">Please complete your registration by filling out all required fields</span>
    </div>
            <!-- First Name & Middle Name -->
    <div class="profile-edit-row">
        <div class="profile-edit-item profile-edit-item-half">
            <x-input-label for="first_name" class="profile-edit-label">
                <i class="fas fa-user"></i> {{ __('First Name:') }}
            </x-input-label>
            <x-text-input id="first_name" name="first_name" type="text" class="profile-edit-input" :value="old('first_name', $user->first_name)" disabled  />
            <x-input-error class="profile-edit-error" :messages="$errors->get('first_name')" />
        </div>
        <div class="profile-edit-item profile-edit-item-half">
            <x-input-label for="middle_name" class="profile-edit-label">
                <i class="fas fa-user"></i> {{ __('Middle Name:') }}
            </x-input-label>
            <x-text-input id="middle_name" name="middle_name" type="text" class="profile-edit-input" :value="old('middle_name', $user->middle_name)" autofocus autocomplete="middle_name" placeholder="Enter Middle Name" />
            <x-input-error class="profile-edit-error" :messages="$errors->get('middle_name')" />
        </div>
    </div>
            <!-- Last Name & Salutation -->
            <div class="profile-edit-row">
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="last_name" class="profile-edit-label">
                        <i class="fas fa-user"></i> {{ __('Last Name:') }}
                    </x-input-label>
                    <x-text-input id="last_name" name="last_name" type="text" class="profile-edit-input" :value="old('last_name', $user->last_name)" disabled  />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('last_name')" />
                </div>
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="salutation" class="profile-edit-label">
                        <i class="fas fa-user"></i> {{ __('Salutation:') }}
                    </x-input-label>
                    <x-text-input id="salutation" name="salutation" type="text" class="profile-edit-input" :value="old('salutation', $user->salutation)" autofocus autocomplete="salutation" placeholder="Example: Dr., Mr., Ms. "   />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('salutation')" />
                </div>
            </div>

            <!-- Email & Contact Number -->
            <div class="profile-edit-row">
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="email" class="profile-edit-label">
                        <i class="fas fa-envelope"></i> {{ __('Email:') }}
                    </x-input-label>
                    <x-text-input id="email" name="email" type="email" class="profile-edit-input" :value="old('email', $user->email)" disabled  />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('email')" />
                </div>
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="contact_number" class="profile-edit-label">
                        <i class="fas fa-phone"></i> {{ __('Contact Number:') }} <span style="color:#ff3333;">*</span>
                    </x-input-label>
                    <x-text-input id="contact_number" name="contact_number" type="text" class="profile-edit-input" :value="old('contact_number', $user->contact_number)" autofocus autocomplete="contact_number" placeholder="123-456-7890" />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('contact_number')" style="color:#ff3333;"/>
                </div>
            </div>

            <!-- Gender & Birthdate -->
            <div class="profile-edit-row">
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="gender" class="profile-edit-label">
                        <i class="fas fa-venus-mars"></i> {{ __('Gender:') }} <span style="color:#ff3333;">*</span>
                    </x-input-label>
                    <select id="gender" name="gender" class="profile-edit-select">
                        <option value="">{{ __('Select Gender') }}</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="profile-edit-error" style="color:#ff3333;"/>
                </div>
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="birthdate" class="profile-edit-label">
                        <i class="fas fa-pencil-alt"></i> {{ __('Birthdate:') }} <span style="color:#ff3333;">*</span>
                    </x-input-label>
                    <input id="birthdate" name="birthdate" type="date" class="profile-edit-textarea" value="{{ old('birthdate', $user->birthdate) }}" required autofocus autocomplete="bday">
                    <x-input-error class="profile-edit-error" :messages="$errors->get('birthdate')" style="color:#ff3333;"/>
                </div>
            </div>

           <!-- Country & College/University -->
<div class="profile-edit-row">
    <div class="profile-edit-item profile-edit-item-half">
        <x-input-label for="country" class="profile-edit-label">
            <i class="fas fa-globe"></i> {{ __('Country:') }} <span style="color:#ff3333;">*</span>
        </x-input-label>
        <select id="country" name="country_id" class="profile-edit-select">
            <option value="">{{ __('Select Country') }}</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}" {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                    {{ $country->countryname }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('country_id')" class="profile-edit-error" style="color:#ff3333;"/>
    </div>

    <div class="profile-edit-item profile-edit-item-half">
        <x-input-label for="college" class="profile-edit-label">
            <i class="fas fa-university"></i> {{ __('College/University:') }} <span style="color:#ff3333;">*</span>
        </x-input-label>
        <x-text-input id="college" name="college" type="text" class="profile-edit-input" :value="old('college', $user->college)" autofocus autocomplete="college" placeholder="Enter College/University" />
        <x-input-error class="profile-edit-error" :messages="$errors->get('college')" style="color:#ff3333;"/>
    </div>
</div>

<!-- Province & Region -->
<div class="profile-edit-row">
    <!-- Region Field -->
    <div class="custom profile-edit-item-half" id="region-container" style="display: none;">
        <x-input-label for="region" class="profile-edit-label">
            <i class="fas fa-map"></i> {{ __('Region:') }} <span style="color:#ff3333;">*</span>
        </x-input-label>
        <select id="region" name="region_id" class="profile-edit-select">
            <option value="">{{ __('Select Region') }}</option>
            @foreach($regions as $region)
                <option value="{{ $region->id }}" {{ old('region_id', $user->region_id) == $region->id ? 'selected' : '' }}>
                    {{ $region->regDesc }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('region_id')" class="profile-edit-error" style="color:#ff3333;" />
    </div>

    <!-- Province Field -->
    <div class="custom profile-edit-item-half" id="province-container" style="display: none;">
        <x-input-label for="province" class="profile-edit-label">
            <i class="fas fa-map-pin"></i> {{ __('Province:') }} <span style="color:#ff3333;">*</span>
        </x-input-label>
        <select id="province" name="province_id" class="profile-edit-select">
            <option value="">{{ __('Select Province') }}</option>
        </select>
        <x-input-error :messages="$errors->get('province_id')" class="profile-edit-error" style="color:#ff3333;" />
    </div>
</div>


        <!-- Profile Picture -->
<div class="profile-edit-row">
    <div class="profile-edit-item">
        <x-input-label for="profile_picture" class="profile-edit-label">
            <i class="fas fa-camera"></i> {{ __('Profile Picture:') }}
        </x-input-label>
        <!-- Custom file input -->
        <div class="custom-file-container">
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="file-input" onchange="previewImage(event)" />
            <label for="profile_picture" class="file-input-label">Choose File</label>
            <span id="file-name" class="file-name"></span>
        </div>

        <!-- Image Preview Container -->
        <div id="image-preview-container" class="image-preview-container">
            @if ($user->profile_picture)
                <img id="image_preview" src="{{ asset($user->profile_picture) }}" alt="Profile Picture" class="profile-edit-image-preview">
            @else
                <img id="image_preview" src="{{ asset('storage/images/profile_pictures/default.jpg') }}" alt="Default Profile Picture" class="profile-edit-image-preview">
            @endif
        </div>

        <x-input-error class="profile-edit-error" :messages="$errors->get('profile_picture')" />
    </div>
</div>
            <!-- Submit Button -->
            <div class="profile-edit-item profile-edit-item-full">
                <button type="submit" class="profile-edit-button custom-button">
                    {{ __('Complete Registration') }}
                </button>
                <hr>

    </form>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <span class="text-sm logout-span" style="cursor: pointer;" onclick="this.closest('form').submit();">
                    {{ __('Log Out') }}
                </span>
            </form>
    </div>
</div>
<style>
    #region-container,
    #province-container {
        display: none; !important 
    }
    .custom {
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        text-align: center !important;
        flex: 0 0 45%;
        max-width: 40%;
        margin-bottom: 10px;
    }
</style>
<script>
function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function() {
        const imagePreview = document.getElementById('image-preview');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        
        imagePreview.src = reader.result;
        imagePreviewContainer.style.display = 'block'; // Show the preview container
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}


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

function loadProvinces(regionId, selectedProvince = null) {
    const provinceSelect = document.getElementById('province');

    if (regionId && !isNaN(regionId)) {
        fetch(`/get-provinces/${regionId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                provinceSelect.innerHTML = '<option value="">Select Province</option>'; // Reset provinces
                if (data.length > 0) {
                    data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.provDesc;
                        if (selectedProvince && selectedProvince == province.id) {
                            option.selected = true;
                        }
                        provinceSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching provinces:', error);
                provinceSelect.innerHTML = '<option value="">Failed to load provinces</option>';
            });
    } else {
        provinceSelect.innerHTML = '<option value="">Select Province</option>'; // Reset province
    }
}

function handleCountryChange(countryId, selectedRegion = null, selectedProvince = null) {
    const regionContainer = document.getElementById('region-container');
    const provinceContainer = document.getElementById('province-container');

    // Show region and province if country is Philippines (ID = 177)
    if (countryId == '177') {
        regionContainer.style.display = 'block';
        provinceContainer.style.display = 'block';

        // Load provinces if a region is pre-selected
        if (selectedRegion) {
            document.getElementById('region').value = selectedRegion;
            loadProvinces(selectedRegion, selectedProvince);
        }
    } else {
        regionContainer.style.display = 'none';
        provinceContainer.style.display = 'none';
        document.getElementById('region').value = ''; // Reset region dropdown
        document.getElementById('province').innerHTML = '<option value="">Select Province</option>'; // Reset province dropdown
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const countrySelect = document.getElementById('country');
    const selectedCountry = countrySelect.value; // Get pre-selected country ID
    const selectedRegion = "{{ old('region_id', $user->region_id) }}";
    const selectedProvince = "{{ old('province_id', $user->province_id) }}";

    // Trigger initial load for hiding/showing fields
    handleCountryChange(selectedCountry, selectedRegion, selectedProvince);

    // Add event listener for country dropdown change
    countrySelect.addEventListener('change', function () {
        handleCountryChange(this.value);
    });

    // Add event listener for region dropdown change
    document.getElementById('region').addEventListener('change', function () {
        const regionId = this.value;
        loadProvinces(regionId);
    });
});
</script>
</html>
