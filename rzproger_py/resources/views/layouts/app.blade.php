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

        /* Header Styles */
        .navbar {
            padding: 0;
            height: var(--header-height);
            transition: all 0.3s ease;
            background: linear-gradient(to right, rgba(33, 37, 41, 0.95), rgba(107, 66, 38, 0.9)) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        }

        .navbar.scrolled {
            height: var(--header-height-scrolled);
            background: linear-gradient(to right, rgba(33, 37, 41, 0.98), rgba(107, 66, 38, 0.95)) !important;
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.3);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--secondary-color) !important;
            display: flex;
            align-items: center;
            height: 100%;
            padding: 0;
        }

        .navbar-brand i {
            font-size: 2rem;
            margin-right: 12px;
        }

        .nav-link {
            font-weight: 500;
            position: relative;
            padding: 0 15px;
            color: rgba(255, 255, 255, 0.85) !important;
            display: flex;
            align-items: center;
            height: 100%;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            background: var(--secondary-color);
            bottom: 0;
            left: 0;
            transition: width 0.3s;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .nav-item {
            height: 100%;
            display: flex;
            align-items: stretch;
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

        /* Breadcrumb styles */
        .breadcrumb-container {
            background-color: var(--light-bg);
            padding: 15px 0;
            box-shadow: inset 0 -1px 0 rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .breadcrumb {
            margin-bottom: 0;
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: var(--primary-color-hover);
            text-decoration: underline;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            content: "\f105";
            color: #6c757d;
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

        /* Dropdown menu styling */
        .dropdown-menu {
            border: none;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            padding: 10px;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .dropdown-item {
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.2s;
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background-color: var(--light-bg);
            color: var(--primary-color);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 8px;
            text-align: center;
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

        /* Custom animation for alerts */
        .custom-alert {
            animation: slideIn 0.5s forwards;
            transform: translateX(100%);
        }

        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }

        /* Custom toast notification */
        .toast-notification {
            position: fixed;
            top: calc(var(--header-height) + 20px);
            right: 20px;
            z-index: 1060;
            max-width: 350px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-radius: 10px;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-20px);
            animation: toastIn 0.5s forwards;
        }

        @keyframes toastIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toast-header {
            background: var(--primary-color);
            color: white;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .toast-body {
            padding: 15px;
        }

        .toast-close {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
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

    <!-- Status message for successful booking or other notifications -->
    @if(session('success') && Route::is('bookings.*'))
    <div class="booking-success">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            <button type="button" class="close-notification" onclick="this.parentElement.parentElement.style.display='none';">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Status message for other notifications -->
    @if((session('success') && !Route::is('bookings.*')) || session('error'))
    <div class="status-message">
        @if(session('success') && !Route::is('bookings.*'))
        <div class="alert alert-success alert-dismissible fade show custom-alert" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>
    @endif

    <!-- Breadcrumbs for inner pages -->
    @if(!Route::is('home'))
    <div class="breadcrumb-container">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home me-1"></i>Главная</a></li>
                    @yield('breadcrumbs')
                    @if(Route::is('bookings.index'))
                    <li class="breadcrumb-item active" aria-current="page">Мои бронирования</li>
                    @elseif(Route::is('rooms.index'))
                    <li class="breadcrumb-item active" aria-current="page">Номера</li>
                    @elseif(Route::is('about'))
                    <li class="breadcrumb-item active" aria-current="page">О нас</li>
                    @elseif(Route::is('contact'))
                    <li class="breadcrumb-item active" aria-current="page">Контакты</li>
                    @elseif(Route::is('bookings.show'))
                    <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Мои бронирования</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Бронирование #{{ request()->route('booking')->id }}</li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
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

            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                const body = document.body;
                const statusMessage = document.querySelector('.status-message');

                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                    body.classList.add('scrolled');
                    if (statusMessage) statusMessage.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                    body.classList.remove('scrolled');
                    if (statusMessage) statusMessage.classList.remove('scrolled');
                }
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert:not(.booking-success)');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    @yield('scripts')
</body>
</html>
