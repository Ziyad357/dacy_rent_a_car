<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomElement([100, 150, 200, 250, 300, 400, 500, 600, 750, 1000]),
            'type' => fake()->randomElement(['deposit', 'deposit', 'rental', 'rental', 'rental', 'penalty', 'refund']),
            'method' => fake()->randomElement(['cash', 'cash', 'card', 'card', 'transfer']),
            'reference_number' => fake()->optional(0.5)->bothify('REF-####-????'),
            'paid_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'note' => fake()->optional(0.2)->sentence(),
        ];
    }
}
