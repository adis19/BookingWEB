<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'LuxuryStay') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
            --sidebar-width: 250px;
            --topbar-height: 70px;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            overflow-x: hidden;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1040;
            transition: all var(--transition-speed);
        }

        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .sidebar-brand-icon {
            font-size: 1.25rem;
            color: white;
            margin-right: 0.75rem;
        }

        .sidebar-brand-text {
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }

        .sidebar-divider {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin: 1rem 0;
        }

        .sidebar-heading {
            padding: 0 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--secondary);
            letter-spacing: 0.05rem;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .nav-sidebar {
            padding-left: 0;
            list-style: none;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--dark);
            font-weight: 500;
            transition: all var(--transition-speed);
            border-left: 4px solid transparent;
        }

        .nav-link:hover {
            color: var(--primary);
            background-color: rgba(78, 115, 223, 0.05);
        }

        .nav-link.active {
            color: var(--primary);
            background-color: rgba(78, 115, 223, 0.1);
            border-left: 4px solid var(--primary);
        }

        .nav-link i {
            margin-right: 0.75rem;
            color: inherit;
            font-size: 1rem;
            width: 1.5rem;
            text-align: center;
        }

        /* Content */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
            transition: all var(--transition-speed);
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--topbar-height);
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1030;
            transition: all var(--transition-speed);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
        }

        .topbar .navbar-search {
            width: 25rem;
        }

        .topbar-divider {
            width: 0;
            border-right: 1px solid #e3e6f0;
            height: 2rem;
            margin: auto 1rem;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
        }

        .user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background-color: var(--light);
        }

        .user-image i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .user-name {
            margin-left: 0.75rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark);
        }

        /* Main Content */
        .main-content {
            padding: 1.5rem;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 1rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0;
        }

        /* Cards */
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid transparent;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
            transition: all var(--transition-speed);
        }

        .card:hover {
            box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.15);
            transform: translateY(-2px);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header:first-child {
            border-radius: 0.35rem 0.35rem 0 0;
        }

        .card-header-title {
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
        }

        .card-body {
            padding: 1.25rem;
        }

        /* Stat Cards */
        .stat-card {
            border-left: 0.25rem solid;
            border-radius: 0.35rem;
        }

        .stat-card-primary {
            border-left-color: var(--primary);
        }

        .stat-card-success {
            border-left-color: var(--success);
        }

        .stat-card-info {
            border-left-color: var(--info);
        }

        /* Enhanced Stat Cards */
        .stat-card {
            border-left: 0.25rem solid;
            border-radius: 0.35rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-card .card-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            margin-bottom: 0.75rem;
        }

        .stat-card .card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0;
        }

        .stat-card .card-value.small-text {
            font-size: 1.25rem;
        }

        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.2;
        }

        .stat-card-primary {
            border-left-color: var(--primary);
        }

        .stat-card-primary .card-title {
            color: var(--primary);
        }

        .stat-card-primary .stat-icon {
            color: var(--primary);
        }

        .stat-card-success {
            border-left-color: var(--success);
        }

        .stat-card-success .card-title {
            color: var(--success);
        }

        .stat-card-success .stat-icon {
            color: var(--success);
        }

        .stat-card-info {
            border-left-color: var(--info);
        }

        .stat-card-info .card-title {
            color: var(--info);
        }

        .stat-card-info .stat-icon {
            color: var(--info);
        }

        .stat-card-warning {
            border-left-color: var(--warning);
        }

        .stat-card-warning .card-title {
            color: var(--warning);
        }

        .stat-card-warning .stat-icon {
            color: var(--warning);
        }

        /* Booking Items */
        .booking-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .booking-item:last-child {
            border-bottom: none;
        }

        .booking-item:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        .booking-item.upcoming:hover {
            background-color: rgba(28, 200, 138, 0.05);
        }

        .booking-info .booking-id {
            font-weight: 700;
            color: var(--primary);
            font-size: 0.9rem;
        }

        .booking-info .booking-user {
            font-weight: 600;
            margin-top: 0.25rem;
            font-size: 0.95rem;
        }

        .booking-info .booking-room {
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }

        .booking-date {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark);
        }

        .booking-date.highlight {
            color: var(--success);
            font-weight: 700;
        }

        .booking-status {
            margin-top: 0.25rem;
        }

        .booking-guests {
            font-size: 0.85rem;
            color: var(--secondary);
            margin-top: 0.25rem;
        }

        .booking-actions {
            margin-top: 0.75rem;
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state p {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        /* Quick Action Buttons */
        .quick-action-btn {
            display: flex;
            align-items: center;
            padding: 1.25rem;
            background: white;
            border: 2px solid #e3e6f0;
            border-radius: 0.5rem;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s ease;
            height: 100%;
            min-height: 100px;
        }

        .quick-action-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1.5rem rgba(78, 115, 223, 0.15);
        }

        .quick-action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .quick-action-icon.bg-primary {
            background-color: var(--primary);
        }

        .quick-action-icon.bg-success {
            background-color: var(--success);
        }

        .quick-action-icon.bg-info {
            background-color: var(--info);
        }

        .quick-action-icon.bg-warning {
            background-color: var(--warning);
        }

        .quick-action-text {
            flex-grow: 1;
        }

        .quick-action-text span {
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Mobile Booking Cards */
        .booking-card {
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .booking-card:last-child {
            border-bottom: none;
        }

        .booking-card:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        .booking-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .booking-id {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .booking-card-body {
            margin-bottom: 1rem;
        }

        .booking-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .booking-info-item i {
            width: 20px;
            margin-right: 0.5rem;
            flex-shrink: 0;
        }

        .booking-info-item span {
            flex-grow: 1;
        }

        .booking-card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .booking-card-actions .btn {
            flex: 1;
            min-width: 90px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .stat-card .card-value {
                font-size: 1.5rem;
            }

            .stat-card .stat-icon {
                font-size: 2rem;
            }

            .booking-item {
                padding: 0.75rem;
            }

            .quick-action-btn {
                padding: 1rem;
                min-height: 80px;
            }

            .quick-action-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
                margin-right: 0.75rem;
            }

            .quick-action-text span {
                font-size: 0.85rem;
            }

            .booking-card-actions .btn {
                font-size: 0.75rem;
                padding: 0.375rem 0.5rem;
            }
        }

        /* Tables */
        .table-responsive {
            border-radius: 0.35rem;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05rem;
            background-color: #f8f9fc;
            color: var(--dark);
            padding: 1rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .table tbody tr {
            border-bottom: 1px solid #e3e6f0;
            transition: all var(--transition-speed);
        }

        .table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: var(--dark);
        }

        /* Forms */
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--dark);
        }

        .form-control, .form-select {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            color: var(--dark);
            transition: all var(--transition-speed);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.15rem rgba(78, 115, 223, 0.25);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Buttons */
        .btn {
            font-size: 0.85rem;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            border-radius: 0.35rem;
            letter-spacing: 0.02rem;
            transition: all var(--transition-speed);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            padding: 0;
        }

        .btn-icon-sm {
            width: 2rem;
            height: 2rem;
            font-size: 0.85rem;
        }

        /* Dropdown */
        .dropdown-menu {
            font-size: 0.85rem;
            border: 0;
            box-shadow: 0 0.5rem 1rem 0 rgba(0, 0, 0, 0.1);
            border-radius: 0.35rem;
        }

        .dropdown-item {
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            color: var(--dark);
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background-color: rgba(78, 115, 223, 0.05);
            color: var(--primary);
        }

        /* Alerts */
        .alert {
            border: 0;
            border-radius: 0.35rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(231, 74, 59, 0.1);
            color: var(--danger);
        }

        /* Modal Stability Fixes */
        .modal {
            z-index: 1055 !important;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        .modal.show .modal-dialog {
            transform: none !important;
        }

        .modal-dialog {
            pointer-events: none;
        }

        .modal-content {
            pointer-events: auto;
        }

        /* Prevent modal flashing/jumping */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out !important;
            transform: translate(0, -50px) !important;
        }

        .modal.show .modal-dialog {
            transform: none !important;
        }

        /* Prevent form resubmission issues */
        .modal form {
            margin: 0;
            padding: 0;
        }

        .modal-footer form {
            display: inline-block;
            margin: 0;
        }

        /* Disable animation for problematic modals */
        .modal.no-animation {
            animation: none !important;
        }

        .modal.no-animation .modal-dialog {
            transition: none !important;
            transform: none !important;
        }

        /* Mobile Booking Cards */
        .booking-card {
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        .booking-card:last-child {
            border-bottom: none;
        }

        .booking-card:hover {
            background-color: rgba(78, 115, 223, 0.05);
        }

        .booking-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .booking-id {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .booking-card-body {
            margin-bottom: 1rem;
        }

        .booking-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .booking-info-item i {
            width: 20px;
            margin-right: 0.5rem;
            font-size: 0.85rem;
        }

        .booking-info-item span {
            color: var(--dark);
        }

        .booking-card-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .booking-card-actions .btn {
            flex: 1;
            min-width: auto;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .content-wrapper, .topbar {
                margin-left: 0;
                left: 0;
            }

            body.sidebar-toggled .sidebar {
                transform: translateX(0);
            }

            body.sidebar-toggled .content-wrapper,
            body.sidebar-toggled .topbar {
                margin-left: var(--sidebar-width);
            }
        }

        /* Mobile specific styles */
        @media (max-width: 768px) {
            .booking-card-actions {
                flex-direction: column;
            }

            .booking-card-actions .btn {
                flex: none;
                width: 100%;
                margin-bottom: 0.25rem;
            }

            .table-responsive {
                font-size: 0.85rem;
            }

            .table th, .table td {
                padding: 0.5rem 0.25rem;
                vertical-align: middle;
            }

            .btn-group .btn {
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fas fa-hotel"></i>
            </div>
            <div class="sidebar-brand-text">Люкс Отель</div>
        </div>

        <div class="sidebar-divider"></div>

        <div class="sidebar-heading">Главное</div>

        <ul class="nav-sidebar">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Дашборд</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-divider"></div>

        <div class="sidebar-heading">Управление отелем</div>

        <ul class="nav-sidebar">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.room-types.*') ? 'active' : '' }}" href="{{ route('admin.room-types.index') }}">
                    <i class="fas fa-fw fa-bed"></i>
                    <span>Типы номеров</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.rooms.*') ? 'active' : '' }}" href="{{ route('admin.rooms.index') }}">
                    <i class="fas fa-fw fa-door-open"></i>
                    <span>Номера</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                    <i class="fas fa-fw fa-calendar-check"></i>
                    <span>Бронирования</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.extra-services.*') ? 'active' : '' }}" href="{{ route('admin.extra-services.index') }}">
                    <i class="fas fa-fw fa-concierge-bell"></i>
                    <span>Доп. услуги</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-divider"></div>

        <div class="sidebar-heading">Аналитика</div>

        <ul class="nav-sidebar">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-fw fa-chart-bar"></i>
                    <span>Отчеты</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.analytics.*') ? 'active' : '' }}" href="{{ route('admin.analytics.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Дашборд аналитики</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-divider"></div>

        <div class="sidebar-heading">Дополнительно</div>

        <ul class="nav-sidebar">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-fw fa-globe"></i>
                    <span>Перейти на сайт</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Topbar -->
        <div class="topbar">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fas fa-bars"></i>
            </button>

            <div class="d-none d-sm-inline-block form-inline ml-auto mr-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Поиск..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="ms-auto"></div>

            <div class="topbar-divider d-none d-sm-block"></div>

            <div class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-info">
                        <div class="user-image d-flex align-items-center justify-content-center">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="user-name d-none d-lg-inline">
                            {{ Auth::user()->name }}
                        </span>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Профиль
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Настройки
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Выход
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">@yield('title', 'Dashboard')</h1>
                <div class="page-actions">
                    @yield('actions')
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom scripts -->
    <script>
        // Toggle sidebar on small screens
        document.getElementById('sidebarToggleTop').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-toggled');
        });

        // Auto hide alerts after 5 seconds
        window.setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Modal stability fixes
        document.addEventListener('DOMContentLoaded', function() {
            // Handle delete booking buttons with custom class
            const deleteBookingButtons = document.querySelectorAll('.delete-booking-btn');

            deleteBookingButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const bookingId = this.getAttribute('data-booking-id');
                    const modalId = `deleteModal${bookingId}`;
                    const modal = document.getElementById(modalId);

                    if (modal) {
                        const modalInstance = new bootstrap.Modal(modal, {
                            backdrop: 'static',
                            keyboard: false
                        });
                        modalInstance.show();
                    }
                });
            });

            // Find all delete modals and stabilize them
            const deleteModals = document.querySelectorAll('[id^="deleteModal"]');

            deleteModals.forEach(modal => {
                // For other modals that still use data-bs-toggle
                const triggerButton = document.querySelector(`[data-bs-target="#${modal.id}"]`);
                if (triggerButton && !triggerButton.classList.contains('delete-booking-btn')) {
                    let isOpening = false;

                    triggerButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (isOpening) return;

                        isOpening = true;
                        const modalInstance = new bootstrap.Modal(modal, {
                            backdrop: 'static',
                            keyboard: false
                        });
                        modalInstance.show();

                        setTimeout(() => {
                            isOpening = false;
                        }, 500);
                    });
                }

                // Handle form submission
                const form = modal.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const submitButton = form.querySelector('button[type="submit"]');
                        if (submitButton) {
                            submitButton.disabled = true;
                            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Удаление...';
                        }
                    });
                }

                // Clean up on hide
                modal.addEventListener('hidden.bs.modal', function() {
                    const form = modal.querySelector('form');
                    if (form) {
                        const submitButton = form.querySelector('button[type="submit"]');
                        if (submitButton) {
                            submitButton.disabled = false;
                            const originalText = submitButton.textContent.includes('бронирование') ? 'Удалить бронирование' : 'Удалить';
                            submitButton.innerHTML = originalText;
                        }
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
