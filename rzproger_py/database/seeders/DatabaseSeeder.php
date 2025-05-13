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
            [
                'name' => 'Standard Room',
                'description' => 'Our Standard Room offers comfort and convenience with one queen-sized bed, a work desk, and a private bathroom. Perfect for solo travelers or couples seeking a cozy stay.',
                'price_per_night' => 89.99,
                'max_occupancy' => 2,
                'amenities' => ['Free WiFi', 'TV', 'Air Conditioning', 'Desk', 'Private Bathroom'],
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'Experience enhanced comfort in our Deluxe Room featuring a king-sized bed, additional seating area, and a spacious bathroom with premium amenities. Ideal for those who desire extra space and amenities.',
                'price_per_night' => 149.99,
                'max_occupancy' => 3,
                'amenities' => ['Free WiFi', 'TV', 'Air Conditioning', 'Mini Bar', 'Coffee Maker', 'Desk', 'Seating Area', 'Premium Bathroom Amenities'],
            ],
            [
                'name' => 'Executive Suite',
                'description' => 'Our luxurious Executive Suite offers separate living and sleeping areas, a king-sized bed, premium furnishings, and a lavish bathroom with a bathtub and shower. Perfect for extended stays or those seeking a truly luxurious experience.',
                'price_per_night' => 249.99,
                'max_occupancy' => 4,
                'amenities' => ['Free WiFi', 'Smart TV', 'Air Conditioning', 'Mini Bar', 'Espresso Machine', 'Desk', 'Lounge Area', 'Premium Bathroom Amenities', 'Bathrobe & Slippers', 'Turndown Service'],
            ],
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
