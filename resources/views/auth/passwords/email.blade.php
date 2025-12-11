@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4 fade-in" style="border-left: 4px solid #e8b4b8;">
                <div class="card-header text-white p-3" style="background: linear-gradient(135deg, #e8b4b8, #d8a1a6); border: none;">
                    <h4 class="mb-0"><i class="fas fa-unlock-alt me-2"></i>{{ __('Reset Password') }}</h4>
                </div>

                <div class="card-body p-4">
                    <!-- Success Message -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" 
                             style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border: none; border-left: 4px solid #28a745;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3" style="color: #28a745;"></i>
                                <div>
                                    <h5 class="alert-heading mb-1" style="color: #155724;">{{ __('Success!') }}</h5>
                                    <p class="mb-0" style="color: #155724;">{{ session('status') }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="card mb-4 border-0" style="background: linear-gradient(135deg, #fdf2f2, #f9f5f0);">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3" style="color: #d8a1a6;">
                                <i class="fas fa-info-circle me-2"></i>Forgot Your Password?
                            </h6>
                            <p class="mb-0">
                                No problem! Enter your email address below and we'll send you a password reset link. 
                                You'll have 60 minutes to reset your password before the link expires.
                            </p>
                        </div>
                    </div>

                    <!-- Security Note -->
                    <div class="alert alert-info mb-4 border-0" style="background: rgba(232, 180, 184, 0.1); border-left: 4px solid #e8b4b8;">
                        <div class="d-flex">
                            <i class="fas fa-shield-alt fa-lg me-3" style="color: #e8b4b8;"></i>
                            <div>
                                <h6 class="alert-heading mb-1" style="color: #d8a1a6;">Security Notice</h6>
                                <p class="mb-0 small">
                                    For security reasons, we can only send password reset links to verified email addresses. 
                                    If you don't receive an email within 5 minutes, please check your spam folder.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Form -->
                    <form method="POST" action="{{ route('password.email') }}" id="reset-form">
                        @csrf

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #d8a1a6;">
                                <i class="fas fa-envelope me-2"></i>{{ __('Email Address') }}
                                <span class="badge ms-2" style="background: #e8b4b8; color: white;">Required</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" style="background: #f9f5f0; border-color: #e8b4b8;">
                                    <i class="fas fa-at" style="color: #d8a1a6;"></i>
                                </span>
                                <input id="email" 
                                       type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus
                                       placeholder="Enter your registered email address"
                                       onkeyup="validateEmail()">
                            </div>
                            
                            <!-- Email Validation Feedback -->
                            <div class="mt-2">
                                <div id="email-validation" style="display: none;">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    <span id="email-validation-text" class="small"></span>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block mt-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-lg px-4" 
                                    style="background: linear-gradient(135deg, #e8b4b8, #d8a1a6); 
                                           color: white; border: none;" 
                                    id="submit-btn">
                                <i class="fas fa-paper-plane me-2"></i>{{ __('Send Password Reset Link') }}
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>

                        <!-- Resend Instructions -->
                        <div class="text-center mt-4">
                            <p class="small text-muted mb-2">
                                <i class="fas fa-clock me-1"></i>Reset link expires in 60 minutes
                            </p>
                            <p class="small text-muted">
                                Didn't receive the email? 
                                <a href="#" class="text-decoration-none" style="color: #d8a1a6;" id="resend-link">
                                    <i class="fas fa-redo me-1"></i>Resend reset link
                                </a>
                            </p>
                        </div>
                    </form>

                    <!-- Troubleshooting Tips -->
                    <div class="card mt-4 border-0" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3" style="color: #d8a1a6;">
                                <i class="fas fa-question-circle me-2"></i>Troubleshooting Tips
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex mb-3">
                                        <i class="fas fa-search me-3" style="color: #e8b4b8;"></i>
                                        <div>
                                            <small class="fw-semibold d-block">Check Spam Folder</small>
                                            <small class="text-muted">Sometimes emails end up in spam/junk folders</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex mb-3">
                                        <i class="fas fa-envelope-open me-3" style="color: #e8b4b8;"></i>
                                        <div>
                                            <small class="fw-semibold d-block">Verify Email Address</small>
                                            <small class="text-muted">Make sure you're using the correct email</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex mb-3">
                                        <i class="fas fa-sync-alt me-3" style="color: #e8b4b8;"></i>
                                        <div>
                                            <small class="fw-semibold d-block">Wait 5 Minutes</small>
                                            <small class="text-muted">Email delivery can sometimes be delayed</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="fas fa-headset me-3" style="color: #e8b4b8;"></i>
                                        <div>
                                            <small class="fw-semibold d-block">Need Help?</small>
                                            <small class="text-muted">Contact support@glambook.com</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(232, 180, 184, 0.1);
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
    
    .badge {
        padding: 4px 8px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .alert {
        border-radius: 8px;
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-10px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    #submit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Email validation function
    function validateEmail() {
        const emailInput = document.getElementById('email');
        const email = emailInput.value.trim();
        const validationDiv = document.getElementById('email-validation');
        const validationText = document.getElementById('email-validation-text');
        const submitBtn = document.getElementById('submit-btn');
        
        // Basic email regex pattern
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            validationDiv.style.display = 'none';
            submitBtn.disabled = true;
            return false;
        }
        
        if (emailPattern.test(email)) {
            validationDiv.style.display = 'block';
            validationDiv.querySelector('i').className = 'fas fa-check-circle text-success me-1';
            validationText.textContent = 'Valid email format';
            validationText.className = 'small text-success';
            submitBtn.disabled = false;
            return true;
        } else {
            validationDiv.style.display = 'block';
            validationDiv.querySelector('i').className = 'fas fa-times-circle text-danger me-1';
            validationText.textContent = 'Please enter a valid email address';
            validationText.className = 'small text-danger';
            submitBtn.disabled = true;
            return false;
        }
    }
    
    // Resend link functionality
    const resendLink = document.getElementById('resend-link');
    let resendCount = 0;
    const maxResends = 3;
    
    resendLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        resendCount++;
        
        if (resendCount > maxResends) {
            alert('You have reached the maximum number of resend attempts. Please try again later or contact support.');
            return;
        }
        
        const email = document.getElementById('email').value;
        
        if (!email || !validateEmail()) {
            alert('Please enter a valid email address before requesting a resend.');
            document.getElementById('email').focus();
            return;
        }
        
        // Show loading state
        const originalText = resendLink.innerHTML;
        resendLink.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending...';
        resendLink.style.pointerEvents = 'none';
        
        // Simulate API call
        setTimeout(() => {
            // In a real app, you would make an AJAX call to resend
            alert(`Reset link has been resent to ${email}. Please check your inbox.`);
            
            // Reset button
            resendLink.innerHTML = originalText;
            resendLink.style.pointerEvents = 'auto';
            
            // Update message
            const remaining = maxResends - resendCount;
            if (remaining > 0) {
                resendLink.innerHTML = `<i class="fas fa-redo me-1"></i>Resend (${remaining} attempts remaining)`;
            } else {
                resendLink.innerHTML = '<i class="fas fa-ban me-1"></i>No more resends';
                resendLink.style.color = '#6c757d';
                resendLink.style.pointerEvents = 'none';
            }
        }, 1000);
    });
    
    // Form submission handler
    const resetForm = document.getElementById('reset-form');
    const submitBtn = document.getElementById('submit-btn');
    
    resetForm.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        
        if (!email || !validateEmail()) {
            e.preventDefault();
            alert('Please enter a valid email address.');
            return false;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
        
        // Add a small delay to show loading state
        setTimeout(() => {
            // Form will submit normally
        }, 500);
        
        return true;
    });
    
    // Initialize validation on page load
    validateEmail();
});
</script>
@endsection