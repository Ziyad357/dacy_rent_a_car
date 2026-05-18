@extends('admin.layout')
@section('title', 'Rezervasiya #' . $reservation->id)

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>

    @php
        $sb=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-indigo-500/20 text-indigo-400','active'=>'bg-green-500/20 text-green-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
        $sl=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv edilib'];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Main Info --}}
        <div class="lg:col-span-2 bg-gray-900 rounded-xl border border-gray-800 p-5">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-white">Rezervasiya məlumatları</h2>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $sb[$reservation->status]??'' }}">{{ $sl[$reservation->status]??$reservation->status }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Müştəri</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->customer?->full_name }}</dd></div>
                <div><dt class="text-gray-500">Agent</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->agent?->name ?? '-' }}</dd></div>
                <div><dt class="text-gray-500">Avtomobil</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->car?->brand }} {{ $reservation->car?->model }} ({{ $reservation->car?->plate_number }})</dd></div>
                <div><dt class="text-gray-500">Gündəlik qiymət</dt><dd class="font-medium text-gray-200 mt-0.5">{{ number_format($reservation->daily_rate, 2) }} ₼</dd></div>
                <div><dt class="text-gray-500">Başlama</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->start_date->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Bitmə</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->end_date->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Gün sayı</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->total_days }}</dd></div>
                <div><dt class="text-gray-500">Endirim</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->discount_percent }}% ({{ number_format($reservation->discount_amount, 2) }} ₼)</dd></div>
                <div><dt class="text-gray-500">Cəmi məbləğ</dt><dd class="font-bold text-indigo-400 mt-0.5 text-base">{{ number_format($reservation->total_amount, 2) }} ₼</dd></div>
                <div><dt class="text-gray-500">Depozit</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->deposit_paid ? 'Ödənilib' : 'Ödənilməyib' }}</dd></div>
                <div><dt class="text-gray-500">Götürmə yeri</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->pickup_location }}</dd></div>
                <div><dt class="text-gray-500">Qaytarma yeri</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->return_location }}</dd></div>
                @if($reservation->notes)
                <div class="col-span-2"><dt class="text-gray-500">Qeyd</dt><dd class="mt-0.5">{{ $reservation->notes }}</dd></div>
                @endif
            </dl>

            {{-- Status change --}}
            @if(!in_array($reservation->status, ['completed','cancelled']))
            <div class="mt-5 border-t border-gray-800 pt-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-3">Status dəyiş</h3>
                <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}" class="flex gap-2">
                    @csrf @method('PATCH')
                    <select name="status" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                        <option value="pending" @selected($reservation->status=='pending')>Gözləyir</option>
                        <option value="approved" @selected($reservation->status=='approved')>Təsdiqlənib</option>
                        <option value="cancelled">Ləğv et</option>
                    </select>
                    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">Yadda saxla</button>
                </form>
            </div>
            @endif
        </div>

        <div class="space-y-5">
            {{-- Contract --}}
            @if($reservation->contract)
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-3">Müqavilə</h3>
                <p class="text-sm font-mono font-bold text-indigo-400">{{ $reservation->contract->contract_number }}</p>
                <p class="text-xs text-gray-500 mt-1">İmzalandı: {{ $reservation->contract->signed_at?->format('d.m.Y') }}</p>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('admin.contracts.show', $reservation->contract) }}" class="text-sm text-blue-600 hover:underline">Bax</a>
                    <a href="{{ route('admin.contracts.pdf', $reservation->contract) }}" class="text-sm text-green-600 hover:underline">PDF</a>
                </div>
            </div>
            @endif

            {{-- Payments --}}
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-3">Ödənişlər</h3>
                @forelse($reservation->payments as $p)
                <div class="flex justify-between text-xs py-2 border-b border-gray-800 last:border-0">
                    <span class="text-gray-400 capitalize">{{ $p->type }}</span>
                    <span class="font-medium">{{ number_format($p->amount,2) }} ₼</span>
                </div>
                @empty
                <p class="text-xs text-gray-600">Ödəniş yoxdur</p>
                @endforelse

                {{-- Add payment --}}
                <div x-data="{open:false}" class="mt-3">
                    <button @click="open=!open" class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">+ Ödəniş əlavə et</button>
                    <form x-show="open" x-transition method="POST" action="{{ route('admin.payments.store') }}" class="mt-2 space-y-2">
                        @csrf
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                        <input name="amount" type="number" step="0.01" placeholder="Məbləğ" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-500 rounded px-2 py-1.5 text-xs">
                        <select name="type" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded px-2 py-1.5 text-xs">
                            <option value="deposit">Depozit</option>
                            <option value="rental">İcarə</option>
                            <option value="penalty">Cərimə</option>
                            <option value="refund">Geri qaytarma</option>
                        </select>
                        <select name="method" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded px-2 py-1.5 text-xs">
                            <option value="cash">Nağd</option>
                            <option value="card">Kart</option>
                            <option value="transfer">Transfer</option>
                        </select>
                        <input name="paid_at" type="date" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded px-2 py-1.5 text-xs">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-1.5 rounded text-xs hover:bg-indigo-500 transition-colors">Əlavə et</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
