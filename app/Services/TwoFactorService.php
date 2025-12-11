<?php

namespace App\Services;

use App\Models\User;
use App\Models\TwoFactorCode;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\TwoFactorVerificationMail;

class TwoFactorService
{
    /**
     * Generate and send 2FA code
     */
    public function generateAndSendCode(User $user)
    {
        // Delete any existing unexpired codes for this user first
        TwoFactorCode::where('user_id', $user->id)
            ->where('expires_at', '>', now())
            ->delete();
        
        // Generate 6-digit numeric code
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Set expiry time (15 minutes from now)
        $expiresAt = Carbon::now()->addMinutes(15);
        
        // Create TwoFactorCode record
        $twoFactorCode = TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => $expiresAt,
            'used' => false
        ]);
        
        // Also store on user model for compatibility (optional but helpful)
        $user->two_factor_code = $code;
        $user->two_factor_expires_at = $expiresAt;
        $user->save();
        
        // Send email
        $this->sendVerificationEmail($user, $code);
        
        \Log::info('2FA code generated for user: ' . $user->email, ['code' => $code]);
        
        return $code;
    }

    /**
     * Send verification email
     */
    protected function sendVerificationEmail(User $user, $code)
    {
        try {
            Mail::to($user->email)->send(new TwoFactorVerificationMail($code));
            
            \Log::info('2FA email sent to: ' . $user->email);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send 2FA email: ' . $e->getMessage());
            // Still allow registration - user can request new code
        }
    }

    /**
     * Verify 2FA code
     */
    public function verifyCode(User $user, $code)
    {
        // Clean expired codes first
        $this->cleanExpiredCodes($user);
        
        // Look for the code in TwoFactorCode table
        $twoFactorCode = TwoFactorCode::where('user_id', $user->id)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
        
        if ($twoFactorCode) {
            // Mark as used
            $twoFactorCode->update(['used' => true]);
            
            // Also clear user model fields
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();
            
            \Log::info('2FA code verified for user: ' . $user->email);
            
            return true;
        }
        
        // Also check user model for compatibility
        if ($user->two_factor_code === $code && 
            $user->two_factor_expires_at && 
            Carbon::now()->lt($user->two_factor_expires_at)) {
            
            // Clear user fields
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();
            
            // Also mark any matching TwoFactorCode as used
            TwoFactorCode::where('user_id', $user->id)
                ->where('code', $code)
                ->update(['used' => true]);
            
            return true;
        }
        
        \Log::warning('Invalid 2FA code attempt for user: ' . $user->email, ['code' => $code]);
        
        return false;
    }

    /**
     * Clean expired codes for user
     */
    private function cleanExpiredCodes(User $user)
    {
        // Clean TwoFactorCode table
        TwoFactorCode::where('user_id', $user->id)
            ->where(function($query) {
                $query->where('used', true)
                      ->orWhere('expires_at', '<=', now());
            })
            ->delete();
        
        // Clean user model fields if expired
        if ($user->two_factor_expires_at && 
            Carbon::now()->gte($user->two_factor_expires_at)) {
            
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();
        }
    }

    /**
     * Check if user has a valid pending 2FA code
     */
    public function hasPendingCode(User $user)
    {
        $this->cleanExpiredCodes($user);
        
        // Check TwoFactorCode table
        $hasCode = TwoFactorCode::where('user_id', $user->id)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->exists();
        
        // Also check user model for compatibility
        $hasUserCode = !empty($user->two_factor_code) && 
                      $user->two_factor_expires_at && 
                      Carbon::now()->lt($user->two_factor_expires_at);
        
        return $hasCode || $hasUserCode;
    }

    /**
     * Get remaining time for current code (in seconds)
     */
    public function getRemainingTime(User $user)
    {
        // First check TwoFactorCode table
        $twoFactorCode = TwoFactorCode::where('user_id', $user->id)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
        
        if ($twoFactorCode) {
            $remaining = Carbon::now()->diffInSeconds($twoFactorCode->expires_at, false);
            return max(0, $remaining);
        }
        
        // Fallback to user model
        if ($user->two_factor_expires_at) {
            $remaining = Carbon::now()->diffInSeconds($user->two_factor_expires_at, false);
            return max(0, $remaining);
        }
        
        return 0;
    }

    /**
     * Clear all 2FA codes for user
     */
    public function clearCodes(User $user)
    {
        TwoFactorCode::where('user_id', $user->id)->delete();
        
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();
        
        \Log::info('All 2FA codes cleared for user: ' . $user->email);
    }
}