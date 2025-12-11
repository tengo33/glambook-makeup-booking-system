<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MakeupLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingAvailabilityController;
use App\Models\Task;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ============================================
// DEBUG ROUTES (Remove in production)
// ============================================
if (config('app.debug')) {
    Route::get('/check-time', function () {
        echo "Server time: " . now() . "<br>";
        echo "PHP time: " . date('Y-m-d H:i:s') . "<br>";
        echo "Timezone: " . config('app.timezone');
    });

    Route::get('/check-users-table', function() {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
        dd($columns);
    });

    Route::get('/test-middleware', function() {
        $middleware = app('router')->getMiddleware();
        dd($middleware);
    });
}

// ============================================
// PUBLIC ROUTES (No authentication required)
// ============================================

// Redirect home to dashboard
Route::get('/', function () {
    if (auth()->check()) {
        // Role-based redirect on home page access
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    // Login (NO 2FA - direct login)
    Route::get('/login', [MakeupLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [MakeupLoginController::class, 'login'])->name('login.post');
    
    // Registration (WITH 2FA verification)
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
    
    // 2FA Routes (For REGISTRATION verification only)
    Route::get('/2fa-verify', [TwoFactorAuthController::class, 'show2faForm'])->name('2fa.show');
    Route::post('/2fa-verify', [TwoFactorAuthController::class, 'verify2fa'])->name('2fa.verify');
    Route::post('/2fa-resend', [TwoFactorAuthController::class, 'resend2fa'])->name('2fa.resend');
    Route::get('/2fa-cancel', [TwoFactorAuthController::class, 'cancelRegistration'])->name('2fa.cancel');
    
    // Password Reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');
    
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// ============================================
// PROTECTED ROUTES (Require authentication)
// ============================================
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [MakeupLoginController::class, 'logout'])->name('logout');
    
    // ============================================
    // DASHBOARD ROUTES (REGULAR USERS)
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/calendar-events', [DashboardController::class, 'getCalendarEvents'])->name('dashboard.calendar-events');
    Route::get('/dashboard/upcoming', [DashboardController::class, 'getUpcomingAppointments'])->name('dashboard.upcoming');
    Route::get('/dashboard/activity', [DashboardController::class, 'getRecentActivity'])->name('dashboard.activity');
    Route::post('/dashboard/clear-cache', [DashboardController::class, 'clearCache'])->name('dashboard.clear-cache');
    
    // ============================================
    // APPOINTMENT ROUTES
    // ============================================
    
    // Appointment Routes - Using resource controller
    Route::resource('tasks', TaskController::class);
    
    // Task status updates
    Route::patch('/tasks/{task}/mark-done', [TaskController::class, 'markAsDone'])->name('tasks.mark-done');
    Route::post('/tasks/{task}/mark-done', [TaskController::class, 'markAsDone'])->name('tasks.mark-done');
    Route::post('/tasks/{task}/mark-not-done', [TaskController::class, 'markAsNotDone'])->name('tasks.mark-not-done');
    Route::patch('/tasks/{task}/mark-not-done', [TaskController::class, 'markAsNotDone'])->name('tasks.mark-not-done');
    Route::post('/tasks/{task}/complete', [TaskController::class, 'markComplete'])->name('tasks.complete');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    
    // ============================================
    // BOOKING AVAILABILITY ROUTES (IMPORTANT!)
    // ============================================
    
    // Use BookingAvailabilityController for availability checks
    Route::post('/check-booking-availability', [BookingAvailabilityController::class, 'checkAvailability'])
        ->name('booking.check-availability');

    Route::post('/get-blocked-slots', [BookingAvailabilityController::class, 'getBlockedSlots'])
        ->name('booking.get-blocked-slots');

    // Optional routes
    Route::post('/get-available-slots', [BookingAvailabilityController::class, 'getAvailableSlots'])
        ->name('booking.get-available-slots');

    Route::get('/get-work-hours/{date}', [BookingAvailabilityController::class, 'getWorkHours'])
        ->name('booking.get-work-hours');
    
    // ============================================
    // API ROUTES FOR AJAX
    // ============================================

    // Get appointment details for modal
    Route::get('/api/appointments/{task}/details', [TaskController::class, 'getDetails'])->name('tasks.details');
    
    // Calendar events API
    Route::get('/api/calendar/events', [TaskController::class, 'calendarEvents'])->name('calendar.events');
    
    // Appointment statistics
    Route::get('/api/appointments/stats', [TaskController::class, 'stats'])->name('tasks.stats');
});

// ============================================
// ADMIN ROUTES (Require auth + admin role)
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{user}/promote', [AdminController::class, 'promote'])->name('users.promote');
    Route::post('/users/{user}/demote', [AdminController::class, 'demote'])->name('users.demote');
    
    // Appointment Management
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
    
    // Optional: Admin can view any appointment
    Route::get('/appointments/{task}', function(Task $task) {
        return view('admin.appointments.show', compact('task'));
    })->name('appointments.show');
    
    // ============================================
    // LOCATION MANAGEMENT ROUTES (NEW)
    // ============================================
    
    // Main location management page
    Route::get('/locations', [AdminController::class, 'locations'])->name('locations');
    
    // Location statistics API endpoint
    Route::get('/locations/statistics', [AdminController::class, 'locationStats'])->name('locations.stats');
    
    // Get location details for specific appointment
    Route::get('/locations/{task}/details', [AdminController::class, 'locationDetails'])->name('locations.details');
    
    // Export locations to CSV
    Route::get('/locations/export', [AdminController::class, 'exportLocations'])->name('locations.export');
    
    // Map view of locations
    Route::get('/locations/map', [AdminController::class, 'locationsMap'])->name('locations.map');
    
    // Dashboard management (admin can view all stats)
    Route::get('/dashboard/overview', [AdminController::class, 'dashboardOverview'])->name('dashboard.overview');
});

