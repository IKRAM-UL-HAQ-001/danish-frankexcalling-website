<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade

use Symfony\Component\HttpFoundation\Response;

class CustomerCareMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('user_role') == 'customercare') {
            return $next($request); // User is an admin, allow request
        }

        // If not an admin, redirect or abort
        return redirect('/')->with('error', 'Access denied: Admins only.');
    }
}
