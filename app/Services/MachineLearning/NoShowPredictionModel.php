<?php

namespace App\Services\MachineLearning;

use App\Models\Task;
use Carbon\Carbon;

class NoShowPredictionModel
{
    /**
     * Train the model - return true for statistical approach
     */
    public function train()
    {
        return true;
    }

    /**
     * Predict no-show probability (0 to 1)
     * 0 = will definitely show up
     * 1 = will definitely not show up
     */
    public function predict($task): float
    {
        $noShowRisk = 0.0;

        if ($task->appointment_at) {
            $leadDays = now()->diffInDays(Carbon::parse($task->appointment_at), false);
            
            if ($leadDays < 0) {
                // Appointment is in the past - no risk
                $noShowRisk = 0.0;
            } elseif ($leadDays == 0) {
                $noShowRisk += 0.20; // Same day = 20% higher risk
            } elseif ($leadDays == 1) {
                $noShowRisk += 0.10; // Next day = 10% higher risk
            } elseif ($leadDays > 30) {
                $noShowRisk += 0.08; // Too far ahead = might forget
            }
        }

        if ($task->appointment_at) {
            $hour = Carbon::parse($task->appointment_at)->hour;
            if ($hour < 9 || $hour > 19) {
                $noShowRisk += 0.05; // Outside business hours
            }
        }

        if ($task->appointment_at) {
            $dayOfWeek = Carbon::parse($task->appointment_at)->dayOfWeek;
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $noShowRisk += 0.05; // Weekend = slightly higher risk
            }
        }

        if (empty($task->phone) || strlen($task->phone) < 10) {
            $noShowRisk += 0.25; // Invalid phone = 25% higher risk
        }

        if (!empty($task->additional_notes) && strlen($task->additional_notes) > 30) {
            $noShowRisk -= 0.08; // Detailed notes = more serious
        }

        if ($task->service_details) {
            $totalBookings = Task::where('service_details', $task->service_details)
                ->where('is_done', true)
                ->orWhere('status', 'completed')
                ->count();
            
            if ($totalBookings > 0) {
                $noShows = Task::where('service_details', $task->service_details)
                    ->where('status', 'cancelled')
                    ->count();
                
                $noShowRate = $totalBookings > 0 ? ($noShows / $totalBookings) : 0;
                $noShowRisk += ($noShowRate * 0.25); // Weight historical rate at 25%
            }
        }

        if ($task->price > 0) {
            if ($task->price < 50) {
                $noShowRisk += 0.10;
            } elseif ($task->price > 150) {
                $noShowRisk -= 0.10; // Higher investment = less likely to no-show
            }
        }

        // Return probability between 0 and 1
        return max(0, min(1, $noShowRisk));
    }
}
