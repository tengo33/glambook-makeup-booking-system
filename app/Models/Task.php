<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        // NEW: Add the 4 name fields
        'first_name',
        'middle_name', 
        'last_name',
        'suffix',
        // Keep client_name for backward compatibility
        'client_name',
        'phone',
        'service_details',
        'appointment_date',
        'appointment_time',       
        'appointment_at',
        'additional_notes',
        'package',
        'addons',
        'price',
        'status',
        'is_done',

        // NEW ML fields
        'actual_duration',
        'predicted_duration',
        'predicted_no_show_score',
        'time_buffer',            // in minutes (e.g., 60)
        'appointment_end_time',
        
        // NEW Location fields
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'location_notes',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'appointment_at' => 'datetime',
        'appointment_end_time' => 'datetime', // ADD THIS LINE
        'price' => 'decimal:2',

        // Casting new ML fields
        'actual_duration' => 'integer',
        'predicted_duration' => 'integer',
        'predicted_no_show_score' => 'decimal:2',
        
        // Casting new Location fields
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    // Add these attributes to be appended when model is converted to array/JSON
    protected $appends = [
        'full_address', 
        'google_maps_link', 
        'has_coordinates', 
        'short_location', 
        'coordinates',
        'formatted_address',
        // NEW: Add full_name attribute
        'full_name',
        'formatted_time', // ADD THIS LINE
        'formatted_end_time' // ADD THIS LINE
    ];

    // ============================================
    // BOOT METHOD FOR AUTOMATIC CALCULATIONS
    // ============================================

    protected static function boot()
    {
        parent::boot();

        // Calculate end time automatically when creating/updating
        static::saving(function ($task) {
            // Always calculate appointment_at from date + time
            if ($task->appointment_date && $task->appointment_time) {
                $time = $task->formatTimeToHHMM($task->appointment_time);
                $task->appointment_at = $task->appointment_date . ' ' . $time . ':00';
            }

            // Calculate appointment_end_time if not provided or if relevant fields changed
            if ($task->shouldCalculateEndTime()) {
                $task->calculateAndSetEndTime();
            }

            // Ensure client_name is set from name fields if not provided
            if (empty($task->client_name) && ($task->first_name || $task->last_name)) {
                $task->client_name = $task->full_name;
            }
        });
    }

    /**
     * Determine if end time should be calculated
     */
    private function shouldCalculateEndTime()
    {
        return $this->appointment_date && 
               $this->appointment_time && 
               ($this->predicted_duration || $this->time_buffer || 
                $this->isDirty(['appointment_date', 'appointment_time', 'predicted_duration', 'time_buffer']));
    }

    /**
     * Calculate and set the appointment_end_time
     */
    private function calculateAndSetEndTime()
    {
        try {
            // Get start datetime
            $time = $this->formatTimeToHHMM($this->appointment_time);
            $startDateTime = $this->appointment_date . ' ' . $time . ':00';
            $start = Carbon::parse($startDateTime);
            
            // Get duration and buffer (use defaults if not set)
            $duration = $this->predicted_duration ?? 60; // default 60 minutes
            $buffer = $this->time_buffer ?? 60; // default 60 minutes buffer
            
            // Calculate end time
            $end = $start->copy()->addMinutes($duration + $buffer);
            
            // Set the end time
            $this->appointment_end_time = $end;
            
        } catch (\Exception $e) {
            \Log::error('Error calculating end time for task: ' . $e->getMessage());
            // If calculation fails, set to null or a default
            $this->appointment_end_time = null;
        }
    }

    // ============================================
    // NEW: Mutators and Accessors for Time
    // ============================================

    /**
     * Mutator for appointment_time to ensure consistent format
     */
    public function setAppointmentTimeAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['appointment_time'] = null;
            return;
        }
        
        // Convert to consistent HH:MM format
        $formattedTime = $this->formatTimeToHHMM($value);
        $this->attributes['appointment_time'] = $formattedTime;
    }

    /**
     * Accessor for appointment_time to ensure consistent format
     */
    public function getAppointmentTimeAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // Ensure it's in HH:MM format
        return $this->formatTimeToHHMM($value);
    }

    /**
     * Get formatted end time for display
     */
    public function getFormattedEndTimeAttribute()
    {
        if (!$this->appointment_end_time) {
            return null;
        }
        
        try {
            return Carbon::parse($this->appointment_end_time)->format('g:i A');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Helper method to format time to HH:MM
     */
    private function formatTimeToHHMM($time)
    {
        if (empty($time)) {
            return null;
        }
        
        // If already in HH:MM format, return as-is
        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time;
        }
        
        // Try to parse different formats
        try {
            // Remove any spaces and convert to uppercase
            $time = strtoupper(trim($time));
            
            // If it has AM/PM, parse as 12-hour format
            if (strpos($time, 'AM') !== false || strpos($time, 'PM') !== false) {
                $dateTime = \DateTime::createFromFormat('g:i A', $time);
                if ($dateTime) {
                    return $dateTime->format('H:i');
                }
            }
            
            // If it's like "0600" or "600"
            if (preg_match('/^\d{3,4}$/', $time)) {
                $time = str_pad($time, 4, '0', STR_PAD_LEFT);
                return substr($time, 0, 2) . ':' . substr($time, 2, 2);
            }
            
            // If it's like "6:00" or "6:00:00"
            if (preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $time)) {
                $parts = explode(':', $time);
                $hour = str_pad(intval($parts[0]), 2, '0', STR_PAD_LEFT);
                $minute = str_pad(intval($parts[1]), 2, '0', STR_PAD_LEFT);
                return $hour . ':' . $minute;
            }
            
            // Try to parse with strtotime as last resort
            $timestamp = strtotime($time);
            if ($timestamp !== false) {
                return date('H:i', $timestamp);
            }
        } catch (\Exception $e) {
            // If parsing fails, return the original value
        }
        
        return $time;
    }

    /**
     * Get formatted time for display (e.g., "6:00 AM")
     */
    public function getFormattedTimeAttribute()
    {
        if (empty($this->appointment_time)) {
            return null;
        }
        
        try {
            $time = $this->appointment_time;
            if (preg_match('/^\d{2}:\d{2}$/', $time)) {
                $dateTime = \DateTime::createFromFormat('H:i', $time);
                if ($dateTime) {
                    return $dateTime->format('g:i A');
                }
            }
        } catch (\Exception $e) {
            // Fallback to original time
        }
        
        return $this->appointment_time;
    }

    /**
     * Set appointment_at based on date and time
     */
    public function setAppointmentAtAttribute($value)
    {
        // If manually setting, use the value
        if ($value) {
            $this->attributes['appointment_at'] = $value;
            return;
        }
        
        // Otherwise, create from date and time
        if ($this->appointment_date && $this->appointment_time) {
            $time = $this->formatTimeToHHMM($this->appointment_time);
            $datetime = $this->appointment_date . ' ' . $time . ':00';
            $this->attributes['appointment_at'] = $datetime;
        }
    }

    /**
     * Get total time (duration + buffer) in minutes
     */
    public function getTotalTimeAttribute()
    {
        $duration = $this->predicted_duration ?? 60;
        $buffer = $this->time_buffer ?? 60;
        return $duration + $buffer;
    }

    /**
     * Get total time in human readable format
     */
    public function getTotalTimeDisplayAttribute()
    {
        $total = $this->total_time;
        $hours = floor($total / 60);
        $minutes = $total % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        return $minutes . 'm';
    }

    // ... rest of your existing model methods ...
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', auth()->id());
    }

    /**
     * Get calculated duration if needed.
     */
    public function getDurationInMinutes()
    {
        if ($this->appointment_at && $this->is_done && !$this->actual_duration) {
            return now()->diffInMinutes($this->appointment_at);
        }
        return $this->actual_duration ?? 0;
    }

    /**
     * Determine if appointment was a no-show.
     */
    public function isNoShow()
    {
        return $this->is_done && $this->status === 'cancelled';
    }

    /**
     * NEW: Get full name from the 4 fields
     */
    public function getFullNameAttribute()
    {
        $name = $this->first_name;
        
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        
        $name .= ' ' . $this->last_name;
        
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        
        return trim($name);
    }

    /**
     * ============================================
     * LOCATION-RELATED METHODS
     * ============================================
     */

    /**
     * Get full formatted address (single line)
     */
    public function getFullAddressAttribute()
    {
        $parts = [];
        if ($this->address) $parts[] = $this->address;
        if ($this->city) $parts[] = $this->city;
        if ($this->state) $parts[] = $this->state;
        if ($this->zip_code) $parts[] = $this->zip_code;
        if ($this->country) $parts[] = $this->country;
        
        return !empty($parts) ? implode(', ', $parts) : 'No address provided';
    }

    /**
     * Get formatted address with line breaks (for admin controller)
     */
    public function getFormattedAddressAttribute()
    {
        $parts = [];
        if ($this->address) $parts[] = $this->address;
        
        $cityStateZip = [];
        if ($this->city) $cityStateZip[] = $this->city;
        if ($this->state) $cityStateZip[] = $this->state;
        if ($this->zip_code) $cityStateZip[] = $this->zip_code;
        
        if (!empty($cityStateZip)) {
            $parts[] = implode(', ', $cityStateZip);
        }
        
        if ($this->country) $parts[] = $this->country;
        
        return !empty($parts) ? implode("\n", $parts) : '';
    }

    /**
     * Alias method for getFormattedAddressAttribute()
     * This allows calling $task->getFormattedAddress()
     */
    public function getFormattedAddress()
    {
        return $this->formatted_address;
    }

    /**
     * Check if location has coordinates
     */
    public function getHasCoordinatesAttribute()
    {
        return !is_null($this->latitude) && !is_null($this->longitude);
    }

    /**
     * Get Google Maps link
     */
    public function getGoogleMapsLinkAttribute()
    {
        if ($this->has_coordinates) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        
        // If no coordinates but has address, use address for directions
        $formattedAddress = $this->formatted_address;
        if (!empty($formattedAddress)) {
            $encodedAddress = urlencode(str_replace("\n", ", ", $formattedAddress));
            return "https://www.google.com/maps/search/?api=1&query={$encodedAddress}";
        }
        
        return null;
    }

    /**
     * Get a static map image URL (optional, requires Google Maps API)
     */
    public function getStaticMapUrl($width = 300, $height = 200, $zoom = 14)
    {
        if ($this->has_coordinates) {
            $apiKey = env('GOOGLE_MAPS_API_KEY', '');
            if ($apiKey) {
                $marker = "color:red%7C{$this->latitude},{$this->longitude}";
                return "https://maps.googleapis.com/maps/api/staticmap?center={$this->latitude},{$this->longitude}&zoom={$zoom}&size={$width}x{$height}&markers={$marker}&key={$apiKey}";
            }
        }
        return null;
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinatesAttribute()
    {
        if ($this->has_coordinates) {
            return [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ];
        }
        return null;
    }

    /**
     * Short location display (for cards)
     */
    public function getShortLocationAttribute()
    {
        if ($this->city && $this->state) {
            return "{$this->city}, {$this->state}";
        } elseif ($this->city) {
            return $this->city;
        } elseif ($this->address) {
            // Truncate long addresses
            return strlen($this->address) > 30 
                ? substr($this->address, 0, 30) . '...' 
                : $this->address;
        }
        return 'Location not specified';
    }

    /**
     * Calculate distance from another location (in kilometers)
     */
    public function distanceFrom($lat, $lng)
    {
        if (!$this->has_coordinates) {
            return null;
        }

        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad($this->latitude);
        $lngFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lngTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lngDelta = $lngTo - $lngFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lngDelta / 2), 2)));

        return round($angle * $earthRadius, 2);
    }
}