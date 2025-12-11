<?php

namespace App\Services;

use App\Models\Task;
use Carbon\Carbon;

class BookingAvailabilityService
{
    // Buffer time in minutes (1 hour = 60 minutes)
    private const TIME_BUFFER = 60;
    
    // Minimum hours before appointment for booking
    private const MIN_ADVANCE_HOURS = 2;
    
    // Standard work hours (INCLUDES 12:00)
    private const WORK_HOURS = [
        '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', 
        '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
    ];
    
    /**
     * Calculate appointment end time with buffer
     */
    public function calculateEndTime(string $startDateTime, int $predictedDuration): string
    {
        $start = Carbon::parse($startDateTime);
        $totalMinutes = $predictedDuration + self::TIME_BUFFER;
        
        return $start->addMinutes($totalMinutes)->format('Y-m-d H:i:s');
    }
    
    /**
     * Round up time to the next hour for blocking purposes
     */
    private function roundUpToNextHour(Carbon $time): Carbon
    {
        $rounded = $time->copy();
        
        // If it's not exactly on the hour, round up to next hour
        if ($rounded->minute > 0 || $rounded->second > 0) {
            $rounded->addHour()->minute(0)->second(0);
        }
        
        return $rounded;
    }
    
    /**
     * Check if a time slot is available considering buffers
     */
    public function isTimeSlotAvailable(string $date, string $time, int $predictedDuration, ?int $taskId = null): array
    {
        $startDateTime = $date . ' ' . $time . ':00';
        $startCarbon = Carbon::parse($startDateTime);
        
        // Calculate when this appointment would end
        $endDateTime = $this->calculateEndTime($startDateTime, $predictedDuration);
        $endCarbon = Carbon::parse($endDateTime);
        
        // ROUND UP to next hour for blocking
        $blockUntilHour = $this->roundUpToNextHour($endCarbon);
        
        \Log::info('Availability check:', [
            'date' => $date,
            'time' => $time,
            'duration' => $predictedDuration,
            'start' => $startCarbon->format('H:i'),
            'end' => $endCarbon->format('H:i'),
            'rounded_end' => $blockUntilHour->format('H:i'),
            'block_until_hour' => $blockUntilHour->hour
        ]);
        
        // Rule 1: Check same-day past time blocking
        if ($this->isPastTime($date, $time)) {
            return [
                'available' => false,
                'message' => 'This time has already passed. Please select a future time.'
            ];
        }
        
        // Rule 2: Check minimum advance booking
        if ($this->isTooCloseToCurrentTime($startCarbon)) {
            $minTime = Carbon::now()->addHours(self::MIN_ADVANCE_HOURS)->format('H:i');
            return [
                'available' => false,
                'message' => 'Bookings require at least ' . self::MIN_ADVANCE_HOURS . ' hours advance. Earliest available: ' . $minTime,
                'next_available' => $minTime
            ];
        }
        
        // Rule 3: Check overlapping appointments with buffer
        // Get all appointments for the selected date
        $appointments = Task::whereDate('appointment_date', $date)
            ->when($taskId, function ($query, $taskId) {
                return $query->where('id', '!=', $taskId);
            })
            ->get();
        
        foreach ($appointments as $appointment) {
            // Get the actual start datetime by combining date and time
            $existingStart = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
            $existingEnd = Carbon::parse($appointment->appointment_end_time);
            
            // ROUND UP existing appointment end time too
            $existingBlockUntil = $this->roundUpToNextHour($existingEnd);
            
            // Check for ANY overlap using rounded times
            if ($startCarbon < $existingBlockUntil && $blockUntilHour > $existingStart) {
                $conflictTime = $existingStart->format('h:i A');
                $nextAvailable = $this->findNextAvailableTime($date, $time, $predictedDuration);
                
                return [
                    'available' => false,
                    'message' => 'Time slot overlaps with ' . $appointment->client_name . 
                               ' (' . $existingStart->format('g:i A') . ' - ' . $existingEnd->format('g:i A') . ')',
                    'next_available' => $nextAvailable
                ];
            }
        }
        
        return [
            'available' => true,
            'end_time' => $endDateTime,
            'total_duration' => $predictedDuration + self::TIME_BUFFER
        ];
    }
    
    /**
     * Check if selected time is in the past
     */
    private function isPastTime(string $date, string $time): bool
    {
        $selectedDateTime = Carbon::parse($date . ' ' . $time . ':00');
        return $selectedDateTime->isPast();
    }
    
    /**
     * Check if booking is too close to current time
     */
    private function isTooCloseToCurrentTime(Carbon $selectedTime): bool
    {
        $minimumTime = Carbon::now()->addHours(self::MIN_ADVANCE_HOURS);
        return $selectedTime->lt($minimumTime);
    }
    
