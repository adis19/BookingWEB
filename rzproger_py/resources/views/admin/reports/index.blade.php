@extends('layouts.admin')

@section('title', 'Отчеты')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Отчеты и аналитика
                </h1>
                <p class="text-muted mb-0">Управление отчетами и аналитика данных</p>
            </div>
            <div>
                <a href="{{ route('admin.analytics.dashboard') }}" class="btn btn-info me-2">
                    <i class="fas fa-tachometer-alt me-1"></i>
                    Дашборд
                </a>
                <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>
                    Создать отчет
                </a>
            </div>
        </div>

            <!-- Быстрые отчеты -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt me-2"></i>
                                Быстрые отчеты
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-primary w-100 quick-report" data-type="bookings" data-period="month">
                                        <i class="fas fa-calendar-check d-block fs-2 mb-2"></i>
                                        Бронирования за месяц
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-success w-100 quick-report" data-type="revenue" data-period="month">
                                        <i class="fas fa-dollar-sign d-block fs-2 mb-2"></i>
                                        Доходы за месяц
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-info w-100 quick-report" data-type="occupancy" data-period="month">
                                        <i class="fas fa-bed d-block fs-2 mb-2"></i>
                                        Заполняемость
                                    </button>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <button class="btn btn-outline-warning w-100 quick-report" data-type="users" data-period="month">
                                        <i class="fas fa-users d-block fs-2 mb-2"></i>
                                        Пользователи
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Сохраненные отчеты -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Сохраненные отчеты
                    </h5>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Название</th>
                                        <th>Тип</th>
                                        <th>Период</th>
                                        <th>Статус</th>
                                        <th>Автор</th>
                                        <th>Создан</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>
                                                <strong>{{ $report->title }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $report->type_label }}</span>
                                            </td>
                                            <td>{{ $report->formatted_period }}</td>
                                            <td>
                                                <span class="badge bg-{{ $report->status_color }}">
                                                    {{ $report->status_label }}
                                                </span>
                                            </td>
                                            <td>{{ $report->user->name }}</td>
                                            <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($report->status === 'completed')
                                                        <a href="{{ route('admin.reports.show', $report) }}"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.reports.export', $report) }}"
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.reports.destroy', $report) }}"
                                                          class="d-inline" onsubmit="return confirm('Удалить этот отчет?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Пагинация -->
                        <div class="mt-3">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt text-muted fs-1 mb-3"></i>
                            <h5 class="text-muted">Отчеты не найдены</h5>
                            <p class="text-muted">Создайте первый отчет, чтобы начать аналитику</p>
                            <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                Создать отчет
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для быстрого отчета -->
<div class="modal fade" id="quickReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Быстрый отчет</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="quickReportContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="mt-2">Генерируется отчет...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Быстрые отчеты
    document.querySelectorAll('.quick-report').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const period = this.dataset.period;

            // Показываем модальное окно
            const modal = new bootstrap.Modal(document.getElementById('quickReportModal'));
            modal.show();

            // Сбрасываем содержимое
            document.getElementById('quickReportContent').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="mt-2">Генерируется отчет...</p>
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
                    displayQuickReport(data.data, type);
                } else {
                    document.getElementById('quickReportContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Ошибка: ${data.error}
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('quickReportContent').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Произошла ошибка при генерации отчета
                    </div>
                `;
            });
        });
    });
});

function displayQuickReport(data, type) {
    let content = '';

    switch(type) {
        case 'bookings':
            content = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>${data.total_bookings}</h3>
                                <p>Всего бронирований</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>${data.confirmed_bookings}</h3>
                                <p>Подтвержденных</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Средняя длительность пребывания: ${Math.round(data.average_stay_duration * 10) / 10} дней</h6>
                </div>
            `;
            break;
        case 'revenue':
            content = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>${new Intl.NumberFormat('ru-RU').format(data.total_revenue)} сом</h3>
                                <p>Общий доход</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h3>${new Intl.NumberFormat('ru-RU').format(Math.round(data.average_booking_value))} сом</h3>
                                <p>Средний чек</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'occupancy':
            content = `
                <div class="text-center">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h2>${Math.round(data.average_occupancy * 10) / 10}%</h2>
                            <p>Средняя заполняемость</p>
                        </div>
                    </div>
                </div>
            `;
            break;
        case 'users':
            content = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>${data.total_users}</h3>
                                <p>Всего пользователей</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>${data.new_users}</h3>
                                <p>Новых за месяц</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
    }

    document.getElementById('quickReportContent').innerHTML = content;
}
</script>
@endsection
