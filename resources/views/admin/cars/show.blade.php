@extends('admin.layout')
@section('title', $car->brand . ' ' . $car->model)

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.cars.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Car Details --}}
        <div class="lg:col-span-1 bg-gray-900 rounded-xl border border-gray-800 p-5">
            @if($car->image)
                <img src="{{ Storage::url($car->image) }}" class="w-full h-40 object-cover rounded-lg mb-4">
            @else
                <div class="w-full h-40 bg-gray-800 rounded-lg mb-4 flex items-center justify-center text-gray-600">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
            <h2 class="text-xl font-bold text-white mb-1">{{ $car->brand }} {{ $car->model }}</h2>
            <p class="text-sm text-gray-500 mb-4">{{ $car->year }} · {{ $car->plate_number }}</p>
            @php $sb=['available'=>'bg-green-500/20 text-green-400','rented'=>'bg-indigo-500/20 text-indigo-400','maintenance'=>'bg-orange-500/20 text-orange-400','reserved'=>'bg-purple-500/20 text-purple-400'];
            $sl=['available'=>'Boş','rented'=>'İcarədə','maintenance'=>'Texniki','reserved'=>'Rezerv']; @endphp
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $sb[$car->status]??'bg-gray-100 text-gray-600' }}">{{ $sl[$car->status]??$car->status }}</span>

            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Rəng</dt><dd class="font-medium text-gray-200">{{ $car->color }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Yanacaq</dt><dd class="font-medium text-gray-200 capitalize">{{ $car->fuel_type }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Ötürücü</dt><dd class="font-medium text-gray-200">{{ $car->transmission == 'manual' ? 'Mexaniki' : 'Avtomat' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Oturacaq</dt><dd class="font-medium text-gray-200">{{ $car->seats }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Km</dt><dd class="font-medium text-gray-200">{{ number_format($car->mileage) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Gündəlik qiymət</dt><dd class="font-bold text-indigo-400">{{ number_format($car->daily_rate, 2) }} ₼</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Depozit</dt><dd class="font-medium text-gray-200">{{ number_format($car->deposit_amount, 2) }} ₼</dd></div>
            </dl>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.cars.edit', $car) }}" class="flex-1 text-center bg-indigo-600 text-white py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Düzəlt</a>
            </div>

            {{-- Status change --}}
            <form method="POST" action="{{ route('admin.cars.status', $car) }}" class="mt-3">
                @csrf @method('PATCH')
                <div class="flex gap-2">
                    <select name="status" class="flex-1 bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
                        <option value="available" @selected($car->status=='available')>Boş</option>
                        <option value="maintenance" @selected($car->status=='maintenance')>Texniki xidmət</option>
                        <option value="reserved" @selected($car->status=='reserved')>Rezerv</option>
                    </select>
                    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-2 rounded-lg text-xs transition-colors">Yenilə</button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-5">
            {{-- Maintenance History --}}
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
                <h3 class="text-base font-semibold text-gray-300 mb-4">Texniki Xidmət Tarixcəsi</h3>
                @forelse($car->maintenances->sortByDesc('started_at') as $m)
                <div class="flex items-start gap-3 py-3 border-b border-gray-800 last:border-0">
                    <div class="w-8 h-8 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-xs font-bold shrink-0">TX</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-200 capitalize">{{ $m->type }}</p>
                            <span class="text-xs text-gray-500">{{ $m->started_at->format('d.m.Y') }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $m->description }}</p>
                        <p class="text-xs font-medium text-gray-400 mt-0.5">{{ number_format($m->cost, 2) }} ₼ · {{ $m->completed_at ? 'Tamamlandı: '.$m->completed_at->format('d.m.Y') : 'Davam edir' }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-600 py-4">Texniki xidmət qeydi yoxdur</p>
                @endforelse

                {{-- Add maintenance --}}
                <div x-data="{open:false}" class="mt-4">
                    <button @click="open=!open" class="text-sm text-indigo-400 hover:text-indigo-300 font-medium transition-colors">+ Texniki xidmət əlavə et</button>
                    <form x-show="open" x-transition method="POST" action="{{ route('admin.cars.maintenance.store', $car) }}" class="mt-3 space-y-3">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Növ</label>
                                <select name="type" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm">
                                    <option value="routine">Profilaktika</option>
                                    <option value="repair">Təmir</option>
                                    <option value="inspection">Texniki baxış</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Xərc (₼)</label>
                                <input name="cost" type="number" step="0.01" min="0" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Başlama tarixi</label>
                                <input name="started_at" type="date" value="{{ date('Y-m-d') }}" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Bitmə tarixi</label>
                                <input name="completed_at" type="date" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Açıqlama</label>
                            <textarea name="description" rows="2" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm resize-none"></textarea>
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Əlavə et</button>
                    </form>
                </div>
            </div>

            {{-- Reservations --}}
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
                <h3 class="text-base font-semibold text-gray-300 mb-4">Rezervasiya Tarixcəsi</h3>
                <div class="space-y-2">
                    @forelse($car->reservations->sortByDesc('start_date')->take(8) as $r)
                    <div class="flex items-center justify-between text-sm py-2 border-b border-gray-800 last:border-0">
                        <span class="font-medium text-gray-300">{{ $r->customer->full_name ?? '-' }}</span>
                        <span class="text-gray-500">{{ $r->start_date->format('d.m.Y') }} – {{ $r->end_date->format('d.m.Y') }}</span>
                        @php $sb2=['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-indigo-500/20 text-indigo-400','active'=>'bg-green-500/20 text-green-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
                        $sl2=['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv']; @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $sb2[$r->status]??'bg-gray-100' }}">{{ $sl2[$r->status]??$r->status }}</span>
                    </div>
                    @empty
                    <p class="text-sm text-gray-600 py-4">Rezervasiya tapılmadı</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
