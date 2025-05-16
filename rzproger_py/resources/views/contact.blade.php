@extends('layouts.app')

@section('title', 'Контакты')

@section('hero')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">Свяжитесь с нами</h1>
        <p class="lead">Мы готовы помочь с любыми вопросами, которые у вас возникнут</p>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-5">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h2>Напишите нам</h2>
                <p class="mb-4">Есть вопросы о наших номерах, услугах или бронировании? Заполните форму, и мы свяжемся с вами как можно скорее.</p>

                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Ваше имя</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Электронная почта</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Тема</label>
                        <input type="text" class="form-control" id="subject" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Сообщение</label>
                        <textarea class="form-control" id="message" rows="5" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Отправить сообщение</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h2>Контактная информация</h2>
                <p class="mb-4">Вы также можете связаться с нами по следующим контактным данным:</p>

                <div class="d-flex mb-3">
                    <i class="fas fa-map-marker-alt text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-1">Адрес</h5>
                        <p>ул. Исанова 79<br>Бишкек, Кыргызстан, 720000</p>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <i class="fas fa-phone text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-1">Телефон</h5>
                        <p>+996 312 123 456<br>+996 555 123 456</p>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <i class="fas fa-envelope text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-1">Электронная почта</h5>
                        <p>info@luxhotel.kg<br>booking@luxhotel.kg</p>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <i class="fas fa-clock text-primary me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="mb-1">Часы работы</h5>
                        <p>Понедельник - Пятница: 9:00 - 18:00<br>Суббота: 10:00 - 16:00<br>Воскресенье: Выходной</p>
                    </div>
                </div>

                <h5 class="mb-3">Подписывайтесь на нас</h5>
                <div class="social-icons">
                    <a href="#" class="me-3"><i class="fab fa-facebook fa-2x text-primary"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-twitter fa-2x text-primary"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-instagram fa-2x text-primary"></i></a>
                    <a href="#" class="me-3"><i class="fab fa-linkedin fa-2x text-primary"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body p-0">
                <div class="ratio ratio-16x9">
                    <!-- Replace with your actual Google Maps embed code -->
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11656.283337838005!2d74.5834499243164!3d42.87411260000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x389eb7c5b9c535e1%3A0x30f8c4dea34c16e4!2sBishkek%2C%20Kyrgyzstan!5e0!3m2!1sen!2sus!4v1620831111111!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
