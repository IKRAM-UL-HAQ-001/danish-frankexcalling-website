<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent Clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Content Security Policy
        // Note: 'unsafe-inline' and 'unsafe-eval' are included because the app 
        // uses inline scripts/styles and CryptoJS/jQuery in Blade templates.
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com https://cdn.datatables.net https://demos.creative-tim.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.datatables.net https://demos.creative-tim.com; " .
               "img-src 'self' data: https://demos.creative-tim.com; " .
               "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com https://demos.creative-tim.com; " .
               "connect-src 'self';";
               
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
