@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4 fade-in" style="border-left: 4px solid #e8b4b8;">
                <div class="card-header text-white p-3" style="background: linear-gradient(135deg, #e8b4b8, #d8a1a6); border: none;">
                    <h4 class="mb-0"><i class="fas fa-key me-2"></i>{{ __('Reset Password') }}</h4>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        <i class="fas fa-info-circle me-2"></i>Please enter your email address and choose a new password for your GlamBook account.
                    </p>

                    <form method="POST" action="{{ route('password.update') }}" id="reset-password-form">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold" style="color: #d8a1a6;">
                                <i class="fas fa-envelope me-2"></i>{{ __('Email Address') }}
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" style="background: #f9f5f0; border-color: #e8b4b8;">
                                    <i class="fas fa-at" style="color: #d8a1a6;"></i>
                                </span>
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ $email ?? old('email') }}" 
                                       required 
                                       autocomplete="email" 
                                       autofocus
                                       placeholder="your.email@example.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- New Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold" style="color: #d8a1a6;">
                                <i class="fas fa-lock me-2"></i>{{ __('New Password') }}
                                <span class="badge ms-2" style="background: #e8b4b8; color: white;">Required</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" style="background: #f9f5f0; border-color: #e8b4b8;">
                                    <i class="fas fa-key" style="color: #d8a1a6;"></i>
                                </span>
                                <input id="password" 
                                       type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Create a strong password"
                                       onkeyup="checkPasswordStrength()">
                                <button class="btn" 
                                        type="button" 
                                        id="togglePassword"
                                        style="background: #e8b4b8; border-color: #d8a1a6; color: white;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Password Strength Meter -->
                            <div class="mt-3">
                                <div class="password-strength-meter">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strength-fill"></div>
                                    </div>
                                    <div class="strength-label mt-2">
                                        <span id="strength-text">Password Strength</span>
                                        <span id="strength-score" class="badge">0/4</span>
                                    </div>
                                </div>
                                
                                <!-- Password Requirements -->
                                <div class="password-requirements mt-3">
                                    <h6 class="fw-semibold mb-2" style="color: #d8a1a6;">
                                        <i class="fas fa-list-check me-2"></i>Password Requirements
                                    </h6>
                                    <ul class="list-unstyled" id="password-rules">
                                        <li class="mb-1" id="rule-length">
                                            <i class="fas fa-circle me-2" style="font-size: 0.5rem;"></i>
                                            At least 8 characters
                                        </li>
                                        <li class="mb-1" id="rule-uppercase">
                                            <i class="fas fa-circle me-2" style="font-size: 0.5rem;"></i>
                                            One uppercase letter
                                        </li>
                                        <li class="mb-1" id="rule-lowercase">
                                            <i class="fas fa-circle me-2" style="font-size: 0.5rem;"></i>
                                            One lowercase letter
                                        </li>
                                        <li class="mb-1" id="rule-number">
                                            <i class="fas fa-circle me-2" style="font-size: 0.5rem;"></i>
                                            One number
                                        </li>
                                        <li class="mb-1" id="rule-special">
                                            <i class="fas fa-circle me-2" style="font-size: 0.5rem;"></i>
                                            One special character (!@#$%^&*)
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            @error('password')
                                <div class="invalid-feedback d-block mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold" style="color: #d8a1a6;">
                                <i class="fas fa-lock me-2"></i>{{ __('Confirm Password') }}
                                <span class="badge ms-2" style="background: #e8b4b8; color: white;">Required</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text" style="background: #f9f5f0; border-color: #e8b4b8;">
                                    <i class="fas fa-key" style="color: #d8a1a6;"></i>
                                </span>
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-control" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Re-enter your new password"
                                       onkeyup="checkPasswordMatch()">
                                <button class="btn" 
                                        type="button" 
                                        id="toggleConfirmPassword"
                                        style="background: #e8b4b8; border-color: #d8a1a6; color: white;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Password Match Indicator -->
                            <div class="mt-2">
                                <span id="password-match-message" style="display: none;">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    <span id="match-text"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Security Notes -->
                        <div class="card mb-4 border-0" style="background: linear-gradient(135deg, #fdf2f2, #f9f5f0);">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3" style="color: #d8a1a6;">
                                    <i class="fas fa-shield-alt me-2"></i>Security Tips
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-lightbulb me-2" style="color: #e8b4b8;"></i>
                                        <strong>Use a unique password</strong> - Don't reuse passwords from other sites
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-lightbulb me-2" style="color: #e8b4b8;"></i>
                                        <strong>Consider a passphrase</strong> - Use multiple words for better security
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-lightbulb me-2" style="color: #e8b4b8;"></i>
                                        <strong>Change regularly</strong> - Update your password every 3-6 months
                                    </li>
                                    <li>
                                        <i class="fas fa-lightbulb me-2" style="color: #e8b4b8;"></i>
                                        <strong>Use 2FA</strong> - Enable Two-Factor Authentication for extra security
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-lg px-4" 
                                    style="background: linear-gradient(135deg, #e8b4b8, #d8a1a6); 
                                           color: white; border: none;" 
                                    id="submit-btn">
                                <i class="fas fa-key me-2"></i>{{ __('Reset Password') }}
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .password-strength-meter {
        background: #f9f5f0;
        border-radius: 8px;
        padding: 15px;
        border: 1px solid #f0f0f0;
    }
    
    .strength-bar {
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }
    
    .strength-fill {
        height: 100%;
        width: 0%;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .strength-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 500;
    }
    
    .password-requirements ul li {
        padding: 4px 0;
        font-size: 0.9rem;
        color: #666;
        transition: all 0.3s ease;
    }
    
    .password-requirements ul li.valid {
        color: #28a745;
    }
    
    .password-requirements ul li.valid i {
        color: #28a745;
    }
    
    .password-requirements ul li.invalid {
        color: #dc3545;
    }
    
    .password-requirements ul li.invalid i {
        color: #dc3545;
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
    
    #submit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password-confirm');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
    
    // Initialize form validation
    checkPasswordStrength();
    checkPasswordMatch();
});

