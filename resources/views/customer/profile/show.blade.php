@extends('customer.layout')
@section('title', 'Profilim')

@section('content')
<h1 class="text-xl font-bold text-white mb-5">Profilim</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    {{-- Personal info --}}
    <div class="theme-card rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Şəxsi məlumatlar</h2>

        @if($errors->has('name') || $errors->has('phone') || $errors->has('address'))
            <div class="mb-4 p-3 rounded-lg text-sm text-red-400" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);">
                <ul class="list-disc list-inside">
                    @foreach(['name','phone','address'] as $f)
                        @error($f)<li>{{ $message }}</li>@enderror
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.profile.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Ad Soyad *</label>
                <input name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Email</label>
                <input value="{{ $user->email }}" disabled class="w-full bg-gray-900 border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-600 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Telefon</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Ünvan</label>
                <textarea name="address" rows="2" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">{{ old('address', $user->customer?->address) }}</textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">Yadda saxla</button>
        </form>
    </div>

    {{-- Licence info (read-only) --}}
    @if($user->customer)
    <div class="theme-card rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Sürücdülük məlumatları</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">FIN kodu</dt><dd class="font-mono font-medium text-gray-200">{{ $user->customer->id_number }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Sürücdülük vəsiqəsi</dt><dd class="font-medium text-gray-200">{{ $user->customer->license_number }}</dd></div>
            <div class="flex justify-between">
                <dt class="text-gray-500">SV bitmə tarixi</dt>
                <dd class="{{ $user->customer->license_expiry?->isPast() ? 'text-red-400 font-bold' : 'font-medium text-gray-200' }}">
                    {{ $user->customer->license_expiry?->format('d.m.Y') }}
                    @if($user->customer->license_expiry?->isPast()) <span class="text-xs">⚠ Müddəti bitib</span> @endif
                </dd>
            </div>
            <div class="flex justify-between"><dt class="text-gray-500">Doğum tarixi</dt><dd class="font-medium text-gray-200">{{ $user->customer->date_of_birth?->format('d.m.Y') }}</dd></div>
        </dl>
        <p class="mt-4 text-xs text-gray-600">Bu məlumatları dəyişdirmək üçün agentlə əlaqə saxlayın.</p>
    </div>
    @endif

    {{-- Password change --}}
    <div class="theme-card rounded-xl p-5">
        <h2 class="text-sm font-semibold text-gray-300 mb-4">Şifuə dəyiş</h2>

        @if($errors->has('current_password') || $errors->has('password'))
            <div class="mb-4 p-3 rounded-lg text-sm text-red-400" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);">
                <ul class="list-disc list-inside">
                    @foreach(['current_password','password'] as $f)
                        @error($f)<li>{{ $message }}</li>@enderror
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.profile.password') }}" class="space-y-3">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Cari şifuə *</label>
                <input name="current_password" type="password" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Yeni şifuə *</label>
                <input name="password" type="password" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Yeni şifuə (təkrar) *</label>
                <input name="password_confirmation" type="password" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
            </div>
            <button type="submit" class="bg-slate-700 hover:bg-slate-600 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors">Şifuəni dəyiş</button>
        </form>
    </div>
</div>
@endsection
