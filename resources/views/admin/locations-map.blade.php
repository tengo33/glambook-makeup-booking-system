@extends('admin.layouts.admin')

@section('title', 'Location Map - GlamBook Admin')

@section('content')
<div class="admin-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold mb-2">
                <i class="fas fa-map me-2" style="color: var(--location-blue);"></i>
                Location Map View
            </h1>
            <p class="text-muted mb-0">
                Visualize appointment locations on an interactive map
            </p>
        </div>
        <div>
            <a href="{{ route('admin.locations') }}" class="btn-admin">
                <i class="fas fa-list me-1"></i>Back to List
            </a>
        </div>
    </div>
</div>

<!-- Map Container -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-0">
        <div id="map" style="height: 600px; border-radius: 20px; overflow: hidden;"></div>
    </div>
</div>

<!-- Legend -->
<div class="row">
    <div class="col-md-8">
        <div class="stat-card">
            <h5 class="mb-3">Map Legend</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div style="width: 20px; height: 20px; background: #28a745; border-radius: 50%; margin-right: 10px;"></div>
                        <span>Completed Appointments</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div style="width: 20px; height: 20px; background: #ffc107; border-radius: 50%; margin-right: 10px;"></div>
                        <span>Pending Appointments</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="d-flex align-items-center">
                        <div style="width: 20px; height: 20px; background: var(--location-blue); border-radius: 50%; margin-right: 10px;"></div>
                        <span>Has Coordinates</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <h5 class="mb-3">Quick Stats</h5>
            <div class="mb-2">
                <span class="text-muted">Total Locations:</span>
                <span class="fw-bold float-end">{{ count($locations) }}</span>
            </div>
            <div class="mb-2">
                <span class="text-muted">Unique Cities:</span>
                @php
                    $uniqueCities = $locations->pluck('city')->unique()->filter()->count();
                @endphp
                <span class="fw-bold float-end">{{ $uniqueCities }}</span>
            </div>
            <div>
                <span class="text-muted">Map Coverage:</span>
                <span class="fw-bold float-end">{{ $locations->count() > 0 ? '100%' : '0%' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Location Details Table -->
@if($locations->count() > 0)
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5 class="mb-3">
            <i class="fas fa-list me-2" style="color: var(--deep-rose);"></i>
            Mapped Locations
        </h5>
        <div class="table-responsive">
            <table class="table admin-table table-hover">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Address</th>
                        <th>City/State</th>
                        <th>Coordinates</th>
                        <th>Appointment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $location['client_name'] }}</div>
                            <small class="text-muted">{{ Str::limit($location['service_details'], 20) }}</small>
                        </td>
                        <td class="location-cell">
                            <div class="location-tooltip">
                                <div class="location-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ Str::limit($location['address'], 20) }}
                                </div>
                                <div class="tooltip-text">
                                    <strong>{{ $location['address'] }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $location['city'] }}, {{ $location['state'] }}
                        </td>
                        <td>
                            <span class="badge-location">
                                <i class="fas fa-crosshairs me-1"></i>
                                {{ $location['latitude'] }}, {{ $location['longitude'] }}
                            </span>
                        </td>
                        <td>
                            {{ $location['appointment_at'] }}
                        </td>
                        <td>
                            <a href="{{ $location['google_maps_link'] }}" 
                               target="_blank" 
                               class="btn btn-sm btn-location"
                               title="Open in Google Maps">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<!-- Leaflet.js for maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map
        const map = L.map('map').setView([39.8283, -98.5795], 4); // Center of US
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Add markers for each location
        const locations = @json($locations);
        
        if (locations.length === 0) {
            // Show message if no locations
            const noDataDiv = document.createElement('div');
            noDataDiv.className = 'text-center py-5';
            noDataDiv.innerHTML = `
                <i class="fas fa-map-marked-alt fa-3x mb-3" style="color: var(--location-blue);"></i>
                <h5>No mappable locations found</h5>
                <p class="text-muted">Appointments with coordinates will appear on the map.</p>
            `;
            document.getElementById('map').appendChild(noDataDiv);
            return;
        }
        
        const markers = [];
        locations.forEach(location => {
            if (location.latitude && location.longitude) {
                const marker = L.marker([location.latitude, location.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <div style="min-width: 200px;">
                            <h6 style="color: var(--deep-rose); margin-bottom: 5px;">
                                <i class="fas fa-user me-1"></i>${location.client_name}
                            </h6>
                            <p style="margin-bottom: 5px;">
                                <strong>${location.address}</strong><br>
                                ${location.city}, ${location.state}
                            </p>
                            <p style="margin-bottom: 5px; font-size: 0.9em;">
                                <i class="fas fa-calendar me-1"></i>${location.appointment_at}<br>
                                <i class="fas fa-spa me-1"></i>${location.service_details}
                            </p>
                            <div class="text-center">
                                <a href="${location.google_maps_link}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-location">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Directions
                                </a>
                            </div>
                        </div>
                    `);
                
                // Customize marker color based on appointment status
                marker.setIcon(L.divIcon({
                    html: `<div style="
                        background: ${location.color};
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        border: 3px solid white;
                        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 12px;
                    ">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>`,
                    className: 'custom-marker',
                    iconSize: [24, 24],
                    iconAnchor: [12, 24]
                }));
                
                markers.push(marker);
            }
        });
        
        // Fit map bounds to show all markers
        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    });
</script>

<style>
    .custom-marker {
        background: none !important;
        border: none !important;
    }
    
    .leaflet-popup-content {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
    }
    
    .leaflet-popup-content-wrapper {
        border-radius: 12px !important;
        box-shadow: var(--shadow-medium) !important;
        border: 1px solid rgba(232, 180, 184, 0.2) !important;
    }
    
    .leaflet-popup-content h6 {
        color: var(--deep-rose) !important;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .leaflet-popup-content p {
        margin-bottom: 8px;
        line-height: 1.4;
    }
    
    .leaflet-popup-content .btn-location {
        padding: 4px 10px;
        font-size: 12px;
    }
</style>
@endsection