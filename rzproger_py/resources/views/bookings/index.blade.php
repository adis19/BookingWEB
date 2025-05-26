@extends('layouts.app')

@section('title', 'Мои бронирования')

@section('content')
<div class="page-title">
    <div class="container">
        <h1><i class="fas fa-calendar-check me-3"></i>Мои бронирования</h1>
        <p class="lead">Управляйте своими бронированиями и отслеживайте статус</p>
    </div>
</div>

<!-- Глобальное модальное окно для отмены бронирования -->
<div class="modal fade" id="globalCancelModal" tabindex="-1" aria-labelledby="globalCancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="globalCancelModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Отменить бронирование
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-calendar-times text-danger" style="font-size: 3rem;"></i>
                </div>
                <p class="mb-3">Вы уверены, что хотите отменить это бронирование?</p>
                <div class="card bg-light mb-3">
                    <div class="card-body py-2">
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Номер:</strong></p>
                                <p class="text-muted" id="modalRoomName"></p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1"><strong>Даты:</strong></p>
                                <p class="text-muted" id="modalDates"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="small text-muted mb-0">Примечание: Отмена бронирования может повлечь за собой штраф в соответствии с нашей политикой отмены.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Отмена
                </button>
                <form id="globalCancelForm" action="" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check me-1"></i> Подтвердить отмену
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        @if($bookings->count() > 0)
            <div class="row">
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Статусы</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-warning text-dark me-2">Ожидает</span>
                                <span class="small">Бронирование на рассмотрении</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-success me-2">Подтверждено</span>
                                <span class="small">Готово к заселению</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-danger me-2">Отменено</span>
                                <span class="small">Бронирование отменено</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-2">Завершено</span>
                                <span class="small">Пребывание завершено</span>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-search me-2"></i>Поиск номера</h5>
                        </div>
                        <div class="card-body">
                            <p class="small mb-3">Хотите забронировать еще один номер? Воспользуйтесь нашей системой поиска!</p>
                            <a href="{{ route('rooms.index') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-bed me-2"></i>Найти номер
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Ваши бронирования</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Номер</th>
                                            <th>Даты</th>
                                            <th>Гости</th>
                                            <th>Итого</th>
                                            <th>Статус</th>
                                            <th>Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            <tr class="{{ $booking->status == 'confirmed' ? 'table-success bg-opacity-25' : '' }}
                                                      {{ $booking->status == 'cancelled' ? 'table-danger bg-opacity-25' : '' }}
                                                      {{ $booking->status == 'pending' ? 'table-warning bg-opacity-25' : '' }}
                                                      {{ $booking->status == 'completed' ? 'table-secondary bg-opacity-25' : '' }}">
                                                <td><strong>#{{ $booking->id }}</strong></td>
                                                <td>
                                                    {{ $booking->room->roomType->name }}<br>
                                                    <small class="text-muted">Номер #{{ $booking->room->room_number }}</small>
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar-alt me-1 text-primary"></i> {{ $booking->check_in_date->format('d.m.Y') }} - {{ $booking->check_out_date->format('d.m.Y') }}<br>
                                                    <small class="text-muted">{{ $booking->getDurationInDays() }} ночь(ей)</small>
                                                </td>
                                                <td><i class="fas fa-users me-1 text-primary"></i> {{ $booking->guests }}</td>
                                                <td><strong>{{ \App\Helpers\CurrencyHelper::convertAndFormat($booking->total_price) }}</strong></td>
                                                <td>
                                                    @if($booking->status == 'pending')
                                                        <span class="badge bg-warning text-dark">Ожидает</span>
                                                    @elseif($booking->status == 'confirmed')
                                                        <span class="badge bg-success">Подтверждено</span>
                                                    @elseif($booking->status == 'cancelled')
                                                        <span class="badge bg-danger">Отменено</span>
                                                    @elseif($booking->status == 'completed')
                                                        <span class="badge bg-secondary">Завершено</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        @if($booking->status == 'pending' || $booking->status == 'confirmed')
                                                            @if($booking->check_in_date->isFuture())
                                                                <a href="#" class="btn btn-sm btn-outline-danger cancel-booking-btn" data-booking-id="{{ $booking->id }}">
                                                                    <i class="fas fa-times"></i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
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
        @else
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="py-4">
                        <i class="fas fa-calendar-alt text-primary" style="font-size: 5rem;"></i>
                        <h3 class="mt-4">У вас пока нет бронирований</h3>
                        <p class="text-muted mb-4">Откройте для себя наши роскошные номера и забронируйте идеальное место для отдыха</p>
                        <a href="{{ route('rooms.index') }}" class="btn btn-primary">
                            <i class="fas fa-bed me-2"></i>Посмотреть доступные номера
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Инициализация глобального модального окна для отмены бронирования
        const modalElement = document.getElementById('globalCancelModal');
        if (!modalElement) return;

        const cancelModal = new bootstrap.Modal(modalElement);

        // Обработка клика по кнопке отмены
        const cancelButtons = document.querySelectorAll('.cancel-booking-btn');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Останавливаем всплытие события, чтобы не активировать строку

                // Получаем данные о бронировании
                const bookingId = this.getAttribute('data-booking-id');
                const row = this.closest('tr');
                const roomName = row.querySelector('td:nth-child(2)').innerText.split('\n')[0].trim();
                const dates = row.querySelector('td:nth-child(3)').innerText.split('\n')[0].trim();

                // Заполняем модальное окно данными
                document.getElementById('modalRoomName').textContent = roomName;
                document.getElementById('modalDates').textContent = dates;
                document.getElementById('globalCancelForm').action = '/bookings/' + bookingId + '/cancel';

                // Открываем модальное окно
                cancelModal.show();
            });
        });

        // Блокировка кнопки отправки при подтверждении отмены
        const cancelForm = document.getElementById('globalCancelForm');
        if (cancelForm) {
            cancelForm.addEventListener('submit', function() {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Обработка...';
            });
        }

        // Делаем строки таблицы кликабельными
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const viewButton = row.querySelector('a.btn-outline-primary');
            if (viewButton) {
                row.style.cursor = 'pointer';

                row.addEventListener('click', function(e) {
                    // Игнорируем клик, если он был на кнопке или другом интерактивном элементе
                    if (!e.target.closest('.btn')) {
                        window.location.href = viewButton.getAttribute('href');
                    }
                });
            }
        });
    });
</script>
@endsection
