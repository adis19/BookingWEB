@extends('layouts.app')

@section('title', 'Добро пожаловать')

@section('hero')
<div class="hero-section">
    <div class="container text-center">
        <h1 data-aos="fade-up" data-aos-delay="100">Добро пожаловать в мир роскоши</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="200">Изысканный отдых и незабываемые впечатления ждут вас в Люкс Отеле</p>
        <div data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-lg mt-3 me-2">
                <i class="fas fa-door-open me-2"></i>Выбрать номер
            </a>
            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg mt-3">
                <i class="fas fa-info-circle me-2"></i>Узнать больше
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-5">
    <div class="col-md-12 text-center">
        <h2 class="section-title" data-aos="fade-up">Найдите идеальное место для отдыха</h2>
        <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Выберите даты проживания и количество гостей, чтобы узнать о доступных номерах</p>
    </div>
    <div class="col-lg-10 col-xl-8 mx-auto">
        <div class="booking-form" data-aos="fade-up" data-aos-delay="200">
            <form action="{{ route('rooms.search') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="check_in" class="form-label"><i class="fas fa-calendar-check me-2"></i>Дата заезда</label>
                        <input type="date" class="form-control form-control-lg @error('check_in') is-invalid @enderror" id="check_in" name="check_in" min="{{ date('Y-m-d') }}" required>
                        @error('check_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="check_out" class="form-label"><i class="fas fa-calendar-minus me-2"></i>Дата выезда</label>
                        <input type="date" class="form-control form-control-lg @error('check_out') is-invalid @enderror" id="check_out" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('check_out')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="guests" class="form-label"><i class="fas fa-users me-2"></i>Гости</label>
                        <select class="form-select form-select-lg @error('guests') is-invalid @enderror" id="guests" name="guests" required>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        @error('guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-lg search-btn w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mb-5 mt-5 pt-4">
    <div class="col-md-12 text-center">
        <h2 class="section-title" data-aos="fade-up">Наши роскошные номера</h2>
        <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Изысканный комфорт и элегантность в каждой детали</p>
    </div>

    @foreach($roomTypes as $index => $roomType)
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 100) }}">
        <div class="room-card {{ $index % 2 == 0 ? 'animate-float' : '' }}">
            <div class="position-relative overflow-hidden">
                @if($roomType->image)
                    <img src="{{ $roomType->image }}" class="card-img-top" alt="{{ $roomType->name }}">
                @else
                    <img src="https://via.placeholder.com/300x200?text=Room+Image" class="card-img-top" alt="Фото номера">
                @endif
                <div class="position-absolute top-0 end-0 p-2">
                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{ \App\Helpers\CurrencyHelper::convertAndFormat($roomType->price_per_night) }}/ночь</span>
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $roomType->name }}</h5>
                <p class="card-text">{{ Str::limit($roomType->description, 100) }}</p>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i> До {{ $roomType->max_occupancy }} гостей
                        </small>
                    </div>
                    <a href="{{ route('rooms.show', $roomType) }}" class="btn btn-outline-primary">
                        <i class="fas fa-info-circle me-1"></i> Подробнее
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="col-md-12 text-center mt-4" data-aos="fade-up">
        <a href="{{ route('rooms.index') }}" class="btn btn-primary">
            <i class="fas fa-th-large me-2"></i>Смотреть все номера
        </a>
    </div>
</div>

<div class="row mb-5 mt-5 pt-4">
    <div class="col-md-12 text-center">
        <h2 class="section-title" data-aos="fade-up">Почему выбирают нас</h2>
        <p class="lead mb-5" data-aos="fade-up" data-aos-delay="100">Ощутите непревзойденный уровень сервиса и комфорта</p>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-box">
            <div class="mb-3">
                <i class="fas fa-concierge-bell"></i>
            </div>
            <h4>Первоклассный сервис</h4>
            <p class="mb-0">Наши профессиональные сотрудники обеспечат вам безупречный сервис 24/7, помощь консьержа, обслуживание номеров и многое другое для вашего комфорта.</p>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-box">
            <div class="mb-3">
                <i class="fas fa-spa"></i>
            </div>
            <h4>Роскошный спа-центр</h4>
            <p class="mb-0">Погрузитесь в атмосферу релаксации в нашем спа-центре с широким выбором массажей, уходовых процедур и парных.</p>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-box">
            <div class="mb-3">
                <i class="fas fa-utensils"></i>
            </div>
            <h4>Изысканная кухня</h4>
            <p class="mb-0">Наш ресторан предлагает блюда высокой кухни от шеф-повара, приготовленные из свежих местных продуктов.</p>
        </div>
    </div>
