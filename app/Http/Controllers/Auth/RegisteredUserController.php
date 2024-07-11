<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $countries = Country::all();
        return view('auth.register', compact('countries'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // 'middle_name' => ['nullable', 'string', 'max:255'],
            //'gender' => ['required', 'string', 'in:male,female'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            //'profile_picture' => ['nullable', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],
            'country_id' => ['nullable', 'int'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        // if ($request->hasFile('profile_picture')) {
        //     $file = $request->file('profile_picture');
        //     $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        //     $path = $file->move(public_path('assets/images/profile_pictures'), $filename);
        //     $path = 'assets/images/profile_pictures/' . $filename;
        // } else {
        //     $path = null;
        // }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            //'middle_name' => $request->middle_name,
            'email' => $request->email,
            //'profile_picture' => $path,
            //'gender' => $request->gender,
            'password' => Hash::make($request->password),
            //'country_id' => $request->country_id,
            'role_id' => 3,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
