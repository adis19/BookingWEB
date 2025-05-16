@extends('layouts.app')

@section('title', 'Мои бронирования')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Мои бронирования</h1>
        
        @if($bookings->count() > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Предстоящие и недавние бронирования</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID бронирования</th>
                                            <th>Номер</th>
                                            <th>Даты</th>
                                            <th>Гости</th>
                                            <th>Итого</th>
                                            <th>Статус</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            <tr>
                                                <td>#{{ $booking->id }}</td>
                                                <td>
                                                    {{ $booking->room->roomType->name }}<br>
                                                    <small class="text-muted">Номер #{{ $booking->room->room_number }}</small>
                                                </td>
                                                <td>
                                                    {{ $booking->check_in_date->format('d.m.Y') }} - {{ $booking->check_out_date->format('d.m.Y') }}<br>
                                                    <small class="text-muted">{{ $booking->getDurationInDays() }} ночь(ей)</small>
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
                                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Просмотр</a>
                                                    
                                                    @if($booking->status == 'pending' || $booking->status == 'confirmed')
                                                        @if($booking->check_in_date->isFuture())
                                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                                                Отменить
                                                            </button>
                                                            
                                                            <!-- Cancel Modal -->
                                                            <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Отменить бронирование #{{ $booking->id }}</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Вы уверены, что хотите отменить это бронирование?</p>
                                                                            <p><strong>Номер:</strong> {{ $booking->room->roomType->name }}</p>
                                                                            <p><strong>Даты:</strong> {{ $booking->check_in_date->format('d.m.Y') }} - {{ $booking->check_out_date->format('d.m.Y') }}</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                                                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST">
                                                                                @csrf
                                                                                <button type="submit" class="btn btn-danger">Отменить бронирование</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle me-2"></i> Информация о бронировании</h5>
                <ul class="mb-0">
                    <li><strong>Ожидает</strong> - Ваше бронирование ожидает подтверждения нашими сотрудниками</li>
                    <li><strong>Подтверждено</strong> - Ваше бронирование подтверждено и готово к заселению</li>
                    <li><strong>Отменено</strong> - Бронирование было отменено</li>
                    <li><strong>Завершено</strong> - Ваше пребывание завершено</li>
                </ul>
            </div>
        @else
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle me-2"></i> Бронирования не найдены</h5>
                <p class="mb-0">У вас еще нет бронирований. <a href="{{ route('rooms.index') }}" class="alert-link">Просмотрите наши номера</a>, чтобы сделать первое бронирование!</p>
            </div>
        @endif
    </div>
</div>
@endsection
