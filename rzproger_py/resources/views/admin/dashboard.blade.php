@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card stat-card stat-card-primary mb-4">
            <div class="card-body">
                <div class="card-title">Total Bookings</div>
                <div class="card-value">{{ $totalBookings }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-success mb-4">
            <div class="card-body">
                <div class="card-title">Confirmed Bookings</div>
                <div class="card-value">{{ $confirmedBookings }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-warning mb-4">
            <div class="card-body">
                <div class="card-title">Pending Bookings</div>
                <div class="card-value">{{ $pendingBookings }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-info mb-4">
            <div class="card-body">
                <div class="card-title">Monthly Revenue</div>
                <div class="card-value">${{ number_format($monthlyRevenue, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card stat-card stat-card-primary mb-4">
            <div class="card-body">
                <div class="card-title">Total Rooms</div>
                <div class="card-value">{{ $totalRooms }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-success mb-4">
            <div class="card-body">
                <div class="card-title">Available Rooms</div>
                <div class="card-value">{{ $availableRooms }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card stat-card-info mb-4">
            <div class="card-body">
                <div class="card-title">Total Users</div>
                <div class="card-value">{{ $totalUsers }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Recent Bookings</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover dashboard-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->room->roomType->name }}</td>
                                    <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
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
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No recent bookings found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All Bookings</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Upcoming Check-ins</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover dashboard-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Room</th>
                                <th>Check-in</th>
                                <th>Guests</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingCheckIns as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->room->roomType->name }}</td>
                                    <td>{{ $booking->check_in_date->format('M d, Y') }}</td>
                                    <td>{{ $booking->guests }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No upcoming check-ins found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary">View All Bookings</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('admin.room-types.create') }}" class="btn btn-outline-primary btn-lg d-block mb-3">
                            <i class="fas fa-plus-circle me-2"></i> Add Room Type
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.rooms.create') }}" class="btn btn-outline-primary btn-lg d-block mb-3">
                            <i class="fas fa-plus-circle me-2"></i> Add Room
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.extra-services.create') }}" class="btn btn-outline-primary btn-lg d-block mb-3">
                            <i class="fas fa-plus-circle me-2"></i> Add Service
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.bookings.index') }}?status=pending" class="btn btn-outline-warning btn-lg d-block mb-3">
                            <i class="fas fa-clock me-2"></i> View Pending
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
