@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0 fw-bold">
                    <i class="fas fa-user-plus me-2"></i>
                    Создать аккаунт
                </h3>
                <p class="mb-0 mt-2 opacity-75">Присоединяйтесь к Люкс Отель</p>
            </div>

            <div class="card-body p-4">
                <!-- Google OAuth кнопка в самом верху -->
                <div class="d-grid mb-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-danger btn-lg py-3">
                        <i class="fab fa-google me-2"></i>
                        Зарегистрироваться через Google
                    </a>
                </div>

                <!-- Разделитель -->
                <div class="position-relative text-center mb-4">
                    <hr class="my-4">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                        или заполните форму
                    </span>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Имя и Email в одной строке -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" placeholder="Ваше имя" required autocomplete="name" autofocus>
                                <label for="name">
                                    <i class="fas fa-user me-1"></i>Имя *
                                </label>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" placeholder="email@example.com" required autocomplete="email">
                                <label for="email">
                                    <i class="fas fa-envelope me-1"></i>Email *
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Телефон -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   name="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67" autocomplete="phone">
                            <label for="phone">
                                <i class="fas fa-phone me-1"></i>Номер телефона
                            </label>
                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Адрес -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                                      name="address" placeholder="Ваш адрес" style="height: 100px">{{ old('address') }}</textarea>
                            <label for="address">
                                <i class="fas fa-map-marker-alt me-1"></i>Адрес
                            </label>
                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Пароли -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" placeholder="Пароль" required autocomplete="new-password">
                                <label for="password">
                                    <i class="fas fa-lock me-1"></i>Пароль *
                                </label>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" placeholder="Подтвердите пароль" required autocomplete="new-password">
                                <label for="password-confirm">
                                    <i class="fas fa-lock me-1"></i>Подтверждение *
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка регистрации -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg py-3">
                            <i class="fas fa-user-plus me-2"></i>
                            Создать аккаунт
                        </button>
                    </div>
                </form>
            </div>

            <div class="card-footer bg-light text-center py-3">
                <p class="mb-0 text-muted">
                    Уже есть аккаунт?
                    <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">
                        Войти
                    </a>
                </p>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Регистрируясь, вы соглашаетесь с нашими условиями использования
            </small>
        </div>
    </div>
</div>

<style>
.form-floating > label {
    color: #6c757d;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    color: #0d6efd;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn-lg {
    border-radius: 10px;
}

.form-control {
    border-radius: 8px;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>
@endsection
