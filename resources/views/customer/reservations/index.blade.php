@extends('customer.layout')
@section('title', 'Rezervasiyalarım')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Rezervasiyalarım</h1>
        <p class="text-sm text-gray-500 mt-0.5">Bütün icarə tarixçəniz</p>
    </div>
    <a href="{{ route('customer.reservations.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Yeni Rezervasiya
    </a>
</div>

@if(session('success'))
<div class="mb-4 rounded-xl px-4 py-3 flex items-center gap-2 text-sm" style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);">
    <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <span class="text-emerald-400">{{ session('success') }}</span>
</div>
@endif

<div class="theme-card rounded-2xl px-4 py-3 mb-5 flex flex-wrap items-center gap-3">
    <form method="GET" class="flex items-center gap-2 flex-1">
        <select name="status"
                class="bg-gray-800 border border-gray-700 rounded-xl px-3 py-2 text-sm text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
            <option value="">Bütün statuslar</option>
            <option value="pending"   @selected(request('status')=='pending')>Gözləyir</option>
            <option value="approved"  @selected(request('status')=='approved')>Təsdiqlənib</option>
            <option value="active"    @selected(request('status')=='active')>Aktiv</option>
            <option value="completed" @selected(request('status')=='completed')>Tamamlandı</option>
            <option value="cancelled" @selected(request('status')=='cancelled')>Ləğv edilib</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition-all duration-200">Filtrə</button>
        @if(request('status'))
        <a href="{{ route('customer.reservations.index') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">Sıfırla ×</a>
        @endif
    </form>
</div>

<div class="space-y-3">
    @forelse($reservations as $r)
    @php
        $sb = ['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-blue-500/20 text-blue-400','active'=>'bg-emerald-500/20 text-emerald-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
        $sl = ['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv edilib'];
        $dot = ['pending'=>'bg-amber-400','approved'=>'bg-blue-400','active'=>'bg-emerald-400','completed'=>'bg-gray-500','cancelled'=>'bg-red-400'];
    @endphp
    <div class="theme-card rounded-2xl p-5 transition-all duration-200 hover:-translate-y-0.5">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(59,130,246,0.12);">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-200">{{ $r->car?->brand }} {{ $r->car?->model }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $r->car?->plate_number }} · {{ $r->total_days }} gün</p>
                    <div class="flex items-center gap-1.5 mt-1 text-xs text-gray-500">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $r->start_date->format('d.m.Y') }} – {{ $r->end_date->format('d.m.Y') }}
                    </div>
                </div>
            </div>
            <div class="text-right shrink-0">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $sb[$r->status]??'bg-gray-100 text-gray-600' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $dot[$r->status]??'bg-gray-400' }}"></span>
                    {{ $sl[$r->status]??$r->status }}
                </span>
                <p class="font-bold text-white text-lg mt-1.5">{{ number_format($r->total_amount, 2) }} ₼</p>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-4 pt-3 border-t border-gray-800">
            <a href="{{ route('customer.reservations.show', $r) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/8 hover:bg-white/12 text-gray-300 text-xs font-medium rounded-lg transition-all duration-200" style="background:rgba(255,255,255,0.06);">
                Ətraflı bax →
            </a>
            @if(in_array($r->status, ['pending','approved']))
            <form method="POST" action="{{ route('customer.reservations.cancel', $r) }}">
                @csrf @method('PATCH')
                <button type="submit" onclick="return confirm('Ləğv etmək istədiyinizə əminsiniz?')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs font-medium rounded-lg transition-all duration-200">
                    Ləğv et
                </button>
            </form>
            @endif
            @if($r->contract)
            <a href="{{ route('customer.reservations.contract.pdf', $r) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 text-xs font-medium rounded-lg transition-all duration-200 ml-auto">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                PDF
            </a>
            @endif
        </div>
    </div>
    @empty
    <div class="theme-card rounded-2xl p-12 text-center">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(255,255,255,0.05);">
            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">Hələ rezervasiya yoxdur ...</p>
        <a href="{{ route('customer.reservations.create') }}"
           class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-all">
            İlk rezervasiyanı yarat
        </a>
    </div>
    @endforelse
</div>

@if(method_exists($reservations, 'links'))
<div class="mt-4">{{ $reservations->links() }}</div>
@endif
@endsection
