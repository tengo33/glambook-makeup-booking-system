<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter'); // 'upcoming', 'completed', or null

        $query = Task::where('user_id', auth()->id());

        // Apply filter
        if ($filter === 'upcoming') {
            $query->where('is_done', false)->where('appointment_at', '>', now());
        } elseif ($filter === 'completed') {
            $query->where('is_done', true);
        }

        // Sort: upcoming first, then done at the bottom
        $tasks = $query->orderBy('is_done')->orderBy('appointment_at', 'asc')->get();

        // Stats for badges
        $total = Task::where('user_id', auth()->id())->count();
        $upcomingCount = Task::where('user_id', auth()->id())
            ->where('is_done', false)
            ->where('appointment_at', '>', now())
            ->count();
        $completedCount = Task::where('user_id', auth()->id())
            ->where('is_done', true)
            ->count();

        return view('tasks.index', compact('tasks', 'total', 'upcomingCount', 'completedCount', 'filter'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        Log::info('Form data received:', $request->all());

        // Validate form input - UPDATED WITH TIME_BUFFER
        $validated = $request->validate([
            // NEW: Replace 'client_name' with these 4 fields
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:10',
            
            // Phone validation - accept 10 digits only (without +63 prefix)
            'phone' => [
                'required',
                'string',
                'regex:/^9[0-9]{9}$/',  // Starts with 9, then 9 more digits (total 10)
            ],
            
            'service_type' => 'required|string|max:255',
            'selected_package' => 'required|string|max:255',
            'selected_addons' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'appointment_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'appointment_time' => 'required|string', // Changed from date_format:H:i to string
            'additional_notes' => 'nullable|string|max:1000',
            
            // Location fields
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_notes' => 'nullable|string|max:500',
            
            // ML Prediction fields - ADDED TIME_BUFFER
            'predicted_duration' => 'required|integer|min:1',
            'time_buffer' => 'required|integer|min:0', // ADDED THIS LINE
            'predicted_no_show_score' => 'nullable|numeric',
        ]);

        try {
            // Format appointment time to ensure it's in HH:MM format
            $appointmentTime = $this->formatTimeForDatabase($validated['appointment_time']);
            
            // Merge date and time into single datetime field
            $appointmentAt = $validated['appointment_date'] . ' ' . $appointmentTime . ':00';
            $appointmentDateTime = Carbon::parse($appointmentAt);
            
            // Calculate appointment_end_time using duration + buffer
            $predictedDuration = $validated['predicted_duration'] ?? 60;
            $timeBuffer = $validated['time_buffer'] ?? 60;
            $totalMinutes = $predictedDuration + $timeBuffer;
            $appointmentEndTime = $appointmentDateTime->copy()->addMinutes($totalMinutes);
            
            // Create full name for display and backward compatibility
            $fullName = trim($validated['first_name'] . ' ' . 
                            ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') . 
                            $validated['last_name'] . 
                            ($validated['suffix'] ? ' ' . $validated['suffix'] : ''));

            // Check for overlapping appointments (not just same time)
            $overlappingAppointment = $this->checkOverlappingAppointment(
                $appointmentDateTime,
                $appointmentEndTime,
                null // No task_id for new appointments
            );

            if ($overlappingAppointment) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This time slot overlaps with ' . $overlappingAppointment->client_name . '\'s appointment at ' . 
                           $overlappingAppointment->appointment_at->format('g:i A') . '. Please choose another time');
            }

            // Use form predictions
            $predictedNoShow = $request->input('predicted_no_show_score', 0.15);

            // Create task - UPDATED WITH TIME_BUFFER AND APPOINTMENT_END_TIME
            $task = Task::create([
                'user_id' => auth()->id(),
                
                // NEW: Add the 4 name fields
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'],
                
                // Keep client_name for backward compatibility (store full name)
                'client_name' => $fullName,
                
                // All other fields
                'phone' => '+63' . $validated['phone'],  // Add +63 prefix for storage
                'service_details' => $validated['service_type'],
                'package' => $validated['selected_package'],
                'addons' => $validated['selected_addons'] ?? '',
                'price' => $validated['total_price'],
                
                // Appointment times
                'appointment_at' => $appointmentDateTime,
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $appointmentTime, // Use formatted time
                'appointment_end_time' => $appointmentEndTime, // ADDED THIS
                
                'additional_notes' => $validated['additional_notes'] ?? '',
                'is_done' => false,
                'status' => 'confirmed', // Default status
                
                // ML Prediction fields
                'predicted_duration' => $predictedDuration,
                'time_buffer' => $timeBuffer, // ADDED THIS
                'predicted_no_show_score' => $predictedNoShow,
                
                // Location data
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip_code' => $validated['zip_code'] ?? null,
                'country' => $validated['country'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'location_notes' => $validated['location_notes'] ?? null
            ]);

            Log::info('Appointment created successfully:', [
                'task_id' => $task->id,
                'appointment_time' => $appointmentTime,
                'appointment_at' => $appointmentDateTime,
                'appointment_end_time' => $appointmentEndTime,
                'duration' => $predictedDuration,
                'buffer' => $timeBuffer,
                'total_time' => $totalMinutes
            ]);

            $noShowPercentage = round((1 - $predictedNoShow) * 100);

            return redirect()->route('tasks.index')
                ->with('success', "Appointment created for " . $fullName . "! Service: {$predictedDuration} min + {$timeBuffer} min buffer = {$totalMinutes} min total. Show-up likelihood: {$noShowPercentage}%");

        } catch (\Exception $e) {
            Log::error('Error creating appointment:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating appointment: ' . $e->getMessage());
        }
    }

    public function show(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        // Validate form input - UPDATED WITH TIME_BUFFER
        $validated = $request->validate([
            // NEW: Replace 'client_name' with these 4 fields
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:10',
            
            // Phone validation - accept 10 digits only (without +63 prefix)
            'phone' => [
                'required',
                'string',
                'regex:/^9[0-9]{9}$/',  // Starts with 9, then 9 more digits (total 10)
            ],
            
            'service_type' => 'required|string|max:255',
            'selected_package' => 'required|string|max:255',
            'selected_addons' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'appointment_date' => 'required|date_format:Y-m-d',
            'appointment_time' => 'required|string', // Changed from date_format:H:i to string
            'additional_notes' => 'nullable|string|max:1000',
            'status' => 'required|in:confirmed,scheduled,completed,cancelled', // Added 'confirmed'
            'is_done' => 'nullable|boolean',
            
            // Location fields
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'location_notes' => 'nullable|string|max:500',
            
            // ML Prediction fields - ADDED TIME_BUFFER
            'predicted_duration' => 'required|integer|min:1',
            'time_buffer' => 'required|integer|min:0', // ADDED THIS LINE
            'predicted_no_show_score' => 'nullable|numeric',
        ]);

        try {
            // Format appointment time to ensure it's in HH:MM format
            $appointmentTime = $this->formatTimeForDatabase($validated['appointment_time']);
            
            // Merge date and time into single datetime field
            $appointmentAt = $validated['appointment_date'] . ' ' . $appointmentTime . ':00';
            $appointmentDateTime = Carbon::parse($appointmentAt);
            
            // Calculate appointment_end_time using duration + buffer
            $predictedDuration = $validated['predicted_duration'] ?? 60;
            $timeBuffer = $validated['time_buffer'] ?? 60;
            $totalMinutes = $predictedDuration + $timeBuffer;
            $appointmentEndTime = $appointmentDateTime->copy()->addMinutes($totalMinutes);

            Log::info('Updating appointment:', [
                'task_id' => $task->id, 
                'appointment_time_input' => $validated['appointment_time'],
                'appointment_time_formatted' => $appointmentTime,
                'appointment_at' => $appointmentDateTime,
                'appointment_end_time' => $appointmentEndTime,
                'duration' => $predictedDuration,
                'buffer' => $timeBuffer,
                'total_time' => $totalMinutes
            ]);

            // Check for overlapping appointments (excluding current appointment)
            $overlappingAppointment = $this->checkOverlappingAppointment(
                $appointmentDateTime,
                $appointmentEndTime,
                $task->id // Exclude current task
            );

            // Create full name for display and backward compatibility
            $fullName = trim($validated['first_name'] . ' ' . 
                            ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') . 
                            $validated['last_name'] . 
                            ($validated['suffix'] ? ' ' . $validated['suffix'] : ''));

            if ($overlappingAppointment) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This time slot overlaps with ' . $overlappingAppointment->client_name . '\'s appointment at ' . 
                           $overlappingAppointment->appointment_at->format('g:i A') . '. Please choose another time');
            }

            // Use form predictions
            $predictedNoShow = $request->input('predicted_no_show_score', $task->predicted_no_show_score ?? 0.15);

            // Determine if done based on status
            $isDone = $validated['is_done'] ?? ($validated['status'] === 'completed');

            // Update task - UPDATED WITH TIME_BUFFER AND APPOINTMENT_END_TIME
            $task->update([
                // NEW: Update the 4 name fields
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'],
                
                // Keep client_name for backward compatibility (store full name)
                'client_name' => $fullName,
                
                // All other fields
                'phone' => '+63' . $validated['phone'],  // Add +63 prefix for storage
                'service_details' => $validated['service_type'],
                'package' => $validated['selected_package'],
                'addons' => $validated['selected_addons'] ?? '',
                'price' => $validated['total_price'],
                
                // Appointment times
                'appointment_at' => $appointmentDateTime,
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $appointmentTime, // Use formatted time
                'appointment_end_time' => $appointmentEndTime, // ADDED THIS
                
                'additional_notes' => $validated['additional_notes'] ?? '',
                'status' => $validated['status'],
                'is_done' => $isDone,
                
                // ML Prediction fields
                'predicted_duration' => $predictedDuration,
                'time_buffer' => $timeBuffer, // ADDED THIS
                'predicted_no_show_score' => $predictedNoShow,
                
                // Update location data
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'zip_code' => $validated['zip_code'] ?? null,
                'country' => $validated['country'],
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'location_notes' => $validated['location_notes'] ?? null
            ]);

            Log::info('Appointment updated successfully:', [
                'task_id' => $task->id,
                'appointment_time' => $appointmentTime,
                'appointment_at' => $appointmentDateTime,
                'appointment_end_time' => $appointmentEndTime
            ]);

            return redirect()->route('tasks.index')
                ->with('success', 'Appointment for ' . $fullName . ' updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating appointment:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating appointment: ' . $e->getMessage());
        }
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Appointment deleted successfully!');
    }

    /**
     * Mark appointment as done (from dashboard)
     */
    public function markAsDone(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        // Calculate duration from appointment start to now (in minutes)
        $appointmentStart = Carbon::parse($task->appointment_at);
        $now = now();
        
        if ($appointmentStart > $now) {
            $actualDuration = 0; // Appointment hasn't started
        } else {
            $actualDuration = (int) $appointmentStart->diffInMinutes($now);
        }

        $task->update([
            'is_done' => true, 
            'status' => 'completed',
            'actual_duration' => max(5, $actualDuration), // Minimum 5 minutes
            'completed_at' => now()
        ]);

        return redirect()->route('tasks.index')->with('success', 'Appointment marked as done!');
    }

    /**
     * Mark appointment as not done
     */
    public function markAsNotDone(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        $task->update([
            'is_done' => false, 
            'status' => 'scheduled',
            'actual_duration' => null,
            'completed_at' => null
        ]);

        return redirect()->route('tasks.index')->with('success', 'Appointment marked as not done!');
    }

    /**
     * Mark appointment as complete (AJAX for dashboard)
     */
    public function markComplete(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);
            
            if ($task->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Unauthorized access to this appointment.'
                ], 403);
            }

            // Check if already completed
            if ($task->is_done) {
                return response()->json([
                    'success' => false,
                    'message' => 'This appointment is already marked as completed.'
                ], 400);
            }

            // Calculate actual duration
            $appointmentStart = Carbon::parse($task->appointment_at);
            $now = now();
            
            if ($appointmentStart > $now) {
                // Appointment is in the future - set to 0 or use predicted duration
                $actualDuration = $task->predicted_duration ?? 60;
            } else {
                $actualDuration = (int) $appointmentStart->diffInMinutes($now);
                $actualDuration = max(15, $actualDuration);
            }

            // Update the task
            $task->update([
                'is_done' => true, 
                'status' => 'completed',
                'actual_duration' => $actualDuration,
                'completed_at' => now()
            ]);

            // CLEAR ALL CACHE that might be affecting dashboard
            $userId = auth()->id();
            
            // Clear Laravel cache if any
            if (function_exists('cache')) {
                cache()->forget('dashboard_stats_' . $userId);
                cache()->forget('today_appointments_' . $userId);
                cache()->forget('user_stats_' . $userId);
            }
            
            // Clear any session cache
            session()->forget('dashboard_stats');
            
            // Also clear browser cache by sending cache control headers
            header_remove('Cache-Control');
            header_remove('Pragma');
            header_remove('Expires');

            return response()->json([
                'success' => true, 
                'message' => 'Appointment marked as completed successfully!',
                'updated_stats' => [
                    'total' => Task::where('user_id', $userId)->count(),
                    'upcoming' => Task::where('user_id', $userId)
                        ->where('is_done', false)
                        ->where('appointment_at', '>', now())
                        ->count(),
                    'past' => Task::where('user_id', $userId)
                        ->where('is_done', true)
                        ->count(),
                    'today' => Task::where('user_id', $userId)
                        ->whereDate('appointment_at', today())
                        ->count(),
                    'today_completed' => Task::where('user_id', $userId)
                        ->whereDate('appointment_at', today())
                        ->where('is_done', true)
                        ->count()
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error marking appointment as complete:', [
                'appointment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while updating the appointment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for overlapping appointments
     */
// In TaskController.php - Update with rounding logic
/**
 * Check for overlapping appointments with rounding
 */
private function checkOverlappingAppointment(Carbon $startTime, Carbon $endTime, $excludeTaskId = null)
{
    // Round up end time to next hour for blocking
    $roundedEndTime = $endTime->copy();
    if ($roundedEndTime->minute > 0 || $roundedEndTime->second > 0) {
        $roundedEndTime->addHour()->minute(0)->second(0);
    }
    
    $query = Task::where('user_id', auth()->id())
        ->where('is_done', false)
        ->where(function ($q) use ($startTime, $roundedEndTime) {
            // Check if new appointment overlaps with existing (using rounded end time)
            $q->where(function ($inner) use ($startTime, $roundedEndTime) {
                // New appointment starts before existing blocked period ends
                // AND new appointment's blocked period ends after existing starts
                $inner->where('appointment_at', '<', $roundedEndTime)
                      ->where('appointment_end_time', '>', $startTime);
            });
        });

    // Exclude current appointment if editing
    if ($excludeTaskId) {
        $query->where('id', '!=', $excludeTaskId);
    }

    return $query->first();
}

    /**
     * Check booking availability (AJAX) - UPDATED TO CHECK OVERLAP NOT JUST EXACT TIME
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
            'predicted_duration' => 'required|integer|min:1',
            'appointment_id' => 'nullable|integer'
        ]);

        // Format the appointment time properly
        $appointmentTime = $this->formatTimeForDatabase($request->appointment_time);
        
        $appointmentAt = Carbon::createFromFormat(
            'Y-m-d H:i', 
            $request->appointment_date . ' ' . $appointmentTime
        );
        
        // Calculate end time using duration + buffer (default buffer is 60)
        $predictedDuration = $request->predicted_duration;
        $timeBuffer = 60; // Default buffer from system
        $totalMinutes = $predictedDuration + $timeBuffer;
        $appointmentEndTime = $appointmentAt->copy()->addMinutes($totalMinutes);

        // Check for overlapping appointments (not just same time)
        $overlappingAppointment = $this->checkOverlappingAppointment(
            $appointmentAt,
            $appointmentEndTime,
            $request->appointment_id
        );

        if ($overlappingAppointment) {
            return response()->json([
                'available' => false,
                'message' => 'This time slot overlaps with ' . $overlappingAppointment->client_name . '\'s appointment at ' . 
                           $overlappingAppointment->appointment_at->format('g:i A') . ' - ' . 
                           $overlappingAppointment->appointment_end_time->format('g:i A') . 
                           '. Please choose another time',
                'existing_client' => $overlappingAppointment->client_name,
                'existing_time' => $overlappingAppointment->appointment_at->format('g:i A') . ' - ' . 
                                 $overlappingAppointment->appointment_end_time->format('g:i A'),
                'existing_location' => $overlappingAppointment->address ?? 'Location not specified'
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Time slot is available'
        ]);
    }

    /**
     * Get appointment details for modal (AJAX) - SIMPLIFIED
     */
    public function getDetails($id)
    {
        $task = Task::findOrFail($id);
        
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Create formatted full name
        $fullName = $task->first_name . ' ' . 
                   ($task->middle_name ? $task->middle_name . ' ' : '') . 
                   $task->last_name . 
                   ($task->suffix ? ' ' . $task->suffix : '');

        $details = [
            'id' => $task->id,
            // Include new name fields
            'first_name' => $task->first_name,
            'middle_name' => $task->middle_name,
            'last_name' => $task->last_name,
            'suffix' => $task->suffix,
            // For backward compatibility
            'client_name' => $task->client_name,
            // Formatted full name
            'formatted_name' => $fullName,
            'phone' => $task->phone,
            'service_details' => $task->service_details,
            'package' => $task->package,
            'price' => number_format($task->price, 2),
            'appointment_at' => $task->appointment_at->format('Y-m-d H:i:s'),
            'appointment_end_time' => $task->appointment_end_time ? $task->appointment_end_time->format('Y-m-d H:i:s') : null,
            'appointment_date' => $task->appointment_date,
            'appointment_time' => $task->appointment_time,
            'formatted_date' => $task->appointment_at->format('l, F j, Y'),
            'formatted_time' => $task->appointment_at->format('g:i A'),
            'formatted_end_time' => $task->appointment_end_time ? $task->appointment_end_time->format('g:i A') : null,
            'additional_notes' => $task->additional_notes,
            'status' => $task->status,
            'is_done' => $task->is_done,
            'predicted_duration' => $task->predicted_duration,
            'time_buffer' => $task->time_buffer, // ADDED THIS
            'total_time' => ($task->predicted_duration ?? 0) + ($task->time_buffer ?? 0), // ADDED THIS
            'show_up_percentage' => $task->predicted_no_show_score ? round((1 - $task->predicted_no_show_score) * 100) : 'N/A',
            'actual_duration' => $task->actual_duration,
            
            // Location data
            'address' => $task->address,
            'city' => $task->city,
            'state' => $task->state,
            'country' => $task->country,
            'location_notes' => $task->location_notes,
            'edit_url' => route('tasks.edit', $task)
        ];

        return response()->json($details);
    }

    /**
     * Get calendar events (AJAX) - SIMPLIFIED
     */
    public function calendar()
{
    return view('tasks.calendar'); // This should use the layout above
}
    public function calendarEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $events = Task::where('user_id', auth()->id())
            ->whereBetween('appointment_at', [$start, $end])
            ->get()
            ->map(function ($task) {
                // Simple color coding
                $color = $task->is_done ? '#28a745' : 
                        ($task->appointment_at < now() ? '#6c757d' : '#e8b4b8');
                
                // Create client name from new fields
                $clientName = $task->first_name . ' ' . $task->last_name;
                
                // Use actual end time if available, otherwise estimate
                $endTime = $task->appointment_end_time ?: $task->appointment_at->copy()->addHours(2);
                
                return [
                    'id' => $task->id,
                    'title' => $clientName . ' - ' . $task->service_details,
                    'start' => $task->appointment_at->format('Y-m-d\TH:i:s'),
                    'end' => $endTime->format('Y-m-d\TH:i:s'),
                    'color' => $color,
                    'textColor' => 'white',
                    'extendedProps' => [
                        'client' => $clientName,
                        'service' => $task->service_details,
                        'time' => $task->appointment_at->format('g:i A'),
                        'end_time' => $endTime->format('g:i A'),
                        'duration' => $task->predicted_duration,
                        'buffer' => $task->time_buffer,
                        'edit_url' => route('tasks.edit', $task)
                    ]
                ];
            });

        return response()->json($events);
    }

    /**
     * Show appointment statistics - SIMPLIFIED
     */
    public function stats()
    {
        $user = auth()->user();
        
        $total = $user->tasks()->count();
        $upcoming = $user->tasks()
            ->where('is_done', false)
            ->where('appointment_at', '>', now())
            ->count();
        $completed = $user->tasks()->where('is_done', true)->count();
        $today = $user->tasks()->whereDate('appointment_at', today())->count();
        
        // Monthly stats
        $monthlyTotal = $user->tasks()
            ->whereMonth('appointment_at', now()->month)
            ->whereYear('appointment_at', now()->year)
            ->count();
            
        $monthlyCompleted = $user->tasks()
            ->whereMonth('appointment_at', now()->month)
            ->whereYear('appointment_at', now()->year)
            ->where('is_done', true)
            ->count();
            
        $monthlyUpcoming = $user->tasks()
            ->whereMonth('appointment_at', now()->month)
            ->whereYear('appointment_at', now()->year)
            ->where('is_done', false)
            ->where('appointment_at', '>', now())
            ->count();

        return response()->json([
            'total' => $total,
            'upcoming' => $upcoming,
            'completed' => $completed,
            'today' => $today,
            'monthly_total' => $monthlyTotal,
            'monthly_completed' => $monthlyCompleted,
            'monthly_upcoming' => $monthlyUpcoming
        ]);
    }

    /**
     * Helper method to format time for database
     */
    private function formatTimeForDatabase($time)
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
            Log::warning('Failed to parse time format:', ['time' => $time, 'error' => $e->getMessage()]);
        }
        
        // Return as-is if we can't parse it
        return $time;
    }
}