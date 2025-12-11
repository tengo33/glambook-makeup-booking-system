@extends('layouts.app')

@section('content')
<!-- Hero Banner with Greeting -->
<div class="hero-banner fade-in-up">
    <div class="row align-items-center">
        <div class="col-md-8">
            @php
                $hour = now()->hour;
                if ($hour < 12) $greeting = "Good Morning";
                elseif ($hour < 17) $greeting = "Good Afternoon";
                else $greeting = "Good Evening";

                $firstName = explode(' ', Auth::user()->name)[0];
            @endphp

            <h1 class="display-5 fw-bold mb-2">
                {{ $greeting }}, {{ $firstName }} 💕
            </h1>
            <p class="lead mb-0">Ready to create beautiful moments today!</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="bg-white rounded-pill px-4 py-2 d-inline-block shadow-sm" style="border: 2px solid var(--primary-rose);">
                <i class="fas fa-calendar-day me-2" style="color: var(--primary-rose);"></i>
                <strong class="text-dark">{{ now()->format('l, F j, Y') }}</strong>
            </div>
        </div>
    </div>
</div>

<!-- Today's Appointments Section -->
@php
    $hasTodayAppointments = isset($todayAppointments) && $todayAppointments->count() > 0;
@endphp

@if($hasTodayAppointments)
<div class="row mb-4" id="todayAppointments">
    <div class="col-12">
        <div class="card luxe-card slide-in-left border-0 shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, var(--primary-rose), #e8b4b8); border: none;">
                <h5 class="mb-0 text-white"><i class="fas fa-clock me-2"></i>Today's Schedule</h5>
                <span class="badge bg-white text-dark rounded-pill px-3 py-2 fw-bold" id="todayAppointmentsCount">
                    <i class="fas fa-users me-1"></i>{{ $todayAppointments->count() }} appointment{{ $todayAppointments->count() > 1 ? 's' : '' }}
                </span>
            </div>
            <div class="card-body" style="background: #f9f0f5ff;">
                <div class="row">
                    @foreach($todayAppointments as $appointment)
                    @php
                        $appointmentTime = \Carbon\Carbon::parse($appointment->appointment_at);
                        $now = now();
                        $isCompleted = $appointment->is_done || $appointmentTime->isPast();
                        $timeDiff = $now->diff($appointmentTime);
                        
                        $statusClass = $isCompleted ? 'completed' : ($appointmentTime->isToday() ? 'upcoming' : 'future');
                        $statusIcon = $isCompleted ? 'fa-check-circle' : ($appointmentTime->isToday() ? 'fa-clock' : 'fa-calendar');
                        $statusColor = $isCompleted ? 'success' : ($appointmentTime->isToday() ? 'warning' : 'info');
                    @endphp
                    
                    <div class="col-md-6 col-lg-4 mb-3 appointment-card-wrapper" id="appointment-card-{{ $appointment->id }}">
                        <div class="card appointment-card h-100 border-0 shadow-sm hover-lift" 
                             data-appointment-id="{{ $appointment->id }}"
                             data-status="{{ $statusClass }}">
                            <div class="card-body position-relative">
                                <!-- Status Badge -->
                                <div class="position-absolute top-0 end-0 mt-2 me-2">
                                    <span class="badge bg-{{ $statusColor }} rounded-pill appointment-status-badge">
                                        <i class="fas {{ $statusIcon }} me-1"></i>
                                        {{ $isCompleted ? 'Completed' : 'Upcoming' }}
                                    </span>
                                </div>
                                
                                <!-- Client Info -->
                                <div class="mb-3">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 50px; color: #fff; background: var(--primary-rose); font-weight: 600; font-size: 0.9rem;">
                                            {{ $appointment->client_name }}
                                        </span>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-phone me-1"></i>{{ $appointment->phone }}
                                    </small>
                                </div>
                                
                                <!-- Service Details -->
                                <div class="mb-3">
                                    <p class="card-text text-dark mb-1">
                                        <i class="fas fa-scissors me-2" style="color: var(--primary-rose);"></i>
                                        <strong>{{ $appointment->service_details ?? 'Hair & Makeup' }}</strong>
                                    </p>
                                    @if($appointment->package)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-box me-1"></i>{{ $appointment->package }}
                                    </small>
                                    @endif
                                    @if($appointment->addons)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-plus-circle me-1"></i>Add-ons included
                                    </small>
                                    @endif
                                </div>
                                
                                <!-- Time Info -->
                                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                    <div>
                                        <span class="text-dark fw-semibold">
                                            <i class="fas fa-clock me-1" style="color: var(--primary-rose);"></i>
                                            {{ $appointmentTime->format('g:i A') }}
                                        </span>
                                    </div>
                                    <div class="text-end appointment-status-text">
                                        @if(!$isCompleted)
                                        <span class="countdown-timer badge bg-light text-dark">
                                            @if($timeDiff->d > 0)
                                            <i class="fas fa-hourglass-start me-1"></i>in {{ $timeDiff->d }}d
                                            @elseif($timeDiff->h > 0)
                                            <i class="fas fa-hourglass-half me-1"></i>in {{ $timeDiff->h }}h {{ $timeDiff->i }}m
                                            @else
                                            <i class="fas fa-hourglass-end me-1"></i>in {{ $timeDiff->i }}m
                                            @endif
                                        </span>
                                        @else
                                        <span class="text-success">
                                            <i class="fas fa-check me-1"></i>Done
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Quick Actions -->
                                <div class="mt-3 pt-2 border-top">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('tasks.edit', $appointment->id) }}" 
                                           class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        @if(!$isCompleted)
                                        <button class="btn btn-sm btn-success flex-fill mark-done-btn" 
                                                data-id="{{ $appointment->id }}">
                                            <i class="fas fa-check me-1"></i>Mark Done
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@else
<!-- No appointments today message -->
<div class="row mb-4" id="noAppointmentsToday">
    <div class="col-12">
        <div class="card luxe-card slide-in-left border-0 shadow" style="background: linear-gradient(135deg, #fdf2f2, #f9f5f0);">
            <div class="card-body text-center py-5">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-calendar-check fa-4x" style="color: var(--primary-rose);"></i>
                </div>
                <h4 class="text-dark fw-bold mb-3">No Appointments Today</h4>
                <p class="text-muted mb-4">Enjoy your day! You're all caught up.</p>
                <a href="{{ route('tasks.create') }}" class="btn btn-luxe px-4">
                    <i class="fas fa-plus me-2"></i>Book New Appointment
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Animated Stats Cards -->
<div class="row mb-5">
    <div class="col-md-3 mb-4">
        <div class="card stat-card luxe-card border-0 shadow-sm" id="totalCard">
            <div class="card-body text-center py-4 position-relative">
                <div class="floating-icon">
                    <i class="fas fa-calendar-check fa-2x"></i>
                </div>
                <h2 class="stat-number fw-bold mt-3" id="totalCount">{{ $total ?? 0 }}</h2>
                <p class="stat-label text-muted mb-0">Total Appointments</p>
                <div class="progress mt-3" style="height: 4px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card luxe-card border-0 shadow-sm" id="upcomingCard">
            <div class="card-body text-center py-4 position-relative">
                <div class="floating-icon">
                    <i class="fas fa-clock fa-2x" style="color: var(--primary-rose);"></i>
                </div>
                <h2 class="stat-number fw-bold mt-3" id="upcomingCount">{{ $upcoming ?? 0 }}</h2>
                <p class="stat-label text-muted mb-0">Upcoming</p>
                <div class="progress mt-3" style="height: 4px;">
                    @php
                        $upcomingPercent = ($total ?? 0) > 0 ? min((($upcoming ?? 0)/($total ?? 1))*100, 100) : 0;
                    @endphp
                    <div class="progress-bar" id="upcomingProgress" style="width: {{ $upcomingPercent }}%; background: var(--primary-rose);"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card luxe-card border-0 shadow-sm" id="todayCard">
            <div class="card-body text-center py-4 position-relative">
                <div class="floating-icon">
                    <i class="fas fa-sun fa-2x" style="color: var(--warm-gold);"></i>
                </div>
                <h2 class="stat-number fw-bold mt-3" id="todayCount">{{ $todayAppointments->count() ?? 0 }}</h2>
                <p class="stat-label text-muted mb-0">Today</p>
                <div class="progress mt-3" style="height: 4px;">
                    @php
                        $todayPercent = ($total ?? 0) > 0 ? min((($todayAppointments->count() ?? 0)/($total ?? 1))*100, 100) : 0;
                    @endphp
                    <div class="progress-bar" id="todayProgress" style="width: {{ $todayPercent }}%; background: var(--warm-gold);"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card stat-card luxe-card border-0 shadow-sm" id="completedCard">
            <div class="card-body text-center py-4 position-relative">
                <div class="floating-icon">
                    <i class="fas fa-check-circle fa-2x" style="color: #28a745;"></i>
                </div>
                <h2 class="stat-number fw-bold mt-3" id="completedCount">{{ $past ?? 0 }}</h2>
                <p class="stat-label text-muted mb-0">Completed</p>
                <div class="progress mt-3" style="height: 4px;">
                    @php
                        $completedPercent = ($total ?? 0) > 0 ? min((($past ?? 0)/($total ?? 1))*100, 100) : 0;
                    @endphp
                    <div class="progress-bar bg-success" id="completedProgress" style="width: {{ $completedPercent }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Status Chart -->
    <div class="col-md-6 mb-4">
        <div class="card luxe-card h-100 border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f8f9fa, #fff);">
                <h5 class="mb-0"><i class="fas fa-chart-pie me-2" style="color: var(--primary-rose);"></i>Appointment Status</h5>
                <span class="badge bg-light text-dark">This Month</span>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                    <canvas id="statusChart" width="300" height="300"></canvas>
                </div>
                <div class="chart-legend mt-3">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="legend-item d-flex align-items-center justify-content-center">
                                <span class="legend-color me-2" style="background: #28a745; width: 12px; height: 12px; border-radius: 50%;"></span>
                                <span id="chartCompletedCount">Completed: {{ $past ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="legend-item d-flex align-items-center justify-content-center">
                                <span class="legend-color me-2" style="background: var(--primary-rose); width: 12px; height: 12px; border-radius: 50%;"></span>
                                <span id="chartUpcomingCount">Upcoming: {{ $upcoming ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="col-md-6 mb-4">
        <div class="card luxe-card h-100 border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f8f9fa, #fff);">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2" style="color: #28a745;"></i>Monthly Overview</h5>
                <span class="badge bg-light text-dark">{{ now()->format('F Y') }}</span>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Calendar Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card luxe-card border-0 shadow-lg overflow-hidden">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, var(--primary-rose), #e8b4b8); border: none;">
                <div>
                    <h5 class="mb-0 text-white"><i class="fas fa-calendar-alt me-2"></i>Appointment Calendar</h5>
                    <small class="text-white opacity-75">View all your appointments at a glance</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-light me-2" id="prevMonth">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light" id="nextMonth">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="calendar" style="min-height: 600px;"></div>
            </div>
            <div class="card-footer bg-light py-3">
                <div class="row">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="d-flex align-items-center">
                            <span class="legend-dot me-2" style="background: #28a745;"></span>
                            <small>Completed</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="d-flex align-items-center">
                            <span class="legend-dot me-2" style="background: var(--primary-rose);"></span>
                            <small>Upcoming</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <span class="legend-dot me-2" style="background: #ffc107;"></span>
                            <small>Today</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card luxe-card border-0 shadow-sm" style="background: linear-gradient(135deg, #fdf2f2, #f9f5f0);">
            <div class="card-body text-center py-4">
                <h5 class="mb-4 fw-bold text-dark">Quick Actions</h5>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('tasks.create') }}" class="btn btn-luxe px-4">
                        <i class="fas fa-plus me-2"></i>New Appointment
                    </a>
                    <a href="{{ route('tasks.index') }}" class="btn btn-luxe-outline px-4">
                        <i class="fas fa-list me-2"></i>View All Appointments
                    </a>
                    @if($hasTodayAppointments)
                    <a href="#todayAppointments" class="btn btn-luxe-outline px-4">
                        <i class="fas fa-clock me-2"></i>Today's Schedule
                    </a>
                    @endif
                    <button class="btn btn-luxe-outline px-4" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3"></div>
@endsection

@section('styles')
<style>
/* Enhanced Calendar Styling */
.fc {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.fc .fc-toolbar-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
}

.fc .fc-button {
    background-color: var(--primary-rose);
    border-color: var(--primary-rose);
    font-weight: 500;
}

.fc .fc-button:hover {
    background-color: #d8a1a6;
    border-color: #d8a1a6;
}

.fc .fc-button-primary:not(:disabled).fc-button-active,
.fc .fc-button-primary:not(:disabled):active {
    background-color: #c19196;
    border-color: #c19196;
}

.fc-day-today {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.fc-daygrid-day-top {
    justify-content: center;
}

.fc-daygrid-day-number {
    font-weight: 600;
    color: #333;
    padding: 8px !important;
}

.fc-event {
    border-radius: 6px;
    border: none;
    padding: 4px 8px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.fc-event:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.fc-event-title {
    font-weight: 600;
}

/* Appointment card hover effect */
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
}

/* Floating icons for stats */
.floating-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover .floating-icon {
    transform: translateY(-5px) scale(1.1);
}

/* Legend dots */
.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

/* Countdown timer animation */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.countdown-timer {
    animation: pulse 2s infinite;
}

/* Progress bars */
.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.5s ease-in-out;
}

/* Custom scrollbar for calendar */
#calendar ::-webkit-scrollbar {
    width: 8px;
}

#calendar ::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

#calendar ::-webkit-scrollbar-thumb {
    background: var(--primary-rose);
    border-radius: 4px;
}

#calendar ::-webkit-scrollbar-thumb:hover {
    background: #d8a1a6;
}

