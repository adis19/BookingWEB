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
        $availableRooms = Room::where('is_available', true)->count();
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
            
        // Calculate monthly revenue
        $monthlyRevenue = Booking::where('status', 'confirmed')
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
