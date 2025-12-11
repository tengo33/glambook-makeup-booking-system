@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="container-fluid py-5 appointment-details-page">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4 animate-fade-in">
                <a href="{{ route('tasks.index') }}" class="btn-back-pink">
                    <i class="fas fa-arrow-left me-2"></i>
                    <span class="d-none d-md-inline">Back to Appointments</span>
                </a>
                <div class="flex-grow-1 text-center">
                    <h1 class="display-5 fw-bold text-pink-dark animate-slide-down">
                        Appointment Details
                    </h1>
                    <div class="mt-2">
                        <span class="appointment-id-badge">
                            <i class="fas fa-hashtag me-1"></i>
                            #{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Status Ribbon -->
            <div class="status-ribbon animate-slide-up">
                <div class="ribbon-content">
                    <div class="ribbon-icon">
                        @if($task->is_done)
                        <i class="fas fa-check-circle"></i>
                        @else
                        <i class="fas fa-clock"></i>
                        @endif
                    </div>
                    <div>
                        <span class="status-text {{ $task->is_done ? 'completed' : 'pending' }}">
                            {{ $task->is_done ? 'Completed' : 'Scheduled' }}
                        </span>
                        @if(!$task->is_done && $task->appointment_at->isFuture())
                        <div class="countdown-text">
                            <i class="fas fa-hourglass-half me-1"></i>
                            {{ $task->appointment_at->diffForHumans() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - Client & Service Info -->
        <div class="col-xl-8">
            <!-- Client & Artist Card -->
            <div class="card glass-card border-0 animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header bg-transparent border-0 pb-3">
                    <h3 class="card-title text-pink-dark">
                        <i class="fas fa-users me-2"></i>
                        Client & Artist Information
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Client -->
                        <div class="col-md-6">
                            <div class="info-card hover-lift">
                                <div class="info-header">
                                    <div class="avatar-wrapper">
                                        <div class="avatar avatar-client">
                                            <span>{{ strtoupper(substr($task->client_name, 0, 1)) }}</span>
                                            <div class="avatar-glow"></div>
                                        </div>
                                    </div>
                                    <div class="info-title">
                                        <h5 class="mb-1">Client</h5>
                                        <h4 class="fw-bold mb-0">{{ $task->client_name }}</h4>
                                    </div>
                                </div>
                                <div class="info-body mt-3">
                                    @if($task->phone)
                                    <div class="info-item">
                                        <i class="fas fa-phone text-pink"></i>
                                        <span>{{ $task->phone }}</span>
                                    </div>
                                    @endif
                                    @if($task->email)
                                    <div class="info-item">
                                        <i class="fas fa-envelope text-pink"></i>
                                        <span>{{ $task->email }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Artist -->
                        <div class="col-md-6">
                            <div class="info-card hover-lift">
                                <div class="info-header">
                                    <div class="avatar-wrapper">
                                        <div class="avatar avatar-artist">
                                            <span>{{ $task->user ? strtoupper(substr($task->user->name, 0, 1)) : '?' }}</span>
                                            <div class="avatar-glow"></div>
                                        </div>
                                    </div>
                                    <div class="info-title">
                                        <h5 class="mb-1">Assigned Artist</h5>
                                        @if($task->user)
                                        <h4 class="fw-bold mb-0">{{ $task->user->name }}</h4>
                                        @else
                                        <h4 class="fw-bold mb-0 text-warning">Not Assigned</h4>
                                        @endif
                                    </div>
                                </div>
                                @if($task->user)
                                <div class="info-body mt-3">
                                    <div class="info-item">
                                        <i class="fas fa-envelope text-pink"></i>
                                        <span>{{ $task->user->email }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Details Card -->
            <div class="card glass-card border-0 mt-4 animate-fade-in" style="animation-delay: 0.2s">
                <div class="card-header bg-transparent border-0 pb-3">
                    <h3 class="card-title text-pink-dark">
                        <i class="fas fa-spa me-2"></i>
                        Service Details
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="service-content shimmer-effect">
                        <div class="service-text">
                            <p>{{ $task->service_details ?? 'No service details provided' }}</p>
                        </div>
                        
                        @if($task->package)
                        <div class="package-tag mt-3">
                            <i class="fas fa-gift me-2"></i>
                            {{ $task->package }}
                        </div>
                        @endif
                        
                        @if($task->additional_notes)
                        <div class="notes-section mt-4 pt-4 border-top border-pink-light">
                            <h6 class="mb-2 text-pink">
                                <i class="fas fa-sticky-note me-2"></i>
                                Additional Notes
                            </h6>
                            <div class="notes-content">
                                {{ $task->additional_notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Location Card -->
            @if($task->address || $task->city || $task->location_notes)
            <div class="card glass-card border-0 mt-4 animate-fade-in" style="animation-delay: 0.3s">
                <div class="card-header bg-transparent border-0 pb-3">
                    <h3 class="card-title text-pink-dark">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Location Information
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="location-content">
                        @if($task->address)
                        <div class="location-item">
                            <div class="location-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="location-details">
                                <h6>Address</h6>
                                <p class="mb-0">{{ $task->address }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->city || $task->state || $task->zip_code)
                        <div class="location-item">
                            <div class="location-icon">
                                <i class="fas fa-city"></i>
                            </div>
                            <div class="location-details">
                                <h6>City/State</h6>
                                <p class="mb-0">
                                    {{ $task->city }}{{ $task->city && $task->state ? ', ' : '' }}{{ $task->state }}
                                    @if($task->zip_code)
                                        {{ $task->zip_code }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->country)
                        <div class="location-item">
                            <div class="location-icon">
                                <i class="fas fa-globe-americas"></i>
                            </div>
                            <div class="location-details">
                                <h6>Country</h6>
                                <p class="mb-0">{{ $task->country }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->location_notes)
                        <div class="location-item">
                            <div class="location-icon">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <div class="location-details">
                                <h6>Location Notes</h6>
                                <div class="notes-content">
                                    {{ $task->location_notes }}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->has_coordinates && $task->google_maps_link)
                        <div class="text-center mt-4">
                            <a href="{{ $task->google_maps_link }}" 
                               target="_blank" 
                               class="btn-map-pulse">
                                <i class="fas fa-map-marked-alt me-2"></i>
                                Open in Google Maps
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- AI Insights Card -->
            @if($task->predicted_duration || $task->predicted_no_show_score !== null)
            <div class="card glass-card border-0 mt-4 animate-fade-in" style="animation-delay: 0.4s">
                <div class="card-header bg-transparent border-0 pb-3">
                    <h3 class="card-title text-pink-dark">
                        <i class="fas fa-brain me-2"></i>
                        AI Insights & Analytics
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @if($task->predicted_duration)
                        <div class="col-md-6">
                            <div class="metric-card hover-scale">
                                <div class="metric-icon">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div class="metric-content">
                                    <div class="metric-label">Predicted Duration</div>
                                    <div class="metric-value">{{ $task->predicted_duration }} min</div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($task->predicted_no_show_score !== null)
                        <div class="col-md-6">
                            <div class="metric-card hover-scale">
                                <div class="metric-icon">
                                    <i class="fas fa-chart-pie"></i>
                                </div>
                                <div class="metric-content">
                                    <div class="metric-label">Show-Up Probability</div>
                                    @php
                                        $showUpPercentage = round((1 - $task->predicted_no_show_score) * 100);
                                        $riskColor = $showUpPercentage >= 80 ? 'text-success' : 
                                                    ($showUpPercentage >= 60 ? 'text-warning' : 'text-danger');
                                    @endphp
                                    <div class="metric-value {{ $riskColor }}">{{ $showUpPercentage }}%</div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar progress-bar-animated" 
                                             style="width: {{ $showUpPercentage }}%; background: linear-gradient(90deg, #ff8fa3, #ff6b9d);">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Appointment & Actions -->
        <div class="col-xl-4">
            <!-- Appointment Time Card -->
            <div class="card glass-card border-0 mb-4 animate-fade-in" style="animation-delay: 0.2s">
                <div class="card-body p-4 text-center">
                    <div class="calendar-widget">
                        <div class="calendar-header">
                            <div class="calendar-month">{{ $task->appointment_at->format('F') }}</div>
                            <div class="calendar-day">{{ $task->appointment_at->format('d') }}</div>
                            <div class="calendar-year">{{ $task->appointment_at->format('Y') }}</div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h4 class="fw-bold text-pink-dark mb-2">{{ $task->appointment_at->format('l') }}</h4>
                        <div class="time-display">
                            <i class="fas fa-clock me-2 text-pink"></i>
                            <span class="h4">{{ $task->appointment_at->format('h:i A') }}</span>
                        </div>
                    </div>
                    
                    <div class="appointment-status mt-4">
                        @if($task->is_done)
                        <div class="status-badge success-pulse">
                            <i class="fas fa-check-circle me-2"></i>
                            Completed Successfully
                        </div>
                        @elseif($task->appointment_at->isPast())
                        <div class="status-badge warning-pulse">
                            <i class="fas fa-history me-2"></i>
                            Past Appointment
                        </div>
                        @else
                        <div class="status-badge info-pulse">
                            <i class="fas fa-calendar-check me-2"></i>
                            Upcoming Appointment
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Card -->
            <div class="card glass-card border-0 mb-4 animate-fade-in" style="animation-delay: 0.3s">
                <div class="card-body p-4">
                    <h4 class="card-title text-pink-dark mb-4">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Payment Details
                    </h4>
                    
                    <div class="price-display">
                        <div class="price-amount">
                            ₱{{ number_format($task->price, 2) }}
                        </div>
                        <div class="price-label">Total Service Fee</div>
                    </div>
                    
                    @if($task->payment_status)
                    <div class="payment-status mt-3">
                        <span class="badge {{ $task->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }} rounded-pill px-3 py-2">
                            <i class="fas {{ $task->payment_status === 'paid' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                            {{ ucfirst($task->payment_status) }}
                        </span>
                    </div>
                    @endif
                    
                    @if($task->deposit_amount)
                    <div class="deposit-info mt-3">
                        <div class="info-label">Deposit Paid</div>
                        <div class="info-value">₱{{ number_format($task->deposit_amount, 2) }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions Card -->
<div class="actions-grid">
    @if(!$task->is_done)
    <form action="{{ route('tasks.mark-done', $task->id) }}" method="POST" class="action-item">
        @csrf
        <button type="submit" class="btn-action success-glow w-100">
            <i class="fas fa-check-circle me-2"></i>
            Mark as Completed
        </button>
    </form>
    @else
    <form action="{{ route('tasks.mark-not-done', $task->id) }}" method="POST" class="action-item">
        @csrf
        <button type="submit" class="btn-action warning-glow w-100">
            <i class="fas fa-redo me-2"></i>
            Mark as Pending
        </button>
    </form>
    @endif
    
    <a href="{{ route('tasks.edit', $task->id) }}" class="btn-action primary-glow action-item w-100">
        <i class="fas fa-edit me-2"></i>
        Edit Appointment
    </a>
    
    @if($task->has_coordinates && $task->google_maps_link)
    <a href="{{ $task->google_maps_link }}" target="_blank" class="btn-action map-glow action-item w-100">
        <i class="fas fa-map-marked-alt me-2"></i>
        View on Map
    </a>
    @endif
    
    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" 
          onsubmit="return confirm('Delete appointment for {{ addslashes($task->client_name) }}?')"
          class="action-item">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-action danger-glow w-100">
            <i class="fas fa-trash me-2"></i>
            Delete Appointment
        </button>
    </form>
</div>
                        

                      
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Meta -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="meta-info">
                <div class="meta-item">
                    <i class="fas fa-calendar-plus text-pink"></i>
                    <span>Created: {{ $task->created_at->format('M d, Y ∙ h:i A') }}</span>
                </div>
                @if($task->created_at != $task->updated_at)
                <div class="meta-item">
                    <i class="fas fa-edit text-pink"></i>
                    <span>Updated: {{ $task->updated_at->format('M d, Y ∙ h:i A') }}</span>
                </div>
                @endif
                @if(auth()->user()->role === 'admin')
                <div class="meta-item">
                    <i class="fas fa-user-shield text-pink"></i>
                    <span>Admin View</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>



<style>
.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-item {
    display: block;
    width: 100%;
}

.btn-action {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    border: none;
    border-radius: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    text-align: center;
    white-space: nowrap;
}

/* Make sure all buttons are the same height */
.btn-action i {
    flex-shrink: 0;
}

.btn-action span {
    flex-grow: 1;
    text-align: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .actions-grid {
        grid-template-columns: 1fr;
    }
}

/* Color Variables */
:root {
    --pink-light: #ffb6c1;
    --pink-medium: #ff8fa3;
    --pink-dark: #d63384;
    --pink-soft: #fff0f5;
    --pink-softer: #fffafb;
    --text-dark: #2d3748;
    --text-muted: #718096;
}

/* Base Styles */
.appointment-details-page {
    background: linear-gradient(135deg, #fffafb 0%, #fff5f7 100%);
    min-height: 100vh;
}

/* Typography */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.text-pink { color: var(--pink-medium) !important; }
.text-pink-dark { color: var(--pink-dark) !important; }
.bg-pink-light { background-color: var(--pink-light) !important; }
.border-pink-light { border-color: var(--pink-light) !important; }

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(255, 143, 163, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(255, 143, 163, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 143, 163, 0); }
}

@keyframes shimmer {
    0% { background-position: -200px 0; }
    100% { background-position: calc(200px + 100%) 0; }
}

@keyframes progress-bar-stripes {
    from { background-position: 1rem 0; }
    to { background-position: 0 0; }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}

.animate-slide-down {
    animation: slideDown 0.5s ease-out forwards;
}

.animate-slide-up {
    animation: slideUp 0.5s ease-out forwards;
}

/* Glass Card Effect */
.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 182, 193, 0.2);
    box-shadow: 
        0 4px 20px rgba(255, 182, 193, 0.1),
        0 1px 2px rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.glass-card:hover {
    transform: translateY(-4px);
    box-shadow: 
        0 12px 30px rgba(255, 182, 193, 0.15),
        0 4px 6px rgba(0, 0, 0, 0.05);
    border-color: rgba(255, 182, 193, 0.4);
}

/* Back Button */
.btn-back-pink {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: rgba(255, 182, 193, 0.1);
    border: 1px solid rgba(255, 182, 193, 0.3);
    border-radius: 50px;
    color: var(--pink-dark);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-back-pink:hover {
    background: rgba(255, 182, 193, 0.2);
    transform: translateX(-5px);
}

/* Status Ribbon */
.status-ribbon {
    background: linear-gradient(135deg, #ffffff 0%, var(--pink-softer) 100%);
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(255, 182, 193, 0.3);
    margin: 0 auto;
    max-width: 300px;
}

.ribbon-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.ribbon-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--pink-medium) 0%, var(--pink-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 15px rgba(255, 143, 163, 0.3);
}

.status-text {
    font-size: 1.2rem;
    font-weight: 600;
}

.status-text.completed {
    color: #28a745;
}

.status-text.pending {
    color: var(--pink-dark);
}

.countdown-text {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin-top: 4px;
}

/* Avatar Styles */
.avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.5rem;
    position: relative;
}

.avatar-client {
    background: linear-gradient(135deg, #ff8fa3 0%, #ff6b9d 100%);
}

.avatar-artist {
    background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%);
}

.avatar-glow {
    position: absolute;
    inset: -2px;
    background: inherit;
    border-radius: inherit;
    filter: blur(8px);
    opacity: 0.6;
    z-index: -1;
}

/* Info Card */
.info-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(255, 182, 193, 0.2);
    height: 100%;
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(255, 182, 193, 0.15);
}

.info-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.info-title h5 {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-bottom: 4px;
}

.info-title h4 {
    color: var(--text-dark);
    font-size: 1.25rem;
}

.info-body {
    border-top: 1px solid rgba(255, 182, 193, 0.1);
    padding-top: 15px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    color: var(--text-dark);
}

.info-item i {
    width: 20px;
    text-align: center;
}

/* Service Content */
.service-content {
    position: relative;
    overflow: hidden;
}

.shimmer-effect {
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}

.service-text p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-dark);
}

.package-tag {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, rgba(255, 182, 193, 0.2) 0%, rgba(255, 182, 193, 0.3) 100%);
    color: var(--pink-dark);
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 500;
}

.notes-section {
    border-top-color: rgba(255, 182, 193, 0.3) !important;
}

.notes-content {
    background: rgba(255, 182, 193, 0.05);
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid var(--pink-medium);
}

/* Location Styles */
.location-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    border-bottom: 1px solid rgba(255, 182, 193, 0.1);
}

