<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\ExtraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function create(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1'
        ]);
        
        $roomType = RoomType::findOrFail($request->room_type_id);
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $guests = $request->guests;
        
        // Find available room of this type
        $availableRoom = null;
        foreach ($roomType->rooms as $room) {
            if ($room->isAvailableBetween($checkIn, $checkOut)) {
                $availableRoom = $room;
                break;
            }
        }
        
        if (!$availableRoom) {
            return redirect()->back()->with('error', 'Sorry, no rooms of this type are available for the selected dates.');
        }
        
        $extraServices = ExtraService::all();
        $duration = $checkIn->diffInDays($checkOut);
        $roomPrice = $roomType->price_per_night * $duration;
        
        return view('bookings.create', compact(
            'roomType', 
            'availableRoom', 
            'checkIn', 
            'checkOut', 
            'guests',
            'extraServices',
            'duration',
            'roomPrice'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'extra_services' => 'nullable|array',
            'extra_services.*' => 'exists:extra_services,id',
            'special_requests' => 'nullable|string'
        ]);
        
        $room = Room::findOrFail($request->room_id);
        $checkIn = Carbon::parse($request->check_in_date);
        $checkOut = Carbon::parse($request->check_out_date);
        
        // Verify room is still available
        if (!$room->isAvailableBetween($checkIn, $checkOut)) {
            return redirect()->route('rooms.index')->with('error', 'Sorry, this room is no longer available for the selected dates.');
        }
        
        // Calculate booking duration in days
        $duration = $checkIn->diffInDays($checkOut);
        
        // Calculate room price
        $roomPrice = $room->roomType->price_per_night * $duration;
        
        // Create booking
        $booking = new Booking([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'guests' => $request->guests,
            'total_price' => $roomPrice, // Will update with extra services
            'status' => 'pending',
            'special_requests' => $request->special_requests
        ]);
        
        $booking->save();
        
        // Add extra services if selected
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
        
        // Update total price with extra services
        $booking->total_price = $roomPrice + $totalExtraServicesPrice;
        $booking->save();
        
        return redirect()->route('bookings.show', $booking)->with('success', 'Your booking has been created successfully!');
    }
    
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        return view('bookings.show', compact('booking'));
    }
    
    public function index()
    {
        $bookings = Auth::user()->bookings;
        
        return view('bookings.index', compact('bookings'));
    }
    
    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        if ($booking->status === 'confirmed' || $booking->status === 'pending') {
            $booking->status = 'cancelled';
            $booking->save();
            return redirect()->route('bookings.show', $booking)->with('success', 'Your booking has been cancelled.');
        }
        
        return redirect()->route('bookings.show', $booking)->with('error', 'This booking cannot be cancelled.');
    }
}
