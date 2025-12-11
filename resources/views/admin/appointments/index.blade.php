@extends('admin.layouts.admin')

@section('title', 'All Appointments')

@section('content')
@php
    use Carbon\Carbon;
    
    // Calculate stats from the collection properly
    $scheduledCount = $appointments->where('is_done', false)->count();
    $completedCount = $appointments->where('is_done', true)->count();
    $totalCount = $appointments->total();
    
    // Calculate today's appointments using Carbon
    $todayCount = $appointments->filter(function($appointment) {
        return Carbon::parse($appointment->appointment_at)->isToday();
    })->count();
    
    // Calculate revenues
    $expectedRevenue = $appointments->where('is_done', false)->sum('price');
    $currentRevenue = $appointments->where('is_done', true)->sum('price');
    $totalRevenue = $expectedRevenue + $currentRevenue;
@endphp

<div class="container-fluid">
    <!-- Page Header -->
    <div class="admin-header mb-4 mb-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h1 class="h2 mb-1 mb-md-2" style="color: var(--charcoal);">
                    <i class="fas fa-calendar-check me-2" style="color: var(--deep-rose);"></i>
                    All Appointments
                </h1>
                <p class="text-muted mb-0 d-none d-md-block">
                    Manage and monitor all makeup appointments in the system
                </p>
                <p class="text-muted mb-0 d-block d-md-none">
                    Manage appointments
                </p>
            </div>
            <div class="text-end">
                <div class="bg-white rounded-pill px-3 px-md-4 py-2 d-inline-block shadow-sm">
                    <i class="fas fa-filter me-1 me-md-2" style="color: var(--primary-rose);"></i>
                    <strong class="text-dark">{{ $totalCount }} Total</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards - Responsive -->
    <div class="row g-3 g-md-4 mb-4 mb-md-5">
        <div class="col-6 col-md-3">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 mb-md-2">Scheduled</h6>
                        <h2 class="mb-0 h3 h-md-2">{{ $scheduledCount }}</h2>
                        <small class="text-muted d-none d-md-block">
                            Expected: ₱{{ number_format($expectedRevenue, 2) }}
                        </small>
                        <small class="text-muted d-block d-md-none">
                            ₱{{ number_format($expectedRevenue, 0) }}
                        </small>
                    </div>
                    <i class="fas fa-clock stat-icon text-warning"></i>
                </div>
                <div class="progress mt-2 mt-md-3" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: {{ $totalCount > 0 ? ($scheduledCount / $totalCount) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 mb-md-2">Completed</h6>
                        <h2 class="mb-0 h3 h-md-2">{{ $completedCount }}</h2>
                        <small class="text-muted d-none d-md-block">
                            Revenue: ₱{{ number_format($currentRevenue, 2) }}
                        </small>
                        <small class="text-muted d-block d-md-none">
                            ₱{{ number_format($currentRevenue, 0) }}
                        </small>
                    </div>
                    <i class="fas fa-check-circle stat-icon text-success"></i>
                </div>
                <div class="progress mt-2 mt-md-3" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: {{ $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mt-3 mt-md-0">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 mb-md-2">Total Revenue</h6>
                        <h2 class="mb-0 h3 h-md-2">₱{{ number_format($totalRevenue, 0) }}</h2>
                        <small class="text-muted d-none d-md-block">
                            {{ $totalCount }} appointments
                        </small>
                        <small class="text-muted d-block d-md-none">
                            {{ $totalCount }} apps
                        </small>
                    </div>
                    <i class="fas fa-coins stat-icon text-warning"></i>
                </div>
                <div class="progress mt-2 mt-md-3" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-md-3 mt-3 mt-md-0">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1 mb-md-2">Today</h6>
                        <h2 class="mb-0 h3 h-md-2">{{ $todayCount }}</h2>
                        <small class="text-muted">
                            Today's appointments
                        </small>
                    </div>
                    <i class="fas fa-calendar-day stat-icon text-primary"></i>
                </div>
                <div class="progress mt-2 mt-md-3" style="height: 4px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments Table Card -->
    <div class="card mb-4" style="border: none; box-shadow: var(--shadow-medium); border-radius: 15px; overflow: hidden;">
        <div class="card-header border-0 bg-white" style="padding: 20px 25px;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h5 class="mb-1" style="color: var(--charcoal); font-weight: 600;">
                        <i class="fas fa-list me-2" style="color: var(--deep-rose);"></i>
                        Appointment List
                    </h5>
                    <small class="text-muted d-none d-md-block">
                        Showing {{ $appointments->count() }} of {{ $totalCount }} appointments
                    </small>
                    <small class="text-muted d-block d-md-none">
                        {{ $appointments->count() }} of {{ $totalCount }}
                    </small>
                </div>
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 w-100 w-md-auto">
                    <!-- Search for mobile -->
                    <div class="input-group input-group-sm d-md-none w-100">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Search..." id="tableSearchMobile">
                    </div>
                    
                    <div class="d-flex gap-2 w-100 w-md-auto justify-content-between justify-content-md-end">
                        <!-- Export buttons for mobile -->
                        <div class="dropdown d-md-none">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="exportDropdownMobile" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-download"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="exportDropdownMobile">
                                <li><a class="dropdown-item" href="#" id="exportCsvMobile"><i class="fas fa-file-csv me-2"></i> CSV</a></li>
                                <li><a class="dropdown-item" href="#" id="exportPdfMobile"><i class="fas fa-file-pdf me-2"></i> PDF</a></li>
                            </ul>
                        </div>
                        
                        <!-- Export buttons for desktop -->
                        <div class="d-none d-md-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" id="exportCsvBtn">
                                <i class="fas fa-file-csv me-1"></i> CSV
                            </button>
                            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" id="exportPdfBtn">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </button>
                        </div>
                        
                        <!-- New Appointment button -->
                        <a href="{{ route('tasks.create') }}" class="btn btn-admin btn-sm rounded-pill px-3">
                            <i class="fas fa-plus me-1 d-none d-md-inline"></i>
                            <span class="d-inline d-md-none">+</span>
                            <span class="d-none d-md-inline">New</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Search and filters for desktop -->
            <div class="d-none d-md-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center gap-2">
                    <!-- Date Range Filter -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dateRangeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-calendar-alt me-1"></i> Date Range
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dateRangeDropdown">
                            <li><a class="dropdown-item" href="#" onclick="setDateRange('today')">Today</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setDateRange('week')">This Week</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setDateRange('month')">This Month</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setDateRange('year')">This Year</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#customDateModal">Custom Range</a></li>
                        </ul>
                    </div>
                    
                    <!-- Search -->
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Search appointments..." id="tableSearch">
                    </div>
                </div>
                
                <!-- Status filter for desktop -->
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('admin.appointments') }}" class="btn btn-outline-primary {{ request()->get('status') == null ? 'active' : '' }}">
                        All
                    </a>
                    <a href="{{ route('admin.appointments') }}?status=scheduled" class="btn btn-outline-warning {{ request()->get('status') == 'scheduled' ? 'active' : '' }}">
                        Scheduled
                    </a>
                    <a href="{{ route('admin.appointments') }}?status=completed" class="btn btn-outline-success {{ request()->get('status') == 'completed' ? 'active' : '' }}">
                        Completed
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Mobile filters header -->
        <div class="d-md-none px-3 pt-2">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">Filters:</small>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilters" aria-expanded="false" aria-controls="mobileFilters">
                    <i class="fas fa-sliders-h"></i> More Filters
                </button>
            </div>
            
            <!-- Mobile filter buttons -->
            <div class="d-flex gap-2 mb-3 overflow-auto pb-2" style="scrollbar-width: none;">
                <a href="{{ route('admin.appointments') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 flex-shrink-0 {{ request()->get('status') == null ? 'active' : '' }}">
                    All
                </a>
                <a href="{{ route('admin.appointments') }}?status=scheduled" class="btn btn-outline-warning btn-sm rounded-pill px-3 flex-shrink-0 {{ request()->get('status') == 'scheduled' ? 'active' : '' }}">
                    Scheduled
                </a>
                <a href="{{ route('admin.appointments') }}?status=completed" class="btn btn-outline-success btn-sm rounded-pill px-3 flex-shrink-0 {{ request()->get('status') == 'completed' ? 'active' : '' }}">
                    Completed
                </a>
                <a href="{{ route('admin.appointments') }}?date=today" class="btn btn-outline-info btn-sm rounded-pill px-3 flex-shrink-0">
                    Today
                </a>
            </div>
            
            <!-- Collapsible mobile filters -->
            <div class="collapse" id="mobileFilters">
                <div class="card card-body border-0 p-3 mb-3" style="background: rgba(232, 180, 184, 0.05);">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">Date Range</label>
                            <select class="form-select form-select-sm" id="mobileDateRange">
                                <option value="">All dates</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="year">This Year</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">Sort By</label>
                            <select class="form-select form-select-sm" id="mobileSortBy">
                                <option value="date_desc">Date (Newest)</option>
                                <option value="date_asc">Date (Oldest)</option>
                                <option value="price_desc">Price (High to Low)</option>
                                <option value="price_asc">Price (Low to High)</option>
                                <option value="name_asc">Name (A-Z)</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-admin btn-sm mt-3 w-100" onclick="applyMobileFilters()">
                        <i class="fas fa-check me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Table Container -->
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table mb-0" style="border-collapse: separate; border-spacing: 0; min-width: 800px;" id="appointmentsTable">
                    <thead>
                        <tr style="background: linear-gradient(135deg, var(--soft-cream) 0%, var(--crisp-white) 100%); position: sticky; top: 0; z-index: 10;">
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 15px 20px; font-weight: 600; color: var(--deep-rose); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; width: 70px;">ID</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 15px 20px; font-weight: 600; color: var(--deep-rose); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Client</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 15px 20px; font-weight: 600; color: var(--deep-rose); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;" class="d-none d-md-table-cell">Artist</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 15px 20px; font-weight: 600; color: var(--deep-rose); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;" class="d-none d-lg-table-cell">Date & Time</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 15px 20px; font-weight: 600; color: var(--deep-rose); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;" class="d-none d-xl-table-cell">Service</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 15px 20px; font-weight: 600; color: var(--deep-rose); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Price</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 15px 20px; font-weight: 600; color: var(--deep-rose); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; width: 100px;">Status</th>
                            <th style="border-top: none; border-bottom: 2px solid rgba(232, 180, 184, 0.3); padding: 15px 20px; font-weight: 600; color: var(--deep-rose); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; width: 120px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr style="border-bottom: 1px solid rgba(232, 180, 184, 0.1); transition: all 0.3s ease; background: var(--crisp-white);">
                            <td style="padding: 15px 20px; vertical-align: middle; font-weight: 600; color: var(--charcoal);">
                                #{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td style="padding: 15px 20px; vertical-align: middle;">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2 me-md-3" style="width: 40px; height: 40px; font-size: 1.1rem; background: linear-gradient(135deg, var(--primary-rose), var(--deep-rose));">
                                        {{ strtoupper(substr($appointment->client_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 mb-md-1" style="color: var(--charcoal); font-weight: 600; font-size: 0.9rem;">
                                            {{ $appointment->client_name }}
                                        </h6>
                                        <small class="text-muted d-none d-md-flex align-items-center" style="font-size: 0.8rem;">
                                            <i class="fas fa-phone me-1"></i>
                                            {{ $appointment->phone }}
                                        </small>
                                        <div class="d-block d-md-none">
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                {{ $appointment->appointment_at->format('M d') }} •
                                                ₱{{ number_format($appointment->price, 0) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px 20px; vertical-align: middle;" class="d-none d-md-table-cell">
                                @if($appointment->user)
                                <div>
                                    <span class="badge-artist d-inline-block" style="padding: 5px 10px; font-size: 0.8rem; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $appointment->user->name }}
                                    </span>
                                    <div class="mt-1 d-none d-lg-block">
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $appointment->user->email }}
                                        </small>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted" style="font-size: 0.8rem;">Unassigned</span>
                                @endif
                            </td>
                            <td style="padding: 15px 20px; vertical-align: middle;" class="d-none d-lg-table-cell">
                                <div>
                                    <strong style="color: var(--charcoal); font-size: 0.9rem; display: block; margin-bottom: 3px;">
                                        {{ $appointment->appointment_at->format('M d, Y') }}
                                    </strong>
                                    <div class="text-muted d-flex align-items-center" style="font-size: 0.8rem;">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $appointment->appointment_at->format('h:i A') }}
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 15px 20px; vertical-align: middle;" class="d-none d-xl-table-cell">
                                <div style="max-width: 200px;">
                                    <span class="d-block" style="color: var(--charcoal); font-size: 0.85rem; line-height: 1.4;">
                                        {{ Str::limit($appointment->service_details, 50) }}
                                    </span>
                                </div>
                            </td>
                            <td style="padding: 15px 20px; vertical-align: middle;">
                                <div class="fw-bold d-none d-md-block" style="color: var(--deep-rose); font-size: 1rem;">
                                    ₱{{ number_format($appointment->price, 2) }}
                                </div>
                                <div class="fw-bold d-block d-md-none" style="color: var(--deep-rose); font-size: 0.9rem;">
                                    ₱{{ number_format($appointment->price, 0) }}
                                </div>
                            </td>
                            <td style="padding: 15px 20px; vertical-align: middle;">
                                @if($appointment->is_done)
                                <span class="badge bg-success py-1 px-2 rounded-pill d-inline-flex align-items-center" style="font-size: 0.75rem; font-weight: 500;">
                                    <i class="fas fa-check-circle me-1"></i> 
                                    <span class="d-none d-md-inline">Done</span>
                                    <span class="d-inline d-md-none">✓</span>
                                </span>
                                @else
                                <span class="badge bg-warning py-1 px-2 rounded-pill d-inline-flex align-items-center" style="font-size: 0.75rem; font-weight: 500;">
                                    <i class="fas fa-clock me-1"></i>
                                    <span class="d-none d-md-inline">Scheduled</span>
                                    <span class="d-inline d-md-none">⌚</span>
                                </span>
                                @endif
                            </td>
                            <td style="padding: 15px 20px; vertical-align: middle; text-align: center;">
                                <div class="d-flex justify-content-center gap-1">             
                                    <!-- EDIT Button -->
                                    <a href="{{ route('tasks.edit', $appointment->id) }}" 
                                       class="btn btn-outline-secondary btn-sm action-btn" 
                                       title="Edit Appointment"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- DELETE Button with confirmation -->
                                    <form action="{{ route('tasks.destroy', $appointment->id) }}" 
                                          method="POST" 
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger btn-sm action-btn" 
                                                title="Delete Appointment"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5" style="padding: 40px 20px;">
                                <div class="py-4">
                                    <i class="fas fa-calendar-times fa-3x mb-3" style="color: var(--primary-rose); opacity: 0.5;"></i>
                                    <h4 class="text-muted mb-2">No appointments found</h4>
                                    <p class="text-muted">All appointments will appear here once created</p>
                                    <a href="{{ route('tasks.create') }}" class="btn btn-admin mt-2">
                                        <i class="fas fa-plus me-1"></i> Create First Appointment
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($appointments->hasPages())
        <div class="card-footer border-0 bg-white" style="padding: 15px 20px; border-top: 1px solid rgba(232, 180, 184, 0.1);">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="text-muted text-center text-md-start" style="font-size: 0.85rem;">
                    <i class="fas fa-list-check me-1"></i> Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} of {{ $totalCount }} entries
                </div>
                <div>
                    <!-- Professional Pagination Design -->
                    <nav aria-label="Appointments pagination">
                        <ul class="pagination pagination-sm mb-0 flex-wrap justify-content-center">
                            <!-- Previous Page Link -->
                            @if($appointments->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" style="border: 1px solid #e8e8e8; border-radius: 6px; margin: 1px; padding: 5px 10px; color: #aaa; font-size: 0.8rem;">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $appointments->previousPageUrl() }}" style="border: 1px solid #e8e8e8; border-radius: 6px; margin: 1px; padding: 5px 10px; color: var(--charcoal); transition: all 0.2s ease; font-size: 0.8rem;">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            @endif
                            
                            <!-- Page Numbers -->
                            @foreach(range(1, $appointments->lastPage()) as $i)
                                @if($i == $appointments->currentPage())
                                <li class="page-item active d-none d-sm-block">
                                    <span class="page-link" style="background: linear-gradient(135deg, var(--primary-rose), var(--deep-rose)); border: none; border-radius: 6px; margin: 1px; padding: 5px 10px; font-size: 0.8rem;">
                                        {{ $i }}
                                    </span>
                                </li>
                                @elseif($i >= $appointments->currentPage() - 1 && $i <= $appointments->currentPage() + 1)
                                    @if($i <= 5) {{-- Show first 5 pages --}}
                                    <li class="page-item d-none d-sm-block">
                                        <a class="page-link" href="{{ $appointments->url($i) }}" style="border: 1px solid #e8e8e8; border-radius: 6px; margin: 1px; padding: 5px 10px; color: var(--charcoal); font-size: 0.8rem;">
                                            {{ $i }}
                                        </a>
                                    </li>
                                    @endif
                                @endif
                            @endforeach
                            
                            <!-- Current page indicator for mobile -->
                            <li class="page-item d-block d-sm-none">
                                <span class="page-link" style="border: none; background: transparent; margin: 1px; padding: 5px 10px; color: var(--charcoal); font-weight: 600; font-size: 0.8rem;">
                                    Page {{ $appointments->currentPage() }} of {{ $appointments->lastPage() }}
                                </span>
                            </li>
                            
                            <!-- Next Page Link -->
                            @if($appointments->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $appointments->nextPageUrl() }}" style="border: 1px solid #e8e8e8; border-radius: 6px; margin: 1px; padding: 5px 10px; color: var(--charcoal); transition: all 0.2s ease; font-size: 0.8rem;">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link" style="border: 1px solid #e8e8e8; border-radius: 6px; margin: 1px; padding: 5px 10px; color: #aaa; font-size: 0.8rem;">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Filters Section at Bottom -->
    <div class="card mb-4 mb-md-5 d-none d-md-block" style="border: none; box-shadow: var(--shadow-medium); border-radius: 15px;">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="me-3" style="color: var(--charcoal); font-weight: 500;">
                        <i class="fas fa-sliders-h me-1"></i> Quick Filters:
                    </span>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Quick filters">
                        <a href="{{ route('admin.appointments') }}" class="btn btn-outline-primary rounded-start-pill px-3 {{ request()->get('status') == null && request()->get('date') == null ? 'active' : '' }}">
                            <i class="fas fa-list me-1"></i> All
                        </a>
                        <a href="{{ route('admin.appointments') }}?status=scheduled" class="btn btn-outline-warning px-3 {{ request()->get('status') == 'scheduled' ? 'active' : '' }}">
                            <i class="fas fa-clock me-1"></i> Scheduled
                        </a>
                        <a href="{{ route('admin.appointments') }}?status=completed" class="btn btn-outline-success px-3 {{ request()->get('status') == 'completed' ? 'active' : '' }}">
                            <i class="fas fa-check-circle me-1"></i> Completed
                        </a>
                        <a href="{{ route('admin.appointments') }}?date=today" class="btn btn-outline-info px-3 {{ request()->get('date') == 'today' ? 'active' : '' }}">
                            <i class="fas fa-calendar-day me-1"></i> Today
                        </a>
                        <a href="{{ route('admin.appointments') }}?date=upcoming" class="btn btn-outline-secondary px-3 {{ request()->get('date') == 'upcoming' ? 'active' : '' }}">
                            <i class="fas fa-calendar-plus me-1"></i> Upcoming
                        </a>
                        <a href="{{ route('admin.appointments') }}?sort=price_high" class="btn btn-outline-dark rounded-end-pill px-3 {{ request()->get('sort') == 'price_high' ? 'active' : '' }}">
                            <i class="fas fa-money-bill-wave me-1"></i> High Value
                        </a>
                    </div>
                </div>
                <div>
                    <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="resetFilters()">
                        <i class="fas fa-redo me-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile bottom quick filters -->
    <div class="d-block d-md-none mb-4">
        <div class="card" style="border: none; box-shadow: var(--shadow-medium); border-radius: 15px;">
            <div class="card-body p-3">
                <h6 class="mb-3" style="color: var(--charcoal); font-weight: 600;">
                    <i class="fas fa-filter me-2" style="color: var(--primary-rose);"></i>
                    Quick Actions
                </h6>
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('tasks.create') }}" class="btn btn-admin w-100">
                            <i class="fas fa-plus me-1"></i> New Appointment
                        </a>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-secondary w-100" id="exportCsvBtnMobile">
                            <i class="fas fa-file-csv me-1"></i> Export CSV
                        </button>
                    </div>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted d-block mb-2">Quick Stats:</small>
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <div class="fw-bold" style="color: var(--deep-rose);">{{ $scheduledCount }}</div>
                            <small class="text-muted">Scheduled</small>
                        </div>
                        <div>
                            <div class="fw-bold" style="color: var(--deep-rose);">{{ $completedCount }}</div>
                            <small class="text-muted">Completed</small>
                        </div>
                        <div>
                            <div class="fw-bold" style="color: var(--deep-rose);">₱{{ number_format($totalRevenue, 0) }}</div>
                            <small class="text-muted">Revenue</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Date Range Modal -->
<div class="modal fade" id="customDateModal" tabindex="-1" aria-labelledby="customDateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: var(--shadow-large);">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, var(--soft-cream) 0%, var(--crisp-white) 100%); border-radius: 15px 15px 0 0; padding: 20px 25px;">
                <h5 class="modal-title" id="customDateModalLabel" style="color: var(--charcoal); font-weight: 600;">
                    <i class="fas fa-calendar-alt me-2" style="color: var(--deep-rose);"></i>
                    Custom Date Range
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="startDate" class="form-label" style="color: var(--charcoal); font-weight: 500;">Start Date</label>
                    <input type="date" class="form-control" id="startDate" style="border-radius: 8px; border: 1px solid rgba(232, 180, 184, 0.3); padding: 10px 15px;">
                </div>
                <div class="mb-4">
                    <label for="endDate" class="form-label" style="color: var(--charcoal); font-weight: 500;">End Date</label>
                    <input type="date" class="form-control" id="endDate" style="border-radius: 8px; border: 1px solid rgba(232, 180, 184, 0.3); padding: 10px 15px;">
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 8px 20px;">Cancel</button>
                <button type="button" class="btn btn-admin" onclick="applyCustomDateRange()" style="border-radius: 8px; padding: 8px 20px;">Apply Filter</button>
            </div>
        </div>
    </div>
