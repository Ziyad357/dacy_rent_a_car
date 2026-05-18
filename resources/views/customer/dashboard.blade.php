@extends('customer.layout')
@section('title', 'Ana Səhifə')

@section('content')
{{-- Welcome + CTA --}}
<div class="flex items-start justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">Xoş gəldiniz, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
        <p class="text-gray-500 text-sm mt-1">Rezervasiyalarınızı izləyin və yeni icarə sifariş edin.</p>
    </div>
    <a href="{{ route('customer.reservations.create') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/35 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Avtomobil icarə et
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @php
        $cards = [
            ['label'=>'Ümumi rezervasiya','value'=>$stats['total_reservations'],  'icon'=>'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4','ibg'=>'rgba(59,130,246,0.12)','glow'=>'rgba(59,130,246,0.15)','ic'=>'text-sky-400','val'=>'text-sky-400'],
            ['label'=>'Aktiv icarə',       'value'=>$stats['active_reservations'], 'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z','ibg'=>'rgba(16,185,129,0.12)','glow'=>'rgba(16,185,129,0.15)','ic'=>'text-emerald-400','val'=>'text-emerald-400'],
            ['label'=>'Gözləyən',          'value'=>$stats['pending_reservations'],'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','ibg'=>'rgba(245,158,11,0.12)','glow'=>'rgba(245,158,11,0.15)','ic'=>'text-amber-400','val'=>'text-amber-400'],
            ['label'=>'Ödənilməmiş cərimə','value'=>number_format($stats['unpaid_penalties'],2).' ₼','icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z','ibg'=>'rgba(239,68,68,0.12)','glow'=>'rgba(239,68,68,0.15)','ic'=>'text-red-400','val'=>'text-red-400'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="theme-card rounded-2xl p-5 flex items-center gap-4 transition-all duration-300 hover:-translate-y-0.5">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $card['ic'] }}"
             style="background: {{ $card['ibg'] }}; box-shadow: 0 0 16px {{ $card['glow'] }};">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $card['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 uppercase tracking-wider">{{ $card['label'] }}</p>
            <p class="text-xl font-bold {{ $card['val'] }} mt-0.5">{{ $card['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Active Reservation Banner --}}
@if($activeReservation)
<div class="relative overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl p-6 mb-6 shadow-lg shadow-blue-500/20">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-white/5 rounded-full translate-y-1/2"></div>
    <div class="relative flex items-center justify-between flex-wrap gap-4">
        <div>
            <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-1">Aktiv İcarə</p>
            <h3 class="text-2xl font-bold">{{ $activeReservation->car?->brand }} {{ $activeReservation->car?->model }}</h3>
            <div class="flex items-center gap-2 mt-2 text-blue-100 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $activeReservation->start_date->format('d.m.Y') }} – {{ $activeReservation->end_date->format('d.m.Y') }}
                · {{ $activeReservation->total_days }} gün
            </div>
            @if($activeReservation->end_date->isPast())
            <span class="mt-2 inline-block px-3 py-1 bg-red-500 text-white text-xs rounded-full font-semibold">⚠ Müddəti keçib</span>
            @elseif($activeReservation->end_date->isToday())
            <span class="mt-2 inline-block px-3 py-1 bg-amber-400 text-amber-900 text-xs rounded-full font-semibold">Bu gün qaytarılmalıdır</span>
            @endif
        </div>
        <div class="text-right">
            <p class="text-blue-200 text-xs">Cəmi məbləğ</p>
            <p class="text-3xl font-bold mt-1">{{ number_format($activeReservation->total_amount, 2) }} ₼</p>
            @if($activeReservation->contract)
            <a href="{{ route('customer.reservations.contract.pdf', $activeReservation) }}"
               class="mt-3 inline-flex items-center gap-1.5 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-4 py-2 rounded-xl text-xs font-medium transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Müqaviləni yüklə
            </a>
            @endif
        </div>
    </div>
</div>
@endif

{{-- Recent Reservations --}}
<div class="theme-card rounded-2xl p-5">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-sm font-semibold text-gray-200 tracking-wide">Son Rezervasiyalar</h3>
        <a href="{{ route('customer.reservations.index') }}" class="text-xs text-blue-400 hover:text-blue-300 transition-colors">Hamısına bax →</a>
    </div>
    <div class="divide-y divide-gray-800">
        @forelse($recentReservations as $r)
        @php
            $sb=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-blue-500/20 text-blue-400','active'=>'bg-emerald-500/20 text-emerald-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
            $sl=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv edilib'];
            $dot=['pending'=>'bg-amber-400','approved'=>'bg-blue-400','active'=>'bg-emerald-400','completed'=>'bg-gray-500','cancelled'=>'bg-red-400'];
        @endphp
        <a href="{{ route('customer.reservations.show', $r) }}"
           class="flex items-center justify-between py-3.5 px-2 rounded-xl hover:bg-white/5 transition-all duration-200 group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform" style="background: rgba(59,130,246,0.12);">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-200 text-sm">{{ $r->car?->brand }} {{ $r->car?->model }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $r->start_date->format('d.m.Y') }} – {{ $r->end_date->format('d.m.Y') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-white text-sm">{{ number_format($r->total_amount, 2) }} ₼</p>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium mt-1 {{ $sb[$r->status]??'bg-gray-500/20 text-gray-400' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dot[$r->status]??'bg-gray-500' }}"></span>
                    {{ $sl[$r->status]??$r->status }}
                </span>
            </div>
        </a>
        @empty
        <div class="py-10 text-center">
            <p class="text-gray-600 text-sm mb-3">Hələ rezervasiya yoxdur</p>
            <a href="{{ route('customer.reservations.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all">
                İlk rezervasiyanı yarat
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
