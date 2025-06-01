<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Главная страница отчетов
     */
    public function index()
    {
        $reports = Report::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Страница создания отчета
     */
    public function create()
    {
        $reportTypes = Report::getTypes();
        return view('admin.reports.create', compact('reportTypes'));
    }

    /**
     * Генерация отчета
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Report::getTypes())),
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start'
        ]);

        // Создаем запись отчета
        $report = Report::create([
            'title' => $request->title,
            'type' => $request->type,
            'generated_by' => Auth::id(),
            'period_start' => $request->period_start ? Carbon::parse($request->period_start) : null,
            'period_end' => $request->period_end ? Carbon::parse($request->period_end) : null,
            'status' => Report::STATUS_GENERATING
        ]);

        try {
            // Генерируем данные отчета
            $data = $this->generateReportData($request->type, $request->period_start, $request->period_end);
            
            // Обновляем отчет с данными
            $report->update([
                'data' => $data,
                'status' => Report::STATUS_COMPLETED
            ]);

            return redirect()->route('admin.reports.show', $report)
                ->with('success', 'Отчет успешно сгенерирован!');

        } catch (\Exception $e) {
            $report->update(['status' => Report::STATUS_FAILED]);
            
            return redirect()->back()
                ->with('error', 'Ошибка при генерации отчета: ' . $e->getMessage());
        }
    }

    /**
     * Просмотр отчета
     */
    public function show(Report $report)
    {
        return view('admin.reports.show', compact('report'));
    }

    /**
     * Удаление отчета
     */
    public function destroy(Report $report)
    {
        $report->delete();
        
        return redirect()->route('admin.reports.index')
            ->with('success', 'Отчет удален!');
    }

    /**
     * Быстрые отчеты (AJAX)
     */
    public function quickReport(Request $request)
    {
        $type = $request->get('type');
        $period = $request->get('period', 'month'); // week, month, quarter, year
        
        $periodStart = $this->getPeriodStart($period);
        $periodEnd = Carbon::now();
        
        try {
            $data = $this->generateReportData($type, $periodStart, $periodEnd);
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Экспорт отчета в CSV
     */
    public function export(Report $report)
    {
        $filename = 'report_' . $report->type . '_' . $report->id . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        $callback = function() use ($report) {
            $file = fopen('php://output', 'w');
            
            // Заголовок CSV
            fputcsv($file, ['Отчет: ' . $report->title]);
            fputcsv($file, ['Период: ' . $report->formatted_period]);
            fputcsv($file, ['Сгенерирован: ' . $report->created_at->format('d.m.Y H:i')]);
            fputcsv($file, []); // Пустая строка
            
            // Данные в зависимости от типа отчета
            $this->exportReportData($file, $report);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Дашборд аналитики
     */
    public function dashboard()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Быстрая статистика
        $stats = [
            'current_month_bookings' => $this->reportService->generateBookingsReport($currentMonth)['total_bookings'],
            'current_month_revenue' => $this->reportService->generateRevenueReport($currentMonth)['total_revenue'],
            'current_occupancy' => $this->reportService->generateOccupancyReport($currentMonth)['average_occupancy'],
            'total_users' => $this->reportService->generateUsersReport()['total_users']
        ];

        return view('admin.reports.dashboard', compact('stats'));
    }

    private function generateReportData($type, $periodStart, $periodEnd)
    {
        switch ($type) {
            case Report::TYPE_BOOKINGS:
                return $this->reportService->generateBookingsReport($periodStart, $periodEnd);
            case Report::TYPE_REVENUE:
                return $this->reportService->generateRevenueReport($periodStart, $periodEnd);
            case Report::TYPE_ROOMS:
                return $this->reportService->generateRoomsReport($periodStart, $periodEnd);
            case Report::TYPE_USERS:
                return $this->reportService->generateUsersReport($periodStart, $periodEnd);
            case Report::TYPE_OCCUPANCY:
                return $this->reportService->generateOccupancyReport($periodStart, $periodEnd);
            default:
                throw new \Exception('Неизвестный тип отчета');
        }
    }

    private function getPeriodStart($period)
    {
        switch ($period) {
            case 'week':
                return Carbon::now()->startOfWeek();
            case 'month':
                return Carbon::now()->startOfMonth();
            case 'quarter':
                return Carbon::now()->startOfQuarter();
            case 'year':
                return Carbon::now()->startOfYear();
            default:
                return Carbon::now()->startOfMonth();
        }
    }

    private function exportReportData($file, $report)
    {
        $data = $report->data;
        
        switch ($report->type) {
            case Report::TYPE_BOOKINGS:
                fputcsv($file, ['ID', 'Гость', 'Номер', 'Тип номера', 'Заезд', 'Выезд', 'Сумма', 'Статус']);
                foreach ($data['bookings_list'] as $booking) {
                    fputcsv($file, [
                        $booking['id'],
                        $booking['user_name'],
                        $booking['room_number'],
                        $booking['room_type'],
                        $booking['check_in'],
                        $booking['check_out'],
                        $booking['total_amount'],
                        $booking['status']
                    ]);
                }
                break;
                
            case Report::TYPE_REVENUE:
                fputcsv($file, ['Дата', 'Доход']);
                foreach ($data['daily_revenue'] as $date => $revenue) {
                    fputcsv($file, [$date, $revenue]);
                }
                break;
                
            // Добавить другие типы по необходимости
        }
    }
}
