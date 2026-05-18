<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', '+1 month');
        $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(1, 14) . ' days');
        $totalDays = $startDate->diff($endDate)->days ?: 1;

        $dailyRate = fake()->randomElement([45, 55, 65, 75, 85, 100, 120, 150]);
        $subtotal = $dailyRate * $totalDays;
        $discountPercent = fake()->randomElement([0, 0, 0, 5, 10, 15]);
        $discountAmount = round($subtotal * $discountPercent / 100, 2);
        $totalAmount = round($subtotal - $discountAmount, 2);

        $locations = ['Bakı Hava Limanı', 'Bakı Şəhər Mərkəzi', 'Neftçilər prospekti', 'Nizami küçəsi',
            '28 May metrosu', 'Heydar Əliyev Mərkəzi', 'Sumqayıt Şəhər Mərkəzi'];

        return [
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'pickup_location' => fake()->randomElement($locations),
            'return_location' => fake()->randomElement($locations),
            'status' => fake()->randomElement(['pending', 'pending', 'approved', 'active', 'completed', 'completed', 'completed', 'cancelled']),
            'total_days' => $totalDays,
            'daily_rate' => $dailyRate,
            'subtotal' => $subtotal,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'deposit_paid' => fake()->boolean(60),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
