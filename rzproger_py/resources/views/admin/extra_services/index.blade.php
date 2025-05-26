@extends('layouts.admin')

@section('title', 'Дополнительные услуги')

@section('actions')
<a href="{{ route('admin.extra-services.create') }}" class="btn btn-primary">
    <i class="fas fa-plus-circle"></i> Добавить услугу
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
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Цена</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($extraServices as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ Str::limit($service->description, 50) }}</td>
                            <td>{{ \App\Helpers\CurrencyHelper::convertAndFormat($service->price) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.extra-services.edit', $service) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $service->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Удалить дополнительную услугу</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Вы уверены, что хотите удалить дополнительную услугу <strong>{{ $service->name }}</strong>?</p>
                                                    @if($service->bookings()->count() > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i> Эта услуга используется в {{ $service->bookings()->count() }} бронированиях. Вы не можете её удалить.
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($service->bookings()->count() == 0)
                                                    <form id="deleteForm{{ $service->id }}" action="{{ route('admin.extra-services.destroy', $service) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endif
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                    @if($service->bookings()->count() == 0)
                                                        <button type="submit" form="deleteForm{{ $service->id }}" class="btn btn-danger">Удалить</button>
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
                            <td colspan="5" class="text-center">Дополнительных услуг не найдено</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
