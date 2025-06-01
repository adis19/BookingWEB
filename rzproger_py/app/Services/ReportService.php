<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function generateBookingsReport($periodStart = null, $periodEnd = null)
    {
        $query = Booking::with(['user', 'room.roomType']);

        if ($periodStart) {
            $query->where('check_in_date', '>=', $periodStart);
        }
        if ($periodEnd) {
            $query->where('check_out_date', '<=', $periodEnd);
        }

        $bookings = $query->get();

        $data = [
            'total_bookings' => $bookings->count(),
            'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'bookings_by_month' => $this->getBookingsByMonth($bookings),
            'bookings_by_room_type' => $this->getBookingsByRoomType($bookings),
            'average_stay_duration' => $this->getAverageStayDuration($bookings),
            'bookings_list' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'user_name' => $booking->user->name,
                    'room_number' => $booking->room->number,
                    'room_type' => $booking->room->roomType->name,
                    'check_in' => $booking->check_in_date->format('d.m.Y'),
                    'check_out' => $booking->check_out_date->format('d.m.Y'),
                    'total_amount' => $booking->total_price,
                    'status' => $booking->status
                ];
            })
        ];

        return $data;
    }

    public function generateRevenueReport($periodStart = null, $periodEnd = null)
    {
        $query = Booking::where('status', 'confirmed');

        if ($periodStart) {
            $query->where('check_in_date', '>=', $periodStart);
        }
        if ($periodEnd) {
            $query->where('check_out_date', '<=', $periodEnd);
        }

        $bookings = $query->get();

        $data = [
            'total_revenue' => $bookings->sum('total_price'),
            'average_booking_value' => $bookings->avg('total_price'),
            'revenue_by_month' => $this->getRevenueByMonth($bookings),
            'revenue_by_room_type' => $this->getRevenueByRoomType($bookings),
            'daily_revenue' => $this->getDailyRevenue($bookings),
            'top_revenue_sources' => $this->getTopRevenueSources($bookings)
        ];

        return $data;
    }

    public function generateRoomsReport($periodStart = null, $periodEnd = null)
    {
        $rooms = Room::with('roomType')->get();

        $data = [
            'total_rooms' => $rooms->count(),
            'rooms_by_type' => $rooms->groupBy('room_type_id')->map(function ($group) {
                return [
                    'type_name' => $group->first()->roomType->name,
                    'count' => $group->count(),
                    'available' => $group->where('status', 'available')->count(),
                    'occupied' => $group->where('status', 'occupied')->count(),
                    'maintenance' => $group->where('status', 'maintenance')->count()
                ];
            })->values(),
            'room_status_distribution' => [
                'available' => $rooms->where('status', 'available')->count(),
                'occupied' => $rooms->where('status', 'occupied')->count(),
                'maintenance' => $rooms->where('status', 'maintenance')->count()
            ],
            'room_types' => RoomType::withCount('rooms')->get()->map(function ($type) {
                return [
                    'name' => $type->name,
                    'rooms_count' => $type->rooms_count,
                    'base_price' => $type->base_price
                ];
            })
        ];

        return $data;
    }

    public function generateUsersReport($periodStart = null, $periodEnd = null)
    {
        $query = User::withCount('bookings');

        if ($periodStart) {
            $query->where('created_at', '>=', $periodStart);
        }
        if ($periodEnd) {
            $query->where('created_at', '<=', $periodEnd);
        }

        $users = $query->get();

        $data = [
            'total_users' => $users->count(),
            'new_users' => $users->where('created_at', '>=', Carbon::now()->subMonth())->count(),
            'users_with_bookings' => $users->where('bookings_count', '>', 0)->count(),
            'users_by_month' => $this->getUsersByMonth($users),
            'top_customers' => $users->sortByDesc('bookings_count')->take(10)->map(function ($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'bookings_count' => $user->bookings_count,
                    'total_spent' => $user->bookings()->where('status', 'confirmed')->sum('total_price')
                ];
            })
        ];

        return $data;
    }

    public function generateOccupancyReport($periodStart = null, $periodEnd = null)
    {
        $totalRooms = Room::count();

        $occupancyData = [];
        $start = $periodStart ? Carbon::parse($periodStart) : Carbon::now()->subMonth();
        $end = $periodEnd ? Carbon::parse($periodEnd) : Carbon::now();

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $occupiedRooms = Booking::where('status', 'confirmed')
                ->where('check_in_date', '<=', $date)
                ->where('check_out_date', '>', $date)
                ->count();

            $occupancyData[] = [
                'date' => $date->format('Y-m-d'),
                'occupied_rooms' => $occupiedRooms,
                'occupancy_rate' => $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0
            ];
        }

        $data = [
            'total_rooms' => $totalRooms,
            'average_occupancy' => collect($occupancyData)->avg('occupancy_rate'),
            'max_occupancy' => collect($occupancyData)->max('occupancy_rate'),
            'min_occupancy' => collect($occupancyData)->min('occupancy_rate'),
            'daily_occupancy' => $occupancyData,
            'occupancy_by_room_type' => $this->getOccupancyByRoomType($start, $end)
        ];

        return $data;
    }

    private function getBookingsByMonth($bookings)
    {
        return $bookings->groupBy(function ($booking) {
            return $booking->created_at->format('Y-m');
        })->map->count();
    }

    private function getBookingsByRoomType($bookings)
    {
        return $bookings->groupBy('room.room_type_id')->map(function ($group) {
            return [
                'type_name' => $group->first()->room->roomType->name,
                'count' => $group->count()
            ];
        })->values();
    }

    private function getAverageStayDuration($bookings)
    {
        $durations = $bookings->map(function ($booking) {
            return $booking->check_in_date->diffInDays($booking->check_out_date);
        });

        return $durations->avg();
    }

    private function getRevenueByMonth($bookings)
    {
        return $bookings->groupBy(function ($booking) {
            return $booking->created_at->format('Y-m');
        })->map->sum('total_price');
    }

    private function getRevenueByRoomType($bookings)
    {
        return $bookings->groupBy('room.room_type_id')->map(function ($group) {
            return [
                'type_name' => $group->first()->room->roomType->name,
                'revenue' => $group->sum('total_price')
            ];
        })->values();
    }

    private function getDailyRevenue($bookings)
    {
        return $bookings->groupBy(function ($booking) {
            return $booking->created_at->format('Y-m-d');
        })->map->sum('total_price');
    }

    private function getTopRevenueSources($bookings)
    {
        return $bookings->groupBy('room.room_type_id')
            ->map(function ($group) {
                return [
                    'source' => $group->first()->room->roomType->name,
                    'revenue' => $group->sum('total_price'),
                    'bookings_count' => $group->count()
                ];
            })
            ->sortByDesc('revenue')
            ->values();
    }

    private function getUsersByMonth($users)
    {
        return $users->groupBy(function ($user) {
            return $user->created_at->format('Y-m');
        })->map->count();
    }

    private function getOccupancyByRoomType($start, $end)
    {
        $roomTypes = RoomType::withCount('rooms')->get();

        return $roomTypes->map(function ($type) use ($start, $end) {
            $totalRoomsOfType = $type->rooms_count;
            $totalDays = $start->diffInDays($end) + 1;

            $occupiedDays = Booking::where('status', 'confirmed')
                ->whereHas('room', function ($query) use ($type) {
                    $query->where('room_type_id', $type->id);
                })
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('check_in_date', [$start, $end])
                        ->orWhereBetween('check_out_date', [$start, $end])
                        ->orWhere(function ($q) use ($start, $end) {
                            $q->where('check_in_date', '<=', $start)
                              ->where('check_out_date', '>=', $end);
                        });
                })
                ->sum(DB::raw('DATEDIFF(LEAST(check_out_date, "' . $end->format('Y-m-d') . '"), GREATEST(check_in_date, "' . $start->format('Y-m-d') . '"))'));

            $maxPossibleDays = $totalRoomsOfType * $totalDays;
            $occupancyRate = $maxPossibleDays > 0 ? round(($occupiedDays / $maxPossibleDays) * 100, 2) : 0;

            return [
                'type_name' => $type->name,
                'occupancy_rate' => $occupancyRate,
                'total_rooms' => $totalRoomsOfType
            ];
        });
    }
}
