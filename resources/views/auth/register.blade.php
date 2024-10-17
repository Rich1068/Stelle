<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/custom.css"> <!-- Ensure custom styles are linked -->
</head>
<body>

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
            <img src="/images/stellelogo.png" alt="Stelle Logo" class="stelle-logo"> <!-- Stelle logo positioned at the bottom -->
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

<!-- Inline CSS to hide carousel on mobile screens and fix form layout -->
<style>

.stelle-logo {
    width: 130px; /* Adjust the width to fit better */
    height: auto; /* Maintain the aspect ratio */
    bottom: 20px; /* Distance from the bottom of the registration container */
    margin-bottom: 20px;

}

.register-account {
    position: relative; /* Ensure child elements (like the logo) can be positioned absolutely */
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.21);
    padding: 20px;
    width: 70%; /* Width of the registration form */
    margin: auto;
    text-align: center;
    box-sizing: border-box; /* Ensure padding is included in width calculation */
}


    body {
            margin: 0;
            background: linear-gradient(to bottom right, #F99C9C, #ACDFF6);
            background-size: 200% 200%; /* Infinite gradient effect */
            animation: gradientAnimation 10s ease infinite;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

    /* Right side: Registration form */
    .registration-container {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Two equal columns */
        max-height: 100vh; /* Full height of the viewport */
        width: 100vw; /* Full width of the viewport */
        padding: 0 75px; /* Add padding to bring the sections closer */
        gap: 10px; /* Optional: Add gap between the columns for more control */
    }

    .register-account {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.21);
        padding: 20px;
        width: 70%; /* Use most of the grid column width */
        margin: auto; /* Center within the grid cell */
        text-align: center;
        box-sizing: border-box; /* Ensure padding is included in width calculation */
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .registration-container {
            display: block;
            padding: 0; /* Reset padding for mobile */
            max-width: 100%; /* Use full width on mobile */
            margin: 0 auto;
        }

        .register-account {
            width: 90% !important; /* Ensure it takes up more space */
            max-width: 600px !important; /* Set a maximum width for better control */
            padding: 30px 20px !important; /* Add more padding for spacious look */
            margin: 20px auto !important; /* Add margin for breathing room */
            box-sizing: border-box !important; /* Ensure padding is included in width */
        }

        .register-input {
            width: calc(100% - 30px) !important; /* Ensure inputs don't overflow */
            margin-bottom: 20px !important; /* Add more space between inputs */
            padding: 14px 20px !important; /* Increase padding for better usability */
            font-size: 1.1rem !important; /* Increase font size for readability */
            box-sizing: border-box !important; /* Include padding in width calculation */
        }

        .login-button, .google-button {
            width: 100% !important; /* Full width for buttons */
            margin: 20px auto !important; /* Add some spacing below */
            padding: 14px 0 !important; /* More padding for better appearance */
        }

        .google-button img {
            max-width: 24px !important; /* Keep the Google icon size appropriate */
            margin-right: 10px;
        }
    }

    @media (max-width: 768px) {
        /* Hide the carousel on mobile */
        .image-carousel {
            display: none !important;
        }

        /* Widen the registration container */
        .registration-container {
            width: 90% !important;
            max-width: 500px !important; /* Set a max-width for better responsiveness */
            margin: 0 auto !important; /* Center the form on mobile */
        }

        /* Make sure the form fields and buttons take up more space */
        .register-account {
            width: 100% !important;
            padding: 20px !important; /* Add some padding for better spacing */
            box-sizing: border-box !important; /* Include padding in width calculation */
        }

        /* Ensure inputs and buttons are full-width on mobile */
        .register-input, .login-button, .google-button {
            width: 100% !important;
            font-size: 1rem !important; /* Adjust font size for better readability */
            padding: 12px !important; /* Adjust padding for input fields */
            margin-bottom: 20px !important; /* Ensure there's enough space between elements */
        }

       /* Fix the Google button width and alignment */
.google-button {
    width: 100% !important; /* Full width of the container */
    padding: 12px !important; /* Add padding for better appearance */
    margin-top: 20px !important; /* Space above the Google button */
    box-sizing: border-box !important; /* Include padding and borders in the width calculation */
    border-radius: 10px !important; /* Match the style of other buttons */
    display: flex !important; /* Align content horizontally */
    align-items: center !important; /* Vertically center icon and text */
    justify-content: center !important; /* Horizontally center icon and text */
}

.google-button img {
    max-width: 24px !important; /* Adjust icon size */
    margin-right: 10px !important; /* Add space between icon and text */
}

.google-button a {
    text-decoration: none;
    color: #7c7c7c !important; /* Ensure the text color stays gray */
    font-size: 1rem !important;
    font-weight: bold !important;
}

.google-button:hover {
    background-color: #001f60 !important; /* Background color on hover */
    color: white !important; /* Text color on hover */
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2) !important; /* Slight shadow for depth */
    transition: background-color 0.3s ease, box-shadow 0.3s ease !important; /* Smooth hover transition */
}

.google-button:hover a {
    color: white !important; /* Change the text color to white on hover */
}

    }
</style>

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

</body>
</html>
