@extends('layouts.admin')

@section('title', 'Редактирование бронирования')

@section('actions')
<a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Назад к бронированиям
</a>
<a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-primary">
    <i class="fas fa-eye"></i> Просмотр бронирования
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="user_id" class="form-label">Гость</label>
                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $booking->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label">Статус</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Ожидает</option>
                        <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Подтверждено</option>
                        <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Отменено</option>
                        <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Завершено</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="room_type_id" class="form-label">Тип номера</label>
                    <select class="form-select @error('room_type_id') is-invalid @enderror" id="room_type_id" name="room_type_id" required>
                        @foreach($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}" {{ old('room_type_id', $booking->room->roomType->id) == $roomType->id ? 'selected' : '' }}>
                                {{ $roomType->name }} - {{ \App\Helpers\CurrencyHelper::formatKgs($roomType->price_per_night) }}/ночь
                            </option>
                        @endforeach
                    </select>
                    @error('room_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="room_id" class="form-label">Номер</label>
                    <select class="form-select @error('room_id') is-invalid @enderror" id="room_id" name="room_id" required>
                        @foreach($booking->room->roomType->rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                                Номер #{{ $room->room_number }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="check_in_date" class="form-label">Дата заезда</label>
                    <input type="date" class="form-control @error('check_in_date') is-invalid @enderror" id="check_in_date" name="check_in_date" value="{{ old('check_in_date', $booking->check_in_date->format('Y-m-d')) }}" required>
                    @error('check_in_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="check_out_date" class="form-label">Дата выезда</label>
                    <input type="date" class="form-control @error('check_out_date') is-invalid @enderror" id="check_out_date" name="check_out_date" value="{{ old('check_out_date', $booking->check_out_date->format('Y-m-d')) }}" required>
                    @error('check_out_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="guests" class="form-label">Количество гостей</label>
                    <input type="number" min="1" class="form-control @error('guests') is-invalid @enderror" id="guests" name="guests" value="{{ old('guests', $booking->guests) }}" required>
                    @error('guests')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="special_requests" class="form-label">Особые пожелания</label>
                <textarea class="form-control @error('special_requests') is-invalid @enderror" id="special_requests" name="special_requests" rows="3">{{ old('special_requests', $booking->special_requests) }}</textarea>
                @error('special_requests')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <h5>Дополнительные услуги</h5>
                <div class="row">
                    @foreach($extraServices as $service)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $service->name }}</h6>
                                    <p class="card-text text-muted">{{ $service->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">{{ \App\Helpers\CurrencyHelper::formatKgs($service->price) }}</span>
                                        <select class="form-select form-select-sm" name="extra_services[{{ $service->id }}]" style="width: 80px;">
                                            @for($i = 0; $i <= 5; $i++)
                                                <option value="{{ $i }}" {{ old('extra_services.' . $service->id, $booking->extraServices->contains($service->id) ? $booking->extraServices->find($service->id)->pivot->quantity : 0) == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Обновить бронирование</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomTypeSelect = document.getElementById('room_type_id');
        const roomSelect = document.getElementById('room_id');
        const guestsInput = document.getElementById('guests');

        // Room types and their rooms data
        const roomTypes = @json($roomTypes->mapWithKeys(function($roomType) {
            return [$roomType->id => [
                'max_occupancy' => $roomType->max_occupancy,
                'rooms' => $roomType->rooms->map(function($room) {
                    return [
                        'id' => $room->id,
                        'room_number' => $room->room_number,
                        'is_available' => $room->is_available
                    ];
                })
            ]];
        }));

        // Update available rooms when room type changes
        roomTypeSelect.addEventListener('change', function() {
            const selectedRoomTypeId = this.value;
            const selectedRoomType = roomTypes[selectedRoomTypeId];

            // Update max guests based on room type
            guestsInput.max = selectedRoomType.max_occupancy;
            if (parseInt(guestsInput.value) > selectedRoomType.max_occupancy) {
                guestsInput.value = selectedRoomType.max_occupancy;
            }

            // Update available rooms
            roomSelect.innerHTML = '';
            selectedRoomType.rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = `Номер #${room.room_number}${!room.is_available ? ' (Недоступен)' : ''}`;
                roomSelect.appendChild(option);
            });
        });

        // Set minimum check-out date to be one day after check-in
        document.getElementById('check_in_date').addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            checkInDate.setDate(checkInDate.getDate() + 1);

            const checkOutField = document.getElementById('check_out_date');
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
