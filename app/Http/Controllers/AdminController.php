<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Admin Dashboard
    public function dashboard()
    {
        // Get admin's first name
        $adminName = auth()->user()->name;
        $firstName = explode(' ', $adminName)[0] ?? $adminName;
        
        // Statistics
        $totalUsers = User::count();
        $totalArtists = User::where('role', 'artist')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalAppointments = Task::count();
        
        // Location statistics for dashboard
        $locationStats = [
            'withLocation' => Task::where(function($query) {
                $query->whereNotNull('address')
                      ->orWhereNotNull('city')
                      ->orWhereNotNull('state');
            })->count(),
            'withCoordinates' => Task::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'topCity' => Task::select('city', DB::raw('COUNT(*) as count'))
                ->whereNotNull('city')
                ->where('city', '!=', '')
                ->groupBy('city')
                ->orderBy('count', 'desc')
                ->first(),
        ];
        
        // Recent data
        $recentUsers = User::latest()->take(5)->get();
        $recentAppointments = Task::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'firstName',
            'totalUsers',
            'totalArtists', 
            'totalAdmins',
            'totalAppointments',
            'locationStats',
            'recentUsers',
            'recentAppointments'
        ));
    }

    // View All Users with filtering AND pagination
    public function users(Request $request)
    {
        $query = User::withCount('tasks')->latest();
        
        // Apply filters if provided
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('new') && $request->new === 'today') {
            $query->whereDate('created_at', today());
        }
        
        // Use pagination instead of get()
        $users = $query->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    // View User Details
    public function showUser(User $user)
    {
        $tasks = $user->tasks()->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'tasks'));
    }

    // View All Appointments  
    public function appointments(Request $request)
    {
        $query = Task::with('user')->latest();
        
        // Apply filters if provided
        if ($request->has('status')) {
            if ($request->status === 'scheduled') {
                $query->where('is_done', false);
            } elseif ($request->status === 'completed') {
                $query->where('is_done', true);
            }
        }
        
        if ($request->has('date')) {
            if ($request->date === 'today') {
                $query->whereDate('appointment_at', today());
            } elseif ($request->date === 'upcoming') {
                $query->where('appointment_at', '>', now())
                      ->where('is_done', false);
            }
        }
        
        $appointments = $query->paginate(20);
        
        // Calculate stats for the view
        $todayCount = Task::whereDate('appointment_at', today())->count();
        
        return view('admin.appointments.index', compact('appointments', 'todayCount'));
    }

    // Promote user to admin
    public function promote(User $user)
    {
        $user->update(['role' => 'admin']);
        return back()->with('success', 'User promoted to admin successfully!');
    }

    // Demote admin to artist
    public function demote(User $user)
    {
        $user->update(['role' => 'artist']);
        return back()->with('success', 'Admin demoted to artist successfully!');
    }

    // ============================================
    // LOCATION MANAGEMENT METHODS (NEW)
    // ============================================

    /**
     * Display location management page
     */
    public function locations()
    {
        // Get appointments with location data
        $appointments = Task::where(function($query) {
            $query->whereNotNull('address')
                  ->orWhereNotNull('city')
                  ->orWhereNotNull('state');
        })
        ->with('user')
        ->orderBy('appointment_at', 'desc')
        ->paginate(20);
        
        // Location statistics
        $stats = [
            'totalWithLocation' => Task::where(function($q) {
                $q->whereNotNull('address')
                  ->orWhereNotNull('city')
                  ->orWhereNotNull('state');
            })->count(),
            'totalWithCoordinates' => Task::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'topCities' => $this->getTopCities(),
            'locationUsageRate' => $this->calculateLocationUsageRate(),
        ];
        
        return view('admin.locations', compact('appointments', 'stats'));
    }

    /**
     * Display location statistics (API endpoint)
     */
    public function locationStats()
    {
        $stats = [
            'totalAppointments' => Task::count(),
            'withLocation' => Task::where(function($query) {
                $query->whereNotNull('address')
                      ->orWhereNotNull('city')
                      ->orWhereNotNull('state');
            })->count(),
            'withFullAddress' => Task::whereNotNull('address')->count(),
            'withCoordinates' => Task::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'locationCompletion' => $this->calculateLocationCompletion(),
            'cities' => $this->getCitiesWithCounts(),
            'states' => $this->getStatesWithCounts(),
            'monthlyTrend' => $this->getMonthlyLocationTrend(),
        ];
        
        return response()->json($stats);
    }

    /**
     * Get location details for a specific appointment (API endpoint)
     */
    public function locationDetails(Task $task)
    {
        return response()->json([
            'address' => $task->address,
            'city' => $task->city,
            'state' => $task->state,
            'zip_code' => $task->zip_code,
            'country' => $task->country,
            'location_notes' => $task->location_notes,
            'latitude' => $task->latitude,
            'longitude' => $task->longitude,
            'google_maps_link' => $task->google_maps_link,
            'has_coordinates' => $task->has_coordinates,
        'formatted_address' => $task->formatted_address, // Use attribute, not method
        ]);
    }

    /**
     * Export locations data to CSV
     */
    public function exportLocations(Request $request)
    {
        $appointments = Task::where(function($query) {
            $query->whereNotNull('address')
                  ->orWhereNotNull('city')
                  ->orWhereNotNull('state');
        })
        ->with('user')
        ->get();
        
        $csvData = [];
        $csvData[] = ['Client Name', 'Email', 'Address', 'City', 'State', 'ZIP', 'Country', 'Latitude', 'Longitude', 'Appointment Date', 'Service'];
        
        foreach ($appointments as $appointment) {
            $csvData[] = [
                $appointment->client_name,
                $appointment->user->email ?? 'N/A',
                $appointment->address ?? '',
                $appointment->city ?? '',
                $appointment->state ?? '',
                $appointment->zip_code ?? '',
                $appointment->country ?? '',
                $appointment->latitude ?? '',
                $appointment->longitude ?? '',
                $appointment->appointment_at->format('Y-m-d H:i:s'),
                $appointment->service_details ?? '',
            ];
        }
        
        $filename = 'locations-export-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display map view of locations
     */
    public function locationsMap()
    {
        $locations = Task::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with('user')
            ->get()
            ->map(function($task) {
                return [
                    'id' => $task->id,
                    'client_name' => $task->client_name,
                    'address' => $task->address,
                    'city' => $task->city,
                    'state' => $task->state,
                    'latitude' => $task->latitude,
                    'longitude' => $task->longitude,
                    'appointment_at' => $task->appointment_at->format('M d, Y h:i A'),
                    'service_details' => $task->service_details,
                    'google_maps_link' => $task->google_maps_link,
                    'color' => $task->is_done ? '#28a745' : '#ffc107',
                ];
            });
        
        return view('admin.locations-map', compact('locations'));
    }

    // ============================================
    // HELPER METHODS FOR LOCATION MANAGEMENT
    // ============================================

    /**
     * Helper method to get top cities
     */
    private function getTopCities($limit = 5)
    {
        return Task::select('city', DB::raw('COUNT(*) as count'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Helper method to calculate location usage rate
     */
    private function calculateLocationUsageRate()
    {
        $totalAppointments = Task::count();
        if ($totalAppointments == 0) return 0;
        
        $withLocation = Task::where(function($query) {
            $query->whereNotNull('address')
                  ->orWhereNotNull('city')
                  ->orWhereNotNull('state');
        })->count();
        
        return round(($withLocation / $totalAppointments) * 100, 2);
    }

    /**
     * Helper method to get cities with appointment counts
     */
    private function getCitiesWithCounts()
    {
        return Task::select('city', DB::raw('COUNT(*) as count'))
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Helper method to get states with appointment counts
     */
    private function getStatesWithCounts()
    {
        return Task::select('state', DB::raw('COUNT(*) as count'))
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->groupBy('state')
            ->orderBy('count', 'desc')
            ->get();
    }

    /**
     * Helper method to calculate location completion percentage
     */
    private function calculateLocationCompletion()
    {
        $totalWithLocation = Task::where(function($query) {
            $query->whereNotNull('address')
                  ->orWhereNotNull('city')
                  ->orWhereNotNull('state');
        })->count();
        
        if ($totalWithLocation == 0) return 0;
        
        $completeAddresses = Task::whereNotNull('address')
            ->whereNotNull('city')
            ->whereNotNull('state')
            ->count();
            
        return round(($completeAddresses / $totalWithLocation) * 100, 2);
    }

    /**
     * Helper method to get monthly location trend
     */
    private function getMonthlyLocationTrend($months = 6)
    {
        $startDate = Carbon::now()->subMonths($months);
        
        return Task::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN address IS NOT NULL OR city IS NOT NULL OR state IS NOT NULL THEN 1 ELSE 0 END) as with_location')
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    }
}