// ============================================
// TEST ROUTE FOR LOGIN REDIRECT (Remove after testing)
// ============================================
Route::get('/test-login-redirect', function() {
    if (auth()->check()) {
        $user = auth()->user();
        return response()->json([
            'logged_in' => true,
            'user' => [
                'email' => $user->email,
                'role' => $user->role,
                'is_admin' => $user->role === 'admin'
            ],
            'should_redirect_to' => $user->role === 'admin' ? '/admin/dashboard' : '/dashboard'
        ]);
    }
    return response()->json(['logged_in' => false, 'message' => 'Please login first']);
})->name('test.login.redirect');

// ============================================
// FALLBACK ROUTE (404)
// ============================================
Route::fallback(function () {
    if (auth()->check()) {
        // Role-based fallback redirect
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Add to web.php
Route::get('/test-specific-availability', function() {
    $date = '2026-01-24';
    $time = '15:00'; // 3:00 PM
    $duration = 60; // Assuming 60 min service
    $buffer = 60; // Assuming 60 min buffer
    
    // Get existing appointments for that date
    $existingAppointments = \App\Models\Task::whereDate('appointment_date', $date)->get();
    
    $newStart = Carbon::parse("{$date} {$time}");
    $newEnd = $newStart->copy()->addMinutes($duration + $buffer);
    
    $results = [];
    
    foreach ($existingAppointments as $appointment) {
        $existingStart = Carbon::parse($appointment->appointment_date);
        $existingEnd = Carbon::parse($appointment->appointment_end_time);
        
        $overlaps = $newStart < $existingEnd && $newEnd > $existingStart;
        
        $results[] = [
            'client' => $appointment->client_name,
            'existing_start' => $existingStart->format('Y-m-d H:i'),
            'existing_end' => $existingEnd->format('Y-m-d H:i'),
            'new_start' => $newStart->format('Y-m-d H:i'),
            'new_end' => $newEnd->format('Y-m-d H:i'),
            'overlaps' => $overlaps ? 'YES - BLOCKED' : 'NO - AVAILABLE',
            'condition_1' => $newStart < $existingEnd ? "{$newStart->format('H:i')} < {$existingEnd->format('H:i')} = TRUE" : "FALSE",
            'condition_2' => $newEnd > $existingStart ? "{$newEnd->format('H:i')} > {$existingStart->format('H:i')} = TRUE" : "FALSE",
        ];
    }
    
    return view('test-availability', compact('results', 'newStart', 'newEnd'));
});
Route::get('/fix-appointment-end-times', function() {
    $service = new \App\Services\BookingAvailabilityService();
    $fixed = $service->fixAllAppointmentEndTimes();
    
    echo "<h1>Fixed Appointment End Times</h1>";
    echo "<p>Fixed " . count($fixed) . " appointments</p>";
    
    echo "<table border='1'>";
    echo "<tr><th>Client</th><th>Date</th><th>Time</th><th>Old End Time</th><th>New End Time</th></tr>";
    
    foreach ($fixed as $fix) {
        echo "<tr>";
        echo "<td>{$fix['client']}</td>";
        echo "<td>{$fix['date']}</td>";
        echo "<td>{$fix['time']}</td>";
        echo "<td>{$fix['old_end']}</td>";
        echo "<td>{$fix['new_end']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
});
Route::get('/debug-rounding-bug', function() {
    echo "<h1>Debug Rounding Bug</h1>";
    
    $service = new \App\Services\BookingAvailabilityService();
    $date = '2025-12-15';
    
    // Get Joe's appointment
    $appointment = \App\Models\Task::whereDate('appointment_date', $date)
        ->where('client_name', 'LIKE', '%Joe%')
        ->first();
    
    if (!$appointment) {
        echo "Joe's appointment not found!";
        return;
    }
    
    $start = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
    $end = \Carbon\Carbon::parse($appointment->appointment_end_time);
    
    echo "<h2>Joe's Appointment:</h2>";
    echo "Start: " . $start->format('h:i A') . "<br>";
    echo "End: " . $end->format('h:i A') . "<br>";
    echo "Duration: " . ($appointment->predicted_duration ?? 'N/A') . "min + " . ($appointment->time_buffer ?? 'N/A') . "min buffer<br>";
    
    // Test rounding manually
    echo "<h2>Manual Rounding Test:</h2>";
    
    $rounded = $end->copy();
    echo "End time: " . $end->format('h:i:s A') . "<br>";
    echo "Minutes: " . $end->minute . "<br>";
    echo "Seconds: " . $end->second . "<br>";
    
    if ($rounded->minute > 0 || $rounded->second > 0) {
        $rounded->addHour()->minute(0)->second(0);
        echo "Rounded UP to: " . $rounded->format('h:i A') . "<br>";
    } else {
        echo "Already on hour: " . $rounded->format('h:i A') . "<br>";
    }
    
    // Test the service's rounding method
    echo "<h2>Service Rounding Method Test:</h2>";
    
    // Use reflection to call private method
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('roundUpToNextHour');
    $method->setAccessible(true);
    
    $serviceRounded = $method->invoke($service, $end);
    echo "Service rounded to: " . $serviceRounded->format('h:i A') . "<br>";
    
    // Check what getBlockedTimeSlots returns
    echo "<h2>Testing getBlockedTimeSlots:</h2>";
    
    $blockedSlots = $service->getBlockedTimeSlots($date, 60);
    
    echo "Blocked slots returned:<br>";
    foreach ($blockedSlots as $slot) {
        echo "{$slot['time']}: {$slot['reason']}<br>";
    }
    
    // Check which slots SHOULD be blocked
    echo "<h2>Which slots SHOULD be blocked:</h2>";
    
    $workHours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00'];
    
    foreach ($workHours as $slot) {
        $slotTime = \Carbon\Carbon::parse("{$date} {$slot}:00");
        $shouldBlock = $slotTime < $serviceRounded;
        
        echo "{$slot}: ";
        if ($shouldBlock) {
            echo "<span style='color: red;'>❌ SHOULD BE BLOCKED</span>";
        } else {
            echo "<span style='color: green;'>✅ SHOULD BE AVAILABLE</span>";
        }
        echo "<br>";
    }
    
    // Test isTimeSlotAvailable for each slot
    echo "<h2>Testing isTimeSlotAvailable:</h2>";
    
    foreach ($workHours as $slot) {
        $result = $service->isTimeSlotAvailable($date, $slot, 60);
        
        echo "{$slot}: ";
        echo $result['available'] ? '✅ AVAILABLE' : '❌ BLOCKED';
        if (!$result['available']) {
            echo " - " . $result['message'];
        }
        echo "<br>";
    }
});

// Add to routes/web.php
Route::get('/debug-appointments/{date}', function($date) {
    $appointments = \App\Models\Task::where('appointment_date', $date)->get();
    
    echo "<h1>Appointments for {$date}</h1>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Client</th><th>Time</th><th>End Time</th><th>Deleted At</th><th>Status</th></tr>";
    
    foreach ($appointments as $app) {
        echo "<tr>";
        echo "<td>{$app->id}</td>";
        echo "<td>{$app->client_name}</td>";
        echo "<td>{$app->appointment_time}</td>";
        echo "<td>{$app->appointment_end_time}</td>";
        echo "<td>" . ($app->deleted_at ?: 'Not deleted') . "</td>";
        echo "<td>" . ($app->deleted_at ? 'SOFT DELETED' : 'ACTIVE') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Count totals
    $total = $appointments->count();
    $deleted = $appointments->whereNotNull('deleted_at')->count();
    $active = $total - $deleted;
    
    echo "<h2>Summary:</h2>";
    echo "Total appointments: {$total}<br>";
    echo "Active appointments: {$active}<br>";
    echo "Soft-deleted appointments: {$deleted}<br>";

    // routes/web.php
Route::get('/tasks/calendar', [TaskController::class, 'calendar'])->name('tasks.calendar');
});

// Add at TOP of routes/web.php
Route::get('/test', function() {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'db_connected' => DB::connection()->getPdo() ? 'yes' : 'no',
        'env' => app()->environment(),
    ]);
});