    /**
     * Find next available time slot
     */
    private function findNextAvailableTime(string $date, string $time, int $predictedDuration): string
    {
        $timeSlots = self::WORK_HOURS;
        
        $currentIndex = array_search($time, $timeSlots);
        if ($currentIndex === false) {
            $currentIndex = 0;
        }
        
        // Check next available slots
        for ($i = $currentIndex + 1; $i < count($timeSlots); $i++) {
            $checkResult = $this->isTimeSlotAvailable($date, $timeSlots[$i], $predictedDuration);
            if ($checkResult['available']) {
                return $timeSlots[$i];
            }
        }
        
        // If no slots today, check next day
        $nextDate = Carbon::parse($date)->addDay()->format('Y-m-d');
        foreach ($timeSlots as $slot) {
            $checkResult = $this->isTimeSlotAvailable($nextDate, $slot, $predictedDuration);
            if ($checkResult['available']) {
                return $nextDate . ' at ' . $slot;
            }
        }
        
        return 'No available slots in the next 2 days';
    }
    
    /**
     * Get blocked time slots for a specific date
     */
    public function getBlockedTimeSlots(string $date, int $predictedDuration): array
    {
        $blockedSlots = [];
        
        // First, get all existing appointments for this date
        $appointments = Task::whereDate('appointment_date', $date)->get();
        
        // For each available time slot
        foreach (self::WORK_HOURS as $slot) {
            $slotTime = $date . ' ' . $slot . ':00';
            $slotStart = Carbon::parse($slotTime);
            
            // Calculate when this new appointment would end (including buffer)
            $newEnd = $slotStart->copy()->addMinutes($predictedDuration + self::TIME_BUFFER);
            $newBlockUntil = $this->roundUpToNextHour($newEnd);
            
            $isBlocked = false;
            $blockReason = '';
            
            foreach ($appointments as $appointment) {
                // Get existing appointment times
                $existingStart = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
                $existingEnd = Carbon::parse($appointment->appointment_end_time);
                $existingBlockUntil = $this->roundUpToNextHour($existingEnd);
                
                // Check for overlap using rounded times
                if ($slotStart < $existingBlockUntil && $newBlockUntil > $existingStart) {
                    $isBlocked = true;
                    $blockReason = "Overlaps with {$appointment->client_name}'s appointment at " . 
                                  $existingStart->format('h:i A') . " - " . $existingEnd->format('h:i A');
                    break;
                }
            }
            
            // Also check past times for today
            if (!$isBlocked && Carbon::parse($date)->isToday()) {
                $now = Carbon::now();
                $currentTimeInMinutes = ($now->hour * 60) + $now->minute;
                $slotTimeInMinutes = (int) explode(':', $slot)[0] * 60;
                
                if ($slotTimeInMinutes < $currentTimeInMinutes + (self::MIN_ADVANCE_HOURS * 60)) {
                    $isBlocked = true;
                    $blockReason = "Requires at least " . self::MIN_ADVANCE_HOURS . 
                                  " hours advance (current time: " . $now->format('h:i A') . ")";
                }
            }
            
            if ($isBlocked) {
                $blockedSlots[] = [
                    'time' => $slot,
                    'reason' => $blockReason
                ];
            }
        }
        
        return $blockedSlots;
    }
    
    /**
     * Fix end times for all appointments
     */
    public function fixAllAppointmentEndTimes(): array
    {
        $fixed = [];
        $appointments = Task::whereNotNull('appointment_date')
            ->whereNotNull('appointment_time')
            ->get();
        
        foreach ($appointments as $appointment) {
            $startDateTime = $appointment->appointment_date . ' ' . $appointment->appointment_time;
            $start = Carbon::parse($startDateTime);
            
            // Use time_buffer from database, fallback to 60
            $timeBuffer = $appointment->time_buffer ?? 60;
            $predictedDuration = $appointment->predicted_duration ?? 60;
            
            $calculatedEnd = $start->copy()->addMinutes($predictedDuration + $timeBuffer);
            
            // Check if current end time is wrong
            if (!$appointment->appointment_end_time || 
                $appointment->appointment_end_time != $calculatedEnd) {
                
                $oldEnd = $appointment->appointment_end_time;
                $appointment->appointment_end_time = $calculatedEnd;
                $appointment->save();
                
                $fixed[] = [
                    'client' => $appointment->client_name,
                    'date' => $appointment->appointment_date,
                    'time' => $appointment->appointment_time,
                    'old_end' => $oldEnd,
                    'new_end' => $calculatedEnd->format('Y-m-d H:i:s')
                ];
            }
        }
        
        return $fixed;
    }
}