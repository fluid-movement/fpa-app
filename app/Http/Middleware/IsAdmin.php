<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and is an admin
        $user = Auth::user();

        if (! $user || ! $user->isAdmin()) {
            // You can redirect, abort with 403, or whatever you like
            abort(403, 'Unauthorized. You must be an admin.');
        }

        return $next($request);
    }
}
