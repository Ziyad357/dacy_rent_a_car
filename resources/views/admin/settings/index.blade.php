@extends('admin.layout')
@section('title', 'Parametrlər')

@section('content')
<div class="max-w-xl">
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-6">Sistem parametrləri</h2>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Şirkət adı *</label>
                <input name="company_name" value="{{ old('company_name', $settings['company_name']) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Ünvan</label>
                <input name="company_address" value="{{ old('company_address', $settings['company_address']) }}" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                <input name="company_email" type="email" value="{{ old('company_email', $settings['company_email']) }}" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Telefon</label>
                <input name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Valyuta *</label>
                <input name="currency" value="{{ old('currency', $settings['currency']) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Gecikmə cəriməsi əmsalı (günlük qiymət × X)</label>
                <input name="late_penalty_rate" type="number" step="0.01" min="0" max="10" value="{{ old('late_penalty_rate', $settings['late_penalty_rate']) }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
                <p class="text-xs text-gray-600 mt-1">Standart: 0.5 (günlük qiymət × 0.5 × gecikmə günü)</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Loqo</label>
                <input name="logo" type="file" accept="image/*" class="w-full bg-gray-800 border border-gray-700 text-gray-400 rounded-lg px-3 py-2 text-sm focus:outline-none">
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">Yadda saxla</button>
        </form>
    </div>
</div>
@endsection
