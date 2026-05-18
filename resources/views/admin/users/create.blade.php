@extends('admin.layout')
@section('title', 'Yeni İstifadəçi')

@section('content')
<div class="max-w-xl">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <h2 class="text-lg font-semibold text-white mb-6">Yeni istifadəçi yarat</h2>
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-lg text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Ad Soyad *</label>
                <input name="name" value="{{ old('name') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Email *</label>
                <input name="email" type="email" value="{{ old('email') }}" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Telefon</label>
                <input name="phone" value="{{ old('phone') }}" class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Rol *</label>
                <select name="role" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
                    <option value="admin">Admin</option>
                    <option value="agent">Agent</option>
                    <option value="customer">Müştəri</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Şifrə *</label>
                <input name="password" type="password" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-1">Şifrə (təkrar) *</label>
                <input name="password_confirmation" type="password" required class="w-full bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">Yarat</button>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-700 text-gray-300 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors">Ləğv et</a>
            </div>
        </form>
    </div>
</div>
@endsection
