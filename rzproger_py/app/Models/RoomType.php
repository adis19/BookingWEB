<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_night',
        'max_occupancy',
        'image',
        'amenities'
    ];

    protected $casts = [
        'amenities' => 'array',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Получает URL изображения
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return \App\Helpers\ImageHelper::getImageUrl($this->image);
    }

    /**
     * Получает значение атрибута image
     *
     * @param string|null $value
     * @return string|null
     */
    public function getImageAttribute($value)
    {
        if ($value) {
            // Если путь начинается с /storage/
            if (strpos($value, '/storage/') === 0) {
                $filename = basename($value);
                
                // Проверяем, существует ли файл в public/storage
                if (file_exists(public_path('storage/room-types/' . $filename))) {
                    return '/storage/room-types/' . $filename;
                }
            }
        }
        
        return $value;
    }
}
