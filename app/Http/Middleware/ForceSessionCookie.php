<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Cookie;

class ForceSessionCookie
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Always set session cookie
        $sessionId = session()->getId();
        $cookieName = config('session.cookie', 'laravel_session');
        
        Log::info('ForceSessionCookie setting', [
            'cookie_name' => $cookieName,
            'session_id' => $sessionId,
            'already_has_cookie' => $request->hasCookie($cookieName)
        ]);
        
        // Create the cookie
        $cookie = new Cookie(
            $cookieName,
            $sessionId,
            time() + (config('session.lifetime', 120) * 60),
            config('session.path', '/'),
            config('session.domain', 'localhost'),
            config('session.secure', false),
            config('session.http_only', true),
            false,
            config('session.same_site', 'lax')
        );
        
        // Add to response
        $response->headers->setCookie($cookie);
        
        // Also set a non-http-only cookie for debugging
        $debugCookie = new Cookie(
            'debug_' . $cookieName,
            $sessionId . '_debug',
            time() + 3600,
            '/',
            'localhost',
            false,
            false  // NOT http-only, so JavaScript can read it
        );
        
        $response->headers->setCookie($debugCookie);
        
        return $response;
    }
}