<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use App\Models\ExtraService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room.roomType']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date);
            $toDate = Carbon::parse($request->to_date);
            $query->whereBetween('check_in_date', [$fromDate, $toDate])
                ->orWhereBetween('check_out_date', [$fromDate, $toDate]);
        }

        // Sort by created date by default
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'room.roomType', 'extraServices']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $booking->load(['user', 'room.roomType', 'extraServices']);
        $users = User::all();
        $roomTypes = RoomType::with('rooms')->get();
        $extraServices = ExtraService::all();

        return view('admin.bookings.edit', compact('booking', 'users', 'roomTypes', 'extraServices'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'special_requests' => 'nullable|string',
            'extra_services' => 'nullable|array',
            'extra_services.*' => 'exists:extra_services,id',
        ]);

        // Check if room is available for new dates (except for this booking)
        $room = Room::findOrFail($request->room_id);
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);

        $conflictingBookings = $room->bookings()
            ->where('id', '!=', $booking->id)
            ->where('status', 'confirmed')  // Проверяем только подтвержденные бронирования
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                          ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->exists();

        if ($conflictingBookings && $request->status == 'confirmed') {
            return back()->with('error', 'Номер недоступен для выбранных дат, так как он уже забронирован')->withInput();
        }

        // Calculate duration and room price
        $duration = $checkIn->diffInDays($checkOut);
        $roomPrice = $room->roomType->price_per_night * $duration;

        // Сохраняем старый статус для сравнения
        $oldStatus = $booking->status;

        // Update booking
        $booking->update([
            'user_id' => $request->user_id,
            'room_id' => $room->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'guests' => $request->guests,
            'status' => $request->status,
            'special_requests' => $request->special_requests,
            'total_price' => $roomPrice, // Will be updated with extra services
        ]);

        // Update extra services
        $booking->extraServices()->detach();
        $totalExtraServicesPrice = 0;

        if ($request->has('extra_services')) {
            foreach ($request->extra_services as $serviceId => $quantity) {
                if ($quantity > 0) {
                    $service = ExtraService::findOrFail($serviceId);
                    $price = $service->price * $quantity;
                    $totalExtraServicesPrice += $price;

                    $booking->extraServices()->attach($serviceId, [
                        'quantity' => $quantity,
                        'price' => $service->price
                    ]);
                }
            }
        }

        // Update total price
        $booking->total_price = $roomPrice + $totalExtraServicesPrice;
        $booking->save();

        // Обновляем статус номера только если статус бронирования изменился
        if ($oldStatus != $request->status) {
            $this->updateRoomStatus($booking);
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Бронирование успешно обновлено');
    }

    public function destroy(Booking $booking)
    {
        // Сохраняем информацию о комнате перед удалением
        $room = $booking->room;
        $roomId = $room->id;

        // Удаляем бронирование
        $booking->extraServices()->detach();
        $booking->delete();

        // Проверяем, есть ли другие подтвержденные бронирования на эту комнату
        $otherConfirmedBookings = Booking::where('room_id', $roomId)
            ->where('status', 'confirmed')
            ->count();

        // Если нет других подтвержденных бронирований, помечаем комнату как свободную
        if ($otherConfirmedBookings == 0) {
            $room->is_available = true;
            $room->save();
            Log::info("После удаления бронирования комната #{$roomId} отмечена как свободная");
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Бронирование успешно удалено');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $oldStatus = $booking->status;
        $booking->status = $request->status;
        $booking->save();

        // Обновляем статус номера только если статус бронирования изменился
        if ($oldStatus != $request->status) {
            $this->updateRoomStatus($booking);
        }

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Статус бронирования успешно обновлен');
    }

    /**
     * Обновляет статус номера на основе статуса бронирования
     *
     * @param Booking $booking Бронирование
     * @return void
     */
    private function updateRoomStatus(Booking $booking)
    {
        // Получаем комнату из бронирования
        $room = $booking->room;

        // Простая логика: если бронирование подтверждено - комната занята, иначе - свободна
        if ($booking->status == 'confirmed') {
            Log::info("Бронирование #{$booking->id} подтверждено - комната #{$room->id} отмечена как занятая");
            $room->is_available = false;
        } else {
            // Перед тем как пометить комнату как свободную, проверим, есть ли другие подтвержденные бронирования
            $otherConfirmedBookings = Booking::where('room_id', $room->id)
                ->where('id', '!=', $booking->id)
                ->where('status', 'confirmed')
                ->count();

            // Если нет других подтвержденных бронирований, помечаем комнату как свободную
            if ($otherConfirmedBookings == 0) {
                Log::info("Бронирование #{$booking->id} имеет статус '{$booking->status}' - комната #{$room->id} отмечена как свободная");
                $room->is_available = true;
            } else {
                Log::info("Бронирование #{$booking->id} имеет статус '{$booking->status}', но есть другие подтвержденные бронирования - комната #{$room->id} остается занятой");
            }
        }

        // Сохраняем статус комнаты
        $room->save();
    }
}
