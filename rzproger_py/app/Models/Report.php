<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'data',
        'generated_by',
        'period_start',
        'period_end',
        'status'
    ];

    protected $casts = [
        'data' => 'array',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Типы отчетов
    const TYPE_BOOKINGS = 'bookings';
    const TYPE_REVENUE = 'revenue';
    const TYPE_ROOMS = 'rooms';
    const TYPE_USERS = 'users';
    const TYPE_OCCUPANCY = 'occupancy';

    // Статусы отчетов
    const STATUS_GENERATING = 'generating';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';

    public static function getTypes()
    {
        return [
            self::TYPE_BOOKINGS => 'Отчет по бронированиям',
            self::TYPE_REVENUE => 'Отчет по доходам',
            self::TYPE_ROOMS => 'Отчет по номерам',
            self::TYPE_USERS => 'Отчет по пользователям',
            self::TYPE_OCCUPANCY => 'Отчет по заполняемости'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function getFormattedPeriodAttribute()
    {
        if ($this->period_start && $this->period_end) {
            return $this->period_start->format('d.m.Y') . ' - ' . $this->period_end->format('d.m.Y');
        }
        return 'Весь период';
    }

    public function getTypeLabelAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_GENERATING => 'Генерируется',
            self::STATUS_COMPLETED => 'Готов',
            self::STATUS_FAILED => 'Ошибка'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_GENERATING => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED => 'danger'
        ];
        return $colors[$this->status] ?? 'secondary';
    }
}
