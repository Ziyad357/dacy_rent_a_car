<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
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

        return view('admin.contracts.index', compact('contracts'));
    }

    public function show(Contract $contract)
    {
        $contract->load(['reservation.car', 'reservation.customer', 'reservation.agent', 'penalties', 'reservation.payments']);

        return view('admin.contracts.show', compact('contract'));
    }

    public function pdf(Contract $contract)
    {
        $contract->load(['reservation.car', 'reservation.customer', 'reservation.agent', 'penalties', 'reservation.payments']);

        $pdf = Pdf::loadView('pdf.contract', compact('contract'));

        return $pdf->download("contract-{$contract->contract_number}.pdf");
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
}
