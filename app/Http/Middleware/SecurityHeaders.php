<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (app()->environment('local')) {
            $csp = implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net http://127.0.0.1:5173 http://localhost:5173 'http://[::1]:5173'",
                "script-src-elem 'self' 'unsafe-inline' https://cdn.jsdelivr.net http://127.0.0.1:5173 http://localhost:5173 'http://[::1]:5173'",
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net http://127.0.0.1:5173 http://localhost:5173 'http://[::1]:5173'",
                "style-src-elem 'self' 'unsafe-inline' https://cdn.jsdelivr.net http://127.0.0.1:5173 http://localhost:5173 'http://[::1]:5173'",
                "img-src 'self' https: data:",
                "font-src 'self' https://cdn.jsdelivr.net",
                "connect-src 'self' http://127.0.0.1:5173 http://localhost:5173 'http://[::1]:5173' ws://127.0.0.1:5173 ws://localhost:5173 'ws://[::1]:5173' https://cdn.jsdelivr.net",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ]);
        } else {
            $csp = implode('; ', [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
                "script-src-elem 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
                "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
                "style-src-elem 'self' 'unsafe-inline' https://cdn.jsdelivr.net",
                "img-src 'self' https: data:",
                "font-src 'self' https://cdn.jsdelivr.net",
                "connect-src 'self' https://cdn.jsdelivr.net",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ]);
        }

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
