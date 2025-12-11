@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
@php
    // Calculate additional stats
    $completedAppointments = $recentAppointments->where('is_done', true)->count();
    $pendingAppointments = $recentAppointments->where('is_done', false)->count();
    $todayAppointments = $recentAppointments->filter(function($appointment) {
        return \Carbon\Carbon::parse($appointment->appointment_at)->isToday();
    })->count();
    
    // Calculate revenue stats
    $totalRevenue = $recentAppointments->sum('price');
    $averagePrice = $recentAppointments->count() > 0 ? $recentAppointments->avg('price') : 0;
@endphp

<div class="container-fluid">
    <!-- Dashboard Header with Breadcrumb -->
    <div class="admin-header mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2" style="background: transparent; padding: 0;">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/admin') }}" class="text-decoration-none">
                                <i class="fas fa-home me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Overview</li>
                    </ol>
                </nav>
                <h1 class="h2 mb-1" style="color: var(--charcoal); font-weight: 700;">
                    <i class="fas fa-chart-line me-2" style="color: var(--deep-rose);"></i>
                    Dashboard Overview
                </h1>
                <p class="text-muted mb-0">
                    Welcome back, Administrator! Here's what's happening today.
                </p>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2">
                <div class="text-muted bg-white rounded-pill px-3 py-2 shadow-sm">
                    <i class="fas fa-clock me-2" style="color: var(--primary-rose);"></i>
                    {{ now()->format('l, F j, Y • h:i A') }}
                </div>
                <button class="btn btn-outline-secondary btn-sm rounded-pill" onclick="refreshDashboard()">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div class="row g-3 g-md-4 mb-4">
        <!-- Total Users Card -->
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.05) 100%); border-left: 4px solid var(--primary-blue);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                        <small class="text-muted">
                            {{ $totalArtists }} artists
                        </small>
                    </div>
                    <div class="metric-icon" style="background: rgba(13, 110, 253, 0.1); color: var(--primary-blue);">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: 75%; background-color: var(--primary-blue);"></div>
                </div>
            </div>
        </div>
        
        <!-- Total Appointments Card -->
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(25, 135, 84, 0.05) 100%); border-left: 4px solid var(--success-green);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Appointments</h6>
                        <h2 class="mb-0">{{ $totalAppointments }}</h2>
                        <small class="text-muted">
                            {{ $todayAppointments }} today
                        </small>
                    </div>
                    <div class="metric-icon" style="background: rgba(25, 135, 84, 0.1); color: var(--success-green);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: 85%; background-color: var(--success-green);"></div>
                </div>
            </div>
        </div>
        
        <!-- Pending Appointments Card -->
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%); border-left: 4px solid var(--warning-yellow);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pending Appointments</h6>
                        <h2 class="mb-0">{{ $pendingAppointments }}</h2>
                        <small class="text-muted">
                            Need attention
                        </small>
                    </div>
                    <div class="metric-icon" style="background: rgba(255, 193, 7, 0.1); color: var(--warning-yellow);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: {{ $totalAppointments > 0 ? ($pendingAppointments / $totalAppointments) * 100 : 0 }}%; background-color: var(--warning-yellow);"></div>
                </div>
            </div>
        </div>
        
        <!-- Revenue Card -->
        <div class="col-md-3 col-sm-6">
            <div class="stat-card" style="background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%); border-left: 4px solid var(--danger-red);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Revenue</h6>
                        <h2 class="mb-0">₱{{ number_format($totalRevenue, 0) }}</h2>
                        <small class="text-muted">
                            Average: ₱{{ number_format($averagePrice, 0) }}
                        </small>
                    </div>
                    <div class="metric-icon" style="background: rgba(220, 53, 69, 0.1); color: var(--danger-red);">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: 100%; background-color: var(--danger-red);"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0; padding: 25px 30px;">
                    <h5 class="mb-0" style="color: var(--charcoal); font-weight: 600;">
                        <i class="fas fa-chart-bar me-2" style="color: var(--deep-rose);"></i>
                        Appointments Analytics
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="analyticsPeriod" data-bs-toggle="dropdown" aria-expanded="false">
                            Last 7 Days
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="analyticsPeriod">
                            <li><a class="dropdown-item" href="#" onclick="changeAnalyticsPeriod('week')">Last 7 Days</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeAnalyticsPeriod('month')">Last 30 Days</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeAnalyticsPeriod('quarter')">Last 90 Days</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changeAnalyticsPeriod('year')">Last Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Appointment Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="mini-stat-card text-center p-3" style="background: linear-gradient(135deg, var(--soft-cream) 0%, var(--crisp-white) 100%); border-radius: 10px;">
                                <div class="mb-2">
                                    <i class="fas fa-check-circle fa-2x" style="color: var(--success-green);"></i>
                                </div>
                                <h3 class="mb-1" style="color: var(--charcoal);">{{ $completedAppointments }}</h3>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="mini-stat-card text-center p-3" style="background: linear-gradient(135deg, var(--soft-cream) 0%, var(--crisp-white) 100%); border-radius: 10px;">
                                <div class="mb-2">
                                    <i class="fas fa-clock fa-2x" style="color: var(--warning-yellow);"></i>
                                </div>
                                <h3 class="mb-1" style="color: var(--charcoal);">{{ $pendingAppointments }}</h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="mini-stat-card text-center p-3" style="background: linear-gradient(135deg, var(--soft-cream) 0%, var(--crisp-white) 100%); border-radius: 10px;">
                                <div class="mb-2">
                                    <i class="fas fa-calendar-day fa-2x" style="color: var(--primary-blue);"></i>
                                </div>
                                <h3 class="mb-1" style="color: var(--charcoal);">{{ $todayAppointments }}</h3>
                                <small class="text-muted">Today</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="mini-stat-card text-center p-3" style="background: linear-gradient(135deg, var(--soft-cream) 0%, var(--crisp-white) 100%); border-radius: 10px;">
                                <div class="mb-2">
                                    <i class="fas fa-percentage fa-2x" style="color: var(--deep-rose);"></i>
                                </div>
                                <h3 class="mb-1" style="color: var(--charcoal);">
                                    {{ $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100) : 0 }}%
                                </h3>
                                <small class="text-muted">Completion Rate</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chart Placeholder -->
                    <div class="chart-container" style="height: 250px; background: linear-gradient(135deg, rgba(232, 180, 184, 0.05) 0%, rgba(232, 180, 184, 0.02) 100%); border-radius: 10px; padding: 20px;">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <div class="text-center">
                                <i class="fas fa-chart-line fa-3x mb-3" style="color: var(--primary-rose); opacity: 0.3;"></i>
                                <h6 class="text-muted mb-1">Appointments Trend</h6>
                                <p class="text-muted small mb-0">(Chart would display here)</p>
                                <small class="text-muted">Implement with Chart.js or similar library</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions Panel -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-0" style="border-radius: 15px 15px 0 0; padding: 25px 30px;">
                    <h5 class="mb-0" style="color: var(--charcoal); font-weight: 600;">
                        <i class="fas fa-bolt me-2" style="color: var(--deep-rose);"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <!-- Action Buttons -->
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <a href="{{ route('tasks.create') }}" class="btn btn-admin w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" style="border-radius: 10px;">
                                <i class="fas fa-plus fa-2x mb-2"></i>
                                <span>New Appointment</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" style="border-radius: 10px; border-width: 2px;">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <span>Add User</span>
                            </a>
                        </div>
                        <div class="col-6 mt-2">
                            <a href="{{ route('admin.appointments') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" style="border-radius: 10px; border-width: 2px;">
                                <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                <span>View Calendar</span>
                            </a>
                        </div>
                        <div class="col-6 mt-2">
                            <a href="#" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" style="border-radius: 10px; border-width: 2px;">
                                <i class="fas fa-file-export fa-2x mb-2"></i>
                                <span>Export Data</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- System Status -->
                    <div class="system-status p-3" style="background: rgba(232, 180, 184, 0.05); border-radius: 10px;">
                        <h6 class="mb-3" style="color: var(--charcoal); font-weight: 600;">
                            <i class="fas fa-server me-2"></i>System Status
                        </h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Database</span>
                            <span class="badge bg-success">Healthy</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Storage</span>
                            <span class="badge bg-success">65% used</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Last Backup</span>
                            <span class="badge bg-info">Today, 2:00 AM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <!-- Recent Users -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0; padding: 25px 30px;">
                    <h5 class="mb-0" style="color: var(--charcoal); font-weight: 600;">
                        <i class="fas fa-user-clock me-2" style="color: var(--deep-rose);"></i>
                        Recent Users
                    </h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: rgba(232, 180, 184, 0.05);">
                                    <th style="padding: 15px 20px; border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.1); color: var(--charcoal); font-weight: 600;">User</th>
                                    <th style="padding: 15px 20px; border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.1); color: var(--charcoal); font-weight: 600;" class="d-none d-md-table-cell">Role</th>
                                    <th style="padding: 15px 20px; border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.1); color: var(--charcoal); font-weight: 600;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr style="border-bottom: 1px solid rgba(232, 180, 184, 0.05);">
                                    <td style="padding: 15px 20px;">
                                        <div class="d-flex align-items-center">
