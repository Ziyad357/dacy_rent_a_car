<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $maleNames = ['Rauf', 'Tural', 'Nicat', 'Elçin', 'Kamran', 'Murad', 'Fərid', 'Orxan', 'Şahin', 'Əli',
            'Elnur', 'Vüsal', 'Anar', 'Namiq', 'Rəşad', 'Emil', 'Bəhruz', 'Ceyhun', 'Ülvi', 'Zaur'];
        $femaleNames = ['Aynur', 'Günel', 'Sevinc', 'Leyla', 'Nərmin', 'Arzu', 'Gülnar', 'Şəbnəm', 'Lalə', 'Nigar'];
        $surnames = ['Əliyev', 'Hüseynov', 'Məmmədov', 'Quliyev', 'Həsənov', 'İsmayılov', 'Nəsirov', 'Babayev',
            'Rəhimov', 'Kərimov', 'Mustafayev', 'Əhmədov', 'Cəfərov', 'Rzayev', 'Mirzəyev'];

        $allNames = array_merge($maleNames, $femaleNames);
        $firstName = fake()->randomElement($allNames);
        $lastName = fake()->randomElement($surnames);
        $fullName = $firstName . ' ' . $lastName;

        $finLetters = 'ABCDEFGHJKLMNPRSTUVWXYZ';
        $fin = '';
        for ($i = 0; $i < 7; $i++) {
            $fin .= $i < 2 ? $finLetters[random_int(0, strlen($finLetters) - 1)] : random_int(0, 9);
        }

        $email = fake()->unique()->safeEmail();

        $user = \App\Models\User::factory()->create([
            'name' => $fullName,
            'email' => $email,
            'is_active' => true,
        ]);
        $user->syncRoles(['customer']);

        return [
            'user_id' => $user->id,
            'full_name' => $fullName,
            'email' => $email,
            'phone' => '+994' . fake()->randomElement(['50', '51', '55', '70', '77']) . fake()->numerify('#######'),
            'id_number' => strtoupper($fin),
            'license_number' => strtoupper(fake()->bothify('??######')),
            'license_expiry' => fake()->dateTimeBetween('now', '+5 years')->format('Y-m-d'),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'address' => fake()->randomElement(['Bakı', 'Gəncə', 'Sumqayıt', 'Mingəçevir', 'Naxçıvan']) . ', ' . fake()->streetAddress(),
            'blacklisted' => false,
        ];
    }
}
