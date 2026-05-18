@extends('agent.layout')
@section('title', $contract->contract_number)

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('agent.contracts.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="md:col-span-2 bg-gray-900 rounded-xl border border-gray-800 p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-semibold text-white">{{ $contract->contract_number }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">İmzalandı: {{ $contract->signed_at?->format('d.m.Y H:i') }}</p>
                </div>
                <a href="{{ route('agent.contracts.pdf', $contract) }}" class="bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs hover:bg-emerald-500 transition-colors">PDF yüklə</a>
            </div>

            <dl class="grid grid-cols-2 gap-3 text-sm mb-5">
                <div><dt class="text-gray-500">Müştəri</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $contract->reservation?->customer?->full_name }}</dd></div>
                <div><dt class="text-gray-500">Avtomobil</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $contract->reservation?->car?->brand }} {{ $contract->reservation?->car?->model }}</dd></div>
                <div><dt class="text-gray-500">Yanacaq (verilən)</dt><dd class="font-medium capitalize text-gray-200 mt-0.5">{{ $contract->fuel_level_out }}</dd></div>
                <div><dt class="text-gray-500">Yanacaq (qaytarılan)</dt><dd class="font-medium capitalize text-gray-200 mt-0.5">{{ $contract->fuel_level_in ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Km (verilən)</dt><dd class="font-medium text-gray-200 mt-0.5">{{ number_format($contract->mileage_out) }}</dd></div>
                <div><dt class="text-gray-500">Km (qaytarılan)</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $contract->mileage_in ? number_format($contract->mileage_in) : '—' }}</dd></div>
                <div><dt class="text-gray-500">Qaytarıldı</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $contract->returned_at?->format('d.m.Y') ?? 'Açıqdır' }}</dd></div>
            </dl>

            @if(!$contract->returned_at)
            <div x-data="{open:false}" class="border-t border-gray-800 pt-4">
                <button @click="open=!open" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-400 transition-colors">Avtomobili qəbul et (müqaviləni bağla)</button>
                <form x-show="open" x-transition method="POST" action="{{ route('agent.contracts.close', $contract) }}" class="mt-4 space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Yanacaq səviyyəsi *</label>
                            <select name="fuel_level_in" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm">
                                <option value="full">Tam dolu</option>
                                <option value="three_quarters">3/4</option>
                                <option value="half">1/2</option>
                                <option value="quarter">1/4</option>
                                <option value="empty">Boş</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Kilometraj *</label>
                            <input name="mileage_in" type="number" min="{{ $contract->mileage_out }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Vəziyyəti *</label>
                        <textarea name="condition_in" rows="2" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-2 py-1.5 text-sm resize-none"></textarea>
                    </div>
                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-400 transition-colors">Bağla</button>
                </form>
            </div>
            @endif

            @if($contract->penalties->count())
            <div class="border-t border-gray-800 pt-4 mt-4">
                <h3 class="text-sm font-semibold text-gray-300 mb-3">Cərimələr</h3>
                @foreach($contract->penalties as $p)
                <div class="flex justify-between text-sm py-2 border-b border-gray-800 last:border-0">
                    <span class="capitalize text-gray-400">{{ $p->type }} — {{ $p->description }}</span>
                    <span class="font-bold text-red-400">{{ number_format($p->amount,2) }} ₼</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
            <h3 class="text-sm font-semibold text-gray-300 mb-3">Ödənişlər</h3>
            @forelse($contract->reservation?->payments ?? [] as $p)
            <div class="flex justify-between text-xs py-2 border-b border-gray-800 last:border-0">
                <div><span class="capitalize text-gray-400">{{ $p->type }}</span><p class="text-gray-600">{{ $p->paid_at?->format('d.m.Y') }}</p></div>
                <span class="font-medium text-gray-200">{{ number_format($p->amount, 2) }} ₼</span>
            </div>
            @empty
            <p class="text-xs text-gray-600">Ödəniş yoxdur</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
