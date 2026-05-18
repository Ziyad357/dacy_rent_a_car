@extends('agent.layout')
@section('title', 'Müqavilələr')

@section('content')
<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Müqavilə nömrəsi axtar..." class="bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-600 rounded-lg px-3 py-2 text-sm w-60 focus:outline-none">
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Axtar</button>
    <a href="{{ route('agent.contracts.index') }}" class="text-sm text-gray-500 self-center hover:text-gray-300 transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800/50 border-b border-gray-800">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Nömrə</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Müştəri</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Avtomobil</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">İmzalandı</th>
                <th class="px-4 py-3 text-left font-medium text-gray-500">Vəziyyət</th>
                <th class="px-4 py-3 text-right font-medium text-gray-500">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($contracts as $c)
            <tr class="hover:bg-gray-800/50 transition-colors">
                <td class="px-4 py-3 font-mono font-bold text-indigo-400">{{ $c->contract_number }}</td>
                <td class="px-4 py-3 text-gray-200">{{ $c->reservation?->customer?->full_name ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-400">{{ $c->reservation?->car?->brand }} {{ $c->reservation?->car?->model }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $c->signed_at?->format('d.m.Y') ?? '-' }}</td>
                <td class="px-4 py-3">
                    @if($c->returned_at)
                        <span class="text-green-400 text-xs font-medium">Bağlandı</span>
                    @else
                        <span class="text-orange-400 text-xs font-medium">Açıqdır</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('agent.contracts.show', $c) }}" class="text-indigo-600 hover:underline text-xs">Bax</a>
                        <a href="{{ route('agent.contracts.pdf', $c) }}" class="text-green-600 hover:underline text-xs">PDF</a>
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
