@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <h3 class="mb-0 fw-bold">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Добро пожаловать
                </h3>
                <p class="mb-0 mt-2 opacity-75">Войдите в свой аккаунт</p>
            </div>

            <div class="card-body p-4">
                <!-- Google OAuth кнопка в самом верху -->
                <div class="d-grid mb-4">
                    <a href="{{ route('auth.google') }}" class="btn btn-outline-danger btn-lg py-3">
                        <i class="fab fa-google me-2"></i>
                        Войти через Google
                    </a>
                </div>

                <!-- Разделитель -->
                <div class="position-relative text-center mb-4">
                    <hr class="my-4">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                        или используйте email
                    </span>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" placeholder="email@example.com" required autocomplete="email" autofocus>
                            <label for="email">
                                <i class="fas fa-envelope me-1"></i>Электронная почта
                            </label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Пароль -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" placeholder="Пароль" required autocomplete="current-password">
                            <label for="password">
                                <i class="fas fa-lock me-1"></i>Пароль
                            </label>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Запомнить меня -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="remember">
                                <i class="fas fa-remember me-1"></i>
                                Запомнить меня
                            </label>
                        </div>
                    </div>

                    <!-- Кнопка входа -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg py-3">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Войти в аккаунт
                        </button>
                    </div>

                    <!-- Забыли пароль -->
                    <div class="text-center mt-3">
                        <a href="#" class="text-muted text-decoration-none small">
                            <i class="fas fa-question-circle me-1"></i>
                            Забыли пароль?
                        </a>
                    </div>
                </form>
            </div>

            <div class="card-footer bg-light text-center py-3">
                <p class="mb-0 text-muted">
                    Еще нет аккаунта?
                    <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">
                        Зарегистрироваться
                    </a>
                </p>
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Ваши данные защищены SSL-шифрованием
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

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-input:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endsection
