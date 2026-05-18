@extends('agent.layout')
@section('title', 'Rezervasiyalar')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('agent.reservations.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni rezervasiya
    </a>
</div>

<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex gap-3">
    <select name="status" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
        <option value="">Bütün statuslar</option>
        <option value="pending" @selected(request('status')=='pending')>Gözləyir</option>
        <option value="approved" @selected(request('status')=='approved')>Təsdiqlənib</option>
        <option value="active" @selected(request('status')=='active')>Aktiv</option>
        <option value="completed" @selected(request('status')=='completed')>Tamamlandı</option>
        <option value="cancelled" @selected(request('status')=='cancelled')>Ləğv edilib</option>
    </select>
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Filtrə</button>
    <a href="{{ route('agent.reservations.index') }}" class="text-sm text-gray-500 self-center hover:text-gray-300 transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800/50 border-b border-gray-800">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Müştəri</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Avtomobil</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Tarix</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Məbləğ</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($reservations as $r)
            @php $sb=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-indigo-500/20 text-indigo-400','active'=>'bg-green-500/20 text-green-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
            $sl=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv edilib']; @endphp
            <tr class="hover:bg-gray-800/50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-200">{{ $r->customer?->full_name ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $r->car?->brand }} {{ $r->car?->model }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $r->start_date->format('d.m.Y') }} – {{ $r->end_date->format('d.m.Y') }}</td>
                <td class="px-4 py-3 font-medium text-gray-200">{{ number_format($r->total_amount, 2) }} ₼</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sb[$r->status]??'' }}">{{ $sl[$r->status]??$r->status }}</span></td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('agent.reservations.show', $r) }}" class="text-indigo-600 hover:underline text-xs">Bax</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-600">Rezervasiya tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-800">{{ $reservations->links() }}</div>
</div>
@endsection
