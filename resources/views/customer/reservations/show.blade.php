@extends('customer.layout')
@section('title', 'Rezervasiya #' . $reservation->id)

@section('content')
<a href="{{ route('customer.reservations.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-5">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Geri
</a>

@php $sb=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-blue-500/20 text-blue-400','active'=>'bg-emerald-500/20 text-emerald-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
$sl=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv edilib']; @endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    {{-- Main --}}
    <div class="md:col-span-2 space-y-5">
        <div class="theme-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-base font-semibold text-gray-200">Rezervasiya məlumatları</h2>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $sb[$reservation->status]??'' }}">{{ $sl[$reservation->status]??$reservation->status }}</span>
            </div>
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Avtomobil</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->car?->brand }} {{ $reservation->car?->model }}</dd></div>
                <div><dt class="text-gray-500">Dövlət nömrəsi</dt><dd class="font-mono text-gray-200 mt-0.5">{{ $reservation->car?->plate_number }}</dd></div>
                <div><dt class="text-gray-500">Başlama tarixi</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->start_date->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Bitmə tarixi</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->end_date->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Gün sayı</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $reservation->total_days }} gün</dd></div>
                <div><dt class="text-gray-500">Gündəlik qiymət</dt><dd class="font-medium text-gray-200 mt-0.5">{{ number_format($reservation->daily_rate, 2) }} ₼</dd></div>
                @if($reservation->discount_percent > 0)
                <div><dt class="text-gray-500">Endirim</dt><dd class="font-medium mt-0.5 text-emerald-400">{{ $reservation->discount_percent }}% (-{{ number_format($reservation->discount_amount, 2) }} ₼)</dd></div>
                @endif
                <div><dt class="text-gray-500">Cəmi məbləğ</dt><dd class="font-bold text-blue-400 text-base mt-0.5">{{ number_format($reservation->total_amount, 2) }} ₼</dd></div>
                <div><dt class="text-gray-500">Götürmə yeri</dt><dd class="text-gray-200 mt-0.5">{{ $reservation->pickup_location }}</dd></div>
                <div><dt class="text-gray-500">Qaytarma yeri</dt><dd class="text-gray-200 mt-0.5">{{ $reservation->return_location }}</dd></div>
            </dl>

            @if(in_array($reservation->status, ['pending','approved']))
            <div class="mt-5 border-t border-gray-800 pt-4">
                <form method="POST" action="{{ route('customer.reservations.cancel', $reservation) }}">
                    @csrf @method('PATCH')
                    <button type="submit" onclick="return confirm('Ləğv etmək istədiyinizə əminsiniz?')" class="text-sm text-red-400 hover:text-red-300 font-medium">
                        Rezervasiyanı ləğv et
                    </button>
                </form>
            </div>
            @endif
        </div>

        {{-- Contract --}}
        @if($reservation->contract)
        <div class="theme-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-gray-200">Müqavilə</h3>
                <a href="{{ route('customer.reservations.contract.pdf', $reservation) }}" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-emerald-500 transition-colors">PDF yüklə</a>
            </div>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-500">Nömrə</dt><dd class="font-mono font-bold text-gray-200 mt-0.5">{{ $reservation->contract->contract_number }}</dd></div>
                <div><dt class="text-gray-500">İmzalandı</dt><dd class="text-gray-200 mt-0.5">{{ $reservation->contract->signed_at?->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Yanacaq (verilən)</dt><dd class="capitalize text-gray-200 mt-0.5">{{ $reservation->contract->fuel_level_out }}</dd></div>
                <div><dt class="text-gray-500">Km (verilən)</dt><dd class="text-gray-200 mt-0.5">{{ number_format($reservation->contract->mileage_out) }}</dd></div>
                @if($reservation->contract->returned_at)
                <div><dt class="text-gray-500">Qaytarlıldı</dt><dd class="font-medium text-emerald-400 mt-0.5">{{ $reservation->contract->returned_at->format('d.m.Y') }}</dd></div>
                @endif
            </dl>

            @if($reservation->contract->penalties->count())
            <div class="mt-4 border-t border-gray-800 pt-3">
                <p class="text-xs font-semibold text-gray-400 mb-2">Cərimələr</p>
                @foreach($reservation->contract->penalties as $p)
                <div class="flex justify-between text-xs py-1.5 border-b border-gray-800 last:border-0">
                    <span class="capitalize text-gray-400">{{ $p->type }}</span>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-red-600">{{ number_format($p->amount,2) }} ₼</span>
                        <span class="{{ $p->paid ? 'text-green-600' : 'text-orange-500' }} text-xs">{{ $p->paid ? '✓' : '⏳' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Payments --}}
    <div class="theme-card rounded-xl p-5">
        <h3 class="text-sm font-semibold text-gray-200 mb-3">Ödənişlər</h3>
        @forelse($reservation->payments as $p)
        @php $tc=['deposit'=>'bg-purple-500/20 text-purple-400','rental'=>'bg-blue-500/20 text-blue-400','penalty'=>'bg-red-500/20 text-red-400','refund'=>'bg-emerald-500/20 text-emerald-400'];
        $tl=['deposit'=>'Depozit','rental'=>'İcarə','penalty'=>'Cərimə','refund'=>'Geri qayt.']; @endphp
        <div class="flex items-center justify-between py-2.5 border-b border-gray-800 last:border-0">
            <div>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $tc[$p->type]??'bg-gray-500/20 text-gray-400' }}">{{ $tl[$p->type]??$p->type }}</span>
                <p class="text-xs text-gray-500 mt-0.5">{{ $p->paid_at?->format('d.m.Y') }}</p>
            </div>
            <span class="font-bold text-gray-200">{{ number_format($p->amount, 2) }} ₼</span>
        </div>
        @empty
        <p class="text-xs text-gray-600 text-center py-4">Ödəniş tapılmadı</p>
        @endforelse

        @if($reservation->payments->count())
        <div class="border-t border-gray-800 pt-3 mt-2 flex justify-between text-sm font-bold text-gray-200">
            <span>Cəmi ödəniş</span>
            <span>{{ number_format($reservation->payments->sum('amount'), 2) }} ₼</span>
        </div>
        @endif
    </div>
</div>
@endsection
