<!-- Основная статистика доходов -->
<div class="row mb-4">
    <div class="col-lg-6 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-ruble-sign fs-1 mb-2"></i>
                <h3 class="mb-1">{{ number_format($data['total_revenue'], 0, ',', ' ') }} сом</h3>
                <p class="mb-0">Общий доход</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <i class="fas fa-chart-line fs-1 mb-2"></i>
                <h3 class="mb-1">{{ number_format($data['average_booking_value'], 0, ',', ' ') }} сом</h3>
                <p class="mb-0">Средний чек</p>
            </div>
        </div>
    </div>
</div>

<!-- Доходы по типам номеров -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Доходы по типам номеров
                </h5>
            </div>
            <div class="card-body">
                @if(isset($data['revenue_by_room_type']) && count($data['revenue_by_room_type']) > 0)
                    @foreach($data['revenue_by_room_type'] as $roomType)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $roomType['type_name'] }}</span>
                            <div>
                                <span class="badge bg-success">{{ number_format($roomType['revenue'], 0, ',', ' ') }} сом</span>
                            </div>
                        </div>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ ($roomType['revenue'] / $data['total_revenue']) * 100 }}%">
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Нет данных о доходах</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    Топ источники дохода
                </h5>
            </div>
            <div class="card-body">
                @if(isset($data['top_revenue_sources']) && count($data['top_revenue_sources']) > 0)
                    @foreach($data['top_revenue_sources'] as $index => $source)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                @if($index == 0)
                                    <i class="fas fa-medal text-warning fs-4"></i>
                                @elseif($index == 1)
                                    <i class="fas fa-medal text-secondary fs-4"></i>
                                @elseif($index == 2)
                                    <i class="fas fa-medal text-info fs-4"></i>
                                @else
                                    <span class="badge bg-primary">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $source['source'] }}</div>
                                <small class="text-muted">
                                    {{ number_format($source['revenue'], 0, ',', ' ') }} сом
                                    ({{ $source['bookings_count'] }} бронирований)
                                </small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Нет данных</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Доходы по месяцам -->
@if(isset($data['revenue_by_month']) && count($data['revenue_by_month']) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Динамика доходов по месяцам
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Месяц</th>
                                <th>Доход</th>
                                <th>Прогресс</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $maxRevenue = collect($data['revenue_by_month'])->max();
                            @endphp
                            @foreach($data['revenue_by_month'] as $month => $revenue)
                                <tr>
                                    <td>{{ date('m.Y', strtotime($month . '-01')) }}</td>
                                    <td class="fw-bold">{{ number_format($revenue, 0, ',', ' ') }} сом</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $maxRevenue > 0 ? ($revenue / $maxRevenue) * 100 : 0 }}%">
                                                <small>{{ round(($revenue / $maxRevenue) * 100, 1) }}%</small>
                                            </div>
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
@endif

<!-- Ежедневные доходы (если есть данные) -->
@if(isset($data['daily_revenue']) && count($data['daily_revenue']) > 0)
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-chart-line me-2"></i>
            Ежедневные доходы
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-sm table-hover">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>Дата</th>
                        <th class="text-end">Доход</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['daily_revenue'] as $date => $revenue)
                        <tr>
                            <td>{{ date('d.m.Y', strtotime($date)) }}</td>
                            <td class="text-end fw-bold">{{ number_format($revenue, 0, ',', ' ') }} сом</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
