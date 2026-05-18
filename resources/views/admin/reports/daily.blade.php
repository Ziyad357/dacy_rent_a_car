@extends('admin.layout')
@section('title', 'Günlük Hesabat')

@section('content')
<div class="max-w-3xl">
    <form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex gap-3">
        <input name="date" type="date" value="{{ $date->toDateString() }}" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Göstər</button>
    </form>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @php
            $cards = [
                ['label' => 'Gəlir', 'value' => number_format($data['total_revenue'], 2) . ' ₼', 'color' => 'green'],
                ['label' => 'Aktiv rezervasiya', 'value' => $data['reservations_count'], 'color' => 'blue'],
                ['label' => 'Yeni rezervasiya', 'value' => $data['new_reservations'], 'color' => 'purple'],
                ['label' => 'Tamamlandı', 'value' => $data['completed_reservations'], 'color' => 'teal'],
                ['label' => 'Ödənilməmiş cərimə', 'value' => number_format($data['unpaid_penalties'], 2) . ' ₼', 'color' => 'red'],
            ];
            $cm = ['green'=>'text-emerald-400','blue'=>'text-indigo-400','purple'=>'text-purple-400','teal'=>'text-teal-400','red'=>'text-red-400'];
        @endphp
        @foreach($cards as $card)
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
            <p class="text-xs text-gray-500 mb-1">{{ $card['label'] }}</p>
            <p class="text-2xl font-bold {{ $cm[$card['color']] ?? 'text-white' }}">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
