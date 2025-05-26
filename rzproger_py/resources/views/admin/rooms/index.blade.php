@extends('layouts.admin')

@section('title', 'Номера')

@section('actions')
<a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">
    <i class="fas fa-plus-circle"></i> Добавить номер
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
                        <th>Номер комнаты</th>
                        <th>Тип номера</th>
                        <th>Цена/Ночь</th>
                        <th>Статус</th>
                        <th>Примечания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td>{{ $room->id }}</td>
                            <td>{{ $room->room_number }}</td>
                            <td>{{ $room->roomType->name }}</td>
                            <td>{{ \App\Helpers\CurrencyHelper::formatKgs($room->roomType->price_per_night) }}</td>
                            <td>
                                @php
                                    // Проверяем наличие подтвержденных бронирований
                                    $confirmedBookings = $room->bookings()
                                        ->where('status', 'confirmed')
                                        ->exists();
                                @endphp

                                @if($confirmedBookings)
                                    <span class="badge bg-danger">Занят</span>
                                @else
                                    <span class="badge bg-success">Доступен</span>
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
                                                    <h5 class="modal-title">Удалить номер</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Вы уверены, что хотите удалить номер <strong>{{ $room->room_number }}</strong>?</p>
                                                    @if($room->bookings()->count() > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i> Этот номер имеет {{ $room->bookings()->count() }} бронирований. Вы не можете удалить его, пока все связанные бронирования не будут удалены.
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($room->bookings()->count() == 0)
                                                    <form id="deleteForm{{ $room->id }}" action="{{ route('admin.rooms.destroy', $room) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                    @if($room->bookings()->count() == 0)
                                                        <button type="submit" form="deleteForm{{ $room->id }}" class="btn btn-danger">Удалить</button>
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
                            <td colspan="7" class="text-center">Номеров не найдено</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
