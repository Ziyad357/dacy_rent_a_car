@extends('agent.layout')
@section('title', $customer->full_name)

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('agent.customers.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="md:col-span-1 bg-gray-900 rounded-xl border border-gray-800 p-5">
            <div class="w-16 h-16 bg-indigo-500/20 text-indigo-400 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-3">
                {{ mb_substr($customer->full_name, 0, 1) }}
            </div>
            <h2 class="text-lg font-bold text-white text-center">{{ $customer->full_name }}</h2>
            @if($customer->blacklisted)
            <div class="mt-2 p-2 bg-red-500/10 border border-red-500/20 rounded-lg text-xs text-red-400 text-center">Qara siyahıda</div>
            @endif
            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Telefon</dt><dd class="font-medium text-gray-200">{{ $customer->phone }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium text-xs break-all text-gray-200">{{ $customer->email }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">FIN</dt><dd class="font-mono text-gray-200">{{ $customer->id_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">SV nömrəsi</dt><dd class="font-medium text-gray-200">{{ $customer->license_number }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">SV bitmə</dt>
                    <dd class="{{ $customer->license_expiry?->isPast() ? 'text-red-400 font-bold' : 'font-medium text-gray-200' }}">{{ $customer->license_expiry?->format('d.m.Y') }}</dd>
                </div>
                <div class="flex justify-between"><dt class="text-gray-500">Doğum tarixi</dt><dd class="font-medium text-gray-200">{{ $customer->date_of_birth?->format('d.m.Y') }}</dd></div>
            </dl>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('agent.customers.edit', $customer) }}" class="flex-1 text-center bg-indigo-600 text-white py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Düzəlt</a>
            </div>
        </div>

        <div class="md:col-span-2 bg-gray-900 rounded-xl border border-gray-800 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-300">Rezervasiyalar</h3>
                <a href="{{ route('agent.reservations.create', ['customer_id' => $customer->id]) }}" class="text-xs text-indigo-600 hover:underline">+ Yeni rezervasiya</a>
            </div>
            <table class="w-full text-sm">
                <thead class="border-b border-gray-800">
                    <tr>
                        <th class="pb-2 text-left font-medium text-gray-500 text-xs">Avtomobil</th>
                        <th class="pb-2 text-left font-medium text-gray-500 text-xs">Tarix</th>
                        <th class="pb-2 text-left font-medium text-gray-500 text-xs">Məbləğ</th>
                        <th class="pb-2 text-left font-medium text-gray-500 text-xs">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($customer->reservations->sortByDesc('start_date') as $r)
                    @php $sb=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-indigo-500/20 text-indigo-400','active'=>'bg-green-500/20 text-green-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
                    $sl=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv']; @endphp
                    <tr>
                        <td class="py-2 text-gray-300">{{ $r->car?->brand }} {{ $r->car?->model }}</td>
                        <td class="py-2 text-gray-500 text-xs">{{ $r->start_date->format('d.m.Y') }} – {{ $r->end_date->format('d.m.Y') }}</td>
                        <td class="py-2 font-medium text-gray-200">{{ number_format($r->total_amount, 2) }} ₼</td>
                        <td class="py-2"><span class="px-2 py-0.5 rounded-full text-xs {{ $sb[$r->status]??'' }}">{{ $sl[$r->status]??$r->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-6 text-center text-gray-600 text-xs">Rezervasiya tapılmadı</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
