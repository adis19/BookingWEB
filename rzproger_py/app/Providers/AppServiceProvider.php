<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Проверяем и создаем директорию для хранения изображений, если она не существует
        $roomTypesDir = public_path('storage/room-types');
        if (!file_exists($roomTypesDir)) {
            if (!file_exists(public_path('storage'))) {
                mkdir(public_path('storage'), 0755, true);
            }
            mkdir($roomTypesDir, 0755, true);
        }
        
        // Копируем все изображения из storage/app/public в public/storage
        $sourceDir = storage_path('app/public/room-types');
        if (file_exists($sourceDir)) {
            $files = scandir($sourceDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && is_file($sourceDir . '/' . $file)) {
                    if (!file_exists($roomTypesDir . '/' . $file)) {
                        copy($sourceDir . '/' . $file, $roomTypesDir . '/' . $file);
                    }
                }
            }
        }
    }
}
