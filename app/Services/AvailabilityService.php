<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Reservation;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Check if a car is available for the given date range.
     *
     * Returns false if there is an approved or active reservation
     * that overlaps with the requested dates.
     */
    public function checkAvailability(int $carId, Carbon $start, Carbon $end): bool
    {
        $car = Car::find($carId);

        if (! $car || in_array($car->status, ['maintenance', 'rented'])) {
            return false;
        }

        $conflict = Reservation::where('car_id', $carId)
            ->whereIn('status', ['approved', 'active'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();

        return ! $conflict;
    }

    /**
     * Get all available cars for a given date range.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Car>
     */
    public function availableCars(Carbon $start, Carbon $end)
    {
        $bookedCarIds = Reservation::whereIn('status', ['approved', 'active'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->pluck('car_id');

        return Car::whereNotIn('id', $bookedCarIds)
            ->whereNotIn('status', ['maintenance', 'rented'])
            ->get();
    }
}
