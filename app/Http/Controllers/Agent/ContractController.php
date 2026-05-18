<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Reservation;
use App\Services\ContractService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function __construct(private readonly ContractService $contractService) {}

    public function index(Request $request)
    {
        $query = Contract::with(['reservation.car', 'reservation.customer'])->latest();

        if ($request->filled('search')) {
            $query->where('contract_number', 'like', "%{$request->search}%");
        }

        $contracts = $query->paginate(15)->withQueryString();

        return view('agent.contracts.index', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $contract->load(['reservation.car', 'reservation.customer', 'penalties', 'reservation.payments']);

        return view('agent.contracts.show', compact('contract'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'fuel_level_out' => 'required|in:full,three_quarters,half,quarter,empty',
            'mileage_out' => 'required|integer|min:0',
            'condition_out' => 'required|string',
            'signed_at' => 'nullable|date',
        ]);

        $reservation = Reservation::findOrFail($data['reservation_id']);

        if ($reservation->status !== 'approved') {
            return back()->withErrors(['reservation_id' => 'Rezervasiya təsdiqlənmiş olmalıdır.']);
        }

        $contract = $this->contractService->createFromReservation($data['reservation_id'], $data);

        return redirect()->route('agent.contracts.show', $contract)->with('success', 'Müqavilə yaradıldı.');
    }

    public function close(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'fuel_level_in' => 'required|in:full,three_quarters,half,quarter,empty',
            'mileage_in' => 'required|integer|min:0',
            'condition_in' => 'required|string',
            'returned_at' => 'nullable|date',
        ]);

        $this->contractService->closeContract($contract->id, $data);

        return back()->with('success', 'Müqavilə bağlandı. Cərimələr hesablandı.');
    }

    public function pdf(Contract $contract)
    {
        $contract->load(['reservation.car', 'reservation.customer', 'reservation.agent', 'penalties', 'reservation.payments']);
        $pdf = Pdf::loadView('pdf.contract', compact('contract'));

        return $pdf->download("contract-{$contract->contract_number}.pdf");
    }
}
