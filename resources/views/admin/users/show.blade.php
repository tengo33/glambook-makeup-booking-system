@extends('admin.layouts.admin')

@section('title', 'User Details: ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Users
        </a>
    </div>

    <!-- Page Header -->
    <div class="admin-header mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2" style="color: var(--charcoal);">
                    <i class="fas fa-user me-2" style="color: var(--deep-rose);"></i>
                    User Details
                </h1>
                <p class="text-muted mb-0">
                    View and manage user account information
                </p>
            </div>
            <div>
                <!-- Status Badge -->
                <span class="badge py-2 px-3 rounded-pill {{ $user->role == 'admin' ? 'badge-admin' : 'badge-artist' }}" style="font-size: 0.9rem; font-weight: 500;">
                    <i class="fas {{ $user->role == 'admin' ? 'fa-user-shield' : 'fa-palette' }} me-1"></i>
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: User Profile -->
        <div class="col-md-4 mb-4">
            <div class="card" style="border: none; box-shadow: var(--shadow-medium); border-radius: 15px;">
                <div class="card-header border-0 bg-white" style="padding: 25px 30px; border-bottom: 1px solid rgba(232, 180, 184, 0.1);">
                    <h5 class="mb-0" style="color: var(--charcoal); font-weight: 600;">
                        <i class="fas fa-user-circle me-2" style="color: var(--deep-rose);"></i>
                        Profile
                    </h5>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <div class="text-center mb-4">
                        <div class="user-avatar mx-auto mb-3" style="width: 120px; height: 120px; font-size: 3rem; background: linear-gradient(135deg, var(--primary-rose), var(--deep-rose));">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h4 style="color: var(--charcoal); font-weight: 600; margin-bottom: 5px;">
                            {{ $user->name }}
                        </h4>
                        <p class="text-muted mb-0">
                            User ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="user-info">
                        <div class="mb-3">
                            <label class="text-muted d-block mb-1" style="font-size: 0.85rem;">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <div class="fw-bold" style="color: var(--charcoal); font-size: 1.1rem;">
                                {{ $user->email }}
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted d-block mb-1" style="font-size: 0.85rem;">
                                <i class="fas fa-user-tag me-2"></i>Account Type
                            </label>
                            <div class="fw-bold" style="color: var(--charcoal); font-size: 1.1rem;">
                                {{ ucfirst($user->role) }} Account
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted d-block mb-1" style="font-size: 0.85rem;">
                                <i class="fas fa-calendar-plus me-2"></i>Joined Date
                            </label>
                            <div class="fw-bold" style="color: var(--charcoal); font-size: 1.1rem;">
                                {{ $user->created_at->format('F d, Y') }}
                                <small class="text-muted d-block">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $user->created_at->format('h:i A') }}
                                </small>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-muted d-block mb-1" style="font-size: 0.85rem;">
                                <i class="fas fa-clock me-2"></i>Last Updated
                            </label>
                            <div class="fw-bold" style="color: var(--charcoal); font-size: 1.1rem;">
                                {{ $user->updated_at->format('F d, Y') }}
                                <small class="text-muted d-block">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $user->updated_at->format('h:i A') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="card-footer border-0 bg-white" style="padding: 25px 30px; border-top: 1px solid rgba(232, 180, 184, 0.1);">
                    <div class="d-flex flex-column gap-2">
                        @if($user->role == 'artist')
                        <form action="{{ route('admin.users.promote', $user->id) }}" method="POST" onsubmit="return confirm('Promote {{ $user->name }} to Administrator?')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2">
                                <i class="fas fa-arrow-up me-2"></i> Promote to Admin
                            </button>
                        </form>
                        @elseif($user->role == 'admin' && $user->id != auth()->id())
                        <form action="{{ route('admin.users.demote', $user->id) }}" method="POST" onsubmit="return confirm('Demote {{ $user->name }} to Artist?')">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 py-2">
                                <i class="fas fa-arrow-down me-2"></i> Demote to Artist
                            </button>
                        </form>
                        @endif
                        
                        @if($user->id != auth()->id())
                        <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 py-2">
                                <i class="fas fa-trash me-2"></i> Delete User
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: User Appointments -->
        <div class="col-md-8">
            <div class="card" style="border: none; box-shadow: var(--shadow-medium); border-radius: 15px; height: 100%;">
                <div class="card-header border-0 bg-white" style="padding: 25px 30px; border-bottom: 1px solid rgba(232, 180, 184, 0.1);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: var(--charcoal); font-weight: 600;">
                            <i class="fas fa-calendar-check me-2" style="color: var(--deep-rose);"></i>
                            Appointments History
                        </h5>
                        <span class="badge bg-primary py-2 px-3">
                            {{ $user->tasks()->count() }} Total
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($tasks->count() > 0)
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table mb-0" style="border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="background: linear-gradient(135deg, var(--soft-cream) 0%, var(--crisp-white) 100%); position: sticky; top: 0; z-index: 10;">
                                    <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">ID</th>
                                    <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Service</th>
                                    <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Date & Time</th>
                                    <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                                    <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 20px 25px; font-weight: 600; color: var(--deep-rose); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                <tr style="border-bottom: 1px solid rgba(232, 180, 184, 0.1);">
                                    <td style="padding: 20px 25px; vertical-align: middle; font-weight: 600; color: var(--charcoal);">
                                        #{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td style="padding: 20px 25px; vertical-align: middle;">
                                        <div class="fw-bold" style="color: var(--charcoal);">
                                            {{ $task->title }}
                                        </div>
                                        <small class="text-muted">{{ $task->description }}</small>
                                    </td>
                                    <td style="padding: 20px 25px; vertical-align: middle;">
                                        <div class="fw-bold" style="color: var(--charcoal);">
                                            {{ $task->appointment_at->format('M d, Y') }}
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $task->appointment_at->format('h:i A') }}
                                        </small>
                                    </td>
                                    <td style="padding: 20px 25px; vertical-align: middle;">
                                        @if($task->is_done)
                                        <span class="badge bg-success py-1 px-3 rounded-pill">
                                            <i class="fas fa-check-circle me-1"></i> Completed
                                        </span>
                                        @else
                                        <span class="badge bg-warning py-1 px-3 rounded-pill">
                                            <i class="fas fa-clock me-1"></i> Scheduled
                                        </span>
                                        @endif
                                    </td>
                                    <td style="padding: 20px 25px; vertical-align: middle; text-align: center;">
                                        <a href="#" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination for Appointments -->
                    @if($tasks->hasPages())
                    <div class="card-footer border-0 bg-white" style="padding: 20px 30px; border-top: 1px solid rgba(232, 180, 184, 0.1);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} appointments
                            </div>
                            <div>
                                {{ $tasks->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-4x mb-4" style="color: var(--primary-rose); opacity: 0.5;"></i>
                        <h4 class="text-muted mb-2">No appointments found</h4>
                        <p class="text-muted">
                            @if($user->role == 'artist')
                                This artist has no scheduled appointments.
                            @else
                                This user has no appointment history.
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.user-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    font-weight: 600;
}

.user-info label {
    letter-spacing: 0.3px;
}

.badge-admin {
    background: linear-gradient(135deg, #dc3545, #bb2d3b);
    color: white;
}

.badge-artist {
    background: linear-gradient(135deg, #198754, #157347);
    color: white;
}
</style>
@endsection