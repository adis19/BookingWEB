@extends('layouts.admin')

@section('title', 'Создать отчет')

@section('actions')
<a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Назад к отчетам
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Параметры отчета</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.reports.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="report_type" class="form-label">Тип отчета</label>
                    <select class="form-select @error('report_type') is-invalid @enderror" id="report_type" name="report_type" required>
                        <option value="daily">Дневной</option>
                        <option value="monthly">Месячный</option>
                        <option value="custom">Произвольный</option>
                    </select>
                    @error('report_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Начальная дата</label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                    @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Конечная дата</label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}" required>
                    @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label for="notes" class="form-label">Примечания</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Создать отчет</button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Предустановленные периоды</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="setDateRange('today')">Сегодня</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="setDateRange('yesterday')">Вчера</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="setDateRange('thisWeek')">Текущая неделя</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="setDateRange('lastWeek')">Прошлая неделя</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="setDateRange('thisMonth')">Текущий месяц</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="setDateRange('lastMonth')">Прошлый месяц</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="setDateRange('thisYear')">Текущий год</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary w-100 mb-2" onclick="setDateRange('lastYear')">Прошлый год</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Изменение типа отчета
        document.getElementById('report_type').addEventListener('change', function() {
            const reportType = this.value;
            
            if (reportType === 'daily') {
                setDateRange('today');
            } else if (reportType === 'monthly') {
                setDateRange('thisMonth');
            }
        });
        
        // Установка минимальной конечной даты
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDateField = document.getElementById('end_date');
            
            if (new Date(endDateField.value) < startDate) {
                endDateField.value = this.value;
            }
            
            endDateField.min = this.value;
        });
    });
    
    function setDateRange(range) {
        const startDateField = document.getElementById('start_date');
        const endDateField = document.getElementById('end_date');
        const reportTypeField = document.getElementById('report_type');
        
        const today = new Date();
        let startDate = new Date();
        let endDate = new Date();
        
        switch(range) {
            case 'today':
                reportTypeField.value = 'daily';
                break;
                
            case 'yesterday':
                startDate.setDate(today.getDate() - 1);
                endDate.setDate(today.getDate() - 1);
                reportTypeField.value = 'daily';
                break;
                
            case 'thisWeek':
                startDate.setDate(today.getDate() - today.getDay());
                reportTypeField.value = 'custom';
                break;
                
            case 'lastWeek':
                startDate.setDate(today.getDate() - today.getDay() - 7);
                endDate.setDate(today.getDate() - today.getDay() - 1);
                reportTypeField.value = 'custom';
                break;
                
            case 'thisMonth':
                startDate.setDate(1);
                reportTypeField.value = 'monthly';
                break;
                
            case 'lastMonth':
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                reportTypeField.value = 'monthly';
                break;
                
            case 'thisYear':
                startDate = new Date(today.getFullYear(), 0, 1);
                reportTypeField.value = 'custom';
                break;
                
            case 'lastYear':
                startDate = new Date(today.getFullYear() - 1, 0, 1);
                endDate = new Date(today.getFullYear() - 1, 11, 31);
                reportTypeField.value = 'custom';
                break;
        }
        
        // Форматируем даты в формат YYYY-MM-DD
        startDateField.value = startDate.toISOString().split('T')[0];
        endDateField.value = endDate.toISOString().split('T')[0];
    }
</script>
@endsection
