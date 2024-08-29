<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\EventParticipant;

class CheckUserJoinedEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user(); // Get the authenticated user
        $eventId = $request->route('id'); // Get the event ID from the route

        // Check if the user has joined the event with status ID 1
        if ($user && EventParticipant::hasJoinedEvent($user->id, $eventId, 1)) {
            return $next($request);
        }

        // Redirect or abort if the user has not joined the event or the status is not 1
        return redirect()->route('/unauthorized')->with('error', 'You have not joined this event.');
    }
}
