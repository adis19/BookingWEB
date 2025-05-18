<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateStorageLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update storage links and copy images to public directory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Updating storage links...');

        // Создаем директорию public/storage, если она не существует
        if (!File::exists(public_path('storage'))) {
            File::makeDirectory(public_path('storage'), 0755, true);
        }

        // Создаем директорию public/storage/room-types, если она не существует
        if (!File::exists(public_path('storage/room-types'))) {
            File::makeDirectory(public_path('storage/room-types'), 0755, true);
        }

        // Копируем все изображения из storage/app/public/room-types в public/storage/room-types
        $sourceDir = storage_path('app/public/room-types');
        $destDir = public_path('storage/room-types');

        if (File::exists($sourceDir)) {
            $files = File::files($sourceDir);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                $destPath = $destDir . '/' . $filename;
                
                if (!File::exists($destPath) || File::lastModified($file) > File::lastModified($destPath)) {
                    File::copy($file, $destPath);
                    $this->info("Copied: {$filename}");
                }
            }
        }

        $this->info('Storage links updated successfully!');
        return 0;
    }
} 