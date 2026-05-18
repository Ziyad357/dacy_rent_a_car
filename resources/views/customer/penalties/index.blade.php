@extends('customer.layout')
@section('title', 'Cərimələrim')

@section('content')
<h1 class="text-xl font-bold text-white mb-5">Cərimələrim</h1>

@php
    $total = $penalties->sum('amount');
    $unpaid = $penalties->where('paid', false)->sum('amount');
@endphp

@if($penalties->count())
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-5">
    <div class="theme-card rounded-xl p-4">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Ümumi cərimə</p>
        <p class="text-xl font-bold text-white mt-1">{{ number_format($total, 2) }} ₼</p>
    </div>
    <div class="theme-card rounded-xl p-4" style="border-color: rgba(239,68,68,0.2) !important;">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Ödənilməmiş</p>
        <p class="text-xl font-bold text-red-400 mt-1">{{ number_format($unpaid, 2) }} ₼</p>
    </div>
    <div class="theme-card rounded-xl p-4" style="border-color: rgba(16,185,129,0.2) !important;">
        <p class="text-xs text-gray-500 uppercase tracking-wider">Ödənilmiş</p>
        <p class="text-xl font-bold text-emerald-400 mt-1">{{ number_format($total - $unpaid, 2) }} ₼</p>
    </div>
</div>
@endif

<div class="theme-card rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead style="background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.06);">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Növ</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Açıqlama</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Müqavilə</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">Məbləğ</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($penalties as $p)
            @php
                $tc=['late_return'=>'bg-orange-500/20 text-orange-400','damage'=>'bg-red-500/20 text-red-400','fuel'=>'bg-amber-500/20 text-amber-400','other'=>'bg-gray-500/20 text-gray-400'];
                $tl=['late_return'=>'Gecikmə','damage'=>'Zədə','fuel'=>'Yanacaq','other'=>'Digər'];
            @endphp
            <tr>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $tc[$p->type]??'bg-gray-100 text-gray-600' }}">{{ $tl[$p->type]??$p->type }}</span>
                </td>
                <td class="px-4 py-3 text-gray-400 max-w-xs truncate">{{ $p->description }}</td>
                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $p->contract?->contract_number }}</td>
                <td class="px-4 py-3 text-right font-bold text-red-400">{{ number_format($p->amount, 2) }} ₼</td>
                <td class="px-4 py-3">
                    @if($p->paid)
                        <span class="px-2 py-0.5 rounded-full text-xs bg-emerald-500/20 text-emerald-400">Ödənilib ✓</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs bg-red-500/20 text-red-400">Ödənilməyib</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-600">Cərimə tapılmadı</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($unpaid > 0)
<div class="mt-4 p-4 rounded-xl text-sm text-orange-400" style="background:rgba(249,115,22,0.07);border:1px solid rgba(249,115,22,0.2);">
    <strong>{{ number_format($unpaid, 2) }} ₼</strong> məbləğdə ödənilməmiş cəriməniz var. Ödəniş üçün agentlə əlaqə saxlayın.
</div>
@endif
@endsection
