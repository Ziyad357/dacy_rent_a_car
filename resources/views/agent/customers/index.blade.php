@extends('agent.layout')
@section('title', 'Müştərilər')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('agent.customers.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni müştəri
    </a>
</div>

<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Ad, FIN, telefon axtar..." class="bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-600 rounded-lg px-3 py-2 text-sm flex-1 max-w-xs focus:outline-none">
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Axtar</button>
    <a href="{{ route('agent.customers.index') }}" class="text-sm text-gray-500 self-center hover:text-gray-300 transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800/50 border-b border-gray-800">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Ad Soyad</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Telefon</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">FIN</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">SV bitmə tarixi</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($customers as $c)
            <tr class="hover:bg-gray-800/50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-200">{{ $c->full_name }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $c->phone }}</td>
                <td class="px-4 py-3 font-mono text-gray-400">{{ $c->id_number }}</td>
                <td class="px-4 py-3 text-gray-400">
                    @if($c->license_expiry && $c->license_expiry->isPast())
                        <span class="text-red-400 font-medium">{{ $c->license_expiry->format('d.m.Y') }} ⚠</span>
                    @else
                        {{ $c->license_expiry?->format('d.m.Y') ?? '-' }}
                    @endif
                </td>
                <td class="px-4 py-3">
                    @if($c->blacklisted)
                        <span class="px-2 py-0.5 rounded-full text-xs bg-red-500/20 text-red-400">Qara siyahı</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-500/20 text-green-400">Aktiv</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('agent.customers.show', $c) }}" class="text-indigo-600 hover:underline text-xs">Bax</a>
                        <a href="{{ route('agent.customers.edit', $c) }}" class="text-yellow-600 hover:underline text-xs">Düzəlt</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-600">Müştəri tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-800">{{ $customers->links() }}</div>
</div>
@endsection
