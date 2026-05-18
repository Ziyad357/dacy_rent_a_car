<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load('customer');

        return view('customer.profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update(['name' => $data['name'], 'phone' => $data['phone'] ?? null]);

        if ($user->customer) {
            $user->customer->update(['address' => $data['address'] ?? $user->customer->address]);
        }

        return back()->with('success', 'Profil yeniləndi.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        auth()->user()->update(['password' => $request->password]);

        return back()->with('success', 'Şifrə dəyişdirildi.');
    }
}
