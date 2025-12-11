<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👑 GlamBook Scheduler | Luxury Beauty Appointment System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

    <style>
        :root {
            /* Pink Luxury Theme */
            --primary-rose: #e8b4b8;
            --deep-rose: #d45079;
            --soft-rose: #fce4ec;
            --cream: #f9f5f0;
            --gold: #d4af37;
            --soft-gold: #f7e8c8;
            --charcoal: #2c2c2c;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --white: #ffffff;
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 8px 30px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.15);
            --shadow-rose: 0 10px 30px rgba(232, 180, 184, 0.2);
            --gradient-rose: linear-gradient(135deg, #e8b4b8 0%, #d45079 100%);
            --gradient-gold: linear-gradient(135deg, #f7e8c8 0%, #d4af37 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--cream);
            color: var(--charcoal);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated Gradient Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            opacity: 0.7;
        }

        .animated-bg::before,
        .animated-bg::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(40px);
            animation: float 20s infinite ease-in-out;
        }

        .animated-bg::before {
            top: 10%;
            left: 10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--primary-rose) 0%, transparent 70%);
            animation-delay: 0s;
        }

        .animated-bg::after {
            bottom: 10%;
            right: 10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--gold) 0%, transparent 70%);
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -30px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        /* Luxury Navigation */
        .luxury-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(232, 180, 184, 0.2);
            box-shadow: var(--shadow-sm);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .luxury-navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: var(--shadow-md);
        }

        .navbar-brand-luxury {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 2rem;
            color: var(--charcoal);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.5rem 0;
            position: relative;
            overflow: hidden;
            animation: fadeInDown 1s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar-brand-luxury::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--gradient-rose);
            transform: translateX(-100%);
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-brand-luxury:hover::after {
            transform: translateX(0);
        }

        .brand-icon {
            color: var(--gold);
            font-size: 1.8rem;
            animation: gentlePulse 3s infinite;
            transition: all 0.3s ease;
        }

        .brand-icon:hover {
            transform: rotate(15deg) scale(1.2);
        }

        @keyframes gentlePulse {
            0%, 100% { 
                transform: scale(1); 
                text-shadow: 0 0 20px rgba(232, 180, 184, 0.3);
            }
            50% { 
                transform: scale(1.1); 
                text-shadow: 0 0 30px rgba(232, 180, 184, 0.5);
            }
        }

        .brand-accent {
            color: var(--deep-rose);
            position: relative;
            display: inline-block;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .brand-accent::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--gradient-rose);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .brand-accent:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Navigation Links */
        .nav-item-luxury {
            margin: 0 0.25rem;
        }

        .nav-link-luxury {
            font-weight: 500;
            color: var(--charcoal) !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 50px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 10px;
            border: 2px solid transparent;
            animation: fadeIn 0.8s ease forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        .nav-item-luxury:nth-child(1) .nav-link-luxury {
            animation-delay: 0.2s;
        }
        .nav-item-luxury:nth-child(2) .nav-link-luxury {
            animation-delay: 0.4s;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-link-luxury::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-rose);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
            border-radius: 50px;
        }

        .nav-link-luxury:hover::before,
        .nav-link-luxury.active::before {
            opacity: 1;
        }

        .nav-link-luxury:hover,
        .nav-link-luxury.active {
            color: white !important;
            transform: translateY(-3px);
            box-shadow: var(--shadow-rose);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link-luxury .nav-icon {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-link-luxury:hover .nav-icon {
            transform: scale(1.2) rotate(10deg);
        }

        /* User Area */
        .user-area {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            animation: slideInLeft 0.8s ease 0.6s forwards;
            opacity: 0;
            transform: translateX(20px);
        }

        @keyframes slideInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .user-greeting {
            color: var(--charcoal);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            background: rgba(232, 180, 184, 0.1);
            border: 1px solid rgba(232, 180, 184, 0.2);
            transition: all 0.3s ease;
        }

        .user-greeting:hover {
            background: rgba(232, 180, 184, 0.2);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .logout-btn {
            background: transparent;
            color: var(--deep-rose);
            border: 2px solid var(--primary-rose);
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-rose);
            transition: left 0.4s ease;
            z-index: -1;
        }

        .logout-btn:hover::before {
            left: 0;
        }

        .logout-btn:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--shadow-rose);
            border-color: transparent;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 180px);
            padding: 4rem 0;
            position: relative;
        }

        /* Luxury Content Card */
        .luxury-card {
            background: var(--white);
            border-radius: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(232, 180, 184, 0.15);
            overflow: hidden;
            position: relative;
            backdrop-filter: blur(10px);
            animation: cardAppear 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .luxury-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-rose);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* ===== FULLCALENDAR STYLING - COMPLETE PINK THEME ===== */
        .calendar-container {
            background: var(--white);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(232, 180, 184, 0.15);
            animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.3s both;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* FullCalendar Toolbar - Complete Pink Override */
/* FullCalendar Toolbar - Complete Pink Override */
.fc .fc-toolbar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem !important;
    padding: 1.5rem !important;
    background: linear-gradient(135deg, rgba(232, 180, 184, 0.1) 0%, rgba(212, 175, 55, 0.05) 100%) !important;
    border-radius: 16px !important;
    border: 1px solid rgba(232, 180, 184, 0.2) !important;
    animation: fadeIn 0.8s ease 0.5s both;
}


        .fc-toolbar-title {
            font-family: 'Playfair Display', serif !important;
            font-weight: 700 !important;
            color: var(--deep-rose) !important;
            font-size: 1.8rem !important;
            margin: 0 !important;
            animation: titleGlow 3s infinite;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        @keyframes titleGlow {
            0%, 100% { 
                text-shadow: 0 0 10px rgba(232, 180, 184, 0.3),
                            0 0 20px rgba(212, 175, 55, 0.1); 
            }
            50% { 
                text-shadow: 0 0 15px rgba(232, 180, 184, 0.5),
                            0 0 30px rgba(212, 175, 55, 0.2); 
            }
        }

        /* All Calendar Buttons - Pink Theme */
        .fc .fc-button {
            background: var(--primary-rose) !important;
            border: 2px solid var(--deep-rose) !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 0.7rem 1.8rem !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: var(--shadow-sm) !important;
            margin: 0 0.5rem !important;
            position: relative;
            overflow: hidden;
        }

        .fc .fc-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .fc .fc-button:hover::before {
            left: 100%;
        }

        .fc .fc-button:hover {
            background: var(--deep-rose) !important;
            transform: translateY(-3px) !important;
            box-shadow: var(--shadow-rose) !important;
        }

        /* Today Button - Special Styling */
        .fc .fc-today-button {
            background: var(--gradient-rose) !important;
            border: none !important;
            padding: 0.7rem 2.5rem !important;
            font-weight: 600 !important;
            animation: pulseToday 2s infinite;
            margin-left: 1rem !important;
        }

        @keyframes pulseToday {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 4px 15px rgba(232, 180, 184, 0.4);
            }
            50% { 
                transform: scale(1.05);
                box-shadow: 0 6px 25px rgba(232, 180, 184, 0.6);
            }
        }

        .fc .fc-today-button:hover {
            background: var(--deep-rose) !important;
            animation: none;
            transform: translateY(-3px) scale(1.05) !important;
        }

        /* Previous/Next Buttons */
        .fc .fc-prev-button,
        .fc .fc-next-button {
            padding: 0.7rem 1.2rem !important;
            min-width: 48px;
        }

        /* View Buttons (Month/Week/Day) */
        .fc .fc-dayGridMonth-button,
        .fc .fc-timeGridWeek-button,
        .fc .fc-timeGridDay-button {
            min-width: 100px;
            margin: 0 0.25rem !important;
        }

        /* Active View Button */
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: var(--deep-rose) !important;
            border-color: var(--deep-rose) !important;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2) !important;
            animation: activeButtonGlow 1.5s infinite;
        }

        @keyframes activeButtonGlow {
            0%, 100% { 
                box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2), 
                            0 0 5px var(--primary-rose),
                            0 0 10px rgba(232, 180, 184, 0.3); 
            }
            50% { 
                box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2), 
                            0 0 15px var(--primary-rose),
                            0 0 25px rgba(232, 180, 184, 0.5); 
            }
        }

        /* Calendar Days & Events */
        .fc .fc-daygrid-day {
            border-radius: 12px;
            transition: all 0.3s ease;
            border: 1px solid rgba(232, 180, 184, 0.1) !important;
        }

        .fc .fc-daygrid-day:hover {
            background: var(--soft-rose) !important;
            transform: scale(1.02) !important;
            box-shadow: var(--shadow-sm) !important;
        }

        .fc .fc-day-today {
            background: var(--gradient-gold) !important;
            border: 2px solid var(--gold) !important;
            border-radius: 12px;
            animation: todayPulse 2s infinite;
        }

        @keyframes todayPulse {
            0%, 100% { 
                border-color: var(--gold);
                box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
            }
            50% { 
                border-color: var(--deep-rose);
                box-shadow: 0 0 25px rgba(212, 175, 55, 0.5);
            }
        }

        .fc .fc-daygrid-day-number {
            font-weight: 600;
            color: var(--charcoal);
            padding: 10px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .fc .fc-daygrid-day:hover .fc-daygrid-day-number {
            color: var(--deep-rose);
            transform: scale(1.1);
        }

        /* Events Styling - Based on Task Status */
        .fc-event {
            border: none !important;
            border-radius: 10px !important;
            padding: 0.8rem !important;
            color: white !important;
            box-shadow: var(--shadow-sm) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            animation: eventAppear 0.5s ease-out;
            border-left: 4px solid var(--gold) !important;
            margin: 2px 0 !important;
            overflow: hidden;
            position: relative;
        }

        @keyframes eventAppear {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .fc-event::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .fc-event:hover::before {
            left: 100%;
        }

        .fc-event:hover {
            transform: translateY(-3px) scale(1.02) !important;
            box-shadow: var(--shadow-md) !important;
            border-left-width: 6px !important;
            z-index: 10 !important;
        }
        /* ===== DOT EVENTS STYLING ===== */
/* Fix for white-looking dot events */
.fc-daygrid-event.fc-daygrid-dot-event {
    background: var(--primary-rose) !important;
    border: 2px solid var(--deep-rose) !important;
    color: white !important;
    border-radius: 50px !important;
    padding: 0.4rem 0.8rem !important;
    margin: 2px 0 !important;
    font-size: 0.75rem !important;
    font-weight: 500 !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm) !important;
}

/* Hover effect for dot events */
.fc-daygrid-event.fc-daygrid-dot-event:hover {
    background: var(--deep-rose) !important;
    transform: translateY(-3px) !important;
    box-shadow: var(--shadow-rose) !important;
    border-color: transparent !important;
    color: white !important;
}

/* Dot event content styling */
.fc-daygrid-event.fc-daygrid-dot-event .fc-event-title {
    font-weight: 600 !important;
    font-size: 0.7rem !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    max-width: 150px !important;
    display: inline-block !important;
    position: relative;
    z-index: 2;
}

/* The colored dot next to the text */
.fc-daygrid-event-dot {
    border: 3px solid var(--deep-rose) !important;
    background: var(--primary-rose) !important;
    margin-right: 8px !important;
    transition: all 0.3s ease !important;
    position: relative;
    z-index: 2;
}

/* Hover effect for the dot */
.fc-daygrid-event.fc-daygrid-dot-event:hover .fc-daygrid-event-dot {
    transform: scale(1.3) rotate(15deg) !important;
    background: white !important;
    border-color: white !important;
}

/* Shimmer effect on hover */
.fc-daygrid-event.fc-daygrid-dot-event::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.4), 
        transparent
    );
    transition: left 0.7s ease;
    z-index: 1;
}

