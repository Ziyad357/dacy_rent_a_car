@extends('admin.layout')
@section('title', 'Ödənişlər')

@section('content')
<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex flex-wrap gap-3">
    <select name="type" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
        <option value="">Bütün növlər</option>
        <option value="deposit" @selected(request('type')=='deposit')>Depozit</option>
        <option value="rental" @selected(request('type')=='rental')>İcarə</option>
        <option value="penalty" @selected(request('type')=='penalty')>Cərimə</option>
        <option value="refund" @selected(request('type')=='refund')>Geri qaytarma</option>
    </select>
    <select name="method" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
        <option value="">Ödəniş üsulu</option>
        <option value="cash" @selected(request('method')=='cash')>Nağd</option>
        <option value="card" @selected(request('method')=='card')>Kart</option>
        <option value="transfer" @selected(request('method')=='transfer')>Transfer</option>
    </select>
    <input name="from" type="date" value="{{ request('from') }}" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
    <input name="to" type="date" value="{{ request('to') }}" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">Filtrə</button>
    <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:text-gray-300 self-center transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 border-b border-gray-700">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Müştəri</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Növ</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Üsul</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Məbləğ</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Tarix</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($payments as $p)
            @php
                $tc=['deposit'=>'bg-purple-500/20 text-purple-400','rental'=>'bg-indigo-500/20 text-indigo-400','penalty'=>'bg-red-500/20 text-red-400','refund'=>'bg-green-500/20 text-green-400'];
                $tl=['deposit'=>'Depozit','rental'=>'İcarə','penalty'=>'Cərimə','refund'=>'Geri qayt.'];
                $mc=['cash'=>'Nağd','card'=>'Kart','transfer'=>'Transfer'];
            @endphp
            <tr class="hover:bg-gray-800/60 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-200">{{ $p->reservation?->customer?->full_name ?? '-' }}</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $tc[$p->type]??'bg-gray-500/20 text-gray-400' }}">{{ $tl[$p->type]??$p->type }}</span></td>
                <td class="px-4 py-3 text-gray-400">{{ $mc[$p->method]??$p->method }}</td>
                <td class="px-4 py-3 font-bold text-gray-200">{{ number_format($p->amount, 2) }} ₼</td>
                <td class="px-4 py-3 text-gray-500">{{ $p->paid_at?->format('d.m.Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-600">Ödəniş tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-800">{{ $payments->links() }}</div>
</div>
@endsection
