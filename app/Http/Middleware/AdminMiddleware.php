<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check 1: Is user logged in?
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Check 2: Is user an admin?
        $user = Auth::user();
        if ($user->role !== 'admin') {
            // Optional: Log unauthorized access attempt
            \Log::warning('Unauthorized admin access attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'route' => $request->fullUrl()
            ]);
            
            // Redirect regular users to dashboard, show error
            return redirect('/dashboard')->with('error', 'Access denied. Admin only.');
        }

        // If both checks pass, allow access
        return $next($request);
    }
}