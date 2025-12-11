<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GlamBook Makeup Appointment System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-pink: #d63384;
            --primary-pink-light: #ff8fa3;
            --primary-pink-lighter: #fff5f7;
            --gradient-pink: linear-gradient(135deg, #ff8fa3 0%, #d63384 100%);
            --gradient-pink-soft: linear-gradient(135deg, #fff5f7 0%, #ffeef2 100%);
            --gradient-success: linear-gradient(135deg, #74c69d 0%, #40916c 100%);
            --shadow-soft: 0 10px 30px rgba(214, 51, 132, 0.15);
            --shadow-hard: 0 20px 50px rgba(214, 51, 132, 0.25);
            --radius-lg: 20px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', 'Arial', sans-serif;
            background: var(--gradient-pink-soft);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animated background elements */
        body::before, body::after {
            content: '';
            position: fixed;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 143, 163, 0.1) 0%, rgba(255, 143, 163, 0) 70%);
            z-index: -1;
        }
        
        body::before {
            top: -100px;
            left: -100px;
            animation: float 20s infinite ease-in-out;
        }
        
        body::after {
            bottom: -100px;
            right: -100px;
            animation: float 25s infinite ease-in-out reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        .login-container {
            width: 100%;
            max-width: 440px;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 48px 40px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-hard);
            border: 1px solid rgba(255, 182, 193, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient-pink);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-pink);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(214, 51, 132, 0.2);
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }
        
        .logo-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.2) 50%, transparent 70%);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .logo-icon i {
            font-size: 36px;
            color: white;
        }
        
        .logo h1 {
            color: var(--primary-pink);
            font-size: 28px;
            margin: 0 0 8px 0;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .logo p {
            color: var(--primary-pink-light);
            font-size: 15px;
            margin: 0;
            font-weight: 500;
            opacity: 0.8;
        }
        
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--primary-pink);
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-group label i {
            font-size: 14px;
            opacity: 0.7;
        }
        
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-wrapper i {
            position: absolute;
            left: 16px;
            color: var(--primary-pink-light);
            font-size: 18px;
            z-index: 1;
            transition: var(--transition-smooth);
        }
        
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 16px 52px 16px 48px;
            border: 2px solid rgba(255, 182, 193, 0.4);
            border-radius: var(--radius-md);
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
            transition: var(--transition-smooth);
            color: #333;
            font-weight: 500;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 4px rgba(214, 51, 132, 0.1);
            background: white;
        }
        
        input[type="email"]:focus + i,
        input[type="password"]:focus + i,
        input[type="text"]:focus + i {
            color: var(--primary-pink);
        }
        
        /* Password Toggle Icon */
        .password-toggle {
            position: absolute;
            right: 16px;
            background: none;
            border: none;
            color: var(--primary-pink-light);
            cursor: pointer;
            padding: 8px;
            font-size: 18px;
            opacity: 0.7;
            transition: var(--transition-smooth);
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        
        .password-toggle:hover {
            opacity: 1;
            background: rgba(214, 51, 132, 0.05);
            transform: scale(1.05);
        }
        
        .login-btn {
            width: 100%;
            padding: 18px;
            background: var(--gradient-pink);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition-smooth);
            margin: 10px 0 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(214, 51, 132, 0.3);
        }
        
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s;
        }
        
        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(214, 51, 132, 0.4);
        }
        
        .login-btn:hover::before {
            left: 100%;
        }
        
        .login-btn:active {
            transform: translateY(-1px);
        }
        
        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: 0 4px 10px rgba(214, 51, 132, 0.2);
        }
        
        /* Account Locked Styles */
        .account-locked {
            background: linear-gradient(135deg, #ff6b6b 0%, #c92a2a 100%);
            pointer-events: none;
        }
        
        /* Lock Timer Display */
        .lock-timer {
            text-align: center;
            color: #dc3545;
            font-size: 14px;
            margin: 20px 0;
            font-weight: 600;
            padding: 16px;
            background: #fdf2f4;
            border-radius: var(--radius-md);
            border-left: 4px solid #dc3545;
            display: none;
            align-items: center;
            justify-content: center;
            gap: 10px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.2); }
            50% { box-shadow: 0 0 0 8px rgba(220, 53, 69, 0); }
        }
        
        .lock-timer i {
            font-size: 18px;
        }
        
        /* Attempts Counter */
        .attempts-warning {
            font-size: 13px;
            color: #dc3545;
            margin-top: 8px;
            text-align: right;
            display: none;
            font-weight: 500;
            padding: 8px 12px;
            background: rgba(220, 53, 69, 0.05);
            border-radius: var(--radius-sm);
            border-left: 3px solid #dc3545;
        }
        
        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 22px;
            height: 22px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Status Messages */
        .alert {
            padding: 16px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideIn 0.4s ease-out;
            position: relative;
            overflow: hidden;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .alert::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
        }
        
        .alert-error {
            background: #fdf2f4;
            color: #721c24;
            border: 1px solid #f8d7da;
        }
        
        .alert-error::before {
            background: #dc3545;
        }
        
        .alert-success {
            background: #f0f9ff;
            color: #0c5460;
            border: 1px solid #d1edff;
        }
        
        .alert-success::before {
            background: #0dcaf0;
        }
        
        .alert-warning {
            background: #fffcf2;
            color: #856404;
            border: 1px solid #fff3cd;
        }
        
        .alert-warning::before {
            background: #ffc107;
        }
        
        .alert i {
            font-size: 18px;
            margin-top: 1px;
        }
        
        /* Forgot Password Styles */
        .forgot-password {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .forgot-password-btn {
            background: transparent;
            border: none;
            color: #6c757d;
            font-size: 14px;
            cursor: pointer;
            padding: 10px 0;
            transition: var(--transition-smooth);
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .forgot-password-btn:hover {
            color: var(--primary-pink);
        }
        
        .forgot-password-btn i {
            font-size: 12px;
        }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 30px 0;
            color: #adb5bd;
            font-size: 14px;
            font-weight: 500;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255, 182, 193, 0.4);
        }
        
        .divider-text {
            padding: 0 20px;
            color: var(--primary-pink);
            font-size: 14px;
            font-weight: 600;
        }
        
        /* Register Section Styles */
        .register-section {
            text-align: center;
            padding: 28px 24px;
            background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
            border-radius: var(--radius-md);
            margin-top: 30px;
            border: 1px solid rgba(255, 182, 193, 0.2);
            transition: var(--transition-smooth);
        }
        
        .register-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
        
        .register-title {
            color: var(--primary-pink);
            margin-bottom: 12px;
            font-size: 20px;
            font-weight: 700;
        }
        
        .register-text {
            color: #6c757d;
            margin-bottom: 20px;
            font-size: 15px;
            line-height: 1.5;
        }
        
        .register-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: var(--gradient-success);
            color: white;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 700;
            transition: var(--transition-smooth);
            border: none;
            cursor: pointer;
            font-size: 15px;
            box-shadow: 0 6px 15px rgba(116, 198, 157, 0.3);
        }
        
        .register-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(116, 198, 157, 0.4);
            color: white;
            text-decoration: none;
        }
        
        .register-btn i {
            font-size: 16px;
        }
        
        /* Modal Styles for Forgot Password */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-content {
            background: white;
            padding: 40px;
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 440px;
            box-shadow: var(--shadow-hard);
            border: 1px solid rgba(255, 182, 193, 0.3);
            animation: modalSlideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-60px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient-pink);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        }
        
        .modal-title {
            color: var(--primary-pink);
            margin-bottom: 24px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
        }
        
        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }
        
        .modal-btn {
            flex: 1;
            padding: 16px;
            border: none;
            border-radius: var(--radius-md);
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition-smooth);
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .modal-btn-primary {
            background: var(--gradient-pink);
            color: white;
            box-shadow: 0 6px 15px rgba(214, 51, 132, 0.3);
        }
        
        .modal-btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }
        
        .modal-btn:hover {
            transform: translateY(-3px);
        }
        
        .modal-btn-primary:hover {
            box-shadow: 0 10px 25px rgba(214, 51, 132, 0.4);
        }
        
        .modal-btn-secondary:hover {
            background: #e9ecef;
        }
        
        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(214, 51, 132, 0.1);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-pink);
            cursor: pointer;
            transition: var(--transition-smooth);
            font-size: 20px;
            font-weight: bold;
        }
        
        .close-modal:hover {
            background: rgba(214, 51, 132, 0.2);
            transform: rotate(90deg);
        }
        
        /* Cooldown Progress Bar */
        .cooldown-bar {
            width: 100%;
            height: 6px;
            background: rgba(233, 236, 239, 0.5);
            border-radius: 3px;
            margin: 10px 0 20px;
            overflow: hidden;
            display: none;
        }
        
        .cooldown-progress {
            height: 100%;
            background: var(--gradient-pink);
            width: 0%;
            transition: width 1s linear;
            border-radius: 3px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #adb5bd;
            font-size: 13px;
            font-weight: 500;
        }
        
        .footer a {
            color: var(--primary-pink);
            text-decoration: none;
            transition: var(--transition-smooth);
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 480px) {
            .login-card {
                padding: 36px 24px;
            }
            
            .modal-content {
                padding: 32px 24px;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .logo h1 {
                font-size: 24px;
            }
            
            .logo p {
                font-size: 14px;
            }
        }
        
        /* Focus styles for accessibility */
        *:focus {
            outline: 2px solid var(--primary-pink);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-spa"></i>
                </div>
                <h1>GlamBook</h1>
                <p>Professional Makeup Artist Dashboard</p>
            </div>

            <!-- Display Laravel Messages -->
            @if($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <!-- Display login attempt warnings -->
            @if(session('attempts_remaining'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>⚠️ {{ session('attempts_remaining') }} attempts remaining</div>
                </div>
            @endif

            @if(session('account_locked'))
                <div class="alert alert-error">
                    <i class="fas fa-lock"></i>
                    <div>🔒 Account temporarily locked. Please try again in {{ session('lock_time') }} minutes.</div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email Address
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="email" id="email" name="email" value="{{ old('email', session('login_email')) }}" required autofocus placeholder="artist@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-key"></i>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                        <button type="button" class="password-toggle" id="passwordToggle" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="attempts-warning" id="attemptsWarning">
                        <i class="fas fa-shield-alt"></i>
                        <span id="attemptsCount">0 </span> failed attempts
                    </div>
                </div>

                <!-- Account Lock Timer -->
                <div class="lock-timer" id="lockTimer">
                    <i class="fas fa-clock"></i>
                    Account locked for <span id="lockMinutes">5</span>:<span id="lockSeconds">00</span>
                </div>
                
                <!-- Cooldown Progress Bar -->
                <div class="cooldown-bar" id="cooldownBar">
                    <div class="cooldown-progress" id="cooldownProgress"></div>
                </div>

                <!-- Show 2FA field if required -->
                @if(session('2fa_required'))
                <div class="form-group">
                    <label for="twofa_code">
                        <i class="fas fa-mobile-alt"></i>
                        2FA Verification Code
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-shield-alt"></i>
                        <input type="text" id="twofa_code" name="twofa_code" required placeholder="Enter 6-digit code" maxlength="6" autocomplete="off">
                    </div>
                    <small style="color: #666; font-size: 13px; display: block; margin-top: 8px; padding-left: 24px;">
                        <i class="fas fa-info-circle"></i> Check your email for the verification code
                    </small>
                </div>
                @endif

                <button type="submit" class="login-btn" id="loginBtn">
                    @if(session('2fa_required'))
                        <span id="btnText">Verify & Continue</span>
                    @else
                        <span id="btnText">Sign In to Dashboard</span>
                    @endif
                    <i class="fas fa-arrow-right" id="btnIcon"></i>
                    <span class="spinner" id="btnSpinner" style="display: none;"></span>
                </button>
            </form>

            <!-- Forgot Password Button -->
            <div class="forgot-password">
                <button type="button" class="forgot-password-btn" id="forgotPasswordBtn">
                    <i class="fas fa-key"></i>
                    Forgot your password?
                </button>
            </div>

            <!-- Divider -->
            <div class="divider">
                <span class="divider-text">OR</span>
            </div>

            <!-- Register Section -->
            <div class="register-section">
                <h3 class="register-title">New Makeup Artist?</h3>
                <p class="register-text">Join GlamBook Pro and elevate your appointment management with our professional tools</p>
                <a href="{{ route('register') }}" class="register-btn">
                    <i class="fas fa-user-plus"></i>
                    Create Professional Account
                </a>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>© 2024 GlamBook Pro. All rights reserved. <a href="#">Privacy Policy</a> • <a href="#">Terms of Service</a></p>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal" id="forgotPasswordModal">
        <div class="modal-content">
            <button class="close-modal" id="closeModal" aria-label="Close modal">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="modal-title">Reset Your Password</h3>
            
            <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                @csrf
                
                <div class="form-group">
                    <label for="resetEmail">
                        <i class="fas fa-envelope"></i>
                        Enter your email address
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-at"></i>
                        <input type="email" name="email" id="resetEmail" required 
                               placeholder="artist@example.com" value="{{ old('email') }}">
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="modal-btn modal-btn-secondary" id="cancelReset">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="modal-btn modal-btn-primary">
                        <i class="fas fa-paper-plane"></i>
                        Send Reset Link
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        const forgotPasswordBtn = document.getElementById('forgotPasswordBtn');
        const forgotPasswordModal = document.getElementById('forgotPasswordModal');
        const closeModal = document.getElementById('closeModal');
        const cancelReset = document.getElementById('cancelReset');
        const lockTimer = document.getElementById('lockTimer');
        const attemptsWarning = document.getElementById('attemptsWarning');
        const attemptsCount = document.getElementById('attemptsCount');
        const cooldownBar = document.getElementById('cooldownBar');
        const cooldownProgress = document.getElementById('cooldownProgress');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');
        const btnSpinner = document.getElementById('btnSpinner');
        
        // Configuration
        const MAX_ATTEMPTS = 5;
        const LOCK_TIME_MINUTES = 5;
        let cooldownInterval;
        
        // Get current email from form
        function getCurrentEmail() {
            const emailInput = document.getElementById('email');
            return emailInput ? emailInput.value.trim().toLowerCase() : '';
        }
        
        // Email-specific storage keys
        function getStorageKey(email, key) {
            return `glambook_login_${btoa(email)}_${key}`;
        }
        
        // Get data for current email
        function getCurrentEmailData() {
            const email = getCurrentEmail();
            if (!email) return null;
            
            const attemptsKey = getStorageKey(email, 'attempts');
            const lockUntilKey = getStorageKey(email, 'lock_until');
            
            return {
                email: email,
                attempts: parseInt(localStorage.getItem(attemptsKey) || 0),
                lockUntil: localStorage.getItem(lockUntilKey),
                isLocked: function() {
                    return this.lockUntil && new Date(this.lockUntil) > new Date();
                }
            };
        }
        
        // Server data from PHP
        const serverIsLockedOut = {{ $isLockedOut ? 'true' : 'false' }} === 'true';
        const serverLockTimeMinutes = {{ $lockTime ?? 5 }};
        
        console.log('Login system initialized:', {
            serverLocked: serverIsLockedOut,
            lockTime: serverLockTimeMinutes
        });
        
        // Initialize display for current email
        function initializeAttemptDisplay() {
            const emailData = getCurrentEmailData();
            if (!emailData) {
                attemptsWarning.style.display = 'none';
                return;
            }
            
            if (emailData.attempts > 0) {
                attemptsWarning.style.display = 'flex';
                attemptsCount.textContent = emailData.attempts;
                
                const remaining = MAX_ATTEMPTS - emailData.attempts;
                if (remaining <= 2 && remaining > 0) {
                    attemptsWarning.innerHTML = `<i class="fas fa-shield-alt"></i><span id="attemptsCount">${emailData.attempts}</span> failed attempts - <strong>${remaining} attempt(s) remaining</strong>`;
                } else if (remaining <= 0 || emailData.isLocked()) {
                    attemptsWarning.innerHTML = `<i class="fas fa-ban"></i><span id="attemptsCount">${emailData.attempts}</span> failed attempts - <strong>Account locked</strong>`;
                } else {
                    attemptsWarning.innerHTML = `<i class="fas fa-shield-alt"></i><span id="attemptsCount">${emailData.attempts}</span> failed attempts`;
                }
            } else {
                attemptsWarning.style.display = 'none';
            }
        }
        
        // Check if current email is locked
        function isEmailLocked() {
            const emailData = getCurrentEmailData();
            if (!emailData) return false;
            
            // Check server lock
            if (serverIsLockedOut) {
                const lastFailedEmail = localStorage.getItem('glambook_last_failed_email');
                if (lastFailedEmail === emailData.email) {
                    return true;
                }
            }
            
            // Check client lock
            return emailData.isLocked();
        }
        
        // Start lock timer for current email
        function startLockTimer(minutes) {
            if (!lockTimer || !cooldownBar || !cooldownProgress) {
                console.error('Timer elements not found');
                return;
            }
            
            // Show timer elements
            lockTimer.style.display = 'flex';
            cooldownBar.style.display = 'block';
            
            // Set lock end time
            const lockEndTime = new Date();
            lockEndTime.setMinutes(lockEndTime.getMinutes() + minutes);
            
            // Store lock for current email
            const email = getCurrentEmail();
            if (email) {
                const lockUntilKey = getStorageKey(email, 'lock_until');
                const attemptsKey = getStorageKey(email, 'attempts');
                
                localStorage.setItem(lockUntilKey, lockEndTime.toISOString());
                localStorage.setItem(attemptsKey, MAX_ATTEMPTS);
                localStorage.setItem('glambook_last_failed_email', email);
            }
            
            // Calculate total lock time
            const totalLockTime = minutes * 60 * 1000;
            
            function updateTimer() {
                const now = new Date();
                const diff = lockEndTime - now;
                
                if (diff <= 0) {
                    clearInterval(cooldownInterval);
                    unlockCurrentEmail();
                    return;
                }
                
                // Update timer display
                const mins = Math.floor(diff / 1000 / 60);
                const secs = Math.floor((diff / 1000) % 60);
                
                document.getElementById('lockMinutes').textContent = mins.toString().padStart(2, '0');
                document.getElementById('lockSeconds').textContent = secs.toString().padStart(2, '0');
                
                // Update progress bar
                const progress = 100 - (diff / totalLockTime * 100);
                cooldownProgress.style.width = `${progress}%`;
            }
            
            // Start the timer
            updateTimer();
            cooldownInterval = setInterval(updateTimer, 1000);
        }
        
        // Lock current email
        function lockCurrentEmail(minutes = LOCK_TIME_MINUTES) {
            // Disable form elements
            loginBtn.disabled = true;
            loginBtn.classList.add('account-locked');
            btnText.textContent = 'Account Locked';
            if (btnIcon) btnIcon.style.display = 'none';
            if (passwordInput) passwordInput.disabled = true;
            if (passwordToggle) passwordToggle.disabled = true;
            
            // Start the timer
            startLockTimer(minutes);
        }
        
        // Unlock current email
        function unlockCurrentEmail() {
            const email = getCurrentEmail();
            if (!email) return;
            
            // Clear email-specific storage
            const attemptsKey = getStorageKey(email, 'attempts');
            const lockUntilKey = getStorageKey(email, 'lock_until');
            
            localStorage.removeItem(attemptsKey);
            localStorage.removeItem(lockUntilKey);
            
            // Enable form elements
            enableLoginForm();
            
            // Hide timer elements
            if (lockTimer) lockTimer.style.display = 'none';
            if (cooldownBar) cooldownBar.style.display = 'none';
            if (attemptsWarning) attemptsWarning.style.display = 'none';
        }
        
        // Enable login form
        function enableLoginForm() {
            loginBtn.disabled = false;
            loginBtn.classList.remove('account-locked');
            btnText.textContent = 'Sign In to Dashboard';
            if (btnIcon) btnIcon.style.display = 'inline-block';
            if (btnSpinner) btnSpinner.style.display = 'none';
            if (passwordInput) passwordInput.disabled = false;
            if (passwordToggle) passwordToggle.disabled = false;
        }
        
        // Update form state based on current email
        function updateFormState() {
            const email = getCurrentEmail();
            if (!email) {
                enableLoginForm();
                attemptsWarning.style.display = 'none';
                lockTimer.style.display = 'none';
                cooldownBar.style.display = 'none';
                return;
            }
            
            if (isEmailLocked()) {
                const emailData = getCurrentEmailData();
                if (emailData && emailData.lockUntil) {
                    const lockEnd = new Date(emailData.lockUntil);
                    const now = new Date();
                    const minutesLeft = Math.ceil((lockEnd - now) / 60000);
                    
                    if (minutesLeft > 0) {
                        lockCurrentEmail(minutesLeft);
                    } else {
                        unlockCurrentEmail();
                    }
                } else {
                    enableLoginForm();
                }
            } else {
                enableLoginForm();
                initializeAttemptDisplay();
            }
        }
        
        // Password visibility toggle
        if (passwordToggle) {
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                }
            });
        }
        
        // Form submission
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                const email = getCurrentEmail();
                if (!email) {
                    e.preventDefault();
                    // Add shake animation to email field
                    const emailInput = document.getElementById('email');
                    emailInput.style.borderColor = '#dc3545';
                    emailInput.classList.add('shake');
                    setTimeout(() => emailInput.classList.remove('shake'), 500);
                    return;
                }
                
                // Check if this specific email is locked
                if (isEmailLocked()) {
                    e.preventDefault();
                    return;
                }
                
                // Show loading state
                loginBtn.disabled = true;
                if (btnSpinner) btnSpinner.style.display = 'inline-block';
                if (btnIcon) btnIcon.style.display = 'none';
                if (btnText) btnText.textContent = 'Authenticating...';
                
                // Store email for auto-fill next time
                localStorage.setItem('glambook_last_login_email', email);
            });
        }
        
        // Email input change - update form state
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                clearTimeout(this.updateTimeout);
                this.updateTimeout = setTimeout(() => {
                    updateFormState();
                }, 300);
            });
        }
        
        // Initialize page
        function initializePage() {
            // Auto-fill email if stored
            const lastLoginEmail = localStorage.getItem('glambook_last_login_email');
            if (lastLoginEmail && emailInput && !emailInput.value) {
                emailInput.value = lastLoginEmail;
            }
            
            // Initial form state update
            updateFormState();
            
            // Cleanup old locks
            cleanupOldLocks();
        }
        
        // Cleanup locks older than 1 hour
        function cleanupOldLocks() {
            const oneHourAgo = new Date();
            oneHourAgo.setHours(oneHourAgo.getHours() - 1);
            
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('glambook_login_') && key.endsWith('_lock_until')) {
                    const lockTime = localStorage.getItem(key);
                    if (lockTime && new Date(lockTime) < oneHourAgo) {
                        const baseKey = key.replace('_lock_until', '');
                        localStorage.removeItem(key);
                        localStorage.removeItem(`${baseKey}_attempts`);
                    }
                }
            }
        }
        
        // Forgot Password Modal
        if (forgotPasswordBtn) {
            forgotPasswordBtn.addEventListener('click', function() {
                forgotPasswordModal.style.display = 'flex';
                const resetEmail = document.getElementById('resetEmail');
                const currentEmail = getCurrentEmail();
                if (currentEmail && resetEmail) {
                    resetEmail.value = currentEmail;
                }
                if (resetEmail) resetEmail.focus();
            });
        }
        
        function closeForgotPasswordModal() {
            forgotPasswordModal.style.display = 'none';
        }
        
        if (closeModal) closeModal.addEventListener('click', closeForgotPasswordModal);
        if (cancelReset) cancelReset.addEventListener('click', closeForgotPasswordModal);
        
        if (forgotPasswordModal) {
            forgotPasswordModal.addEventListener('click', function(e) {
                if (e.target === forgotPasswordModal) {
                    closeForgotPasswordModal();
                }
            });
        }
        
        // Handle Laravel validation errors for forgot password
        @if($errors->has('email') && old('_token'))
            @if(request()->is('password/email') || request()->is('forgot-password'))
                setTimeout(() => {
                    forgotPasswordModal.style.display = 'flex';
                }, 500);
            @endif
        @endif
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && forgotPasswordModal.style.display === 'flex') {
                closeForgotPasswordModal();
            }
        });
        
        // Auto-submit 2FA code
        const twofaInput = document.getElementById('twofa_code');
        if (twofaInput) {
            twofaInput.addEventListener('input', function(e) {
                if (this.value.length === 6) {
                    loginForm.submit();
                }
            });
            twofaInput.focus();
        }
        
        // Handle server messages
        @if(session('account_locked'))
            const lockedEmail = '{{ session("login_email", "") }}'.toLowerCase();
            if (lockedEmail) {
                // Store lock for this specific email
                const lockEndTime = new Date();
                lockEndTime.setMinutes(lockEndTime.getMinutes() + {{ session('lock_time', 5) }});
                
                const attemptsKey = `glambook_login_${btoa(lockedEmail)}_attempts`;
                const lockUntilKey = `glambook_login_${btoa(lockedEmail)}_lock_until`;
                
                localStorage.setItem(attemptsKey, {{ session('login_attempts', 5) }});
                localStorage.setItem(lockUntilKey, lockEndTime.toISOString());
                localStorage.setItem('glambook_last_failed_email', lockedEmail);
                
                // Update display if current email matches
                if (getCurrentEmail() === lockedEmail) {
                    updateFormState();
                }
            }
        @elseif(session('attempts_remaining'))
            const attemptEmail = '{{ session("login_email", "") }}'.toLowerCase();
            if (attemptEmail) {
                const attemptsKey = `glambook_login_${btoa(attemptEmail)}_attempts`;
                localStorage.setItem(attemptsKey, {{ session('login_attempts', 0) }});
                
                // Update display if current email matches
                if (getCurrentEmail() === attemptEmail) {
                    updateFormState();
                }
            }
        @endif
        
        // Add shake animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            .shake {
                animation: shake 0.5s ease-in-out;
            }
        `;
        document.head.appendChild(style);
        
        // Initialize the page
        initializePage();
    });
    </script>
</body>
</html>