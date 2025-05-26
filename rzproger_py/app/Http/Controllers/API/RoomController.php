<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Получить список всех типов комнат
     */
    public function index()
    {
        $roomTypes = RoomType::all();
        
        return response()->json([
            'data' => $roomTypes
        ]);
    }

    /**
     * Получить детали конкретного типа комнаты
     */
    public function show(RoomType $roomType)
    {
        return response()->json([
            'data' => $roomType
        ]);
    }

    /**
     * Поиск доступных комнат
     */
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
        
        // Получаем типы комнат, которые могут вместить указанное количество гостей
        $roomTypes = RoomType::where('max_occupancy', '>=', $guests)->get();
        
        // Фильтруем, чтобы включить только типы комнат с доступными комнатами в указанном диапазоне дат
        $availableRoomTypes = $roomTypes->filter(function ($roomType) use ($checkIn, $checkOut) {
            foreach ($roomType->rooms as $room) {
                if ($room->is_available && $room->isAvailableBetween($checkIn, $checkOut)) {
                    return true;
                }
            }
            return false;
        })->values(); // Переиндексируем массив
        
        return response()->json([
            'data' => $availableRoomTypes,
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'guests' => $guests
        ]);
    }
} 