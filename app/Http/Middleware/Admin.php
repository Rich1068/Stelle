<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->role_id != 2)
        {
            if(Auth::user()->role_id === 1)
            {
            return redirect('super_admin/dashboard');
            }
            if(Auth::user()->role_id === 3)
            {
            return redirect('user/dashboard');
            }
        }
        return $next($request);
    }
}
