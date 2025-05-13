<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'guests',
        'total_price',
        'status',
        'special_requests'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function extraServices()
    {
        return $this->belongsToMany(ExtraService::class, 'booking_extra_service')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function getExtraServicesTotal()
    {
        return $this->extraServices->sum(function ($extraService) {
            return $extraService->pivot->price * $extraService->pivot->quantity;
        });
    }

    public function getDurationInDays()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }
}
