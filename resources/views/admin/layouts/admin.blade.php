<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - GlamBook Admin</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --light-pink: #ffb6c1;
            --soft-pink: #ffe4e9;
            --medium-pink: #ff8fa3;
            --dark-pink: #ff6b9d;
            --pink-glow: rgba(255, 182, 193, 0.3);
            --white: #ffffff;
            --off-white: #fffafb;
            --text-dark: #2d3748;
            --text-light: #718096;
            --gold-accent: #ffd166;
            --shadow-soft: 0 8px 30px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff5f7 0%, #ffeef2 100%);
            color: var(--text-dark);
            min-height: 100vh;
        }
        
        /* Admin Layout */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar - Light Pink Theme */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(135deg, var(--white) 0%, var(--off-white) 100%);
            box-shadow: var(--shadow-medium);
            border-right: 2px solid var(--soft-pink);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .admin-sidebar::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--light-pink), var(--medium-pink), var(--dark-pink));
            z-index: 1001;
        }
        
        /* Main Content */
        .admin-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
            background: var(--off-white);
        }
        
        /* Brand Section */
        .admin-brand {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255, 182, 193, 0.3);
            background: linear-gradient(135deg, rgba(255, 182, 193, 0.1), rgba(255, 143, 163, 0.05));
        }
        
        .brand-title {
            font-family: 'Dancing Script', cursive;
            font-weight: 700;
            font-size: 2.5rem;
            color: var(--dark-pink);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .brand-subtitle {
            color: var(--medium-pink);
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 1px;
            margin-top: 5px;
        }
        
        /* User Info Card */
        .user-info-card {
            background: var(--white);
            border-radius: 18px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 5px 20px var(--pink-glow);
            border: 2px solid var(--soft-pink);
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--light-pink), var(--dark-pink));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            margin-right: 15px;
            box-shadow: 0 4px 15px var(--pink-glow);
            border: 3px solid white;
        }
        
        .user-role-badge {
            background: linear-gradient(135deg, var(--dark-pink), #ff4d87);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 5px;
        }
        
        /* Navigation */
        .admin-nav {
            padding: 20px 0;
        }
        
        .nav-section-title {
            color: var(--dark-pink);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 15px 25px 8px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .admin-nav-link {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            margin: 6px 15px;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 15px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-weight: 500;
            position: relative;
            overflow: hidden;
            background: transparent;
            border: 2px solid transparent;
        }
        
        .admin-nav-link:hover {
            background: linear-gradient(135deg, rgba(255, 182, 193, 0.15), rgba(255, 143, 163, 0.1));
            color: var(--dark-pink);
            transform: translateX(8px);
            border-color: var(--soft-pink);
        }
        
        .admin-nav-link.active {
            background: linear-gradient(135deg, var(--light-pink), var(--medium-pink));
            color: white;
            box-shadow: 0 6px 20px var(--pink-glow);
            border-color: transparent;
            animation: gentleGlow 2s infinite alternate;
        }
        
        @keyframes gentleGlow {
            0% { box-shadow: 0 6px 20px rgba(255, 182, 193, 0.4); }
            100% { box-shadow: 0 6px 25px rgba(255, 182, 193, 0.6); }
        }
        
        .admin-nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        .nav-badge {
            margin-left: auto;
            background: var(--gold-accent);
            color: var(--text-dark);
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 15px;
            font-weight: 600;
            min-width: 30px;
            text-align: center;
        }
        
        /* Logout Section */
        .logout-section {
            padding: 25px;
            margin-top: auto;
            border-top: 1px solid rgba(255, 182, 193, 0.3);
            background: rgba(255, 255, 255, 0.8);
        }
        
        .btn-logout {
            width: 100%;
            background: linear-gradient(135deg, var(--light-pink), var(--medium-pink));
            border: none;
            color: white;
            padding: 14px;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 4px 15px var(--pink-glow);
        }
        
        .btn-logout:hover {
            background: linear-gradient(135deg, var(--medium-pink), var(--dark-pink));
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 143, 163, 0.4);
        }
        
        /* Main Content Header */
        .admin-header {
            background: var(--white);
            border-radius: 25px;
            padding: 30px 35px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-soft);
            border: 2px solid var(--soft-pink);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--medium-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
        }
        
        .header-subtitle {
            color: var(--text-light);
            font-size: 1rem;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: var(--white);
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--shadow-soft);
            border: 2px solid var(--soft-pink);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--light-pink), var(--medium-pink));
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 182, 193, 0.2);
        }
        
        .stat-icon {
            font-size: 2.8rem;
            background: linear-gradient(135deg, var(--light-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-pink);
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        /* Recent Activity Cards */
        .recent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .recent-card {
            background: var(--white);
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--shadow-soft);
            border: 2px solid var(--soft-pink);
        }
        
        .recent-card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-pink);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--soft-pink);
        }
        
        .recent-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 182, 193, 0.2);
        }
        
        .recent-item:last-child {
            border-bottom: none;
        }
        
        .recent-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--light-pink), var(--medium-pink));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .recent-info {
            flex: 1;
        }
        
        .recent-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 3px;
        }
        
        .recent-detail {
            color: var(--text-light);
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .recent-time {
            color: var(--medium-pink);
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        /* Tables */
        .table-container {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 2px solid var(--soft-pink);
            margin-bottom: 30px;
        }
        
        .admin-table {
            margin: 0;
        }
        
        .admin-table thead {
            background: linear-gradient(135deg, var(--soft-pink), rgba(255, 182, 193, 0.3));
            border-bottom: 3px solid var(--light-pink);
        }
        
        .admin-table th {
            color: var(--dark-pink);
            font-weight: 600;
            padding: 20px;
            border: none;
            font-size: 0.95rem;
        }
        
        .admin-table td {
            padding: 18px 20px;
            vertical-align: middle;
            border-color: rgba(255, 182, 193, 0.15);
        }
        
        .admin-table tbody tr:hover {
            background: rgba(255, 182, 193, 0.05);
        }
        
        /* Badges */
        .badge-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .badge-completed {
            background: linear-gradient(135deg, #34d399, #10b981);
            color: white;
        }
        
        .badge-pending {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }
        
        .badge-cancelled {
            background: linear-gradient(135deg, #f87171, #ef4444);
            color: white;
        }
        
        /* Alerts */
        .alert-admin {
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 25px;
            border: 2px solid;
            backdrop-filter: blur(10px);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(167, 243, 208, 0.9), rgba(110, 231, 183, 0.8));
            border-color: #34d399;
            color: #065f46;
        }
        
        .alert-error {
            background: linear-gradient(135deg, rgba(254, 202, 202, 0.9), rgba(252, 165, 165, 0.8));
            border-color: #f87171;
            color: #7f1d1d;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .action-btn {
            background: var(--white);
            border: 2px solid var(--soft-pink);
            border-radius: 18px;
            padding: 20px;
            text-align: center;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        
        .action-btn:hover {
            background: linear-gradient(135deg, var(--soft-pink), rgba(255, 182, 193, 0.2));
            transform: translateY(-5px);
            color: var(--dark-pink);
            box-shadow: 0 10px 25px var(--pink-glow);
        }
        
        .action-btn i {
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--light-pink), var(--dark-pink));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Responsive Design */
        @media (max-width: 1200px) {
            .admin-sidebar {
                width: 250px;
            }
            
            .admin-content {
                margin-left: 250px;
                padding: 25px;
            }
        }
        
        @media (max-width: 992px) {
            .admin-wrapper {
                flex-direction: column;
            }
            
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .admin-content {
                margin-left: 0;
            }
            
            .admin-nav {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                padding: 15px;
            }
            
            .admin-nav-link {
                margin: 0;
                justify-content: center;
                text-align: center;
                flex-direction: column;
                padding: 15px;
            }
            
            .admin-nav-link i {
                margin-right: 0;
                margin-bottom: 8px;
                font-size: 1.3rem;
            }
            
            .nav-badge {
                margin: 8px 0 0 0;
            }
            
            .logout-section {
                text-align: center;
            }
        }
        
        @media (max-width: 768px) {
            .admin-content {
                padding: 20px;
            }
            
            .admin-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
                padding: 25px;
            }
            
            .stats-grid,
            .recent-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .admin-nav {
                grid-template-columns: 1fr;
            }
            
            .admin-content {
                padding: 15px;
            }
            
            .header-title {
                font-size: 1.8rem;
            }
        }
        
        /* Loading Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <!-- Brand -->
            <div class="admin-brand">
                <div class="brand-title">
                    GlamBook
                </div>
                <div class="brand-subtitle">
                    <i class="fas fa-crown me-1"></i>
                    Admin Dashboard
                </div>
            </div>
            
            <!-- User Info -->
            <div class="user-info-card d-flex align-items-center fade-in-up">

                <div>
                    <h6 class="mb-1 fw-bold">{{ Auth::user()->name }}</h6>
                    <p class="mb-1 small text-muted">
                        <i class="fas fa-envelope me-1"></i>{{ Auth::user()->email }}
                    </p>
                    <span class="user-role-badge">
                        <i class="fas fa-user-shield me-1"></i>Administrator
                    </span>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="admin-nav">
                <div class="nav-section-title">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </div>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Overview</span>
                </a>
                
                <div class="nav-section-title mt-3">
                    <i class="fas fa-users"></i>
                    User Management
                </div>
                
                <a href="{{ route('admin.users') }}" 
                   class="admin-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-user-friends"></i>
                    <span>All Users</span>
                    <span class="nav-badge">{{ \App\Models\User::count() }}</span>
                </a>
                
                
                <div class="nav-section-title mt-3">
                    <i class="fas fa-calendar-alt"></i>
                    Appointments
                </div>
                
                <a href="{{ route('admin.appointments') }}" 
                   class="admin-nav-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>All Appointments</span>
                    <span class="nav-badge">{{ \App\Models\Task::count() }}</span>
                </a>
                
                <a href="{{ route('admin.appointments') }}?filter=today" 
                   class="admin-nav-link">
                    <i class="fas fa-sun"></i>
                    <span>Today's</span>
                    <span class="nav-badge">{{ \App\Models\Task::whereDate('appointment_at', today())->count() }}</span>
                </a>
                
                <div class="nav-section-title mt-3">
                    <i class="fas fa-map-marker-alt"></i>
                    Locations
                </div>
                
                <a href="{{ route('admin.locations') }}" 
                   class="admin-nav-link {{ request()->routeIs('admin.locations*') ? 'active' : '' }}">
                    <i class="fas fa-map"></i>
                    <span>Location Management</span>
                    <span class="nav-badge badge-pending">New</span>
                </a>
                
                <a href="{{ route('admin.locations.map') }}" 
                   class="admin-nav-link">
                    <i class="fas fa-globe-americas"></i>
                    <span>Map View</span>
                </a>
            </nav>
            
            <!-- Logout Section -->
            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout fade-in-up">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout from Admin Panel
                    </button>
                </form>
                <p class="text-center mt-3 mb-0 small text-muted">
                    <i class="fas fa-lock me-1"></i>
                    Secure Admin Session
                </p>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="admin-content">
            <!-- Page Header -->
            <div class="admin-header fade-in-up">
                <div>
                    <h1 class="header-title">@yield('page-title', 'Admin Dashboard')</h1>
                    <p class="header-subtitle">
                        <i class="fas fa-calendar-day"></i>
                        {{ now()->format('l, F j, Y') }}
                        •
                        <i class="fas fa-clock"></i>
                        {{ now()->format('h:i A') }}
                    </p>
                </div>
                <div>
                    <span class="badge-status badge-pending">
                        <i class="fas fa-shield-alt me-1"></i>
                        Admin Mode Active
                    </span>
                </div>
            </div>
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert-admin alert-success alert-dismissible fade show fade-in-up" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3" style="font-size: 1.8rem;"></i>
                        <div>
                            <h5 class="mb-1">Success!</h5>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert-admin alert-error alert-dismissible fade show fade-in-up" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3" style="font-size: 1.8rem;"></i>
                        <div>
                            <h5 class="mb-1">Attention Needed</h5>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
            
            <!-- Footer -->
            <div class="mt-5 pt-4 border-top border-soft-pink text-center">
                <p class="text-muted small">
                    <i class="fas fa-spa me-1"></i>
                    GlamBook Admin Panel • v1.0 • 
                    © {{ date('Y') }} • 
                    <i class="fas fa-heart ms-2" style="color: var(--medium-pink);"></i>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth animations for all cards
            const cards = document.querySelectorAll('.stat-card, .recent-card, .action-btn');
            cards.forEach(card => {
                card.classList.add('fade-in-up');
            });
            
            // Logout confirmation
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to logout from the admin panel?')) {
                        e.preventDefault();
                    }
                });
            }
            
            // Update live time
            function updateLiveTime() {
                const now = new Date();
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true 
                };
                
                const timeElements = document.querySelectorAll('.live-time');
                timeElements.forEach(el => {
                    el.textContent = now.toLocaleDateString('en-US', options);
                });
            }
            
            // Update time every minute
            setInterval(updateLiveTime, 60000);
            updateLiveTime();
            
            // Mobile menu toggle
            if (window.innerWidth <= 992) {
                const createMobileToggle = () => {
                    const sidebar = document.querySelector('.admin-sidebar');
                    const toggleBtn = document.createElement('button');
                    
                    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                    toggleBtn.style.cssText = `
                        position: fixed;
                        top: 20px;
                        left: 20px;
                        z-index: 1001;
                        background: linear-gradient(135deg, var(--medium-pink), var(--dark-pink));
                        color: white;
                        border: none;
                        border-radius: 12px;
                        width: 50px;
                        height: 50px;
                        font-size: 1.2rem;
                        box-shadow: 0 4px 15px var(--pink-glow);
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    `;
                    
                    document.body.appendChild(toggleBtn);
                    
                    toggleBtn.addEventListener('click', () => {
                        sidebar.classList.toggle('mobile-hidden');
                    });
                    
                    // Add mobile styles
                    const style = document.createElement('style');
                    style.textContent = `
                        @media (max-width: 992px) {
                            .admin-sidebar.mobile-hidden {
                                transform: translateX(-100%);
                            }
                            
                            .admin-sidebar {
                                transition: transform 0.3s ease;
                                position: fixed;
                                height: 100vh;
                                overflow-y: auto;
                            }
                        }
                    `;
                    document.head.appendChild(style);
                };
                
                createMobileToggle();
            }
            
            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('.btn-logout, .action-btn, .admin-nav-link');
            buttons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.3);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        top: ${y}px;
                        left: ${x}px;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });
            
            // Add ripple animation
            const rippleStyle = document.createElement('style');
            rippleStyle.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(rippleStyle);
        });
    </script>
    
    @yield('scripts')
</body>
</html>