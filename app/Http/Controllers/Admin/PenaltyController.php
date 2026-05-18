<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penalty;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function index(Request $request)
    {
        $query = Penalty::with(['contract.reservation.customer'])->latest();

        if ($request->filled('paid')) {
            $query->where('paid', $request->paid === '1');
        }

        $penalties = $query->paginate(15)->withQueryString();

        return view('admin.penalties.index', compact('penalties'));
    }

    public function show(Penalty $penalty)
    {
        $penalty->load('contract.reservation.customer');

        return view('admin.penalties.show', compact('penalty'));
    }

    public function markPaid(Penalty $penalty)
    {
        $penalty->update(['paid' => true, 'paid_at' => now()]);

        return back()->with('success', 'Cərimə ödəniş edilmiş kimi qeydiyyat edildi.');
    }
}
