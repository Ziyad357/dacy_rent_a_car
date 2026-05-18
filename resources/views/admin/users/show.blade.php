@extends('admin.layout')
@section('title', $user->name)

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-300 mb-4 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Geri
    </a>
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-indigo-500/20 text-indigo-400 rounded-full flex items-center justify-center text-xl font-bold">{{ $user->initials() }}</div>
            <div>
                <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <div class="flex gap-2 mt-1">
                    @foreach($user->roles as $role)
                    @php $rc=['admin'=>'bg-red-500/20 text-red-400','agent'=>'bg-indigo-500/20 text-indigo-400','customer'=>'bg-green-500/20 text-green-400']; @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $rc[$role->name]??'bg-gray-100' }}">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div><dt class="text-gray-500">Telefon</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $user->phone ?? '-' }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">{{ $user->is_active ? 'Aktiv' : 'Deaktiv' }}</span></dd></div>
            <div><dt class="text-gray-500">Qeydiyyat tarixi</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $user->created_at->format('d.m.Y') }}</dd></div>
        </dl>
        @if($user->customer)
        <div class="mt-6 border-t border-gray-800 pt-5">
            <h3 class="text-sm font-semibold text-gray-300 mb-3">Müştəri məlumatları</h3>
            <dl class="grid grid-cols-2 gap-3 text-sm">
                <div><dt class="text-gray-500">FIN</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $user->customer->id_number }}</dd></div>
                <div><dt class="text-gray-500">Sürücülük vəsiqəsi</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $user->customer->license_number }}</dd></div>
                <div><dt class="text-gray-500">SV bitmə tarixi</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $user->customer->license_expiry?->format('d.m.Y') }}</dd></div>
                <div><dt class="text-gray-500">Doğum tarixi</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $user->customer->date_of_birth?->format('d.m.Y') }}</dd></div>
                <div class="col-span-2"><dt class="text-gray-500">Ünvan</dt><dd class="font-medium text-gray-200 mt-0.5">{{ $user->customer->address }}</dd></div>
                @if($user->customer->blacklisted)
                <div class="col-span-2">
                    <div class="p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-sm text-red-400">
                        <strong>Qara siyahıda:</strong> {{ $user->customer->blacklist_reason }}
                    </div>
                </div>
                @endif
            </dl>
        </div>
        @endif
        <div class="mt-5 flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Düzəlt</a>
        </div>
    </div>
</div>
@endsection
