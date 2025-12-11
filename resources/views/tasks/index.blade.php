@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success fade-in alert-dismissible fade show" role="alert" style="background: linear-gradient(135deg, #ffb6c1 0%, #ff8fa3 100%); border: none; color: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(255, 182, 193, 0.4);">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Welcome Section -->
    <div class="card p-4 mb-4 border-0 welcome-card">
        <div class="row align-items-center">   
            <div class="col-md-8">
                <div class="welcome-highlight">
                    <h2 class="fw-bold mb-3 welcome-title">
                        <span>W</span><span>e</span><span>l</span><span>c</span><span>o</span><span>m</span><span>e</span> 
                        <span>t</span><span>o</span> 
                        <span>Y</span><span>o</span><span>u</span><span>r</span> 
                        <span>M</span><span>a</span><span>k</span><span>e</span><span>u</span><span>p</span><br>
                        <span>A</span><span>p</span><span>p</span><span>o</span><span>i</span><span>n</span><span>t</span><span>m</span><span>e</span><span>n</span><span>t</span> 
                        <span>P</span><span>l</span><span>a</span><span>n</span><span>n</span><span>e</span><span>r</span>
                        <div class="glow"></div>
                        <div class="corner corner-tl"></div>
                        <div class="corner corner-tr"></div>
                    </h2>
                    <p class="fs-5 mb-0 welcome-subtitle">Keep track of your daily beauty appointments in style!</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="profile-image-wrapper">
                    <img src="{{ asset('images/bae.jpg') }}" alt="Bae" class="profile-image">
                </div>
            </div>
        </div>
    </div>

    <!-- Top Section - Heading and Add Appointment Button -->
    <div class="d-flex justify-content-between align-items-center mb-4 section-header">
        <h2 class="fw-bold section-title">Upcoming Appointments</h2>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary-add shadow-lg">
            <i class="fas fa-plus me-2"></i>Add Appointment
        </a>
    </div>

    <!-- Appointment Cards -->
    <div class="row" id="appointmentsContainer">
        @forelse ($tasks as $task)
            <div class="col-md-6 col-lg-4 mb-4 fade-in">
                <div class="appointment-card-wrapper {{ $task->is_done ? 'completed-glow' : 'pending-glow' }}">
                    <div class="card border-0 h-100 appointment-card">
                        <div class="card-body">
                            <!-- Client Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold mb-0 client-name">
                                    <i class="fas fa-user-circle me-2"></i>{{ $task->client_name }}
                                </h5>
                                <span class="status-badge {{ $task->is_done ? 'status-done' : 'status-pending' }}">
                                    @if($task->is_done)
                                        <i class="fas fa-check-circle me-1"></i>Completed
                                    @else
                                        <i class="fas fa-clock me-1"></i>Pending
                                    @endif
                                </span>
                            </div>

                            <!-- Service Details -->
                            <div class="mb-3 service-details">
                                <div class="service-icon-text">
                                    <i class="fas fa-spa me-2"></i>
                                    <span>{{ $task->service_details ?? 'No service details provided' }}</span>
                                </div>
                            </div>
                            
                            <!-- Location Display -->
                            @if($task->address || $task->city)
                            <div class="mb-3 location-display">
                                <div class="location-icon-text">
                                    <i class="fas fa-map-marker-alt me-2" style="color: #ff8fa3;"></i>
                                    <div>
                                        <small class="d-block text-muted mb-1" style="font-size: 0.75rem;">
                                            <i class="fas fa-location-dot me-1"></i>Appointment Location
                                        </small>
                                        @if($task->address)
                                            <div class="mb-1" style="color: var(--text-dark); font-size: 0.9rem;">
                                                <strong>{{ $task->address }}</strong>
                                            </div>
                                        @endif
                                        @if($task->city || $task->state)
                                            <div style="color: var(--text-muted); font-size: 0.85rem;">
                                                {{ $task->city }}{{ $task->city && $task->state ? ', ' : '' }}{{ $task->state }}
                                                @if($task->zip_code)
                                                    {{ $task->zip_code }}
                                                @endif
                                                @if($task->country)
                                                    • {{ $task->country }}
                                                @endif
                                            </div>
                                        @endif
                                        @if($task->location_notes)
                                            <div class="mt-1" style="color: #666; font-size: 0.8rem; font-style: italic;">
                                                <i class="fas fa-sticky-note me-1"></i>{{ Str::limit($task->location_notes, 60) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if($task->has_coordinates && $task->google_maps_link)
                                    <div class="mt-2">
                                        <a href="{{ $task->google_maps_link }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary"
                                           style="border-color: #ff8fa3; color: #ff8fa3; font-size: 0.75rem; padding: 4px 10px;">
                                            <i class="fas fa-external-link-alt me-1"></i>Open in Maps
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @endif
                            
                            <!-- ML Predictions & Time Buffer Display -->
                            @if($task->predicted_duration || $task->predicted_no_show_score !== null || $task->time_buffer)
                            <div class="mb-3 ml-predictions" style="background: linear-gradient(135deg, #fff5f7, #fffafb); padding: 12px; border-radius: 8px;">
                                <!-- Time Predictions Section -->
                                @if($task->predicted_duration || $task->time_buffer)
                                <div class="mb-3 pb-3 border-bottom" style="border-color: rgba(255, 143, 163, 0.2) !important;">
                                    <!-- Predicted Duration -->
                                    @if($task->predicted_duration)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small style="color: #666;">
                                            <i class="fas fa-hourglass-half me-1" style="color: #ff8fa3;"></i>Service Time
                                        </small>
                                        <span class="badge" style="background: #ff8fa3; color: white;">{{ $task->predicted_duration }} min</span>
                                    </div>
                                    @endif
                                    
                                    <!-- Buffer Time -->
                                    @if($task->time_buffer)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small style="color: #666;">
                                            <i class="fas fa-clock me-1" style="color: #28a745;"></i>Prep & Travel Buffer
                                        </small>
                                        <span class="badge" style="background: #28a745; color: white;">{{ $task->time_buffer }} min</span>
                                    </div>
                                    @endif
                                    
                                    <!-- Total Time (Duration + Buffer) -->
                                    @if($task->predicted_duration && $task->time_buffer)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small style="color: #666;">
                                            <i class="fas fa-calendar-check me-1" style="color: #9c27b0;"></i>Total Time Block
                                        </small>
                                        @php
                                            $totalMinutes = $task->predicted_duration + $task->time_buffer;
                                            $hours = floor($totalMinutes / 60);
                                            $minutes = $totalMinutes % 60;
                                            $displayTime = ($hours > 0 ? $hours . 'h ' : '') . $minutes . 'm';
                                        @endphp
                                        <span class="badge" style="background: #9c27b0; color: white;">{{ $displayTime }}</span>
                                    </div>
                                    @endif
                                    
                                    <!-- End Time Display -->
                                    @if($task->appointment_end_time)
                                    <div class="mt-2 pt-2 border-top" style="border-color: rgba(255, 143, 163, 0.1) !important;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small style="color: #666;">
                                                <i class="fas fa-flag-checkered me-1" style="color: #ff6b6b;"></i>Expected Finish
                                            </small>
                                            <small style="color: #ff6b6b; font-weight: 600;">
                                                {{ \Carbon\Carbon::parse($task->appointment_end_time)->format('h:i A') }}
                                            </small>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif
                                
                                <!-- Show-Up Likelihood -->
                                @if($task->predicted_no_show_score !== null)
                                <div class="d-flex justify-content-between align-items-center">
                                    <small style="color: #666;">
                                        <i class="fas fa-check-circle me-1" style="color: #ff8fa3;"></i>Show-Up Likelihood
                                    </small>
                                    @php
                                        $showUpPercentage = round((1 - $task->predicted_no_show_score) * 100);
                                        $riskColor = $showUpPercentage >= 80 
                                            ? '#28a745' 
                                            : ($showUpPercentage >= 60 ? '#ffc107' : '#dc3545');
                                    @endphp
                                    <span class="badge" style="background: {{ $riskColor }}; color: white;">{{ $showUpPercentage }}%</span>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Appointment Date & Time -->
                            <div class="mb-3 appointment-time">
                                <div class="time-item">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <strong>{{ \Carbon\Carbon::parse($task->appointment_at)->format('F d, Y') }}</strong>
                                </div>
                                <div class="time-item">
                                    <i class="fas fa-clock me-2"></i>
                                    {{ \Carbon\Carbon::parse($task->appointment_at)->format('h:i A') }}
                                </div>
                                @if($task->appointment_end_time)
                                <div class="time-item" style="background: rgba(255, 107, 107, 0.1);">
                                    <i class="fas fa-hourglass-end me-2"></i>
                                    <small>Ends at: {{ \Carbon\Carbon::parse($task->appointment_end_time)->format('h:i A') }}</small>
                                </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top action-section">
                                <!-- Mark Done/Not Done -->
                                <div>
                                    @if (!$task->is_done)
                                        <form action="{{ route('tasks.mark-done', $task) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success-mark">
                                                <i class="fas fa-check me-1"></i>Mark Done
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('tasks.mark-not-done', $task) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-warning-undo">
                                                <i class="fas fa-undo me-1"></i>Undo
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- View, Edit and Delete -->
                                <div class="d-flex gap-2">
                                    <!-- View Button - Redirects to show page -->
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-view-action">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-edit-action">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete-action"
                                            onclick="return confirm('Are you sure you want to delete this appointment?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state-card text-center py-5">
                    <i class="fas fa-calendar-times empty-icon"></i>
                    <h4 class="empty-title">No appointments yet 💅</h4>
                    <p class="empty-text">Start by adding your first appointment!</p>
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary-add mt-2">
                        <i class="fas fa-plus me-2"></i>Add Your First Appointment
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Bottom Section - Additional Appointments Summary -->
    <div class="d-flex justify-content-between align-items-center mb-4 section-header mt-5 pt-4" style="border-top: 2px solid rgba(255, 182, 193, 0.3);">
        <div>
            <h2 class="fw-bold section-title mb-2">Appointments Overview</h2>
            <p class="text-muted mb-0">
                <a href="{{ route('tasks.index') }}" class="badge bg-light text-dark me-2 {{ is_null($filter) ? 'active' : '' }}">
                    <i class="fas fa-calendar-check me-1"></i>Total: {{ $total }}
                </a>
                <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="badge bg-success-light text-success me-2 {{ $filter === 'completed' ? 'active' : '' }}">
                    <i class="fas fa-check-circle me-1"></i>Completed: {{ $completedCount }}
                </a>
                <a href="{{ route('tasks.index', ['filter' => 'upcoming']) }}" class="badge bg-warning-light text-warning {{ $filter === 'upcoming' ? 'active' : '' }}">
                    <i class="fas fa-clock me-1"></i>Pending: {{ $upcomingCount }}
                </a>
            </p>
        </div>

        <a href="{{ route('tasks.create') }}" class="btn btn-primary-add shadow-lg">
            <i class="fas fa-plus me-2"></i>Add Another Appointment
        </a>
    </div>

    <style>
        /* CSS Variables for consistent theming */
        :root {
            --pink-light: #ffb6c1;
            --pink-medium: #ff8fa3;
            --pink-dark: #d63384;
            --pink-soft: #fff0f5;
            --pink-softer: #fffafb;
            --success-green: #28a745;
            --warning-orange: #ffc107;
            --edit-blue: #2196f3;
            --view-purple: #9c27b0;
            --delete-red: #f44336;
            --text-dark: #2d3748;
            --text-muted: #718096;
        }

        /* View Button Styling */
        .btn-view-action {
            background: linear-gradient(135deg, var(--view-purple) 0%, #7b1fa2 100%);
            color: white;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.875rem;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 
                0 3px 8px rgba(0, 0, 0, 0.15),
                0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-view-action:hover {
            transform: translateY(-1px) scale(1.05);
            box-shadow: 
                0 5px 15px rgba(156, 39, 176, 0.5),
                0 2px 0 rgba(255, 255, 255, 0.6);
            color: white;
            text-decoration: none;
        }

        /* Welcome Section Styling */
        .welcome-card {
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.98) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 245, 248, 0.95) 0%, transparent 50%),
                linear-gradient(135deg, 
                    rgba(255, 252, 252, 0.99) 0%, 
                    rgba(255, 248, 250, 0.97) 30%, 
                    rgba(255, 245, 248, 0.95) 70%,
                    rgba(255, 242, 246, 0.93) 100%);
            border: 2px solid rgba(255, 182, 193, 0.3);
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 15px 35px rgba(255, 182, 193, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.9),
                0 1px 3px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
            animation: cardElevate 8s ease-in-out infinite;
        }

        @keyframes cardElevate {
            0%, 100% { 
                transform: translateY(0px);
                box-shadow: 0 15px 35px rgba(255, 182, 193, 0.15);
            }
            50% { 
                transform: translateY(-5px);
                box-shadow: 0 20px 45px rgba(255, 182, 193, 0.25);
            }
        }

        .welcome-title {
            color: #ff8fa3;
            font-size: 2.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            line-height: 1.1;
            position: relative;
            display: inline-block;
            animation: titleFadeIn 1.5s ease-out;
            text-shadow: 
                0 2px 4px rgba(255, 143, 163, 0.2),
                0 4px 8px rgba(255, 143, 163, 0.1),
                0 1px 0 rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
            padding: 0 0 20px 0;
            text-align: left;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .welcome-title::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, 
                #ffb6c1, #ff8fa3, #ff6b9d, #ff8fa3, #ffb6c1);
            background-size: 200% 100%;
            border-radius: 2px;
            animation: lineFlow 3s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        @keyframes lineFlow {
            0%, 100% { 
                background-position: 0% 50%;
                opacity: 0.8;
            }
            50% { 
                background-position: 100% 50%;
                opacity: 1;
            }
        }

        .welcome-subtitle {
            color: #ff6b9d;
            font-size: 1.2rem;
            font-weight: 500;
            line-height: 1.5;
            position: relative;
            padding: 1rem 2rem;
            background: rgba(255, 182, 193, 0.08);
            border: 1px solid rgba(255, 182, 193, 0.15);
            border-radius: 12px;
            text-shadow: 
                0 1px 2px rgba(255, 107, 157, 0.15),
                0 1px 0 rgba(255, 255, 255, 0.9);
            animation: subtitleFadeIn 2s ease-out 0.3s both;
            backdrop-filter: blur(5px);
            box-shadow: 
                inset 0 1px 2px rgba(255, 255, 255, 0.8),
                0 2px 8px rgba(255, 182, 193, 0.1);
        }

        .profile-image-wrapper {
            display: inline-block;
            padding: 3px;
            background: linear-gradient(135deg, #ffb6c1 0%, #ff8fa3 50%, #ffb6c1 100%);
            border-radius: 50%;
            box-shadow: 
                0 4px 15px rgba(255, 182, 193, 0.3),
                0 0 0 2px rgba(255, 255, 255, 0.9);
            animation: profileGlow 4s ease-in-out infinite;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }

        /* Section Header */
        .section-header {
            border-bottom: 2px solid var(--pink-light);
            padding-bottom: 1rem;
            box-shadow: 0 2px 0 rgba(255, 182, 193, 0.1);
        }

        .section-title {
            color: #ff8fa3;
            font-size: 2rem;
            font-weight: 600;
            position: relative;
            display: inline-block;
            padding-bottom: 12px;
            margin-bottom: 2rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, 
                #ffb6c1, 
                #ff8fa3, 
                #ff6b9d, 
                #ff8fa3, 
                #ffb6c1);
            border-radius: 1px;
            animation: linePulse 3s ease-in-out infinite;
        }

        /* Card Wrapper with Creative Glowing Borders */
        .appointment-card-wrapper {
            position: relative;
            padding: 3px;
            border-radius: 16px;
            background: linear-gradient(135deg, 
                rgba(255, 182, 193, 0.8) 0%, 
                rgba(255, 182, 193, 0.4) 50%, 
                rgba(255, 182, 193, 0.8) 100%);
            box-shadow: 
                0 4px 20px rgba(255, 182, 193, 0.3),
                inset 0 1px 1px rgba(255, 255, 255, 0.6);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .appointment-card-wrapper:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 
                0 12px 35px rgba(255, 182, 193, 0.5),
                0 0 0 1px rgba(255, 182, 193, 0.3),
                inset 0 1px 1px rgba(255, 255, 255, 0.8);
        }

        .appointment-card {
            border-radius: 14px !important;
            background: linear-gradient(135deg, #ffffff 0%, var(--pink-softer) 100%);
            border: 1px solid rgba(255, 182, 193, 0.3);
            box-shadow: 
                inset 0 2px 4px rgba(255, 182, 193, 0.1),
                0 1px 0 rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
        }

        /* Client Name Styling */
        .client-name {
            color: var(--pink-medium);
            text-shadow: 0 1px 2px rgba(255, 143, 163, 0.3);
            border-bottom: 1px dashed var(--pink-light);
            padding-bottom: 8px;
            font-size: 1.2rem;
        }

        /* Status Badges */
        .status-badge {
            font-size: 0.75rem;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid;
            box-shadow: 
                0 2px 8px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .status-done {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            color: var(--success-green);
            border-color: rgba(40, 167, 69, 0.3);
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            color: #f57c00;
            border-color: rgba(255, 193, 7, 0.3);
        }

        /* Service Details */
        .service-details {
            color: var(--text-muted);
        }

        .service-icon-text {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: rgba(255, 182, 193, 0.1);
            border-radius: 8px;
            border-left: 3px solid var(--pink-light);
            box-shadow: inset 0 1px 2px rgba(255, 182, 193, 0.1);
        }

        /* Location Display */
        .location-display {
            background: linear-gradient(135deg, rgba(255, 182, 193, 0.05), rgba(255, 182, 193, 0.1));
            border-radius: 8px;
            padding: 10px;
            border-left: 3px solid #ff8fa3;
            margin-bottom: 15px;
        }

        /* Appointment Time */
        .appointment-time {
            color: var(--text-dark);
        }

        .time-item {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
            padding: 6px 10px;
            background: rgba(255, 182, 193, 0.05);
            border-radius: 6px;
            border: 1px solid rgba(255, 182, 193, 0.2);
        }

        /* Action Section */
        .action-section {
            border-color: var(--pink-light) !important;
            border-top-style: dashed !important;
            box-shadow: 0 -1px 0 rgba(255, 182, 193, 0.2);
        }

        /* Primary Add Button */
        .btn-primary-add {
            background: linear-gradient(135deg, var(--pink-medium) 0%, var(--pink-dark) 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 
                0 4px 15px rgba(255, 143, 163, 0.4),
                0 2px 0 rgba(255, 182, 193, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary-add:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 8px 25px rgba(255, 143, 163, 0.6),
                0 4px 0 rgba(255, 182, 193, 0.4);
        }

        /* Action Buttons */
        .btn-success-mark {
            background: linear-gradient(135deg, var(--success-green) 0%, #1e7e34 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 
                0 3px 10px rgba(40, 167, 69, 0.4),
                0 1px 0 rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .btn-success-mark:hover {
            transform: translateY(-1px);
            box-shadow: 
                0 5px 15px rgba(40, 167, 69, 0.6),
                0 2px 0 rgba(255, 255, 255, 0.3);
        }

        .btn-warning-undo {
            background: transparent;
            color: var(--warning-orange);
            border: 2px solid var(--warning-orange);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 
                0 3px 10px rgba(255, 193, 7, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .btn-warning-undo:hover {
            background: var(--warning-orange);
            color: white;
            transform: translateY(-1px);
            box-shadow: 
                0 5px 15px rgba(255, 193, 7, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .btn-edit-action, .btn-delete-action {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.875rem;
            border: none;
            box-shadow: 
                0 3px 8px rgba(0, 0, 0, 0.15),
                0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .btn-edit-action {
            background: linear-gradient(135deg, var(--edit-blue) 0%, #1976d2 100%);
            color: white;
        }

        .btn-edit-action:hover {
            transform: translateY(-1px) rotate(5deg);
            box-shadow: 
                0 5px 15px rgba(33, 150, 243, 0.5),
                0 2px 0 rgba(255, 255, 255, 0.6);
        }

        .btn-delete-action {
            background: linear-gradient(135deg, var(--delete-red) 0%, #d32f2f 100%);
            color: white;
        }

        .btn-delete-action:hover {
            transform: translateY(-1px) scale(1.1);
            box-shadow: 
                0 5px 15px rgba(244, 67, 54, 0.5),
                0 2px 0 rgba(255, 255, 255, 0.6);
        }

        /* Empty State */
        .empty-state-card {
            background: linear-gradient(135deg, var(--pink-softer) 0%, var(--pink-soft) 100%);
            border: 2px dashed var(--pink-light);
            border-radius: 20px;
            box-shadow: 
                0 8px 25px rgba(255, 182, 193, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--pink-medium);
            margin-bottom: 1rem;
        }

        .empty-title {
            color: var(--pink-medium);
            font-size: 1.5rem;
        }

        /* Animations */
        .fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(20px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-title {
                font-size: 1.8rem;
                text-align: center;
            }
            
            .welcome-subtitle {
                font-size: 1.1rem;
                padding: 0.8rem 1.5rem;
                text-align: center;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection