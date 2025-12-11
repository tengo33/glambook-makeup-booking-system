@extends('layouts.app')
@section('content')
<div class="card p-4 fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0" style="color: #d8a1a6;">Book New Appointment</h3>
        <div class="badge" style="background: linear-gradient(135deg, #e8b4b8, #d8a1a6);">Makeup by Angel Lou</div>
    </div>
    
    <form action="{{ route('tasks.store') }}" method="POST" id="appointment-form">
        @csrf
        
        <!-- Client Information - Updated with individual name fields -->
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold" style="color: #d8a1a6;">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name" class="form-control form-control-lg" placeholder="First name" required value="{{ old('first_name') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold" style="color: #d8a1a6;">Middle Name</label>
                <input type="text" name="middle_name" class="form-control form-control-lg" placeholder="Middle name" value="{{ old('middle_name') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold" style="color: #d8a1a6;">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name" class="form-control form-control-lg" placeholder="Last name" required value="{{ old('last_name') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-semibold" style="color: #d8a1a6;">Suffix</label>
                <select name="suffix" class="form-control form-control-lg">
                    <option value="">None</option>
                    <option value="Jr" {{ old('suffix') == 'Jr' ? 'selected' : '' }}>Jr</option>
                    <option value="Sr" {{ old('suffix') == 'Sr' ? 'selected' : '' }}>Sr</option>
                    <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                    <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                    <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>IV</option>
                </select>
            </div>
        </div>

        <!-- Client Name (Full) for display -->
        <div class="mb-3">
            <label class="form-label fw-semibold" style="color: #d8a1a6;">Client Name</label>
            <input type="text" name="client_name" class="form-control form-control-lg bg-light" id="client_name_display" readonly placeholder="Full name will appear here">
        </div>

        <!-- Phone Number with +63 format -->
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label fw-semibold" style="color: #d8a1a6;">Phone Number <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
                <span class="input-group-text" style="background: #e8b4b8; color: white;">+63</span>
                <input type="tel" class="form-control" id="phone" name="phone"
                       placeholder="9123456789"
                       pattern="[0-9]{10}"
                       title="Please enter 10-digit phone number (9123456789)"
                       required
                       value="{{ old('phone') }}">
            </div>
            <div class="form-text text-muted">Format: +63 9123456789 (Must start with 9, 10 digits total)</div>
            <div class="invalid-feedback" id="phone-error" style="display: none;">Please enter a valid Philippine phone number (+63 followed by 10 digits)</div>
        </div>

        <!-- Service Selection -->
        <div class="mb-4">
            <label class="form-label fw-semibold" style="color: #d8a1a6;">Service Category <span class="text-danger">*</span></label>
            <div class="input-group input-group-lg">
                <input type="text" id="service-display" name="service_type" class="form-control" placeholder="Select service category" readonly required value="{{ old('service_type') }}">
                <button type="button" class="btn" style="background: #e8b4b8; border-color: #d8a1a6; color: white;" id="service-dropdown-toggle">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <!-- Service Category Options -->
            <div class="service-options mt-2" id="service-options" style="display: none;">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #e8b4b8, #d8a1a6);">
                        <h6 class="mb-0"><i class="fas fa-spa me-2"></i>Select Service Category</h6>
                    </div>
                    <div class="card-body p-3">
                        <!-- Bridal Services -->
                        <div class="service-category mb-3">
                            <h6 class="mb-2" style="color: #e8b4b8;"><i class="fas fa-ring me-2"></i>Bridal Services</h6>
                            <div class="service-option" data-value="Full Bridal Package" data-category="bridal">
                                <div>
                                    <div class="fw-semibold">Full Bridal Package</div>
                                    <small class="text-muted">Complete bridal transformation</small>
                                </div>
                                <span class="badge" style="background: #f4e4b6; color: #8b7355;">Premium</span>
                            </div>
                            <div class="service-option" data-value="Civil Wedding Package" data-category="bridal">
                                <div>
                                    <div class="fw-semibold">Civil Wedding Package</div>
                                    <small class="text-muted">Simple & elegant for civil ceremonies</small>
                                </div>
                                <span class="badge" style="background: #e8b4b8; color: white;">Popular</span>
                            </div>
                        </div>
                        <!-- Traditional Makeup -->
                        <div class="service-category mb-3">
                            <h6 class="mb-2" style="color: #e8b4b8;"><i class="fas fa-palette me-2"></i>Traditional Makeup</h6>
                            <div class="service-option" data-value="Traditional Makeup Only" data-category="traditional">
                                <div>
                                    <div class="fw-semibold">Traditional Makeup Only</div>
                                    <small class="text-muted">Classic makeup application</small>
                                </div>
                            </div>
                            <div class="service-option" data-value="Traditional Makeup + Hair" data-category="traditional">
                                <div>
                                    <div class="fw-semibold">Traditional Makeup + Hair</div>
                                    <small class="text-muted">Complete traditional look</small>
                                </div>
                            </div>
                        </div>
                        <!-- Special Events -->
                        <div class="service-category mb-3">
                            <h6 class="mb-2" style="color: #e8b4b8;"><i class="fas fa-star me-2"></i>Special Events</h6>
                            <div class="service-option" data-value="Debut Makeup" data-category="events">
                                <div>
                                    <div class="fw-semibold">Debut Makeup</div>
                                    <small class="text-muted">Perfect for 18th birthday celebrations</small>
                                </div>
                            </div>
                            <div class="service-option" data-value="Graduation Makeup" data-category="events">
                                <div>
                                    <div class="fw-semibold">Graduation Makeup</div>
                                    <small class="text-muted">Elegant look for graduation photos</small>
                                </div>
                            </div>
                            <div class="service-option" data-value="Formal Event Makeup" data-category="events">
                                <div>
                                    <div class="fw-semibold">Formal Event Makeup</div>
                                    <small class="text-muted">Glamorous look for parties & events</small>
                                </div>
                            </div>
                        </div>
                        <!-- Hair Services -->
                        <div class="service-category">
                            <h6 class="mb-2" style="color: #e8b4b8;"><i class="fas fa-cut me-2"></i>Hair Services</h6>
                            <div class="service-option" data-value="Hair Styling Only" data-category="hair">
                                <div>
                                    <div class="fw-semibold">Hair Styling Only</div>
                                    <small class="text-muted">Professional hairstyling</small>
                                </div>
                            </div>
                            <div class="service-option" data-value="Hair Trial" data-category="hair">
                                <div>
                                    <div class="fw-semibold">Hair Trial</div>
                                    <small class="text-muted">Preview your wedding hairstyle</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Package Selection (Conditional) -->
        <div class="mb-4" id="package-section" style="display: none;">
            <label class="form-label fw-semibold" style="color: #d8a1a6;">Select Package</label>
            <div class="row" id="package-options">
                <!-- Packages will be dynamically loaded here -->
            </div>
        </div>

        <!-- Additional Services -->
        <div class="mb-4" id="addon-section" style="display: none;">
            <label class="form-label fw-semibold" style="color: #d8a1a6;">Additional Services</label>
            <div class="card border-0" style="background: #f9f5f0;">
                <div class="card-body">
                    <div class="row" id="addon-options">
                        <!-- Add-ons will be dynamically loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- ==============================================
                    ENHANCED LOCATION SECTION WITH OPENSTREETMAP
            ============================================== -->
        
        <div class="card border-0 mb-4" style="background: linear-gradient(135deg, #fdf2f2, #f9f5f0); border-left: 4px solid #e8b4b8 !important;">
            <div class="card-body">
                <h5 class="mb-3" style="color: #d8a1a6;">
                    <i class="fas fa-map-marker-alt me-2"></i>Appointment Location
                    <span class="badge ms-2" style="background: #e8b4b8; color: white;">Required</span>
                </h5>
                
                <!-- OpenStreetMap Address Search -->
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="color: #d8a1a6;">
                        <i class="fas fa-search me-1"></i>Quick Address Search
                    </label>
                    <div class="input-group">
                        <input type="text" 
                               id="address-search" 
                               class="form-control" 
                               placeholder="Search for address, venue, or landmark..."
                               value="{{ old('address_search') }}">
                        <button class="btn" 
                                type="button" 
                                id="search-address-btn"
                                style="background: #e8b4b8; border-color: #d8a1a6; color: white;">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                    <div class="form-text">
                        <i class="fas fa-lightbulb me-1"></i>Search to auto-fill address fields and get coordinates
                    </div>
                    
                    <!-- Search Results -->
                    <div id="search-results" class="mt-2" style="display: none;">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-2">
                                <div id="results-container">
                                    <!-- Results will appear here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div id="search-loading" class="text-center mb-3" style="display: none;">
                    <div class="spinner-border spinner-border-sm" style="color: #e8b4b8;"></div>
                    <span class="ms-2" style="color: #d8a1a6;">Searching OpenStreetMap...</span>
                </div>

                <!-- Simple Address Fields -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #d8a1a6;">
                        <i class="fas fa-home me-1"></i>Complete Address <span class="text-danger">*</span>
                    </label>
                    <textarea name="address" id="address" class="form-control" rows="2" 
                              placeholder="Enter complete address including street, barangay, city, and province"
                              required>{{ old('address') }}</textarea>
                    <div class="form-text">Example: 123 Main Street, Barangay San Antonio, Quezon City, Metro Manila</div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #d8a1a6;">
                            <i class="fas fa-city me-1"></i>City <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="city" id="city" class="form-control" 
                               placeholder="e.g., Manila, Quezon City" required value="{{ old('city') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #d8a1a6;">
                            <i class="fas fa-map me-1"></i>Province/State <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="state" id="state" class="form-control" 
                               placeholder="e.g., Metro Manila, Cavite" required value="{{ old('state') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold" style="color: #d8a1a6;">
                            <i class="fas fa-mail-bulk me-1"></i>ZIP Code
                        </label>
                        <input type="text" name="zip_code" id="zip_code" class="form-control" 
                               placeholder="e.g., 1000 (optional)" value="{{ old('zip_code') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #d8a1a6;">
                        <i class="fas fa-globe-asia me-1"></i>Country <span class="text-danger">*</span>
                    </label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="">Select country</option>
                        <option value="Philippines" {{ old('country', 'Philippines') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                        <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>United States</option>
                        <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>United Kingdom</option>
                        <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                        <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                        <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                        <option value="South Korea" {{ old('country') == 'South Korea' ? 'selected' : '' }}>South Korea</option>
                        <option value="Singapore" {{ old('country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                        <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Location Notes -->
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="color: #d8a1a6;">
                        <i class="fas fa-sticky-note me-1"></i>Additional Location Instructions
                    </label>
                    <textarea name="location_notes" class="form-control" rows="2"
                              placeholder="Any special instructions like '3rd floor', 'look for pink door', 'gate code: 1234', etc.">{{ old('location_notes') }}</textarea>
                </div>

                <!-- Quick Location Buttons -->
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="color: #d8a1a6;">
                        <i class="fas fa-bolt me-1"></i>Quick Locations
                    </label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm quick-location-btn" 
                                data-address="Quezon City Memorial Circle, Quezon City"
                                style="background: rgba(232, 180, 184, 0.2); color: #d8a1a6; border: 1px solid #e8b4b8;">
                            <i class="fas fa-park me-1"></i>QC Circle
                        </button>
                        <button type="button" class="btn btn-sm quick-location-btn"
                                data-address="SM Mall of Asia, Pasay City"
                                style="background: rgba(232, 180, 184, 0.2); color: #d8a1a6; border: 1px solid #e8b4b8;">
                            <i class="fas fa-shopping-cart me-1"></i>MOA
                        </button>
                        <button type="button" class="btn btn-sm quick-location-btn"
                                data-address="Makati Central Business District"
                                style="background: rgba(232, 180, 184, 0.2); color: #d8a1a6; border: 1px solid #e8b4b8;">
                            <i class="fas fa-building me-1"></i>Makati CBD
                        </button>
                        <button type="button" class="btn btn-sm quick-location-btn"
                                data-address="Bonifacio Global City, Taguig"
                                style="background: rgba(232, 180, 184, 0.2); color: #d8a1a6; border: 1px solid #e8b4b8;">
                            <i class="fas fa-city me-1"></i>BGC
                        </button>
                    </div>
                    <div class="form-text">Click to quickly set common locations</div>
                </div>

                <!-- Hidden fields for coordinates -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', '') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', '') }}">
                
                <!-- Map Preview -->
                <div class="mt-4 pt-3 border-top">
                    <label class="form-label fw-semibold" style="color: #d8a1a6;">
                        <i class="fas fa-map-marked-alt me-1"></i>Location Preview
                    </label>
                    <div class="card border-0" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                        <div class="card-body p-3">
                            <div id="map-preview" style="height: 600px; border-radius: 8px; display: none;">
                                <!-- Map will appear here -->
                            </div>
                            <div id="no-location-message" class="text-center p-4">
                                <i class="fas fa-map-marker-alt fa-2x mb-3" style="color: #e8b4b8;"></i>
                                <h6 style="color: #d8a1a6;" class="mb-2">Location Preview</h6>
                                <p class="text-muted small mb-0">
                                    Enter an address above to see the location on the map.
                                    Coordinates will be saved automatically.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-text mt-2">
                        <i class="fas fa-info-circle me-1"></i>Coordinates: 
                        <span id="coordinates-display">Not set yet</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date & Time -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold" style="color: #d8a1a6;">Appointment Date <span class="text-danger">*</span></label>
                <input type="date" name="appointment_date" class="form-control form-control-lg" id="appointment_date" required
                       min="{{ date('Y-m-d') }}" value="{{ old('appointment_date') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold" style="color: #d8a1a6;">Appointment Time <span class="text-danger">*</span></label>
                <select name="appointment_time" class="form-control form-control-lg" id="appointment_time" required>
                    <option value="">Select time</option>
                    <option value="06:00" {{ old('appointment_time') == '07:00' ? 'selected' : '' }}>6:00 AM</option>
                    <option value="07:00" {{ old('appointment_time') == '06:00' ? 'selected' : '' }}>7:00 AM</option>
                    <option value="08:00" {{ old('appointment_time') == '08:00' ? 'selected' : '' }}>8:00 AM</option>
                    <option value="09:00" {{ old('appointment_time') == '09:00' ? 'selected' : '' }}>9:00 AM</option>
                    <option value="10:00" {{ old('appointment_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                    <option value="11:00" {{ old('appointment_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                    <option value="12:00" {{ old('appointment_time') == '11:00' ? 'selected' : '' }}>12:00 PM</option>
                    <option value="13:00" {{ old('appointment_time') == '13:00' ? 'selected' : '' }}>1:00 PM</option>
                    <option value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>2:00 PM</option>
                    <option value="15:00" {{ old('appointment_time') == '15:00' ? 'selected' : '' }}>3:00 PM</option>
                    <option value="16:00" {{ old('appointment_time') == '16:00' ? 'selected' : '' }}>4:00 PM</option>
                    <option value="17:00" {{ old('appointment_time') == '17:00' ? 'selected' : '' }}>5:00 PM</option>
                    <option value="18:00" {{ old('appointment_time') == '17:00' ? 'selected' : '' }}>6:00 PM</option>

                </select>
                <div class="form-text text-muted">
                    <i class="fas fa-info-circle me-1"></i>Each time slot is exclusive to one appointment
                </div>
            </div>
        </div>

        <!-- Booking Conflict Alert -->
        <div class="alert alert-danger mb-4" id="booking-conflict-alert" style="display: none;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span id="conflict-message"></span>
        </div>

        <!-- Additional Notes -->
        <div class="mb-4">
            <label class="form-label fw-semibold" style="color: #d8a1a6;">Special Requests & Notes</label>
            <textarea name="additional_notes" class="form-control" rows="3" placeholder="Any allergies, preferred styles, or special requirements...">{{ old('additional_notes') }}</textarea>
        </div>

        <!-- Hidden fields for package and addon data -->
        <input type="hidden" name="selected_package" id="selected-package" value="{{ old('selected_package') }}">
        <input type="hidden" name="selected_addons" id="selected-addons" value="{{ old('selected_addons') }}">
        <input type="hidden" name="total_price" id="total-price" value="{{ old('total_price', 0) }}">
        <input type="hidden" name="predicted_duration" id="predicted-duration-input" value="{{ old('predicted_duration', 60) }}">
        <input type="hidden" name="predicted_no_show_score" id="predicted-no-show-input" value="{{ old('predicted_no_show_score', 0.15) }}">

        <!-- Estimated Total -->
        <div class="card mb-4 text-white" id="total-section" style="display: none; background: linear-gradient(135deg, #e8b4b8, #d8a1a6);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Estimated Total</h6>
                        <small>Final price may vary based on requirements</small>
                    </div>
                    <h4 class="mb-0" id="estimated-total">₱0</h4>
                </div>
            </div>
        </div>

        <!-- ML Prediction Preview -->
        <div class="card mb-4 border-0 shadow-sm" id="ml-prediction-preview" style="display: none; background: linear-gradient(135deg, #fff5f7, #fffafb);">
            <div class="card-body">
                <h5 class="mb-3" style="color: #d8a1a6;">
                    <i class="fas fa-brain me-2"></i>AI-Powered Insights
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded" style="border-left: 4px solid #ff8fa3;">
                            <small class="text-muted">Predicted Duration</small>
                            <h4 class="mb-0" style="color: #ff8fa3;">
                                <i class="fas fa-hourglass-half me-2"></i><span id="predicted-duration-display">--</span> min
                            </h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-white rounded" style="border-left: 4px solid #28a745;">
                            <small class="text-muted">Show-Up Likelihood</small>
                            <h4 class="mb-0" style="color: #28a745;">
                                <i class="fas fa-check-circle me-2"></i><span id="show-up-percentage">--</span>%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-lg px-4" style="background: linear-gradient(135deg, #e8b4b8, #d8a1a6); color: white; border: none;" id="submit-btn" disabled>
                <i class="fas fa-calendar-check me-2"></i>Book Appointment
            </button>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                <i class="fas fa-arrow-left me-2"></i>Cancel
            </a>
        </div>

        <!-- Error Display Section -->
        @if($errors->any())
            <div class="alert alert-danger mt-4">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</div>

<style>
.service-option {
    padding: 15px;
    cursor: pointer;
    border-radius: 12px;
    transition: all 0.3s ease;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 2px solid transparent;
    background: white;
}

.service-option:hover {
    border-color: #e8b4b8;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(232, 180, 184, 0.2);
}

.service-option.selected {
    border-color: #e8b4b8;
    background: linear-gradient(135deg, #fdf2f2, #f9f5f0);
}

.service-category {
    border-left: 4px solid #e8b4b8;
    padding-left: 15px;
    margin-bottom: 20px;
}

.package-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    height: 100%;
}

.package-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(232, 180, 184, 0.15);
}

.package-card.selected {
    border-color: #e8b4b8;
    background: linear-gradient(135deg, #fdf2f2, #f9f5f0);
}

.package-feature {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.package-feature:last-child {
    border-bottom: none;
}

.addon-item {
    padding: 12px;
    border-radius: 8px;
    background: white;
    margin-bottom: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.2s ease;
}

.addon-item:hover {
    border-color: #e8b4b8;
}

.addon-item.selected {
    border-color: #e8b4b8;
    background: #fdf2f2;
}

.price-tag {
    font-size: 1.1em;
    font-weight: 700;
    color: #d8a1a6;
}

.btn-outline-secondary {
    border-color: #e8b4b8;
    color: #d8a1a6;
}

.btn-outline-secondary:hover {
    background: #e8b4b8;
    border-color: #e8b4b8;
    color: white;
}

.form-control:focus {
    border-color: #e8b4b8;
    box-shadow: 0 0 0 0.2rem rgba(232, 180, 184, 0.25);
}

.form-select:focus {
    border-color: #e8b4b8;
    box-shadow: 0 0 0 0.2rem rgba(232, 180, 184, 0.25);
}

#submit-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.time-slot-unavailable {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: #f8f9fa;
}

.time-slot-unavailable option {
    color: #6c757d;
}

#booking-conflict-alert {
    border-left: 4px solid #dc3545;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Map placeholder styling */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

/* OpenStreetMap Search Results */
.search-result-item {
    padding: 12px;
    cursor: pointer;
    border-bottom: 1px solid rgba(232, 180, 184, 0.2);
    transition: all 0.2s ease;
}

.search-result-item:hover {
    background: linear-gradient(135deg, rgba(232, 180, 184, 0.1), rgba(232, 180, 184, 0.05));
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-title {
    font-weight: 600;
    color: #d8a1a6;
    margin-bottom: 4px;
}

.search-result-address {
    font-size: 0.85em;
    color: #666;
    line-height: 1.3;
}

.quick-location-btn:hover {
    background: linear-gradient(135deg, #e8b4b8, #d8a1a6) !important;
    color: white !important;
    transform: translateY(-2px);
}

/* Map preview */
#map-preview {
    transition: all 0.3s ease;
}

/* Required field indicator */
.text-danger {
    color: #dc3545 !important;
}

/* Error styling */
.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}
/* Enhanced time slot styling */
.time-slot-unavailable {
    opacity: 0.5;
    cursor: not-allowed;
    background-color: #f8d7da !important;
    color: #721c24 !important;
    text-decoration: line-through;
}

.time-slot-unavailable:hover {
    background-color: #f8d7da !important;
}

.time-slot-available {
    background-color: #d1e7dd !important;
    color: #0f5132 !important;
}

.time-slot-warning {
    background-color: #fff3cd !important;
    color: #856404 !important;
}

/* Buffer indicator */
.buffer-indicator {
    font-size: 0.8em;
    color: #6c757d;
    margin-left: 5px;
}

.buffer-indicator i {
    color: #e8b4b8;
}

/* Duration display */
#predicted-duration-display strong {
    color: #d8a1a6;
    background: rgba(232, 180, 184, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
}

/* Time slot tooltip */
option[title]:hover::after {
    content: attr(title);
    position: absolute;
    background: #333;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    z-index: 1000;
    white-space: pre-line;
}
</style>

<script>
// UPDATED SERVICE DATA TO MATCH YOUR HTML EXACTLY
const serviceData = {
    // Bridal Services (matches your HTML)
    'Full Bridal Package': {
        packages: [
            {
                name: 'Package A - Essential Bridal',
                price: 5000,
                features: [
                    'Proper skin prep',
                    'Traditional makeup',
                    'Basic hairstyle',
                    '3D eyelashes',
                    'Contact lens (no grade)'
                ]
            },
            {
                name: 'Package B - Complete Bridal',
                price: 8000,
                features: [
                    'Premium skin prep',
                    'Airbrush makeup',
                    '2 Hairstyles (Ceremony & Reception)',
                    '3D eyelashes + Contact lens',
                    'Human hair extension',
                    'Free accessories'
                ],
                popular: true
            },
            {
                name: 'Package C - Luxury Bridal',
                price: 12000,
                features: [
                    'Luxury skin prep & care',
                    'HD Airbrush makeup',
                    '3 Hairstyles with trial',
                    'Premium 3D eyelashes',
                    'Human hair extension',
                    'Full accessory set',
                    'Bridal robe',
                    'Touch-up kit'
                ]
            }
        ],
        addons: [
            { name: 'Additional Head (Traditional)', price: 1000 },
            { name: 'Mother of the Bride Makeup', price: 1500 },
            { name: 'Bridesmaid Makeup', price: 1200 },
            { name: 'Early Call Time (before 6 AM)', price: 500 },
            { name: 'Trial Session', price: 1500 }
        ]
    },
    
    'Civil Wedding Package': {
        packages: [
            {
                name: 'Simple Civil Package',
                price: 3500,
                features: [
                    'Skin preparation',
                    'Natural makeup look',
                    'Elegant hairstyle',
                    'Basic eyelashes',
                    '2-hour service'
                ]
            },
            {
                name: 'Complete Civil Package',
                price: 5500,
                features: [
                    'Premium skin prep',
                    'Traditional makeup',
                    '2 Hairstyles',
                    '3D eyelashes',
                    'Contact lens',
                    'Human hair extension',
                    '4-hour service'
                ],
                popular: true
            }
        ],
        addons: [
            { name: 'Additional Head', price: 800 },
            { name: 'Photo Shoot Touch-up', price: 500 }
        ]
    },
    
    // Traditional Makeup (matches your HTML)
    'Traditional Makeup Only': {
        price: 1500,
        packages: [],
        addons: [
            { name: 'Airbrush Upgrade', price: 500 },
            { name: '3D Eyelashes', price: 200 },
            { name: 'Contact Lens', price: 150 }
        ]
    },
    
    'Traditional Makeup + Hair': {
        price: 2500,
        packages: [],
        addons: [
            { name: 'Hair Extension', price: 800 },
            { name: 'Special Hairstyle', price: 500 },
            { name: 'Hair Accessories', price: 300 }
        ]
    },
    
    // Special Events (matches your HTML)
    'Debut Makeup': {
        packages: [
            {
                name: 'Basic Debut Package',
                price: 2500,
                features: [
                    'Skin preparation',
                    'Traditional makeup',
                    'Simple hairstyle',
                    'Basic accessories',
                    '3-hour service'
                ]
            },
            {
                name: 'Complete Debut Package',
                price: 4500,
                features: [
                    'Premium skin prep',
                    'Airbrush makeup',
                    '2 Hairstyles (Entrance & Party)',
                    '3D eyelashes',
                    'Crown/Headdress',
                    'Touch-up kit',
                    '4-hour service'
                ],
                popular: true
            }
        ],
        addons: [
            { name: 'Parent Makeup', price: 1000 },
            { name: 'Crown/Headdress', price: 500 },
            { name: 'Extra Touch-up', price: 300 }
        ]
    },
    
    'Graduation Makeup': {
        price: 1200,
        packages: [],
        addons: [
            { name: 'Cap Hairstyling', price: 300 },
            { name: 'Additional Touch-up', price: 200 },
            { name: 'Photo-ready Finish', price: 250 }
        ]
    },
    
    'Formal Event Makeup': {
        price: 1800,
        packages: [],
        addons: [
            { name: 'Glamorous Upgrade', price: 700 },
            { name: 'False Eyelashes', price: 250 },
            { name: 'Quick Change', price: 500 }
        ]
    },
    
    // Hair Services (matches your HTML)
    'Hair Styling Only': {
        price: 1000,
        packages: [],
        addons: [
            { name: 'Hair Extension', price: 800 },
            { name: 'Hair Accessories', price: 300 },
            { name: 'Complex Braiding', price: 500 }
        ]
    },
    
    'Hair Trial': {
        price: 800,
        packages: [],
        addons: [
            { name: 'Consultation Session', price: 300 },
            { name: 'Style Photos', price: 200 },
            { name: 'Product Recommendations', price: 150 }
        ]
    }
};

// ============================================
// COMPLETE JAVASCRIPT WITH EVERYTHING
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // ========== SETTINGS ==========
    const DEBUG_MODE = true;
    const USE_BACKEND = true; // Set to false until backend is fixed
    const TIME_BUFFER_MINUTES = 60; // 1 hour buffer
    const MIN_ADVANCE_HOURS = 2; // Minimum 2 hours advance booking
    
    function debugLog(...args) {
        if (DEBUG_MODE) {
            console.log('[Booking Debug]', ...args);
        }
    }

    // ========== ALL FORM ELEMENTS ==========
    // Location elements
    const addressSearch = document.getElementById('address-search');
    const searchBtn = document.getElementById('search-address-btn');
    const searchResults = document.getElementById('search-results');
    const resultsContainer = document.getElementById('results-container');
    const searchLoading = document.getElementById('search-loading');
    const addressInput = document.getElementById('address');
    const cityInput = document.getElementById('city');
    const stateInput = document.getElementById('state');
    const zipInput = document.getElementById('zip_code');
    const countrySelect = document.getElementById('country');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const mapPreview = document.getElementById('map-preview');
    const noLocationMessage = document.getElementById('no-location-message');
    const coordinatesDisplay = document.getElementById('coordinates-display');
    const quickLocationBtns = document.querySelectorAll('.quick-location-btn');
    
    // Appointment elements
    const dropdownToggle = document.getElementById('service-dropdown-toggle');
    const serviceOptions = document.getElementById('service-options');
    const serviceDisplay = document.getElementById('service-display');
    const serviceOptionsList = document.querySelectorAll('.service-option');
    const packageSection = document.getElementById('package-section');
    const packageOptions = document.getElementById('package-options');
    const addonSection = document.getElementById('addon-section');
    const addonOptions = document.getElementById('addon-options');
    const totalSection = document.getElementById('total-section');
    const estimatedTotal = document.getElementById('estimated-total');
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');
    const appointmentForm = document.getElementById('appointment-form');
    const submitBtn = document.getElementById('submit-btn');
    
    // Name elements
    const firstNameInput = document.querySelector('input[name="first_name"]');
    const middleNameInput = document.querySelector('input[name="middle_name"]');
    const lastNameInput = document.querySelector('input[name="last_name"]');
    const suffixSelect = document.querySelector('select[name="suffix"]');
    const clientNameDisplay = document.getElementById('client_name_display');
    
    // Time elements
    const appointmentDateInput = document.getElementById('appointment_date');
    const appointmentTimeSelect = document.getElementById('appointment_time');
    const bookingConflictAlert = document.getElementById('booking-conflict-alert');
    const conflictMessage = document.getElementById('conflict-message');
    const mlPredictionPreview = document.getElementById('ml-prediction-preview');
    
    // App state
    let selectedService = '';
    let selectedPackage = null;
    let selectedAddons = [];
    let basePrice = 0;
    let hasPackages = false;
    let isCheckingAvailability = false;
    let debounceTimer;
    let map = null;
    let marker = null;

    // ========== INITIALIZATION ==========
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    appointmentDateInput.min = today;
    
    debugLog('Booking system loaded. Using:', USE_BACKEND ? 'Backend Mode' : 'Simulation Mode');

    // ========== OPENSTREETMAP FUNCTIONS ==========
    
    // Event Listeners for OpenStreetMap Search
    searchBtn.addEventListener('click', searchAddress);
    addressSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchAddress();
        }
    });

    // Quick location buttons
    quickLocationBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const address = this.getAttribute('data-address');
            addressSearch.value = address;
            searchAddress();
        });
    });

    function searchAddress() {
        const query = addressSearch.value.trim();
        
        if (!query || query.length < 2) {
            showAlert('Please enter at least 2 characters to search.', 'warning');
            return;
        }

        // Show loading
        searchLoading.style.display = 'block';
        searchResults.style.display = 'none';
        resultsContainer.innerHTML = '';

        // First: Try Philippines-specific search
        const searchUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ', Philippines')}&limit=10&countrycodes=ph&addressdetails=1&viewbox=116.928,4.587,126.605,18.229&bounded=1`;
        
        fetch(searchUrl)
            .then(response => response.json())
            .then(data => {
                searchLoading.style.display = 'none';
                
                if (data && data.length > 0) {
                    displaySearchResults(data);
                    searchResults.style.display = 'block';
                    showAlert('Philippine location found!', 'success');
                } else {
                    // Try broader search
                    const broaderUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=8&addressdetails=1`;
                    
                    fetch(broaderUrl)
                        .then(response => response.json())
                        .then(broaderData => {
                            if (broaderData && broaderData.length > 0) {
                                displaySearchResults(broaderData);
                                searchResults.style.display = 'block';
                            } else {
                                resultsContainer.innerHTML = `
                                    <div class="text-center p-3">
                                        <i class="fas fa-search fa-2x mb-3" style="color: #e8b4b8;"></i>
                                        <h6 style="color: #d8a1a6;">No results found</h6>
                                        <p class="text-muted small">Try being more specific or enter the address manually.</p>
                                    </div>
                                `;
                                searchResults.style.display = 'block';
                            }
                        });
                }
            })
            .catch(error => {
                searchLoading.style.display = 'none';
                console.error('OpenStreetMap search error:', error);
                showAlert('Error searching address. Please try again.', 'danger');
            });
    }

    function displaySearchResults(results) {
        resultsContainer.innerHTML = '';
        
        // Sort results by relevance (Philippine locations first)
        results.sort((a, b) => {
            const aIsPH = a.display_name.includes('Philippines') || a.display_name.includes('PH');
            const bIsPH = b.display_name.includes('Philippines') || b.display_name.includes('PH');
            if (aIsPH && !bIsPH) return -1;
            if (!aIsPH && bIsPH) return 1;
            return 0;
        });
        
        results.forEach((result, index) => {
            const item = document.createElement('div');
            item.className = 'search-result-item';
            
            // Extract readable address parts
            const address = result.display_name || 'Location';
            const shortAddress = address.length > 80 ? address.substring(0, 80) + '...' : address;
            
            // Add Philippine flag for Philippine locations
            const isPhilippine = address.includes('Philippines') || address.includes('PH');
            const flagIcon = isPhilippine ? '<span class="me-1">🇵🇭</span>' : '';
            
            item.innerHTML = `
                <div class="search-result-title">
                    <i class="fas fa-map-marker-alt me-2" style="color: #e8b4b8;"></i>
                    ${flagIcon}${shortAddress}
                </div>
                <div class="search-result-address">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        ${parseAddressType(result)} • Coordinates: ${result.lat}, ${result.lon}
                    </small>
                </div>
            `;
            
            item.addEventListener('click', () => selectSearchResult(result));
            resultsContainer.appendChild(item);
        });
    }

    function parseAddressType(result) {
        const type = result.type || '';
        const address = result.display_name || '';
        
        // Philippine-specific type detection
        if (address.toLowerCase().includes('sm ') || address.toLowerCase().includes('mall') || type === 'mall') {
            return 'Shopping Mall';
        }
        if (address.toLowerCase().includes('hotel') || type === 'hotel') {
            return 'Hotel';
        }
        if (address.toLowerCase().includes('restaurant') || type === 'restaurant') {
            return 'Restaurant';
        }
        if (address.toLowerCase().includes('school') || type === 'school') {
            return 'School';
        }
        if (address.toLowerCase().includes('hospital') || type === 'hospital') {
            return 'Hospital';
        }
        if (address.toLowerCase().includes('barangay') || type === 'administrative') {
            return 'Barangay';
        }
        if (address.toLowerCase().includes('city hall') || type === 'townhall') {
            return 'City Hall';
        }
        if (type === 'house' || type === 'residential') {
            return 'Residential';
        }
        
        return type.charAt(0).toUpperCase() + type.slice(1) || 'Location';
    }

    function selectSearchResult(result) {
        // Hide search results
        searchResults.style.display = 'none';
        
        // Extract and parse address components
        const parsedAddress = parsePhilippineAddress(result);
        
        // Fill form fields with parsed data
        addressInput.value = parsedAddress.street || result.display_name.split(',')[0] || '';
        cityInput.value = parsedAddress.city || '';
        stateInput.value = parsedAddress.state || '';
        zipInput.value = parsedAddress.postcode || '';
        countrySelect.value = parsedAddress.country || 'Philippines';
        
        // Set coordinates
        latitudeInput.value = result.lat;
        longitudeInput.value = result.lon;
        
        // Update coordinates display
        coordinatesDisplay.style.color = '#28a745';
        coordinatesDisplay.innerHTML = `<i class="fas fa-check-circle me-1"></i>${result.lat}, ${result.lon}`;
        
        // Update search input
        addressSearch.value = result.display_name;
        
        // Show map preview
        showMapPreview(result.lat, result.lon, result.display_name);
        
        // Validate form
        validateForm();
        
        // Show success message
        showAlert('Address found! Fields have been auto-filled.', 'success');
    }

    function parsePhilippineAddress(result) {
        const parsed = {
            street: '',
            city: '',
            state: '',
            postcode: '',
            country: 'Philippines'
        };

        // Use addressdetails if available
        if (result.address) {
            const addr = result.address;
            
            // Extract street address (Philippine format)
            const streetParts = [
                addr.house_number,
                addr.house_name,
                addr.road,
                addr.neighbourhood,
                addr.suburb,
                addr.village
            ].filter(Boolean);
            
            parsed.street = streetParts.join(', ') || '';
            
            // Extract city/municipality
            parsed.city = addr.city || addr.town || addr.municipality || addr.county || '';
            
            // Extract province/region
            parsed.state = addr.state || addr.province || addr.region || '';
            
            // Extract postal code
            parsed.postcode = addr.postcode || '';
            
            // Clean up Philippine city names
            if (parsed.city) {
                parsed.city = parsed.city.replace(/ City$/i, '').trim();
                // Add "City" back for major cities
                const majorCities = ['Quezon', 'Manila', 'Makati', 'Pasig', 'Taguig', 'Parañaque', 'Muntinlupa', 'Las Piñas', 'Caloocan', 'Malabon', 'Navotas', 'Valenzuela', 'Mandaluyong', 'Marikina', 'Pasay', 'San Juan', 'Pateros'];
                if (majorCities.includes(parsed.city)) {
                    parsed.city = parsed.city + ' City';
                }
            }
            
            // Handle Metro Manila
            if (parsed.state === 'Metro Manila' || parsed.state === 'NCR') {
                parsed.state = 'Metro Manila';
            }
            
            return parsed;
        }
        
        // Fallback: Parse from display_name
        const parts = result.display_name.split(', ').map(part => part.trim());
        
        // Philippine address parsing logic
        for (let i = 0; i < parts.length; i++) {
            const part = parts[i];
            
            // Check for Philippine provinces
            if (isPhilippineProvince(part)) {
                parsed.state = part;
            }
            // Check for Philippine cities
            else if (isPhilippineCity(part)) {
                parsed.city = formatPhilippineCity(part);
            }
            // Check for postal code (4 digits)
            else if (/^\d{4}$/.test(part)) {
                parsed.postcode = part;
            }
            // First non-country part is usually street
            else if (i === 0 || (parsed.street === '' && part !== 'Philippines' && !part.includes('Philippines'))) {
                if (parsed.street === '') {
                    parsed.street = part;
                } else {
                    parsed.street += ', ' + part;
                }
            }
        }
        
        // Default for Metro Manila cities
        if (!parsed.state && parsed.city && isMetroManilaCity(parsed.city)) {
            parsed.state = 'Metro Manila';
        }
        
        return parsed;
    }

    function isPhilippineCity(name) {
        const phCities = [
            'Manila', 'Quezon City', 'Caloocan', 'Davao City', 'Cebu City',
            'Zamboanga City', 'Taguig', 'Antipolo', 'Pasig', 'Cagayan de Oro',
            'Parañaque', 'Makati', 'Bacolod', 'Muntinlupa', 'Iloilo City',
            'Calamba', 'Baguio', 'Butuan', 'Lapu-Lapu', 'Las Piñas',
            'Marikina', 'Mandaue', 'Navotas', 'Pasay', 'San Juan',
            'Valenzuela', 'Malabon', 'Mandaluyong', 'Taguig', 'Pateros',
            'Angeles', 'Bacoor', 'Imus', 'Dasmariñas', 'General Trias',
            'Santa Rosa', 'Binan', 'San Pablo', 'San Pedro', 'Cabuyao',
            'Lucena', 'Naga', 'Legazpi', 'Iriga', 'Roxas', 'Sorsogon',
            'Masbate', 'Catarman', 'Calbayog', 'Borongan', 'Tacloban',
            'Tagaytay', 'Batangas City', 'Lipá', 'Tanauan', 'Malvar'
        ];
        
        const normalized = name.replace(/ City$/i, '').trim();
        return phCities.some(city => 
            normalized.toLowerCase() === city.toLowerCase() || 
            normalized.toLowerCase().includes(city.toLowerCase()) ||
            city.toLowerCase().includes(normalized.toLowerCase())
        );
    }

    function isPhilippineProvince(name) {
        const phProvinces = [
            'Metro Manila', 'NCR', 'Cavite', 'Laguna', 'Rizal', 'Batangas',
            'Bulacan', 'Pampanga', 'Tarlac', 'Zambales', 'Bataan',
            'Nueva Ecija', 'Quezon', 'Aurora', 'Albay', 'Camarines Sur',
            'Camarines Norte', 'Sorsogon', 'Catanduanes', 'Masbate',
            'Iloilo', 'Negros Occidental', 'Cebu', 'Bohol', 'Leyte',
            'Samar', 'Eastern Samar', 'Northern Samar', 'Zamboanga del Sur',
            'Zamboanga del Norte', 'Zamboanga Sibugay', 'Bukidnon',
            'Misamis Oriental', 'Davao del Sur', 'Davao del Norte',
            'Davao Oriental', 'Cotabato', 'South Cotabato', 'Sultan Kudarat'
        ];
        
        return phProvinces.some(province =>
            name.toLowerCase().includes(province.toLowerCase()) ||
            province.toLowerCase().includes(name.toLowerCase())
        );
    }

    function formatPhilippineCity(cityName) {
        const normalized = cityName.replace(/ City$/i, '').trim();
        const majorCities = {
            'Quezon': 'Quezon City',
            'Caloocan': 'Caloocan City',
            'Pasay': 'Pasay City',
            'Makati': 'Makati City',
            'Mandaluyong': 'Mandaluyong City',
            'Taguig': 'Taguig City',
            'Manila': 'Manila',
            'Davao': 'Davao City',
            'Cebu': 'Cebu City',
            'San Pablo': 'San Pablo City',
            'Zamboanga': 'Zamboanga City',
            'Iloilo': 'Iloilo City'
        };
        
        return majorCities[normalized] || normalized;
    }

    function isMetroManilaCity(cityName) {
        const normalized = cityName.replace(/ City$/i, '').trim();
        const metroManilaCities = [
            'Quezon', 'Manila', 'Caloocan', 'Las Piñas', 'Makati',
            'Malabon', 'Mandaluyong', 'Marikina', 'Muntinlupa', 'Navotas',
            'Parañaque', 'Pasay', 'Pasig', 'San Juan', 'Taguig', 'Valenzuela',
            'Pateros'
        ];
        return metroManilaCities.includes(normalized);
    }

    function showMapPreview(lat, lng, address) {
        // Hide "no location" message
        noLocationMessage.style.display = 'none';
        
        // Check if coordinates are valid
        if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
            showAlert('Invalid coordinates. Please try a different address.', 'warning');
            return;
        }
        
        // Load Leaflet.js dynamically if not already loaded
        if (typeof L === 'undefined') {
            // Load Leaflet CSS
            const leafletCSS = document.createElement('link');
            leafletCSS.rel = 'stylesheet';
            leafletCSS.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(leafletCSS);
            
            // Load Leaflet JS
            const leafletJS = document.createElement('script');
            leafletJS.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            leafletJS.onload = function() {
                initMap(parseFloat(lat), parseFloat(lng), address);
            };
            document.head.appendChild(leafletJS);
        } else {
            initMap(parseFloat(lat), parseFloat(lng), address);
        }
    }

    function initMap(lat, lng, address) {
        try {
            // Clear existing map
            mapPreview.innerHTML = '';
            mapPreview.style.display = 'block';
            
            // Validate coordinates
            if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                throw new Error('Invalid coordinate range');
            }
            
            // Create map
            map = L.map('map-preview').setView([lat, lng], 15);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Add marker
            marker = L.marker([lat, lng]).addTo(map);
            
            // Add popup with address
            if (address) {
                const shortAddress = address.length > 100 ? address.substring(0, 100) + '...' : address;
                marker.bindPopup(`<b>Selected Location:</b><br>${shortAddress}`).openPopup();
            }
            
            // Add click handler to map (users can adjust pin)
            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);
                latitudeInput.value = e.latlng.lat.toFixed(6);
                longitudeInput.value = e.latlng.lng.toFixed(6);
                coordinatesDisplay.innerHTML = `<i class="fas fa-mouse-pointer me-1"></i>${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}`;
                showAlert('Location updated! You can also click on the map to adjust the pin.', 'info');
            });
            
        } catch (error) {
            console.error('Map initialization error:', error);
            mapPreview.innerHTML = `
                <div class="text-center p-4">
                    <i class="fas fa-map-marker-alt fa-2x mb-3" style="color: #28a745;"></i>
                    <h6 style="color: #28a745;">Location Saved</h6>
                    <p class="text-muted small">Coordinates: ${lat}, ${lng}</p>
                    <p class="text-muted small">Map preview unavailable, but location is saved.</p>
                </div>
            `;
        }
    }

    // ========== HELPER FUNCTIONS ==========
    
    function showAlert(message, type) {
        // Remove existing alerts
        document.querySelectorAll('.alert.alert-dismissible').forEach(alert => {
            if (alert.parentNode) alert.parentNode.removeChild(alert);
        });
        
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert after location card header
        const locationCard = document.querySelector('.card.border-0.mb-4 .card-body');
        if (locationCard) {
            locationCard.insertBefore(alertDiv, locationCard.firstChild);
        }
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // ========== SIMULATION FUNCTIONS ==========
    
    function simulateGetBlockedSlots(date, predictedDuration) {
        debugLog('SIMULATION: Getting blocked slots for', date, 'Duration:', predictedDuration);
        
        return new Promise((resolve) => {
            setTimeout(() => {
                const blockedSlots = [];
                const selectedDate = new Date(date);
                const now = new Date();
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                const selectedDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), selectedDate.getDate());
                
                // Block past dates
                if (selectedDay < today) {
                    // All slots blocked for past dates
                    ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00','12:00', '13:00', '14:00', '15:00', '16:00', '17:00'].forEach(slot => {
                        blockedSlots.push({
                            time: slot,
                            reason: 'Cannot book in the past'
                        });
                    });
                }
                // Block times for today
                else if (selectedDay.getTime() === today.getTime()) {
                    const currentHour = now.getHours();
                    const currentMinute = now.getMinutes();
                    const currentTimeInMinutes = (currentHour * 60) + currentMinute;
                    
                    // Check each time slot
                    ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00','12:00', '13:00', '14:00', '15:00', '16:00', '17:00'].forEach(slot => {
                        const slotHour = parseInt(slot.split(':')[0]);
                        const slotTimeInMinutes = slotHour * 60;
                        
                        // Check if slot is too close to current time
                        if (slotTimeInMinutes < currentTimeInMinutes + (MIN_ADVANCE_HOURS * 60)) {
                            blockedSlots.push({
                                time: slot,
                                reason: `Requires at least ${MIN_ADVANCE_HOURS} hours advance (current time: ${currentHour}:${currentMinute.toString().padStart(2, '0')})`
                            });
                        }
                    });
                }
                
                // Block based on predicted duration + buffer
                const totalDuration = predictedDuration + TIME_BUFFER_MINUTES;
                debugLog('Total duration with Extra Reserved Time:', totalDuration, 'minutes');
                
                // Example: If appointment takes 2+ hours, block adjacent slots
                if (totalDuration >= 120) {
                    // Block 10:00 for long appointments (simulating conflict)
                    if (!blockedSlots.some(b => b.time === '10:00')) {
                        blockedSlots.push({
                            time: '10:00',
                            reason: `Time slot conflicts with ${predictedDuration}m service + ${TIME_BUFFER_MINUTES}m Extra Time`
                        });
                    }
                    // Block 11:00 if 10:00 is booked
                    if (!blockedSlots.some(b => b.time === '11:00')) {
                        blockedSlots.push({
                            time: '11:00',
                            reason: `Time slot too close to previous long appointment`
                        });
                    }
                }
                
                resolve(blockedSlots);
            }, 300);
        });
    }

    function simulateCheckAvailability(date, time, predictedDuration) {
        debugLog('SIMULATION: Checking availability for', date, time, 'Duration:', predictedDuration);
        
        return new Promise((resolve) => {
            setTimeout(() => {
                const selectedDate = new Date(date);
                const now = new Date();
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                const selectedDay = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), selectedDate.getDate());
                
                // Check if date is in the past
                if (selectedDay < today) {
                    resolve({
                        available: false,
                        message: 'Cannot book appointments in the past'
                    });
                    return;
                }
                
                // Check if time is too close for today
                if (selectedDay.getTime() === today.getTime()) {
                    const slotHour = parseInt(time.split(':')[0]);
                    const currentHour = now.getHours();
                    const currentMinute = now.getMinutes();
                    const currentTimeInMinutes = (currentHour * 60) + currentMinute;
                    const slotTimeInMinutes = slotHour * 60;
                    
                    if (slotTimeInMinutes < currentTimeInMinutes + (MIN_ADVANCE_HOURS * 60)) {
                        const earliestHour = Math.ceil((currentTimeInMinutes + (MIN_ADVANCE_HOURS * 60)) / 60);
                        resolve({
                            available: false,
                            message: `Bookings require at least ${MIN_ADVANCE_HOURS} hours advance. Earliest available: ${earliestHour}:00`
                        });
                        return;
                    }
                }
                
                // Check if time conflicts with buffer logic
                const totalDuration = predictedDuration + TIME_BUFFER_MINUTES;
                const slotHour = parseInt(time.split(':')[0]);
                
                // Example conflict: 10:00 AM is always "booked" in simulation
                if (time === '10:00' && predictedDuration > 60) {
                    resolve({
                        available: false,
                        message: `Time slot conflicts with existing appointment (${predictedDuration}m + ${TIME_BUFFER_MINUTES}m Extra Time)`,
                        next_available: '13:00'
                    });
                    return;
                }
                
                // Calculate end time
                const startDateTime = new Date(date + 'T' + time + ':00');
                const endDateTime = new Date(startDateTime.getTime() + totalDuration * 60000);
                
                resolve({
                    available: true,
                    end_time: endDateTime.toISOString(),
                    total_duration: totalDuration
                });
            }, 300);
        });
    }

    // ========== TIME SLOT MANAGEMENT ==========

    function updateTimeSlotsBasedOnDate() {
        const date = appointmentDateInput.value;
        const predictedDuration = parseInt(document.getElementById('predicted-duration-input').value) || 60;
        
        if (!date) {
            debugLog('No date selected');
            return;
        }
        
        debugLog('Updating time slots for date:', date);
        
        // Show loading state
        const timeOptions = appointmentTimeSelect.querySelectorAll('option');
        timeOptions.forEach(option => {
            if (option.value) {
                option.disabled = true;
                option.classList.add('time-slot-unavailable');
                option.title = 'Checking availability...';
            }
        });
        
        if (USE_BACKEND) {
            // Backend mode (will fail until backend is fixed)
            fetch('/get-blocked-slots', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 'test'
                },
                body: JSON.stringify({ appointment_date: date, predicted_duration: predictedDuration })
            })
            .then(response => response.json())
            .then(processBlockedSlots)
            .catch(error => {
                debugLog('Backend failed, using simulation:', error);
                simulateGetBlockedSlots(date, predictedDuration).then(processBlockedSlots);
            });
        } else {
            // Simulation mode
            simulateGetBlockedSlots(date, predictedDuration).then(processBlockedSlots);
        }
    }

    function processBlockedSlots(blockedSlots) {
        debugLog('Processing blocked slots:', blockedSlots);
        
        // First, enable all slots
        const timeOptions = appointmentTimeSelect.querySelectorAll('option');
        timeOptions.forEach(option => {
            if (option.value) {
                option.disabled = false;
                option.classList.remove('time-slot-unavailable');
                option.removeAttribute('title');
            }
        });
        
        // Then disable the blocked ones
        if (blockedSlots && Array.isArray(blockedSlots)) {
            blockedSlots.forEach(slot => {
                const option = appointmentTimeSelect.querySelector(`option[value="${slot.time}"]`);
                if (option) {
                    option.disabled = true;
                    option.classList.add('time-slot-unavailable');
                    if (slot.reason) {
                        option.title = slot.reason;
                    }
                }
            });
        }
        
        // Check if current selection is blocked
        const selectedOption = appointmentTimeSelect.options[appointmentTimeSelect.selectedIndex];
        if (selectedOption && selectedOption.disabled) {
            appointmentTimeSelect.value = '';
            bookingConflictAlert.style.display = 'block';
            conflictMessage.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                Your selected time is no longer available. Please choose another time.
            `;
        } else {
            bookingConflictAlert.style.display = 'none';
        }
        
        validateForm();
    }

    function checkBookingAvailability() {
        const date = appointmentDateInput.value;
        const time = appointmentTimeSelect.value;
        const predictedDuration = parseInt(document.getElementById('predicted-duration-input').value) || 60;

        if (!date || !time || isCheckingAvailability) return;

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            isCheckingAvailability = true;
            bookingConflictAlert.style.display = 'none';
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Checking availability...';

            debugLog('Checking availability for:', date, time);

            if (USE_BACKEND) {
                // Backend mode
                fetch('/check-booking-availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 'test'
                    },
                    body: JSON.stringify({
                        appointment_date: date,
                        appointment_time: time,
                        predicted_duration: predictedDuration
                    })
                })
                .then(response => response.json())
                .then(data => {
                    isCheckingAvailability = false;
                    processAvailabilityResponse(data);
                })
                .catch(error => {
                    debugLog('Backend failed, using simulation:', error);
                    isCheckingAvailability = false;
                    simulateCheckAvailability(date, time, predictedDuration).then(processAvailabilityResponse);
                });
            } else {
                // Simulation mode
                simulateCheckAvailability(date, time, predictedDuration).then(data => {
                    isCheckingAvailability = false;
                    processAvailabilityResponse(data);
                });
            }
        }, 300);
    }

    function processAvailabilityResponse(data) {
        debugLog('Availability response:', data);
        
        if (data.available === false) {
            conflictMessage.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${data.message}
                ${data.next_available ? `<br><strong>Next available: ${data.next_available}</strong>` : ''}
            `;
            bookingConflictAlert.style.display = 'block';
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Time Slot Unavailable';
            submitBtn.style.opacity = '0.5';
        } else {
            bookingConflictAlert.style.display = 'none';
            submitBtn.disabled = !validateForm();
            submitBtn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Book Appointment';
            submitBtn.style.opacity = validateForm() ? '1' : '0.5';
        }
    }

    // ========== EVENT LISTENERS ==========
    
    // Date change
    appointmentDateInput.addEventListener('change', function() {
        debugLog('Date changed to:', this.value);
        validateForm();
        updateTimeSlotsBasedOnDate();
        
        if (appointmentTimeSelect.value) {
            checkBookingAvailability();
        }
    });

    // Time change
    appointmentTimeSelect.addEventListener('change', function() {
        debugLog('Time changed to:', this.value);
        validateForm();
        
        if (appointmentDateInput.value) {
            checkBookingAvailability();
        }
        
        if (this.value && selectedService) {
            generateMLPredictions();
        }
    });

    // Service selection
    dropdownToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        if (serviceOptions.style.display === 'none') {
            serviceOptions.style.display = 'block';
            dropdownToggle.innerHTML = '<i class="fas fa-chevron-up"></i>';
        } else {
            serviceOptions.style.display = 'none';
            dropdownToggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
        }
    });

    serviceOptionsList.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            serviceDisplay.value = value;
            selectedService = value;

            serviceOptionsList.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');

            loadServiceDetails(value);

            serviceOptions.style.display = 'none';
            dropdownToggle.innerHTML = '<i class="fas fa-chevron-down"></i>';

            validateForm();
            
            debugLog('Service selected:', value);
            
            setTimeout(() => {
                if (appointmentDateInput.value) {
                    updateTimeSlotsBasedOnDate();
                }
                
                if (appointmentTimeSelect.value) {
                    generateMLPredictions();
                }
            }, 100);
        });
    });

    document.addEventListener('click', function() {
        serviceOptions.style.display = 'none';
        dropdownToggle.innerHTML = '<i class="fas fa-chevron-down"></i>';
    });

    // ========== SERVICE FUNCTIONS ==========
    
    function loadServiceDetails(service) {
        const data = serviceData[service];

        selectedPackage = null;
        selectedAddons = [];
        basePrice = 0;
        hasPackages = false;

        if (data && data.packages && data.packages.length > 0) {
            hasPackages = true;
            packageSection.style.display = 'block';
            loadPackages(data.packages);
        } else {
            packageSection.style.display = 'none';
            hasPackages = false;
            basePrice = data.price || 0;
        }

        if (data && data.addons && data.addons.length > 0) {
            addonSection.style.display = 'block';
            loadAddons(data.addons);
        } else {
            addonSection.style.display = 'none';
        }

        updateTotal();
        validateForm();
    }

    function loadPackages(packages) {
        packageOptions.innerHTML = '';
        packages.forEach((pkg, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-6 col-lg-4 mb-3';
            col.innerHTML = `
                <div class="card package-card ${pkg.popular ? 'border-warning' : ''}" data-price="${pkg.price}" data-index="${index}">
                    ${pkg.popular ? '<div class="card-header text-center py-2" style="background: #f4e4b6; color: #8b7355;"><small class="fw-bold"><i class="fas fa-crown me-1"></i>MOST POPULAR</small></div>' : ''}
                    <div class="card-body">
                        <h6 class="card-title">${pkg.name}</h6>
                        <div class="price-tag mb-3">₱${pkg.price.toLocaleString()}</div>
                        <div class="package-features">
                            ${pkg.features.map(feature => `<div class="package-feature"><small>✓ ${feature}</small></div>`).join('')}
                        </div>
                    </div>
                </div>
            `;
            packageOptions.appendChild(col);
        });

        document.querySelectorAll('.package-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.package-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedPackage = packages[this.getAttribute('data-index')];
                basePrice = selectedPackage.price;
                updateTotal();
                validateForm();
                
                debugLog('Package selected:', selectedPackage.name);
                
                setTimeout(() => {
                    if (appointmentDateInput.value) {
                        updateTimeSlotsBasedOnDate();
                    }
                    
                    if (appointmentTimeSelect.value && selectedService) {
                        generateMLPredictions();
                    }
                }, 100);
            });
        });
    }

    function loadAddons(addons) {
        addonOptions.innerHTML = '';
        addons.forEach((addon, index) => {
            const col = document.createElement('div');
            col.className = 'col-md-6';
            col.innerHTML = `
                <div class="addon-item d-flex justify-content-between align-items-center" data-index="${index}">
                    <div>
                        <div class="fw-semibold">${addon.name}</div>
                    </div>
                    <div class="text-end">
                        <div class="price-tag">+₱${addon.price.toLocaleString()}</div>
                        <small class="text-muted">Optional</small>
                    </div>
                </div>
            `;
            addonOptions.appendChild(col);
        });

        document.querySelectorAll('.addon-item').forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('selected');
                const index = this.getAttribute('data-index');
                const addon = addons[index];

                if (this.classList.contains('selected')) {
                    selectedAddons.push(addon);
                } else {
                    selectedAddons = selectedAddons.filter(a => a !== addon);
                }
                updateTotal();
                
                debugLog('Addon toggled:', addon.name);
                
                setTimeout(() => {
                    if (appointmentDateInput.value) {
                        updateTimeSlotsBasedOnDate();
                    }
                    
                    if (appointmentTimeSelect.value && selectedService) {
                        generateMLPredictions();
                    }
                }, 100);
            });
        });
    }

    // ========== VALIDATION FUNCTIONS ==========
    
    function validateForm() {
        const firstName = firstNameInput.value.trim();
        const lastName = lastNameInput.value.trim();
        const phone = phoneInput.value.trim();
        const appointmentDate = appointmentDateInput.value.trim();
        const appointmentTime = appointmentTimeSelect.value.trim();
        const address = addressInput.value.trim();
        const city = cityInput.value.trim();
        const state = stateInput.value.trim();
        const country = countrySelect.value.trim();

        let isValid = true;

        if (!firstName || !lastName || !phone || !appointmentDate || !appointmentTime || 
            !address || !city || !state || !country) {
            isValid = false;
        }

        if (phone && !validatePhoneNumber(phone)) {
            isValid = false;
        }

        if (!selectedService) {
            isValid = false;
        }

        if (selectedService && hasPackages && !selectedPackage) {
            isValid = false;
        }

        submitBtn.disabled = !isValid;
        
        return isValid;
    }

    function validatePhoneNumber(phone) {
        const pattern = /^9[0-9]{9}$/;
        const isValid = pattern.test(phone);
        
        if (phone && !isValid) {
            phoneInput.classList.add('is-invalid');
            phoneError.textContent = 'Phone must start with 9 and be 10 digits total (e.g., 9123456789)';
            phoneError.style.display = 'block';
            return false;
        } else {
            phoneInput.classList.remove('is-invalid');
            phoneError.style.display = 'none';
            return true;
        }
    }

    // ========== ML PREDICTIONS ==========
    
    function generateMLPredictions() {
        mlPredictionPreview.style.display = 'block';
        
        const serviceType = selectedService.toLowerCase();
        let predictedDuration = 60;
        
        if (serviceType.includes('bridal')) {
            predictedDuration = selectedPackage ? (selectedPackage.price >= 8000 ? 120 : 90) : 90;
        } else if (serviceType.includes('civil')) {
            predictedDuration = 75;
        } else if (serviceType.includes('traditional')) {
            predictedDuration = serviceType.includes('+ hair') ? 75 : 60;
        } else if (serviceType.includes('debut')) {
            predictedDuration = selectedPackage ? (selectedPackage.price >= 4000 ? 120 : 90) : 90;
        } else {
            predictedDuration = 45;
        }
        
        predictedDuration += (selectedAddons.length * 15);
        
        const hour = parseInt(appointmentTimeSelect.value.split(':')[0]);
        if (hour >= 10 && hour <= 14) {
            predictedDuration = Math.round(predictedDuration * 1.1);
        }
        
        const totalWithBuffer = predictedDuration + TIME_BUFFER_MINUTES;
        
        const hours = Math.floor(totalWithBuffer / 60);
        const minutes = totalWithBuffer % 60;
        let durationDisplay = hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;
        
        let showUpScore = 0.85;
        if (serviceType.includes('bridal')) showUpScore += 0.10;
        if (hour < 12) showUpScore += 0.05;
        if (totalWithBuffer > 120) showUpScore += 0.05;
        
        showUpScore = Math.max(0.5, Math.min(0.95, showUpScore));
        const showUpPercentage = Math.round(showUpScore * 100);
        
        document.getElementById('predicted-duration-display').innerHTML = `
            ${predictedDuration}M Service + ${TIME_BUFFER_MINUTES} Extra Time = <strong>${durationDisplay}</strong> Total
        `;
        document.getElementById('show-up-percentage').textContent = showUpPercentage;
        
        document.getElementById('predicted-duration-input').value = predictedDuration;
        document.getElementById('predicted-no-show-input').value = (1 - showUpScore).toFixed(2);
        
        if (appointmentDateInput.value) {
            updateTimeSlotsBasedOnDate();
        }
        
        debugLog('ML Prediction:', predictedDuration, 'min service + buffer =', totalWithBuffer, 'min total');
    }

    function calculateTotal() {
        let total = basePrice;
        selectedAddons.forEach(addon => {
            total += addon.price;
        });
        return total;
    }

    function updateTotal() {
        const total = calculateTotal();
        if (total > 0) {
            estimatedTotal.textContent = `₱${total.toLocaleString()}`;
            totalSection.style.display = 'block';
        } else {
            totalSection.style.display = 'none';
        }
    }

    // ========== FORM SUBMISSION ==========
    
    appointmentForm.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            alert('Please fill in all required fields correctly.');
            return;
        }

        // Add hidden fields
        const clientNameInput = document.createElement('input');
        clientNameInput.type = 'hidden';
        clientNameInput.name = 'client_name';
        clientNameInput.value = clientNameDisplay.value;
        this.appendChild(clientNameInput);

        const fullPhoneInput = document.createElement('input');
        fullPhoneInput.type = 'hidden';
        fullPhoneInput.name = 'phone_full';
        fullPhoneInput.value = '+63' + phoneInput.value;
        this.appendChild(fullPhoneInput);

        if (selectedPackage) {
            document.getElementById('selected-package').value = selectedPackage.name;
        } else {
            document.getElementById('selected-package').value = selectedService;
        }

        document.getElementById('selected-addons').value = JSON.stringify(selectedAddons.map(a => a.name));
        document.getElementById('total-price').value = calculateTotal();
        
        // Calculate end time with buffer
        const appointmentDateTime = appointmentDateInput.value + ' ' + appointmentTimeSelect.value + ':00';
        const predictedDuration = parseInt(document.getElementById('predicted-duration-input').value) || 60;
        const startDate = new Date(appointmentDateTime);
        const endDate = new Date(startDate.getTime() + (predictedDuration + TIME_BUFFER_MINUTES) * 60000);
        
        const endTimeInput = document.createElement('input');
        endTimeInput.type = 'hidden';
        endTimeInput.name = 'appointment_end_time';
        endTimeInput.value = endDate.toISOString().slice(0, 19).replace('T', ' ');
        this.appendChild(endTimeInput);
        
        const bufferInput = document.createElement('input');
        bufferInput.type = 'hidden';
        bufferInput.name = 'time_buffer';
        bufferInput.value = TIME_BUFFER_MINUTES;
        this.appendChild(bufferInput);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Booking...';
    });

    // ========== INITIALIZATION ==========
    
    // Update client name
    [firstNameInput, middleNameInput, lastNameInput, suffixSelect].forEach(input => {
        input.addEventListener('input', function() {
            const firstName = firstNameInput.value.trim();
            const middleName = middleNameInput.value.trim();
            const lastName = lastNameInput.value.trim();
            const suffix = suffixSelect.value.trim();

            let fullName = '';
            if (firstName) fullName += firstName + ' ';
            if (middleName) fullName += middleName + ' ';
            if (lastName) fullName += lastName;
            if (suffix) fullName += ' ' + suffix;
            
            clientNameDisplay.value = fullName.trim();
            validateForm();
        });
    });

    // Phone validation
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 10) value = value.substring(0, 10);
        e.target.value = value;
        validatePhoneNumber(value);
        validateForm();
    });

    // Other validation
    firstNameInput.addEventListener('input', validateForm);
    lastNameInput.addEventListener('input', validateForm);
    addressInput.addEventListener('input', validateForm);
    cityInput.addEventListener('input', validateForm);
    stateInput.addEventListener('input', validateForm);
    countrySelect.addEventListener('change', validateForm);

    // Location validation
    addressInput.addEventListener('input', validateForm);
    cityInput.addEventListener('input', validateForm);
    stateInput.addEventListener('input', validateForm);
    countrySelect.addEventListener('change', validateForm);

    // Initialize
    debugLog('Booking system initialized');
});
</script>
@endsection 