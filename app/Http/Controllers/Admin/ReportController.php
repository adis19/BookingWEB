<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Report;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Показать список отчетов
     */
    public function index()
    {
        $reports = Report::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Показать форму для создания нового отчета
     */
    public function create()
    {
        return view('admin.reports.create');
    }

    /**
     * Создать новый отчет
     */
    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:daily,monthly,custom',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);
        
        // Установить период отчета
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // Получить все бронирования за выбранный период
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])->get();
        
        // Получить только завершенные бронирования за выбранный период
        $completedBookings = $bookings->where('status', 'completed');
        
        // Получить отмененные бронирования
        $cancelledBookings = $bookings->where('status', 'cancelled');
        
        // Общий доход (только от завершенных бронирований)
        $totalRevenue = $completedBookings->sum('total_price');
        
        // Средняя стоимость завершенного бронирования
        $averageBookingValue = $completedBookings->count() > 0 
            ? $totalRevenue / $completedBookings->count() 
            : 0;
        
        // Найти самый популярный тип номера
        $mostBookedRoomTypeId = null;
        if ($completedBookings->count() > 0) {
            $roomTypeBookings = [];
            foreach ($completedBookings as $booking) {
                $roomTypeId = $booking->room->roomType->id;
                if (!isset($roomTypeBookings[$roomTypeId])) {
                    $roomTypeBookings[$roomTypeId] = 0;
                }
                $roomTypeBookings[$roomTypeId]++;
            }
            
            $mostBookedRoomTypeId = array_search(max($roomTypeBookings), $roomTypeBookings);
        }
        
        // Разделение дохода на доход от номеров и доход от услуг
        $roomRevenue = 0;
        $servicesRevenue = 0;
        
        foreach ($completedBookings as $booking) {
            // Считаем стоимость номера за все ночи
            $duration = $booking->check_in_date->diffInDays($booking->check_out_date);
            $roomPrice = $booking->room->roomType->price_per_night * $duration;
            $roomRevenue += $roomPrice;
            
            // Считаем стоимость доп. услуг
            $servicesRevenue += $booking->total_price - $roomPrice;
        }
        
        // Создаем отчет
        $report = Report::create([
            'report_type' => $request->report_type,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_bookings' => $bookings->count(),
            'completed_bookings' => $completedBookings->count(),
            'cancelled_bookings' => $cancelledBookings->count(),
            'total_revenue' => $totalRevenue,
            'average_booking_value' => $averageBookingValue,
            'most_booked_room_type_id' => $mostBookedRoomTypeId,
            'room_revenue' => $roomRevenue,
            'services_revenue' => $servicesRevenue,
            'generated_by' => Auth::user()->name,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Отчет успешно создан');
    }

    /**
     * Показать отчет
     */
    public function show(Report $report)
    {
        // Получаем дополнительные данные для отображения отчета
        $startDate = $report->start_date;
        $endDate = $report->end_date;
        
        // Получаем данные о бронированиях по дням
        $dailyStats = Booking::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Получаем статистику по типам номеров
        $roomTypeStats = Booking::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'room_types.id',
                'room_types.name',
                DB::raw('COUNT(bookings.id) as bookings_count'),
                DB::raw('SUM(bookings.total_price) as revenue')
            )
            ->groupBy('room_types.id', 'room_types.name')
            ->orderBy('bookings_count', 'desc')
            ->get();
        
        return view('admin.reports.show', compact('report', 'dailyStats', 'roomTypeStats'));
    }

    /**
     * Получить данные для панели мониторинга
     */
    public function dashboard()
    {
        // Получаем данные о доходах за текущий месяц (только завершенные бронирования)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $monthlyRevenue = Booking::where('status', 'completed')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');
        
        // Получаем данные о доходах за предыдущий месяц
        $previousMonth = Carbon::now()->subMonth()->month;
        $previousMonthYear = Carbon::now()->subMonth()->year;
        
        $previousMonthRevenue = Booking::where('status', 'completed')
            ->whereMonth('created_at', $previousMonth)
            ->whereYear('created_at', $previousMonthYear)
            ->sum('total_price');
        
        // Получаем данные о доходах за последние 6 месяцев
        $lastSixMonths = [];
        for ($i = 0; $i < 6; $i++) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            
            $revenue = Booking::where('status', 'completed')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->sum('total_price');
            
            $lastSixMonths[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }
        
        // Разворачиваем массив, чтобы месяцы шли в хронологическом порядке
        $lastSixMonths = array_reverse($lastSixMonths);
        
        // Получаем топ-5 типов номеров по доходу
        $topRoomTypes = Booking::where('status', 'completed')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'room_types.id',
                'room_types.name',
                DB::raw('COUNT(bookings.id) as bookings_count'),
                DB::raw('SUM(bookings.total_price) as revenue')
            )
            ->groupBy('room_types.id', 'room_types.name')
            ->orderBy('revenue', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.reports.dashboard', compact(
            'monthlyRevenue',
            'previousMonthRevenue',
            'lastSixMonths',
            'topRoomTypes'
        ));
    }
}
