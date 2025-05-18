@extends('layouts.admin')

@section('title', 'Добавить тип номера')

@section('actions')
<a href="{{ route('admin.room-types.index') }}" class="btn btn-outline-primary">
    <i class="fas fa-arrow-left me-1"></i> К списку типов номеров
</a>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css">
<style>
    .amenity-tag {
        display: inline-block;
        background-color: rgba(78, 115, 223, 0.1);
        color: var(--primary);
        border-radius: 20px;
        padding: 6px 12px;
        margin-right: 8px;
        margin-bottom: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .amenity-tag.selected {
        background-color: var(--primary);
        color: white;
    }

    .preview-container {
        margin-top: 1rem;
        border: 2px dashed #dee2e6;
        border-radius: 0.35rem;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s;
    }

    .preview-container:hover {
        border-color: var(--primary);
    }

    .image-preview {
        max-height: 200px;
        max-width: 100%;
        margin: 0 auto;
        display: none;
    }

    .upload-icon {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    .cropper-container {
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-header-title">
                    <i class="fas fa-plus me-2"></i> Информация о типе номера
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.room-types.store') }}" method="POST" enctype="multipart/form-data" id="roomTypeForm">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="name" class="form-label">Название типа номера</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        placeholder="Например: Люкс с видом на море" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Введите уникальное название типа номера.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price_per_night" class="form-label">Цена за ночь (сом)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control @error('price_per_night') is-invalid @enderror"
                                        id="price_per_night" name="price_per_night" value="{{ old('price_per_night') }}"
                                        placeholder="0.00" required>
                                    @error('price_per_night')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="max_occupancy" class="form-label">Максимальное количество гостей</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <input type="number" min="1" max="10"
                                        class="form-control @error('max_occupancy') is-invalid @enderror"
                                        id="max_occupancy" name="max_occupancy" value="{{ old('max_occupancy', 2) }}"
                                        required>
                                    @error('max_occupancy')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Описание</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-align-left"></i>
                            </span>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="5"
                                placeholder="Подробное описание типа номера..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Максимум 500 символов.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Удобства и особенности</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                <i class="fas fa-list"></i>
                            </span>
                            <input type="text" class="form-control" id="amenity-input"
                                placeholder="Введите удобство и нажмите Enter">
                        </div>
                        <div id="amenities-container" class="mb-2">
                            <div class="amenity-tag" data-value="Wi-Fi">
                                <i class="fas fa-wifi me-1"></i> Wi-Fi
                            </div>
                            <div class="amenity-tag" data-value="Кондиционер">
                                <i class="fas fa-snowflake me-1"></i> Кондиционер
                            </div>
                            <div class="amenity-tag" data-value="Телевизор">
                                <i class="fas fa-tv me-1"></i> Телевизор
                            </div>
                            <div class="amenity-tag" data-value="Мини-бар">
                                <i class="fas fa-glass-martini-alt me-1"></i> Мини-бар
                            </div>
                            <div class="amenity-tag" data-value="Сейф">
                                <i class="fas fa-lock me-1"></i> Сейф
                            </div>
                            <div class="amenity-tag" data-value="Джакузи">
                                <i class="fas fa-hot-tub me-1"></i> Джакузи
                            </div>
                            <div class="amenity-tag" data-value="Балкон">
                                <i class="fas fa-door-open me-1"></i> Балкон
                            </div>
                        </div>
                        <div id="selected-amenities" class="mb-2 small text-muted">
                            Выбранные удобства: <span id="amenities-list">нет</span>
                        </div>
                        <input type="hidden" name="amenities" id="amenities" value="{{ old('amenities') }}">
                        @error('amenities')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Изображение номера</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-image"></i>
                            </span>
                            <input class="form-control @error('image') is-invalid @enderror"
                                type="file" id="image-input" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Рекомендуемый размер: 1200x800 пикселей, до 2MB.</small>

                        <div class="preview-container" id="preview-container">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <p class="mb-0">Перетащите изображение сюда или нажмите для выбора</p>
                            <img src="#" alt="Предпросмотр" class="image-preview" id="image-preview">
                        </div>

                        <div class="cropper-container d-none" id="cropper-container">
                            <canvas id="cropper-canvas"></canvas>
                            <div class="d-flex justify-content-between mt-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="cancel-crop">
                                    <i class="fas fa-times me-1"></i> Отмена
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" id="apply-crop">
                                    <i class="fas fa-crop-alt me-1"></i> Применить
                                </button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cropped_image" id="cropped_image">

                    <div class="d-flex justify-content-between">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-undo me-1"></i> Сбросить
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Создать тип номера
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card stat-card stat-card-info mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Предпросмотр</h5>
                <div class="room-preview">
                    <div class="room-preview-image mb-3 bg-light rounded" style="height: 150px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="fw-bold" id="preview-name">Название типа номера</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary px-3 py-2" id="preview-price">0 сом / ночь</span>
                        <span class="text-muted small">
                            <i class="fas fa-users me-1"></i> <span id="preview-occupancy">0</span> гостей макс.
                        </span>
                    </div>
                    <p class="text-muted small mb-3" id="preview-description">Описание типа номера будет отображаться здесь...</p>
                    <div class="mb-2">
                        <div class="d-flex flex-wrap" id="preview-amenities">
                            <!-- Здесь будут отображаться выбранные удобства -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card stat-card stat-card-primary">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Подсказки</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        <strong>Название:</strong> Используйте короткие и запоминающиеся названия.
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        <strong>Описание:</strong> Включите уникальные особенности номера.
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        <strong>Цена:</strong> Изучите рынок для конкурентного ценообразования.
                    </li>
                    <li>
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        <strong>Фото:</strong> Загружайте качественные и хорошо освещенные изображения.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
    // Обработка удобств
    const amenityTags = document.querySelectorAll('.amenity-tag');
    const amenitiesInput = document.getElementById('amenities');
    const amenitiesList = document.getElementById('amenities-list');
    const amenityInput = document.getElementById('amenity-input');

    // Функция обновления выбранных удобств
    function updateSelectedAmenities() {
        const selectedTags = document.querySelectorAll('.amenity-tag.selected');
        const selectedValues = Array.from(selectedTags).map(tag => tag.dataset.value);

        amenitiesInput.value = selectedValues.join(',');
        amenitiesList.textContent = selectedValues.length > 0 ? selectedValues.join(', ') : 'нет';

        // Обновление предпросмотра
        const previewAmenities = document.getElementById('preview-amenities');
        previewAmenities.innerHTML = '';

        selectedValues.forEach(amenity => {
            const badge = document.createElement('span');
            badge.classList.add('badge', 'bg-light', 'text-primary', 'me-1', 'mb-1');
            badge.textContent = amenity;
            previewAmenities.appendChild(badge);
        });
    }

    // Инициализация удобств из сохраненного значения
    if (amenitiesInput.value) {
        const savedAmenities = amenitiesInput.value.split(',');
        amenityTags.forEach(tag => {
            if (savedAmenities.includes(tag.dataset.value)) {
                tag.classList.add('selected');
            }
        });
        updateSelectedAmenities();
    }

    // Обработка клика по тегам удобств
    amenityTags.forEach(tag => {
        tag.addEventListener('click', () => {
            tag.classList.toggle('selected');
            updateSelectedAmenities();
        });
    });

    // Добавление пользовательских удобств
    amenityInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();

            const value = amenityInput.value.trim();
            if (value) {
                // Проверка на дубликаты
                const exists = Array.from(amenityTags).some(tag =>
                    tag.dataset.value.toLowerCase() === value.toLowerCase()
                );

                if (!exists) {
                    const container = document.getElementById('amenities-container');
                    const newTag = document.createElement('div');
                    newTag.classList.add('amenity-tag');
                    newTag.dataset.value = value;
                    newTag.innerHTML = `<i class="fas fa-check me-1"></i> ${value}`;

                    newTag.addEventListener('click', () => {
                        newTag.classList.toggle('selected');
                        updateSelectedAmenities();
                    });

                    container.appendChild(newTag);
                    newTag.classList.add('selected');
                    updateSelectedAmenities();
                }

                amenityInput.value = '';
            }
        }
    });

    // Обработка предпросмотра
    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price_per_night');
    const occupancyInput = document.getElementById('max_occupancy');
    const descriptionInput = document.getElementById('description');

    const previewName = document.getElementById('preview-name');
    const previewPrice = document.getElementById('preview-price');
    const previewOccupancy = document.getElementById('preview-occupancy');
    const previewDescription = document.getElementById('preview-description');

    // Обновление предпросмотра при вводе
    nameInput.addEventListener('input', () => {
        previewName.textContent = nameInput.value || 'Название типа номера';
    });

    priceInput.addEventListener('input', () => {
        previewPrice.textContent = `${priceInput.value || 0} сом / ночь`;
    });

    occupancyInput.addEventListener('input', () => {
        previewOccupancy.textContent = occupancyInput.value || 0;
    });

    descriptionInput.addEventListener('input', () => {
        previewDescription.textContent = descriptionInput.value || 'Описание типа номера будет отображаться здесь...';
    });

    // Инициализация предпросмотра
    if (nameInput.value) previewName.textContent = nameInput.value;
    if (priceInput.value) previewPrice.textContent = `${priceInput.value} сом / ночь`;
    if (occupancyInput.value) previewOccupancy.textContent = occupancyInput.value;
    if (descriptionInput.value) previewDescription.textContent = descriptionInput.value;

    // Обработка загрузки изображения
    const imageInput = document.getElementById('image-input');
    const imagePreview = document.getElementById('image-preview');
    const previewContainer = document.getElementById('preview-container');
    const cropperContainer = document.getElementById('cropper-container');
    const uploadIcon = document.querySelector('.upload-icon');
    const previewText = document.querySelector('#preview-container p');

    let cropper;

    // Обработка события выбора файла
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Проверка типа файла
            if (!file.type.startsWith('image/')) {
                alert('Пожалуйста, выберите изображение.');
                return;
            }

            // Отображение предпросмотра
            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block';
                uploadIcon.style.display = 'none';
                previewText.style.display = 'none';

                // Обновление предпросмотра в карточке
                const roomPreviewImage = document.querySelector('.room-preview-image');
                roomPreviewImage.innerHTML = '';
                roomPreviewImage.style.background = `url(${event.target.result}) no-repeat center center`;
                roomPreviewImage.style.backgroundSize = 'cover';

                // Инициализация Cropper
                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(imagePreview, {
                    aspectRatio: 16 / 9,
                    viewMode: 1,
                    guides: true,
                    autoCropArea: 0.8,
                    responsive: true,
                    background: false,
                });

                cropperContainer.classList.remove('d-none');
            };

            reader.readAsDataURL(file);
        }
    });

    // Обработка Drag & Drop
    previewContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
        previewContainer.style.borderColor = 'var(--primary)';
        previewContainer.style.backgroundColor = 'rgba(78, 115, 223, 0.05)';
    });

    previewContainer.addEventListener('dragleave', function() {
        previewContainer.style.borderColor = '#dee2e6';
        previewContainer.style.backgroundColor = '';
    });

    previewContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        previewContainer.style.borderColor = '#dee2e6';
        previewContainer.style.backgroundColor = '';

        if (e.dataTransfer.files.length) {
            imageInput.files = e.dataTransfer.files;
            // Генерация события change
            const event = new Event('change', { bubbles: true });
            imageInput.dispatchEvent(event);
        }
    });

    // Клик по области загрузки
    previewContainer.addEventListener('click', function() {
        if (!cropper) {
            imageInput.click();
        }
    });

    // Кнопки для работы с кропером
    document.getElementById('cancel-crop').addEventListener('click', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        imagePreview.style.display = 'none';
        uploadIcon.style.display = 'block';
        previewText.style.display = 'block';
        cropperContainer.classList.add('d-none');
        imageInput.value = '';

        // Сброс предпросмотра в карточке
        const roomPreviewImage = document.querySelector('.room-preview-image');
        roomPreviewImage.innerHTML = '<i class="fas fa-image text-muted" style="font-size: 3rem;"></i>';
        roomPreviewImage.style.background = '';
    });

    document.getElementById('apply-crop').addEventListener('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 1200,
                height: 800,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            // Установка кадрированного изображения в предпросмотр
            canvas.toDataURL('image/jpeg', 0.8);

            // Сохранение кадрированного изображения
            document.getElementById('cropped_image').value = canvas.toDataURL('image/jpeg', 0.8);

            // Обновление предпросмотра в карточке
            const roomPreviewImage = document.querySelector('.room-preview-image');
            roomPreviewImage.innerHTML = '';
            roomPreviewImage.style.background = `url(${canvas.toDataURL('image/jpeg', 0.8)}) no-repeat center center`;
            roomPreviewImage.style.backgroundSize = 'cover';

            cropperContainer.classList.add('d-none');
        }
    });

    // Валидация формы
    document.getElementById('roomTypeForm').addEventListener('submit', function(e) {
        let isValid = true;

        if (!nameInput.value.trim()) {
            nameInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!priceInput.value || parseFloat(priceInput.value) <= 0) {
            priceInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!occupancyInput.value || parseInt(occupancyInput.value) <= 0) {
            occupancyInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!descriptionInput.value.trim()) {
            descriptionInput.classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Сброс валидации при вводе
    const inputs = [nameInput, priceInput, occupancyInput, descriptionInput];
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
</script>
@endsection