</div>

<!-- Export Loading Modal -->
<div class="modal fade" id="exportLoadingModal" tabindex="-1" aria-labelledby="exportLoadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center" style="border-radius: 15px; border: none; box-shadow: var(--shadow-large);">
            <div class="modal-body p-4">
                <div class="spinner-border text-rose" style="width: 2.5rem; height: 2.5rem; border-width: 0.2em;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="mt-3 mb-0" style="color: var(--charcoal); font-weight: 600;">Generating Export...</h6>
                <p class="text-muted mb-0 small">Please wait a moment</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Responsive adjustments */
@media (max-width: 768px) {
    .stat-card {
        padding: 15px !important;
    }
    
    .stat-icon {
        font-size: 1.5rem !important;
    }
    
    .card-header {
        padding: 15px 20px !important;
    }
    
    .table-responsive {
        max-height: 400px !important;
    }
    
    th, td {
        padding: 12px 15px !important;
        font-size: 0.85rem !important;
    }
    
    .user-avatar {
        width: 35px !important;
        height: 35px !important;
        font-size: 1rem !important;
    }
    
    .action-btn {
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
    }
}

@media (max-width: 576px) {
    .admin-header h1 {
        font-size: 1.5rem !important;
    }
    
    .stat-card h2 {
        font-size: 1.25rem !important;
    }
    
    .card-header h5 {
        font-size: 1.1rem !important;
    }
    
    .badge {
        padding: 4px 8px !important;
        font-size: 0.7rem !important;
    }
}

