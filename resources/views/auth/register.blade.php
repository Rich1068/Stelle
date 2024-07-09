<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="first_name">
                {{ __('First Name') }}<span style="color: red;">*</span>
            </x-input-label>
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Middle Name -->
        <!-- <div class="mt-4">
            <x-input-label for="middle_name" :value="__('Middle Name')" />
            <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name')" autofocus autocomplete="middle-name" />
            <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
        </div> -->

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name">
                {{ __('Last Name') }}<span style="color: red;">*</span>
            </x-input-label>
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email">
                {{ __('Email') }}<span style="color: red;">*</span>
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password">
                {{ __('Password') }}<span style="color: red;">*</span>
            </x-input-label>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation">
                {{ __('Confirm Password') }}<span style="color: red;">*</span>
            </x-input-label>
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- <div class="mt-4">
            <x-input-label for="gender">
                {{ __('Gender') }}<span style="color: red;">*</span>
            </x-input-label>
            <select id="gender" name="gender" class="block mt-1 w-full" required>
                <option value="">{{ __('Select Gender') }}</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div> -->

        <!-- <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <select id="country" name="country_id" class="block mt-1 w-full" required>
                <option value="">{{ __('Select Country') }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                        {{ $country->countryname }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
        </div> -->

        <!-- <div class="mt-4">
        <x-input-label for="profile_picture">
            {{ __('Profile Picture') }}
        </x-input-label>
        <x-text-input id="profile_picture" class="block mt-1 w-full" type="file" name="profile_picture" accept="image/*" onchange="previewImage(event)" />
        <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
        <img id="image_preview" alt="Image Preview" class="mt-4" style="max-width: 200px; display: none;">
        </div> -->

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<!-- <script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('image_preview');
        output.src = reader.result;
        output.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script> -->