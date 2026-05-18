<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $agentId = auth()->id();

        $stats = [
            'my_reservations_today' => Reservation::where('agent_id', $agentId)
                ->whereDate('created_at', today())
                ->count(),

            'active_reservations' => Reservation::where('agent_id', $agentId)
                ->where('status', 'active')
                ->count(),

            'pending_reservations' => Reservation::where('agent_id', $agentId)
                ->where('status', 'pending')
                ->count(),

            'available_cars' => Car::where('status', 'available')->count(),

            'total_customers' => Customer::whereHas('user', fn ($q) => $q->where('is_active', true))
                ->where('blacklisted', false)
                ->count(),

            'open_contracts' => Contract::whereNull('returned_at')
                ->whereHas('reservation', fn ($q) => $q->where('agent_id', $agentId))
                ->count(),
        ];

        $recentReservations = Reservation::with(['car', 'customer'])
            ->where('agent_id', $agentId)
            ->latest()
            ->take(5)
            ->get();

        $returningToday = Reservation::with(['car', 'customer'])
            ->where('status', 'active')
            ->whereDate('end_date', today())
            ->get();

        return view('agent.dashboard', compact('stats', 'recentReservations', 'returningToday'));
    }
}