<div class="user-avatar me-3 d-flex justify-content-center align-items-center"
     style="
        width: 44px; 
        height: 44px; 
        border-radius: 50%; 
        font-size: 1.1rem; 
        font-weight: 600; 
        color: #fff;
        background: linear-gradient(135deg, #ff6b9d, #d94676);
        box-shadow: 0px 2px 6px rgba(0,0,0,0.15);
     ">
    {{ strtoupper(substr($user->name, 0, 1)) }}
</div>

                                            </div>
                                            <div>
                                                <h6 class="mb-0" style="color: var(--charcoal); font-size: 0.9rem;">
                                                    {{ $user->name }}
                                                </h6>
                                                <small class="text-muted d-block d-md-none" style="font-size: 0.75rem;">
                                                    {{ $user->role == 'admin' ? 'Administrator' : 'Artist' }}
                                                </small>
                                                <small class="text-muted d-none d-md-block" style="font-size: 0.8rem;">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px;" class="d-none d-md-table-cell">
<span class="badge py-1 px-2 rounded-pill"
      style="
          font-size: 0.75rem;
          font-weight: 600;
          color: #fff;
          background: {{ $user->role == 'admin' ? '#d63384' : '#6f42c1' }};
          box-shadow: 0 2px 4px rgba(0,0,0,0.15);
      ">
    {{ ucfirst($user->role) }}
</span>
                                            <i class="fas fa-{{ $user->role == 'admin' ? 'shield-alt' : 'palette' }} me-1"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        <span class="badge bg-success bg-opacity-10 text-success py-1 px-2 rounded-pill" style="font-size: 0.75rem;">
                                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                            Active
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Appointments -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0; padding: 25px 30px;">
                    <h5 class="mb-0" style="color: var(--charcoal); font-weight: 600;">
                        <i class="fas fa-calendar-clock me-2" style="color: var(--deep-rose);"></i>
                        Recent Appointments
                    </h5>
                    <a href="{{ route('admin.appointments') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr style="background: rgba(232, 180, 184, 0.05);">
                                    <th style="padding: 15px 20px; border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.1); color: var(--charcoal); font-weight: 600;">Client</th>
                                    <th style="padding: 15px 20px; border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.1); color: var(--charcoal); font-weight: 600;" class="d-none d-md-table-cell">Time</th>
                                    <th style="padding: 15px 20px; border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.1); color: var(--charcoal); font-weight: 600;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAppointments as $appointment)
                                <tr style="border-bottom: 1px solid rgba(232, 180, 184, 0.05);">
                                    <td style="padding: 15px 20px;">
                                        <div class="d-flex align-items-center">
<div class="client-avatar me-3 d-flex justify-content-center align-items-center"
     style="
         width: 45px;
         height: 45px;
         border-radius: 50%;
         background: linear-gradient(135deg, #ff8fb3, #ff6b9d);
         color: white;
         font-weight: 700;
         font-size: 1rem;
         text-transform: uppercase;
         box-shadow: 0 3px 8px rgba(255, 107, 157, 0.35);
     ">
    {{ strtoupper(substr($appointment->client_name, 0, 1)) }}
</div>

                                            </div>
                                            <div>
                                                <h6 class="mb-0" style="color: var(--charcoal); font-size: 0.9rem;">
                                                    {{ $appointment->client_name }}
                                                </h6>
                                                <small class="text-muted d-block d-md-none" style="font-size: 0.75rem;">
                                                    {{ $appointment->appointment_at->format('h:i A • M d') }}
                                                </small>
                                                <small class="text-muted d-none d-md-block" style="font-size: 0.8rem;">
                                                    <i class="fas fa-palette me-1"></i>
                                                    {{ $appointment->user->name ?? 'Unassigned' }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 15px 20px;" class="d-none d-md-table-cell">
                                        <div style="color: var(--charcoal); font-size: 0.9rem;">
                                            {{ $appointment->appointment_at->format('h:i A') }}
                                        </div>
                                        <small class="text-muted" style="font-size: 0.8rem;">
                                            {{ $appointment->appointment_at->format('M d') }}
                                        </small>
                                    </td>
                                    <td style="padding: 15px 20px;">
                                        @if($appointment->is_done)
                                        <span class="badge bg-success bg-opacity-10 text-success py-1 px-2 rounded-pill" style="font-size: 0.75rem;">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Completed
                                        </span>
                                        @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning py-1 px-2 rounded-pill" style="font-size: 0.75rem;">
                                            <i class="fas fa-clock me-1"></i>
                                            Scheduled
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0; padding: 25px 30px;">
                    <h5 class="mb-0" style="color: var(--charcoal); font-weight: 600;">
                        <i class="fas fa-bell me-2" style="color: var(--deep-rose);"></i>
                        Notifications
                    </h5>
                    <span class="badge bg-primary">0</span>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-bell-slash fa-3x mb-3" style="color: var(--primary-rose); opacity: 0.3;"></i>
                    <h6 class="text-muted mb-1">You have no new notifications</h6>
                    <p class="text-muted mb-0">All caught up! Check back later for updates.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Back to Dashboard Button -->
<div class="floating-nav d-block d-md-none">
    <a href="{{ url('/admin') }}" class="btn btn-primary rounded-pill shadow-lg" style="position: fixed; bottom: 20px; right: 20px; padding: 12px 20px; z-index: 1000;">
        <i class="fas fa-home me-2"></i> Back to Dashboard
    </a>
</div>
@endsection

@section('styles')
<style>
/* Dashboard Header Styling */
.admin-header {
    position: relative;
    z-index: 100;
}

.breadcrumb {
    font-size: 0.85rem;
}

.breadcrumb-item a {
    color: var(--deep-rose);
    text-decoration: none;
    transition: all 0.3s ease;
}

.breadcrumb-item a:hover {
    color: var(--primary-rose);
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: var(--charcoal);
    font-weight: 500;
}

/* Stat Card Styling */
.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: var(--shadow-medium);
    transition: all 0.3s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-large);
}

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

/* Progress Bar Styling */
.progress {
    border-radius: 10px;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.05);
}

