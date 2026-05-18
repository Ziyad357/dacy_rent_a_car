<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

class PenaltyController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;

        $penalties = $customer?->reservations()
            ->with('contract.penalties')
            ->get()
            ->flatMap(fn ($r) => $r->contract?->penalties ?? collect())
            ->sortByDesc('created_at')
            ->values() ?? collect();

        return view('customer.penalties.index', compact('penalties'));
    }
}
