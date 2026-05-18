<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Reservation;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availability,
        private readonly PricingService $pricing,
    ) {}

    public function index(Request $request)
    {
        $query = Reservation::with(['car', 'customer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->paginate(15)->withQueryString();

        return view('agent.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $cars = Car::where('status', 'available')->orderBy('brand')->get();
        $customers = Customer::where('blacklisted', false)->orderBy('full_name')->get();

        return view('agent.reservations.create', compact('cars', 'customers'));
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

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);

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

        return redirect()->route('agent.reservations.index')->with('success', 'Rezervasiya yaradıldı.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['car', 'customer', 'contract.penalties', 'payments']);

        return view('agent.reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $cars = Car::orderBy('brand')->get();
        $customers = Customer::orderBy('full_name')->get();

        return view('agent.reservations.edit', compact('reservation', 'cars', 'customers'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $reservation->update($data);

        return redirect()->route('agent.reservations.index')->with('success', 'Rezervasiya yeniləndi.');
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate(['status' => 'required|in:pending,approved,cancelled']);
        $reservation->update(['status' => $request->status]);

        return back()->with('success', 'Status yeniləndi.');
    }

    public function calculatePrice(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $pricing = $this->pricing->calculate(
            $data['car_id'],
            Carbon::parse($data['start_date']),
            Carbon::parse($data['end_date']),
            $data['discount_percent'] ?? 0
        );

        return response()->json($pricing);
    }
}
