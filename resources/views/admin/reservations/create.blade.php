@extends('admin.layout')
@section('title', 'Yeni Rezervasiya')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.reservations.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-6">Yeni rezervasiya yarat</h2>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.reservations.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Avtomobil *</label>
                    <select name="car_id" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                        <option value="">Seçin...</option>
                        @foreach($cars as $car)
                        <option value="{{ $car->id }}" @selected(old('car_id')==$car->id)>{{ $car->brand }} {{ $car->model }} ({{ $car->plate_number }}) — {{ number_format($car->daily_rate,2) }} ₼/gün</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Müştəri *</label>
                    <select name="customer_id" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                        <option value="">Seçin...</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id')==$customer->id)>{{ $customer->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Başlama tarixi *</label>
                    <input name="start_date" type="date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Bitmə tarixi *</label>
                    <input name="end_date" type="date" value="{{ old('end_date') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Təhvil yeri *</label>
                    <input name="pickup_location" value="{{ old('pickup_location') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Qaytarma yeri *</label>
                    <input name="return_location" value="{{ old('return_location') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Endirim (%)</label>
                    <input name="discount_percent" type="number" step="0.01" min="0" max="100" value="{{ old('discount_percent', 0) }}" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Qeyd</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm resize-none">{{ old('notes') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">Yarat</button>
                <a href="{{ route('admin.reservations.index') }}" class="bg-gray-700 text-gray-300 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">Ləğv et</a>
            </div>
        </form>
    </div>
</div>
@endsection
