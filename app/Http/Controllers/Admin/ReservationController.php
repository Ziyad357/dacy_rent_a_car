<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Reservation;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availability,
        private readonly PricingService $pricing,
    ) {}

    public function index(Request $request)
    {
        $query = Reservation::with(['car', 'customer', 'agent'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('car_id')) {
            $query->where('car_id', $request->car_id);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('from')) {
            $query->where('start_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('end_date', '<=', $request->to);
        }

        $reservations = $query->paginate(15)->withQueryString();
        $cars = Car::orderBy('brand')->get();
        $customers = Customer::orderBy('full_name')->get();

        return view('admin.reservations.index', compact('reservations', 'cars', 'customers'));
    }

    public function create()
    {
        $cars = Car::where('status', 'available')->orderBy('brand')->get();
        $customers = Customer::with('user')->orderBy('full_name')->get();

        return view('admin.reservations.create', compact('cars', 'customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'customer_id' => 'required|exists:customers,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $start = \Carbon\Carbon::parse($data['start_date']);
        $end = \Carbon\Carbon::parse($data['end_date']);

        if (! $this->availability->checkAvailability($data['car_id'], $start, $end)) {
            return back()->withErrors(['car_id' => 'Seçilmiş tarixdə avtomobil mövcud deyil.'])->withInput();
        }

        $pricing = $this->pricing->calculate($data['car_id'], $start, $end, $data['discount_percent'] ?? 0);

        Reservation::create([
            ...$data,
            'agent_id' => auth()->id(),
            'status' => 'pending',
            'total_days' => $pricing['total_days'],
            'daily_rate' => $pricing['daily_rate'],
            'subtotal' => $pricing['subtotal'],
            'discount_percent' => $data['discount_percent'] ?? 0,
            'discount_amount' => $pricing['discount_amount'],
            'total_amount' => $pricing['total_amount'],
        ]);

        return redirect()->route('admin.reservations.index')->with('success', 'Rezervasiya yaradıldı.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['car', 'customer', 'agent', 'contract.penalties', 'payments']);

        return view('admin.reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $cars = Car::orderBy('brand')->get();
        $customers = Customer::orderBy('full_name')->get();

        return view('admin.reservations.edit', compact('reservation', 'cars', 'customers'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $reservation->update($data);

        return redirect()->route('admin.reservations.index')->with('success', 'Rezervasiya yeniləndi.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return back()->with('success', 'Rezervasiya silindi.');
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate(['status' => 'required|in:pending,approved,active,completed,cancelled']);

        $data = ['status' => $request->status];

        if ($request->status === 'cancelled') {
            $request->validate(['cancellation_reason' => 'nullable|string']);
            $data['cancellation_reason'] = $request->cancellation_reason;

            if ($reservation->car) {
                $reservation->car->update(['status' => 'available']);
            }
        }

        $reservation->update($data);

        return back()->with('success', 'Status yeniləndi.');
    }
}
