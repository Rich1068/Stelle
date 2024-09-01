<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->

<div class="registration-container">
    <!-- Left Side: Image Carousel -->
    <div class="image-carousel">
        <button class="carousel-arrow left-arrow">&larr;</button>
        <div class="carousel-wrapper"> <!-- Wrapper to contain the scrolling -->
            <div class="carousel-images">
                <img src="/images/ILLUSTRATION2.png" alt="Image 1">
                <img src=/images/illustration3.png alt="Image 2">
                <img src=/images/illustration4.png  alt="Image 3">
                <!-- Add more images as needed -->
            </div>
        </div>
        <button class="carousel-arrow right-arrow">&rarr;</button>
    </div>


    <!-- Right Side: Registration Form -->
    <div class="register-account">
        <a class="back-to-login" href="{{ route('login') }}">Back To Login</a>
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

 <!-- First Name -->
 <div class="input-group">
                <x-input-label for="first_name" class="register-label">
                    {{ __('First Name') }}
                </x-input-label>
                <x-text-input id="first_name" class="register-input" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first-name" placeholder="Input First Name Here" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="input-group">
                <x-input-label for="last_name" class="register-label">
                    {{ __('Last Name') }}
                </x-input-label>
                <x-text-input id="last_name" class="register-input" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last-name" placeholder="Input Last Name Here" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="input-group">
                <x-input-label for="email" class="register-label">
                    {{ __('Email') }}
                </x-input-label>
                <x-text-input id="email" class="register-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Input Email Here" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="input-group">
                <x-input-label for="password" class="register-label">
                    {{ __('Create Password') }}
                </x-input-label>
                <x-text-input id="password" class="register-input" type="password" name="password" required autocomplete="new-password" placeholder="Create Password Here" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="input-group">
                <x-input-label for="password_confirmation" class="register-label">
                    {{ __('Retype Password') }}
                </x-input-label>
                <x-text-input id="password_confirmation" class="register-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Retype Password Here" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="register-button">
                    {{ __('Sign Up') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>

<script>
const leftArrow = document.querySelector('.left-arrow');
const rightArrow = document.querySelector('.right-arrow');
const carouselImages = document.querySelector('.carousel-images');

let scrollAmount = 0;

leftArrow.addEventListener('click', () => {
    const imageWidth = carouselImages.querySelector('img').offsetWidth + 70; // Image width plus margin
    scrollAmount -= imageWidth; // Move left by image width plus margin
    if (scrollAmount < 0) scrollAmount = 0; // Prevent scrolling before the start
    carouselImages.scrollTo({
        left: scrollAmount,
        behavior: 'smooth'
    });
});

rightArrow.addEventListener('click', () => {
    const imageWidth = carouselImages.querySelector('img').offsetWidth + 70; // Image width plus margin
    scrollAmount += imageWidth; // Move right by image width plus margin
    if (scrollAmount > carouselImages.scrollWidth - carouselImages.clientWidth) {
        scrollAmount = carouselImages.scrollWidth - carouselImages.clientWidth; // Prevent scrolling after the end
    }
    carouselImages.scrollTo({
        left: scrollAmount,
        behavior: 'smooth'
    });
});

</script>