<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserEvent;

class CheckEventCreator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role_id === 1) {
            return $next($request); // Bypass the check for super admins
        }
        $eventId = $request->route('id');
        $userEvent = UserEvent::where('event_id', $eventId)->first();

        if (!$userEvent || $userEvent->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
