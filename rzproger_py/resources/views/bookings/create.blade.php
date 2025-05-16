@extends('layouts.app')

@section('title', 'Бронирование номера')

@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Номера</a></li>
                <li class="breadcrumb-item active" aria-current="page">Бронирование номера</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Завершите ваше бронирование</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i> Пожалуйста, исправьте следующие ошибки:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form id="booking-form" action="{{ route('bookings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $availableRoom->id }}">
                    <input type="hidden" name="check_in_date" value="{{ $checkIn->format('Y-m-d') }}">
                    <input type="hidden" name="check_out_date" value="{{ $checkOut->format('Y-m-d') }}">
                    <input type="hidden" name="guests" value="{{ $guests }}">
                    
                    <div class="mb-4">
                        <h5>Детали бронирования</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p><strong>Дата заезда:</strong> {{ $checkIn->format('d.m.Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Дата выезда:</strong> {{ $checkOut->format('d.m.Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Гости:</strong> {{ $guests }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Продолжительность:</strong> {{ $duration }} ночь(ей)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Дополнительные услуги</h5>
                        <p class="text-muted mb-3">Выберите дополнительные услуги, которые вы хотели бы добавить к вашему пребыванию:</p>
                        
                        <div class="row">
                            @foreach($extraServices as $service)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="card-title mb-0">{{ $service->name }}</h6>
                                                <span class="badge bg-primary">{{ \App\Helpers\CurrencyHelper::convertAndFormat($service->price) }}</span>
                                            </div>
                                            <p class="card-text text-muted small">{{ $service->description }}</p>
                                            <div class="d-flex justify-content-end align-items-center">
                                                <label for="service_{{ $service->id }}" class="me-2">Количество:</label>
                                                <select class="form-select form-select-sm extra-service" 
                                                        style="width: 80px;" 
                                                        id="service_{{ $service->id }}" 
                                                        name="extra_services[{{ $service->id }}]"
                                                        data-price="{{ \App\Helpers\CurrencyHelper::usdToKgs($service->price) }}">
                                                    <option value="0">0</option>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Особые пожелания</h5>
                        <textarea class="form-control" name="special_requests" rows="3" placeholder="Если у вас есть какие-либо особые пожелания или требования, сообщите нам здесь."></textarea>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Подтвердить бронирование</button>
                        <a href="{{ url('/debug/request') }}" class="btn btn-outline-secondary btn-sm">Отладка формы</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4 sticky-top" style="top: 20px;">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Итог бронирования</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>{{ $roomType->name }}</h6>
                    <p class="text-muted small mb-0">Номер #{{ $availableRoom->room_number }}</p>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Стоимость номера:</span>
                        <span>{{ \App\Helpers\CurrencyHelper::convertAndFormat($roomType->price_per_night) }} × {{ $duration }} ночь(ей)</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Итого за номер:</span>
                        <span class="fw-bold">{{ \App\Helpers\CurrencyHelper::convertAndFormat($roomPrice) }}</span>
                    </div>
                </div>
                
                <div id="extra-services-summary" class="mb-3 d-none">
                    <hr>
                    <h6>Дополнительные услуги:</h6>
                    <div id="selected-services"></div>
                    <div class="d-flex justify-content-between">
                        <span>Итого за услуги:</span>
                        <span class="fw-bold" id="services-total">0 сом</span>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-between">
                    <span class="h5">Всего:</span>
                    <span class="h5" id="total-price">{{ \App\Helpers\CurrencyHelper::convertAndFormat($roomPrice) }}</span>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const extraServices = document.querySelectorAll('.extra-service');
        const totalPriceEl = document.getElementById('total-price');
        const servicesTotalEl = document.getElementById('services-total');
        const extraServicesSummary = document.getElementById('extra-services-summary');
        const selectedServicesEl = document.getElementById('selected-services');
        const roomPrice = {{ \App\Helpers\CurrencyHelper::usdToKgs($roomPrice) }};
        
        // Update booking summary when extra services are selected
        extraServices.forEach(service => {
            service.addEventListener('change', updateBookingSummary);
        });
        
        function updateBookingSummary() {
            let servicesTotal = 0;
            let hasServices = false;
            let servicesHtml = '';
            
            extraServices.forEach(service => {
                const quantity = parseInt(service.value);
                if (quantity > 0) {
                    hasServices = true;
                    const price = parseFloat(service.getAttribute('data-price'));
                    const serviceTotal = price * quantity;
                    servicesTotal += serviceTotal;
                    
                    const serviceName = service.closest('.card').querySelector('.card-title').textContent;
                    servicesHtml += `
                        <div class="d-flex justify-content-between mb-2">
                            <span>${serviceName} × ${quantity}</span>
                            <span>${serviceTotal.toLocaleString('ru-RU')} сом</span>
                        </div>
                    `;
                }
            });
            
            if (hasServices) {
                extraServicesSummary.classList.remove('d-none');
                selectedServicesEl.innerHTML = servicesHtml;
                servicesTotalEl.textContent = servicesTotal.toLocaleString('ru-RU') + ' сом';
            } else {
                extraServicesSummary.classList.add('d-none');
            }
            
            const totalPrice = roomPrice + servicesTotal;
            totalPriceEl.textContent = totalPrice.toLocaleString('ru-RU') + ' сом';
        }
    });
</script>
@endsection
