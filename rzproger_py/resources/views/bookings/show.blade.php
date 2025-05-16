@extends('layouts.app')

@section('title', 'Информация о бронировании')

@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">Мои бронирования</a></li>
                <li class="breadcrumb-item active" aria-current="page">Бронирование #{{ $booking->id }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Детали бронирования <span class="float-end">
                    @if($booking->status == 'pending')
                        <span class="badge bg-warning text-dark">Ожидает</span>
                    @elseif($booking->status == 'confirmed')
                        <span class="badge bg-success">Подтверждено</span>
                    @elseif($booking->status == 'cancelled')
                        <span class="badge bg-danger">Отменено</span>
                    @elseif($booking->status == 'completed')
                        <span class="badge bg-secondary">Завершено</span>
                    @endif
                </span></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Информация о бронировании</h5>
                        <p><strong>ID бронирования:</strong> #{{ $booking->id }}</p>
                        <p><strong>Дата заезда:</strong> {{ $booking->check_in_date->format('d.m.Y') }}</p>
                        <p><strong>Дата выезда:</strong> {{ $booking->check_out_date->format('d.m.Y') }}</p>
                        <p><strong>Продолжительность:</strong> {{ $booking->getDurationInDays() }} ночь(ей)</p>
                        <p><strong>Количество гостей:</strong> {{ $booking->guests }}</p>
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
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Информация о номере</h5>
                        <p><strong>Тип номера:</strong> {{ $booking->room->roomType->name }}</p>
                        <p><strong>Номер комнаты:</strong> {{ $booking->room->room_number }}</p>
                        <p><strong>Макс. вместимость:</strong> {{ $booking->room->roomType->max_occupancy }} гостей</p>
                        <p><strong>Цена за ночь:</strong> {{ \App\Helpers\CurrencyHelper::convertAndFormat($booking->room->roomType->price_per_night) }}</p>
                        
                        @if($booking->special_requests)
                            <h5 class="mt-4">Особые пожелания</h5>
                            <p>{{ $booking->special_requests }}</p>
                        @endif
                    </div>
                </div>
                
                @if($booking->extraServices->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Дополнительные услуги</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Услуга</th>
                                            <th>Цена</th>
                                            <th>Количество</th>
                                            <th>Всего</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($booking->extraServices as $service)
                                            <tr>
                                                <td>{{ $service->name }}</td>
                                                <td>{{ \App\Helpers\CurrencyHelper::convertAndFormat($service->pivot->price) }}</td>
                                                <td>{{ $service->pivot->quantity }}</td>
                                                <td>{{ \App\Helpers\CurrencyHelper::convertAndFormat($service->pivot->price * $service->pivot->quantity) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4">
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Назад к бронированиям</a>
                    
                    @if(($booking->status == 'pending' || $booking->status == 'confirmed') && $booking->check_in_date->isFuture())
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            Отменить бронирование
                        </button>
                        
                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
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
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Итог оплаты</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Стоимость номера:</span>
                    <span>{{ \App\Helpers\CurrencyHelper::convertAndFormat($booking->room->roomType->price_per_night) }} × {{ $booking->getDurationInDays() }} ночь(ей)</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Итого за номер:</span>
                    <span>{{ \App\Helpers\CurrencyHelper::convertAndFormat($booking->room->roomType->price_per_night * $booking->getDurationInDays()) }}</span>
                </div>
                
                @if($booking->extraServices->count() > 0)
                    <hr>
                    <h6>Дополнительные услуги:</h6>
                    @foreach($booking->extraServices as $service)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $service->name }} × {{ $service->pivot->quantity }}</span>
                            <span>{{ \App\Helpers\CurrencyHelper::convertAndFormat($service->pivot->price * $service->pivot->quantity) }}</span>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mb-2">
                        <span>Итого за услуги:</span>
                        <span>{{ \App\Helpers\CurrencyHelper::convertAndFormat($booking->getExtraServicesTotal()) }}</span>
                    </div>
                @endif
                
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="h5">Всего:</span>
                    <span class="h5">{{ \App\Helpers\CurrencyHelper::convertAndFormat($booking->total_price) }}</span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Нужна помощь?</h5>
                <p class="card-text">Если у вас есть вопросы или вам нужна помощь с бронированием, пожалуйста, не стесняйтесь связаться с нами.</p>
                <div class="d-grid">
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary">Связаться с нами</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
