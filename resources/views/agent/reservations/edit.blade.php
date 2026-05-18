@extends('agent.layout')
@section('title', 'Rezervasiyanı Düzəlt')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('agent.reservations.show', $reservation) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-6">Rezervasiya #{{ $reservation->id }} — Düzəliş</h2>
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('agent.reservations.update', $reservation) }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Götürmə yeri *</label>
                <input name="pickup_location" value="{{ old('pickup_location', $reservation->pickup_location) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Qaytarma yeri *</label>
                <input name="return_location" value="{{ old('return_location', $reservation->return_location) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Qeyd</label>
                <textarea name="notes" rows="3" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm resize-none">{{ old('notes', $reservation->notes) }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">Yadda saxla</button>
                <a href="{{ route('agent.reservations.show', $reservation) }}" class="bg-gray-700 text-gray-300 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">Ləğv et</a>
            </div>
        </form>
    </div>
</div>
@endsection