/* Hide/show table columns for responsive */
@media (max-width: 1200px) {
    .d-xl-table-cell {
        display: none !important;
    }
}

@media (max-width: 992px) {
    .d-lg-table-cell {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .d-md-table-cell {
        display: none !important;
    }
}

/* Professional Pagination Styling */
.page-link {
    min-width: 32px;
    text-align: center;
    font-size: 0.8rem;
    font-weight: 500;
    transition: all 0.2s ease !important;
}

.page-link:hover {
    background-color: rgba(232, 180, 184, 0.1);
    border-color: var(--primary-rose) !important;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(232, 180, 184, 0.2);
}

.page-item.active .page-link {
    box-shadow: 0 4px 12px rgba(232, 180, 184, 0.3);
}

.page-item.disabled .page-link {
    cursor: not-allowed;
    background-color: #f9f9f9;
}

/* Mobile pagination */
@media (max-width: 576px) {
    .page-link {
        min-width: 28px;
        padding: 4px 8px !important;
        font-size: 0.75rem !important;
    }
}

/* Action buttons styling */
.action-btn {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px !important;
    border-width: 1.5px !important;
    transition: all 0.2s ease !important;
    font-size: 0.85rem;
}

.action-btn:hover {
    transform: translateY(-2px) scale(1.1);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.btn-outline-primary.action-btn:hover {
    background-color: var(--primary-rose);
    border-color: var(--primary-rose);
    color: white;
}

.btn-outline-secondary.action-btn:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-outline-danger.action-btn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Export button styling */
#exportCsvBtn, #exportPdfBtn, #exportCsvBtnMobile {
    transition: all 0.3s ease;
    border-width: 1.5px;
}

#exportCsvBtn:hover, #exportCsvBtnMobile:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(25, 135, 84, 0.2);
}

