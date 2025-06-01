<!-- Основная статистика -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-calendar-check fs-1 mb-2"></i>
                <h3 class="mb-1">{{ $data['total_bookings'] }}</h3>
                <p class="mb-0">Всего бронирований</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fs-1 mb-2"></i>
                <h3 class="mb-1">{{ $data['confirmed_bookings'] }}</h3>
                <p class="mb-0">Подтвержденных</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-clock fs-1 mb-2"></i>
                <h3 class="mb-1">{{ $data['pending_bookings'] }}</h3>
                <p class="mb-0">В ожидании</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <i class="fas fa-times-circle fs-1 mb-2"></i>
                <h3 class="mb-1">{{ $data['cancelled_bookings'] }}</h3>
                <p class="mb-0">Отмененных</p>
            </div>
        </div>
    </div>
</div>

<!-- Дополнительная статистика -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Бронирования по типам номеров
                </h5>
            </div>
            <div class="card-body">
                @if(isset($data['bookings_by_room_type']) && count($data['bookings_by_room_type']) > 0)
                    @foreach($data['bookings_by_room_type'] as $roomType)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $roomType['type_name'] }}</span>
                            <div>
                                <span class="badge bg-primary">{{ $roomType['count'] }}</span>
                            </div>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ ($roomType['count'] / $data['total_bookings']) * 100 }}%">
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Нет данных</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Бронирования по месяцам
                </h5>
            </div>
            <div class="card-body">
                @if(isset($data['bookings_by_month']) && count($data['bookings_by_month']) > 0)
                    @foreach($data['bookings_by_month'] as $month => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ date('m.Y', strtotime($month . '-01')) }}</span>
                            <span class="badge bg-info">{{ $count }}</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Нет данных</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Средняя длительность пребывания -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Дополнительная информация
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h4 class="text-primary">{{ round($data['average_stay_duration'], 1) }}</h4>
                        <p class="text-muted">Средняя длительность пребывания (дней)</p>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-success">{{ round(($data['confirmed_bookings'] / max($data['total_bookings'], 1)) * 100, 1) }}%</h4>
                        <p class="text-muted">Процент подтвержденных бронирований</p>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-warning">{{ round(($data['cancelled_bookings'] / max($data['total_bookings'], 1)) * 100, 1) }}%</h4>
                        <p class="text-muted">Процент отмененных бронирований</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Таблица с деталями бронирований -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            Детали бронирований
        </h5>
    </div>
    <div class="card-body">
        @if(isset($data['bookings_list']) && count($data['bookings_list']) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Гость</th>
                            <th>Номер</th>
                            <th>Тип номера</th>
                            <th>Заезд</th>
                            <th>Выезд</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['bookings_list'] as $booking)
                            <tr>
                                <td><strong>#{{ $booking['id'] }}</strong></td>
                                <td>{{ $booking['user_name'] }}</td>
                                <td>{{ $booking['room_number'] }}</td>
                                <td>{{ $booking['room_type'] }}</td>
                                <td>{{ $booking['check_in'] }}</td>
                                <td>{{ $booking['check_out'] }}</td>
                                <td>{{ number_format($booking['total_amount'], 0, ',', ' ') }} сом</td>
                                <td>
                                    @switch($booking['status'])
                                        @case('confirmed')
                                            <span class="badge bg-success">Подтвержден</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">В ожидании</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">Отменен</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $booking['status'] }}</span>
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-calendar-times text-muted fs-1 mb-3"></i>
                <h5 class="text-muted">Бронирования не найдены</h5>
                <p class="text-muted">За указанный период бронирования отсутствуют</p>
            </div>
        @endif
    </div>
</div>