/* Toast styling */
.toast-container {
    z-index: 9999;
}

/* Fade animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in-up {
    animation: fadeIn 0.5s ease-out;
}

.slide-in-left {
    animation: fadeIn 0.5s ease-out 0.1s backwards;
}

/* Smooth transitions */
.stat-number, .progress-bar {
    transition: all 0.3s ease;
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendar;
    const csrfToken = '{{ csrf_token() }}';
    
    // Store chart instances globally
    let statusChartInstance = null;
    let monthlyChartInstance = null;
    
    // Initialize FullCalendar
    function initCalendar() {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap5',
            height: 'auto',
            firstDay: 0,
            weekNumbers: true,
            weekNumberFormat: { week: 'numeric' },
            navLinks: true,
            editable: false,
            selectable: false,
            dayMaxEvents: 3,
            events: '{{ route("dashboard.calendar-events") }}',
            eventDidMount: function(info) {
                // Highlight today's appointments
                const today = new Date();
                today.setHours(0,0,0,0);
                const eventDate = new Date(info.event.start);
                eventDate.setHours(0,0,0,0);
                
                if (today.getTime() === eventDate.getTime()) {
                    info.el.style.boxShadow = '0 0 0 2px #ffc107';
                }
            }
        });

        calendar.render();
        
        // Navigation buttons
        document.getElementById('prevMonth').addEventListener('click', () => {
            calendar.prev();
        });
        
        document.getElementById('nextMonth').addEventListener('click', () => {
            calendar.next();
        });
    }

    // Mark appointment as done with real-time updates
    function setupMarkDoneButtons() {
        // Use event delegation for dynamic content
        document.addEventListener('click', function(event) {
            const button = event.target.closest('.mark-done-btn');
            if (button) {
                event.preventDefault();
                
                const appointmentId = button.dataset.id;
                const card = button.closest('.appointment-card');
                
                if (confirm('Mark this appointment as completed?')) {
                    markAppointmentAsDone(appointmentId, card, button);
                }
            }
        });
    }
    
    function markAppointmentAsDone(appointmentId, card, button) {
        // Show loading state
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
        button.disabled = true;
        
        // Add cache-busting parameter
        const timestamp = new Date().getTime();
        
        fetch(`/tasks/${appointmentId}/complete?_=${timestamp}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                }).catch(() => {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success response:', data);
            
            if (data.success) {
                // Update UI immediately
                updateAppointmentCard(appointmentId, card, button);
                
                // Update stats from response (REAL-TIME UPDATE)
                if (data.updated_stats) {
                    updateDashboardStatsRealtime(data.updated_stats);
                } else {
                    // Fallback: manually update stats
                    updateDashboardStats();
                }
                
                // Update calendar if exists
                if (calendar) {
                    calendar.refetchEvents();
                }
                
                // Show success message
                showToast(data.message || 'Appointment marked as completed!', 'success');
                
                // Force refresh the chart if exists
                refreshChartsImmediately();
            } else {
                throw new Error(data.message || 'Failed to mark appointment as complete');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Error updating appointment. Please try again.', 'danger');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
    
    // Helper function to update appointment card UI
    function updateAppointmentCard(appointmentId, card, button) {
        // Update status badge
        const badge = card.querySelector('.appointment-status-badge');
        if (badge) {
            badge.innerHTML = '<i class="fas fa-check-circle me-1"></i>Completed';
            badge.className = 'badge bg-success rounded-pill appointment-status-badge';
        }
        
        // Update countdown timer
        const countdownSection = card.querySelector('.appointment-status-text');
        if (countdownSection) {
            countdownSection.innerHTML = `
                <span class="text-success">
                    <i class="fas fa-check me-1"></i>Done
                </span>
            `;
        }
        
        // Remove the mark done button
        if (button) {
            button.remove();
        }
        
        // Update the edit button to take full width if mark done button was removed
        const editBtn = card.querySelector('.btn-outline-primary');
        if (editBtn) {
            editBtn.classList.remove('flex-fill');
            editBtn.classList.add('w-100');
        }
        
        // Update appointment count badge
        updateAppointmentCountBadge();
    }
    
    // Update appointment count badge
    function updateAppointmentCountBadge() {
        const todayCards = document.querySelectorAll('.appointment-card-wrapper:not(.removed)');
        const headerBadge = document.getElementById('todayAppointmentsCount');
        
        if (headerBadge && todayCards.length > 0) {
            headerBadge.innerHTML = `<i class="fas fa-users me-1"></i>${todayCards.length} appointment${todayCards.length > 1 ? 's' : ''}`;
        } else if (todayCards.length === 0) {
            // If no appointments left, show the "No appointments today" message
            const todaySection = document.getElementById('todayAppointments');
            const noAppointmentsSection = document.getElementById('noAppointmentsToday');
            
            if (todaySection) {
                todaySection.style.display = 'none';
            }
            
            if (!noAppointmentsSection) {
                const noAppointmentsHTML = `
                    <div class="row mb-4" id="noAppointmentsToday">
                        <div class="col-12">
                            <div class="card luxe-card slide-in-left border-0 shadow" style="background: linear-gradient(135deg, #fdf2f2, #f9f5f0);">
                                <div class="card-body text-center py-5">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-calendar-check fa-4x" style="color: var(--primary-rose);"></i>
                                    </div>
                                    <h4 class="text-dark fw-bold mb-3">No Appointments Today</h4>
                                    <p class="text-muted mb-4">Enjoy your day! You're all caught up.</p>
                                    <a href="{{ route('tasks.create') }}" class="btn btn-luxe px-4">
                                        <i class="fas fa-plus me-2"></i>Book New Appointment
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                const todayContainer = document.querySelector('#todayAppointments').parentElement;
                todayContainer.insertAdjacentHTML('afterend', noAppointmentsHTML);
            }
        }
    }
    
    // NEW: Function to update stats in real-time from response data
    function updateDashboardStatsRealtime(stats) {
        console.log('Updating stats with real-time data:', stats);
        
        // Update total appointments
        if (stats.total !== undefined) {
            const totalElement = document.getElementById('totalCount');
            if (totalElement) {
                totalElement.textContent = stats.total;
            }
        }
        
        // Update upcoming count
        if (stats.upcoming !== undefined) {
            const upcomingElement = document.getElementById('upcomingCount');
            if (upcomingElement) {
                upcomingElement.textContent = stats.upcoming;
            }
        }
        
        // Update completed count
        if (stats.past !== undefined) {
            const completedElement = document.getElementById('completedCount');
            if (completedElement) {
                completedElement.textContent = stats.past;
            }
        }
        
        // Update today count
        if (stats.today !== undefined) {
            const todayElement = document.getElementById('todayCount');
            if (todayElement) {
                todayElement.textContent = stats.today;
            }
        }
        
        // Update progress bars
        updateProgressBars(stats);
        
        // Update chart legend if exists
        updateChartLegend(stats);
    }
    
    // NEW: Update progress bars
    function updateProgressBars(stats) {
        const total = parseInt(stats.total) || 0;
        
        // Update upcoming progress
        if (stats.upcoming !== undefined && total > 0) {
            const upcomingPercent = Math.min((stats.upcoming / total) * 100, 100);
            const upcomingProgressBar = document.getElementById('upcomingProgress');
            if (upcomingProgressBar) {
                upcomingProgressBar.style.width = `${upcomingPercent}%`;
            }
        }
        
        // Update today progress
        if (stats.today !== undefined && total > 0) {
            const todayPercent = Math.min((stats.today / total) * 100, 100);
            const todayProgressBar = document.getElementById('todayProgress');
            if (todayProgressBar) {
                todayProgressBar.style.width = `${todayPercent}%`;
            }
        }
        
        // Update completed progress
        if (stats.past !== undefined && total > 0) {
            const completedPercent = Math.min((stats.past / total) * 100, 100);
            const completedProgressBar = document.getElementById('completedProgress');
            if (completedProgressBar) {
                completedProgressBar.style.width = `${completedPercent}%`;
            }
        }
    }
    
    // NEW: Update chart legend
    function updateChartLegend(stats) {
        // Update status chart legend
        if (stats.past !== undefined) {
            const completedLegend = document.getElementById('chartCompletedCount');
            if (completedLegend) {
                completedLegend.textContent = `Completed: ${stats.past}`;
            }
        }
        
        if (stats.upcoming !== undefined) {
            const upcomingLegend = document.getElementById('chartUpcomingCount');
            if (upcomingLegend) {
                upcomingLegend.textContent = `Upcoming: ${stats.upcoming}`;
            }
        }
    }
    
    // NEW: Force refresh charts immediately
    function refreshChartsImmediately() {
        // Update status chart if exists
        if (statusChartInstance) {
            const completedCount = parseInt(document.getElementById('completedCount')?.textContent) || 0;
            const upcomingCount = parseInt(document.getElementById('upcomingCount')?.textContent) || 0;
            
            if (statusChartInstance.data && statusChartInstance.data.datasets && statusChartInstance.data.datasets[0]) {
                statusChartInstance.data.datasets[0].data = [completedCount, upcomingCount];
                statusChartInstance.update('none'); // Update without animation for immediate effect
            }
        }
    }
    
    // Fallback function to update dashboard stats
    function updateDashboardStats() {
        // Update completed count
        const completedElement = document.getElementById('completedCount');
        if (completedElement) {
            const currentCount = parseInt(completedElement.textContent) || 0;
            completedElement.textContent = currentCount + 1;
        }
        
        // Update upcoming count
        const upcomingElement = document.getElementById('upcomingCount');
        if (upcomingElement) {
            const currentCount = parseInt(upcomingElement.textContent) || 0;
            if (currentCount > 0) {
                upcomingElement.textContent = currentCount - 1;
            }
        }
        
        // Update today count
        const todayElement = document.getElementById('todayCount');
        if (todayElement) {
            const currentCount = parseInt(todayElement.textContent) || 0;
            if (currentCount > 0) {
                todayElement.textContent = currentCount - 1;
            }
        }
        
        // Recalculate progress bars
        recalculateProgressBars();
        
        // Refresh charts
        refreshChartsImmediately();
    }
    
    // Recalculate progress bars based on current counts
    function recalculateProgressBars() {
        const total = parseInt(document.getElementById('totalCount')?.textContent) || 0;
        const upcoming = parseInt(document.getElementById('upcomingCount')?.textContent) || 0;
        const today = parseInt(document.getElementById('todayCount')?.textContent) || 0;
        const completed = parseInt(document.getElementById('completedCount')?.textContent) || 0;
        
        if (total > 0) {
            // Update upcoming progress
            const upcomingPercent = Math.min((upcoming / total) * 100, 100);
            const upcomingProgressBar = document.getElementById('upcomingProgress');
            if (upcomingProgressBar) {
                upcomingProgressBar.style.width = `${upcomingPercent}%`;
            }
            
            // Update today progress
            const todayPercent = Math.min((today / total) * 100, 100);
            const todayProgressBar = document.getElementById('todayProgress');
            if (todayProgressBar) {
                todayProgressBar.style.width = `${todayPercent}%`;
            }
            
            // Update completed progress
            const completedPercent = Math.min((completed / total) * 100, 100);
            const completedProgressBar = document.getElementById('completedProgress');
            if (completedProgressBar) {
                completedProgressBar.style.width = `${completedPercent}%`;
            }
        }
    }

    // Initialize Charts
    function initCharts() {
        // Status Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const completed = {{ $past ?? 0 }};
            const upcoming = {{ $upcoming ?? 0 }};
            
            // Only show chart if there's data
            if (completed > 0 || upcoming > 0) {
                statusChartInstance = new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'Upcoming'],
                        datasets: [{
                            data: [completed, upcoming],
                            backgroundColor: [
                                '#28a745',
                                'var(--primary-rose)'
                            ],
                            borderWidth: 3,
                            borderColor: '#fff',
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        cutout: '70%',
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                });
            } else {
                statusCtx.parentElement.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-pie fa-3x mb-3"></i>
                        <p>No data available for chart</p>
                    </div>
                `;
            }
        }

        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            const monthlyCompleted = {{ $monthlyCompleted ?? 0 }};
            const monthlyUpcoming = {{ $monthlyUpcoming ?? 0 }};
            const monthlyTotal = {{ $monthlyTotal ?? 0 }};
            
            if (monthlyCompleted > 0 || monthlyUpcoming > 0 || monthlyTotal > 0) {
                monthlyChartInstance = new Chart(monthlyCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Completed', 'Upcoming', 'Total'],
                        datasets: [{
                            label: 'Appointments',
                            data: [monthlyCompleted, monthlyUpcoming, monthlyTotal],
                            backgroundColor: [
                                '#28a745',
                                'var(--primary-rose)',
                                'var(--warm-gold)'
                            ],
                            borderWidth: 0,
                            borderRadius: 10,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    drawBorder: false,
                                    color: 'rgba(0,0,0,0.1)'
                                },
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else {
                monthlyCtx.parentElement.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <p>No monthly data available</p>
                    </div>
                `;
            }
        }
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
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        // Add to container
        const container = document.getElementById('toastContainer');
        if (!container) {
            const newContainer = document.createElement('div');
            newContainer.id = 'toastContainer';
            newContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(newContainer);
            newContainer.appendChild(toast);
        } else {
            container.appendChild(toast);
        }
        
        // Show toast
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
        bsToast.show();
        
        // Remove after hide
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Initialize everything
    initCalendar();
    initCharts();
    setupMarkDoneButtons();
});
</script>
@endsection