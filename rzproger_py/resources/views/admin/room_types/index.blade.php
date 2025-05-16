@extends('layouts.admin')

@section('title', 'Типы номеров')

@section('actions')
<a href="{{ route('admin.room-types.create') }}" class="btn btn-primary">
    <i class="fas fa-plus-circle"></i> Добавить тип номера
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
                        <th>Изображение</th>
                        <th>Название</th>
                        <th>Цена/Ночь</th>
                        <th>Макс. гостей</th>
                        <th>Комнат</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomTypes as $roomType)
                        <tr>
                            <td>{{ $roomType->id }}</td>
                            <td>
                                @if($roomType->image)
                                    <img src="{{ $roomType->image }}" alt="{{ $roomType->name }}" width="50" height="50" class="img-thumbnail">
                                @else
                                    <div class="bg-light text-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted" style="line-height: 50px;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $roomType->name }}</td>
                            <td>{{ \App\Helpers\CurrencyHelper::formatKgs($roomType->price_per_night) }}</td>
                            <td>{{ $roomType->max_occupancy }}</td>
                            <td>{{ $roomType->rooms()->count() }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.room-types.edit', $roomType) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $roomType->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $roomType->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Удалить тип номера</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Вы уверены, что хотите удалить тип номера <strong>{{ $roomType->name }}</strong>?</p>
                                                    @if($roomType->rooms()->count() > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i> Этот тип номера имеет {{ $roomType->rooms()->count() }} связанных комнат. Вы не можете удалить его, пока все связанные номера не будут удалены.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                    @if($roomType->rooms()->count() == 0)
                                                        <form action="{{ route('admin.room-types.destroy', $roomType) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Удалить</button>
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
                            <td colspan="7" class="text-center">Типов номеров не найдено</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
