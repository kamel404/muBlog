<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and is an admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }




        // Redirect non-admin users with an error message
        return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
    }
}
