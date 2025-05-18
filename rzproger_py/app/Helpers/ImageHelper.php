<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Получает правильный URL для изображения
     *
     * @param string|null $imagePath Путь к изображению
     * @param string $defaultImage URL изображения по умолчанию
     * @return string URL изображения
     */
    public static function getImageUrl($imagePath, $defaultImage = 'https://via.placeholder.com/800x500?text=Room+Image')
    {
        if (empty($imagePath)) {
            return $defaultImage;
        }
        
        // Если путь начинается с /storage/
        if (strpos($imagePath, '/storage/') === 0) {
            $filename = basename($imagePath);
            
            // Проверяем, существует ли файл в public/storage
            if (file_exists(public_path('storage/room-types/' . $filename))) {
                return asset('storage/room-types/' . $filename);
            }
            
            // Проверяем, существует ли файл в storage/app/public
            if (file_exists(storage_path('app/public/room-types/' . $filename))) {
                // Копируем файл в public/storage для обеспечения доступа
                $sourceFile = storage_path('app/public/room-types/' . $filename);
                $destFile = public_path('storage/room-types/' . $filename);
                
                if (!file_exists(dirname($destFile))) {
                    mkdir(dirname($destFile), 0755, true);
                }
                
                copy($sourceFile, $destFile);
                return asset('storage/room-types/' . $filename);
            }
        }
        
        // Если файл не найден, возвращаем изображение по умолчанию
        return $defaultImage;
    }
} 