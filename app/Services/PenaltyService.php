<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Penalty;
use Carbon\Carbon;

class PenaltyService
{
    /**
     * Fuel level order for comparison (higher index = more fuel).
     */
    private const FUEL_ORDER = ['empty', 'quarter', 'half', 'three_quarters', 'full'];

    /**
     * Fuel penalty multipliers per one-step drop.
     *
     * Key: "from_level→to_level" transition multiplier.
     * Penalty is assessed per step down from the out level.
     */
    private const FUEL_PENALTIES = [
        'full_to_three_quarters' => 0.10,
        'three_quarters_to_half' => 0.20,
        'half_to_quarter' => 0.30,
        'quarter_to_empty' => 0.40,
    ];

    /**
     * Calculate and persist a late return penalty.
     *
     * Each overdue day costs: daily_rate × 0.5
     */
    public function lateReturn(int $contractId, Carbon $actualReturn): Penalty
    {
        $contract = Contract::with('reservation')->findOrFail($contractId);
        $reservation = $contract->reservation;

        $expectedReturn = Carbon::parse($reservation->end_date)->endOfDay();
        $lateDays = (int) $expectedReturn->diffInDays($actualReturn, false);

        if ($lateDays <= 0) {
            $lateDays = 0;
        }

        $dailyRate = (float) $reservation->daily_rate;
        $amount = round($dailyRate * 0.5 * $lateDays, 2);

        return Penalty::create([
            'contract_id' => $contractId,
            'type' => 'late_return',
            'description' => "Gecikmə cəriməsi: {$lateDays} gün × {$dailyRate} × 0.5",
            'amount' => $amount,
        ]);
    }

    /**
     * Calculate fuel penalty amount based on fuel level difference.
     *
     * full → 3/4     : daily_rate × 0.10
     * 3/4  → 1/2     : daily_rate × 0.20
     * 1/2  → 1/4     : daily_rate × 0.30
     * 1/4  → empty   : daily_rate × 0.40
     */
    public function fuelPenalty(string $fuelOut, string $fuelIn, float $dailyRate): float
    {
        $outIndex = array_search($fuelOut, self::FUEL_ORDER);
        $inIndex = array_search($fuelIn, self::FUEL_ORDER);

        if ($outIndex === false || $inIndex === false || $inIndex >= $outIndex) {
            return 0.0;
        }

        $multiplierMap = [
            'full' => ['three_quarters' => 0.10, 'half' => 0.30, 'quarter' => 0.60, 'empty' => 1.00],
            'three_quarters' => ['half' => 0.20, 'quarter' => 0.50, 'empty' => 0.90],
            'half' => ['quarter' => 0.30, 'empty' => 0.70],
            'quarter' => ['empty' => 0.40],
        ];

        $multiplier = $multiplierMap[$fuelOut][$fuelIn] ?? 0.0;

        return round($dailyRate * $multiplier, 2);
    }

    /**
     * Create a damage penalty record.
     */
    public function damagePenalty(int $contractId, string $description, float $amount): Penalty
    {
        return Penalty::create([
            'contract_id' => $contractId,
            'type' => 'damage',
            'description' => $description,
            'amount' => round($amount, 2),
        ]);
    }

    /**
     * Create and persist a fuel penalty for a contract.
     */
    public function createFuelPenalty(int $contractId, string $fuelOut, string $fuelIn, float $dailyRate): ?Penalty
    {
        $amount = $this->fuelPenalty($fuelOut, $fuelIn, $dailyRate);

        if ($amount <= 0) {
            return null;
        }

        return Penalty::create([
            'contract_id' => $contractId,
            'type' => 'fuel',
            'description' => "Yanacaq cəriməsi: {$fuelOut} → {$fuelIn}",
            'amount' => $amount,
        ]);
    }
}
