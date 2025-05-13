@extends('layouts.admin')

@section('title', 'Rooms')

@section('actions')
<a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
    <i class="fas fa-plus-circle"></i> Add Room
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Price/Night</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td>{{ $room->id }}</td>
                            <td>{{ $room->room_number }}</td>
                            <td>{{ $room->roomType->name }}</td>
                            <td>${{ $room->roomType->price_per_night }}</td>
                            <td>
                                @if($room->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-danger">Unavailable</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($room->notes, 30) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $room->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $room->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Room</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete the room <strong>{{ $room->room_number }}</strong>?</p>
                                                    @if($room->bookings()->count() > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i> This room has {{ $room->bookings()->count() }} bookings associated with it. You cannot delete it until all associated bookings are removed.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    @if($room->bookings()->count() == 0)
                                                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No rooms found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
