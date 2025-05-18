@extends('layouts.admin')

@section('title', 'Добавить номер')

@section('actions')
<a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-primary">
    <i class="fas fa-arrow-left me-1"></i> К списку номеров
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="card-header-title">
            <i class="fas fa-plus me-2"></i> Информация о новом номере
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.rooms.store') }}" method="POST">
            @csrf

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Номер комнаты</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <input type="text" class="form-control @error('room_number') is-invalid @enderror"
                                id="room_number" name="room_number" value="{{ old('room_number') }}"
                                placeholder="Например: 101" required>
                            @error('room_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Введите уникальный номер комнаты.</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="room_type_id" class="form-label">Тип номера</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-bed"></i>
                            </span>
                            <select class="form-select @error('room_type_id') is-invalid @enderror"
                                id="room_type_id" name="room_type_id" required>
                                <option value="">Выберите тип номера</option>
                                @foreach($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                        {{ $roomType->name }} - ${{ $roomType->price_per_night }}/ночь
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Выберите тип номера из списка доступных.</small>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100 stat-card stat-card-info bg-light">
                        <div class="card-body">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" value="1"
                                    id="is_available" name="is_available"
                                    {{ old('is_available', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="is_available">
                                    Номер доступен для бронирования
                                </label>
                            </div>
                            <p class="text-muted mt-2 mb-0 small">
                                <i class="fas fa-info-circle me-1"></i> Если номер недоступен, его нельзя будет забронировать на сайте.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 stat-card stat-card-primary bg-light">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">Особенности номера</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="1" id="has_wifi" name="features[wifi]"
                                    {{ old('features.wifi') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_wifi">
                                    <i class="fas fa-wifi me-1"></i> Бесплатный Wi-Fi
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="1" id="has_view" name="features[view]"
                                    {{ old('features.view') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_view">
                                    <i class="fas fa-mountain me-1"></i> Вид из окна
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="has_ac" name="features[ac]"
                                    {{ old('features.ac') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_ac">
                                    <i class="fas fa-snowflake me-1"></i> Кондиционер
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="notes" class="form-label">Заметки</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-sticky-note"></i>
                    </span>
                    <textarea class="form-control @error('notes') is-invalid @enderror"
                        id="notes" name="notes" rows="4"
                        placeholder="Дополнительная информация о номере...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <small class="text-muted">Добавьте любые заметки или особенности данного номера.</small>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100 stat-card stat-card-warning bg-light">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">Расположение</h6>
                            <div class="mb-3">
                                <label for="floor" class="form-label">Этаж</label>
                                <select class="form-select" id="floor" name="floor">
                                    <option value="">Выберите этаж</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('floor') == $i ? 'selected' : '' }}>{{ $i }} этаж</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="is_corner" name="is_corner"
                                    {{ old('is_corner') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_corner">
                                    <i class="fas fa-border-all me-1"></i> Угловой номер
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 stat-card stat-card-success bg-light">
                        <div class="card-body">
                            <h6 class="fw-bold mb-2">Дополнительно</h6>
                            <div class="mb-3">
                                <label for="max_guests" class="form-label">Максимальное количество гостей</label>
                                <input type="number" class="form-control" id="max_guests" name="max_guests"
                                    min="1" max="10" value="{{ old('max_guests', 2) }}">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="is_smoking" name="is_smoking"
                                    {{ old('is_smoking') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_smoking">
                                    <i class="fas fa-smoking me-1"></i> Разрешено курение
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="fas fa-undo me-1"></i> Сбросить
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Создать номер
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Инициализация всплывающих подсказок
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Валидация формы на стороне клиента
    document.querySelector('form').addEventListener('submit', function(event) {
        let isValid = true;
        const roomNumber = document.getElementById('room_number');
        const roomType = document.getElementById('room_type_id');

        if (!roomNumber.value.trim()) {
            roomNumber.classList.add('is-invalid');
            isValid = false;
        } else {
            roomNumber.classList.remove('is-invalid');
        }

        if (!roomType.value) {
            roomType.classList.add('is-invalid');
            isValid = false;
        } else {
            roomType.classList.remove('is-invalid');
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
</script>
@endsection
