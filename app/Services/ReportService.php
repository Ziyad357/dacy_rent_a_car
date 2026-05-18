<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Payment;
use App\Models\Penalty;
use App\Models\Reservation;
use Carbon\Carbon;

class ReportService
{
    /**
     * Daily summary for a specific date.
     *
     * @return array{
     *     date: string,
     *     total_revenue: float,
     *     reservations_count: int,
     *     new_reservations: int,
     *     completed_reservations: int,
     *     unpaid_penalties: float
     * }
     */
    public function dailySummary(Carbon $date): array
    {
        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $date->copy()->endOfDay();

        $totalRevenue = Payment::whereBetween('paid_at', [$dayStart, $dayEnd])
            ->whereIn('type', ['rental', 'deposit'])
            ->sum('amount');

        $reservationsCount = Reservation::whereDate('start_date', $date)->count();

        $newReservations = Reservation::whereBetween('created_at', [$dayStart, $dayEnd])->count();

        $completedReservations = Reservation::where('status', 'completed')
            ->whereBetween('updated_at', [$dayStart, $dayEnd])
            ->count();

        $unpaidPenalties = Penalty::where('paid', false)->sum('amount');

        return [
            'date' => $date->toDateString(),
            'total_revenue' => (float) $totalRevenue,
            'reservations_count' => $reservationsCount,
            'new_reservations' => $newReservations,
            'completed_reservations' => $completedReservations,
            'unpaid_penalties' => (float) $unpaidPenalties,
        ];
    }

    /**
     * Monthly summary for a given year and month.
     *
     * @return array{
     *     year: int,
     *     month: int,
     *     total_revenue: float,
     *     total_reservations: int,
     *     completed_reservations: int,
     *     cancelled_reservations: int,
     *     penalty_revenue: float,
     *     daily_breakdown: array<int, array{date: string, revenue: float}>
     * }
     */
    public function monthlySummary(int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $totalRevenue = Payment::whereBetween('paid_at', [$start, $end])
            ->whereIn('type', ['rental', 'deposit'])
            ->sum('amount');

        $penaltyRevenue = Payment::whereBetween('paid_at', [$start, $end])
            ->where('type', 'penalty')
            ->sum('amount');

        $totalReservations = Reservation::whereBetween('created_at', [$start, $end])->count();

        $completedReservations = Reservation::where('status', 'completed')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $cancelledReservations = Reservation::where('status', 'cancelled')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $dailyBreakdown = Payment::selectRaw('DATE(paid_at) as date, SUM(amount) as revenue')
            ->whereBetween('paid_at', [$start, $end])
            ->whereIn('type', ['rental', 'deposit'])
            ->groupByRaw('DATE(paid_at)')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => ['date' => $row->date, 'revenue' => (float) $row->revenue])
            ->toArray();

        return [
            'year' => $year,
            'month' => $month,
            'total_revenue' => (float) $totalRevenue,
            'total_reservations' => $totalReservations,
            'completed_reservations' => $completedReservations,
            'cancelled_reservations' => $cancelledReservations,
            'penalty_revenue' => (float) $penaltyRevenue,
            'daily_breakdown' => $dailyBreakdown,
        ];
    }

    /**
     * Car utilization percentage over a date range.
     *
     * Utilization = (rented days / total days in range) × 100
     */
    public function carUtilization(int $carId, Carbon $from, Carbon $to): float
    {
        $totalDays = (int) $from->diffInDays($to);

        if ($totalDays === 0) {
            return 0.0;
        }

        $rentedDays = Reservation::where('car_id', $carId)
            ->whereIn('status', ['active', 'completed'])
            ->where('start_date', '<=', $to)
            ->where('end_date', '>=', $from)
            ->get()
            ->sum(function (Reservation $reservation) use ($from, $to) {
                $start = max($reservation->start_date, $from);
                $end = min($reservation->end_date, $to);

                return max(0, (int) Carbon::parse($start)->diffInDays(Carbon::parse($end)));
            });

        return round(min(($rentedDays / $totalDays) * 100, 100), 2);
    }

    /**
     * Last N days revenue for chart display.
     *
     * @return array<int, array{date: string, revenue: float}>
     */
    public function lastDaysRevenue(int $days = 30): array
    {
        $start = now()->subDays($days - 1)->startOfDay();
        $end = now()->endOfDay();

        $payments = Payment::selectRaw('DATE(paid_at) as date, SUM(amount) as revenue')
            ->whereBetween('paid_at', [$start, $end])
            ->whereIn('type', ['rental', 'deposit'])
            ->groupByRaw('DATE(paid_at)')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $result[] = [
                'date' => $date,
                'revenue' => isset($payments[$date]) ? (float) $payments[$date]->revenue : 0.0,
            ];
        }

        return $result;
    }

    /**
     * Car status distribution for donut chart.
     *
     * @return array<string, int>
     */
    public function carStatusDistribution(): array
    {
        return Car::selectRaw('status, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
    }
}
