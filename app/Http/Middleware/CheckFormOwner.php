<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\EvaluationForm; // or your Form model

class CheckFormOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the form ID from the route
        $formId = $request->route('form');

        // Find the form
        $form = EvaluationForm::with('event.userEvent')->find($formId);

        // Check if the form exists and if the authenticated user is the owner
        if ($form->event->userEvent->user_id == Auth::id()) {
            return $next($request);
        }

        // If not, redirect to a forbidden page or any other route
        return redirect()->route('/unauthorized')->withErrors('You are not authorized to access this page.');
    }
}
