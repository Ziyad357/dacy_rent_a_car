<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::with('user')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                    ->orWhere('id_number', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('agent.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agent.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'id_number' => 'required|string|max:20|unique:customers,id_number',
            'license_number' => 'required|string|max:50|unique:customers,license_number',
            'license_expiry' => 'required|date|after:today',
            'date_of_birth' => 'required|date|before:-18 years',
            'address' => 'required|string|max:500',
        ]);

        Customer::create($data);

        return redirect()->route('agent.customers.index')->with('success', 'Müştəri əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load('reservations.car');

        return view('agent.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('agent.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'id_number' => 'required|string|max:20|unique:customers,id_number,' . $customer->id,
            'license_number' => 'required|string|max:50|unique:customers,license_number,' . $customer->id,
            'license_expiry' => 'required|date',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:500',
        ]);

        $customer->update($data);

        return redirect()->route('agent.customers.index')->with('success', 'Müştəri yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
