<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\ExtraService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $roomTypes = RoomType::all();
        $extraServices = ExtraService::all();
        
        return view('rooms.index', compact('roomTypes', 'extraServices'));
    }
    
    public function show(RoomType $roomType)
    {
        $extraServices = ExtraService::all();
        
        return view('rooms.show', compact('roomType', 'extraServices'));
    }
    
    public function search(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1'
        ]);
        
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $guests = $request->guests;
        
        // Get room types that can accommodate the guests
        $roomTypes = RoomType::where('max_occupancy', '>=', $guests)->get();
        
        // Filter to only include room types with available rooms in the date range
        $availableRoomTypes = $roomTypes->filter(function ($roomType) use ($checkIn, $checkOut) {
            foreach ($roomType->rooms as $room) {
                if ($room->isAvailableBetween($checkIn, $checkOut)) {
                    return true;
                }
            }
            return false;
        });
        
        $extraServices = ExtraService::all();
        
        return view('rooms.search', compact('availableRoomTypes', 'extraServices', 'checkIn', 'checkOut', 'guests'));
    }
}
