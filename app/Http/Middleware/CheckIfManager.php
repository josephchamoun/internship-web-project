<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfManager
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
        // Check if the user is authenticated
        if (Auth::check() && Auth::user()->role === 'Manager') {
            return $next($request); // Allow access
        }

        // Redirect or abort if not a manager
        return redirect('/users')->with('error', 'Unauthorized access.'); // Redirect to users with error
        // Or use abort(403); // Uncomment to show a 403 Forbidden page
    }
}
