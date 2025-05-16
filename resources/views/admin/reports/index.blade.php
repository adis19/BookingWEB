@extends('layouts.admin')

@section('title', 'Отчеты')

@section('actions')
<div class="btn-group">
    <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle"></i> Создать отчет
    </a>
    <a href="{{ route('admin.reports.dashboard') }}" class="btn btn-outline-primary">
        <i class="fas fa-chart-line"></i> Панель аналитики
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Тип отчета</th>
                        <th>Период</th>
                        <th>Завершенные бронирования</th>
                        <th>Общий доход</th>
                        <th>Создан</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>
                                @if($report->report_type == 'daily')
                                    <span class="badge bg-info">Дневной</span>
                                @elseif($report->report_type == 'monthly')
                                    <span class="badge bg-primary">Месячный</span>
                                @else
                                    <span class="badge bg-secondary">Произвольный</span>
                                @endif
                            </td>
                            <td>
                                {{ $report->start_date->format('d.m.Y') }} - {{ $report->end_date->format('d.m.Y') }}
                            </td>
                            <td>{{ $report->completed_bookings }}</td>
                            <td>{{ \App\Helpers\CurrencyHelper::formatKgs($report->total_revenue) }}</td>
                            <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Отчетов не найдено</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $reports->links() }}
    </div>
</div>
@endsection
