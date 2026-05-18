<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContractService
{
    public function __construct(
        private readonly PenaltyService $penaltyService,
    ) {}

    /**
     * Generate the next contract number in format CNT-YYYY-XXXXX.
     */
    public function generateContractNumber(): string
    {
        $year = now()->year;
        $prefix = "CNT-{$year}-";

        $last = Contract::where('contract_number', 'like', "{$prefix}%")
            ->orderByDesc('contract_number')
            ->value('contract_number');

        $sequence = $last
            ? (int) substr($last, strlen($prefix)) + 1
            : 1;

        return $prefix . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new contract from an approved reservation.
     *
     * @param  array{fuel_level_out: string, mileage_out: int, condition_out: string, signed_at?: string}  $data
     */
    public function createFromReservation(int $reservationId, array $data): Contract
    {
        $reservation = Reservation::findOrFail($reservationId);

        return DB::transaction(function () use ($reservation, $data): Contract {
            $contract = Contract::create([
                'reservation_id' => $reservation->id,
                'contract_number' => $this->generateContractNumber(),
                'signed_at' => $data['signed_at'] ?? now(),
                'fuel_level_out' => $data['fuel_level_out'],
                'mileage_out' => $data['mileage_out'],
                'condition_out' => $data['condition_out'],
            ]);

            $reservation->update(['status' => 'active']);
            $reservation->car->update(['status' => 'rented']);

            return $contract;
        });
    }

    /**
     * Close a contract on vehicle return.
     *
     * Automatically calculates late return and fuel penalties.
     *
     * @param  array{fuel_level_in: string, mileage_in: int, condition_in: string, returned_at?: string}  $returnData
     */
    public function closeContract(int $contractId, array $returnData): Contract
    {
        $contract = Contract::with('reservation.car')->findOrFail($contractId);
        $reservation = $contract->reservation;

        $returnedAt = isset($returnData['returned_at'])
            ? Carbon::parse($returnData['returned_at'])
            : now();

        return DB::transaction(function () use ($contract, $reservation, $returnData, $returnedAt): Contract {
            $contract->update([
                'fuel_level_in' => $returnData['fuel_level_in'],
                'mileage_in' => $returnData['mileage_in'],
                'condition_in' => $returnData['condition_in'],
                'returned_at' => $returnedAt,
            ]);

            $expectedReturn = Carbon::parse($reservation->end_date)->endOfDay();
            if ($returnedAt->greaterThan($expectedReturn)) {
                $this->penaltyService->lateReturn($contract->id, $returnedAt);
            }

            $this->penaltyService->createFuelPenalty(
                $contract->id,
                $contract->fuel_level_out,
                $returnData['fuel_level_in'],
                (float) $reservation->daily_rate
            );

            $reservation->update(['status' => 'completed']);
            $reservation->car->update(['status' => 'available']);

            return $contract->fresh();
        });
    }
}
