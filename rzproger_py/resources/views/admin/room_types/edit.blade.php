@extends('layouts.admin')

@section('title', 'Редактировать тип номера')

@section('actions')
<a href="{{ route('admin.room-types.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Назад к типам номеров
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.room-types.update', $roomType) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Название</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $roomType->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="price_per_night" class="form-label">Цена за ночь (сом)</label>
                    <input type="number" step="0.01" min="0" class="form-control @error('price_per_night') is-invalid @enderror" id="price_per_night" name="price_per_night" value="{{ old('price_per_night', $roomType->price_per_night) }}" required>
                    @error('price_per_night')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="max_occupancy" class="form-label">Макс. количество гостей</label>
                    <input type="number" min="1" class="form-control @error('max_occupancy') is-invalid @enderror" id="max_occupancy" name="max_occupancy" value="{{ old('max_occupancy', $roomType->max_occupancy) }}" required>
                    @error('max_occupancy')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $roomType->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="amenities" class="form-label">Удобства (через запятую)</label>
                <input type="text" class="form-control @error('amenities') is-invalid @enderror" id="amenities" name="amenities" value="{{ old('amenities', is_array($roomType->amenities) ? implode(', ', $roomType->amenities) : $roomType->amenities) }}" placeholder="WiFi, TV, Mini Bar, etc.">
                @error('amenities')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Введите удобства через запятую (например, Wi-Fi, ТВ, Мини-бар)</div>
            </div>

            <div class="mb-4">
                <label for="image" class="form-label">Изображение номера</label>

                @if($roomType->image)
                    <div class="mb-2">
                        <img src="{{ \App\Helpers\ImageHelper::getImageUrl($roomType->image) }}" alt="{{ $roomType->name }}" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                @endif

                <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Оставьте пустым, чтобы сохранить текущее изображение</div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Обновить тип номера</button>
            </div>
        </form>
    </div>
</div>
@endsection
