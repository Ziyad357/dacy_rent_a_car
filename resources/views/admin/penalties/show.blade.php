@extends('admin.layout')
@section('title', 'Cərimə #' . $penalty->id)

@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.penalties.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-5">Cərimə məlumatları</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Müştəri</dt><dd class="font-medium text-gray-200">{{ $penalty->contract?->reservation?->customer?->full_name ?? '-' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Müqavilə</dt><dd class="font-mono font-bold text-indigo-400">{{ $penalty->contract?->contract_number }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Növ</dt><dd class="capitalize font-medium text-gray-200">{{ $penalty->type }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Açıqlama</dt><dd class="font-medium text-gray-200 max-w-xs text-right">{{ $penalty->description }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Məbləğ</dt><dd class="font-bold text-red-400 text-base">{{ number_format($penalty->amount, 2) }} ₼</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Status</dt>
                <dd><span class="px-2 py-0.5 rounded-full text-xs {{ $penalty->paid ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">{{ $penalty->paid ? 'Ödənilib' : 'Ödənilməyib' }}</span></dd>
            </div>
            @if($penalty->paid_at)
            <div class="flex justify-between"><dt class="text-gray-500">Ödəniş tarixi</dt><dd class="font-medium text-gray-200">{{ $penalty->paid_at->format('d.m.Y H:i') }}</dd></div>
            @endif
        </dl>
        @if(!$penalty->paid)
        <form method="POST" action="{{ route('admin.penalties.pay', $penalty) }}" class="mt-5">
            @csrf @method('PATCH')
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-500 transition-colors">Ödənilib kimi qəyd et</button>
        </form>
        @endif
    </div>
</div>
@endsection
