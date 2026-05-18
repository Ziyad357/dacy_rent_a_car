<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Reservation;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create(Request $request)
    {
        $start = $request->filled('start_date') ? Carbon::parse($request->start_date) : null;
        $end   = $request->filled('end_date')   ? Carbon::parse($request->end_date)   : null;

        $cars = collect();
        if ($start && $end && $end->gt($start)) {
            $cars = app(AvailabilityService::class)->availableCars($start, $end);
        }

        $selectedCar = null;
        $pricing     = null;
        if ($start && $end && $request->filled('car_id')) {
            $selectedCar = Car::find($request->car_id);
            if ($selectedCar) {
                $pricing = app(PricingService::class)->calculate($selectedCar->id, $start, $end);
            }
        }

        return view('customer.reservations.create', compact('cars', 'selectedCar', 'pricing', 'start', 'end'));
    }

    public function store(Request $request)
    {
        $customer = auth()->user()->customer;
        if (! $customer) {
            return back()->with('error', 'Müştəri profili tapılmadı.');
        }

        $request->validate([
            'car_id'          => 'required|exists:cars,id',
            'start_date'      => 'required|date|after_or_equal:today',
            'end_date'        => 'required|date|after:start_date',
            'pickup_location' => 'required|string|max:255',
            'return_location' => 'required|string|max:255',
            'notes'           => 'nullable|string|max:500',
        ]);

        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);

        if (! app(AvailabilityService::class)->checkAvailability($request->car_id, $start, $end)) {
            return back()->withInput()->with('error', 'Seçilən avtomobil bu tarixlər üçün mövcud deyil.');
        }

        $pricing = app(PricingService::class)->calculate($request->car_id, $start, $end);

        Reservation::create([
            'car_id'           => $request->car_id,
            'customer_id'      => $customer->id,
            'agent_id'         => null,
            'start_date'       => $start,
            'end_date'         => $end,
            'pickup_location'  => $request->pickup_location,
            'return_location'  => $request->return_location,
            'status'           => 'pending',
            'total_days'       => $pricing['total_days'],
            'daily_rate'       => $pricing['daily_rate'],
            'subtotal'         => $pricing['subtotal'],
            'discount_percent' => 0,
            'discount_amount'  => 0,
            'total_amount'     => $pricing['total_amount'],
            'deposit_paid'     => false,
            'notes'            => $request->notes,
        ]);

        return redirect()->route('customer.reservations.index')
            ->with('success', 'Rezervasiya uğurla göndərildi. Agent tərəfindən təsdiqlənəcək.');
    }

    public function index(Request $request)
    {
        $customer = auth()->user()->customer;

        $query = $customer?->reservations()->with(['car'])->latest();

        if ($request->filled('status')) {
            $query?->where('status', $request->status);
        }

        $reservations = $query?->paginate(10)->withQueryString() ?? collect();

        return view('customer.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $reservation->load(['car', 'contract.penalties', 'payments']);

        return view('customer.reservations.show', compact('reservation'));
    }

    public function cancel(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        if (! in_array($reservation->status, ['pending', 'approved'])) {
            return back()->with('error', 'Bu rezervasiyanı ləğv etmək mümkün deyil.');
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Müştəri tərəfindən ləğv edildi',
        ]);

        if ($reservation->car && $reservation->status !== 'rented') {
            $reservation->car->update(['status' => 'available']);
        }

        return back()->with('success', 'Rezervasiya ləğv edildi.');
    }

    public function contractPdf(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        if (! $reservation->contract) {
            abort(404, 'Müqavilə tapılmadı.');
        }

        $contract = $reservation->contract->load(['reservation.car', 'reservation.customer', 'reservation.agent', 'penalties', 'reservation.payments']);
        $pdf = Pdf::loadView('pdf.contract', compact('contract'));

        return $pdf->download("contract-{$contract->contract_number}.pdf");
    }

    private function authorizeReservation(Reservation $reservation): void
    {
        $customer = auth()->user()->customer;

        if ($reservation->customer_id !== $customer?->id) {
            abort(403);
        }
    }
}
