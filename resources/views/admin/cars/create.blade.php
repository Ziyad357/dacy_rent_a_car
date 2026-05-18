@extends('admin.layout')
@section('title', 'Yeni Avtomobil')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.cars.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>

    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-6">Yeni avtomobil əlavə et</h2>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.cars.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Marka *</label>
                    <input name="brand" value="{{ old('brand') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Model *</label>
                    <input name="model" value="{{ old('model') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">İl *</label>
                    <input name="year" type="number" value="{{ old('year', date('Y')) }}" min="1990" max="{{ date('Y')+1 }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Dövlət nömrəsi *</label>
                    <input name="plate_number" value="{{ old('plate_number') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 uppercase" style="text-transform:uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Rəng *</label>
                    <input name="color" value="{{ old('color') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Oturacaq sayı *</label>
                    <input name="seats" type="number" value="{{ old('seats', 5) }}" min="1" max="20" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Yanacaq növü *</label>
                    <select name="fuel_type" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                        <option value="petrol" @selected(old('fuel_type')=='petrol')>Benzin</option>
                        <option value="diesel" @selected(old('fuel_type')=='diesel')>Dizel</option>
                        <option value="electric" @selected(old('fuel_type')=='electric')>Elektrik</option>
                        <option value="hybrid" @selected(old('fuel_type')=='hybrid')>Hibrid</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Ötürücü *</label>
                    <select name="transmission" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                        <option value="manual" @selected(old('transmission')=='manual')>Mexaniki</option>
                        <option value="automatic" @selected(old('transmission')=='automatic')>Avtomat</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Kilometraj *</label>
                    <input name="mileage" type="number" value="{{ old('mileage', 0) }}" min="0" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Gündəlik qiymət (₼) *</label>
                    <input name="daily_rate" type="number" step="0.01" value="{{ old('daily_rate') }}" min="0" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Depozit məbləği (₼) *</label>
                    <input name="deposit_amount" type="number" step="0.01" value="{{ old('deposit_amount') }}" min="0" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Status *</label>
                    <select name="status" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                        <option value="available" @selected(old('status','available')=='available')>Boş</option>
                        <option value="maintenance" @selected(old('status')=='maintenance')>Texniki xidmət</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Şəkil</label>
                <input name="image" type="file" accept="image/*" class="w-full bg-gray-800 border border-gray-700 text-gray-400 rounded-lg px-3 py-2 text-sm focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Təsvir</label>
                <textarea name="description" rows="3" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">Əlavə et</button>
                <a href="{{ route('admin.cars.index') }}" class="bg-gray-700 text-gray-300 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">Ləğv et</a>
            </div>
        </form>
    </div>
</div>
@endsection
