@extends('layouts.app')

@section('title', 'Добро пожаловать')

@section('hero')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">Добро пожаловать в Люкс Отель</h1>
        <p class="lead">Ощутите непревзойденную роскошь в наших номерах премиум-класса</p>
        <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-lg mt-3">Просмотреть номера</a>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-5">
    <div class="col-md-12 text-center">
        <h2>Найдите идеальное место для проживания</h2>
        <p class="lead mb-4">Найдите доступные номера на основе ваших дат и предпочтений</p>
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
                        <button type="submit" class="btn btn-primary w-100">Поиск</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12 text-center mb-4">
        <h2>Наши типы номеров</h2>
        <p class="lead">Ознакомьтесь с нашим выбором комфортабельных номеров</p>
    </div>
    
    @foreach($roomTypes as $roomType)
    <div class="col-md-4">
        <div class="card room-card">
            @if($roomType->image)
                <img src="{{ $roomType->image }}" class="card-img-top" alt="{{ $roomType->name }}">
            @else
                <img src="https://via.placeholder.com/300x200?text=Room+Image" class="card-img-top" alt="Фото номера">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $roomType->name }}</h5>
                <p class="card-text">{{ Str::limit($roomType->description, 100) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="h5">{{ \App\Helpers\CurrencyHelper::convertAndFormat($roomType->price_per_night) }} <small>/сутки</small></span>
                    <a href="{{ route('rooms.show', $roomType) }}" class="btn btn-sm btn-outline-primary">Подробнее</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row mb-5">
    <div class="col-md-12 text-center mb-4">
        <h2>Почему выбирают нас</h2>
        <p class="lead">Наслаждайтесь роскошными удобствами и услугами во время вашего пребывания</p>
    </div>
    
    <div class="col-md-4">
        <div class="feature-box">
            <i class="fas fa-concierge-bell"></i>
            <h4>Первоклассный сервис</h4>
            <p>Обслуживание 24/7, помощь консьержа, обслуживание номеров и многое другое для вашего комфорта.</p>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="feature-box">
            <i class="fas fa-wifi"></i>
            <h4>Современные удобства</h4>
            <p>Высокоскоростной Wi-Fi, умные телевизоры, спа-услуги, фитнес-центр и бассейн.</p>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="feature-box">
            <i class="fas fa-map-marker-alt"></i>
            <h4>Отличное расположение</h4>
            <p>Расположен в центре города с легким доступом к туристическим достопримечательностям и деловым районам.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center mb-4">
        <h2>Дополнительные услуги</h2>
        <p class="lead">Улучшите свое пребывание с нашими дополнительными услугами</p>
    </div>
    
    @foreach($extraServices as $service)
    <div class="col-md-3">
        <div class="card mb-4 text-center">
            <div class="card-body">
                <i class="fas fa-plus-circle text-primary mb-3" style="font-size: 2rem;"></i>
                <h5 class="card-title">{{ $service->name }}</h5>
                <p class="card-text">{{ Str::limit($service->description, 80) }}</p>
                <p class="fw-bold">{{ \App\Helpers\CurrencyHelper::convertAndFormat($service->price) }}</p>
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
