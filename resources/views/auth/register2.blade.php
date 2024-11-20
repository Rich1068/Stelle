<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stelle</title>
    <link rel="icon" href="{{ asset('images/stelle_icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->
    <!-- <script>
        document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === "F12") {
                event.preventDefault();
            }
            if (event.ctrlKey && (event.shiftKey && ['I', 'J'].includes(event.key)) || event.key === 'U') {
                event.preventDefault();
            }
        });
    </script> -->
</head>
<form method="POST" action="{{ route('register.step2.submit') }}" enctype="multipart/form-data">
    @csrf

    <div class="profile-edit-container">
                <!-- First Name -->
                <div class="profile-edit-item">
                    <x-input-label for="first_name" class="profile-edit-label">
                        <i class="fas fa-user"></i> {{ __('First Name:') }}
                    </x-input-label>
                    <x-text-input id="first_name" name="first_name" type="text" class="profile-edit-input" :value="old('first_name', $user->first_name)" disabled  />
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
                    <x-text-input id="last_name" name="last_name" type="text" class="profile-edit-input" :value="old('last_name', $user->last_name)" disabled  />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('last_name')" />
                </div>
                <div class="profile-edit-item">
                    <x-input-label for="salutation" class="profile-edit-label">
                        <i class="fas fa-user"></i> {{ __('Salutation:') }}
                    </x-input-label>
                    <x-text-input id="salutation" name="salutation" type="text" class="profile-edit-input" :value="old('salutation', $user->salutation)" autofocus autocomplete="salutation" placeholder="Example: Dr., Mr., Ms. "   />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('salutation')" />
                </div>
                <!-- Email -->
                <div class="profile-edit-item">
                    <x-input-label for="email" class="profile-edit-label">
                        <i class="fas fa-envelope"></i> {{ __('Email:') }}
                    </x-input-label>
                    <x-text-input id="email" name="email" type="email" class="profile-edit-input" :value="old('email', $user->email)" disabled  />
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
                        <i class="fas fa-venus-mars"></i> {{ __('Gender:') }} <span style="color:#ff3333;">*</span>
                    </x-input-label>
                    <select id="gender" name="gender" class="profile-edit-select">
                        <option value="">{{ __('Select Gender') }}</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="profile-edit-error" style="color:#ff3333;"/>
                </div>

                <!-- Contact Number -->
                <div class="profile-edit-item profile-edit-item-half">
                    <x-input-label for="contact_number" class="profile-edit-label">
                        <i class="fas fa-phone"></i> {{ __('Contact Number:') }} <span style="color:#ff3333;">*</span>
                    </x-input-label>
                    <x-text-input id="contact_number" name="contact_number" type="text" class="profile-edit-input" :value="old('contact_number', $user->contact_number)" autofocus autocomplete="contact_number" placeholder="123-456-7890" />
                    <x-input-error class="profile-edit-error" :messages="$errors->get('contact_number')" style="color:#ff3333;"/>
                </div>

                <!-- Country -->
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
            </div>
            <!-- Region -->
            <div class="profile-edit-item profile-edit-item-half" id="region-container" style="display: none;">
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
                <x-input-error :messages="$errors->get('region_id')" class="profile-edit-error" style="color:#ff3333;"/>
            </div>
            <!-- Province -->
            <div class="profile-edit-item profile-edit-item-half" id="province-container" style="display: none;">
                <x-input-label for="province" class="profile-edit-label">
                    <i class="fas fa-map-pin"></i> {{ __('Province:') }} <span style="color:#ff3333;">*</span>
                </x-input-label>
                <select id="province" name="province_id" class="profile-edit-select">
                    <option value="">{{ __('Select Province') }}</option>
                </select>
                <x-input-error :messages="$errors->get('province_id')" class="profile-edit-error" style="color:#ff3333;"/>
            </div>
            <!-- college -->
            <div class="profile-edit-item profile-edit-item-full">
                <x-input-label for="college" class="profile-edit-label">
                    <i class="fas fa-university"></i> {{ __('College/University:') }} <span style="color:#ff3333;">*</span>
                </x-input-label>
                <textarea id="college" name="college" placeholder="Example: Angeles University Foundation" class="profile-edit-textarea" required autofocus autocomplete="college">{{ old('college', $user->college) }}</textarea>
                <x-input-error class="profile-edit-error" :messages="$errors->get('college')" style="color:#ff3333;"/>
            </div>

            <div class="profile-edit-item profile-edit-item-full">
                <x-input-label for="birthdate" class="profile-edit-label">
                    <i class="fas fa-pencil-alt"></i> {{ __('Birthdate:') }} <span style="color:#ff3333;">*</span>
                </x-input-label>
                
                <!-- Use input type="date" for date picker -->
                <input id="birthdate" name="birthdate" type="date" class="profile-edit-textarea" 
                    value="{{ old('birthdate', $user->birthdate) }}" required
                    autofocus autocomplete="bday">
                    
                <x-input-error class="profile-edit-error" :messages="$errors->get('birthdate')" style="color:#ff3333;"/>
            </div>

    <!-- Submit Button -->
    <div class="profile-edit-item profile-edit-item-full">
        <button type="submit" class="profile-edit-button">
            {{ __('Complete Registration') }}
        </button>
    </div>
