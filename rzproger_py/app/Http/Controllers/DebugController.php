<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\ExtraService;
use Carbon\Carbon;

class DebugController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $roomTypes = RoomType::with('rooms')->get();
        $checkIn = Carbon::now()->addDay();
        $checkOut = Carbon::now()->addDays(3);
        
        $availableRooms = [];
        
        foreach ($roomTypes as $roomType) {
            $availableRoomsCount = 0;
            
            foreach ($roomType->rooms as $room) {
                if ($room->is_available && $room->isAvailableBetween($checkIn, $checkOut)) {
                    $availableRoomsCount++;
                    $availableRooms[] = [
                        'room_id' => $room->id,
                        'room_number' => $room->room_number,
                        'room_type' => $roomType->name,
                        'is_available' => $room->is_available ? 'Yes' : 'No'
                    ];
                }
            }
        }
        
        return response()->json([
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'available_rooms' => $availableRooms
        ]);
    }
    
    public function debugRequest(Request $request)
    {
        return view('debug', ['data' => $request->all()]);
    }
}
