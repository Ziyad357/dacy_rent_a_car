@extends('admin.layout')
@section('title', 'Avtomobillər')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('admin.cars.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni avtomobil
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex flex-wrap gap-3">
    <input name="brand" value="{{ request('brand') }}" placeholder="Marka axtar..." class="bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-500 rounded-lg px-3 py-2 text-sm w-40 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
    <select name="status" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
        <option value="">Bütün statuslar</option>
        <option value="available" @selected(request('status')=='available')>Boş</option>
        <option value="rented" @selected(request('status')=='rented')>İcarədə</option>
        <option value="maintenance" @selected(request('status')=='maintenance')>Texniki xidmət</option>
        <option value="reserved" @selected(request('status')=='reserved')>Rezerv</option>
    </select>
    <select name="fuel_type" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
        <option value="">Yanacaq növü</option>
        <option value="petrol" @selected(request('fuel_type')=='petrol')>Benzin</option>
        <option value="diesel" @selected(request('fuel_type')=='diesel')>Dizel</option>
        <option value="electric" @selected(request('fuel_type')=='electric')>Elektrik</option>
        <option value="hybrid" @selected(request('fuel_type')=='hybrid')>Hibrid</option>
    </select>
    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">Filtrə</button>
    <a href="{{ route('admin.cars.index') }}" class="text-sm text-gray-500 hover:text-gray-300 self-center transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 border-b border-gray-700">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Avtomobil</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Nömrə</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Yanacaq</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Qiymət/gün</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Status</th>
                <th class="px-4 py-3 text-right font-medium text-gray-400">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($cars as $car)
            @php
                $statusBadge = ['available'=>'bg-green-500/20 text-green-400','rented'=>'bg-indigo-500/20 text-indigo-400','maintenance'=>'bg-orange-500/20 text-orange-400','reserved'=>'bg-purple-500/20 text-purple-400'];
                $statusLabel = ['available'=>'Boş','rented'=>'İcarədə','maintenance'=>'Texniki','reserved'=>'Rezerv'];
            @endphp
            <tr class="hover:bg-gray-800/60 transition-colors {{ $car->trashed() ? 'opacity-40' : '' }}">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        @if($car->image)
                            <img src="{{ Storage::url($car->image) }}" class="w-10 h-10 rounded-lg object-cover">
                        @else
                            <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-200">{{ $car->brand }} {{ $car->model }}</p>
                            <p class="text-xs text-gray-500">{{ $car->year }} · {{ $car->color }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 font-mono text-gray-400">{{ $car->plate_number }}</td>
                <td class="px-4 py-3 text-gray-500 capitalize">{{ $car->fuel_type }}</td>
                <td class="px-4 py-3 font-medium text-gray-200">{{ number_format($car->daily_rate, 2) }} ₼</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusBadge[$car->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabel[$car->status] ?? $car->status }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.cars.show', $car) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Bax">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        @unless($car->trashed())
                        <a href="{{ route('admin.cars.edit', $car) }}" class="text-gray-400 hover:text-yellow-600 transition-colors" title="Düzəlt">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" onsubmit="return confirm('Silmək istədiyinizə əminsiniz?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Sil">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                        @endunless
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-600">Avtomobil tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-800">
        {{ $cars->links() }}
    </div>
</div>
@endsection
