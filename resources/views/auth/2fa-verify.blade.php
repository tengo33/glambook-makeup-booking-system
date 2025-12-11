<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - GlamBook</title>
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
        
        .verify-card {
            background: var(--crisp-white);
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(232, 180, 184, 0.2);
            overflow: hidden;
        }
        
        .verify-card::before {
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
        
        .btn-outline-luxe {
            background: transparent;
            color: var(--deep-rose);
            border: 2px solid var(--deep-rose);
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-luxe:hover {
            background: rgba(232, 180, 184, 0.1);
            color: var(--deep-rose);
        }
        
        .code-input {
            font-size: 2rem;
            letter-spacing: 0.5rem;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
        }
        
        .code-input:focus {
            border-color: var(--primary-rose);
            box-shadow: 0 0 0 0.2rem rgba(232, 180, 184, 0.25);
        }
        
        .timer {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--deep-rose);
            margin-top: 1rem;
        }
        
        .email-display {
            background: linear-gradient(135deg, rgba(232, 180, 184, 0.1) 0%, rgba(247, 235, 235, 0.1) 100%);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
            border-left: 4px solid var(--deep-rose);
        }
        
        .email-display strong {
            color: var(--deep-rose);
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
        }
        
        .alert-info {
            background: rgba(23, 162, 184, 0.1);
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="verify-card position-relative p-5">
                    <div class="brand-header">
                        <i class="fas fa-crown text-warning"></i>
                        Glam<span style="color: var(--deep-rose)">Book</span>
                    </div>
                    
                    <h2 class="text-center mb-3" style="font-family: 'Playfair Display', serif;">Verify Your Email</h2>
                    <p class="text-center text-muted mb-4">
                        Enter the 6-digit code sent to your email address
                    </p>
                    
                    <!-- Email Display -->
                    @if(session('email'))
                        <div class="email-display">
                            <i class="fas fa-envelope me-2"></i>
                            Code sent to: <strong>{{ session('email') }}</strong>
                        </div>
                    @endif
                    
                    <!-- Display Messages -->
                    @if(session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('2fa.verify') }}" id="verifyForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="code" class="form-label">
                                <i class="fas fa-shield-alt me-1"></i>6-Digit Verification Code
                            </label>
                            <input id="code" type="text" class="form-control code-input @error('code') is-invalid @enderror" 
                                   name="code" value="{{ old('code') }}" 
                                   required autofocus maxlength="6" pattern="[0-9]{6}"
                                   placeholder="000000">
                            <small class="text-muted mt-2 d-block">Enter the 6-digit code sent to your email</small>
                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <!-- Timer display -->
                        <div class="timer text-center mb-4">
                            <i class="fas fa-clock me-2"></i>
                            Code expires in: <span id="countdown">15:00</span>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-luxe btn-lg" id="verifyBtn">
                                <i class="fas fa-check-circle me-2"></i>
                                <span id="btnText">Verify Account</span>
                                <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                            </button>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('2fa.resend') }}" class="d-grid">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-luxe w-100">
                                        <i class="fas fa-redo me-2"></i>Resend Code
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('2fa.cancel') }}" class="btn btn-outline-secondary w-100" 
                                   onclick="return confirm('Are you sure you want to cancel registration?')">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p>Didn't receive the code?</p>
                            <ul class="list-unstyled">
                                <li>Check your spam/junk folder</li>
                                <li>Make sure you entered the correct email</li>
                                <li>Wait a few minutes and try resending</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const verifyForm = document.getElementById('verifyForm');
        const verifyBtn = document.getElementById('verifyBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const countdownElement = document.getElementById('countdown');
        const codeInput = document.getElementById('code');
        
        // Auto-format code input (add spaces for readability)
        codeInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 6) value = value.substring(0, 6);
            e.target.value = value;
        });
        
        // Auto-submit when 6 digits are entered
        codeInput.addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                verifyForm.submit();
                verifyBtn.disabled = true;
                btnText.textContent = 'Verifying...';
                btnSpinner.classList.remove('d-none');
            }
        });
        
        // Form submission handler
        verifyForm.addEventListener('submit', function(e) {
            // Validate code format
            const code = codeInput.value;
            if (code.length !== 6 || !/^\d+$/.test(code)) {
                e.preventDefault();
                alert('Please enter a valid 6-digit code');
                codeInput.focus();
                return false;
            }
            
            // Show loading state
            verifyBtn.disabled = true;
            btnText.textContent = 'Verifying...';
            btnSpinner.classList.remove('d-none');
            
            return true;
        });
        
        // Countdown timer (15 minutes)
        let timeLeft = 15 * 60; // 15 minutes in seconds
        
        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            countdownElement.textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                countdownElement.innerHTML = '<span class="text-danger">Code expired</span>';
                
                // Disable verify button
                verifyBtn.disabled = true;
                verifyBtn.classList.add('btn-secondary');
                verifyBtn.classList.remove('btn-luxe');
                btnText.textContent = 'Code Expired';
            }
            
            timeLeft--;
        }
        
        // Start countdown
        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial call
        
        // Auto-focus on code input
        codeInput.focus();
    });
    </script>
</body>
</html>