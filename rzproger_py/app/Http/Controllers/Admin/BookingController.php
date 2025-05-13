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
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                          ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->exists();
            
        if ($conflictingBookings) {
            return back()->with('error', 'Room is not available for the selected dates')->withInput();
        }

        // Calculate duration and room price
        $duration = $checkIn->diffInDays($checkOut);
        $roomPrice = $room->roomType->price_per_night * $duration;

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

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking updated successfully');
    }

    public function destroy(Booking $booking)
    {
        $booking->extraServices()->detach();
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->status = $request->status;
        $booking->save();

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking status updated successfully');
    }
}
