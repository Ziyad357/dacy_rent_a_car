<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(private readonly AvailabilityService $availability) {}

    public function index(Request $request)
    {
        $query = Car::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('brand')) {
            $query->where('brand', 'like', "%{$request->brand}%");
        }

        $cars = $query->paginate(15)->withQueryString();

        return view('agent.cars.index', compact('cars'));
    }

    public function show(Car $car)
    {
        $car->load('maintenances', 'reservations.customer');

        return view('agent.cars.show', compact('car'));
    }

    public function checkAvailability(Request $request)
    {
        $data = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $available = $this->availability->checkAvailability(
            $data['car_id'],
            Carbon::parse($data['start_date']),
            Carbon::parse($data['end_date'])
        );

        return response()->json(['available' => $available]);
    }

    public function updateStatus(Request $request, Car $car)
    {
        $request->validate(['status' => 'required|in:available,maintenance,reserved']);
        $car->update(['status' => $request->status]);

        return back()->with('success', 'Status yeniləndi.');
    }
}
