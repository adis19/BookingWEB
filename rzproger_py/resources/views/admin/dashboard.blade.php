@extends('layouts.admin')

@section('title', 'Панель управления')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card stat-card stat-card-primary mb-4">
            <div class="card-body">
                <div class="card-title">Всего бронирований</div>
                <div class="card-value">{{ $totalBookings }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-success mb-4">
            <div class="card-body">
                <div class="card-title">Подтвержденные бронирования</div>
                <div class="card-value">{{ $confirmedBookings }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-warning mb-4">
            <div class="card-body">
                <div class="card-title">Ожидающие бронирования</div>
                <div class="card-value">{{ $pendingBookings }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-info mb-4">
            <div class="card-body">
                <div class="card-title">Ежемесячный доход</div>
                <div class="card-value">{{ \App\Helpers\CurrencyHelper::formatKgs($monthlyRevenue) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card stat-card stat-card-primary mb-4">
            <div class="card-body">
                <div class="card-title">Всего номеров</div>
                <div class="card-value">{{ $totalRooms }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-success mb-4">
            <div class="card-body">
                <div class="card-title">Доступные номера</div>
                <div class="card-value">{{ $availableRooms }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-info mb-4">
            <div class="card-body">
                <div class="card-title">Всего пользователей</div>
                <div class="card-value">{{ $totalUsers }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Недавние бронирования</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover dashboard-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Номер</th>
                                <th>Заезд</th>
                                <th>Статус</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->room->roomType->name }}</td>
                                    <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                            <span class="badge bg-warning text-dark">Ожидает</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge bg-success">Подтверждено</span>
                                        @elseif($booking->status == 'cancelled')
                                            <span class="badge bg-danger">Отменено</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge bg-secondary">Завершено</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Просмотр</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Недавних бронирований не найдено</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">Посмотреть все бронирования</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ближайшие заезды</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover dashboard-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Номер</th>
                                <th>Заезд</th>
                                <th>Гости</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingCheckIns as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->room->roomType->name }}</td>
                                    <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                                    <td>{{ $booking->guests }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Просмотр</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Ближайших заездов не найдено</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">Посмотреть все бронирования</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Быстрые действия</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.room-types.create') }}" class="btn btn-outline-primary btn-lg d-block mb-3">
                            <i class="fas fa-plus-circle me-2"></i> Добавить тип номера
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.rooms.create') }}" class="btn btn-outline-primary btn-lg d-block mb-3">
                            <i class="fas fa-plus-circle me-2"></i> Добавить номер
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.extra-services.create') }}" class="btn btn-outline-primary btn-lg d-block mb-3">
                            <i class="fas fa-plus-circle me-2"></i> Добавить услугу
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.bookings.index') }}?status=pending" class="btn btn-outline-warning btn-lg d-block mb-3">
                            <i class="fas fa-clock me-2"></i> Просмотр ожидающих
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