.fc-daygrid-event.fc-daygrid-dot-event:hover::before {
    left: 100%;
}

/* Responsive design for dot events */
@media (max-width: 768px) {
    .fc-daygrid-event.fc-daygrid-dot-event {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.65rem !important;
        margin: 1px 0 !important;
    }
    
    .fc-daygrid-event.fc-daygrid-dot-event .fc-event-title {
        max-width: 100px !important;
    }
}
        /* Event colors based on status */
        .event-scheduled {
            background: var(--gradient-rose) !important;
        }

        .event-completed {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%) !important;
        }

        .event-cancelled {
            background: linear-gradient(135deg, #9E9E9E 0%, #616161 100%) !important;
        }

        .event-urgent {
            background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%) !important;
            animation: urgentPulse 1.5s infinite;
        }

        @keyframes urgentPulse {
            0%, 100% { 
                opacity: 1;
                box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);
            }
            50% { 
                opacity: 0.9;
                box-shadow: 0 6px 25px rgba(255, 152, 0, 0.6);
            }
        }

        .event-content {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            position: relative;
            z-index: 2;
        }

        .service-icon {
            font-size: 1rem;
            margin-top: 2px;
            animation: iconFloat 2s infinite ease-in-out;
        }

        @keyframes iconFloat {
            0%, 100% { transform: translateY(0) rotate(0); }
            50% { transform: translateY(-3px) rotate(5deg); }
        }

        .event-details {
            flex: 1;
        }

        .fc-event-title {
            font-weight: 600;
            font-size: 0.85rem;
            display: block;
            margin-bottom: 2px;
            line-height: 1.3;
        }

        .fc-event-time {
            font-size: 0.75rem;
            opacity: 0.9;
            display: block;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .fc-event-client {
            font-size: 0.7rem;
            opacity: 0.9;
            display: block;
            font-style: italic;
        }

        /* Event popup details */
        .event-details-popup {
            position: absolute;
            background: var(--white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-lg);
            border: 2px solid var(--primary-rose);
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            animation: popupAppear 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes popupAppear {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .event-details-header {
            border-bottom: 2px solid var(--primary-rose);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .event-details-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--charcoal);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .event-details-body {
            color: var(--charcoal);
        }

        .event-detail-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            gap: 10px;
            animation: fadeInRow 0.3s ease forwards;
            opacity: 0;
            transform: translateX(-10px);
        }

        @keyframes fadeInRow {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .event-detail-row:nth-child(1) { animation-delay: 0.1s; }
        .event-detail-row:nth-child(2) { animation-delay: 0.2s; }
        .event-detail-row:nth-child(3) { animation-delay: 0.3s; }
        .event-detail-row:nth-child(4) { animation-delay: 0.4s; }
        .event-detail-row:nth-child(5) { animation-delay: 0.5s; }

        .event-detail-icon {
            color: var(--deep-rose);
            width: 20px;
            text-align: center;
            margin-top: 2px;
        }

        /* Luxury Footer */
        .luxury-footer {
            background: linear-gradient(135deg, var(--white) 0%, var(--cream) 100%);
            border-top: 1px solid rgba(232, 180, 184, 0.2);
            padding: 3rem 0 2rem;
            position: relative;
            overflow: hidden;
        }

        .luxury-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-rose);
            animation: shimmer 3s linear infinite;
        }

        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--charcoal) 0%, var(--deep-rose) 50%, var(--gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gentlePulse 4s infinite;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }

        .footer-link {
            color: var(--charcoal);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            animation: bounceIn 0.6s ease forwards;
            opacity: 0;
        }

        .footer-link:nth-child(1) { animation-delay: 0.1s; }
        .footer-link:nth-child(2) { animation-delay: 0.2s; }
        .footer-link:nth-child(3) { animation-delay: 0.3s; }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .footer-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-rose);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .footer-link:hover {
            color: var(--deep-rose);
            transform: translateY(-3px);
        }

        .footer-link:hover::after {
            width: 80%;
        }

        /* Enhanced Typography */
        .display-title {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--charcoal) 0%, var(--deep-rose) 50%, var(--gold) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
            margin-bottom: 2rem;
            position: relative;
            animation: textReveal 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        @keyframes textReveal {
            from {
                opacity: 0;
                transform: translateY(20px);
                clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);
            }
            to {
                opacity: 1;
                transform: translateY(0);
                clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
            }
        }

        /* Loading States */
        .loading-spinner {
            display: inline-block;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(232, 180, 184, 0.3);
            border-radius: 50%;
            border-top-color: var(--primary-rose);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Notification System */
        .notification {
            position: fixed;
            top: 100px;
            right: 30px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 1.5rem;
            border-left: 5px solid var(--primary-rose);
            animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top right;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .loading-content {
            background: var(--white);
            padding: 3rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: var(--shadow-lg);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Badges */
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-scheduled {
            background: var(--gradient-rose);
            color: white;
        }

        .badge-completed {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
        }

        .badge-cancelled {
            background: linear-gradient(135deg, #9E9E9E 0%, #616161 100%);
            color: white;
        }
/* ===== FULLCALENDAR BLUE BUTTON OVERRIDE ===== */
/* This targets ALL FullCalendar buttons to override Bootstrap's blue theme */
.fc .fc-button-primary {
    --bs-btn-color: #fff !important;
    --bs-btn-bg: #e8b4b8 !important;
    --bs-btn-border-color: #d45079 !important;
    --bs-btn-hover-color: #fff !important;
    --bs-btn-hover-bg: #d45079 !important;
    --bs-btn-hover-border-color: #c13d69 !important;
    --bs-btn-focus-shadow-rgb: 232, 180, 184, 0.5 !important;
    --bs-btn-active-color: #fff !important;
    --bs-btn-active-bg: #c13d69 !important;
    --bs-btn-active-border-color: #b3365e !important;
    --bs-btn-active-shadow: inset 0 3px 8px rgba(0, 0, 0, 0.2) !important;
    --bs-btn-disabled-color: #fff !important;
    --bs-btn-disabled-bg: rgba(232, 180, 184, 0.4) !important;
    --bs-btn-disabled-border-color: rgba(212, 80, 121, 0.3) !important;
}

/* Individual button types to ensure full coverage */
.fc .fc-today-button,
.fc .fc-prev-button,
.fc .fc-next-button,
.fc .fc-dayGridMonth-button,
.fc .fc-timeGridWeek-button,
.fc .fc-timeGridDay-button,
.fc .fc-listMonth-button {
    --bs-btn-bg: #e8b4b8 !important;
    --bs-btn-border-color: #d45079 !important;
    --bs-btn-hover-bg: #d45079 !important;
    --bs-btn-hover-border-color: #c13d69 !important;
    --bs-btn-active-bg: #c13d69 !important;
    --bs-btn-active-border-color: #b3365e !important;
}

/* Active state override */
.fc .fc-button-primary.fc-button-active {
    --bs-btn-bg: #d45079 !important;
    --bs-btn-border-color: #d45079 !important;
}

/* Also set direct styles to override Bootstrap */
.fc .fc-button-primary,
.fc .fc-button-primary:not(:disabled):active,
.fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: var(--bs-btn-bg) !important;
    border-color: var(--bs-btn-border-color) !important;
    color: var(--bs-btn-color) !important;
}

.fc .fc-button-primary:hover {
    background-color: var(--bs-btn-hover-bg) !important;
    border-color: var(--bs-btn-hover-border-color) !important;
    color: var(--bs-btn-hover-color) !important;
}

/* Remove Bootstrap's blue focus shadow */
.fc .fc-button-primary:focus {
    box-shadow: 0 0 0 0.25rem rgba(232, 180, 184, 0.5) !important;
}

/* Make sure Today button stays pink */
.fc .fc-today-button {
    background: linear-gradient(135deg, #e8b4b8 0%, #d45079 100%) !important;
    border: none !important;
}

/* Hover effect for Today button */
.fc .fc-today-button:hover {
    background: linear-gradient(135deg, #d45079 0%, #c13d69 100%) !important;
    border: none !important;
}


        /* Responsive Design */
        @media (max-width: 992px) {
            .fc .fc-toolbar {
                flex-direction: column !important;
                gap: 1rem !important;
            }
            
            .fc-toolbar-title {
                order: -1 !important;
                width: 100% !important;
                text-align: center !important;
            }
            
            .fc .fc-button-group {
                justify-content: center !important;
                flex-wrap: wrap !important;
            }
            
            .display-title {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 2rem 0;
            }
            
            .luxury-card {
                border-radius: 20px;
            }
            
            .calendar-container {
                padding: 1.5rem;
            }
            
            .display-title {
                font-size: 2rem;
            }
            
            .fc .fc-button-group {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }
            
            .fc .fc-button {
                width: 100%;
                margin: 0.25rem 0 !important;
            }
            
            .fc .fc-today-button {
                margin-left: 0 !important;
            }
            
            .navbar-brand-luxury {
                font-size: 1.6rem;
            }
            
            .user-area {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }
            
            .logout-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        *:focus {
            outline: 3px solid var(--gold);
            outline-offset: 3px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--soft-rose);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-rose);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--deep-rose);
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg"></div>

    <!-- Luxury Navigation -->
    <nav class="navbar navbar-expand-lg luxury-navbar" id="mainNavbar">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand-luxury" href="{{ route('dashboard') }}">
                <i class="fas fa-gem brand-icon"></i>
                <span class="d-none d-md-inline">
                    <span class="brand-text">Glam</span><span class="brand-accent">Book</span>
                </span>
                <span class="d-inline d-md-none">
                    <span class="brand-accent">GB</span>
                </span>
                <span class="sr-only">GlamBook Scheduler</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarContent" aria-controls="navbarContent" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item-luxury">
                        <a class="nav-link-luxury {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}" aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                            <i class="fas fa-home nav-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item-luxury">
                        <a class="nav-link-luxury {{ request()->routeIs('tasks.*') ? 'active' : '' }}" 
                           href="{{ route('tasks.index') }}" aria-current="{{ request()->routeIs('tasks.*') ? 'page' : 'false' }}">
                            <i class="fas fa-calendar-alt nav-icon"></i>
                            <span>Appointments</span>
                        </a>
                    </li>
                </ul>

                <!-- User Area -->
                <div class="user-area">
                    @auth
                    <div class="user-greeting d-none d-md-flex">
                        <i class="fas fa-user-circle"></i>
                        <span>Welcome, {{ Auth::user()->name }}!</span>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn logout-btn" aria-label="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="d-none d-md-inline">Logout</span>
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            @if(Request::routeIs('tasks.calendar'))
                <div class="mb-4">
                    <h1 class="display-title">
                        <i class="fas fa-calendar-alt me-3"></i>
                        Appointment Calendar
                    </h1>
                    <p class="text-muted fs-5">
                        <i class="fas fa-sparkle text-warning me-2"></i>
                        View and manage all your beauty appointments in one place
                    </p>
                </div>
            @endif
            
            <div class="luxury-card">
                <div class="p-4 p-lg-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <!-- Luxury Footer -->
    <footer class="luxury-footer">
        <div class="container">
            <div class="text-center">
                <div class="footer-brand">
                    GlamBook Scheduler
                </div>
                
                <div class="footer-links">
                    <a href="{{ route('dashboard') }}" class="footer-link">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('tasks.index') }}" class="footer-link">
                        <i class="fas fa-calendar-alt me-2"></i>Appointments
                    </a>
                    <a href="{{ route('tasks.create') }}" class="footer-link">
                        <i class="fas fa-plus-circle me-2"></i>New Booking
                    </a>
                </div>
                
                <div class="mt-4 pt-3 border-top border-light">
                    <p class="text-muted mb-2">
                        <i class="fas fa-heart text-danger me-2"></i>
                        Crafted with elegance for the modern beauty professional
                    </p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-copyright me-2"></i>
                        {{ date('Y') }} GlamBook Scheduler
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    
    <script>
        // Helper function for service icons
        function getServiceIcon(serviceType) {
            const icons = {
                'makeup': '💄',
                'hair': '💇‍♀️',
                'nails': '💅',
                'bridal': '👰',
                'facial': '✨',
                'massage': '💆‍♀️',
                'waxing': '✨',
                'spa': '🛁',
                'eyelash': '👁️',
                'eyebrow': '✏️',
                'default': '📅'
            };
            
            if (!serviceType) return icons['default'];
            
            const key = serviceType.toLowerCase().replace(/[^a-z]/g, '');
            return icons[key] || icons['default'];
        }

        // Helper function to get event class based on status
        function getEventClass(status, isDone) {
            if (isDone) return 'event-completed';
            
            const statusMap = {
                'scheduled': 'event-scheduled',
                'completed': 'event-completed',
                'cancelled': 'event-cancelled',
                'confirmed': 'event-scheduled',
                'pending': 'event-urgent',
                'no-show': 'event-cancelled'
            };
            return statusMap[status?.toLowerCase()] || 'event-scheduled';
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            document.querySelectorAll('.notification').forEach(n => n.remove());
            
            const notification = document.createElement('div');
            notification.className = `notification`;
            notification.setAttribute('role', 'alert');
            notification.setAttribute('aria-live', 'polite');
            
            const icon = type === 'info' ? 'info-circle' : 'check-circle';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${icon} me-3" style="color: ${type === 'info' ? 'var(--deep-rose)' : '#28a745'}"></i>
                    <div class="flex-grow-1">${message}</div>
                    <button class="btn-close" onclick="this.parentElement.parentElement.remove()" 
                            aria-label="Close notification"></button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideOutRight 0.5s ease forwards';
                    setTimeout(() => notification.remove(), 500);
                }
            }, 4000);
        }

        // Initialize calendar with proper event rendering
        document.addEventListener('DOMContentLoaded', function() {
            // Navbar scroll effect
            const navbar = document.getElementById('mainNavbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

            // Initialize calendar if element exists
            const calendarEl = document.getElementById('calendar');
            if (calendarEl) {
                initGlamBookCalendar(calendarEl);
            }

            // Add hover effect to cards
            const cards = document.querySelectorAll('.luxury-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-5px)';
                    card.style.boxShadow = 'var(--shadow-lg)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                });
            });
        });

        // GlamBook Calendar Initialization
        function initGlamBookCalendar(element) {
            // Show loading state
            element.innerHTML = `
                <div class="text-center py-5">
                    <div class="loading-spinner" style="width: 40px; height: 40px; margin: 0 auto;"></div>
                    <p class="mt-3 text-muted">Loading appointments...</p>
                </div>
            `;

            // Fetch events from your API endpoint
            function fetchEvents(fetchInfo, successCallback, failureCallback) {
                const start = fetchInfo.startStr;
                const end = fetchInfo.endStr;
                
                console.log('Fetching events from:', start, 'to', end);
                
                // Use your existing calendarEvents route
                fetch(`/api/calendar/events?start=${start}&end=${end}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(events => {
                    console.log('Events fetched successfully:', events.length);
                    
                    // Transform events to include proper styling
                    const formattedEvents = events.map(event => {
                        // Get client name from extendedProps
                        const clientName = event.extendedProps?.client || 
                                         (event.extendedProps?.first_name ? 
                                          `${event.extendedProps.first_name} ${event.extendedProps.last_name}` : 
                                          'Client');
                        
                        // Get service type
                        const serviceType = event.extendedProps?.service || 'Appointment';
                        
                        // Determine event class
                        const eventClass = getEventClass(event.extendedProps?.status, event.extendedProps?.is_done);
                        
                        // Get service icon
                        const serviceIcon = getServiceIcon(serviceType);
                        
                        return {
                            id: event.id,
                            title: `${serviceType} - ${clientName}`,
                            start: event.start,
                            end: event.end,
                            allDay: event.allDay || false,
                            className: eventClass,
                            extendedProps: {
                                ...event.extendedProps,
                                client: clientName,
                                service: serviceType,
                                serviceIcon: serviceIcon,
                                formatted_time: event.extendedProps?.time || '',
                                formatted_end_time: event.extendedProps?.end_time || '',
                                duration: event.extendedProps?.duration || '60 min',
                                price: event.extendedProps?.price || 'N/A',
                                notes: event.extendedProps?.notes || ''
                            }
                        };
                    });
                    
                    successCallback(formattedEvents);
                    
                    // Show success notification
                    if (formattedEvents.length > 0) {
                        showNotification(`📅 Loaded ${formattedEvents.length} appointments`, 'success');
                    }
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    
                    // Fallback to sample data
                    const sampleEvents = getSampleEvents();
                    successCallback(sampleEvents);
                    
                    showNotification('⚠️ Using sample data - API connection failed', 'warning');
                });
            }

            // Sample data for fallback
            function getSampleEvents() {
                const now = new Date();
                const events = [];
                
                // Create sample events for the next 7 days
                for (let i = 1; i <= 5; i++) {
                    const date = new Date(now);
                    date.setDate(date.getDate() + i);
                    
                    events.push({
                        id: `sample-${i}-1`,
                        title: 'Makeup Session - Sarah Johnson',
                        start: new Date(date.setHours(10, 0, 0, 0)),
                        end: new Date(date.setHours(12, 0, 0, 0)),
                        className: 'event-scheduled',
                        extendedProps: {
                            client: 'Sarah Johnson',
                            phone: '+1 (555) 123-4567',
                            service: 'Makeup',
                            price: '$150.00',
                            status: 'scheduled',
                            serviceIcon: '💄',
                            formatted_time: '10:00 AM',
                            formatted_end_time: '12:00 PM',
                            duration: '120 min',
                            notes: 'Bridal trial session'
                        }
                    });
                    
                    events.push({
                        id: `sample-${i}-2`,
                        title: 'Hair Styling - Emma Wilson',
                        start: new Date(date.setHours(14, 0, 0, 0)),
                        end: new Date(date.setHours(16, 0, 0, 0)),
                        className: 'event-completed',
                        extendedProps: {
                            client: 'Emma Wilson',
                            phone: '+1 (555) 987-6543',
                            service: 'Hair Styling',
                            price: '$120.00',
                            status: 'completed',
                            serviceIcon: '💇‍♀️',
                            formatted_time: '2:00 PM',
                            formatted_end_time: '4:00 PM',
                            duration: '120 min',
                            notes: 'Regular haircut and styling'
                        }
                    });
                }
                
                return events;
            }

            // Initialize calendar
            const calendar = new FullCalendar.Calendar(element, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                events: fetchEvents,
                eventMouseEnter: function(info) {
                    info.el.style.zIndex = '1000';
                    info.el.style.boxShadow = 'var(--shadow-lg)';
                },
                eventMouseLeave: function(info) {
                    info.el.style.zIndex = '';
                    info.el.style.boxShadow = '';
                },
                eventClick: function(info) {
                    showEventDetails(info.event);
                },
                dayMaxEvents: 4,
                editable: true,
                selectable: true,
                selectMirror: true,
                weekNumbers: true,
                weekNumberCalculation: 'ISO',
                navLinks: true,
                businessHours: {
                    daysOfWeek: [1, 2, 3, 4, 5, 6],
                    startTime: '09:00',
                    endTime: '19:00'
                },
                eventContent: function(arg) {
                    const task = arg.event.extendedProps;
                    const serviceIcon = task.serviceIcon || getServiceIcon(task.service);
                    const statusClass = arg.event.classNames.includes('event-completed') ? 'badge-completed' :
                                      arg.event.classNames.includes('event-cancelled') ? 'badge-cancelled' :
                                      'badge-scheduled';
                    
                    return {
                        html: `
                            <div class="event-content">
                                <div class="service-icon">${serviceIcon}</div>
                                <div class="event-details">
                                    <div class="fc-event-title">${task.service}</div>
                                    <div class="fc-event-time">
                                        <i class="fas fa-clock me-1" style="font-size: 0.6rem;"></i>
                                        ${task.formatted_time || ''}
                                        ${task.formatted_end_time ? ` - ${task.formatted_end_time}` : ''}
                                    </div>
                                    <div class="fc-event-client">
                                        <i class="fas fa-user me-1" style="font-size: 0.6rem;"></i>
                                        ${task.client}
                                    </div>
                                    <span class="badge ${statusClass}" style="margin-top: 4px; padding: 2px 6px; font-size: 0.6rem;">
                                        ${task.status || 'scheduled'}
                                    </span>
                                </div>
                            </div>
                        `
                    };
                },
                loading: function(isLoading) {
                    if (isLoading) {
                        element.innerHTML = `
                            <div class="text-center py-5">
                                <div class="loading-spinner" style="width: 40px; height: 40px; margin: 0 auto;"></div>
                                <p class="mt-3 text-muted">Loading appointments...</p>
                            </div>
                        `;
                    }
                }
            });
            
            calendar.render();
            
            // Store calendar instance globally
            window.calendar = calendar;
        }

        // Show event details popup
        function showEventDetails(event) {
            // Remove existing popups
            document.querySelectorAll('.event-details-popup').forEach(p => p.remove());
            
            const task = event.extendedProps;
            const popup = document.createElement('div');
            popup.className = 'event-details-popup';
            
            // Calculate position
            const rect = event.el.getBoundingClientRect();
            popup.style.position = 'fixed';
            popup.style.top = `${rect.bottom + window.scrollY + 10}px`;
            popup.style.left = `${Math.max(10, rect.left)}px`;
            
            // Determine status badge
            let statusBadge = '';
            if (event.classNames.includes('event-completed')) {
                statusBadge = '<span class="badge badge-completed ms-2">Completed</span>';
            } else if (event.classNames.includes('event-cancelled')) {
                statusBadge = '<span class="badge badge-cancelled ms-2">Cancelled</span>';
            } else {
                statusBadge = '<span class="badge badge-scheduled ms-2">Scheduled</span>';
            }
            
            popup.innerHTML = `
                <div class="event-details-header">
                    <div class="event-details-title">
                        ${task.serviceIcon || getServiceIcon(task.service)} ${task.service}
                        ${statusBadge}
                    </div>
                    <div class="text-muted">Appointment #${task.task_id || event.id}</div>
                </div>
                
                <div class="event-details-body">
                    <div class="event-detail-row">
                        <i class="fas fa-user event-detail-icon"></i>
                        <div>
                            <strong>Client:</strong> ${task.client}
                        </div>
                    </div>
                    
                    ${task.phone ? `
                    <div class="event-detail-row">
                        <i class="fas fa-phone event-detail-icon"></i>
                        <div>
                            <strong>Phone:</strong> ${task.phone}
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="event-detail-row">
                        <i class="fas fa-clock event-detail-icon"></i>
                        <div>
                            <strong>Time:</strong> ${task.formatted_time || 'N/A'}
                            ${task.formatted_end_time ? ` to ${task.formatted_end_time}` : ''}
                        </div>
                    </div>
                    
                    ${task.duration ? `
                    <div class="event-detail-row">
                        <i class="fas fa-hourglass-half event-detail-icon"></i>
                        <div>
                            <strong>Duration:</strong> ${task.duration}
                        </div>
                    </div>
                    ` : ''}
                    
                    ${task.price && task.price !== 'N/A' ? `
                    <div class="event-detail-row">
                        <i class="fas fa-dollar-sign event-detail-icon"></i>
                        <div>
                            <strong>Price:</strong> ${task.price}
                        </div>
                    </div>
                    ` : ''}
                    
                    ${task.notes ? `
                    <div class="event-detail-row">
                        <i class="fas fa-sticky-note event-detail-icon"></i>
                        <div>
                            <strong>Notes:</strong> ${task.notes}
                        </div>
                    </div>
                    ` : ''}
                    
                    ${task.edit_url ? `
                    <div class="event-detail-row mt-3">
                        <a href="${task.edit_url}" class="btn btn-sm" style="background: var(--gradient-rose); color: white; border: none; padding: 0.5rem 1rem;">
                            <i class="fas fa-edit me-2"></i>
                            Edit Appointment
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.body.appendChild(popup);
            
            // Close popup when clicking outside
            setTimeout(() => {
                const closePopup = (e) => {
                    if (!popup.contains(e.target) && !event.el.contains(e.target)) {
                        popup.remove();
                        document.removeEventListener('click', closePopup);
                    }
                };
                document.addEventListener('click', closePopup);
            }, 100);
        }

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOutRight {
                from { 
                    transform: translateX(0); 
                    opacity: 1; 
                }
                to { 
                    transform: translateX(100%); 
                    opacity: 0; 
                }
            }
            
            /* Custom button styles */
            .btn-rose {
                background: var(--gradient-rose);
                color: white;
                border: none;
                border-radius: 12px;
                padding: 0.5rem 1.5rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }
            
            .btn-rose:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-rose);
                color: white;
            }
            
            /* Ensure all calendar buttons are pink */
            .fc .fc-button-primary {
                background-color: var(--primary-rose) !important;
                border-color: var(--deep-rose) !important;
            }
            
            .fc .fc-button-primary:hover {
                background-color: var(--deep-rose) !important;
                border-color: var(--deep-rose) !important;
            }
            
            .fc .fc-button-primary:disabled {
                background-color: rgba(232, 180, 184, 0.5) !important;
                border-color: rgba(232, 180, 184, 0.3) !important;
            }
            
            /* Fix toolbar spacing */
            .fc-toolbar-chunk {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }
            
            .fc .fc-button-group {
                gap: 0.5rem;
            }
            
            /* Custom scrollbar for calendar */
            .fc-scroller::-webkit-scrollbar {
                width: 6px;
            }
            
            .fc-scroller::-webkit-scrollbar-track {
                background: var(--soft-rose);
                border-radius: 10px;
            }
            
            .fc-scroller::-webkit-scrollbar-thumb {
                background: var(--gradient-rose);
                border-radius: 10px;
            }
            
            .fc-scroller::-webkit-scrollbar-thumb:hover {
                background: var(--deep-rose);
            }
        `;
        document.head.appendChild(style);
    </script>

    @yield('scripts')
</body>
</html>