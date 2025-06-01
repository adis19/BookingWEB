@extends('layouts.admin')

@section('title', 'Дашборд аналитики')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Дашборд аналитики
                </h1>
                <p class="text-muted mb-0">Ключевые метрики и показатели в реальном времени</p>
            </div>
            <div>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-chart-bar me-1"></i>
                    Все отчеты
                </a>
                <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Создать отчет
                </a>
            </div>
        </div>

        <!-- Основные метрики -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card stat-card-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="card-title">Бронирования (месяц)</div>
                                <div class="card-value">{{ $stats['current_month_bookings'] }}</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card stat-card-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="card-title">Доход (месяц)</div>
                                <div class="card-value small-text">{{ number_format($stats['current_month_revenue'], 0, ',', ' ') }} сом</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card stat-card-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="card-title">Заполняемость</div>
                                <div class="card-value">{{ round($stats['current_occupancy'], 1) }}%</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card stat-card stat-card-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="card-title">Всего пользователей</div>
                                <div class="card-value">{{ $stats['total_users'] }}</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Быстрая аналитика -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Быстрая аналитика
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-outline-primary w-100 h-100 quick-analytics" data-type="bookings" data-period="week">
                                    <div class="py-3">
                                        <i class="fas fa-calendar-week fs-1 d-block mb-2"></i>
                                        <strong>Бронирования</strong><br>
                                        <small>за неделю</small>
                                    </div>
                                </button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-outline-success w-100 h-100 quick-analytics" data-type="revenue" data-period="week">
                                    <div class="py-3">
                                        <i class="fas fa-chart-bar fs-1 d-block mb-2"></i>
                                        <strong>Доходы</strong><br>
                                        <small>за неделю</small>
                                    </div>
                                </button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-outline-info w-100 h-100 quick-analytics" data-type="occupancy" data-period="month">
                                    <div class="py-3">
                                        <i class="fas fa-chart-area fs-1 d-block mb-2"></i>
                                        <strong>Заполняемость</strong><br>
                                        <small>за месяц</small>
                                    </div>
                                </button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-outline-warning w-100 h-100 quick-analytics" data-type="users" data-period="quarter">
                                    <div class="py-3">
                                        <i class="fas fa-user-friends fs-1 d-block mb-2"></i>
                                        <strong>Пользователи</strong><br>
                                        <small>за квартал</small>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Быстрые действия
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Создать детальный отчет
                            </a>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-list me-2"></i>
                                Все сохраненные отчеты
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-info">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Главная админ-панель
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Информационная карточка -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            О системе отчетов
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><i class="fas fa-check text-success me-2"></i> Автоматическая генерация</li>
                            <li><i class="fas fa-check text-success me-2"></i> Экспорт в CSV</li>
                            <li><i class="fas fa-check text-success me-2"></i> Фильтрация по периодам</li>
                            <li><i class="fas fa-check text-success me-2"></i> Визуализация данных</li>
                            <li><i class="fas fa-check text-success me-2"></i> Сохранение отчетов</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для быстрой аналитики -->
<div class="modal fade" id="quickAnalyticsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Быстрая аналитика</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="quickAnalyticsContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="mt-2">Генерируется аналитика...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Создать детальный отчет
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Быстрая аналитика
    document.querySelectorAll('.quick-analytics').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const period = this.dataset.period;

            // Показываем модальное окно
            const modal = new bootstrap.Modal(document.getElementById('quickAnalyticsModal'));
            modal.show();

            // Сбрасываем содержимое
            document.getElementById('quickAnalyticsContent').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="mt-2">Генерируется аналитика...</p>
                </div>
            `;

            // Отправляем AJAX запрос
            fetch('{{ route("admin.reports.quick") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    period: period
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayQuickAnalytics(data.data, type, period);
                } else {
                    document.getElementById('quickAnalyticsContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Ошибка: ${data.error}
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('quickAnalyticsContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Произошла ошибка при генерации аналитики
                    </div>
                `;
            });
        });
    });
});

function displayQuickAnalytics(data, type, period) {
    const periodLabels = {
        'week': 'неделю',
        'month': 'месяц',
        'quarter': 'квартал',
        'year': 'год'
    };

    let content = `<div class="text-center mb-3">
        <h5 class="text-primary">Аналитика за ${periodLabels[period]}</h5>
    </div>`;

    switch(type) {
        case 'bookings':
            content += `
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body text-center">
                                <h3>${data.total_bookings}</h3>
                                <p class="mb-0">Всего бронирований</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body text-center">
                                <h3>${data.confirmed_bookings}</h3>
                                <p class="mb-0">Подтвержденных</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white mb-3">
                            <div class="card-body text-center">
                                <h3>${data.cancelled_bookings}</h3>
                                <p class="mb-0">Отмененных</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Средняя длительность:</strong> ${Math.round(data.average_stay_duration * 10) / 10} дней
                </div>
            `;
            break;
        case 'revenue':
            content += `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body text-center">
                                <h3>${new Intl.NumberFormat('ru-RU').format(data.total_revenue)} сом</h3>
                                <p class="mb-0">Общий доход</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body text-center">
                                <h3>${new Intl.NumberFormat('ru-RU').format(Math.round(data.average_booking_value))} сом</h3>
                                <p class="mb-0">Средний чек</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'occupancy':
            content += `
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card bg-info text-white mb-3">
                            <div class="card-body text-center">
                                <h2 class="display-4">${Math.round(data.average_occupancy * 10) / 10}%</h2>
                                <p class="mb-0">Средняя заполняемость</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <strong>Максимум:</strong> ${Math.round(data.max_occupancy * 10) / 10}%
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-warning">
                            <strong>Минимум:</strong> ${Math.round(data.min_occupancy * 10) / 10}%
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'users':
            content += `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body text-center">
                                <h3>${data.total_users}</h3>
                                <p class="mb-0">Всего пользователей</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success text-white mb-3">
                            <div class="card-body text-center">
                                <h3>${data.new_users}</h3>
                                <p class="mb-0">Новых пользователей</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-users me-2"></i>
                    <strong>Активных пользователей:</strong> ${data.users_with_bookings} (${Math.round((data.users_with_bookings / data.total_users) * 100)}%)
                </div>
            `;
            break;
    }

    document.getElementById('quickAnalyticsContent').innerHTML = content;
}
</script>
@endsection
