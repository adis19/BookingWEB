@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Rooms</a></li>
                <li class="breadcrumb-item active" aria-current="page">Search Results</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-0">Search Criteria</h5>
                        <p class="text-muted mb-0">
                            <strong>Check-in:</strong> {{ $checkIn->format('M d, Y') }} | 
                            <strong>Check-out:</strong> {{ $checkOut->format('M d, Y') }} | 
                            <strong>Guests:</strong> {{ $guests }} | 
                            <strong>Duration:</strong> {{ $checkIn->diffInDays($checkOut) }} night(s)
                        </p>
                    </div>
                    <button class="btn btn-outline-secondary mt-2 mt-md-0" type="button" data-bs-toggle="collapse" data-bs-target="#searchForm">
                        Modify Search
                    </button>
                </div>
                
                <div class="collapse mt-3" id="searchForm">
                    <form action="{{ route('rooms.search') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="check_in" class="form-label">Check In</label>
                                <input type="date" class="form-control" id="check_in" name="check_in" min="{{ date('Y-m-d') }}" value="{{ $checkIn->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="check_out" class="form-label">Check Out</label>
                                <input type="date" class="form-control" id="check_out" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ $checkOut->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label for="guests" class="form-label">Guests</label>
                                <select class="form-select" id="guests" name="guests" required>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ $guests == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h2>Available Rooms</h2>
        @if($availableRoomTypes->count() > 0)
            <p>We found {{ $availableRoomTypes->count() }} room type(s) matching your criteria.</p>
            
            <div class="row">
                @foreach($availableRoomTypes as $roomType)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 room-card">
                            @if($roomType->image)
                                <img src="{{ $roomType->image }}" class="card-img-top" alt="{{ $roomType->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=Room+Image" class="card-img-top" alt="Room placeholder">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $roomType->name }}</h5>
                                <p class="card-text">{{ Str::limit($roomType->description, 100) }}</p>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item"><i class="fas fa-user-friends me-2"></i> Max {{ $roomType->max_occupancy }} guests</li>
                                    <li class="list-group-item"><i class="fas fa-dollar-sign me-2"></i> ${{ $roomType->price_per_night }} per night</li>
                                    <li class="list-group-item"><i class="fas fa-calculator me-2"></i> Total: ${{ $roomType->price_per_night * $checkIn->diffInDays($checkOut) }}</li>
                                </ul>
                                
                                <form action="{{ route('bookings.create') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                    <input type="hidden" name="check_in" value="{{ $checkIn->format('Y-m-d') }}">
                                    <input type="hidden" name="check_out" value="{{ $checkOut->format('Y-m-d') }}">
                                    <input type="hidden" name="guests" value="{{ $guests }}">
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Book Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> Sorry, we couldn't find any available rooms matching your criteria. Please try different dates or guest count.
            </div>
        @endif
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Extra Services Available</h5>
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
                <p class="text-muted mt-2">Note: Extra services can be added during the booking process.</p>
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
