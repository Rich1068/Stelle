<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification or redirect based on role if already verified.
     */
    public function store(Request $request): RedirectResponse
    {
        // Check if the user has already verified their email
        if ($request->user()->hasVerifiedEmail()) {
            // If the email is already verified, redirect based on the user's role
            return $this->redirectBasedOnRole($request->user());
        }

        // If the email is not verified, send the verification notification
        $request->user()->sendEmailVerificationNotification();

        // Redirect back with a status indicating the link was sent
        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Redirect based on the user's role.
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        // Redirect to the appropriate dashboard based on the user's role_id
        if ($user->role_id == 1) {
            return redirect()->route('super_admin.dashboard');
        } elseif ($user->role_id == 2) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role_id == 3) {
            return redirect()->route('user.dashboard');
        }

        // Default redirection if no specific role match
        return redirect()->intended(route('dashboard'));
    }
}
