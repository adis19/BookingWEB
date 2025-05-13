@extends('layouts.app')

@section('title', 'Welcome')

@section('hero')
<div class="hero-section">
    <div class="container text-center">
        <h1 class="display-4">Welcome to LuxuryStay</h1>
        <p class="lead">Experience the ultimate luxury in our premium hotel rooms</p>
        <a href="{{ route('rooms.index') }}" class="btn btn-primary btn-lg mt-3">Browse Rooms</a>
    </div>
</div>
@endsection

@section('content')
<div class="row mb-5">
    <div class="col-md-12 text-center">
        <h2>Find Your Perfect Stay</h2>
        <p class="lead mb-4">Search for available rooms based on your dates and preferences</p>
    </div>
    <div class="col-md-8 mx-auto">
        <div class="booking-form">
            <form action="{{ route('rooms.search') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="check_in" class="form-label">Check In</label>
                        <input type="date" class="form-control @error('check_in') is-invalid @enderror" id="check_in" name="check_in" min="{{ date('Y-m-d') }}" required>
                        @error('check_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="check_out" class="form-label">Check Out</label>
                        <input type="date" class="form-control @error('check_out') is-invalid @enderror" id="check_out" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('check_out')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="guests" class="form-label">Guests</label>
                        <select class="form-select @error('guests') is-invalid @enderror" id="guests" name="guests" required>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        @error('guests')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12 text-center mb-4">
        <h2>Our Room Types</h2>
        <p class="lead">Explore our selection of premium accommodations</p>
    </div>
    
    @foreach($roomTypes as $roomType)
    <div class="col-md-4">
        <div class="card room-card">
            @if($roomType->image)
                <img src="{{ $roomType->image }}" class="card-img-top" alt="{{ $roomType->name }}">
            @else
                <img src="https://via.placeholder.com/300x200?text=Room+Image" class="card-img-top" alt="Room placeholder">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $roomType->name }}</h5>
                <p class="card-text">{{ Str::limit($roomType->description, 100) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="h5">${{ $roomType->price_per_night }} <small>/night</small></span>
                    <a href="{{ route('rooms.show', $roomType) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row mb-5">
    <div class="col-md-12 text-center mb-4">
        <h2>Why Choose Us</h2>
        <p class="lead">Enjoy luxury amenities and services during your stay</p>
    </div>
    
    <div class="col-md-4">
        <div class="feature-box">
            <i class="fas fa-concierge-bell"></i>
            <h4>Premium Services</h4>
            <p>24/7 service, concierge assistance, room service, and more to make your stay comfortable.</p>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="feature-box">
            <i class="fas fa-wifi"></i>
            <h4>Modern Amenities</h4>
            <p>High-speed WiFi, smart TVs, spa facilities, fitness center, and swimming pool.</p>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="feature-box">
            <i class="fas fa-map-marker-alt"></i>
            <h4>Prime Location</h4>
            <p>Located in the heart of the city with easy access to tourist attractions and business districts.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center mb-4">
        <h2>Extra Services</h2>
        <p class="lead">Enhance your stay with our additional services</p>
    </div>
    
    @foreach($extraServices as $service)
    <div class="col-md-3">
        <div class="card mb-4 text-center">
            <div class="card-body">
                <i class="fas fa-plus-circle text-primary mb-3" style="font-size: 2rem;"></i>
                <h5 class="card-title">{{ $service->name }}</h5>
                <p class="card-text">{{ Str::limit($service->description, 80) }}</p>
                <p class="fw-bold">${{ $service->price }}</p>
            </div>
        </div>
    </div>
    @endforeach
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
