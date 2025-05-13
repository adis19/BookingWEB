@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">My Bookings</h1>
        
        @if($bookings->count() > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Upcoming & Recent Bookings</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Room</th>
                                            <th>Dates</th>
                                            <th>Guests</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            <tr>
                                                <td>#{{ $booking->id }}</td>
                                                <td>
                                                    {{ $booking->room->roomType->name }}<br>
                                                    <small class="text-muted">Room #{{ $booking->room->room_number }}</small>
                                                </td>
                                                <td>
                                                    {{ $booking->check_in_date->format('M d, Y') }} - {{ $booking->check_out_date->format('M d, Y') }}<br>
                                                    <small class="text-muted">{{ $booking->getDurationInDays() }} night(s)</small>
                                                </td>
                                                <td>{{ $booking->guests }}</td>
                                                <td>${{ $booking->total_price }}</td>
                                                <td>
                                                    @if($booking->status == 'pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @elseif($booking->status == 'confirmed')
                                                        <span class="badge bg-success">Confirmed</span>
                                                    @elseif($booking->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @elseif($booking->status == 'completed')
                                                        <span class="badge bg-secondary">Completed</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                    
                                                    @if($booking->status == 'pending' || $booking->status == 'confirmed')
                                                        @if($booking->check_in_date->isFuture())
                                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                                                Cancel
                                                            </button>
                                                            
                                                            <!-- Cancel Modal -->
                                                            <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
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
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle me-2"></i> Booking Information</h5>
                <ul class="mb-0">
                    <li><strong>Pending</strong> - Your booking is waiting to be confirmed by our staff</li>
                    <li><strong>Confirmed</strong> - Your booking has been confirmed and is ready for check-in</li>
                    <li><strong>Cancelled</strong> - The booking has been cancelled</li>
                    <li><strong>Completed</strong> - Your stay has been completed</li>
                </ul>
            </div>
        @else
            <div class="alert alert-info">
                <h5><i class="fas fa-info-circle me-2"></i> No Bookings Found</h5>
                <p class="mb-0">You don't have any bookings yet. <a href="{{ route('rooms.index') }}" class="alert-link">Browse our rooms</a> to make your first booking!</p>
            </div>
        @endif
    </div>
</div>
@endsection
