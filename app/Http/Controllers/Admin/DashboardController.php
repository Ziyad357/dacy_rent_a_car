<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Penalty;
use App\Models\Reservation;
use App\Services\ReportService;

class DashboardController extends Controller
{
    public function __construct(private readonly ReportService $reportService) {}

    public function index()
    {
        $stats = [
            'cars_rented_today' => Reservation::where('status', 'active')
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->count(),

            'returning_today' => Reservation::where('status', 'active')
                ->whereDate('end_date', today())
                ->count(),

            'monthly_revenue' => Payment::whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->whereIn('type', ['rental', 'deposit'])
                ->sum('amount'),

            'unpaid_penalties' => Penalty::where('paid', false)->sum('amount'),

            'available_cars' => Car::where('status', 'available')->count(),

            'active_customers' => Customer::whereHas('user', fn ($q) => $q->where('is_active', true))
                ->where('blacklisted', false)
                ->count(),
        ];

        $recentReservations = Reservation::with(['car', 'customer'])
            ->latest()
            ->take(5)
            ->get();

        $revenueChart = $this->reportService->lastDaysRevenue(30);
        $statusChart = $this->reportService->carStatusDistribution();

        return view('admin.dashboard', compact('stats', 'recentReservations', 'revenueChart', 'statusChart'));
    }
}