#exportPdfBtn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
}

/* Table search styling */
#tableSearch, #tableSearchMobile {
    transition: all 0.3s ease;
}

#tableSearch:focus, #tableSearchMobile:focus {
    box-shadow: 0 0 0 3px rgba(232, 180, 184, 0.2);
    border-color: var(--primary-rose);
}

/* Date range dropdown */
.dropdown-toggle {
    border-radius: 8px !important;
    border-width: 1.5px;
}

.dropdown-item {
    border-radius: 6px;
    margin: 2px 8px;
    padding: 8px 12px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: rgba(232, 180, 184, 0.1);
    color: var(--deep-rose);
}

/* Bottom filters section */
.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.btn-group .btn.active {
    background-color: rgba(232, 180, 184, 0.1);
    border-color: var(--primary-rose);
    color: var(--deep-rose);
    font-weight: 500;
}

/* Mobile filters scroll */
.overflow-auto::-webkit-scrollbar {
    display: none;
}

/* Spinner for export */
.text-rose {
    color: var(--deep-rose) !important;
}

/* Mobile bottom action card */
@media (max-width: 768px) {
    .card-body .row .col-6 .btn {
        padding: 8px 12px !important;
        font-size: 0.85rem !important;
    }
}
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
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
    
    // Table search functionality - desktop
    const tableSearch = document.getElementById('tableSearch');
    if (tableSearch) {
        tableSearch.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#appointmentsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
    
    // Table search functionality - mobile
    const tableSearchMobile = document.getElementById('tableSearchMobile');
    if (tableSearchMobile) {
        tableSearchMobile.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#appointmentsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
    
    // Delete form confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This appointment will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--deep-rose)',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                background: 'var(--crisp-white)',
                iconColor: 'var(--deep-rose)'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
    
    // Mobile export CSV
    document.getElementById('exportCsvMobile')?.addEventListener('click', function(e) {
        e.preventDefault();
        exportToCSV();
    });
    
    // Mobile export PDF
    document.getElementById('exportPdfMobile')?.addEventListener('click', function(e) {
        e.preventDefault();
        exportToPDF();
    });
    
    // Mobile export CSV from bottom button
    document.getElementById('exportCsvBtnMobile')?.addEventListener('click', function(e) {
        e.preventDefault();
        exportToCSV();
    });
});

