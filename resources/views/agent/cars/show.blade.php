@extends('agent.layout')
@section('title', $car->brand . ' ' . $car->model)

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('agent.cars.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
            @if($car->image)
                <img src="{{ Storage::url($car->image) }}" class="w-full h-40 object-cover rounded-lg mb-4">
            @endif
            <h2 class="text-xl font-bold text-white">{{ $car->brand }} {{ $car->model }}</h2>
            <p class="text-sm text-gray-500">{{ $car->year }} · {{ $car->plate_number }}</p>
            @php $sb=['available'=>'bg-green-500/20 text-green-400','rented'=>'bg-indigo-500/20 text-indigo-400','maintenance'=>'bg-orange-500/20 text-orange-400','reserved'=>'bg-purple-500/20 text-purple-400'];
            $sl=['available'=>'Boş','rented'=>'İcarədə','maintenance'=>'Texniki','reserved'=>'Rezerv']; @endphp
            <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $sb[$car->status]??'' }}">{{ $sl[$car->status]??$car->status }}</span>

            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Rəng</dt><dd class="font-medium text-gray-200">{{ $car->color }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Yanacaq</dt><dd class="font-medium capitalize text-gray-200">{{ $car->fuel_type }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Ötürücü</dt><dd class="font-medium text-gray-200">{{ $car->transmission == 'manual' ? 'Mexaniki' : 'Avtomat' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Oturacaq</dt><dd class="font-medium text-gray-200">{{ $car->seats }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Günlük qiymət</dt><dd class="font-bold text-indigo-400">{{ number_format($car->daily_rate, 2) }} ₼</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Depozit</dt><dd class="font-medium text-gray-200">{{ number_format($car->deposit_amount, 2) }} ₼</dd></div>
            </dl>

            @if($car->status === 'available')
            <a href="{{ route('agent.reservations.create', ['car_id' => $car->id]) }}" class="mt-4 block w-full text-center bg-indigo-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">
                Rezervasiya yarat
            </a>
            @endif
        </div>

        {{-- Availability check --}}
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-5" x-data="{ from:'', to:'', result:null, loading:false }">
            <h3 class="text-sm font-semibold text-gray-300 mb-4">Mövcudluğu yoxla</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Başlama tarixi</label>
                    <input type="date" x-model="from" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Bitmə tarixi</label>
                    <input type="date" x-model="to" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                </div>
                <button @click="
                    loading = true; result = null;
                    fetch('{{ route('agent.cars.availability') }}', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                        body: JSON.stringify({car_id: {{ $car->id }}, start_date: from, end_date: to})
                    }).then(r => r.json()).then(d => { result = d.available; loading = false; })
                " :disabled="!from || !to || loading"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors disabled:opacity-50">
                    <span x-text="loading ? 'Yoxlanılır...' : 'Yoxla'"></span>
                </button>
                <div x-show="result !== null">
                    <p x-show="result" class="text-sm text-green-400 font-medium">Mövcuddur ✓</p>
                    <p x-show="!result" class="text-sm text-red-400 font-medium">Mövcud deyil ✗</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
