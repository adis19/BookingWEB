@extends('layouts.admin')

@section('title', $report->title)

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Заголовок отчета -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">{{ $report->title }}</h1>
                <div class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $report->formatted_period }} |
                    <i class="fas fa-user me-1"></i>
                    {{ $report->user->name }} |
                    <i class="fas fa-clock me-1"></i>
                    {{ $report->created_at->format('d.m.Y H:i') }}
                </div>
            </div>
            <div>
                <a href="{{ route('admin.reports.export', $report) }}" class="btn btn-success me-2">
                    <i class="fas fa-download me-1"></i>
                    Экспорт CSV
                </a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Назад к списку
                </a>
            </div>
        </div>

        @if($report->status !== 'completed')
            <div class="alert alert-{{ $report->status_color }}">
                <i class="fas fa-info-circle me-2"></i>
                Статус отчета: {{ $report->status_label }}
            </div>
        @else
            <!-- Содержимое отчета -->
            @switch($report->type)
                @case('bookings')
                    @include('admin.reports.partials.bookings', ['data' => $report->data])
                    @break
                @case('revenue')
                    @include('admin.reports.partials.revenue', ['data' => $report->data])
                    @break
                @case('rooms')
                    @include('admin.reports.partials.rooms', ['data' => $report->data])
                    @break
                @case('users')
                    @include('admin.reports.partials.users', ['data' => $report->data])
                    @break
                @case('occupancy')
                    @include('admin.reports.partials.occupancy', ['data' => $report->data])
                    @break
                @default
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Неизвестный тип отчета
                    </div>
            @endswitch
        @endif
    </div>
</div>
@endsection
