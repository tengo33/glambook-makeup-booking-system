@extends('admin.layouts.admin')

@section('title', 'All Users')

@section('content')
@php
    // FIXED: Get total counts from database, NOT from paginated results
    $totalUsers = \App\Models\User::count();
    $totalArtists = \App\Models\User::where('role', 'artist')->count();
    $totalAdmins = \App\Models\User::where('role', 'admin')->count();
    $newToday = \App\Models\User::whereDate('created_at', today())->count();
    
    // For percentage calculations
    $artistPercentage = $totalUsers > 0 ? round(($totalArtists / $totalUsers) * 100, 1) : 0;
    $adminPercentage = $totalUsers > 0 ? round(($totalAdmins / $totalUsers) * 100, 1) : 0;
    
    // Get filter values for active state
    $activeRole = request()->get('role');
    $activeNew = request()->get('new');
@endphp

<div class="container-fluid">
    <!-- Page Header -->
    <div class="admin-header mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2" style="color: var(--charcoal);">
                    <i class="fas fa-users me-2" style="color: var(--deep-rose);"></i>
                    User Management
                </h1>
                <p class="text-muted mb-0">
                    Manage artists, administrators, and user accounts
                </p>
            </div>
            <div class="text-end">
                <div class="bg-white rounded-pill px-4 py-2 d-inline-block shadow-sm">
                    <i class="fas fa-users me-2" style="color: var(--primary-rose);"></i>
                    <strong class="text-dark">{{ $totalUsers }} Total</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Users</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                        <small class="text-muted">
                            All registered users
                        </small>
                    </div>
                    <i class="fas fa-users stat-icon text-primary"></i>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Makeup Artists</h6>
                        <h2 class="mb-0">{{ $totalArtists }}</h2>
                        <small class="text-muted">
                            {{ $artistPercentage }}% of total
                        </small>
                    </div>
                    <i class="fas fa-palette stat-icon text-success"></i>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: {{ $artistPercentage }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Administrators</h6>
                        <h2 class="mb-0">{{ $totalAdmins }}</h2>
                        <small class="text-muted">
                            {{ $adminPercentage }}% of total
                        </small>
                    </div>
                    <i class="fas fa-user-shield stat-icon text-danger"></i>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-danger" style="width: {{ $adminPercentage }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">New Today</h6>
                        <h2 class="mb-0">{{ $newToday }}</h2>
                        <small class="text-muted">
                            Registered today
                        </small>
                    </div>
                    <i class="fas fa-user-plus stat-icon text-info"></i>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card admin-table mb-4">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="me-2" style="color: var(--charcoal); font-weight: 500;">
                            <i class="fas fa-filter me-1"></i> Quick Filters:
                        </span>
                        <a href="{{ route('admin.users') }}" 
                           class="btn btn-sm rounded-pill px-3 {{ !$activeRole && !$activeNew ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-list me-1"></i> All Users
                        </a>
                        <a href="{{ route('admin.users', ['role' => 'artist']) }}" 
                           class="btn btn-sm rounded-pill px-3 {{ $activeRole == 'artist' ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="fas fa-palette me-1"></i> Artists Only
                        </a>
                        <a href="{{ route('admin.users', ['role' => 'admin']) }}" 
                           class="btn btn-sm rounded-pill px-3 {{ $activeRole == 'admin' ? 'btn-danger' : 'btn-outline-danger' }}">
                            <i class="fas fa-user-shield me-1"></i> Admins Only
                        </a>
                        <a href="{{ route('admin.users', ['new' => 'today']) }}" 
                           class="btn btn-sm rounded-pill px-3 {{ $activeNew == 'today' ? 'btn-info' : 'btn-outline-info' }}">
                            <i class="fas fa-user-plus me-1"></i> New Today
                        </a>
                        
                        <!-- Reset Filters Button -->
                        @if($activeRole || $activeNew)
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 ms-auto">
                            <i class="fas fa-times me-1"></i> Clear Filters
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card" style="border: none; box-shadow: var(--shadow-medium); border-radius: 15px; overflow: hidden;">
        <div class="card-header border-0 bg-white" style="padding: 25px 30px;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1" style="color: var(--charcoal); font-weight: 600;">
                        <i class="fas fa-list me-2" style="color: var(--deep-rose);"></i>
                        User List
                    </h5>
                    <!-- FIXED: Show proper pagination info -->
                    <small class="text-muted">
                        @if($users->count() > 0)
                            Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }} users
                            @if($activeRole || $activeNew)
                                (filtered results)
                            @endif
                        @else
                            No users found
                        @endif
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" onclick="exportUsers()">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table mb-0" style="border-collapse: separate; border-spacing: 0; min-width: 1200px;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, var(--soft-cream) 0%, var(--crisp-white) 100%); position: sticky; top: 0; z-index: 10;">
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">ID</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">User Details</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Role</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Appointments</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Joined</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr style="border-bottom: 1px solid rgba(232, 180, 184, 0.1); transition: all 0.3s ease; background: var(--crisp-white);">
                            <td style="padding: 25px; vertical-align: middle; font-weight: 600; color: var(--charcoal);">
                                #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td style="padding: 25px; vertical-align: middle;">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3" style="width: 50px; height: 50px; font-size: 1.3rem; background: linear-gradient(135deg, var(--primary-rose), var(--deep-rose));">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-1" style="color: var(--charcoal); font-weight: 600; font-size: 1rem;">
                                            {{ $user->name }}
                                            @if($user->id == auth()->id())
                                            <span class="badge bg-info ms-2" style="font-size: 0.7rem;">You</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted d-flex align-items-center" style="font-size: 0.85rem;">
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ $user->email }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 25px; vertical-align: middle;">
                                <span class="badge py-2 px-3 rounded-pill {{ $user->role == 'admin' ? 'badge-admin' : 'badge-artist' }}" style="font-size: 0.85rem; font-weight: 500;">
                                    <i class="fas {{ $user->role == 'admin' ? 'fa-user-shield' : 'fa-palette' }} me-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td style="padding: 25px; vertical-align: middle;">
                                <div class="fw-bold" style="color: var(--charcoal); font-size: 1.1rem;">
                                    {{ $user->tasks_count ?? 0 }}
                                </div>
                                <small class="text-muted">Appointments</small>
                            </td>
                            <td style="padding: 25px; vertical-align: middle;">
                                <div>
                                    <strong style="color: var(--charcoal); font-size: 1rem; display: block; margin-bottom: 5px;">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </strong>
                                    <div class="text-muted d-flex align-items-center" style="font-size: 0.9rem;">
                                        <i class="fas fa-clock me-2"></i>
                                        {{ $user->created_at->format('h:i A') }}
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 25px; vertical-align: middle; text-align: center;">
                                <div class="d-flex justify-content-center gap-1">
                                    <!-- VIEW Button -->
                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                       class="btn btn-outline-primary btn-sm" 
                                       title="View User Details"
                                       style="border-radius: 8px; padding: 8px 12px;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- PROMOTE/DEMOTE Buttons -->
                                    @if($user->role == 'artist')
                                    <form action="{{ route('admin.users.promote', $user->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Promote {{ $user->name }} to Administrator?')">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-outline-success btn-sm" 
                                                title="Promote to Admin"
                                                style="border-radius: 8px; padding: 8px 12px;">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                    </form>
                                    @elseif($user->role == 'admin' && $user->id != auth()->id())
                                    <form action="{{ route('admin.users.demote', $user->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Demote {{ $user->name }} to Artist?')">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-outline-warning btn-sm" 
                                                title="Demote to Artist"
                                                style="border-radius: 8px; padding: 8px 12px;">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        @if($user->role == 'artist')
                                        Can promote to admin
                                        @elseif($user->id == auth()->id())
                                        <span class="text-info">Current user</span>
                                        @endif
                                    </small>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5" style="padding: 60px 20px;">
                                <div class="py-5">
                                    <i class="fas fa-users-slash fa-4x mb-4" style="color: var(--primary-rose); opacity: 0.5;"></i>
                                    <h4 class="text-muted mb-2">No users found</h4>
                                    <p class="text-muted">
                                        @if($activeRole || $activeNew)
                                            No users match your filter criteria. 
                                            <a href="{{ route('admin.users') }}" class="text-primary">Clear filters</a>
                                        @else
                                            All registered users will appear here
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($users->hasPages())
        <div class="card-footer border-0 bg-white" style="padding: 25px 30px; border-top: 1px solid rgba(232, 180, 184, 0.1);">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                </div>
                <div>
                    {{ $users->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Row hover effect
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(232, 180, 184, 0.05)';
            this.style.boxShadow = '0 5px 15px rgba(232, 180, 184, 0.1)';
            this.style.transform = 'translateY(-2px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'var(--crisp-white)';
            this.style.boxShadow = 'none';
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Role badge hover effects
    const roleBadges = document.querySelectorAll('.badge-admin, .badge-artist');
    roleBadges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Export function
function exportUsers() {
    alert('Export functionality would generate a CSV/Excel file of all users.');
}
</script>
@endsection