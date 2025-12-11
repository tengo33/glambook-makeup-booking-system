<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GlamBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-rose: #e8b4b8;
            --deep-rose: #d8a1a6;
            --soft-cream: #f9f5f0;
            --warm-gold: #d4af37;
            --charcoal: #2c2c2c;
            --crisp-white: #ffffff;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--soft-cream) 0%, #fef9f7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        
        .register-card {
            background: var(--crisp-white);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(232, 180, 184, 0.2);
            overflow: hidden;
        }
        
        .register-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-rose), var(--warm-gold), var(--primary-rose));
        }
        
        .brand-header {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .btn-luxe {
            background: linear-gradient(135deg, var(--primary-rose) 0%, var(--deep-rose) 100%);
            color: var(--crisp-white);
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-luxe:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(232, 180, 184, 0.4);
            color: var(--crisp-white);
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-rose);
            box-shadow: 0 0 0 0.2rem rgba(232, 180, 184, 0.25);
        }
        
        /* Name fields grid */
        .name-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .name-grid .form-group {
            margin-bottom: 0;
        }
        
        .name-grid .full-width {
            grid-column: span 2;
        }
        
        /* Security info box */
        .security-info {
            background: linear-gradient(135deg, rgba(232, 180, 184, 0.1) 0%, rgba(247, 235, 235, 0.1) 100%);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--deep-rose);
        }
        
        .security-info h6 {
            color: var(--deep-rose);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .security-info p {
            color: var(--charcoal);
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }
        
        .security-info ul {
            padding-left: 1.2rem;
            margin-bottom: 0;
        }
        
        .security-info li {
            font-size: 0.9rem;
            color: var(--charcoal);
            margin-bottom: 0.3rem;
        }
        
        /* Password strength indicator */
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
        
        .strength-weak {
            color: #dc3545;
        }
        
        .strength-medium {
            color: #fd7e14;
        }
        
        .strength-strong {
            color: #28a745;
        }
        
        .password-requirements {
            background-color: rgba(232, 180, 184, 0.05);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
        
        .password-requirements ul {
            padding-left: 1.2rem;
            margin-bottom: 0;
        }
        
        .password-requirements li {
            margin-bottom: 0.25rem;
            color: #666;
        }
        
        .requirement-met {
            color: #28a745;
        }
        
        .requirement-unmet {
            color: #dc3545;
        }
        
        /* Alert styling */
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
        }
        
        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
        }
        
        /* Form validation */
        .is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            font-size: 0.85rem;
            margin-top: 0.3rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .name-grid {
                grid-template-columns: 1fr;
            }
            
            .name-grid .full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="register-card position-relative p-5">
                    <div class="brand-header">
                        <i class="fas fa-crown text-warning"></i>
                        Glam<span style="color: var(--deep-rose)">Book</span>
                    </div>
                    
                    <h2 class="text-center mb-3" style="font-family: 'Playfair Display', serif;">Create Account</h2>
                    <p class="text-center text-muted mb-4">
                        Join our community of professional makeup artists
                    </p>
                    
                    <!-- Display Messages -->
                    @if(session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif
                    
                    <!-- 2FA Redirection Message -->
                    @if(session('verification_sent'))
                        <div class="alert alert-success">
                            <i class="fas fa-shield-alt me-2"></i>
                            A verification code has been sent to your email. Please check your inbox and enter the code to complete registration.
                        </div>
                    @endif
                    
                    <!-- Security Information -->
                    <div class="security-info">
                        <h6><i class="fas fa-shield-alt me-2"></i>Secure Registration Process</h6>
                        <p class="mb-2">For your security, we use 2-factor authentication:</p>
                        <ul>
                            <li>Register with your email</li>
                            <li>Receive a 6-digit verification code via email</li>
                            <li>Enter code to verify your account</li>
                            <li>Automatic login after verification</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <!-- Name Fields Grid -->
                        <div class="name-grid">
                            <div class="form-group">
                                <label for="first_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>First Name *
                                </label>
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       name="first_name" value="{{ old('first_name') }}" 
                                       required autocomplete="given-name" autofocus
                                       placeholder="John">
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="last_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Last Name *
                                </label>
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       name="last_name" value="{{ old('last_name') }}" 
                                       required autocomplete="family-name"
                                       placeholder="Doe">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="middle_name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Middle Name
                                </label>
                                <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                       name="middle_name" value="{{ old('middle_name') }}" 
                                       autocomplete="additional-name"
                                       placeholder="Michael (Optional)">
                                @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="suffix" class="form-label">
                                    <i class="fas fa-user-tag me-1"></i>Suffix
                                </label>
                                <select id="suffix" class="form-control @error('suffix') is-invalid @enderror" 
                                        name="suffix" autocomplete="honorific-suffix">
                                    <option value="">Select Suffix</option>
                                    <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>IV</option>
                                    <option value="V" {{ old('suffix') == 'V' ? 'selected' : '' }}>V</option>
                                    <option value="Ph.D." {{ old('suffix') == 'Ph.D.' ? 'selected' : '' }}>Ph.D.</option>
                                    <option value="M.D." {{ old('suffix') == 'M.D.' ? 'selected' : '' }}>M.D.</option>
                                    <option value="Esq." {{ old('suffix') == 'Esq.' ? 'selected' : '' }}>Esq.</option>
                                </select>
                                @error('suffix')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Full name preview -->
                        <div class="mb-3">
                            <div class="form-label">Full Name Preview</div>
                            <div id="fullNamePreview" class="p-3 border rounded" style="background-color: #f8f9fa; font-style: italic;">
                                {{ old('first_name') ? old('first_name') . ' ' . (old('middle_name') ? old('middle_name') . ' ' : '') . old('last_name') . ' ' . old('suffix') : 'Your name will appear here...' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>Email Address *
                            </label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" 
                                   required autocomplete="email"
                                   placeholder="your.email@example.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Password *
                            </label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="new-password"
                                   placeholder="Create a strong password">
                            
                            <!-- Password requirements -->
                            <div class="password-requirements">
                                <small class="d-block mb-2"><strong>For a strong password, include:</strong></small>
                                <ul class="mb-2">
                                    <li id="req-length" class="requirement-unmet">At least 8 characters</li>
                                    <li id="req-uppercase" class="requirement-unmet">One uppercase letter (A-Z)</li>
                                    <li id="req-lowercase" class="requirement-unmet">One lowercase letter (a-z)</li>
                                    <li id="req-number" class="requirement-unmet">One number (0-9)</li>
                                    <li id="req-symbol" class="requirement-unmet">One symbol (!@#$%^&*)</li>
                                </ul>
                                <small class="text-muted">Tip: Mix uppercase, lowercase, numbers, and symbols for maximum security</small>
                            </div>
                            
                            <!-- Password strength indicator -->
                            <div class="password-strength">
                                <span id="strengthText">Strength: </span>
                                <span id="strengthLevel" class="fw-bold">None</span>
                            </div>
                            
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">
                                <i class="fas fa-lock me-1"></i>Confirm Password *
                            </label>
                            <input id="password-confirm" type="password" class="form-control" 
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Re-enter your password">
                            <div id="passwordMatch" class="mt-1"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-luxe btn-lg" id="registerBtn">
                                <i class="fas fa-user-plus me-2"></i>
                                <span id="btnText">Register Now</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p>Already have an account? 
                                <a href="{{ route('login') }}" style="color: var(--deep-rose); text-decoration: none;">
                                    <strong><i class="fas fa-sign-in-alt me-1"></i>Sign in here</strong>
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const registerForm = document.getElementById('registerForm');
        const registerBtn = document.getElementById('registerBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        
        // Name field elements
        const firstName = document.getElementById('first_name');
        const middleName = document.getElementById('middle_name');
        const lastName = document.getElementById('last_name');
        const suffix = document.getElementById('suffix');
        const fullNamePreview = document.getElementById('fullNamePreview');
        
        // Password elements
        const passwordInput = document.getElementById('password');
        const passwordConfirm = document.getElementById('password-confirm');
        const passwordMatch = document.getElementById('passwordMatch');
        
        // Password requirement elements
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqLowercase = document.getElementById('req-lowercase');
        const reqNumber = document.getElementById('req-number');
        const reqSymbol = document.getElementById('req-symbol');
        const strengthText = document.getElementById('strengthText');
        const strengthLevel = document.getElementById('strengthLevel');
        
        // Update full name preview
        function updateFullNamePreview() {
            let fullName = firstName.value || '';
            
            if (middleName.value) {
                fullName += ' ' + middleName.value;
            }
            
            if (lastName.value) {
                fullName += ' ' + lastName.value;
            }
            
            if (suffix.value) {
                fullName += ' ' + suffix.value;
            }
            
            fullNamePreview.textContent = fullName || 'Your name will appear here...';
        }
        
        // Check password strength
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Check requirements
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSymbol = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
            
            // Update requirement indicators
            updateRequirement(reqLength, hasLength);
            updateRequirement(reqUppercase, hasUppercase);
            updateRequirement(reqLowercase, hasLowercase);
            updateRequirement(reqNumber, hasNumber);
            updateRequirement(reqSymbol, hasSymbol);
            
            // Calculate strength score
            if (hasLength) strength++;
            if (hasUppercase) strength++;
            if (hasLowercase) strength++;
            if (hasNumber) strength++;
            if (hasSymbol) strength++;
            
            // Determine strength level
            let level = 'None';
            let color = '#6c757d';
            let className = '';
            
            if (password.length === 0) {
                level = 'None';
                color = '#6c757d';
                className = '';
            } else if (strength <= 2) {
                level = 'Weak';
                color = '#dc3545';
                className = 'strength-weak';
            } else if (strength <= 3) {
                level = 'Fair';
                color = '#fd7e14';
                className = 'strength-medium';
            } else if (strength === 4) {
                level = 'Good';
                color = '#ffc107';
                className = 'strength-medium';
            } else {
                level = 'Strong';
                color = '#28a745';
                className = 'strength-strong';
            }
            
            // Update display
            strengthLevel.textContent = level;
            strengthLevel.className = `fw-bold ${className}`;
            strengthLevel.style.color = color;
            strengthText.style.color = color;
        }
        
        // Update requirement indicator
        function updateRequirement(element, met) {
            if (met) {
                element.classList.remove('requirement-unmet');
                element.classList.add('requirement-met');
                element.innerHTML = '<i class="fas fa-check-circle me-1"></i>' + element.textContent.replace('✓ ', '').replace('✗ ', '');
            } else {
                element.classList.remove('requirement-met');
                element.classList.add('requirement-unmet');
                element.innerHTML = '<i class="fas fa-times-circle me-1"></i>' + element.textContent.replace('✓ ', '').replace('✗ ', '');
            }
        }
        
        // Check if passwords match
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = passwordConfirm.value;
            
            if (confirmPassword.length === 0) {
                passwordMatch.innerHTML = '';
                return;
            }
            
            if (password === confirmPassword) {
                passwordMatch.innerHTML = '<small class="text-success"><i class="fas fa-check-circle me-1"></i>Passwords match</small>';
            } else {
                passwordMatch.innerHTML = '<small class="text-danger"><i class="fas fa-times-circle me-1"></i>Passwords do not match</small>';
            }
        }
        
        // Event listeners for name fields
        firstName.addEventListener('input', updateFullNamePreview);
        middleName.addEventListener('input', updateFullNamePreview);
        lastName.addEventListener('input', updateFullNamePreview);
        suffix.addEventListener('change', updateFullNamePreview);
        
        // Event listeners for password
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
        
        passwordConfirm.addEventListener('input', checkPasswordMatch);
        
        // Form submission handler
        registerForm.addEventListener('submit', function(e) {
            // Validate passwords match
            if (passwordInput.value !== passwordConfirm.value) {
                e.preventDefault();
                passwordConfirm.focus();
                passwordMatch.innerHTML = '<small class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Passwords do not match. Please fix before submitting.</small>';
                return false;
            }
            
            // Validate password strength (optional, but recommended)
            const password = passwordInput.value;
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            
            if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber) {
                const shouldSubmit = confirm('Your password is weak. For better security, we recommend using uppercase letters, lowercase letters, and numbers. Do you want to continue anyway?');
                if (!shouldSubmit) {
                    e.preventDefault();
                    passwordInput.focus();
                    return false;
                }
            }
            
            // Show loading state
            registerBtn.disabled = true;
            btnText.textContent = 'Creating Account...';
            btnSpinner.classList.remove('d-none');
            
            // Allow form to submit normally
            return true;
        });
        
        // Auto-focus on first error field
        @if($errors->has('first_name'))
            document.getElementById('first_name').focus();
        @elseif($errors->has('last_name'))
            document.getElementById('last_name').focus();
        @elseif($errors->has('email'))
            document.getElementById('email').focus();
        @elseif($errors->has('password'))
            document.getElementById('password').focus();
        @endif
        
        // Initialize password strength check
        checkPasswordStrength(passwordInput.value);
        updateFullNamePreview();
    });
    </script>
</body>
</html>