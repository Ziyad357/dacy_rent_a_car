<?php

namespace Database\Factories;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fuelLevels = ['full', 'three_quarters', 'half', 'quarter', 'empty'];
        $mileageOut = fake()->numberBetween(10000, 150000);
        $isClosed = fake()->boolean(70);

        return [
            'contract_number' => 'CNT-' . date('Y') . '-' . str_pad(fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'signed_at' => fake()->dateTimeBetween('-5 months', 'now'),
            'fuel_level_out' => fake()->randomElement($fuelLevels),
            'fuel_level_in' => $isClosed ? fake()->randomElement($fuelLevels) : null,
            'mileage_out' => $mileageOut,
            'mileage_in' => $isClosed ? $mileageOut + fake()->numberBetween(50, 3000) : null,
            'condition_out' => fake()->randomElement(['Yaxşı', 'Əla', 'Normal', 'Kiçik cızıqlar var']),
            'condition_in' => $isClosed ? fake()->randomElement(['Yaxşı', 'Əla', 'Normal', 'Cızıq var']) : null,
            'returned_at' => $isClosed ? fake()->dateTimeBetween('-4 months', 'now') : null,
        ];
    }
}
