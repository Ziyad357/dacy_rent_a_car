@extends('agent.layout')
@section('title', 'Avtomobillər')

@section('content')
<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex flex-wrap gap-3">
    <input name="brand" value="{{ request('brand') }}" placeholder="Marka axtar..." class="bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-600 rounded-lg px-3 py-2 text-sm w-40 focus:outline-none">
    <select name="status" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
        <option value="">Bütün statuslar</option>
        <option value="available" @selected(request('status')=='available')>Boş</option>
        <option value="rented" @selected(request('status')=='rented')>İcarədə</option>
        <option value="maintenance" @selected(request('status')=='maintenance')>Texniki</option>
        <option value="reserved" @selected(request('status')=='reserved')>Rezerv</option>
    </select>
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Filtrə</button>
    <a href="{{ route('agent.cars.index') }}" class="text-sm text-gray-500 self-center hover:text-gray-300 transition-colors">Sıfırla</a>
</form>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($cars as $car)
    @php $sb=['available'=>'bg-green-500/20 text-green-400','rented'=>'bg-indigo-500/20 text-indigo-400','maintenance'=>'bg-orange-500/20 text-orange-400','reserved'=>'bg-purple-500/20 text-purple-400'];
    $sl=['available'=>'Boş','rented'=>'İcarədə','maintenance'=>'Texniki','reserved'=>'Rezerv']; @endphp
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden hover:border-gray-700 transition-colors">
        @if($car->image)
            <img src="{{ Storage::url($car->image) }}" class="w-full h-36 object-cover">
        @else
            <div class="w-full h-36 bg-gray-800 flex items-center justify-center text-gray-600">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        @endif
        <div class="p-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold text-white">{{ $car->brand }} {{ $car->model }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $car->year }} · {{ $car->plate_number }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sb[$car->status]??'' }}">{{ $sl[$car->status]??$car->status }}</span>
            </div>
            <div class="flex items-center justify-between mt-3">
                <span class="font-bold text-indigo-400">{{ number_format($car->daily_rate,2) }} ₼/gün</span>
                <a href="{{ route('agent.cars.show', $car) }}" class="text-xs text-indigo-600 hover:underline">Ətraflı →</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 py-12 text-center text-gray-600">Avtomobil tapılmadı</div>
    @endforelse
</div>
<div class="mt-4">{{ $cars->links() }}</div>
@endsection