</form>

<style>
    .profile-edit-item {
        margin-bottom: 15px;
    }

    .profile-edit-item-half {
        width: 48%;
        display: inline-block;
    }

    .profile-edit-item-full {
        width: 100%;
    }

    .profile-edit-label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .profile-edit-select,
    .profile-edit-input {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .profile-edit-error {
        color: #ff3333;
        font-size: 0.9rem;
    }

    .profile-edit-button {
        background-color: #001f60;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        display: block;
        width: 100%;
    }

    .profile-edit-button:hover {
        background-color: #004080;
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

function loadProvinces(regionId, selectedProvince = null) {
    const provinceSelect = document.getElementById('province');

    if (regionId) {
        fetch(`/get-provinces/${regionId}`)
            .then(response => response.json())
            .then(data => {
                provinceSelect.innerHTML = '<option value="">{{ __('Select Province') }}</option>'; // Reset provinces
                data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.provDesc;
                    if (selectedProvince && selectedProvince == province.id) {
                        option.selected = true; // Set the selected province
                    }
                    provinceSelect.appendChild(option);
                });
            });
    } else {
        provinceSelect.innerHTML = '<option value="">{{ __('Select Province') }}</option>'; // Reset province if no region is selected
    }
}


function handleCountryChange(countryId, selectedRegion = null, selectedProvince = null) {
    const regionContainer = document.getElementById('region-container');
    const provinceContainer = document.getElementById('province-container');

    // Show region and province if country is the Philippines (replace '177' with the actual ID for Philippines)
    if (countryId == '177') {
        regionContainer.style.display = 'block';
        provinceContainer.style.display = 'block';

        // If region is already selected (on page load), load provinces
        if (selectedRegion) {
            document.getElementById('region').value = selectedRegion;
            loadProvinces(selectedRegion, selectedProvince);
        }
    } else {
        regionContainer.style.display = 'none';
        provinceContainer.style.display = 'none';
        document.getElementById('region').value = ''; // Reset region
        document.getElementById('province').innerHTML = '<option value="">{{ __('Select Province') }}</option>'; // Reset province
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const countrySelect = document.getElementById('country');
    const selectedCountry = countrySelect.value; // Pre-selected country
    const selectedRegion = "{{ old('region_id', $user->region_id) }}"; // Pre-selected region from server
    const selectedProvince = "{{ old('province_id', $user->province_id) }}"; // Pre-selected province from server

    // Handle initial load (pre-selected country, region, and province)
    handleCountryChange(selectedCountry, selectedRegion, selectedProvince);

    // Event listener for country change
    countrySelect.addEventListener('change', function () {
        handleCountryChange(this.value);
    });

    // Event listener for region change
    document.getElementById('region').addEventListener('change', function () {
        const regionId = this.value;
        loadProvinces(regionId);
    });
});
</script>
</html>
