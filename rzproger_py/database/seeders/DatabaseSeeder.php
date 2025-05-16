<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\ExtraService;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Regular User
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'phone' => '123-456-7890',
            'address' => '123 Test St, Test City, Test Country',
            'role' => 'user',
        ]);

        // Create Room Types
        $roomTypes = [

        ];

        foreach ($roomTypes as $roomTypeData) {
            $roomType = RoomType::create($roomTypeData);

            // Create Rooms for each Room Type
            for ($i = 1; $i <= 5; $i++) {
                Room::create([
                    'room_number' => $roomType->name[0] . str_pad($i, 2, '0', STR_PAD_LEFT), // S01, S02, D01, D02, etc.
                    'room_type_id' => $roomType->id,
                    'is_available' => true,
                ]);
            }
        }

        // Create Extra Services
        $extraServices = [
            [
                'name' => 'Breakfast Buffet',
                'description' => 'Enjoy a delicious breakfast buffet with a variety of hot and cold options.',
                'price' => 15.99,
            ],
            [
                'name' => 'Airport Transfer',
                'description' => 'Convenient transportation from/to the airport in a comfortable vehicle.',
                'price' => 49.99,
            ],
            [
                'name' => 'Late Check-out',
                'description' => 'Extend your check-out time until 3:00 PM (subject to availability).',
                'price' => 29.99,
            ],
            [
                'name' => 'Spa Treatment',
                'description' => 'Relaxing 60-minute massage or facial treatment at our on-site spa.',
                'price' => 79.99,
            ],
        ];

        foreach ($extraServices as $serviceData) {
            ExtraService::create($serviceData);
        }
    }
}
