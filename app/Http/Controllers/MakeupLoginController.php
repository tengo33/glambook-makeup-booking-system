<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MakeupLoginController extends Controller
{
    use AuthenticatesUsers;

    // Default redirect for regular users
    protected $redirectTo = '/dashboard';
    
    // Login throttling settings - ADD THESE PROPERTIES
    protected $maxAttempts = 5;    // Maximum number of attempts
    protected $decayMinutes = 5;   // Lockout time in minutes

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Get failed attempt count for the current IP/email
        $throttleKey = $this->throttleKey(request());
        $attempts = RateLimiter::attempts($throttleKey);
        $remaining = $this->maxAttempts - $attempts; // Use $this->maxAttempts
        
        // Check if user is currently locked out
        $availableIn = RateLimiter::availableIn($throttleKey);
        $isLockedOut = $availableIn > 0 && $attempts >= $this->maxAttempts; // Use $this->maxAttempts
        $lockTimeMinutes = ceil($availableIn / 60); // Convert to minutes
        
        // Store login email if available for auto-fill
        $loginEmail = session('login_email', old('email'));
        
        // Get session data for previous failed attempts
        $sessionAttempts = session('login_attempts', $attempts);
        
return view('auth.login', [
    'attempts' => $attempts,
    'remaining' => $remaining,
    'isLockedOut' => $isLockedOut,
    'lockTime' => $lockTimeMinutes,
    'login_email' => $loginEmail,
    'session_attempts' => $sessionAttempts,
    'maxAttempts' => $this->maxAttempts // Add this line
]);
    }

    /**
     * Handle login - DIRECT LOGIN (no 2FA)
     */
    public function login(Request $request)
    {
        // Check if user is already locked out
        if ($this->hasTooManyLoginAttempts($request)) {
            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );
            $minutes = ceil($seconds / 60);
            
            // Store email for auto-fill after lockout
            $request->session()->put('login_email', $request->email);
            $request->session()->put('login_attempts', $this->maxAttempts); // Use $this->maxAttempts
            
            return back()
                ->withErrors([
                    'email' => [
                        "Too many login attempts. Your account is temporarily locked.",
                        "Please try again in {$minutes} minute(s)."
                    ]
                ])
                ->withInput($request->only('email', 'remember'))
                ->with([
                    'account_locked' => true,
                    'lock_time' => $minutes,
                    'attempts' => $this->maxAttempts // Use $this->maxAttempts
                ]);
        }

        // Validate inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Clear any previous failed attempts for this user
            $this->clearLoginAttempts($request);
            
            // Store successful login timestamp
try {
    if ($user = Auth::user()) {
        $user->last_login_at = now();
        $user->save();
    }
} catch (\Exception $e) {
    // Column doesn't exist, just continue without error
    \Log::warning('last_login_at column not found: ' . $e->getMessage());
}
            
            $request->session()->regenerate();
            
            // Clear the stored login email and attempts
            $request->session()->forget('login_email');
            $request->session()->forget('login_attempts');
            
            // Get the authenticated user
            $user = Auth::user();
            
            // Check user role and redirect accordingly
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // Regular users go to dashboard
            return redirect()->intended($this->redirectTo);
        }

        // If authentication fails - increment attempts
        $this->incrementLoginAttempts($request);
        
        // Calculate remaining attempts
        $attempts = $this->limiter()->attempts($this->throttleKey($request));
        $remaining = $this->maxAttempts - $attempts; // Use $this->maxAttempts
        
        // Store email and attempts for auto-fill
        $request->session()->put('login_email', $request->email);
        $request->session()->put('login_attempts', $attempts);
        
        // Prepare error messages
        $errorMessage = 'The provided credentials do not match our records.';
        
        if ($remaining <= 3 && $remaining > 0) {
            $errorMessage .= " You have {$remaining} attempt(s) remaining.";
        } elseif ($remaining == 0) {
            $errorMessage = "Maximum login attempts reached. Please try again in {$this->decayMinutes} minutes."; // Use $this->decayMinutes
        }

        return back()
            ->withErrors(['email' => $errorMessage])
            ->withInput($request->only('email', 'remember'))
            ->with([
                'attempts_remaining' => $remaining > 0 ? "{$remaining} attempts remaining" : null,
                'login_attempts' => $attempts
            ]);
    }

    /**
     * Override to use email field
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Get the throttle key for the given request.
     * Combines email and IP address for better security
     */
    protected function throttleKey(Request $request)
    {
        return Str::transliterate(Str::lower($request->input($this->username())).'|'.$request->ip());
    }

    /**
     * Override the default method to customize lockout response
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        
        $minutes = ceil($seconds / 60);
        
        throw ValidationException::withMessages([
            $this->username() => [
                "Too many login attempts. Please try again in {$minutes} minute(s)."
            ]
        ]);
    }

    /**
     * Customize the login attempt increment
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request),
            $this->decayMinutes * 60 // Use $this->decayMinutes
        );
    }

    /**
     * Clear login attempts for the given request
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Check if the user has too many login attempts
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts, // Use $this->maxAttempts
            $this->decayMinutes * 60 // Use $this->decayMinutes
        );
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    
    /**
     * Where to redirect after successful login
     * This method overrides the default from AuthenticatesUsers trait
     */
    protected function redirectTo()
    {
        if (auth()->check()) {
            if (auth()->user()->role === 'admin') {
                return route('admin.dashboard');
            }
        }
        
        return $this->redirectTo;
    }
    
}