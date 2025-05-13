@extends('layouts.admin')

@section('title', 'Booking Details')

@section('actions')
<a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back to Bookings
</a>
<a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-primary">
    <i class="fas fa-edit"></i> Edit Booking
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Booking #{{ $booking->id }}</h5>
                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm me-2" style="width: 150px;">
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Update Status</button>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Guest Information</h6>
                        <p><strong>Name:</strong> {{ $booking->user->name }}</p>
                        <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                        <p><strong>Phone:</strong> {{ $booking->user->phone ?? 'Not provided' }}</p>
                        <p><strong>Address:</strong> {{ $booking->user->address ?? 'Not provided' }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h6>Booking Details</h6>
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
                        <p><strong>Check-in Date:</strong> {{ $booking->check_in_date->format('M d, Y') }}</p>
                        <p><strong>Check-out Date:</strong> {{ $booking->check_out_date->format('M d, Y') }}</p>
                        <p><strong>Duration:</strong> {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} night(s)</p>
                        <p><strong>Guests:</strong> {{ $booking->guests }}</p>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-12">
                        <h6>Room Information</h6>
                        <p><strong>Room Type:</strong> {{ $booking->room->roomType->name }}</p>
                        <p><strong>Room Number:</strong> {{ $booking->room->room_number }}</p>
                        <p><strong>Price per Night:</strong> ${{ $booking->room->roomType->price_per_night }}</p>
                    </div>
                </div>
                
                @if($booking->special_requests)
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Special Requests</h6>
                            <p>{{ $booking->special_requests }}</p>
                        </div>
                    </div>
                @endif
                
                @if($booking->extraServices->count() > 0)
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h6>Extra Services</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Service</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($booking->extraServices as $service)
                                            <tr>
                                                <td>{{ $service->name }}</td>
                                                <td>${{ $service->pivot->price }}</td>
                                                <td>{{ $service->pivot->quantity }}</td>
                                                <td class="text-end">${{ $service->pivot->price * $service->pivot->quantity }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Add more cards for booking history, notes, etc. if needed -->
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Room Rate:</span>
                    <span>${{ $booking->room->roomType->price_per_night }} × {{ $booking->check_in_date->diffInDays($booking->check_out_date) }} night(s)</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Room Subtotal:</span>
                    <span>${{ $booking->room->roomType->price_per_night * $booking->check_in_date->diffInDays($booking->check_out_date) }}</span>
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
                        <span>Services Subtotal:</span>
                        <span>${{ $booking->getExtraServicesTotal() }}</span>
                    </div>
                @endif
                
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total:</span>
                    <span class="fw-bold">${{ $booking->total_price }}</span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-success" onclick="window.print()">
                        <i class="fas fa-print me-2"></i> Print Booking Details
                    </button>
                    
                    <!-- Delete Button with Modal -->
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i> Delete Booking
                    </button>
                    
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Booking</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this booking?</p>
                                    <p><strong>Room:</strong> {{ $booking->room->roomType->name }} #{{ $booking->room->room_number }}</p>
                                    <p><strong>Guest:</strong> {{ $booking->user->name }}</p>
                                    <p><strong>Dates:</strong> {{ $booking->check_in_date->format('M d, Y') }} - {{ $booking->check_out_date->format('M d, Y') }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete Booking</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
