<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->role));
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = ['admin', 'agent', 'customer'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,agent,customer',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        $user->assignRole($data['role']);

        return redirect()->route('admin.users.index')->with('success', 'İstifadəçi əlavə edildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles', 'customer');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'İstifadəçi yeniləndi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'İstifadəçi silindi.');
    }

    /**
     * Update the role of the specified resource in storage.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,agent,customer']);
        $user->syncRoles([$request->role]);

        return back()->with('success', 'Rol yeniləndi.');
    }

    /**
     * Toggle the active status of the specified resource in storage.
     */
    public function toggleActive(User $user)
    {
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Status dəyişdirildi.');
    }

    /**
     * Blacklist the specified resource in storage.
     */
    public function blacklist(Request $request, User $user)
    {
        $request->validate(['blacklist_reason' => 'required|string|max:500']);

        if ($user->customer) {
            $user->customer->update([
                'blacklisted' => true,
                'blacklist_reason' => $request->blacklist_reason,
            ]);
        }

        return back()->with('success', 'Müştəri qara siyahıya alındı.');
    }
}
