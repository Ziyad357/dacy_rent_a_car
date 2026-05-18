@extends('admin.layout')
@section('title', 'İstifadəçini Düzəlt')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6 space-y-5">
        <h2 class="text-lg font-semibold text-white">{{ $user->name }} — Düzəliş</h2>

        @if($errors->any())
            <div class="p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        {{-- Basic info --}}
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Ad Soyad</label>
                <input name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Telefon</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">Yadda saxla</button>
        </form>

        {{-- Role --}}
        <div class="border-t border-gray-800 pt-5">
            <h3 class="text-sm font-semibold text-gray-300 mb-3">Rol dəyiş</h3>
            <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex gap-2">
                @csrf @method('PATCH')
                <select name="role" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    <option value="admin" @selected($user->hasRole('admin'))>Admin</option>
                    <option value="agent" @selected($user->hasRole('agent'))>Agent</option>
                    <option value="customer" @selected($user->hasRole('customer'))>Müştəri</option>
                </select>
                <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">Dəyiş</button>
            </form>
        </div>

        {{-- Blacklist (only for customers) --}}
        @if($user->customer && !$user->customer->blacklisted)
        <div class="border-t border-gray-800 pt-5" x-data="{open:false}">
            <h3 class="text-sm font-semibold text-gray-300 mb-3">Qara siyahıya al</h3>
            <button @click="open=!open" class="text-sm text-red-400 hover:text-red-300 transition-colors">+ Qara siyahıya əlavə et</button>
            <form x-show="open" x-transition method="POST" action="{{ route('admin.users.blacklist', $user) }}" class="mt-3 space-y-2">
                @csrf @method('PATCH')
                <textarea name="blacklist_reason" rows="2" required placeholder="Səbəbi daxil edin..." class="w-full bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-600 rounded-lg px-3 py-2 text-sm resize-none"></textarea>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-500 transition-colors">Qara siyahıya al</button>
            </form>
        </div>
        @elseif($user->customer && $user->customer->blacklisted)
        <div class="border-t border-gray-800 pt-5">
            <div class="p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-sm text-red-400">
                <strong>Qara siyahıda:</strong> {{ $user->customer->blacklist_reason }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
