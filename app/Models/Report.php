<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'start_date',
        'end_date',
        'total_bookings',
        'completed_bookings',
        'cancelled_bookings',
        'total_revenue',
        'average_booking_value',
        'most_booked_room_type_id',
        'room_revenue',
        'services_revenue',
        'generated_by',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_revenue' => 'decimal:2',
        'average_booking_value' => 'decimal:2',
        'room_revenue' => 'decimal:2',
        'services_revenue' => 'decimal:2',
    ];

    /**
     * Связь с типом номера, который был наиболее популярен в этом отчете
     */
    public function mostBookedRoomType()
    {
        return $this->belongsTo(RoomType::class, 'most_booked_room_type_id');
    }
}
