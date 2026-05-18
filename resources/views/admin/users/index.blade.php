@extends('admin.layout')
@section('title', 'İstifadəçilər')

@section('content')
<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yeni istifadəçi
    </a>
</div>

<form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex flex-wrap gap-3">
    <input name="search" value="{{ request('search') }}" placeholder="Ad / Email axtar..." class="bg-gray-800 border border-gray-700 text-gray-200 placeholder-gray-500 rounded-lg px-3 py-2 text-sm w-52 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500">
    <select name="role" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40">
        <option value="">Bütün rollar</option>
        @foreach($roles as $role)
            <option value="{{ $role }}" @selected(request('role')==$role)>{{ ucfirst($role) }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">Axtar</button>
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-300 self-center transition-colors">Sıfırla</a>
</form>

<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 border-b border-gray-700">
            <tr>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Ad</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Email</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Rol</th>
                <th class="px-4 py-3 text-left font-medium text-gray-400">Status</th>
                <th class="px-4 py-3 text-right font-medium text-gray-400">Əməliyyat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-800">
            @forelse($users as $user)
            <tr class="hover:bg-gray-800/60 transition-colors">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-indigo-500/20 text-indigo-400 rounded-full flex items-center justify-center text-xs font-bold">{{ $user->initials() }}</div>
                        <span class="font-medium text-gray-200">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-400">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    @foreach($user->roles as $role)
                    @php $rc=['admin'=>'bg-red-500/20 text-red-400','agent'=>'bg-indigo-500/20 text-indigo-400','customer'=>'bg-green-500/20 text-green-400']; @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $rc[$role->name]??'bg-gray-500/20 text-gray-400' }}">{{ ucfirst($role->name) }}</span>
                    @endforeach
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $user->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                        {{ $user->is_active ? 'Aktiv' : 'Deaktiv' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-400 hover:text-yellow-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-gray-400 hover:text-blue-600" title="{{ $user->is_active ? 'Deaktiv et' : 'Aktiv et' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $user->is_active ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-600">İstifadəçi tapılmadı</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-800">{{ $users->links() }}</div>
</div>
@endsection
