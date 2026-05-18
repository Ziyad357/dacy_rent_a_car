<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with('reservation.customer')->latest('paid_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }
        if ($request->filled('from')) {
            $query->where('paid_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('paid_at', '<=', $request->to . ' 23:59:59');
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:deposit,rental,penalty,refund',
            'method' => 'required|in:cash,card,transfer',
            'reference_number' => 'nullable|string|max:100',
            'paid_at' => 'required|date',
            'note' => 'nullable|string',
        ]);

        Payment::create($data);

        if ($data['type'] === 'deposit') {
            Reservation::find($data['reservation_id'])?->update(['deposit_paid' => true]);
        }

        return back()->with('success', 'Ödəniş qeyd edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
