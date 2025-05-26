@extends('layouts.admin')

@section('title', 'Бронирования')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>Фильтр бронирований
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bookings.index') }}">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label for="status" class="form-label">Статус</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Все статусы</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершено</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label for="from_date" class="form-label">Дата с</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                </div>

                <div class="col-lg-3 col-md-6">
                    <label for="to_date" class="form-label">Дата по</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                </div>

                <div class="col-lg-3 col-md-6 d-flex align-items-end">
                    <div class="btn-group w-100">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Применить
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Сбросить
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Список бронирований
        </h5>
    </div>

    <!-- Desktop Table -->
    <div class="d-none d-lg-block">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th style="width: 140px;">Гость</th>
                            <th style="width: 160px;">Номер</th>
                            <th style="width: 140px;">Даты</th>
                            <th style="width: 60px;">Гостей</th>
                            <th style="width: 100px;">Итого</th>
                            <th style="width: 100px;">Статус</th>
                            <th style="width: 120px;">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td><strong>#{{ $booking->id }}</strong></td>
                                <td>{{ Str::limit($booking->user->name, 20) }}</td>
                                <td>
                                    {{ Str::limit($booking->room->roomType->name, 15) }}<br>
                                    <small class="text-muted">#{{ $booking->room->room_number }}</small>
                                </td>
                                <td>
                                    {{ $booking->check_in_date->format('d.m.Y') }}<br>
                                    <small class="text-muted">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }}н.</small>
                                </td>
                                <td>{{ $booking->guests }}</td>
                                <td>{{ \App\Helpers\CurrencyHelper::formatKgs($booking->total_price) }}</td>
                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark">Ожидает</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge bg-success">Подтвержд.</span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="badge bg-danger">Отменено</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge bg-secondary">Заверш.</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" title="Просмотр">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-sm btn-outline-secondary" title="Редактировать">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-booking-btn" data-booking-id="{{ $booking->id }}" data-booking-user="{{ $booking->user->name }}" title="Удалить">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-calendar-times text-muted" style="font-size: 2rem;"></i>
                                    <p class="text-muted mt-2 mb-0">Бронирований не найдено</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="d-lg-none">
        <div class="card-body p-0">
            @forelse($bookings as $booking)
                <div class="booking-card">
                    <div class="booking-card-header">
                        <div class="booking-id">#{{ $booking->id }}</div>
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
                    <div class="booking-card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="booking-info-item">
                                    <i class="fas fa-user text-primary"></i>
                                    <span>{{ $booking->user->name }}</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-bed text-primary"></i>
                                    <span>{{ $booking->room->roomType->name }}</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-door-open text-primary"></i>
                                    <span>Номер #{{ $booking->room->room_number }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="booking-info-item">
                                    <i class="fas fa-calendar text-success"></i>
                                    <span>{{ $booking->check_in_date->format('d.m.Y') }}</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-users text-info"></i>
                                    <span>{{ $booking->guests }} гостей</span>
                                </div>
                                <div class="booking-info-item">
                                    <i class="fas fa-money-bill-wave text-warning"></i>
                                    <span>{{ \App\Helpers\CurrencyHelper::formatKgs($booking->total_price) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="booking-card-actions">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Просмотр
                        </a>
                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-edit me-1"></i> Изменить
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-booking-btn" data-booking-id="{{ $booking->id }}" data-booking-user="{{ $booking->user->name }}">
                            <i class="fas fa-trash me-1"></i> Удалить
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <p>Бронирований не найдено</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="card-footer">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

<!-- Delete Modals for each booking -->
@foreach($bookings as $booking)
    <div class="modal fade" id="deleteModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                        Удалить бронирование
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить бронирование <strong>#{{ $booking->id }}</strong> для <strong>{{ $booking->user->name }}</strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Это действие необратимо!
                    </div>
                </div>
                <form id="deleteForm{{ $booking->id }}" action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Отмена
                    </button>
                    <button type="submit" form="deleteForm{{ $booking->id }}" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Удалить бронирование
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