// Set date range filter
function setDateRange(range) {
    let url = new URL(window.location.href);
    
    switch(range) {
        case 'today':
            const today = new Date().toISOString().split('T')[0];
            url.searchParams.set('start_date', today);
            url.searchParams.set('end_date', today);
            break;
        case 'week':
            const now = new Date();
            const startOfWeek = new Date(now.setDate(now.getDate() - now.getDay()));
            const endOfWeek = new Date(now.setDate(now.getDate() - now.getDay() + 6));
            url.searchParams.set('start_date', startOfWeek.toISOString().split('T')[0]);
            url.searchParams.set('end_date', endOfWeek.toISOString().split('T')[0]);
            break;
        case 'month':
            const currentDate = new Date();
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            url.searchParams.set('start_date', firstDay.toISOString().split('T')[0]);
            url.searchParams.set('end_date', lastDay.toISOString().split('T')[0]);
            break;
        case 'year':
            const year = new Date().getFullYear();
            url.searchParams.set('start_date', `${year}-01-01`);
            url.searchParams.set('end_date', `${year}-12-31`);
            break;
    }
    
    url.searchParams.set('date_range', range);
    window.location.href = url.toString();
}

// Apply custom date range
function applyCustomDateRange() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (!startDate || !endDate) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing dates',
            text: 'Please select both start and end dates',
            confirmButtonColor: 'var(--deep-rose)',
            background: 'var(--crisp-white)'
        });
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid range',
            text: 'Start date must be before end date',
            confirmButtonColor: 'var(--deep-rose)',
            background: 'var(--crisp-white)'
        });
        return;
    }
    
    let url = new URL(window.location.href);
    url.searchParams.set('start_date', startDate);
    url.searchParams.set('end_date', endDate);
    url.searchParams.set('date_range', 'custom');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('customDateModal'));
    modal.hide();
    
    // Redirect to filtered URL
    window.location.href = url.toString();
}