.progress-bar {
    border-radius: 10px;
}

/* Mini Stat Cards */
.mini-stat-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(232, 180, 184, 0.1);
}

.mini-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

/* User Avatar */
.user-avatar, .client-avatar {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

/* Badge Styling */
.badge-admin {
    background-color: rgba(220, 53, 69, 0.1);
    color: var(--danger-red);
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.badge-artist {
    background-color: rgba(25, 135, 84, 0.1);
    color: var(--success-green);
    border: 1px solid rgba(25, 135, 84, 0.2);
}

/* Quick Action Buttons */
.btn-admin {
    background: linear-gradient(135deg, var(--primary-rose), var(--deep-rose));
    border: none;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-admin:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(232, 180, 184, 0.4);
    color: white;
}

/* Table Styling */
.table-hover tbody tr {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(232, 180, 184, 0.05) !important;
}

/* Floating Navigation Button */
.floating-nav {
    display: none;
}

@media (max-width: 768px) {
    .floating-nav {
        display: block;
    }
    
    .admin-header h1 {
        font-size: 1.5rem !important;
    }
    
    .stat-card {
        padding: 20px !important;
        margin-bottom: 10px;
    }
    
    .metric-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .card-header {
        padding: 20px !important;
    }
    
    .table th, .table td {
        padding: 12px 15px !important;
    }
    
    .mini-stat-card {
        padding: 15px !important;
    }
    
    .mini-stat-card i {
        font-size: 1.5rem !important;
    }
    
    /* Adjust spacing for mobile */
    .container-fluid {
        padding-bottom: 80px;
    }
}

/* Chart Container */
.chart-container {
    transition: all 0.3s ease;
}

.chart-container:hover {
    box-shadow: 0 5px 15px rgba(232, 180, 184, 0.1);
}

/* System Status */
.system-status {
    transition: all 0.3s ease;
}

.system-status:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
}

/* Animation for Refresh */
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.refresh-spin {
    animation: spin 0.5s linear;
}
</style>
@endsection

@section('scripts')
<script>
// Refresh dashboard function
function refreshDashboard() {
    const refreshBtn = event.currentTarget;
    const icon = refreshBtn.querySelector('i');
    
    // Add spin animation
    icon.classList.add('refresh-spin');
    
    // Simulate refresh delay
    setTimeout(() => {
        // Remove spin animation
        icon.classList.remove('refresh-spin');
        
        // Show success toast
        showToast('Dashboard refreshed successfully!', 'success');
        
        // In a real application, you would reload data here
        // window.location.reload();
    }, 1000);
}

// Change analytics period
function changeAnalyticsPeriod(period) {
    const periodTextMap = {
        'week': 'Last 7 Days',
        'month': 'Last 30 Days',
        'quarter': 'Last 90 Days',
        'year': 'Last Year'
    };
    
    const button = document.getElementById('analyticsPeriod');
    button.textContent = periodTextMap[period];
    
    // Show loading state
    showToast(`Loading ${periodTextMap[period]} data...`, 'info');
    
    // In a real application, you would make an AJAX request here
    // to fetch data for the selected period
}

// Show toast notification
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Add to container
    const container = document.querySelector('.toast-container');
    if (!container) {
        const newContainer = document.createElement('div');
        newContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(newContainer);
        newContainer.appendChild(toast);
    } else {
        container.appendChild(toast);
    }
    
    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', function () {
        toast.remove();
    });
}

// Initialize dashboard on load
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to cards
    const cards = document.querySelectorAll('.stat-card, .mini-stat-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Handle window resize
window.addEventListener('resize', function() {
    // Adjust floating button position on resize
    const floatingBtn = document.querySelector('.floating-nav .btn');
    if (floatingBtn && window.innerWidth >= 768) {
        floatingBtn.style.display = 'none';
    } else if (floatingBtn) {
        floatingBtn.style.display = 'flex';
    }
});

// Initialize on page load
window.dispatchEvent(new Event('resize'));
</script>
@endsection