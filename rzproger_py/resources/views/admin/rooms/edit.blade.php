@extends('layouts.admin')

@section('title', 'Edit Room')

@section('actions')
<a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back to Rooms
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.rooms.update', $room) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="room_number" class="form-label">Room Number</label>
                    <input type="text" class="form-control @error('room_number') is-invalid @enderror" id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" required>
                    @error('room_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="room_type_id" class="form-label">Room Type</label>
                    <select class="form-select @error('room_type_id') is-invalid @enderror" id="room_type_id" name="room_type_id" required>
                        <option value="">Select Room Type</option>
                        @foreach($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}" {{ old('room_type_id', $room->room_type_id) == $roomType->id ? 'selected' : '' }}>
                                {{ $roomType->name }} - ${{ $roomType->price_per_night }}/night
                            </option>
                        @endforeach
                    </select>
                    @error('room_type_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="is_available" name="is_available" {{ old('is_available', $room->is_available) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_available">
                        Is Available
                    </label>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $room->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Update Room</button>
            </div>
        </form>
    </div>
</div>
@endsection
