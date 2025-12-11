<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Services\TwoFactorService;

class TwoFactorAuthController extends Controller
{
    protected $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        // Only guests can access 2FA (for registration)
        $this->middleware('guest');
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Show the 2FA verification form (for registration)
     */
    public function show2faForm()
    {
        // Check if user is in session (from registration)
        if (!Session::has('2fa_user_id') || !Session::has('2fa_email')) {
            return redirect()->route('register')
                ->with('error', 'Registration session expired or not found. Please register first.');
        }

        $userId = Session::get('2fa_user_id');
        $user = User::find($userId);
        
        if (!$user) {
            Session::forget(['2fa_user_id', '2fa_email']);
            return redirect()->route('register')
                ->with('error', 'User not found. Please register again.');
        }

        // Check if code has expired and resend if needed
        if ($this->isCodeExpired($user)) {
            $this->twoFactorService->generateAndSendCode($user);
            Session::flash('status', 'A new verification code has been sent to your email.');
        }

        return view('auth.2fa-verify', [
            'email' => Session::get('2fa_email'),
            'user' => $user
        ]);
    }

    /**
     * Verify the 2FA code (for registration)
     */
    public function verify2fa(Request $request)
    {
        $request->validate([
            'code' => 'required|string|digits:6|regex:/^[0-9]{6}$/',
        ]);

        // Get user from session (from registration)
        $userId = Session::get('2fa_user_id');
        $email = Session::get('2fa_email');

        if (!$userId || !$email) {
            return redirect()->route('register')
                ->with('error', 'Registration session expired. Please register again.');
        }

        $user = User::find($userId);

        if (!$user || $user->email !== $email) {
            Session::forget(['2fa_user_id', '2fa_email']);
            return redirect()->route('register')
                ->with('error', 'Invalid session. Please register again.');
        }

        // Verify the 2FA code
        if ($this->twoFactorService->verifyCode($user, $request->code)) {
            // Clear 2FA data from user
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->email_verified_at = now();
            $user->save();
            
            // Clear session data
            Session::forget(['2fa_user_id', '2fa_email']);
            
            // Log the user in (auto-login after registration verification)
            Auth::login($user, $remember = true);
            
            return redirect()->intended('/dashboard')
                ->with('success', 'Email verified successfully! Welcome to GlamBook.');
        }

        return back()
            ->withErrors([
                'code' => 'Invalid or expired verification code. Please try again.',
            ])
            ->withInput($request->only('code'))
            ->with('email', $email);
    }

    /**
     * Resend 2FA code (for registration)
     */
    public function resend2fa()
    {
        // Get user from session (from registration)
        $userId = Session::get('2fa_user_id');
        $email = Session::get('2fa_email');

        if (!$userId || !$email) {
            return redirect()->route('register')
                ->with('error', 'Registration session expired. Please register again.');
        }

        $user = User::find($userId);

        if (!$user || $user->email !== $email) {
            Session::forget(['2fa_user_id', '2fa_email']);
            return redirect()->route('register')
                ->with('error', 'Invalid session. Please register again.');
        }

        // Resend the 2FA code
        $this->twoFactorService->generateAndSendCode($user);
        
        return back()
            ->with('success', 'A new verification code has been sent to your email.')
            ->with('email', $email);
    }

    /**
     * Check if verification code has expired
     */
    private function isCodeExpired(User $user): bool
    {
        if (!$user->two_factor_expires_at) {
            return true;
        }
        
        return now()->greaterThan($user->two_factor_expires_at);
    }

    /**
     * Cancel registration and clear session
     */
    public function cancelRegistration()
    {
        $userId = Session::get('2fa_user_id');
        
        if ($userId) {
            // Optional: Delete the user if they haven't verified
            $user = User::find($userId);
            if ($user && !$user->email_verified_at) {
                $user->delete();
            }
        }
        
        // Clear all session data
        Session::forget(['2fa_user_id', '2fa_email']);
        
        return redirect()->route('register')
            ->with('info', 'Registration cancelled. You can register again.');
    }
}