function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');
    const strengthScore = document.getElementById('strength-score');
    const submitBtn = document.getElementById('submit-btn');
    
    let score = 0;
    let strength = 'Very Weak';
    let color = '#dc3545'; // Red
    
    // Check password rules
    const hasLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*]/.test(password);
    
    // Update rule indicators
    updateRuleIndicator('rule-length', hasLength);
    updateRuleIndicator('rule-uppercase', hasUppercase);
    updateRuleIndicator('rule-lowercase', hasLowercase);
    updateRuleIndicator('rule-number', hasNumber);
    updateRuleIndicator('rule-special', hasSpecial);
    
    // Calculate score
    if (hasLength) score++;
    if (hasUppercase) score++;
    if (hasLowercase) score++;
    if (hasNumber) score++;
    if (hasSpecial) score++;
    
    // Determine strength level
    if (score === 0) {
        strength = 'Very Weak';
        color = '#dc3545';
        width = '20%';
    } else if (score === 1) {
        strength = 'Weak';
        color = '#ff6b6b';
        width = '40%';
    } else if (score === 2) {
        strength = 'Fair';
        color = '#ffd93d';
        width = '60%';
    } else if (score === 3) {
        strength = 'Good';
        color = '#6bcf7f';
        width = '80%';
    } else if (score >= 4) {
        strength = 'Strong';
        color = '#28a745';
        width = '100%';
    }
    
    // Update UI
    strengthFill.style.width = width;
    strengthFill.style.backgroundColor = color;
    strengthText.textContent = strength;
    strengthScore.textContent = `${score}/5`;
    strengthScore.style.backgroundColor = color;
    
    // Enable/disable submit button based on password strength
    const isPasswordStrong = score >= 3; // At least "Good" strength
    submitBtn.disabled = !isPasswordStrong;
    
    return score;
}

function updateRuleIndicator(elementId, isValid) {
    const element = document.getElementById(elementId);
    const icon = element.querySelector('i');
    
    if (isValid) {
        element.classList.add('valid');
        element.classList.remove('invalid');
        icon.className = 'fas fa-check-circle me-2';
        icon.style.color = '#28a745';
    } else {
        element.classList.add('invalid');
        element.classList.remove('valid');
        icon.className = 'fas fa-times-circle me-2';
        icon.style.color = '#dc3545';
    }
}

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password-confirm').value;
    const matchMessage = document.getElementById('password-match-message');
    const matchText = document.getElementById('match-text');
    
    if (!password || !confirmPassword) {
        matchMessage.style.display = 'none';
        return;
    }
    
    if (password === confirmPassword) {
        matchMessage.style.display = 'inline-block';
        matchText.textContent = 'Passwords match';
        matchMessage.style.color = '#28a745';
        matchMessage.querySelector('i').className = 'fas fa-check-circle me-1';
        matchMessage.querySelector('i').style.color = '#28a745';
    } else {
        matchMessage.style.display = 'inline-block';
        matchText.textContent = 'Passwords do not match';
        matchMessage.style.color = '#dc3545';
        matchMessage.querySelector('i').className = 'fas fa-times-circle me-1';
        matchMessage.querySelector('i').style.color = '#dc3545';
    }
}

// Form submission validation
document.getElementById('reset-password-form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password-confirm').value;
    const strengthScore = checkPasswordStrength();
    
    // Check if passwords match
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match. Please make sure both passwords are identical.');
        document.getElementById('password-confirm').focus();
        return false;
    }
    
    // Check password strength
    if (strengthScore < 3) {
        e.preventDefault();
        alert('Please use a stronger password. Your password should be at least "Good" strength.');
        document.getElementById('password').focus();
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Resetting Password...';
    
    return true;
});
</script>
@endsection