.location-item:last-child {
    border-bottom: none;
}

.location-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 182, 193, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--pink-dark);
    font-size: 1.2rem;
}

.location-details h6 {
    color: var(--pink-dark);
    margin-bottom: 5px;
    font-size: 0.875rem;
}

.location-details p {
    color: var(--text-dark);
    margin-bottom: 0;
}

.btn-map-pulse {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--pink-medium) 0%, var(--pink-dark) 100%);
    color: white;
    border: none;
    border-radius: 50px;
    font-weight: 500;
    text-decoration: none;
    animation: pulse 2s infinite;
    transition: all 0.3s ease;
}

.btn-map-pulse:hover {
    transform: scale(1.05);
    color: white;
}

/* Metric Cards */
.metric-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid rgba(255, 182, 193, 0.2);
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
}

.hover-scale:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(255, 182, 193, 0.15);
}

.metric-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, rgba(255, 182, 193, 0.1) 0%, rgba(255, 182, 193, 0.2) 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: var(--pink-dark);
    font-size: 1.5rem;
}

.metric-label {
    color: var(--text-muted);
    font-size: 0.875rem;
    margin-bottom: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.metric-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--pink-dark);
    margin-bottom: 5px;
}

/* Progress Bar */
.progress {
    height: 8px;
    background-color: rgba(255, 182, 193, 0.1);
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    border-radius: 4px;
}

