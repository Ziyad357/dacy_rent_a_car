@extends('agent.layout')
@section('title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
    @php
        $cards = [
            ['label' => 'Bu gün açılan',      'value' => $stats['my_reservations_today'], 'color' => 'indigo'],
            ['label' => 'Aktiv rezervasiya',   'value' => $stats['active_reservations'],   'color' => 'green'],
            ['label' => 'Gözləyən',            'value' => $stats['pending_reservations'],  'color' => 'yellow'],
            ['label' => 'Boş avtomobil',       'value' => $stats['available_cars'],        'color' => 'blue'],
            ['label' => 'Aktiv müştəri',       'value' => $stats['total_customers'],       'color' => 'purple'],
            ['label' => 'Açıq müqavilə',       'value' => $stats['open_contracts'],        'color' => 'orange'],
        ];
        $cm = ['indigo'=>'text-indigo-400','green'=>'text-emerald-400','yellow'=>'text-amber-400','blue'=>'text-sky-400','purple'=>'text-purple-400','orange'=>'text-orange-400'];
    @endphp
    @php
        $glowMap = ['indigo'=>'rgba(99,102,241,0.15)','green'=>'rgba(16,185,129,0.15)','yellow'=>'rgba(245,158,11,0.15)','blue'=>'rgba(14,165,233,0.15)','purple'=>'rgba(139,92,246,0.15)','orange'=>'rgba(249,115,22,0.15)'];
        $bgMap   = ['indigo'=>'rgba(99,102,241,0.1)', 'green'=>'rgba(16,185,129,0.1)', 'yellow'=>'rgba(245,158,11,0.1)', 'blue'=>'rgba(14,165,233,0.1)', 'purple'=>'rgba(139,92,246,0.1)', 'orange'=>'rgba(249,115,22,0.1)'];
    @endphp
    @foreach($cards as $card)
    <div class="theme-card rounded-2xl p-5 transition-all duration-300 hover:-translate-y-0.5">
        <div class="w-8 h-8 rounded-lg mb-3 flex items-center justify-center {{ $cm[$card['color']] ?? '' }}"
             style="background: {{ $bgMap[$card['color']] ?? '' }}; box-shadow: 0 0 14px {{ $glowMap[$card['color']] ?? 'transparent' }};">
            <span class="text-sm font-bold">{{ substr($card['label'],0,1) }}</span>
        </div>
        <p class="text-xs text-gray-600 uppercase tracking-wider">{{ $card['label'] }}</p>
        <p class="text-2xl font-bold {{ $cm[$card['color']] ?? 'text-white' }} mt-1">{{ $card['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Returning Today --}}
@if($returningToday->count())
<div class="rounded-2xl p-5 mb-5" style="background: rgba(249,115,22,0.07); border: 1px solid rgba(249,115,22,0.2);">
    <h3 class="text-sm font-semibold text-orange-400 mb-3">Bu gün qaytarılmalıdır ({{ $returningToday->count() }})</h3>
    <div class="space-y-2">
        @foreach($returningToday as $r)
        <div class="flex items-center justify-between bg-gray-800 rounded-lg px-4 py-2.5 text-sm">
            <span class="font-medium text-gray-200">{{ $r->customer?->full_name }}</span>
            <span class="text-gray-500">{{ $r->car?->brand }} {{ $r->car?->model }} — {{ $r->car?->plate_number }}</span>
            <a href="{{ route('agent.reservations.show', $r) }}" class="text-indigo-600 hover:underline text-xs">Bax</a>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Recent Reservations --}}
<div class="theme-card rounded-2xl p-5">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-sm font-semibold text-gray-200 tracking-wide">Mənim rezervasiyalarım</h3>
        <a href="{{ route('agent.reservations.create') }}" class="inline-flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-indigo-500 transition-colors">
            + Yeni
        </a>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-800">
                <th class="pb-3 text-left font-medium text-gray-500">Müştəri</th>
                <th class="pb-3 text-left font-medium text-gray-500">Avtomobil</th>
                <th class="pb-3 text-left font-medium text-gray-500">Tarix</th>
                <th class="pb-3 text-left font-medium text-gray-500">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($recentReservations as $r)
            @php $sb=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-indigo-500/20 text-indigo-400','active'=>'bg-green-500/20 text-green-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
            $sl=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv']; @endphp
            <tr>
                <td class="py-3 font-medium text-gray-200">{{ $r->customer?->full_name ?? '-' }}</td>
                <td class="py-3 text-gray-400">{{ $r->car?->brand }} {{ $r->car?->model }}</td>
                <td class="py-3 text-gray-500 text-xs">{{ $r->start_date->format('d.m.Y') }} – {{ $r->end_date->format('d.m.Y') }}</td>
                <td class="py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sb[$r->status]??'' }}">{{ $sl[$r->status]??$r->status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" class="py-6 text-center text-gray-600">Rezervasiya tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
