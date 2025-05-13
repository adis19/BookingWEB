<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price'
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
