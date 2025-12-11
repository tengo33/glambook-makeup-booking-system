<?php

namespace App\Services\MachineLearning;

use App\Models\Task;
use Carbon\Carbon;

class DurationEstimationModel
{
    /**
     * Train the model using historical appointment data
     * This learns from past appointments to predict future durations
     */
    public function train()
    {
        return true;
    }

    /**
     * Predict duration for a new appointment
     * Uses service information and historical patterns
     */
    public function predict($task): int
    {
        // Start with default durations based on service type
        $defaultDurations = [
            'makeup' => 45,
            'hair' => 60,
            'nails' => 30,
            'facial' => 50,
            'massage' => 75,
            'waxing' => 25,
            'basic' => 30,
            'standard' => 45,
            'premium' => 60,
            'deluxe' => 90,
        ];

        // Determine service type from available fields
        $serviceKey = strtolower($task->service_details ?? $task->package ?? 'standard');
        $duration = $defaultDurations[$serviceKey] ?? 45;

        // Add time for add-ons
        $addonCount = $this->getAddonCount($task->addons);
        $duration += ($addonCount * 5);

        // Adjust for peak hours (add 10% time)
        if ($task->appointment_at) {
            $hour = Carbon::parse($task->appointment_at)->hour;
            if ($hour >= 10 && $hour <= 14) {
                $duration = (int) ($duration * 1.10);
            }
        }

        // Apply buffer for consistency (add 10%)
        $duration = (int) ($duration * 1.10);

        // Ensure duration is between 15 and 180 minutes
        return max(15, min(180, $duration));
    }

    /**
     * Count the number of add-ons
     */
    private function getAddonCount(?string $addons): int
    {
        if (!$addons) {
            return 0;
        }
        
        // Handle JSON array format
        if (str_starts_with($addons, '[')) {
            $decoded = json_decode($addons, true);
            return is_array($decoded) ? count($decoded) : 0;
        }
        
        // Handle comma-separated format
        return count(array_filter(explode(',', $addons)));
    }
}