@extends('layouts.admin')

@section('title', 'Создать отчет')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Создать новый отчет
                </h4>
            </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.reports.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-1"></i>
                                Название отчета *
                            </label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title') }}"
                                   placeholder="Например: Отчет по бронированиям за январь 2024" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="form-label">
                                <i class="fas fa-chart-bar me-1"></i>
                                Тип отчета *
                            </label>
                            <select class="form-select @error('type') is-invalid @enderror"
                                    id="type" name="type" required>
                                <option value="">Выберите тип отчета</option>
                                @foreach($reportTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Описания типов отчетов -->
                            <div class="mt-3">
                                <div class="report-description" data-type="bookings" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Отчет по бронированиям</strong> - статистика по всем бронированиям: количество, статусы, средняя длительность пребывания, распределение по типам номеров.
                                    </div>
                                </div>
                                <div class="report-description" data-type="revenue" style="display: none;">
                                    <div class="alert alert-success">
                                        <i class="fas fa-dollar-sign me-2"></i>
                                        <strong>Отчет по доходам</strong> - финансовая аналитика: общий доход, средний чек, доходы по типам номеров, динамика по периодам.
                                    </div>
                                </div>
                                <div class="report-description" data-type="rooms" style="display: none;">
                                    <div class="alert alert-warning">
                                        <i class="fas fa-bed me-2"></i>
                                        <strong>Отчет по номерам</strong> - статистика номерного фонда: общее количество, распределение по типам, статусы номеров.
                                    </div>
                                </div>
                                <div class="report-description" data-type="users" style="display: none;">
                                    <div class="alert alert-primary">
                                        <i class="fas fa-users me-2"></i>
                                        <strong>Отчет по пользователям</strong> - аналитика клиентской базы: общее количество, новые регистрации, активность, топ клиенты.
                                    </div>
                                </div>
                                <div class="report-description" data-type="occupancy" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-chart-line me-2"></i>
                                        <strong>Отчет по заполняемости</strong> - аналитика загрузки: процент заполняемости, динамика по дням, загрузка по типам номеров.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="period_start" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Начало периода
                                </label>
                                <input type="date" class="form-control @error('period_start') is-invalid @enderror"
                                       id="period_start" name="period_start" value="{{ old('period_start') }}">
                                @error('period_start')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="period_end" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Конец периода
                                </label>
                                <input type="date" class="form-control @error('period_end') is-invalid @enderror"
                                       id="period_end" name="period_end" value="{{ old('period_end') }}">
                                @error('period_end')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-light">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Подсказка:</strong> Если период не указан, отчет будет сгенерирован за весь доступный период данных.
                        </div>

                        <!-- Быстрые периоды -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-clock me-1"></i>
                                Быстрый выбор периода
                            </label>
                            <div class="btn-group d-flex" role="group">
                                <button type="button" class="btn btn-outline-secondary quick-period" data-period="week">
                                    Неделя
                                </button>
                                <button type="button" class="btn btn-outline-secondary quick-period" data-period="month">
                                    Месяц
                                </button>
                                <button type="button" class="btn btn-outline-secondary quick-period" data-period="quarter">
                                    Квартал
                                </button>
                                <button type="button" class="btn btn-outline-secondary quick-period" data-period="year">
                                    Год
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Назад
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-chart-line me-1"></i>
                                Сгенерировать отчет
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const descriptions = document.querySelectorAll('.report-description');

    // Показ описания типа отчета
    typeSelect.addEventListener('change', function() {
        descriptions.forEach(desc => desc.style.display = 'none');

        if (this.value) {
            const targetDesc = document.querySelector(`[data-type="${this.value}"]`);
            if (targetDesc) {
                targetDesc.style.display = 'block';
            }
        }
    });

    // Быстрые периоды
    document.querySelectorAll('.quick-period').forEach(button => {
        button.addEventListener('click', function() {
            const period = this.dataset.period;
            const now = new Date();
            let startDate, endDate;

            // Убираем активность с других кнопок
            document.querySelectorAll('.quick-period').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            switch(period) {
                case 'week':
                    startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7);
                    endDate = now;
                    break;
                case 'month':
                    startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                    endDate = now;
                    break;
                case 'quarter':
                    const quarterStart = Math.floor(now.getMonth() / 3) * 3;
                    startDate = new Date(now.getFullYear(), quarterStart, 1);
                    endDate = now;
                    break;
                case 'year':
                    startDate = new Date(now.getFullYear(), 0, 1);
                    endDate = now;
                    break;
            }

            // Форматируем даты
            document.getElementById('period_start').value = startDate.toISOString().split('T')[0];
            document.getElementById('period_end').value = endDate.toISOString().split('T')[0];
        });
    });

    // Автоматическое заполнение названия отчета
    typeSelect.addEventListener('change', function() {
        const titleInput = document.getElementById('title');
        if (!titleInput.value && this.value) {
            const reportName = this.options[this.selectedIndex].text;
            const currentDate = new Date().toLocaleDateString('ru-RU', {
                year: 'numeric',
                month: 'long'
            });
            titleInput.value = `${reportName} за ${currentDate}`;
        }
    });
});
</script>
@endsection
