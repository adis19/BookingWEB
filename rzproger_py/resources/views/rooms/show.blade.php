@extends('layouts.app')

@section('title', $roomType->name)

@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Rooms</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $roomType->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-7">
        @if($roomType->image)
            <img src="{{ $roomType->image }}" class="img-fluid rounded" alt="{{ $roomType->name }}">
        @else
            <img src="https://via.placeholder.com/800x500?text=Room+Image" class="img-fluid rounded" alt="Room placeholder">
        @endif
    </div>
    <div class="col-md-5">
        <h1>{{ $roomType->name }}</h1>
        <div class="d-flex align-items-center mb-3">
            <span class="badge bg-primary me-2">{{ $roomType->max_occupancy }} Guests</span>
            <span class="h5 mb-0">${{ $roomType->price_per_night }} <small class="text-muted">per night</small></span>
        </div>
        
        <div class="mb-4">
            <p>{{ $roomType->description }}</p>
        </div>
        
        @if(is_array($roomType->amenities) && count($roomType->amenities) > 0)
            <h5>Amenities</h5>
            <div class="row mb-4">
                @foreach($roomType->amenities as $amenity)
                    <div class="col-md-6 mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i> {{ $amenity }}
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="booking-form p-4 bg-light rounded">
            <h5 class="mb-3">Check Availability & Book Now</h5>
            <form action="{{ route('bookings.create') }}" method="POST">
                @csrf
                <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                
                <div class="mb-3">
                    <label for="check_in" class="form-label">Check In Date</label>
                    <input type="date" class="form-control @error('check_in') is-invalid @enderror" id="check_in" name="check_in" min="{{ date('Y-m-d') }}" required>
                    @error('check_in')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="check_out" class="form-label">Check Out Date</label>
                    <input type="date" class="form-control @error('check_out') is-invalid @enderror" id="check_out" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    @error('check_out')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="guests" class="form-label">Number of Guests</label>
                    <select class="form-select @error('guests') is-invalid @enderror" id="guests" name="guests" required>
                        @for ($i = 1; $i <= $roomType->max_occupancy; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    @error('guests')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100">Check Availability</button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Extra Services</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($extraServices as $service)
                        <div class="col-md-3 mb-3">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $service->name }}</h5>
                                    <p class="card-text">{{ $service->description }}</p>
                                    <p class="fw-bold mb-0">${{ $service->price }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
    });
</script>
@endsection
