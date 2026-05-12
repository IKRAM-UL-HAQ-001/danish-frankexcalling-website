<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnyRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a user role session exists
        if (session('user_role')) {
            return $next($request);
        }

        // If not logged in, redirect to login page
        return redirect('/')->with('error', 'Access denied: Please log in first.');
    }
}
