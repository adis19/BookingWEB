<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\ExtraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_type_id' => 'required|exists:room_types,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'guests' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please correct the errors below.');
            }
            
            $roomType = RoomType::findOrFail($request->room_type_id);
            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);
            $guests = $request->guests;
            
            // Log inputs for debugging
            Log::info('Create booking request', [
                'room_type_id' => $request->room_type_id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests' => $request->guests
            ]);
            
            // Find available room of this type
            $availableRoom = null;
            foreach ($roomType->rooms as $room) {
                Log::info('Checking room', [
                    'room_id' => $room->id,
                    'room_number' => $room->room_number,
                    'is_available' => $room->is_available ? 'Yes' : 'No',
                    'availability_check' => $room->isAvailableBetween($checkIn, $checkOut) ? 'Available' : 'Not Available'
                ]);
                
                if ($room->is_available && $room->isAvailableBetween($checkIn, $checkOut)) {
                    $availableRoom = $room;
                    break;
                }
            }
            
            if (!$availableRoom) {
                Log::warning('No available rooms found for this room type', [
                    'room_type_id' => $roomType->id,
                    'check_in' => $checkIn->format('Y-m-d'),
                    'check_out' => $checkOut->format('Y-m-d')
                ]);
                
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
        } catch (\Exception $e) {
            Log::error('Error in create booking', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        try {
            Log::info('Store booking request', [
                'input' => $request->all()
            ]);
            
            $validator = Validator::make($request->all(), [
                'room_id' => 'required|exists:rooms,id',
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'guests' => 'required|integer|min:1',
                'extra_services' => 'nullable|array',
                'special_requests' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                Log::warning('Validation failed in store booking', [
                    'errors' => $errors
                ]);
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please check your input: ' . implode(', ', $errors));
            }
            
            $room = Room::findOrFail($request->room_id);
            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            
            // Verify room is still available
            if (!$room->isAvailableBetween($checkIn, $checkOut)) {
                Log::warning('Room no longer available', [
                    'room_id' => $room->id,
                    'check_in' => $checkIn->format('Y-m-d'),
                    'check_out' => $checkOut->format('Y-m-d')
                ]);
                
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
            
            Log::info('Booking created', [
                'booking_id' => $booking->id,
                'room_id' => $room->id,
                'user_id' => Auth::id()
            ]);
            
            // Add extra services if selected
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
                        
                        Log::info('Extra service added to booking', [
                            'booking_id' => $booking->id,
                            'service_id' => $serviceId,
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
        } catch (\Exception $e) {
            Log::error('Error in store booking', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')->with('error', 'An error occurred: ' . $e->getMessage());
        }
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
