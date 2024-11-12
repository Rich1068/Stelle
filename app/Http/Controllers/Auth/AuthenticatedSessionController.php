<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        Auth::guard('web')->logout();

    // Invalidate and regenerate the session to prevent issues with stale data
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {

            $user = \App\Models\User::withTrashed()->where('email', $request->input('email'))->first();

            if ($user && $user->trashed()) {
                // If the user is soft-deleted, redirect to the account deleted page
                return redirect()->route('account.deleted');
            }
            // Authenticate the user
            $request->authenticate();
            // Get the current password to log out other sessions
            $this->clearOtherSessions($user->id);
            // Regenerate session
            $request->session()->regenerate();

            // Redirect based on roles, or handle unverified email
            if (!$request->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            if ($request->user()->role_id == 1) {
                return redirect()->route('super_admin.dashboard');
            } elseif ($request->user()->role_id == 2) {
                return redirect()->route('admin.dashboard');
            } elseif ($request->user()->role_id == 3) {
                return redirect()->route('user.dashboard');
            }

            return redirect()->intended(route('auth.login'));
        } catch (\Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            return back()->withErrors(['login' => 'Credentials does not match any accounts in our database']);
        }
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


    protected function clearOtherSessions(int $userId): void
    {
        if (config('session.driver') === 'database') {
            DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', session()->getId()) // Keep current session
                ->delete();
        } 
    }
}
