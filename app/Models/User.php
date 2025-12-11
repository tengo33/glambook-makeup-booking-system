<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Added for API token support

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'suffix',
        'name',
        'email',
        'password',
        'role',
        'two_factor_code',
        'two_factor_expires_at',
        'email_verified_at',
        'is_active',
        // Add any other fields you have in your users table
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code', // Hide 2FA code
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'two_factor_expires_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'is_active' => true,
        'role' => 'artist',
    ];

    /**
     * Relationship with TwoFactorCode
     * Note: User can have multiple TwoFactorCodes (one for each verification attempt)
     */
    public function twoFactorCodes()
    {
        return $this->hasMany(TwoFactorCode::class);
    }

    /**
     * Relationship with Tasks (Appointments)
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the user's full name with all name parts
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->first_name;
        
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        
        $name .= ' ' . $this->last_name;
        
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        
        return $name;
    }

    /**
     * Get the user's formal name (Last Name, First Name)
     */
    public function getFormalNameAttribute(): string
    {
        $name = $this->last_name . ', ' . $this->first_name;
        
        if ($this->middle_name) {
            $name .= ' ' . substr($this->middle_name, 0, 1) . '.';
        }
        
        if ($this->suffix) {
            $name .= ', ' . $this->suffix;
        }
        
        return $name;
    }

    /**
     * Get the user's initials
     */
    public function getInitialsAttribute(): string
    {
        $initials = strtoupper(substr($this->first_name, 0, 1));
        
        if ($this->middle_name) {
            $initials .= strtoupper(substr($this->middle_name, 0, 1));
        }
        
        $initials .= strtoupper(substr($this->last_name, 0, 1));
        
        return $initials;
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an artist
     */
    public function isArtist(): bool
    {
        return $this->role === 'artist';
    }

    /**
     * Check if user has a valid two-factor code
     */
    public function hasValidTwoFactorCode(): bool
    {
        return !empty($this->two_factor_code) && 
               $this->two_factor_expires_at && 
               now()->lt($this->two_factor_expires_at);
    }

    /**
     * Clear two-factor code from user
     */
    public function clearTwoFactorCode(): bool
    {
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        return $this->save();
    }

    /**
     * Check if user's email is verified
     * Override default method to include 2FA verification
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): bool
    {
        $this->email_verified_at = now();
        return $this->save();
    }

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for artist users
     */
    public function scopeArtists($query)
    {
        return $query->where('role', 'artist');
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for users with verified email
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Get user statistics
     */
    public function getStatisticsAttribute(): array
    {
        return [
            'total_appointments' => $this->tasks()->count(),
            'upcoming_appointments' => $this->tasks()
                ->where('is_done', false)
                ->where('appointment_at', '>', now())
                ->count(),
            'completed_appointments' => $this->tasks()
                ->where('is_done', true)
                ->count(),
            'today_appointments' => $this->tasks()
                ->whereDate('appointment_at', today())
                ->count(),
        ];
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate full name before saving if not provided
        static::creating(function ($user) {
            if (empty($user->name) && $user->first_name) {
                $user->name = $user->full_name;
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty(['first_name', 'last_name', 'middle_name', 'suffix'])) {
                $user->name = $user->full_name;
            }
        });
    }
}