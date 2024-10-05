<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info('User attempting to log in', ['email' => $request->input('email')]);
        // Authenticate the user
        $request->authenticate();

        // Regenerate session
        $request->session()->regenerate();

        // Check if the user's email is verified
        if (!$request->user()->hasVerifiedEmail()) {
            // If not verified, redirect to the email verification notice page
            return redirect()->route('verification.notice');
        }

        // Check the role and redirect accordingly
        if ($request->user()->role_id == 1) {
            return redirect()->route('super_admin.dashboard');
        } elseif ($request->user()->role_id == 2) {
            return redirect()->route('admin.dashboard');
        } elseif ($request->user()->role_id == 3) {
            return redirect()->route('user.dashboard');
        }
        Log::info('Default login redirection for user', ['user_id' => $request->user()->id]);
        // Default redirect if no role matches
        return redirect()->intended(route('auth.login', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
