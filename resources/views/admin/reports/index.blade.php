@extends('admin.layout')
@section('title', 'Hesabatlar')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <a href="{{ route('admin.reports.daily') }}" class="bg-gray-900 rounded-xl border border-gray-800 p-6 hover:border-indigo-700/50 hover:bg-gray-800 transition-all flex items-center gap-4 group">
        <div class="w-12 h-12 bg-indigo-500/15 text-indigo-400 rounded-xl flex items-center justify-center group-hover:bg-indigo-500/25 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <h3 class="font-semibold text-white">Günlük hesabat</h3>
            <p class="text-sm text-gray-500 mt-0.5">Seçilmiş gün üzrə statistika</p>
        </div>
    </a>

    <a href="{{ route('admin.reports.monthly') }}" class="bg-gray-900 rounded-xl border border-gray-800 p-6 hover:border-emerald-700/50 hover:bg-gray-800 transition-all flex items-center gap-4 group">
        <div class="w-12 h-12 bg-emerald-500/15 text-emerald-400 rounded-xl flex items-center justify-center group-hover:bg-emerald-500/25 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div>
            <h3 class="font-semibold text-white">Aylıq hesabat</h3>
            <p class="text-sm text-gray-500 mt-0.5">Ay üzrə gəlir və statistika</p>
        </div>
    </a>

    <a href="{{ route('admin.reports.utilization') }}" class="bg-gray-900 rounded-xl border border-gray-800 p-6 hover:border-purple-700/50 hover:bg-gray-800 transition-all flex items-center gap-4 group">
        <div class="w-12 h-12 bg-purple-500/15 text-purple-400 rounded-xl flex items-center justify-center group-hover:bg-purple-500/25 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
        </div>
        <div>
            <h3 class="font-semibold text-white">Avtomobil istifadəsi</h3>
            <p class="text-sm text-gray-500 mt-0.5">Utilizasiya faizi</p>
        </div>
    </a>
</div>
@endsection
