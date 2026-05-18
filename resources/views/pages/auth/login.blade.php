<x-layouts::auth :title="__('Giriş')">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 space-y-6">

        {{-- Header --}}
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-gray-900">Xoş gəldiniz</h2>
            <p class="text-sm text-gray-500">Hesabınıza daxil olmaq üçün məlumatlarınızı daxil edin</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div class="space-y-1.5">
                <label for="email" class="text-sm font-medium text-gray-700">Email ünvanı</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email"
                           placeholder="admin@carrent.az"
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all @error('email') border-red-300 @enderror">
                </div>
            </div>

            {{-- Password --}}
            <div class="space-y-1.5" x-data="{ show: false }">
                <div class="flex items-center justify-between">
                    <label for="password" class="text-sm font-medium text-gray-700">Şifrə</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                            Şifrəni unutdum?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password" name="password"
                           :type="show ? 'text' : 'password'"
                           required autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition-all @error('password') border-red-300 @enderror">
                    <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div class="flex items-center gap-2.5">
                <input id="remember" name="remember" type="checkbox"
                       {{ old('remember') ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">Məni xatırla</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                Daxil ol
            </button>
        </form>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500">
                Hesabınız yoxdur?
                <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:text-blue-700 hover:underline transition-colors">
                    Qeydiyyat
                </a>
            </p>
        @endif
    </div>
</x-layouts::auth>
