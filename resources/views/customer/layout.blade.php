<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Müştəri Panel') — {{ config('app.name') }}</title>
    <script>(function(){var t=localStorage.getItem('theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #07080f; }
        .cust-nav { background: rgba(9,10,20,0.88); backdrop-filter: blur(24px); border-bottom: 1px solid rgba(255,255,255,0.06); }
        .cust-content-bg { background: radial-gradient(ellipse at 60% 0%, rgba(59,130,246,0.04) 0%, transparent 60%), #07080f; }
        .cust-nav-active { background: rgba(59,130,246,0.15); color: #93c5fd !important; border-radius: 0.5rem; }
        .cust-nav-link { color: #6b7280; transition: color 0.2s, background 0.2s; border-radius: 0.5rem; }
        .cust-nav-link:hover { color: #e2e8f0; background: rgba(255,255,255,0.05); }
        .theme-card { background: rgba(13,15,30,0.9); border: 1px solid rgba(255,255,255,0.07); box-shadow: 0 4px 24px rgba(0,0,0,0.3); }
        .cust-avatar { box-shadow: 0 0 0 2px rgba(59,130,246,0.5), 0 0 12px rgba(59,130,246,0.25); }
        .light-icon { display: none; }

        /* ── Light mode overrides ── */
        html[data-theme="light"] body { background: #f1f5f9 !important; }
        html[data-theme="light"] .cust-nav { background: rgba(255,255,255,0.96) !important; border-bottom: 1px solid #e2e8f0 !important; }
        html[data-theme="light"] .cust-content-bg { background: #f1f5f9 !important; }
        html[data-theme="light"] .cust-nav-active { background: rgba(59,130,246,0.1) !important; color: #2563eb !important; }
        html[data-theme="light"] .cust-nav-link { color: #475569 !important; }
        html[data-theme="light"] .cust-nav-link:hover { color: #0f172a !important; background: rgba(0,0,0,0.04) !important; }
        html[data-theme="light"] .theme-card { background: #ffffff !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 2px 12px rgba(0,0,0,0.06) !important; }
        html[data-theme="light"] .bg-gray-900 { background-color: #ffffff !important; }
        html[data-theme="light"] .bg-gray-800 { background-color: #f8fafc !important; }
        html[data-theme="light"] .bg-white { background-color: #ffffff !important; }
        html[data-theme="light"] .border-gray-800, html[data-theme="light"] .border-gray-700 { border-color: #e2e8f0 !important; }
        html[data-theme="light"] .border-gray-100 { border-color: #e2e8f0 !important; }
        html[data-theme="light"] .border-gray-50 { border-color: #f1f5f9 !important; }
        html[data-theme="light"] .divide-gray-800 > * + *, html[data-theme="light"] .divide-gray-50 > * + * { border-color: #f1f5f9 !important; }
        html[data-theme="light"] .text-white { color: #0f172a !important; }
        html[data-theme="light"] .text-gray-900 { color: #0f172a !important; }
        html[data-theme="light"] .text-gray-800 { color: #1e293b !important; }
        html[data-theme="light"] .text-gray-700 { color: #334155 !important; }
        html[data-theme="light"] .text-gray-600 { color: #475569 !important; }
        html[data-theme="light"] .text-gray-500 { color: #64748b !important; }
        html[data-theme="light"] .text-gray-400 { color: #94a3b8 !important; }
        html[data-theme="light"] .hover\:bg-gray-50:hover { background-color: #f8fafc !important; }
        html[data-theme="light"] .hover\:bg-gray-800\/50:hover { background-color: #f8fafc !important; }
        html[data-theme="light"] input, html[data-theme="light"] select, html[data-theme="light"] textarea { background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
        html[data-theme="light"] .dark-icon { display: none !important; }
        html[data-theme="light"] .light-icon { display: block !important; }
        html[data-theme="light"] .cust-nav .text-white { color: #0f172a !important; }
        html[data-theme="light"] .bg-gradient-to-r.from-blue-600 { background: linear-gradient(to right, #1d4ed8, #4338ca) !important; }
        /* Car cards in step 2 */
        html[data-theme="light"] .car-card { background: #ffffff !important; border-color: #e2e8f0 !important; box-shadow: 0 2px 12px rgba(0,0,0,0.06) !important; }
        html[data-theme="light"] .car-card:hover { border-color: #bfdbfe !important; box-shadow: 0 4px 20px rgba(59,130,246,0.12) !important; }
    </style>
    <script>
        window.toggleTheme = function() {
            const t = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', t);
            localStorage.setItem('theme', t);
        };
    </script>
</head>
<body class="font-sans antialiased" x-data="{ mobileMenu: false }">

{{-- Top Nav --}}
<nav class="cust-nav sticky top-0 z-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-14">

            {{-- Logo + Nav links --}}
            <div class="flex items-center gap-6">
                <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <div class="w-7 h-7 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center" style="box-shadow: 0 0 16px rgba(59,130,246,0.35);">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4"/>
                        </svg>
                    </div>
                    <span class="font-bold text-sm tracking-tight text-white">DaCy <span class="text-blue-400">Rent</span></span>
                </a>

                <div class="hidden sm:flex items-center gap-0.5">
                    @php
                        $unreadSupport = \App\Models\SupportMessage::where('user_id', auth()->id())->where('sender_role', 'admin')->whereNull('read_at')->count();
                        $links = [
                            ['route' => 'customer.dashboard',          'label' => 'Ana Səhifə'],
                            ['route' => 'customer.reservations.index', 'label' => 'Rezervasiyalarım'],
                            ['route' => 'customer.penalties.index',    'label' => 'Cərimələrim'],
                            ['route' => 'customer.profile.show',       'label' => 'Profil'],
                            ['route' => 'customer.support.index',      'label' => 'Dəstək', 'badge' => $unreadSupport],
                        ];
                    @endphp
                    @foreach($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="px-3 py-1.5 text-sm font-medium transition-all duration-200 cust-nav-link flex items-center gap-1.5 {{ request()->routeIs($link['route']) ? 'cust-nav-active' : '' }}">
                        {{ $link['label'] }}
                        @if(!empty($link['badge']) && $link['badge'] > 0)
                            <span class="w-4 h-4 rounded-full text-white text-xs flex items-center justify-center font-bold" style="background:#ef4444;font-size:10px;">{{ $link['badge'] }}</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Right: toggle + user --}}
            <div class="flex items-center gap-2">
                <button onclick="window.toggleTheme()" title="Tema dəyiş"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 hover:text-gray-300 hover:bg-white/5 transition-all duration-200">
                    <svg class="dark-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="light-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <div class="hidden sm:flex items-center gap-2.5">
                    <div class="cust-avatar w-7 h-7 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ auth()->user()->initials() }}
                    </div>
                    <span class="text-sm text-gray-300 font-medium">{{ auth()->user()->name }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-600 hover:text-red-400 transition-colors px-2 py-1.5 rounded-lg hover:bg-red-500/10">Çıxış</button>
                </form>

                <button @click="mobileMenu = !mobileMenu" class="sm:hidden w-8 h-8 flex items-center justify-center text-gray-500 hover:text-gray-300 rounded-lg hover:bg-white/5 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="sm:hidden pb-3 pt-2 space-y-0.5" style="border-top: 1px solid rgba(255,255,255,0.06);">
            @foreach($links as $link)
            <a href="{{ route($link['route']) }}"
               class="block px-3 py-2 rounded-lg text-sm font-medium cust-nav-link {{ request()->routeIs($link['route']) ? 'cust-nav-active' : '' }}">
                {{ $link['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</nav>

{{-- Content --}}
<main class="cust-content-bg min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-7">
        @if(session('success'))
            <div class="mb-5 p-3.5 rounded-xl flex items-center gap-3 text-sm"
                 style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2);">
                <div class="w-5 h-5 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-emerald-400">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 p-3.5 rounded-xl flex items-center gap-3 text-sm"
                 style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
                <div class="w-5 h-5 rounded-full bg-red-500/20 flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <span class="text-red-400">{{ session('error') }}</span>
            </div>
        @endif
        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>
