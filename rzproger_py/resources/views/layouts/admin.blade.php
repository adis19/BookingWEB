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

        .stat-card-warning {
            border-left-color: var(--warning);
        }

        .stat-card .card-body {
            padding: 1.25rem;
        }

        .stat-card .card-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            margin-bottom: 0.25rem;
        }

        .stat-card-primary .card-title {
            color: var(--primary);
        }

        .stat-card-success .card-title {
            color: var(--success);
        }

        .stat-card-info .card-title {
            color: var(--info);
        }

        .stat-card-warning .card-title {
            color: var(--warning);
        }

        .stat-card .card-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0;
        }

        .stat-card .card-icon {
            font-size: 2rem;
            opacity: 0.1;
            position: absolute;
            top: 1rem;
            right: 1rem;
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
    </script>

    @yield('scripts')
</body>
</html>
