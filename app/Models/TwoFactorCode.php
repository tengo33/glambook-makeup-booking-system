<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'code',
        'used',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'used' => false,
    ];

    /**
     * Get the user that owns the two-factor code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the code is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the code is valid (not expired and not used).
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->used;
    }

    /**
     * Mark the code as used.
     *
     * @return bool
     */
    public function markAsUsed(): bool
    {
        return $this->update(['used' => true]);
    }

    /**
     * Scope a query to only include valid codes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('used', false)
                     ->where('expires_at', '>', now());
    }

    /**
     * Scope a query to only include codes for a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get the remaining time in minutes.
     *
     * @return int
     */
    public function getRemainingMinutesAttribute(): int
    {
        return max(0, now()->diffInMinutes($this->expires_at, false));
    }

    /**
     * Get the remaining time in seconds.
     *
     * @return int
     */
    public function getRemainingSecondsAttribute(): int
    {
        return max(0, now()->diffInSeconds($this->expires_at, false));
    }

    /**
     * Generate a new code for a user.
     *
     * @param  int  $userId
     * @param  int  $expiryMinutes
     * @return TwoFactorCode
     */
    public static function generateForUser(int $userId, int $expiryMinutes = 15): TwoFactorCode
    {
        // Delete any existing codes for this user
        self::where('user_id', $userId)->delete();

        return self::create([
            'user_id' => $userId,
            'code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes($expiryMinutes),
            'used' => false,
        ]);
    }

    /**
     * Find a valid code for verification.
     *
     * @param  int  $userId
     * @param  string  $code
     * @return TwoFactorCode|null
     */
    public static function findValidCode(int $userId, string $code): ?TwoFactorCode
    {
        return self::forUser($userId)
            ->where('code', $code)
            ->valid()
            ->first();
    }

    /**
     * Clean up expired and used codes.
     *
     * @return int Number of deleted records
     */
    public static function cleanupExpired(): int
    {
        return self::where('used', true)
            ->orWhere('expires_at', '<=', now())
            ->delete();
    }
}