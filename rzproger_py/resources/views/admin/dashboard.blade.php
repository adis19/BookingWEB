@extends('layouts.admin')

@section('title', 'Панель управления')

@section('content')
<!-- Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card stat-card-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Всего бронирований</div>
                        <div class="card-value">{{ $totalBookings }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card stat-card-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Подтверждено</div>
                        <div class="card-value">{{ $confirmedBookings }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card stat-card-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Ожидает</div>
                        <div class="card-value">{{ $pendingBookings }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card stat-card-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Доход/месяц</div>
                        <div class="card-value small-text">{{ \App\Helpers\CurrencyHelper::formatKgs($monthlyRevenue) }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card stat-card stat-card-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Всего номеров</div>
                        <div class="card-value">{{ $totalRooms }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-bed"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card stat-card stat-card-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Доступно</div>
                        <div class="card-value">{{ $availableRooms }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card stat-card stat-card-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="card-title">Пользователи</div>
                        <div class="card-value">{{ $totalUsers }}</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings & Upcoming Check-ins -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Недавние бронирования
                </h5>
            </div>
            <div class="card-body p-0">
                @forelse($recentBookings as $booking)
                    <div class="booking-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="booking-info">
                                <div class="booking-id">#{{ $booking->id }}</div>
                                <div class="booking-user">{{ Str::limit($booking->user->name, 20) }}</div>
                                <div class="booking-room text-muted">{{ Str::limit($booking->room->roomType->name, 25) }}</div>
                            </div>
                            <div class="text-end">
                                <div class="booking-date">{{ $booking->check_in_date->format('d.m.Y') }}</div>
                                <div class="booking-status">
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark">Ожидает</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge bg-success">Подтверждено</span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="badge bg-danger">Отменено</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge bg-secondary">Завершено</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Просмотр
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>Недавних бронирований не найдено</p>
                    </div>
                @endforelse
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-right me-1"></i> Все бронирования
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-day me-2"></i>Ближайшие заезды
                </h5>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingCheckIns as $booking)
                    <div class="booking-item upcoming">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="booking-info">
                                <div class="booking-id">#{{ $booking->id }}</div>
                                <div class="booking-user">{{ Str::limit($booking->user->name, 20) }}</div>
                                <div class="booking-room text-muted">{{ Str::limit($booking->room->roomType->name, 25) }}</div>
                            </div>
                            <div class="text-end">
                                <div class="booking-date highlight">{{ $booking->check_in_date->format('d.m.Y') }}</div>
                                <div class="booking-guests">
                                    <i class="fas fa-users"></i> {{ $booking->guests }} чел.
                                </div>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-eye"></i> Просмотр
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-plus"></i>
                        <p>Ближайших заездов не найдено</p>
                    </div>
                @endforelse
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-arrow-right me-1"></i> Все бронирования
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Быстрые действия
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.room-types.create') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-primary">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="quick-action-text">
                                <span>Добавить тип номера</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.rooms.create') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-success">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="quick-action-text">
                                <span>Добавить номер</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.extra-services.create') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-info">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <div class="quick-action-text">
                                <span>Добавить услугу</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.bookings.index') }}?status=pending" class="quick-action-btn">
                            <div class="quick-action-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="quick-action-text">
                                <span>Ожидающие брони</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Вторая строка быстрых действий -->
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.reports.index') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-danger">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="quick-action-text">
                                <span>Отчеты и аналитика</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.analytics.dashboard') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-dark">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="quick-action-text">
                                <span>Дашборд аналитики</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.reports.create') }}" class="quick-action-btn">
                            <div class="quick-action-icon bg-secondary">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="quick-action-text">
                                <span>Создать отчет</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="{{ route('admin.bookings.index') }}?status=confirmed" class="quick-action-btn">
                            <div class="quick-action-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="quick-action-text">
                                <span>Активные брони</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
