@extends('layouts.app')

@section('title', 'Наши номера')

@section('hero')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">Наши роскошные номера</h1>
        <p class="lead">Выберите из различных изысканно оформленных номеров для идеального пребывания</p>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-5">
    <div class="col-md-12 text-center">
        <h2>Найти доступные номера</h2>
        <p class="lead mb-4">Поиск доступных номеров на основе выбранных дат и предпочтений</p>
    </div>
    <div class="col-md-8 mx-auto">
        <div class="booking-form">
            <form action="{{ route('rooms.search') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="check_in" class="form-label">Дата заезда</label>
                        <input type="date" class="form-control @error('check_in') is-invalid @enderror" id="check_in" name="check_in" min="{{ date('Y-m-d') }}" required>
                        @error('check_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="check_out" class="form-label">Дата выезда</label>
                        <input type="date" class="form-control @error('check_out') is-invalid @enderror" id="check_out" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('check_out')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="guests" class="form-label">Гости</label>
                        <select class="form-select @error('guests') is-invalid @enderror" id="guests" name="guests" required>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        @error('guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary search-btn w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center mb-4">
        <h2>Все типы номеров</h2>
        <p class="lead">Ознакомьтесь с нашим выбором комфортабельных номеров</p>
    </div>

    @foreach($roomTypes as $roomType)
    <div class="col-md-4 mb-4">
        <div class="card h-100 room-card">
            <img src="{{ \App\Helpers\ImageHelper::getImageUrl($roomType->image) }}" class="card-img-top" alt="{{ $roomType->name }}">
            <div class="card-body">
                <h5 class="card-title">{{ $roomType->name }}</h5>
                <p class="card-text">{{ Str::limit($roomType->description, 100) }}</p>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><i class="fas fa-user-friends me-2"></i> Макс. {{ $roomType->max_occupancy }} гостей</li>
                    <li class="list-group-item"><i class="fas fa-dollar-sign me-2"></i> {{ \App\Helpers\CurrencyHelper::convertAndFormat($roomType->price_per_night) }} за ночь</li>
                    @if(is_array($roomType->amenities) && count($roomType->amenities) > 0)
                        <li class="list-group-item">
                            <i class="fas fa-concierge-bell me-2"></i>
                            {{ implode(', ', array_slice($roomType->amenities, 0, 3)) }}
                            @if(count($roomType->amenities) > 3)
                                и другое...
                            @endif
                        </li>
                    @endif
                </ul>
                <div class="text-center">
                    <a href="{{ route('rooms.show', $roomType) }}" class="btn btn-outline-primary">Подробнее</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
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
