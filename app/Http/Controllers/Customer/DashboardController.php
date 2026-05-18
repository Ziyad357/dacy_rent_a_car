<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;

        $stats = [
            'total_reservations' => $customer?->reservations()->count() ?? 0,
            'active_reservations' => $customer?->reservations()->where('status', 'active')->count() ?? 0,
            'pending_reservations' => $customer?->reservations()->where('status', 'pending')->count() ?? 0,
            'unpaid_penalties' => $customer?->reservations()
                ->with('contract.penalties')
                ->get()
                ->flatMap(fn ($r) => $r->contract?->penalties ?? collect())
                ->where('paid', false)
                ->sum('amount') ?? 0,
        ];

        $recentReservations = $customer?->reservations()
            ->with(['car'])
            ->latest()
            ->take(5)
            ->get() ?? collect();

        $activeReservation = $customer?->reservations()
            ->with(['car', 'contract'])
            ->where('status', 'active')
            ->latest()
            ->first();

        return view('customer.dashboard', compact('stats', 'recentReservations', 'activeReservation'));
    }
}