// Apply mobile filters
function applyMobileFilters() {
    const dateRange = document.getElementById('mobileDateRange').value;
    const sortBy = document.getElementById('mobileSortBy').value;
    
    let url = new URL(window.location.href);
    
    if (dateRange) {
        setDateRange(dateRange);
    } else {
        url.searchParams.delete('date_range');
        url.searchParams.delete('start_date');
        url.searchParams.delete('end_date');
    }
    
    if (sortBy) {
        url.searchParams.set('sort', sortBy);
    }
    
    window.location.href = url.toString();
}

// Reset all filters
function resetFilters() {
    let url = new URL(window.location.href);
    
    // Remove all filter parameters
    const paramsToRemove = ['status', 'date', 'date_range', 'start_date', 'end_date', 'sort'];
    paramsToRemove.forEach(param => {
        url.searchParams.delete(param);
    });
    
    window.location.href = url.toString();
}

// Export to CSV function
function exportToCSV() {
    // Show loading modal
    const exportModal = new bootstrap.Modal(document.getElementById('exportLoadingModal'));
    exportModal.show();
    
    // Get table data
    const table = document.getElementById('appointmentsTable');
    const rows = table.querySelectorAll('tr');
    const csvData = [];
    
    // Add headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        // Skip actions column for export
        if (!th.textContent.toLowerCase().includes('action')) {
            headers.push(th.textContent.trim());
        }
    });
    csvData.push(headers);
    
    // Add data rows
    rows.forEach((row, index) => {
        if (index === 0) return; // Skip header row
        
        const rowData = [];
        const cells = row.querySelectorAll('td');
        
        cells.forEach((cell, cellIndex) => {
            // Skip actions column (last column)
            if (cellIndex === cells.length - 1) return;
            
            let cellText = cell.textContent.trim();
            
            // Clean up the text
            cellText = cellText.replace(/\s+/g, ' ').trim();
            
            // Handle specific formatting
            if (cell.querySelector('.user-avatar')) {
                // Extract client name
                const nameElement = cell.querySelector('h6');
                cellText = nameElement ? nameElement.textContent.trim() : '';
            } else if (cell.querySelector('.badge-artist')) {
                // Extract artist name
                const artistElement = cell.querySelector('.badge-artist');
                cellText = artistElement ? artistElement.textContent.trim() : '';
            } else if (cell.querySelector('.badge')) {
                // Extract status
                const badgeElement = cell.querySelector('.badge');
                cellText = badgeElement.textContent.trim().replace(/[\n\r]+|[\s]{2,}/g, ' ');
            }
            
            // Escape commas and quotes for CSV
            if (cellText.includes(',') || cellText.includes('"') || cellText.includes('\n')) {
                cellText = `"${cellText.replace(/"/g, '""')}"`;
            }
            
            rowData.push(cellText);
        });
        
        // Only add row if it has data (not empty row)
        if (rowData.length > 0 && rowData.some(cell => cell.trim() !== '')) {
            csvData.push(rowData);
        }
    });
    
    // Convert to CSV string
    const csvString = csvData.map(row => row.join(',')).join('\n');
    
    // Create and download file
    const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `appointments_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Hide loading modal after a short delay
    setTimeout(() => {
        exportModal.hide();
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Export Complete',
            text: 'Appointments data has been exported to CSV',
            confirmButtonColor: 'var(--deep-rose)',
            background: 'var(--crisp-white)',
            timer: 3000,
            timerProgressBar: true
        });
    }, 1000);
}

// Export to PDF
document.getElementById('exportPdfBtn')?.addEventListener('click', function(e) {
    e.preventDefault();
    
    // Show loading modal
    const exportModal = new bootstrap.Modal(document.getElementById('exportLoadingModal'));
    exportModal.show();
    
    // In a real implementation, you would make an AJAX request to the server
    // to generate a PDF. For now, we'll simulate this with a message.
    setTimeout(() => {
        exportModal.hide();
        
        Swal.fire({
            icon: 'info',
            title: 'PDF Export',
            html: 'PDF export would be generated server-side. <br><br>In production, this would:<br>1. Send a request to your backend<br>2. Generate a formatted PDF<br>3. Download the file',
            confirmButtonColor: 'var(--deep-rose)',
            background: 'var(--crisp-white)',
            confirmButtonText: 'Got it!',
            showCancelButton: true,
            cancelButtonText: 'Learn more'
        }).then((result) => {
            if (result.isConfirmed) {
                // For demo purposes, we'll create a simple client-side PDF
                generateDemoPDF();
            }
        });
    }, 1500);
});

function exportToPDF() {
    // Show loading modal
    const exportModal = new bootstrap.Modal(document.getElementById('exportLoadingModal'));
    exportModal.show();
    
    setTimeout(() => {
        exportModal.hide();
        generateDemoPDF();
    }, 1000);
}

// Demo PDF generation (client-side, for demonstration only)
function generateDemoPDF() {
    // This is a simplified demo. In production, use server-side PDF generation
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Add title
    doc.setFontSize(18);
    doc.setTextColor(40, 40, 40);
    doc.text('Appointments Report', 20, 20);
    
    // Add date
    doc.setFontSize(10);
    doc.setTextColor(100, 100, 100);
    doc.text(`Generated: ${new Date().toLocaleDateString()}`, 20, 30);
    
    // Add stats
    doc.setFontSize(12);
    doc.setTextColor(60, 60, 60);
    doc.text(`Total Appointments: {{ $totalCount }}`, 20, 45);
    doc.text(`Scheduled: {{ $scheduledCount }}`, 20, 55);
    doc.text(`Completed: {{ $completedCount }}`, 20, 65);
    doc.text(`Total Revenue: ₱{{ number_format($totalRevenue, 0) }}`, 20, 75);
    
    // Note
    doc.setFontSize(10);
    doc.setTextColor(150, 150, 150);
    doc.text('Note: Full appointment list would be generated server-side', 20, 90);
    
    // Save the PDF
    doc.save(`appointments_report_${new Date().toISOString().split('T')[0]}.pdf`);
    
    // Show final message
    Swal.fire({
        icon: 'success',
        title: 'Demo PDF Generated',
        text: 'A sample PDF has been downloaded. In production, this would include the full data.',
        confirmButtonColor: 'var(--deep-rose)',
        background: 'var(--crisp-white)'
    });
}
</script>
@endsection