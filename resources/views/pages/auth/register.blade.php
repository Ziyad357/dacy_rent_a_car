<x-layouts::auth :title="__('Qeydiyyat')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Hesab yaradın')" :description="__('Aşağıdakı məlumatları daxil edin')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Ad Soyad -->
            <flux:input
                name="name"
                label="Ad Soyad"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Ad Soyadınız"
            />

            <!-- Email -->
            <flux:input
                name="email"
                label="Email ünvanı"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Telefon -->
            <flux:input
                name="phone"
                label="Telefon nömrəsi"
                :value="old('phone')"
                type="tel"
                required
                placeholder="+994501234567"
            />

            <!-- FIN -->
            <flux:input
                name="id_number"
                label="FIN kod"
                :value="old('id_number')"
                type="text"
                required
                placeholder="FIN kodunuz"
            />

            <!-- Sürücülük vəsiqəsi nömrəsi -->
            <flux:input
                name="license_number"
                label="Sürücülük vəsiqəsi nömrəsi"
                :value="old('license_number')"
                type="text"
                required
                placeholder="SV nömrəsi"
            />

            <!-- Sürücülük vəsiqəsi bitmə tarixi -->
            <flux:input
                name="license_expiry"
                label="Sürücülük vəsiqəsi bitmə tarixi"
                :value="old('license_expiry')"
                type="date"
                required
            />

            <!-- Doğum tarixi -->
            <flux:input
                name="date_of_birth"
                label="Doğum tarixi"
                :value="old('date_of_birth')"
                type="date"
                required
            />

            <!-- Ünvan -->
            <flux:input
                name="address"
                label="Ünvan"
                :value="old('address')"
                type="text"
                required
                placeholder="Yaşayış ünvanınız"
            />

            <!-- Şifrə -->
            <flux:input
                name="password"
                label="Şifrə"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Şifrə"
                viewable
            />

            <!-- Şifrə təkrar -->
            <flux:input
                name="password_confirmation"
                label="Şifrəni təkrarlayın"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Şifrəni təkrarlayın"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    Qeydiyyatdan keç
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>Hesabınız var?</span>
            <flux:link :href="route('login')" wire:navigate>Daxil olun</flux:link>
        </div>
    </div>
</x-layouts::auth>
