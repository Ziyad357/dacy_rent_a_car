@extends('admin.layout')
@section('title', 'Avtomobil Utilizasiyası')

@section('content')
<div class="max-w-2xl">
    <form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex flex-wrap gap-3">
        <select name="car_id" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
            <option value="">Avtomobil seçin...</option>
            @foreach($cars as $car)
            <option value="{{ $car->id }}" @selected($carId==$car->id)>{{ $car->brand }} {{ $car->model }} ({{ $car->plate_number }})</option>
            @endforeach
        </select>
        <input name="from" type="date" value="{{ $from->toDateString() }}" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
        <input name="to" type="date" value="{{ $to->toDateString() }}" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Hesabla</button>
    </form>

    @if($utilization !== null)
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6 text-center">
        <p class="text-sm text-gray-500 mb-2">Seçilmiş dövr üzrə utilizasiya</p>
        <p class="text-5xl font-bold text-indigo-400">{{ $utilization }}%</p>
        <p class="text-sm text-gray-500 mt-2">{{ $from->format('d.m.Y') }} – {{ $to->format('d.m.Y') }}</p>

        <div class="mt-5 w-full bg-gray-800 rounded-full h-4">
            <div class="bg-indigo-600 h-4 rounded-full transition-all duration-500" style="width: {{ $utilization }}%"></div>
        </div>
    </div>
    @endif
</div>
@endsection
