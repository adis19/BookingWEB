<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalBookings = Booking::count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $totalRooms = Room::count();

        // Получение списка комнат с подтвержденными бронированиями
        $roomsWithConfirmedBookings = Booking::where('status', 'confirmed')
            ->distinct('room_id')
            ->pluck('room_id')
            ->toArray();

        // Доступные номера - это все номера, которые не имеют подтвержденных бронирований
        $availableRooms = $totalRooms - count($roomsWithConfirmedBookings);

        $totalUsers = User::where('role', 'user')->count();

        // Get recent bookings
        $recentBookings = Booking::with(['user', 'room.roomType'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get upcoming check-ins
        $upcomingCheckIns = Booking::with(['user', 'room.roomType'])
            ->where('check_in_date', '>=', Carbon::today())
            ->where('status', 'confirmed')
            ->orderBy('check_in_date')
            ->take(5)
            ->get();

        // Calculate monthly revenue (только от завершенных бронирований)
        $monthlyRevenue = Booking::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');

        return view('admin.dashboard', compact(
            'totalBookings',
            'confirmedBookings',
            'pendingBookings',
            'totalRooms',
            'availableRooms',
            'totalUsers',
            'recentBookings',
            'upcomingCheckIns',
            'monthlyRevenue'
        ));
    }
}
