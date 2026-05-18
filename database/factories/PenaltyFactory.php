<?php

namespace Database\Factories;

use App\Models\Penalty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penalty>
 */
class PenaltyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['late_return', 'late_return', 'fuel', 'damage', 'other']);

        $descriptions = [
            'late_return' => fake()->randomElement([
                '2 gün gecikdirmə', '3 gün gecikdirmə', '1 gün gecikdirmə', '5 gün gecikdirmə',
            ]),
            'fuel' => fake()->randomElement([
                'Yanacaq çatışmazlığı: full → half', 'Yanacaq çatışmazlığı: 3/4 → 1/4', 'Yanacaq çatışmazlığı: full → empty',
            ]),
            'damage' => fake()->randomElement([
                'Ön bamper zədəsi', 'Yan güzgü sınması', 'Şüşə cızığı', 'Kapot cızığı',
            ]),
            'other' => fake()->randomElement([
                'Siqaret qoxusu', 'İçəri çirklənmə', 'Əlavə xidmət', 'Digər zərər',
            ]),
        ];

        $isPaid = fake()->boolean(40);

        return [
            'type' => $type,
            'description' => $descriptions[$type],
            'amount' => fake()->randomElement([50, 75, 100, 120, 150, 200, 250, 300]),
            'paid' => $isPaid,
            'paid_at' => $isPaid ? fake()->dateTimeBetween('-3 months', 'now') : null,
        ];
    }
}
