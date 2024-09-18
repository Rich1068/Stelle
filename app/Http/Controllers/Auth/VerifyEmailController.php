<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // If the user has already verified their email, redirect them to the appropriate dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectBasedOnRole($request->user());
        }

        // Mark the email as verified and fire the Verified event
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // After marking the email as verified, redirect based on the user's role
        return $this->redirectBasedOnRole($request->user());
    }

    /**
     * Redirect the user based on their role_id.
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        // Redirect based on role_id
        if ($user->role_id == 1) {
            return redirect()->route('super_admin.dashboard')->with('verified', 1);
        } elseif ($user->role_id == 2) {
            return redirect()->route('admin.dashboard')->with('verified', 1);
        } elseif ($user->role_id == 3) {
            return redirect()->route('user.dashboard')->with('verified', 1);
        }

        // Default redirect if no role matches
        return redirect('/login');
    }
}
