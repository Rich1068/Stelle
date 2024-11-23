<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RestrictRegistrationStep2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if the user has completed step 2 and verified their email
        if ($user && $user->profile_completed && $user->hasVerifiedEmail()) {
            if ($user->role_id == 1) {
                return redirect()->route('super_admin.dashboard');
            } elseif ($user->role_id == 2) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role_id == 3) {
                return redirect()->route('user.dashboard');
            } 
        }

        return $next($request); 
    }
}
