<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt or redirect based on role if verified.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // If the user's email is verified, redirect based on their role
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($request->user());
        }

        // If not verified, show the email verification prompt
        return view('auth.verify-email');
    }

    /**
     * Redirect based on user role.
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        // Redirect based on role_id
        if ($user->role_id == 1) {
            return redirect()->route('super_admin.dashboard');
        } elseif ($user->role_id == 2) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role_id == 3) {
            return redirect()->route('user.dashboard');
        }

        // Default redirect if no role matches
        return redirect()->intended(route('dashboard'));
    }
}
