<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
    // Validate the input fields
    $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults() // starts with default rules
                    ->min(8)          
                    ->mixedCase()     
                    ->letters()       
                    ->numbers()       
                    ->symbols()       
                    ->uncompromised()],
    ]);

    // Create the user
    $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => 3,  // Assign a default role
    ]);

    Auth::login($user);

    // Redirect to the email verification notice page instead of the dashboard
    return redirect()->route('register.step2');
    }
    public function showStep2()
    {
        $countries = Country::all();
        $regions = Region::all();
        return view('auth.register2', compact('countries', 'regions'),['user' => Auth::user()]);
    }

    public function registerStep2(Request $request): RedirectResponse
    {
        // Validate Step 2 fields
        $request->validate([
            'middle_name' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'salutation' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
            'country_id' => ['required', 'int'],
            'region_id' => ['required_if:country_id,177', 'int'], 
            'province_id' => ['required_if:country_id,177', 'int'], 
            'college' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:11', 'regex:/^\d+$/'],
            'birthdate' => ['required', 'date', 'before:today', 'after:1900-01-01'],
        ]);
        $user = Auth::user();
        $relativePath = $user->profile_picture;
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $relativePath = 'storage/images/profile_pictures/' . $filename;
            // Move the uploaded file to the desired directory
            $file->storeAs('/images/profile_pictures', $filename);
            // Update the profile picture path in the validated data
        } 
        $user->update([
            'salutation' => $request->salutation,
            'middle_name' => $request->middle_name,
            'gender' => $request->gender,
            'country_id' => $request->country_id,
            'region_id' => $request->region_id,
            'profile_picture' => $relativePath,
            'province_id' => $request->province_id,
            'college' => $request->college,
            'contact_number' => $request->contact_number,
            'birthdate' => $request->birthdate,
            'profile_completed' => true,
        ]);
        // Trigger the registered event to send email verification
        event(new Registered($user));
        
        // Redirect to email verification notice
        return redirect()->route('verification.notice');
    }
}
