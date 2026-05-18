<?php

namespace App\Services;

use App\Models\Car;
use Carbon\Carbon;

class PricingService
{
    /**
     * Calculate rental pricing for a car over a date range.
     *
     * @return array{
     *     total_days: int,
     *     daily_rate: float,
     *     subtotal: float,
     *     discount_amount: float,
     *     total_amount: float,
     *     deposit_amount: float
     * }
     */
    public function calculate(int $carId, Carbon $start, Carbon $end, float $discountPercent = 0): array
    {
        $car = Car::findOrFail($carId);

        $totalDays = (int) $start->diffInDays($end);

        if ($totalDays < 1) {
            $totalDays = 1;
        }

        $dailyRate = (float) $car->daily_rate;
        $subtotal = $dailyRate * $totalDays;
        $discountAmount = round($subtotal * ($discountPercent / 100), 2);
        $totalAmount = round($subtotal - $discountAmount, 2);
        $depositAmount = (float) $car->deposit_amount;

        return [
            'total_days' => $totalDays,
            'daily_rate' => $dailyRate,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'deposit_amount' => $depositAmount,
        ];
    }
}
