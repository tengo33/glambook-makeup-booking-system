<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingAvailabilityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes for your application. These routes are automatically
| assigned the "api" middleware group by the RouteServiceProvider.
|
*/

Route::post('/check-booking-availability', [BookingAvailabilityController::class, 'check']);
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tasks/calendar', function (Request $request) {
        $start = $request->query('start');
        $end = $request->query('end');
        
        $tasks = Task::where('user_id', auth()->id())
            ->whereBetween('appointment_at', [$start, $end])
            ->orWhere(function($query) use ($start, $end) {
                $query->whereNull('appointment_at')
                      ->whereBetween('appointment_date', [$start, $end]);
            })
            ->get();
        
        return response()->json($tasks);
    });
});