@extends('layouts.admin')

@section('title', 'Детали бронирования')

@section('actions')
<a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Назад к бронированиям
</a>
<a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-primary">
    <i class="fas fa-edit"></i> Редактировать бронирование
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Бронирование #{{ $booking->id }}</h5>
                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm me-2" style="width: 150px;">
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Завершено</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Обновить статус</button>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Информация о госте</h6>
                        <p><strong>Имя:</strong> {{ $booking->user->name }}</p>
                        <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                        <p><strong>Телефон:</strong> {{ $booking->user->phone ?? 'Не указан' }}</p>
                        <p><strong>Адрес:</strong> {{ $booking->user->address ?? 'Не указан' }}</p>
                    </div>

                    <div class="col-md-6">
                        <h6>Детали бронирования</h6>
                        <p><strong>Статус:</strong>
                            @if($booking->status == 'pending')
                                <span class="badge bg-warning text-dark">Ожидает</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="badge bg-success">Подтверждено</span>
                            @elseif($booking->status == 'cancelled')
                                <span class="badge bg-danger">Отменено</span>
                            @elseif($booking->status == 'completed')
                                <span class="badge bg-secondary">Завершено</span>
                            @endif
                        </p>
                        <p><strong>Дата бронирования:</strong> {{ $booking->created_at->format('d.m.Y H:i') }}</p>
                        <p><strong>Дата заезда:</strong> {{ $booking->check_in_date->format('d.m.Y') }}</p>
                        <p><strong>Дата выезда:</strong> {{ $booking->check_out_date->format('d.m.Y') }}</p>
                        <p><strong>Продолжительность:</strong> {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} ночь(ей)</p>
                        <p><strong>Гостей:</strong> {{ $booking->guests }}</p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h6>Информация о номере</h6>
                        <p><strong>Тип номера:</strong> {{ $booking->room->roomType->name }}</p>
                        <p><strong>Номер комнаты:</strong> {{ $booking->room->room_number }}</p>
                        <p><strong>Цена за ночь:</strong> {{ \App\Helpers\CurrencyHelper::formatKgs($booking->room->roomType->price_per_night) }}</p>
                    </div>
                </div>

                @if($booking->special_requests)
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Особые пожелания</h6>
                            <p>{{ $booking->special_requests }}</p>
                        </div>
                    </div>
                @endif

                @if($booking->extraServices->count() > 0)
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Дополнительные услуги</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Услуга</th>
                                            <th>Цена</th>
                                            <th>Количество</th>
                                            <th class="text-end">Итого</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($booking->extraServices as $service)
                                            <tr>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ \App\Helpers\CurrencyHelper::formatKgs($service->pivot->price) }}</td>
                                                <td>{{ $service->pivot->quantity }}</td>
                                                <td class="text-end">{{ \App\Helpers\CurrencyHelper::formatKgs($service->pivot->price * $service->pivot->quantity) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Итог оплаты</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Стоимость номера:</span>
                    <span>{{ \App\Helpers\CurrencyHelper::formatKgs($booking->room->roomType->price_per_night) }} × {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} ночь(ей)</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Итого за номер:</span>
                    <span>{{ \App\Helpers\CurrencyHelper::formatKgs($booking->room->roomType->price_per_night * $booking->check_in_date->diffInDays($booking->check_out_date)) }}</span>
                </div>

                @if($booking->extraServices->count() > 0)
                    <hr>
                    <h6>Дополнительные услуги:</h6>
                    @foreach($booking->extraServices as $service)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $service->name }} × {{ $service->pivot->quantity }}</span>
                            <span>{{ \App\Helpers\CurrencyHelper::formatKgs($service->pivot->price * $service->pivot->quantity) }}</span>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mb-2">
                        <span>Итого за услуги:</span>
                        <span>{{ \App\Helpers\CurrencyHelper::formatKgs($booking->getExtraServicesTotal()) }}</span>
                    </div>
                @endif

                <hr>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Всего:</span>
                    <span class="fw-bold">{{ \App\Helpers\CurrencyHelper::formatKgs($booking->total_price) }}</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Действия</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success" onclick="window.print()">
                        <i class="fas fa-print me-2"></i> Печать деталей бронирования
                    </button>

                    <!-- Delete Button with Modal -->
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i> Удалить бронирование
                    </button>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Удалить бронирование</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Вы уверены, что хотите удалить это бронирование?</p>
                                    <p><strong>Номер:</strong> {{ $booking->room->roomType->name }} #{{ $booking->room->room_number }}</p>
                                    <p><strong>Гость:</strong> {{ $booking->user->name }}</p>
                                    <p><strong>Даты:</strong> {{ $booking->check_in_date->format('d.m.Y') }} - {{ $booking->check_out_date->format('d.m.Y') }}</p>
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
            </div>
        </div>
    </div>
</div>
@endsection
