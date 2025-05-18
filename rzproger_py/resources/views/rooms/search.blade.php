@extends('layouts.app')

@section('title', 'Результаты поиска')

@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Номера</a></li>
                <li class="breadcrumb-item active" aria-current="page">Результаты поиска</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-0">Критерии поиска</h5>
                        <p class="text-muted mb-0">
                            <strong>Заезд:</strong> {{ $checkIn->format('d.m.Y') }} | 
                            <strong>Выезд:</strong> {{ $checkOut->format('d.m.Y') }} | 
                            <strong>Гости:</strong> {{ $guests }} | 
                            <strong>Продолжительность:</strong> {{ $checkIn->diffInDays($checkOut) }} ночь(ей)
                        </p>
                    </div>
                    <button class="btn btn-outline-secondary mt-2 mt-md-0" type="button" data-bs-toggle="collapse" data-bs-target="#searchForm">
                        Изменить параметры
                    </button>
                </div>
                
                <div class="collapse mt-3" id="searchForm">
                    <form action="{{ route('rooms.search') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="check_in" class="form-label">Заезд</label>
                                <input type="date" class="form-control" id="check_in" name="check_in" min="{{ date('Y-m-d') }}" value="{{ $checkIn->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="check_out" class="form-label">Выезд</label>
                                <input type="date" class="form-control" id="check_out" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ $checkOut->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label for="guests" class="form-label">Гости</label>
                                <select class="form-select" id="guests" name="guests" required>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $guests == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Поиск</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>Доступные номера</h2>
        @if($availableRoomTypes->count() > 0)
            <p>Найдено {{ $availableRoomTypes->count() }} типов номеров, соответствующих вашим критериям.</p>
            
            <div class="row">
                @foreach($availableRoomTypes as $roomType)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 room-card">
                            <img src="{{ \App\Helpers\ImageHelper::getImageUrl($roomType->image) }}" class="card-img-top" alt="{{ $roomType->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $roomType->name }}</h5>
                                <p class="card-text">{{ Str::limit($roomType->description, 100) }}</p>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item"><i class="fas fa-user-friends me-2"></i> Макс. {{ $roomType->max_occupancy }} гостей</li>
                                    <li class="list-group-item"><i class="fas fa-dollar-sign me-2"></i> {{ \App\Helpers\CurrencyHelper::convertAndFormat($roomType->price_per_night) }} за ночь</li>
                                    <li class="list-group-item"><i class="fas fa-calculator me-2"></i> Всего: {{ \App\Helpers\CurrencyHelper::convertAndFormat($roomType->price_per_night * $checkIn->diffInDays($checkOut)) }}</li>
                                </ul>
                                
                                <form action="{{ route('bookings.create') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                    <input type="hidden" name="check_in" value="{{ $checkIn->format('Y-m-d') }}">
                                    <input type="hidden" name="check_out" value="{{ $checkOut->format('Y-m-d') }}">
                                    <input type="hidden" name="guests" value="{{ $guests }}">
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Забронировать</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> Извините, мы не смогли найти доступных номеров, соответствующих вашим критериям. Пожалуйста, попробуйте выбрать другие даты или количество гостей.
            </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Доступные дополнительные услуги</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($extraServices as $service)
                        <div class="col-md-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $service->name }}</h5>
                                    <p class="card-text">{{ $service->description }}</p>
                                    <p class="fw-bold mb-0">{{ \App\Helpers\CurrencyHelper::convertAndFormat($service->price) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="text-muted mt-2">Примечание: Дополнительные услуги можно добавить в процессе бронирования.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum check-out date to be one day after check-in
        document.getElementById('check_in').addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            checkInDate.setDate(checkInDate.getDate() + 1);
            
            const checkOutField = document.getElementById('check_out');
            const checkOutMin = checkInDate.toISOString().split('T')[0];
            
            checkOutField.min = checkOutMin;
            
            // If current check-out date is earlier than new min, update it
            if (checkOutField.value && new Date(checkOutField.value) < checkInDate) {
                checkOutField.value = checkOutMin;
            }
        });
    });
</script>
@endsection
