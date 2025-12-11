<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Get today's appointments (NO CACHING)
        $todayAppointments = Task::where('user_id', $user->id)
            ->whereDate('appointment_at', $today)
            ->orderBy('appointment_at')
            ->get();
        
        // Calculate stats (NO CACHING)
        $total = Task::where('user_id', $user->id)->count();
        $upcoming = Task::where('user_id', $user->id)
            ->where('is_done', false)
            ->where('appointment_at', '>', now())
            ->count();
        $past = Task::where('user_id', $user->id)
            ->where('is_done', true)
            ->count();
        
        // Monthly stats (NO CACHING)
        $monthlyCompleted = Task::where('user_id', $user->id)
            ->whereMonth('appointment_at', now()->month)
            ->whereYear('appointment_at', now()->year)
            ->where('is_done', true)
            ->count();
            
        $monthlyUpcoming = Task::where('user_id', $user->id)
            ->whereMonth('appointment_at', now()->month)
            ->whereYear('appointment_at', now()->year)
            ->where('is_done', false)
            ->where('appointment_at', '>', now())
            ->count();
            
        $monthlyTotal = Task::where('user_id', $user->id)
            ->whereMonth('appointment_at', now()->month)
            ->whereYear('appointment_at', now()->year)
            ->count();

        return view('dashboard', compact(
            'todayAppointments',
            'total',
            'upcoming',
            'past',
            'monthlyCompleted',
            'monthlyUpcoming',
            'monthlyTotal'
        ));
    }
    
    public function getStats()
    {
        $user = Auth::user();
        
        $stats = Cache::remember('user_stats_' . $user->id, 60, function() use ($user) {
            $total = $user->tasks()->count();
            
            $upcoming = $user->tasks()
                ->where('is_done', false)
                ->where('appointment_at', '>', now())
                ->count();
            
            $today = $user->tasks()
                ->whereDate('appointment_at', today())
                ->count();
            
            $completed = $user->tasks()
                ->where('is_done', true)
                ->count();
            
            // This week's appointments
            $weekStart = now()->startOfWeek();
            $weekEnd = now()->endOfWeek();
            $thisWeek = $user->tasks()
                ->whereBetween('appointment_at', [$weekStart, $weekEnd])
                ->count();
            
            // Revenue calculation (if you have price field)
            $revenue = $user->tasks()
                ->where('is_done', true)
                ->sum('price') ?? 0;
            
            return [
                'total' => $total,
                'upcoming' => $upcoming,
                'today' => $today,
                'completed' => $completed,
                'this_week' => $thisWeek,
                'revenue' => number_format($revenue, 2)
            ];
        });
        
        return response()->json($stats);
    }
    
    /**
     * Clear dashboard cache (useful for testing or after updates)
     */
    public function clearCache()
    {
        $user = Auth::user();
        Cache::forget('dashboard_data_' . $user->id);
        Cache::forget('user_stats_' . $user->id);
        
        return response()->json(['success' => true, 'message' => 'Dashboard cache cleared']);
    }
    
    /**
     * Get appointments for a specific date range (for calendar)
     */
    public function getCalendarEvents(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date'
        ]);
        
        $user = Auth::user();
        
        $events = $user->tasks()
            ->whereBetween('appointment_at', [$request->start, $request->end])
            ->get()
            ->map(function ($task) {
                // Determine color based on status
                if ($task->is_done) {
                    $color = '#28a745'; // Green for completed
                } elseif ($task->appointment_at < now()) {
                    $color = '#6c757d'; // Gray for past but not completed
                } else {
                    $color = '#e8b4b8'; // Rose for upcoming
                }
                
                // Format start and end times
                $start = Carbon::parse($task->appointment_at);
                $end = $start->copy()->addHours(2); // Assuming 2-hour appointments
                
                return [
                    'id' => $task->id,
                    'title' => $task->client_name . ' - ' . $task->service_details,
                    'start' => $start->format('Y-m-d\TH:i:s'),
                    'end' => $end->format('Y-m-d\TH:i:s'),
                    'color' => $color,
                    'textColor' => 'white',
                    'extendedProps' => [
                        'client' => $task->client_name,
                        'service' => $task->service_details,
                        'phone' => $task->phone,
                        'time' => $start->format('g:i A'),
                        'is_done' => $task->is_done,
                        'edit_url' => route('tasks.edit', $task->id)
                    ]
                ];
            });
        
        return response()->json($events);
    }
    
    /**
     * Get upcoming appointments (for sidebar or widget)
     */
    public function getUpcomingAppointments()
    {
        $user = Auth::user();
        
        $upcoming = $user->tasks()
            ->where('is_done', false)
            ->where('appointment_at', '>', now())
            ->orderBy('appointment_at', 'asc')
            ->take(5)
            ->get(['id', 'client_name', 'appointment_at', 'service_details', 'is_done']);
        
        return response()->json($upcoming);
    }
    
    /**
     * Get recent activity
     */
    public function getRecentActivity()
    {
        $user = Auth::user();
        
        $recent = $user->tasks()
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get(['id', 'client_name', 'appointment_at', 'service_details', 'is_done', 'updated_at']);
        
        $activity = $recent->map(function ($task) {
            $action = $task->is_done ? 'completed' : 'updated';
            $time = $task->updated_at->diffForHumans();
            
            return [
                'id' => $task->id,
                'message' => "Appointment with {$task->client_name} was {$action}",
                'time' => $time,
                'client' => $task->client_name,
                'service' => $task->service_details,
                'edit_url' => route('tasks.edit', $task->id)
            ];
        });
        
        return response()->json($activity);
    }
}