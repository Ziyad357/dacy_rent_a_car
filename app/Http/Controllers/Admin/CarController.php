<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::withTrashed()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('brand')) {
            $query->where('brand', 'like', "%{$request->brand}%");
        }
        if ($request->filled('fuel_type')) {
            $query->where('fuel_type', $request->fuel_type);
        }

        $cars = $query->paginate(15)->withQueryString();

        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'plate_number' => 'required|string|unique:cars,plate_number',
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'transmission' => 'required|in:manual,automatic',
            'seats' => 'required|integer|min:1|max:20',
            'mileage' => 'required|integer|min:0',
            'daily_rate' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,reserved',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        Car::create($data);

        return redirect()->route('admin.cars.index')->with('success', 'Avtomobil əlavə edildi.');
    }

    public function show(Car $car)
    {
        $car->load('maintenances', 'reservations.customer');

        return view('admin.cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $data = $request->validate([
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'year' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'plate_number' => 'required|string|unique:cars,plate_number,' . $car->id,
            'color' => 'required|string|max:50',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',
            'transmission' => 'required|in:manual,automatic',
            'seats' => 'required|integer|min:1|max:20',
            'mileage' => 'required|integer|min:0',
            'daily_rate' => 'required|numeric|min:0',
            'deposit_amount' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,reserved',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        $car->update($data);

        return redirect()->route('admin.cars.index')->with('success', 'Avtomobil yeniləndi.');
    }

    public function destroy(Car $car)
    {
        $car->delete();

        return back()->with('success', 'Avtomobil silindi.');
    }

    public function updateStatus(Request $request, Car $car)
    {
        $request->validate(['status' => 'required|in:available,rented,maintenance,reserved']);
        $car->update(['status' => $request->status]);

        return back()->with('success', 'Status yeniləndi.');
    }

    public function storeMaintenance(Request $request, Car $car)
    {
        $data = $request->validate([
            'type' => 'required|in:routine,repair,inspection',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'started_at' => 'required|date',
            'completed_at' => 'nullable|date|after_or_equal:started_at',
        ]);

        $car->maintenances()->create($data);

        if (! $data['completed_at']) {
            $car->update(['status' => 'maintenance']);
        }

        return back()->with('success', 'Texniki xidmət əlavə edildi.');
    }
}