</div>

<div class="row py-5">
    <div class="col-md-6" data-aos="fade-right">
        <div class="bg-light p-4 rounded-3 h-100 d-flex flex-column justify-content-center">
            <span class="badge bg-primary mb-2">Новое предложение</span>
            <h2 class="mb-3">Скидка 20% на weekend-пакеты</h2>
            <p class="mb-4">Забронируйте проживание на выходные и получите скидку 20% на номер и доступ к спа-центру. Предложение ограничено!</p>
            <a href="{{ route('rooms.index') }}" class="btn btn-primary align-self-start">
                <i class="fas fa-tag me-2"></i>Получить скидку
            </a>
        </div>
    </div>
    <div class="col-md-6" data-aos="fade-left">
        <div class="row h-100">
            <div class="col-6 mb-4">
                <div class="bg-primary text-white p-4 rounded-3 h-100 d-flex flex-column justify-content-center">
                    <i class="fas fa-award mb-3" style="font-size: 2rem;"></i>
                    <h5>Лучший отель 2025</h5>
                    <p class="mb-0">По версии TravelAdvisor</p>
                </div>
            </div>
            <div class="col-6 mb-4">
                <div class="bg-secondary text-white p-4 rounded-3 h-100 d-flex flex-column justify-content-center">
                    <i class="fas fa-user-tie mb-3" style="font-size: 2rem;"></i>
                    <h5>Персональный консьерж</h5>
                    <p class="mb-0">Для всех гостей</p>
                </div>
            </div>
            <div class="col-6">
                <div class="bg-dark text-white p-4 rounded-3 h-100 d-flex flex-column justify-content-center">
                    <i class="fas fa-shuttle-van mb-3" style="font-size: 2rem;"></i>
                    <h5>Бесплатный трансфер</h5>
                    <p class="mb-0">Из аэропорта и обратно</p>
                </div>
            </div>
            <div class="col-6">
                <div class="bg-info text-white p-4 rounded-3 h-100 d-flex flex-column justify-content-center">
                    <i class="fas fa-wifi mb-3" style="font-size: 2rem;"></i>
                    <h5>Бесплатный Wi-Fi</h5>
                    <p class="mb-0">На всей территории</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5 mt-5 pt-4">
    <div class="col-md-12 text-center">
        <h2 class="section-title" data-aos="fade-up">Дополнительные услуги</h2>
        <p class="lead mb-5" data-aos="fade-up" data-aos-delay="100">Сделайте свое пребывание еще более комфортным</p>
    </div>

    @foreach($extraServices as $index => $service)
    <div class="col-md-3 mb-4" data-aos="zoom-in" data-aos-delay="{{ 100 + ($index * 50) }}">
        <div class="service-card text-center p-4">
            <div class="service-icon">
                @php
                    $icons = ['fas fa-utensils', 'fas fa-car', 'fas fa-spa', 'fas fa-glass-cheers', 'fas fa-concierge-bell', 'fas fa-bath', 'fas fa-coffee', 'fas fa-baby'];
                    $iconIndex = $index % count($icons);
                @endphp
                <i class="{{ $icons[$iconIndex] }}"></i>
            </div>
            <h5 class="my-3">{{ $service->name }}</h5>
            <p class="mb-3 text-muted">{{ Str::limit($service->description, 60) }}</p>
            <div class="price-tag p-2 bg-light rounded-pill d-inline-block px-3">
                <strong>{{ \App\Helpers\CurrencyHelper::convertAndFormat($service->price) }}</strong>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row mb-5 bg-light py-5 rounded-3" data-aos="fade-up">
    <div class="col-md-12 text-center mb-4">
        <h2 class="section-title">Отзывы наших гостей</h2>
        <p class="lead">Узнайте, что говорят о нас клиенты</p>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white p-4 rounded-3 h-100 position-relative">
            <i class="fas fa-quote-left fa-2x text-primary opacity-25 position-absolute top-0 start-0 m-3"></i>
            <div class="text-center mb-3">
                <div class="mb-2">
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                </div>
                <p class="mb-3">"Потрясающий сервис и внимание к деталям! Номер превзошел все наши ожидания. Обязательно вернемся снова."</p>
                <div>
                    <h6 class="mb-0">Алина Сергеева</h6>
                    <small class="text-muted">Москва, Россия</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
        <div class="bg-white p-4 rounded-3 h-100 position-relative">
            <i class="fas fa-quote-left fa-2x text-primary opacity-25 position-absolute top-0 start-0 m-3"></i>
            <div class="text-center mb-3">
                <div class="mb-2">
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                </div>
                <p class="mb-3">"Лучший отель в Бишкеке! Особенно впечатлил ресторан и спа-центр. Персонал очень приветливый и профессиональный."</p>
                <div>
                    <h6 class="mb-0">Марат Касымов</h6>
                    <small class="text-muted">Алматы, Казахстан</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="300">
        <div class="bg-white p-4 rounded-3 h-100 position-relative">
            <i class="fas fa-quote-left fa-2x text-primary opacity-25 position-absolute top-0 start-0 m-3"></i>
            <div class="text-center mb-3">
                <div class="mb-2">
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star text-warning"></i>
                    <i class="fas fa-star-half-alt text-warning"></i>
                </div>
                <p class="mb-3">"Прекрасное расположение и очень удобные номера. Завтрак был восхитительным. Идеальное место для деловых поездок."</p>
                <div>
                    <h6 class="mb-0">Джон Смит</h6>
                    <small class="text-muted">Нью-Йорк, США</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5" data-aos="fade-up">
    <div class="col-md-12 text-center mb-4">
        <h2 class="section-title">Часто задаваемые вопросы</h2>
        <p class="lead mb-5">Ответы на популярные вопросы наших гостей</p>
    </div>

    <div class="col-lg-10 mx-auto">
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-question-circle me-2 text-primary"></i> Какое время заезда и выезда?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Стандартное время заезда — 14:00, выезда — 12:00. Ранний заезд и поздний выезд возможны при наличии свободных номеров и за дополнительную плату.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fas fa-question-circle me-2 text-primary"></i> Есть ли в отеле бесплатный Wi-Fi?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Да, высокоскоростной Wi-Fi доступен бесплатно во всех номерах и общественных зонах отеля.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="fas fa-question-circle me-2 text-primary"></i> Предоставляет ли отель трансфер из аэропорта?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Да, мы предлагаем бесплатный трансфер из аэропорта и обратно для всех наших гостей. Пожалуйста, сообщите нам детали вашего рейса заранее, чтобы мы могли организовать встречу.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <i class="fas fa-question-circle me-2 text-primary"></i> Можно ли отменить бронирование?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Бронирование можно отменить бесплатно за 48 часов до даты заезда. В случае более поздней отмены или незаезда взимается плата в размере стоимости первой ночи проживания.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" data-aos="fade-up">
    <div class="col-md-12">
        <div class="bg-primary text-white p-5 rounded-3 text-center">
            <h2 class="mb-3">Готовы забронировать номер?</h2>
            <p class="lead mb-4">Сделайте первый шаг к незабываемому отдыху прямо сейчас</p>
            <a href="{{ route('rooms.index') }}" class="btn btn-light btn-lg px-5">
                <i class="fas fa-calendar-check me-2"></i>Забронировать
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
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

        // Animate room cards on hover
        const roomCards = document.querySelectorAll('.room-card');
        roomCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.classList.add('shadow-lg');
            });

            card.addEventListener('mouseleave', () => {
                card.classList.remove('shadow-lg');
            });
        });
    });
</script>
@endsection