.progress-bar-animated {
    background-size: 1rem 1rem;
    animation: progress-bar-stripes 1s linear infinite;
}

/* Calendar Widget */
.calendar-widget {
    width: 120px;
    height: 120px;
    margin: 0 auto;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(255, 182, 193, 0.3);
}

.calendar-header {
    height: 100%;
    background: linear-gradient(135deg, var(--pink-medium) 0%, var(--pink-dark) 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: white;
}

.calendar-month {
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.9;
}

.calendar-day {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1;
    margin: 5px 0;
}

.calendar-year {
    font-size: 0.875rem;
    opacity: 0.8;
}

.time-display {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: rgba(255, 182, 193, 0.1);
    border-radius: 50px;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 500;
}

.success-pulse {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    animation: pulse 3s infinite;
}

.warning-pulse {
    background: rgba(255, 193, 7, 0.1);
    color: #f57c00;
    animation: pulse 3s infinite;
}

.info-pulse {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    animation: pulse 3s infinite;
}

/* Price Display */
.price-display {
    text-align: center;
    padding: 20px 0;
}

.price-amount {
    font-size: 3rem;
    font-weight: 700;
    color: var(--pink-dark);
    line-height: 1;
}

.price-label {
    color: var(--text-muted);
    margin-top: 5px;
}

/* Actions Grid */
.actions-grid {
    display: grid;
    gap: 12px;
}

.btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
    border: none;
    border-radius: 12px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    width: 100%;
}

