@extends('admin.layouts.admin')

@section('title', 'Location Management - GlamBook Admin')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold mb-2">
                <i class="fas fa-map-marker-alt me-2" style="color: var(--location-blue);"></i>
                Location Management
            </h1>
            <p class="text-muted mb-0">
                Track and manage appointment locations across all bookings
            </p>
        </div>
        <div>
            <button class="btn-admin me-2" onclick="exportLocations()">
                <i class="fas fa-download me-1"></i>Export CSV
            </button>
            <a href="{{ route('admin.locations.map') }}" class="btn-admin">
                <i class="fas fa-map me-1"></i>Map View
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="text-muted mb-2">Total with Location</h5>
                    <h2 class="mb-0">{{ $stats['totalWithLocation'] }}</h2>
                    <small class="text-success">
                        <i class="fas fa-chart-line me-1"></i>{{ $stats['locationUsageRate'] }}% usage rate
                    </small>
                </div>
                <i class="fas fa-map-marked-alt stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stat-card stat-card-location">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="text-muted mb-2">With Coordinates</h5>
                    <h2 class="mb-0">{{ $stats['totalWithCoordinates'] }}</h2>
                    <small class="text-primary">
                        <i class="fas fa-crosshairs me-1"></i>Mappable locations
                    </small>
                </div>
                <i class="fas fa-bullseye stat-icon"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="stat-card">
            <h5 class="text-muted mb-3">Top Cities</h5>
            <div class="row">
                @forelse($stats['topCities'] as $city)
                <div class="col-6 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="location-badge">
                            <i class="fas fa-city me-1"></i>{{ $city->city ?: 'Unknown' }}
                        </span>
                        <span class="fw-bold" style="color: var(--deep-rose);">{{ $city->count }}</span>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-muted text-center mb-0">No city data available</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Location Table -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table admin-table table-hover">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Location</th>
                        <th>City/State</th>
                        <th>Coordinates</th>
                        <th>Appointment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3">
                                    {{ strtoupper(substr($appointment->client_name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $appointment->client_name }}</div>
                                    <small class="text-muted">{{ $appointment->user->email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-artist">
                                {{ Str::limit($appointment->service_details, 30) }}
                            </span>
                        </td>
                        <td class="location-cell">
                            @if($appointment->address)
                                <div class="location-tooltip">
                                    <div class="location-badge">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ Str::limit($appointment->address, 25) }}
                                    </div>
                                    <div class="tooltip-text">
                                        <strong>{{ $appointment->address }}</strong>
                                        @if($appointment->location_notes)
                                        <br><small><i>{{ $appointment->location_notes }}</i></small>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">No address</span>
                            @endif
                        </td>
                        <td>
                            @if($appointment->city || $appointment->state)
                                <span class="d-block">
                                    <i class="fas fa-city me-1" style="color: var(--location-blue);"></i>
                                    {{ $appointment->city }}
                                </span>
                                <small class="text-muted">{{ $appointment->state }}</small>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($appointment->has_coordinates)
                                <button class="btn btn-sm btn-location" 
                                        onclick="showLocationDetails({{ $appointment->id }})">
                                    <i class="fas fa-crosshairs me-1"></i>{{ $appointment->latitude }}, {{ $appointment->longitude }}
                                </button>
                            @else
                                <span class="text-muted">No coordinates</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $appointment->appointment_at->format('M d, Y') }}</span>
                                <small class="text-muted">{{ $appointment->appointment_at->format('h:i A') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick="showLocationDetails({{ $appointment->id }})"
                                        title="View Location Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($appointment->has_coordinates)
                                <a href="{{ $appointment->google_maps_link }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-location"
                                   title="Open in Google Maps">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endif
                                <button class="btn btn-sm btn-outline-secondary copy-location"
        data-location="{{ $appointment->formatted_address }}"
        title="Copy Address"> </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                                <h5>No location data available</h5>
                                <p>Appointments with location information will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($appointments->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Location Statistics Section -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="stat-card">
            <h5 class="mb-3">
                <i class="fas fa-chart-pie me-2" style="color: var(--deep-rose);"></i>
                Location Completion
            </h5>
            <div id="locationCompletionChart" style="height: 200px;"></div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="stat-card">
            <h5 class="mb-3">
                <i class="fas fa-chart-bar me-2" style="color: var(--location-blue);"></i>
                Location Usage Trend
            </h5>
            <div id="locationTrendChart" style="height: 200px;"></div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Chart.js for statistics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Export locations functionality
    function exportLocations() {
        window.location.href = '{{ route("admin.locations.export") }}';
    }
    
    // Show location details
    function showLocationDetails(taskId) {
        fetch(`/admin/locations/${taskId}/details`)
            .then(response => response.json())
            .then(data => {
                // Create modal with location details
                const modal = new bootstrap.Modal(document.createElement('div'));
                const modalContent = `
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-map-marker-alt me-2" style="color: var(--location-blue);"></i>
                                    Location Details
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="location-display mb-3">
                                    ${data.address ? `
                                    <div class="location-line">
                                        <i class="fas fa-home"></i>
                                        <span class="location-address">${data.address}</span>
                                    </div>
                                    ` : ''}
                                    
                                    ${data.city || data.state ? `
                                    <div class="location-line">
                                        <i class="fas fa-city"></i>
                                        <span class="location-city">
                                            ${data.city || ''}${data.city && data.state ? ', ' : ''}${data.state || ''}
                                            ${data.zip_code ? ` ${data.zip_code}` : ''}
                                        </span>
                                    </div>
                                    ` : ''}
                                    
                                    ${data.country ? `
                                    <div class="location-line">
                                        <i class="fas fa-globe"></i>
                                        <span>${data.country}</span>
                                    </div>
                                    ` : ''}
                                    
                                    ${data.location_notes ? `
                                    <div class="location-notes mt-2">
                                        <i class="fas fa-sticky-note me-1"></i>${data.location_notes}
                                    </div>
                                    ` : ''}
                                    
                                    ${data.latitude && data.longitude ? `
                                    <div class="location-line mt-2">
                                        <i class="fas fa-map-pin"></i>
                                        <small class="text-muted">Coordinates: ${data.latitude}, ${data.longitude}</small>
                                    </div>
                                    ` : ''}
                                </div>
                                
                                ${data.has_coordinates && data.google_maps_link ? `
                                <div class="text-center">
                                    <a href="${data.google_maps_link}" 
                                       target="_blank" 
                                       class="btn btn-admin">
                                        <i class="fas fa-external-link-alt me-1"></i>
                                        Open in Google Maps
                                    </a>
                                </div>
                                ` : ''}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-admin copy-location" 
                                        data-location="${data.formatted_address}">
                                    <i class="fas fa-copy me-1"></i>Copy Address
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                const modalElement = document.createElement('div');
                modalElement.className = 'modal fade';
                modalElement.innerHTML = modalContent;
                document.body.appendChild(modalElement);
                
                modal._element = modalElement;
                modal.show();
                
                // Remove modal from DOM after hiding
                modalElement.addEventListener('hidden.bs.modal', function () {
                    document.body.removeChild(modalElement);
                });
            })
            .catch(error => {
                console.error('Error fetching location details:', error);
                alert('Error loading location details. Please try again.');
            });
    }
    
    // Load location statistics
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch location statistics
        fetch('{{ route("admin.locations.stats") }}')
            .then(response => response.json())
            .then(stats => {
                // Create completion chart
                const completionCtx = document.getElementById('locationCompletionChart').getContext('2d');
                new Chart(completionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Complete Address', 'Partial Address', 'No Address'],
                        datasets: [{
                            data: [
                                stats.withFullAddress,
                                stats.withLocation - stats.withFullAddress,
                                stats.totalAppointments - stats.withLocation
                            ],
                            backgroundColor: [
                                'rgba(74, 111, 165, 0.8)',
                                'rgba(232, 180, 184, 0.8)',
                                'rgba(212, 212, 212, 0.8)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
                
                // Create trend chart if data exists
                if (stats.monthlyTrend && stats.monthlyTrend.length > 0) {
                    const trendCtx = document.getElementById('locationTrendChart').getContext('2d');
                    const months = stats.monthlyTrend.map(item => {
                        // Format month as "Jan 2024"
                        const [year, month] = item.month.split('-');
                        const date = new Date(year, month - 1);
                        return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                    });
                    const totals = stats.monthlyTrend.map(item => item.total);
                    const withLocations = stats.monthlyTrend.map(item => item.with_location);
                    
                    new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [
                                {
                                    label: 'Total Appointments',
                                    data: totals,
                                    borderColor: 'rgba(232, 180, 184, 1)',
                                    backgroundColor: 'rgba(232, 180, 184, 0.1)',
                                    tension: 0.4,
                                    borderWidth: 2
                                },
                                {
                                    label: 'With Location',
                                    data: withLocations,
                                    borderColor: 'rgba(74, 111, 165, 1)',
                                    backgroundColor: 'rgba(74, 111, 165, 0.1)',
                                    tension: 0.4,
                                    borderWidth: 2
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            }
                        }
                    });
                }
            });
            
        // Copy location to clipboard functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.copy-location')) {
                const button = e.target.closest('.copy-location');
                const locationText = button.getAttribute('data-location');
                
                if (locationText && locationText !== '') {
                    navigator.clipboard.writeText(locationText).then(() => {
                        const originalHTML = button.innerHTML;
                        const originalClass = button.className;
                        
                        button.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
                        button.className = 'btn btn-sm btn-success';
                        
                        setTimeout(() => {
                            button.innerHTML = originalHTML;
                            button.className = originalClass;
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy: ', err);
                        alert('Failed to copy address to clipboard');
                    });
                }
            }
        });
    });
</script>
@endsection