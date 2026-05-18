@extends('admin.layout')
@section('title', 'Cərimələr')

@section('content')
<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex gap-3">
    <select name="paid" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
        <option value="">Hamısı</option>
        <option value="0" @selected(request('paid')==='0')>Ödənilməmiş</option>
        <option value="1" @selected(request('paid')==='1')>Ödənilmiş</option>
    </select>
    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">Filtrə</button>
    <a href="{{ route('admin.penalties.index') }}" class="text-sm text-gray-500 hover:text-gray-300 self-center transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 border-b border-gray-700">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Müştəri</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Növ</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Açıqlama</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Məbləğ</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Status</th>
                <th class="px-4 py-3 text-right font-medium text-gray-400">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($penalties as $p)
            @php
                $tc=['late_return'=>'bg-orange-500/20 text-orange-400','damage'=>'bg-red-500/20 text-red-400','fuel'=>'bg-amber-500/20 text-amber-400','other'=>'bg-gray-500/20 text-gray-400'];
                $tl=['late_return'=>'Gecikmə','damage'=>'Zədə','fuel'=>'Yanacaq','other'=>'Digər'];
            @endphp
            <tr class="hover:bg-gray-800/60 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-200">{{ $p->contract?->reservation?->customer?->full_name ?? '-' }}</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $tc[$p->type]??'' }}">{{ $tl[$p->type]??$p->type }}</span></td>
                <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $p->description }}</td>
                <td class="px-4 py-3 font-bold text-red-400">{{ number_format($p->amount, 2) }} ₼</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $p->paid ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">{{ $p->paid ? 'Ödənilib' : 'Ödənilməyib' }}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.penalties.show', $p) }}" class="text-blue-600 hover:underline text-xs">Bax</a>
                        @if(!$p->paid)
                        <form method="POST" action="{{ route('admin.penalties.pay', $p) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs text-green-600 hover:underline">Ödənilib</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-600">Cərimə tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-800">{{ $penalties->links() }}</div>
</div>
@endsection
