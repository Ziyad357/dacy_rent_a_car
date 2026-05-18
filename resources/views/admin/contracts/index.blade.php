@extends('admin.layout')
@section('title', 'Müqavilələr')

@section('content')
<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Müqavilə nömrəsi axtar..." class="bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-500 rounded-lg px-3 py-2 text-sm w-60 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">Axtar</button>
    <a href="{{ route('admin.contracts.index') }}" class="text-sm text-gray-500 hover:text-gray-300 self-center transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 border-b border-gray-700">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Nömrə</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Müştəri</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Avtomobil</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">İmzalandı</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Qaytarıldı</th>
                <th class="px-4 py-3 text-right font-medium text-gray-400">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($contracts as $c)
            <tr class="hover:bg-gray-800/60 transition-colors">
                <td class="px-4 py-3 font-mono font-bold text-indigo-400">{{ $c->contract_number }}</td>
                <td class="px-4 py-3 text-gray-300">{{ $c->reservation?->customer?->full_name ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $c->reservation?->car?->brand }} {{ $c->reservation?->car?->model }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $c->signed_at?->format('d.m.Y') ?? '-' }}</td>
                <td class="px-4 py-3">
                    @if($c->returned_at)
                        <span class="text-green-400 text-xs font-medium">{{ $c->returned_at->format('d.m.Y') }}</span>
                    @else
                        <span class="text-orange-400 text-xs font-medium">Açıqdır</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.contracts.show', $c) }}" class="text-blue-600 hover:underline text-xs">Bax</a>
                        <a href="{{ route('admin.contracts.pdf', $c) }}" class="text-green-600 hover:underline text-xs">PDF</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-600">Müqavilə tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-800">{{ $contracts->links() }}</div>
</div>
@endsection
