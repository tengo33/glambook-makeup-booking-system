<?php

namespace App\Http\Controllers;

use App\Services\BookingAvailabilityService;
use Illuminate\Http\Request;

class BookingAvailabilityController extends Controller
{
    protected $availabilityService;

    /**
     * Constructor with dependency injection
     */
    public function __construct(BookingAvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Check if a specific time slot is available
     * IMPORTANT: Must match JavaScript expectations!
     */
    public function checkAvailability(Request $request)
    {
        try {
            $request->validate([
                'appointment_date' => 'required|date',
                'appointment_time' => 'required',
                'predicted_duration' => 'required|integer|min:1',
                'task_id' => 'sometimes|exists:tasks,id'
            ]);
            
            $result = $this->availabilityService->isTimeSlotAvailable(
                $request->appointment_date,
                $request->appointment_time,
                $request->predicted_duration,
                $request->input('task_id')
            );
            
            // RETURN FORMAT MUST MATCH JAVASCRIPT EXPECTATIONS!
            return response()->json([
                'available' => $result['available'] ?? false,
                'message' => $result['message'] ?? 'Time slot check completed',
                'next_available' => $result['next_available'] ?? null,
                'end_time' => $result['end_time'] ?? null,
                'total_duration' => $result['total_duration'] ?? 0
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all blocked time slots for a specific date
     * FIXED: Simplified to match service output
     */
    public function getBlockedSlots(Request $request)
    {
        try {
            $request->validate([
                'appointment_date' => 'required|date',
                'predicted_duration' => 'required|integer|min:1'
            ]);
            
            // Service already returns [{time: "10:00", reason: "..."}, ...]
            $blockedSlots = $this->availabilityService->getBlockedTimeSlots(
                $request->appointment_date,
                $request->predicted_duration
            );
            
            // DIRECTLY return what the service gives us
            return response()->json($blockedSlots);
            
        } catch (\Exception $e) {
            return response()->json([
                ['time' => 'error', 'reason' => 'Server error: ' . $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Get available time slots for a specific date
     * (Optional - not used by your current JavaScript)
     */
    public function getAvailableSlots(Request $request)
    {
        try {
            $request->validate([
                'appointment_date' => 'required|date',
                'predicted_duration' => 'required|integer|min:1'
            ]);
            
            $availableSlots = $this->availabilityService->getAvailableTimeSlots(
                $request->appointment_date,
                $request->predicted_duration
            );
            
            return response()->json([
                'success' => true,
                'date' => $request->appointment_date,
                'available_slots' => $availableSlots,
                'total_slots' => count($availableSlots)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get work hours for a specific date
     * (Optional - not used by your current JavaScript)
     */
    public function getWorkHours(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date'
            ]);
            
            $workHours = $this->availabilityService->getWorkHours($request->date);
            
            return response()->json($workHours);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get multiple days availability summary
     * (Optional - not used by your current JavaScript)
     */
    public function getMultiDayAvailability(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'predicted_duration' => 'required|integer|min:1'
            ]);
            
            $availabilitySummary = $this->availabilityService->getMultiDayAvailability(
                $request->start_date,
                $request->end_date,
                $request->predicted_duration
            );
            
            return response()->json([
                'success' => true,
                'availability_summary' => $availabilitySummary
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}