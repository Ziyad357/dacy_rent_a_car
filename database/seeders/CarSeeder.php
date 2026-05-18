<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            // ─── AVROPA ───────────────────────────────────────────────────────
            // Porsche — lüks (350-500)
            ['brand'=>'Porsche','model'=>'911 Carrera','tier'=>'lux'],
            ['brand'=>'Porsche','model'=>'Cayenne','tier'=>'lux'],
            ['brand'=>'Porsche','model'=>'Macan','tier'=>'premium'],
            ['brand'=>'Porsche','model'=>'Cayenne GTS','tier'=>'lux'],
            ['brand'=>'Porsche','model'=>'Panamera','tier'=>'lux'],

            // Land Rover — lüks
            ['brand'=>'Land Rover','model'=>'Defender 110','tier'=>'lux'],
            ['brand'=>'Land Rover','model'=>'Discovery','tier'=>'premium'],
            ['brand'=>'Land Rover','model'=>'Range Rover Sport','tier'=>'lux'],
            ['brand'=>'Land Rover','model'=>'Range Rover Vogue','tier'=>'lux'],
            ['brand'=>'Land Rover','model'=>'Freelander','tier'=>'premium'],

            // BMW — premium/lüks
            ['brand'=>'BMW','model'=>'X5 xDrive40i','tier'=>'premium'],
            ['brand'=>'BMW','model'=>'X6 M50i','tier'=>'lux'],
            ['brand'=>'BMW','model'=>'X7 xDrive50i','tier'=>'lux'],
            ['brand'=>'BMW','model'=>'M3 Competition','tier'=>'lux'],
            ['brand'=>'BMW','model'=>'M5 Competition','tier'=>'lux'],
            ['brand'=>'BMW','model'=>'5 Series 530i','tier'=>'premium'],
            ['brand'=>'BMW','model'=>'3 Series 320i','tier'=>'orta'],

            // Mercedes — premium/lüks
            ['brand'=>'Mercedes','model'=>'GLE 450','tier'=>'lux'],
            ['brand'=>'Mercedes','model'=>'GLS 580','tier'=>'lux'],
            ['brand'=>'Mercedes','model'=>'S-Class S500','tier'=>'lux'],
            ['brand'=>'Mercedes','model'=>'AMG GT 63','tier'=>'lux'],
            ['brand'=>'Mercedes','model'=>'E-Class E300','tier'=>'premium'],
            ['brand'=>'Mercedes','model'=>'C-Class C200','tier'=>'orta'],
            ['brand'=>'Mercedes','model'=>'GLC 300','tier'=>'premium'],

            // Audi — premium/lüks
            ['brand'=>'Audi','model'=>'Q7 55 TFSI','tier'=>'lux'],
            ['brand'=>'Audi','model'=>'Q8 60 TFSI e','tier'=>'lux'],
            ['brand'=>'Audi','model'=>'A6 45 TFSI','tier'=>'premium'],
            ['brand'=>'Audi','model'=>'A8 L 60 TFSI','tier'=>'lux'],
            ['brand'=>'Audi','model'=>'RS6 Avant','tier'=>'lux'],
            ['brand'=>'Audi','model'=>'Q5 40 TDI','tier'=>'orta'],
            ['brand'=>'Audi','model'=>'A4 35 TFSI','tier'=>'orta'],

            // Volkswagen — orta
            ['brand'=>'Volkswagen','model'=>'Touareg 3.0 TDI','tier'=>'premium'],
            ['brand'=>'Volkswagen','model'=>'Passat B8','tier'=>'orta'],
            ['brand'=>'Volkswagen','model'=>'Tiguan R-Line','tier'=>'orta'],

            // Volvo — premium
            ['brand'=>'Volvo','model'=>'XC90 T8','tier'=>'lux'],
            ['brand'=>'Volvo','model'=>'XC60 T6','tier'=>'premium'],
            ['brand'=>'Volvo','model'=>'S90 T8','tier'=>'premium'],

            // ─── YAPONIYA ─────────────────────────────────────────────────────
            // Toyota — büdcə/orta/lüks
            ['brand'=>'Toyota','model'=>'Land Cruiser 200','tier'=>'lux'],
            ['brand'=>'Toyota','model'=>'Land Cruiser 300','tier'=>'lux'],
            ['brand'=>'Toyota','model'=>'Land Cruiser Prado','tier'=>'premium'],
            ['brand'=>'Toyota','model'=>'Alphard 3.5','tier'=>'lux'],
            ['brand'=>'Toyota','model'=>'Supra GR','tier'=>'lux'],
            ['brand'=>'Toyota','model'=>'Camry 2.5','tier'=>'orta'],
            ['brand'=>'Toyota','model'=>'RAV4 Hybrid','tier'=>'orta'],
            ['brand'=>'Toyota','model'=>'Hilux','tier'=>'orta'],

            // Lexus — lüks
            ['brand'=>'Lexus','model'=>'LX 570','tier'=>'lux'],
            ['brand'=>'Lexus','model'=>'RX 350','tier'=>'premium'],
            ['brand'=>'Lexus','model'=>'ES 300h','tier'=>'premium'],
            ['brand'=>'Lexus','model'=>'GX 460','tier'=>'lux'],
            ['brand'=>'Lexus','model'=>'NX 300h','tier'=>'premium'],

            // Nissan — orta/lüks
            ['brand'=>'Nissan','model'=>'Patrol Y62','tier'=>'lux'],
            ['brand'=>'Nissan','model'=>'GT-R','tier'=>'lux'],
            ['brand'=>'Nissan','model'=>'Qashqai','tier'=>'budce'],
            ['brand'=>'Nissan','model'=>'X-Trail','tier'=>'orta'],

            // Honda — büdcə/orta
            ['brand'=>'Honda','model'=>'CR-V','tier'=>'orta'],
            ['brand'=>'Honda','model'=>'Pilot','tier'=>'premium'],
            ['brand'=>'Honda','model'=>'Accord','tier'=>'orta'],
            ['brand'=>'Honda','model'=>'Civic','tier'=>'budce'],

            // ─── KOREYA ───────────────────────────────────────────────────────
            ['brand'=>'Hyundai','model'=>'Palisade','tier'=>'premium'],
            ['brand'=>'Hyundai','model'=>'Santa Fe','tier'=>'orta'],
            ['brand'=>'Hyundai','model'=>'Tucson N Line','tier'=>'orta'],
            ['brand'=>'Hyundai','model'=>'Elantra','tier'=>'budce'],
            ['brand'=>'Kia','model'=>'Telluride','tier'=>'premium'],
            ['brand'=>'Kia','model'=>'Sorento','tier'=>'orta'],
            ['brand'=>'Kia','model'=>'Stinger GT','tier'=>'premium'],
            ['brand'=>'Kia','model'=>'Sportage','tier'=>'budce'],

            // ─── AMERİKA ──────────────────────────────────────────────────────
            ['brand'=>'Ford','model'=>'Mustang GT','tier'=>'premium'],
            ['brand'=>'Ford','model'=>'Explorer','tier'=>'orta'],
            ['brand'=>'Ford','model'=>'F-150 Raptor','tier'=>'lux'],
            ['brand'=>'Chevrolet','model'=>'Tahoe','tier'=>'premium'],
            ['brand'=>'Chevrolet','model'=>'Suburban','tier'=>'lux'],
            ['brand'=>'Chevrolet','model'=>'Camaro SS','tier'=>'premium'],
            ['brand'=>'Cadillac','model'=>'Escalade','tier'=>'lux'],
            ['brand'=>'Cadillac','model'=>'CT5-V','tier'=>'lux'],

            // ─── ÇİN ──────────────────────────────────────────────────────────
            ['brand'=>'Chery','model'=>'Tiggo 8 Pro','tier'=>'orta'],
            ['brand'=>'Chery','model'=>'Arrizo 8','tier'=>'budce'],
            ['brand'=>'Chery','model'=>'Tiggo 7 Pro','tier'=>'budce'],
            ['brand'=>'Geely','model'=>'Coolray','tier'=>'budce'],
            ['brand'=>'Geely','model'=>'Atlas Pro','tier'=>'orta'],
            ['brand'=>'Geely','model'=>'Monjaro','tier'=>'orta'],
            ['brand'=>'BYD','model'=>'Han EV','tier'=>'premium'],
            ['brand'=>'BYD','model'=>'Tang EV','tier'=>'premium'],
            ['brand'=>'BYD','model'=>'Atto 3','tier'=>'orta'],
            ['brand'=>'BYD','model'=>'Seal','tier'=>'premium'],
            ['brand'=>'Haval','model'=>'H6','tier'=>'budce'],
            ['brand'=>'Haval','model'=>'H9','tier'=>'orta'],
            ['brand'=>'Haval','model'=>'Jolion Pro','tier'=>'budce'],
            ['brand'=>'OMODA','model'=>'C5','tier'=>'budce'],
            ['brand'=>'OMODA','model'=>'C9','tier'=>'orta'],
            ['brand'=>'Jaecoo','model'=>'J7','tier'=>'budce'],
            ['brand'=>'Jaecoo','model'=>'J8','tier'=>'orta'],
            ['brand'=>'Changan','model'=>'CS75 Plus','tier'=>'orta'],
            ['brand'=>'Changan','model'=>'Uni-K','tier'=>'orta'],
            ['brand'=>'Changan','model'=>'Uni-Z','tier'=>'budce'],
            ['brand'=>'Li Auto','model'=>'L7','tier'=>'premium'],
            ['brand'=>'Li Auto','model'=>'L9','tier'=>'lux'],
            ['brand'=>'Nio','model'=>'ES8','tier'=>'lux'],
            ['brand'=>'Nio','model'=>'ET7','tier'=>'lux'],
            ['brand'=>'Nio','model'=>'ES6','tier'=>'premium'],
        ];

        $tiers = [
            'budce'   => ['rate' => [60, 65, 70, 75, 80],   'fuel' => ['petrol', 'diesel']],
            'orta'    => ['rate' => [100, 110, 120, 130, 150], 'fuel' => ['petrol', 'diesel', 'hybrid']],
            'premium' => ['rate' => [200, 220, 250, 280, 300], 'fuel' => ['petrol', 'hybrid']],
            'lux'     => ['rate' => [350, 380, 400, 450, 500], 'fuel' => ['petrol', 'hybrid', 'electric']],
        ];

        $colors = ['Ağ', 'Qara', 'Gümüşü', 'Boz', 'Tünd Boz', 'Mavi', 'Tünd Mavi', 'Qırmızı', 'Bej', 'Yaşıl'];
        $statuses = ['available', 'available', 'available', 'rented', 'reserved', 'maintenance'];
        $transmissions = ['automatic', 'automatic', 'automatic', 'manual'];

        $platePrefixes = ['10', '77', '99', '90'];
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        foreach ($cars as $car) {
            $tier = $tiers[$car['tier']];
            $dailyRate = fake()->randomElement($tier['rate']);
            $plate = fake()->randomElement($platePrefixes) . '-'
                . $letters[random_int(0, 25)]
                . $letters[random_int(0, 25)]
                . '-' . fake()->numberBetween(100, 999);

            \App\Models\Car::create([
                'brand'          => $car['brand'],
                'model'          => $car['model'],
                'year'           => fake()->numberBetween(2020, 2024),
                'plate_number'   => $plate,
                'color'          => fake()->randomElement($colors),
                'fuel_type'      => fake()->randomElement($tier['fuel']),
                'transmission'   => fake()->randomElement($transmissions),
                'seats'          => in_array($car['brand'], ['Li Auto','Nio','BYD','Alphard']) ? 7 : fake()->randomElement([4, 5, 5]),
                'mileage'        => fake()->numberBetween(0, 50000),
                'daily_rate'     => $dailyRate,
                'deposit_amount' => $dailyRate * 5,
                'status'         => fake()->randomElement($statuses),
            ]);
        }

        $this->command->info('✓ ' . count($cars) . ' avtomobil əlavə edildi (Cəmi: ' . \App\Models\Car::count() . ')');
    }
}
