<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LuxuryStay') }} - @yield('title', 'Welcome')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Nunito:wght@300;400;600;700&display=swap">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        :root {
            --primary-color: #6b4226;
            --primary-color-hover: #5a371f;
            --secondary-color: #d5b788;
            --text-color: #333333;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --header-height: 80px;
            --header-height-scrolled: 60px;
        }

        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text-color);
            padding-top: var(--header-height); /* Add padding for fixed navbar */
        }

        h1, h2, h3, h4, h5, .navbar-brand {
            font-family: 'Playfair Display', serif;
        }

        .main-content {
            flex: 1;
        }

        /* Enhanced Header Styles */
        .navbar {
            padding: 0;
            height: var(--header-height);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: linear-gradient(135deg, rgba(33, 37, 41, 0.95) 0%, rgba(107, 66, 38, 0.9) 100%) !important;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
        }

        .navbar.scrolled {
            height: var(--header-height-scrolled);
            background: linear-gradient(135deg, rgba(33, 37, 41, 0.98) 0%, rgba(107, 66, 38, 0.95) 100%) !important;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.25);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--secondary-color) !important;
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            color: #fff !important;
        }

        .navbar-brand i {
            font-size: 2rem;
            margin-right: 12px;
            animation: pulse 3s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--secondary-color), #fff);
            transition: width 0.4s ease;
        }

        .navbar-brand:hover::after {
            width: 100%;
        }

        .nav-link {
            font-weight: 500;
            position: relative;
            padding: 0 20px;
            color: rgba(255, 255, 255, 0.85) !important;
            display: flex;
            align-items: center;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            overflow: hidden;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            transform: translateY(-2px);
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 255, 255, 0.1), rgba(213, 183, 136, 0.2));
            transform: translateX(-100%);
            transition: transform 0.4s ease;
        }

        .nav-link:hover::before {
            transform: translateX(0);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), #fff);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-radius: 2px 2px 0 0;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 80%;
        }

        .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover i {
            transform: scale(1.2) rotate(5deg);
        }

        .nav-item {
            height: 100%;
            display: flex;
            align-items: stretch;
            position: relative;
        }

        /* Special effects for user dropdown */
        .nav-item.dropdown {
            position: relative;
        }

        .nav-item.dropdown::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -10px;
            width: 6px;
            height: 6px;
            background: var(--secondary-color);
            border-radius: 50%;
            transform: translateY(-50%) scale(0);
            transition: transform 0.3s ease;
        }

        .nav-item.dropdown:hover::before {
            transform: translateY(-50%) scale(1);
        }

        /* Enhanced dropdown */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 15px;
            margin-top: 15px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-10px);
            opacity: 0;
        }

        .dropdown-menu.show {
            transform: translateY(0);
            opacity: 1;
        }

        .dropdown-item {
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dropdown-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(107, 66, 38, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .dropdown-item:hover::before {
            left: 100%;
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background: linear-gradient(135deg, var(--light-bg), rgba(107, 66, 38, 0.05));
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover i {
            transform: scale(1.2);
            color: var(--primary-color);
        }

        /* Mobile navbar improvements */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: linear-gradient(135deg, rgba(33, 37, 41, 0.98), rgba(107, 66, 38, 0.95));
                border-radius: 0 0 15px 15px;
                padding: 20px;
                max-height: calc(100vh - var(--header-height));
                overflow-y: auto;
                margin-top: 10px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            }

            .nav-item {
                height: auto;
                margin: 5px 0;
            }

            .nav-link {
                padding: 15px 20px;
                height: auto;
                border-radius: 8px;
                margin: 2px 0;
            }

            .nav-link::after {
                bottom: 10px;
                left: 20px;
                transform: none;
            }

            .nav-link:hover::after, .nav-link.active::after {
                width: calc(100% - 40px);
            }
        }

        /* Add glow effect on active pages */
        .navbar .nav-link.active {
            position: relative;
        }

        .navbar .nav-link.active::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(213, 183, 136, 0.2) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            z-index: -1;
        }

        /* Alert Styles */
        .alert-wrapper {
            position: fixed;
            top: var(--header-height);
            left: 0;
            width: 100%;
            z-index: 999;
            transition: top 0.3s;
        }

        .scrolled .alert-wrapper {
            top: var(--header-height-scrolled);
        }

        .alert {
            margin-bottom: 0;
            border-radius: 0;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        /* Page title styles */
        .page-title {
            background-color: var(--light-bg);
            padding: 40px 0;
            margin-bottom: 40px;
            position: relative;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }

        .page-title h1 {
            margin-bottom: 10px;
            color: var(--primary-color);
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 180px 0 120px;
            margin-top: calc(-1 * var(--header-height)); /* Adjust for the header */
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
        }

        /* Enhanced dropdown menu styling */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 15px;
            margin-top: 15px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .dropdown-item {
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dropdown-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(107, 66, 38, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .dropdown-item:hover::before {
            left: 100%;
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background: linear-gradient(135deg, var(--light-bg), rgba(107, 66, 38, 0.05));
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 12px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover i {
            transform: scale(1.2);
            color: var(--primary-color);
        }

        /* Status message styles */
        .status-message {
            position: fixed;
            top: calc(var(--header-height) + 10px);
            right: 15px;
            max-width: 400px;
            min-width: 300px;
            z-index: 1050;
            transition: top 0.3s;
        }

        .scrolled .status-message {
            top: calc(var(--header-height-scrolled) + 10px);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-color-hover);
            border-color: var(--primary-color-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .section-title {
            position: relative;
            margin-bottom: 25px;
            padding-bottom: 15px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            width: 70px;
            height: 3px;
            background: var(--secondary-color);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .booking-form {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .room-card {
            transition: all 0.4s ease;
            margin-bottom: 30px;
            height: 100%;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .room-card img {
            height: 220px;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .room-card:hover img {
            transform: scale(1.05);
        }

        .room-card .card-body {
            padding: 20px;
        }

        .room-card .card-title {
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .feature-box {
            text-align: center;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 10px;
            transition: all 0.3s ease;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-box i {
            font-size: 3.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .feature-box:hover i {
            transform: scale(1.1);
            color: var(--primary-color);
        }

        .feature-box h4 {
            margin-bottom: 15px;
            font-weight: 700;
        }

        .footer {
            background-color: var(--dark-bg);
            color: white;
            padding: 60px 0 30px;
            margin-top: 70px;
        }

        .footer h4 {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 20px;
            color: var(--secondary-color);
        }

        .footer h4:after {
            content: '';
            position: absolute;
            width: 50px;
            height: 2px;
            background: var(--secondary-color);
            bottom: 0;
            left: 0;
        }

        .footer a {
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: var(--secondary-color);
            padding-left: 5px;
        }

        .footer address p {
            margin-bottom: 10px;
        }

        .footer .social-icons {
            margin-top: 20px;
        }

        .footer .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .footer .social-icons a:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
        }

        /* Service Card Styles */
        .service-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            background-color: white;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .service-card .service-icon {
            width: 70px;
            height: 70px;
            background-color: var(--primary-color);
            border-radius: 50%;
            color: white;
            font-size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            background-color: var(--secondary-color);
            transform: scale(1.1);
        }

        /* Animation for room cards */
        @keyframes floatAnimation {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .animate-float {
            animation: floatAnimation 3s ease-in-out infinite;
        }

        .search-btn {
            padding: 0;
            height: 54px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .search-btn i {
            font-size: 1.5rem;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(107, 66, 38, 0.3);
        }

        /* Media Queries */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: linear-gradient(rgba(33, 37, 41, 0.98), rgba(107, 66, 38, 0.95));
                border-radius: 0 0 10px 10px;
                padding: 15px;
                max-height: calc(100vh - var(--header-height));
                overflow-y: auto;
            }

            .nav-item {
                height: auto;
            }

            .nav-link {
                padding: 10px 15px;
                height: auto;
            }

            .nav-link::after {
                bottom: 5px;
            }
        }

        /* Booking Success Notification */
        .booking-success {
            position: fixed;
            top: var(--header-height);
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 0;
            z-index: 1055;
            box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
            transform: translateY(-100%);
            animation: slideInTop 0.6s ease-out forwards;
            transition: top 0.3s ease;
        }

        .scrolled .booking-success {
            top: var(--header-height-scrolled);
        }

        @keyframes slideInTop {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }

        .booking-success .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .booking-success i {
            font-size: 1.2rem;
            margin-right: 10px;
            color: #fff;
        }

        .close-notification {
            background: none;
            border: none;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        .close-notification:hover {
            background: rgba(255, 255, 255, 0.2);
            opacity: 1;
            transform: scale(1.1);
        }

        /* Toast notification improvements */
        .toast-notification {
            position: fixed;
            top: calc(var(--header-height) + 20px);
            right: 20px;
            z-index: 1060;
            max-width: 400px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 12px;
            overflow: hidden;
            opacity: 0;
            transform: translateX(100%);
            animation: toastSlideIn 0.6s ease-out forwards;
            border-left: 5px solid #28a745;
        }

        @keyframes toastSlideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .scrolled .toast-notification {
            top: calc(var(--header-height-scrolled) + 20px);
        }

        .toast-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
        }

        .toast-body {
            padding: 20px;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .toast-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .toast-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        /* Alternative success notification style */
        .success-banner {
            position: fixed;
            top: var(--header-height);
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px 0;
            z-index: 1055;
            box-shadow: 0 5px 25px rgba(40, 167, 69, 0.4);
            transform: translateY(-100%);
            animation: successBannerSlide 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        @keyframes successBannerSlide {
            0% {
                transform: translateY(-100%);
            }
            70% {
                transform: translateY(10px);
            }
            100% {
                transform: translateY(0);
            }
        }

        .success-banner .success-content {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .success-banner .success-icon {
            font-size: 1.5rem;
            margin-right: 15px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .success-banner .close-success {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
            opacity: 0.9;
        }

        .success-banner .close-success:hover {
            background: rgba(255, 255, 255, 0.2);
            opacity: 1;
            transform: translateY(-50%) rotate(90deg);
        }
    </style>
    @yield('styles')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-hotel"></i>Люкс Отель
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('rooms.index') ? 'active' : '' }}" href="{{ route('rooms.index') }}">Номера</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('about') ? 'active' : '' }}" href="{{ route('about') }}">О нас</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Контакты</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i> Вход
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i> Регистрация
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('bookings.index') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                                    <i class="fas fa-calendar-check me-1"></i> Мои бронирования
                                </a>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cogs me-1"></i> Панель администратора
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-1"></i> Выход
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Success Banner for Booking -->
    @if(session('success') && Route::is('bookings.*'))
    <div class="success-banner" id="successBanner">
        <div class="container">
            <div class="success-content">
                <i class="fas fa-check-circle success-icon"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="close-success" onclick="closeSuccessBanner()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Toast Notifications for other messages -->
    @if((session('success') && !Route::is('bookings.*')) || session('error'))
        @if(session('success') && !Route::is('bookings.*'))
        <div class="toast-notification" id="successToast">
            <div class="toast-header">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Успешно!</strong>
                <button type="button" class="toast-close" onclick="closeToast('successToast')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast-notification" id="errorToast" style="border-left-color: #dc3545;">
            <div class="toast-header" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Ошибка!</strong>
                <button type="button" class="toast-close" onclick="closeToast('errorToast')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
        @endif
    @endif

    <main class="main-content">
        @yield('hero')

        <div class="container py-4">
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4>Люкс Отель</h4>
                    <p>Почувствуйте непревзойденную роскошь в наших номерах премиум-класса. Идеально подходит как для деловых путешественников, так и для отдыхающих.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-tripadvisor"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h4>Быстрые ссылки</h4>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}"><i class="fas fa-angle-right me-2"></i> Главная</a></li>
                        <li><a href="{{ route('rooms.index') }}"><i class="fas fa-angle-right me-2"></i> Номера</a></li>
                        <li><a href="{{ route('about') }}"><i class="fas fa-angle-right me-2"></i> О нас</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fas fa-angle-right me-2"></i> Контакты</a></li>
                        @guest
                            <li><a href="{{ route('login') }}"><i class="fas fa-angle-right me-2"></i> Вход</a></li>
                            <li><a href="{{ route('register') }}"><i class="fas fa-angle-right me-2"></i> Регистрация</a></li>
                        @else
                            <li><a href="{{ route('bookings.index') }}"><i class="fas fa-angle-right me-2"></i> Мои бронирования</a></li>
                        @endguest
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h4>Связаться с нами</h4>
                    <address>
                        <p><i class="fas fa-map-marker-alt me-2"></i> ул. Люкс, 123, г. Бишкек, Кыргызстан</p>
                        <p><i class="fas fa-phone me-2"></i> +996 312 123 456</p>
                        <p><i class="fas fa-envelope me-2"></i> info@luxotel.kg</p>
                        <p><i class="fas fa-clock me-2"></i> Круглосуточно, 7 дней в неделю</p>
                    </address>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Люкс Отель. Все права защищены.</p>
            </div>
        </div>
    </footer>

    <!-- Admin indicator for administrators -->
    @auth
        @if(Auth::user()->isAdmin())
        <div class="admin-indicator">
            <i class="fas fa-shield-alt me-1"></i> Режим администратора
        </div>
        @endif
    @endauth

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animation library
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });

        // Function to close success banner
        function closeSuccessBanner() {
            const banner = document.getElementById('successBanner');
            if (banner) {
                banner.style.animation = 'successBannerSlideOut 0.5s ease-in forwards';
                setTimeout(function() {
                    banner.remove();
                    // Reset body padding
                    document.body.style.paddingTop = 'var(--header-height)';
                }, 500);
            }
        }

        // Function to close toast notifications
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'toastSlideOut 0.4s ease-in forwards';
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }
        }

        // Auto-hide toast notifications and enhanced effects
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    if (toast.id === 'successToast') {
                        closeToast('successToast');
                    } else if (toast.id === 'errorToast') {
                        closeToast('errorToast');
                    }
                }, 6000); // Auto-hide after 6 seconds
            });

            // Enhanced dropdown animations
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('shown.bs.dropdown', function() {
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu) {
                        dropdownMenu.style.animation = 'dropdownFadeIn 0.4s ease-out forwards';
                    }
                });
            });

            // Add interactive hover effects to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    if (!this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(-2px) scale(1.02)';
                    }
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active') && !this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
        });

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                const body = document.body;
                const statusMessage = document.querySelector('.status-message');
                const successBanner = document.querySelector('.success-banner');

                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                    body.classList.add('scrolled');
                    if (statusMessage) statusMessage.classList.add('scrolled');
                    if (successBanner) successBanner.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                    body.classList.remove('scrolled');
                    if (statusMessage) statusMessage.classList.remove('scrolled');
                    if (successBanner) successBanner.classList.remove('scrolled');
                }
            });

        // Function to close success banner
        function closeSuccessBanner() {
            const banner = document.getElementById('successBanner');
            if (banner) {
                banner.style.animation = 'successBannerSlideOut 0.5s ease-in forwards';
                setTimeout(function() {
                    banner.remove();
                    // Reset body padding
                    document.body.style.paddingTop = 'var(--header-height)';
                }, 500);
            }
        }

        // Function to close toast notifications
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'toastSlideOut 0.4s ease-in forwards';
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }
        }

        // Auto-hide toast notifications and enhanced effects
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    if (toast.id === 'successToast') {
                        closeToast('successToast');
                    } else if (toast.id === 'errorToast') {
                        closeToast('errorToast');
                    }
                }, 6000); // Auto-hide after 6 seconds
            });

            // Enhanced dropdown animations
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('shown.bs.dropdown', function() {
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu) {
                        dropdownMenu.style.animation = 'dropdownFadeIn 0.4s ease-out forwards';
                    }
                });
            });

            // Add interactive hover effects to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    if (!this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(-2px) scale(1.02)';
                    }
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active') && !this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
        });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert:not(.booking-success)');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });

        // Function to close success banner
        function closeSuccessBanner() {
            const banner = document.getElementById('successBanner');
            if (banner) {
                banner.style.animation = 'successBannerSlideOut 0.5s ease-in forwards';
                setTimeout(function() {
                    banner.remove();
                    // Reset body padding
                    document.body.style.paddingTop = 'var(--header-height)';
                }, 500);
            }
        }

        // Function to close toast notifications
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'toastSlideOut 0.4s ease-in forwards';
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }
        }

        // Auto-hide toast notifications and enhanced effects
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    if (toast.id === 'successToast') {
                        closeToast('successToast');
                    } else if (toast.id === 'errorToast') {
                        closeToast('errorToast');
                    }
                }, 6000); // Auto-hide after 6 seconds
            });

            // Enhanced dropdown animations
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('shown.bs.dropdown', function() {
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu) {
                        dropdownMenu.style.animation = 'dropdownFadeIn 0.4s ease-out forwards';
                    }
                });
            });

            // Add interactive hover effects to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    if (!this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(-2px) scale(1.02)';
                    }
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active') && !this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
        });
            }, 5000);

            // Auto-hide success banner after 7 seconds
            const successBanner = document.getElementById('successBanner');
            if (successBanner) {
                setTimeout(function() {
                    closeSuccessBanner();
                }, 7000);

                // Add scroll adjustment for content
                document.body.style.paddingTop = (parseInt(getComputedStyle(document.body).paddingTop) + successBanner.offsetHeight) + 'px';
            }
        });

        // Function to close success banner
        function closeSuccessBanner() {
            const banner = document.getElementById('successBanner');
            if (banner) {
                banner.style.animation = 'successBannerSlideOut 0.5s ease-in forwards';
                setTimeout(function() {
                    banner.remove();
                    // Reset body padding
                    document.body.style.paddingTop = 'var(--header-height)';
                }, 500);
            }
        }

        // Function to close toast notifications
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'toastSlideOut 0.4s ease-in forwards';
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }
        }

        // Auto-hide toast notifications and enhanced effects
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    if (toast.id === 'successToast') {
                        closeToast('successToast');
                    } else if (toast.id === 'errorToast') {
                        closeToast('errorToast');
                    }
                }, 6000); // Auto-hide after 6 seconds
            });

            // Enhanced dropdown animations
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('shown.bs.dropdown', function() {
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu) {
                        dropdownMenu.style.animation = 'dropdownFadeIn 0.4s ease-out forwards';
                    }
                });
            });

            // Add interactive hover effects to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    if (!this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(-2px) scale(1.02)';
                    }
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active') && !this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
        });

        // Function to close success banner
        function closeSuccessBanner() {
            const banner = document.getElementById('successBanner');
            if (banner) {
                banner.style.animation = 'successBannerSlideOut 0.5s ease-in forwards';
                setTimeout(function() {
                    banner.remove();
                    // Reset body padding
                    document.body.style.paddingTop = 'var(--header-height)';
                }, 500);
            }
        }

        // Function to close toast notifications
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'toastSlideOut 0.4s ease-in forwards';
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }
        }

        // Auto-hide toast notifications
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    if (toast.id === 'successToast') {
                        closeToast('successToast');
                    } else if (toast.id === 'errorToast') {
                        closeToast('errorToast');
                    }
                }, 6000); // Auto-hide after 6 seconds
            });

        // Function to close success banner
        function closeSuccessBanner() {
            const banner = document.getElementById('successBanner');
            if (banner) {
                banner.style.animation = 'successBannerSlideOut 0.5s ease-in forwards';
                setTimeout(function() {
                    banner.remove();
                    // Reset body padding
                    document.body.style.paddingTop = 'var(--header-height)';
                }, 500);
            }
        }

        // Function to close toast notifications
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'toastSlideOut 0.4s ease-in forwards';
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }
        }

        // Auto-hide toast notifications and enhanced effects
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    if (toast.id === 'successToast') {
                        closeToast('successToast');
                    } else if (toast.id === 'errorToast') {
                        closeToast('errorToast');
                    }
                }, 6000); // Auto-hide after 6 seconds
            });

            // Enhanced dropdown animations
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('shown.bs.dropdown', function() {
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu) {
                        dropdownMenu.style.animation = 'dropdownFadeIn 0.4s ease-out forwards';
                    }
                });
            });

            // Add interactive hover effects to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    if (!this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(-2px) scale(1.02)';
                    }
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active') && !this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
        });
        });

        // Function to close success banner
        function closeSuccessBanner() {
            const banner = document.getElementById('successBanner');
            if (banner) {
                banner.style.animation = 'successBannerSlideOut 0.5s ease-in forwards';
                setTimeout(function() {
                    banner.remove();
                    // Reset body padding
                    document.body.style.paddingTop = 'var(--header-height)';
                }, 500);
            }
        }

        // Function to close toast notifications
        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.style.animation = 'toastSlideOut 0.4s ease-in forwards';
                setTimeout(function() {
                    toast.remove();
                }, 400);
            }
        }

        // Auto-hide toast notifications and enhanced effects
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.toast-notification');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    if (toast.id === 'successToast') {
                        closeToast('successToast');
                    } else if (toast.id === 'errorToast') {
                        closeToast('errorToast');
                    }
                }, 6000); // Auto-hide after 6 seconds
            });

            // Enhanced dropdown animations
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(function(toggle) {
                toggle.addEventListener('shown.bs.dropdown', function() {
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu) {
                        dropdownMenu.style.animation = 'dropdownFadeIn 0.4s ease-out forwards';
                    }
                });
            });

            // Add interactive hover effects to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('mouseenter', function() {
                    if (!this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(-2px) scale(1.02)';
                    }
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active') && !this.parentElement.classList.contains('dropdown')) {
                        this.style.transform = 'translateY(0) scale(1)';
                    }
                });
            });
        });

        // Add CSS for slide out animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes successBannerSlideOut {
                0% {
                    transform: translateY(0);
                    opacity: 1;
                }
                100% {
                    transform: translateY(-100%);
                    opacity: 0;
                }
            }

            @keyframes toastSlideOut {
                0% {
                    transform: translateX(0);
                    opacity: 1;
                }
                100% {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }

            @keyframes dropdownFadeIn {
                0% {
                    opacity: 0;
                    transform: translateY(-10px) scale(0.95);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .scrolled .success-banner {
                top: var(--header-height-scrolled);
            }
        `;
        document.head.appendChild(style);
    </script>
    @yield('scripts')
</body>
</html>