.primary-glow {
    background: linear-gradient(135deg, var(--pink-medium) 0%, var(--pink-dark) 100%);
    color: white;
}

.primary-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 143, 163, 0.4);
    color: white;
}

.success-glow {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    color: white;
}

.success-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    color: white;
}

.warning-glow {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    color: #212529;
}

.warning-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
}

.danger-glow {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.danger-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
    color: white;
}

.map-glow {
    background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
    color: white;
}

.map-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(156, 39, 176, 0.4);
    color: white;
}

/* Meta Info */
.meta-info {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
    padding: 20px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 12px;
    border: 1px solid rgba(255, 182, 193, 0.2);
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
}

.meta-item i {
    font-size: 0.875rem;
}

/* Appointment ID Badge */
.appointment-id-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: rgba(255, 182, 193, 0.1);
    border-radius: 6px;
    color: var(--pink-dark);
    font-weight: 500;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 16px;
}

.modal-header {
    background: linear-gradient(135deg, #fffafb 0%, #fff5f7 100%);
    border-radius: 16px 16px 0 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .ribbon-content {
        flex-direction: column;
        text-align: center;
    }
    
    .info-header {
        flex-direction: column;
        text-align: center;
    }
    
    .price-amount {
        font-size: 2.5rem;
    }
    
    .meta-info {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .calendar-widget {
        width: 100px;
        height: 100px;
    }
    
    .calendar-day {
        font-size: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add click animations to buttons
    const buttons = document.querySelectorAll('.btn-action, .btn-back-pink, .btn-map-pulse');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.href || this.getAttribute('type') !== 'submit') {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            }
        });
    });

    // Add scroll animation for cards
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all cards
    document.querySelectorAll('.glass-card').forEach(card => {
        observer.observe(card);
    });

    // Add hover effect to metric cards
    const metricCards = document.querySelectorAll('.metric-card');
    metricCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });

    // Handle modal show/hide animations
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', () => {
            document.body.style.overflow = 'hidden';
        });
        
        deleteModal.addEventListener('hidden.bs.modal', () => {
            document.body.style.overflow = '';
        });
    }

    // Add parallax effect to background on scroll
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        document.querySelector('.appointment-details-page').style.backgroundPosition = `0px ${rate}px`;
    });
});
</script>
@endsection