@extends('agent.layout')
@section('title', 'Müştərini Düzəlt')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('agent.customers.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-6">{{ $customer->full_name }} — Düzəliş</h2>
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('agent.customers.update', $customer) }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-1">Ad Soyad *</label>
                    <input name="full_name" value="{{ old('full_name', $customer->full_name) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Email *</label>
                    <input name="email" type="email" value="{{ old('email', $customer->email) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Telefon *</label>
                    <input name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">FIN kodu *</label>
                    <input name="id_number" value="{{ old('id_number', $customer->id_number) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Doğum tarixi *</label>
                    <input name="date_of_birth" type="date" value="{{ old('date_of_birth', $customer->date_of_birth?->format('Y-m-d')) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Sürücülük vəsiqəsi *</label>
                    <input name="license_number" value="{{ old('license_number', $customer->license_number) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">SV bitmə tarixi *</label>
                    <input name="license_expiry" type="date" value="{{ old('license_expiry', $customer->license_expiry?->format('Y-m-d')) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-1">Ünvan *</label>
                    <textarea name="address" rows="2" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500/40">{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">Yadda saxla</button>
                <a href="{{ route('agent.customers.index') }}" class="bg-gray-700 text-gray-300 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">Ləğv et</a>
            </div>
        </form>
    </div>
</div>
@endsection
