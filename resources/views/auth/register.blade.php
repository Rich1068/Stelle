<link rel="stylesheet" href="/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->

<div class="registration-container">
    <!-- Left Side: Image Carousel -->
    <div class="image-carousel">
        <button class="carousel-arrow left-arrow">&larr;</button>
        <div class="carousel-wrapper"> <!-- Wrapper to contain the scrolling -->
            <div class="carousel-images">
                <img src="/images/ILLUSTRATION2.png" alt="Image 1">
                <img src="/images/illustration3.png" alt="Image 2">
                <img src="/images/illustration4.png" alt="Image 3">
                <!-- Add more images as needed -->
            </div>
        </div>
        <button class="carousel-arrow right-arrow">&rarr;</button>
        
        <!-- Progress bar -->
        <div class="carousel-progress">
            <div class="progress-fill"></div>
        </div>
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

            <!-- Sign Up Button -->
            <div class="flex items-center justify-center mt-4">
                <x-primary-button class="login-button">
                    {{ __('Sign Up') }}
                </x-primary-button>
            </div>

            <div class="or-divider">
                <span>or</span>
            </div>

            <!-- Google Login -->
            <div class="google-button">
                <a href="{{ route('google.redirect') }}">
                    <img src="/images/googleicon.png" alt="Google Icon" class="google-icon">
                    Login with Google
                </a>
            </div>
        </form>
    </div>
</div>

<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.bundle.min.js"></script>

<script>
// Carousel functionality
const carouselImages = document.querySelector('.carousel-images');
const images = carouselImages.querySelectorAll('img');
const imageWidth = images[0].offsetWidth + 70; // Image width plus margin
let scrollAmount = 0;

// Duplicate first and last images to create an infinite loop effect
const firstImage = images[0].cloneNode(true);
const lastImage = images[images.length - 1].cloneNode(true);
carouselImages.appendChild(firstImage);
carouselImages.insertBefore(lastImage, images[0]);

// Set the initial scroll position to the first real image
scrollAmount = imageWidth; 
carouselImages.scrollLeft = scrollAmount; // Start from the first image after the cloned last image

// Continuous scrolling function
function continuousScroll() {
    scrollAmount += 1; // Increment scroll amount by 1 pixel for smooth effect
    carouselImages.scrollTo({
        left: scrollAmount,
        behavior: 'instant' // Instantly jump to the new scroll position without animation
    });

    // Reset the scroll position for the infinite loop effect
    if (scrollAmount >= carouselImages.scrollWidth - carouselImages.clientWidth) {
        scrollAmount = imageWidth; // Jump back to the second image (first real image)
        carouselImages.scrollTo({
            left: scrollAmount,
            behavior: 'instant' // No smooth transition for immediate jump
        });
    }

    // Continue scrolling
    requestAnimationFrame(continuousScroll); // Use requestAnimationFrame for smooth animation
}

// Start the continuous scrolling
continuousScroll();

// Pause the scrolling when hovering
document.querySelector('.image-carousel').addEventListener('mouseover', () => {
    // Pause scrolling on mouseover
    carouselImages.style.animationPlayState = 'paused';
});

document.querySelector('.image-carousel').addEventListener('mouseout', () => {
    // Resume scrolling on mouseout
    carouselImages.style.animationPlayState = 'running';
});

// Manual controls
document.querySelector('.left-arrow').addEventListener('click', () => {
    scrollAmount -= imageWidth; // Scroll left
    carouselImages.scrollTo({
        left: scrollAmount,
        behavior: 'smooth'
    });
});

document.querySelector('.right-arrow').addEventListener('click', () => {
    scrollAmount += imageWidth; // Scroll right
    carouselImages.scrollTo({
        left: scrollAmount,
        behavior: 'smooth'
    });
});

</script>