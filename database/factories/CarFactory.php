<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cars = [
            ['brand' => 'Toyota',     'models' => ['Camry', 'Corolla', 'RAV4', 'Yaris', 'Land Cruiser']],
            ['brand' => 'BMW',        'models' => ['3 Series', '5 Series', 'X5', 'X3', '7 Series']],
            ['brand' => 'Mercedes',   'models' => ['C-Class', 'E-Class', 'S-Class', 'GLC', 'GLE']],
            ['brand' => 'Audi',       'models' => ['A4', 'A6', 'Q5', 'Q7', 'A3']],
            ['brand' => 'Hyundai',    'models' => ['Tucson', 'Elantra', 'Santa Fe', 'Sonata', 'Accent']],
            ['brand' => 'Kia',        'models' => ['Sportage', 'Sorento', 'Cerato', 'Optima', 'Stinger']],
            ['brand' => 'Volkswagen', 'models' => ['Passat', 'Golf', 'Tiguan', 'Polo', 'Touareg']],
            ['brand' => 'Nissan',     'models' => ['Qashqai', 'X-Trail', 'Altima', 'Murano', 'Pathfinder']],
            ['brand' => 'Honda',      'models' => ['Civic', 'Accord', 'CR-V', 'HR-V', 'Pilot']],
            ['brand' => 'Lexus',      'models' => ['RX', 'NX', 'ES', 'LX', 'IS']],
        ];

        $carData = fake()->randomElement($cars);
        $brand = $carData['brand'];
        $model = fake()->randomElement($carData['models']);

        $colors = ['Ağ', 'Qara', 'Gümüşü', 'Boz', 'Qırmızı', 'Mavi', 'Yaşıl', 'Bej'];
        $year = fake()->numberBetween(2018, 2024);

        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $plate = fake()->randomElement(['10', '77', '99']) . '-' .
            $letters[random_int(0, 25)] . $letters[random_int(0, 25)] . '-' .
            fake()->numberBetween(100, 999);

        $dailyRate = fake()->randomElement([45, 55, 65, 75, 85, 100, 120, 150, 180, 200]);

        return [
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'plate_number' => $plate,
            'color' => fake()->randomElement($colors),
            'fuel_type' => fake()->randomElement(['petrol', 'diesel', 'hybrid', 'electric']),
            'transmission' => fake()->randomElement(['automatic', 'manual']),
            'seats' => fake()->randomElement([4, 5, 5, 5, 7]),
            'mileage' => fake()->numberBetween(5000, 120000),
            'daily_rate' => $dailyRate,
            'deposit_amount' => $dailyRate * 5,
            'status' => fake()->randomElement(['available', 'available', 'available', 'rented', 'maintenance']),
            'description' => null,
        ];
    }
}
