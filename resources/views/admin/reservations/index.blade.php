@extends('admin.layout')
@section('title', 'Rezervasiyalar')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('admin.reservations.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni rezervasiya
    </a>
</div>

<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex flex-wrap gap-3">
    <select name="status" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
        <option value="">Bütün statuslar</option>
        <option value="pending" @selected(request('status')=='pending')>Gözləyir</option>
        <option value="approved" @selected(request('status')=='approved')>Təsdiqlənib</option>
        <option value="active" @selected(request('status')=='active')>Aktiv</option>
        <option value="completed" @selected(request('status')=='completed')>Tamamlandı</option>
        <option value="cancelled" @selected(request('status')=='cancelled')>Ləğv edilib</option>
    </select>
    <input name="from" type="date" value="{{ request('from') }}" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
    <input name="to" type="date" value="{{ request('to') }}" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">Filtrə</button>
    <a href="{{ route('admin.reservations.index') }}" class="text-sm text-gray-500 hover:text-gray-300 self-center transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 border-b border-gray-700">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Müştəri</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Avtomobil</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Başlama</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Bitmə</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Məbləğ</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Status</th>
                <th class="px-4 py-3 text-right font-medium text-gray-400">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($reservations as $r)
            @php
                $sb=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-indigo-500/20 text-indigo-400','active'=>'bg-green-500/20 text-green-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
                $sl=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv edilib'];
            @endphp
            <tr class="hover:bg-gray-800/60 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-200">{{ $r->customer?->full_name ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $r->car?->brand }} {{ $r->car?->model }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $r->start_date->format('d.m.Y') }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $r->end_date->format('d.m.Y') }}</td>
                <td class="px-4 py-3 font-medium text-gray-200">{{ number_format($r->total_amount, 2) }} ₼</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sb[$r->status]??'' }}">{{ $sl[$r->status]??$r->status }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.reservations.show', $r) }}" class="text-gray-400 hover:text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        @if(in_array($r->status, ['pending','approved']))
                        <div x-data="{open:false}" class="relative">
                            <button @click="open=!open" class="text-gray-400 hover:text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </button>
                            <div x-show="open" @click.outside="open=false" class="absolute right-0 top-6 z-10 bg-gray-800 border border-gray-700 shadow-xl rounded-lg py-1 w-36">
                                @foreach(['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','cancelled'=>'Ləğv et'] as $val=>$lbl)
                                <form method="POST" action="{{ route('admin.reservations.status', $r) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $val }}">
                                    <button type="submit" class="w-full text-left px-3 py-1.5 text-xs hover:bg-gray-700 text-gray-300 transition-colors">{{ $lbl }}</button>
                                </form>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-600">Rezervasiya tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-800">{{ $reservations->links() }}</div>
</div>
@endsection
