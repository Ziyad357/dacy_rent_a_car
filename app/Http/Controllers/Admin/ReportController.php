<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    public function index()
    {
        return view('admin.reports.index');
    }

    public function daily(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : today();
        $data = $this->reportService->dailySummary($date);

        return view('admin.reports.daily', compact('data', 'date'));
    }

    public function monthly(Request $request)
    {
        $year = $request->integer('year', now()->year);
        $month = $request->integer('month', now()->month);
        $data = $this->reportService->monthlySummary($year, $month);

        return view('admin.reports.monthly', compact('data', 'year', 'month'));
    }

    public function utilization(Request $request)
    {
        $request->validate([
            'car_id' => 'nullable|exists:cars,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);

        $from = $request->filled('from') ? Carbon::parse($request->from) : now()->subMonth();
        $to = $request->filled('to') ? Carbon::parse($request->to) : now();
        $carId = $request->integer('car_id');

        $utilization = $carId ? $this->reportService->carUtilization($carId, $from, $to) : null;

        $cars = \App\Models\Car::orderBy('brand')->get();

        return view('admin.reports.utilization', compact('cars', 'utilization', 'carId', 'from', 'to'));
    }

    public function export(Request $request)
    {
        $year = $request->integer('year', now()->year);
        $month = $request->integer('month', now()->month);
        $data = $this->reportService->monthlySummary($year, $month);

        $filename = "report-{$year}-{$month}.csv";
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$filename}"];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tarix', 'Gəlir (AZN)']);
            foreach ($data['daily_breakdown'] as $row) {
                fputcsv($file, [$row['date'], $row['revenue']]);
            }
            fputcsv($file, []);
            fputcsv($file, ['Ümumi gəlir', $data['total_revenue']]);
            fputcsv($file, ['Ümumi rezervasiya', $data['total_reservations']]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
