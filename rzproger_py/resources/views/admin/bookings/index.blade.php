@extends('layouts.admin')

@section('title', 'Бронирования')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Фильтр бронирований</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.bookings.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Статус</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Все статусы</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершено</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="from_date" class="form-label">Дата с</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3">
                    <label for="to_date" class="form-label">Дата по</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Применить</button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Сбросить</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Гость</th>
                        <th>Номер</th>
                        <th>Даты</th>
                        <th>Гостей</th>
                        <th>Итого</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>
                                {{ $booking->room->roomType->name }}<br>
                                <small class="text-muted">Номер #{{ $booking->room->room_number }}</small>
                            </td>
                            <td>
                                {{ $booking->check_in_date->format('M d, Y') }} - {{ $booking->check_out_date->format('M d, Y') }}<br>
                                <small class="text-muted">{{ $booking->check_in_date->diffInDays($booking->check_out_date) }} ночь(ей)</small>
                            </td>
                            <td>{{ $booking->guests }}</td>
                            <td>{{ \App\Helpers\CurrencyHelper::convertAndFormat($booking->total_price) }}</td>
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
                                <div class="btn-group">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $booking->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Удалить бронирование</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Вы уверены, что хотите удалить бронирование <strong>#{{ $booking->id }}</strong> для <strong>{{ $booking->user->name }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Удалить бронирование</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Бронирований не найдено</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
