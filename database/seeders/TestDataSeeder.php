<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agent = \App\Models\User::role('agent')->first();
        if (! $agent) {
            $this->command->error('Agent tapılmadı. Əvvəlcə AdminSeeder işlədin.');

            return;
        }

        // ── 50 Müştəri ────────────────────────────────────────────────────────
        $this->command->info('50 müştəri yaradılır...');
        $customers = \App\Models\Customer::factory(50)->create();

        // ── 30 Avtomobil ──────────────────────────────────────────────────────
        $this->command->info('30 avtomobil yaradılır...');
        $cars = \App\Models\Car::factory(30)->create();

        // ── 100 Rezervasiya ───────────────────────────────────────────────────
        $this->command->info('100 rezervasiya yaradılır...');
        $reservations = collect();

        for ($i = 0; $i < 100; $i++) {
            $reservation = \App\Models\Reservation::factory()->create([
                'car_id' => $cars->random()->id,
                'customer_id' => $customers->random()->id,
                'agent_id' => $agent->id,
            ]);
            $reservations->push($reservation);
        }

        // ── 50 Müqavilə (completed/active rezervasiyalar üçün) ────────────────
        $this->command->info('50 müqavilə yaradılır...');
        $eligibleReservations = $reservations
            ->whereIn('status', ['active', 'completed', 'approved'])
            ->take(50);

        $contractCount = 0;
        foreach ($eligibleReservations as $reservation) {
            if ($contractCount >= 50) {
                break;
            }
            \App\Models\Contract::factory()->create([
                'reservation_id' => $reservation->id,
            ]);
            $contractCount++;
        }

        // Əgər eligible rezervasiya 50-dən azdırsa, qalanını completed rezervasiyalara qoş
        if ($contractCount < 50) {
            $needed = 50 - $contractCount;
            $remaining = $reservations->whereNotIn('status', ['cancelled'])->take($needed);
            foreach ($remaining as $reservation) {
                if (! \App\Models\Contract::where('reservation_id', $reservation->id)->exists()) {
                    \App\Models\Contract::factory()->create([
                        'reservation_id' => $reservation->id,
                    ]);
                }
            }
        }

        // ── 30 Ödəniş ────────────────────────────────────────────────────────
        $this->command->info('30 ödəniş yaradılır...');
        $payableReservations = $reservations->whereNotIn('status', ['cancelled'])->values();

        for ($i = 0; $i < 30; $i++) {
            \App\Models\Payment::factory()->create([
                'reservation_id' => $payableReservations->random()->id,
            ]);
        }

        // ── 20 Cərimə ────────────────────────────────────────────────────────
        $this->command->info('20 cərimə yaradılır...');
        $contracts = \App\Models\Contract::all();

        if ($contracts->count() > 0) {
            for ($i = 0; $i < 20; $i++) {
                \App\Models\Penalty::factory()->create([
                    'contract_id' => $contracts->random()->id,
                ]);
            }
        }

        $this->command->info('');
        $this->command->info('✓ Test datası uğurla yaradıldı:');
        $this->command->info('  - Müştəri: ' . \App\Models\Customer::count());
        $this->command->info('  - Avtomobil: ' . \App\Models\Car::count());
        $this->command->info('  - Rezervasiya: ' . \App\Models\Reservation::count());
        $this->command->info('  - Müqavilə: ' . \App\Models\Contract::count());
        $this->command->info('  - Ödəniş: ' . \App\Models\Payment::count());
        $this->command->info('  - Cərimə: ' . \App\Models\Penalty::count());
    }
}
