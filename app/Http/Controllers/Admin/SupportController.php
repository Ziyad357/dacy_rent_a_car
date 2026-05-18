<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $conversations = User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))
            ->whereHas('supportMessages')
            ->withCount(['supportMessages as unread_count' => fn ($q) => $q->where('sender_role', 'customer')->whereNull('read_at')])
            ->with(['supportMessages' => fn ($q) => $q->latest()->limit(1)])
            ->orderByDesc('unread_count')
            ->get();

        return view('admin.support.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $messages = SupportMessage::forUser($user->id)
            ->orderBy('created_at')
            ->get();

        SupportMessage::forUser($user->id)
            ->where('sender_role', 'customer')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.support.show', compact('user', 'messages'));
    }

    public function reply(Request $request, User $user)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        SupportMessage::create([
            'user_id'     => $user->id,
            'sender_role' => 'admin',
            'body'        => $request->body,
        ]);

        return back();
    }
}
