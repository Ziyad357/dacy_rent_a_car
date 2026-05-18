@extends('agent.layout')
@section('title', 'Rezervasiya #' . $reservation->id)

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('agent.reservations.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>

    @php $sb=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-indigo-500/20 text-indigo-400','active'=>'bg-green-500/20 text-green-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
    $sl=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv edilib']; @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="md:col-span-2 bg-gray-900 rounded-xl border border-gray-800 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-white">Rezervasiya məlumatları</h2>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $sb[$reservation->status]??'' }}">{{ $sl[$reservation->status]??$reservation->status }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-500">Müştəri</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->customer?->full_name }}</dd></div>
                <div><dt class="text-gray-500">Avtomobil</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->car?->brand }} {{ $reservation->car?->model }}</dd></div>
                <div><dt class="text-gray-500">Başlama</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->start_date->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Bitmə</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->end_date->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Gün sayı</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->total_days }}</dd></div>
                <div><dt class="text-gray-500">Cəmi məbləğ</dt><dd class="font-bold text-indigo-400 mt-0.5">{{ number_format($reservation->total_amount, 2) }} ₼</dd></div>
                <div><dt class="text-gray-500">Götürmə yeri</dt><dd class="text-gray-200 mt-0.5">{{ $reservation->pickup_location }}</dd></div>
                <div><dt class="text-gray-500">Qaytarma yeri</dt><dd class="text-gray-200 mt-0.5">{{ $reservation->return_location }}</dd></div>
            </dl>

            {{-- Status actions --}}
            @if(in_array($reservation->status, ['pending','approved']))
            <div class="mt-5 border-t border-gray-800 pt-5 flex gap-2">
                @if($reservation->status === 'pending')
                <form method="POST" action="{{ route('agent.reservations.status', $reservation) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-500 transition-colors">Təsdiqlə</button>
                </form>
                @endif
                <form method="POST" action="{{ route('agent.reservations.status', $reservation) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="bg-red-500/20 text-red-400 px-4 py-2 rounded-lg text-sm hover:bg-red-500/30 transition-colors" onclick="return confirm('Ləğv etmək istədiyinizə əminsiniz?')">Ləğv et</button>
                </form>
            </div>
            @endif

            {{-- Create contract button --}}
            @if($reservation->status === 'approved' && !$reservation->contract)
            <div class="mt-4 border-t border-gray-800 pt-4" x-data="{open:false}">
                <button @click="open=!open" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Müqavilə yarat</button>
                <form x-show="open" x-transition method="POST" action="{{ route('agent.contracts.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Yanacaq səviyyəsi (verilən) *</label>
                            <select name="fuel_level_out" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm">
                                <option value="full">Tam dolu</option>
                                <option value="three_quarters">3/4</option>
                                <option value="half">1/2</option>
                                <option value="quarter">1/4</option>
                                <option value="empty">Boş</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kilometraj *</label>
                            <input name="mileage_out" type="number" min="0" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Avtomobilin vəziyyəti *</label>
                        <textarea name="condition_out" rows="2" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm resize-none"></textarea>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Müqaviləni imzala</button>
                </form>
            </div>
            @elseif($reservation->contract)
            <div class="mt-4 border-t border-gray-800 pt-4">
                <div class="flex gap-3">
                    <a href="{{ route('agent.contracts.show', $reservation->contract) }}" class="bg-indigo-500/20 text-indigo-400 px-4 py-2 rounded-lg text-sm hover:bg-indigo-500/30 transition-colors">Müqaviləyə bax</a>
                    <a href="{{ route('agent.contracts.pdf', $reservation->contract) }}" class="bg-emerald-500/20 text-emerald-400 px-4 py-2 rounded-lg text-sm hover:bg-emerald-500/30 transition-colors">PDF yüklə</a>
                </div>
            </div>
            @endif
        </div>

        {{-- Payments --}}
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
            <h3 class="text-sm font-semibold text-gray-300 mb-3">Ödənişlər</h3>
            @forelse($reservation->payments as $p)
            <div class="flex justify-between text-xs py-2 border-b border-gray-800 last:border-0">
                <span class="capitalize text-gray-400">{{ $p->type }}</span>
                <span class="font-medium text-gray-200">{{ number_format($p->amount,2) }} ₼</span>
            </div>
            @empty
            <p class="text-xs text-gray-600">Ödəniş yoxdur</p>
            @endforelse

            <div x-data="{open:false}" class="mt-3">
                <button @click="open=!open" class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">+ Ödəniş əlavə et</button>
                <form x-show="open" x-transition method="POST" action="{{ route('admin.payments.store') }}" class="mt-2 space-y-2">
                    @csrf
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                    <input name="amount" type="number" step="0.01" placeholder="Məbləğ" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-600 rounded px-2 py-1.5 text-xs">
                    <select name="type" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded px-2 py-1.5 text-xs">
                        <option value="deposit">Depozit</option>
                        <option value="rental">İcarə</option>
                        <option value="penalty">Cərimə</option>
                        <option value="refund">Geri qayt.</option>
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
@endsection
