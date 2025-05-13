@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">My Bookings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Booking #{{ $booking->id }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Booking Details <span class="float-end">
                    @if($booking->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($booking->status == 'confirmed')
                        <span class="badge bg-success">Confirmed</span>
                    @elseif($booking->status == 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @elseif($booking->status == 'completed')
                        <span class="badge bg-secondary">Completed</span>
                    @endif
                </span></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Booking Information</h5>
                        <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                        <p><strong>Check-in Date:</strong> {{ $booking->check_in_date->format('M d, Y') }}</p>
                        <p><strong>Check-out Date:</strong> {{ $booking->check_out_date->format('M d, Y') }}</p>
                        <p><strong>Duration:</strong> {{ $booking->getDurationInDays() }} night(s)</p>
                        <p><strong>Number of Guests:</strong> {{ $booking->guests }}</p>
                        <p><strong>Status:</strong> 
                            @if($booking->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @elseif($booking->status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @elseif($booking->status == 'completed')
                                <span class="badge bg-secondary">Completed</span>
                            @endif
                        </p>
                        <p><strong>Booking Date:</strong> {{ $booking->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Room Information</h5>
                        <p><strong>Room Type:</strong> {{ $booking->room->roomType->name }}</p>
                        <p><strong>Room Number:</strong> {{ $booking->room->room_number }}</p>
                        <p><strong>Max Occupancy:</strong> {{ $booking->room->roomType->max_occupancy }} guests</p>
                        <p><strong>Price per Night:</strong> ${{ $booking->room->roomType->price_per_night }}</p>
                        
                        @if($booking->special_requests)
                            <h5 class="mt-4">Special Requests</h5>
                            <p>{{ $booking->special_requests }}</p>
                        @endif
                    </div>
                </div>
                
                @if($booking->extraServices->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Extra Services</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Service</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($booking->extraServices as $service)
                                            <tr>
                                                <td>{{ $service->name }}</td>
                                                <td>${{ $service->pivot->price }}</td>
                                                <td>{{ $service->pivot->quantity }}</td>
                                                <td>${{ $service->pivot->price * $service->pivot->quantity }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4">
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back to Bookings</a>
                    
                    @if(($booking->status == 'pending' || $booking->status == 'confirmed') && $booking->check_in_date->isFuture())
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            Cancel Booking
                        </button>
                        
                        <!-- Cancel Modal -->
                        <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cancel Booking #{{ $booking->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to cancel this booking?</p>
                                        <p><strong>Room:</strong> {{ $booking->room->roomType->name }}</p>
                                        <p><strong>Dates:</strong> {{ $booking->check_in_date->format('M d, Y') }} - {{ $booking->check_out_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Cancel Booking</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Room Rate:</span>
                    <span>${{ $booking->room->roomType->price_per_night }} × {{ $booking->getDurationInDays() }} night(s)</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Room Total:</span>
                    <span>${{ $booking->room->roomType->price_per_night * $booking->getDurationInDays() }}</span>
                </div>
                
                @if($booking->extraServices->count() > 0)
                    <hr>
                    <h6>Extra Services:</h6>
                    @foreach($booking->extraServices as $service)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $service->name }} × {{ $service->pivot->quantity }}</span>
                            <span>${{ $service->pivot->price * $service->pivot->quantity }}</span>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mb-2">
                        <span>Services Total:</span>
                        <span>${{ $booking->getExtraServicesTotal() }}</span>
                    </div>
                @endif
                
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="h5">Total:</span>
                    <span class="h5">${{ $booking->total_price }}</span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Need Help?</h5>
                <p class="card-text">If you have any questions or need assistance with your booking, please don't hesitate to contact us.</p>
                <div class="d-grid">
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
