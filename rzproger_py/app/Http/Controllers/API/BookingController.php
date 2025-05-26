<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\ExtraService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Получить список бронирований пользователя
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
                    ->with(['room.roomType', 'extraServices'])
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        return response()->json([
            'data' => $bookings
        ]);
    }

    /**
     * Создание бронирования
     */
    public function create(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1'
        ]);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $roomTypeId = $request->room_type_id;
        $guests = $request->guests;

        // Найти доступную комнату этого типа
        $availableRoom = Room::whereHas('roomType', function($query) use ($roomTypeId, $guests) {
            $query->where('id', $roomTypeId)
                  ->where('max_occupancy', '>=', $guests);
        })
        ->where('is_available', true)
        ->get()
        ->first(function($room) use ($checkIn, $checkOut) {
            return $room->isAvailableBetween($checkIn, $checkOut);
        });

        if (!$availableRoom) {
            return response()->json([
                'message' => 'Нет доступных комнат указанного типа на выбранные даты.'
            ], 400);
        }
        
        // Получаем дополнительные услуги для передачи на фронтенд
        $extraServices = ExtraService::all();
        
        // Рассчитываем стоимость проживания
        $duration = $checkIn->diffInDays($checkOut);
        $roomPrice = $availableRoom->roomType->price_per_night * $duration;

        return response()->json([
            'room' => $availableRoom->load('roomType'),
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'guests' => $guests,
            'duration' => $duration,
            'room_price' => $roomPrice,
            'extra_services' => $extraServices
        ]);
    }

    /**
     * Сохранение бронирования
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'extra_services' => 'nullable|array',
            'special_requests' => 'nullable|string'
        ]);

        $room = Room::findOrFail($request->room_id);
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);

        // Проверить, доступна ли комната
        if (!$room->isAvailableBetween($checkIn, $checkOut)) {
            return response()->json([
                'message' => 'Комната больше не доступна на выбранные даты.'
            ], 400);
        }

        // Расчет длительности пребывания в днях
        $duration = $checkIn->diffInDays($checkOut);

        // Расчет стоимости комнаты
        $roomPrice = $room->roomType->price_per_night * $duration;

        // Создание бронирования
        $booking = new Booking([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'guests' => $request->guests,
            'total_price' => $roomPrice, // Обновим с учетом доп. услуг
            'status' => 'pending',
            'special_requests' => $request->special_requests
        ]);

        $booking->save();
        
        // Добавление дополнительных услуг, если выбраны
        $totalExtraServicesPrice = 0;

        if ($request->has('extra_services') && is_array($request->extra_services)) {
            foreach ($request->extra_services as $serviceId => $quantity) {
                if (is_numeric($quantity) && intval($quantity) > 0) {
                    $service = ExtraService::findOrFail($serviceId);
                    $price = $service->price * intval($quantity);
                    $totalExtraServicesPrice += $price;

                    $booking->extraServices()->attach($serviceId, [
                        'quantity' => intval($quantity),
                        'price' => $service->price
                    ]);
                }
            }
        }

        // Обновление общей стоимости с учетом доп. услуг
        $booking->total_price = $roomPrice + $totalExtraServicesPrice;
        $booking->save();

        return response()->json([
            'message' => 'Бронирование успешно создано',
            'data' => $booking->load(['room.roomType', 'extraServices'])
        ], 201);
    }

    /**
     * Детали бронирования
     */
    public function show(Booking $booking)
    {
        // Проверка, принадлежит ли бронирование текущему пользователю
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Доступ запрещен'
            ], 403);
        }

        return response()->json([
            'data' => $booking->load(['room.roomType', 'extraServices'])
        ]);
    }

    /**
     * Отмена бронирования
     */
    public function cancel(Booking $booking)
    {
        // Проверка, принадлежит ли бронирование текущему пользователю
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Доступ запрещен'
            ], 403);
        }

        // Проверка, можно ли отменить бронирование
        if ($booking->status === 'cancelled') {
            return response()->json([
                'message' => 'Бронирование уже отменено'
            ], 400);
        }

        if ($booking->status === 'completed') {
            return response()->json([
                'message' => 'Нельзя отменить завершенное бронирование'
            ], 400);
        }

        // Отмена бронирования
        $booking->status = 'cancelled';
        $booking->save();

        return response()->json([
            'message' => 'Бронирование успешно отменено',
            'data' => $booking
        ]);
    }
} 