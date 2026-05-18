<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — {{ config('app.name') }}</title>
    <script>(function(){var t=localStorage.getItem('theme')||'dark';document.documentElement.setAttribute('data-theme',t);})();</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: #07080f; }
        .sidebar-bg { background: linear-gradient(180deg, #0d0f1e 0%, #090b18 60%, #07080f 100%); }
        .sidebar-active { background: linear-gradient(135deg, rgba(99,102,241,0.25) 0%, rgba(139,92,246,0.15) 100%); border: 1px solid rgba(99,102,241,0.3); }
        .sidebar-hover:hover { background: rgba(255,255,255,0.05); }
        .header-glass { background: rgba(9,10,20,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.06); }
        .content-bg { background: radial-gradient(ellipse at 20% 0%, rgba(99,102,241,0.04) 0%, transparent 60%), #07080f; }
        .logo-glow { box-shadow: 0 0 20px rgba(99,102,241,0.4); }
        .avatar-ring { box-shadow: 0 0 0 2px rgba(99,102,241,0.5), 0 0 12px rgba(99,102,241,0.3); }
        .theme-card { background: rgba(13,15,30,0.9); border: 1px solid rgba(255,255,255,0.07); box-shadow: 0 4px 24px rgba(0,0,0,0.3); }
        .light-icon { display: none; }

        /* ── Light mode overrides ── */
        html[data-theme="light"] body { background: #f1f5f9 !important; }
        html[data-theme="light"] .sidebar-bg { background: linear-gradient(180deg, #0d0f1e 0%, #090b18 100%) !important; }
        html[data-theme="light"] .content-bg { background: #f1f5f9 !important; }
        html[data-theme="light"] .header-glass { background: rgba(255,255,255,0.95) !important; border-bottom: 1px solid #e2e8f0 !important; }
        html[data-theme="light"] .theme-card { background: #ffffff !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 2px 12px rgba(0,0,0,0.06) !important; }
        html[data-theme="light"] .bg-gray-900 { background-color: #ffffff !important; }
        html[data-theme="light"] .bg-gray-800 { background-color: #f8fafc !important; }
        html[data-theme="light"] .border-gray-800, html[data-theme="light"] .border-gray-700 { border-color: #e2e8f0 !important; }
        html[data-theme="light"] .divide-gray-800 > * + * { border-color: #f1f5f9 !important; }
        html[data-theme="light"] .text-white { color: #0f172a !important; }
        html[data-theme="light"] .text-gray-200 { color: #1e293b !important; }
        html[data-theme="light"] .text-gray-300 { color: #334155 !important; }
        html[data-theme="light"] .text-gray-400 { color: #64748b !important; }
        html[data-theme="light"] .text-gray-500 { color: #94a3b8 !important; }
        html[data-theme="light"] .text-gray-600 { color: #94a3b8 !important; }
        html[data-theme="light"] input.bg-gray-800, html[data-theme="light"] select.bg-gray-800, html[data-theme="light"] textarea.bg-gray-800 { background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
        html[data-theme="light"] .hover\:bg-gray-800\/50:hover { background-color: #f8fafc !important; }
        html[data-theme="light"] .dark-icon { display: none !important; }
        html[data-theme="light"] .light-icon { display: block !important; }
        html[data-theme="light"] .header-glass .text-white { color: #0f172a !important; }
        html[data-theme="light"] .header-glass .text-gray-300 { color: #475569 !important; }
        html[data-theme="light"] .header-glass .text-gray-600 { color: #94a3b8 !important; }
        html[data-theme="light"] .avatar-ring { box-shadow: 0 0 0 2px rgba(99,102,241,0.4) !important; }
    </style>
    <script>
        window.toggleTheme = function() {
            const t = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', t);
            localStorage.setItem('theme', t);
        };
    </script>
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: true }">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="sidebar-bg flex flex-col shrink-0 transition-all duration-300 text-white shadow-2xl"
           style="border-right: 1px solid rgba(255,255,255,0.05);"
           :class="sidebarOpen ? 'w-64' : 'w-16'">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="logo-glow w-8 h-8 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div x-show="sidebarOpen">
                <p class="font-bold text-base tracking-tight leading-none text-white">DaCy <span class="text-indigo-400">Rent</span></p>
                <p class="text-[10px] text-gray-500 mt-0.5 tracking-widest uppercase">Admin Panel</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-2 py-4 space-y-0.5 overflow-y-auto">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'admin.cars.index', 'label' => 'Avtomobillər', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4'],
                    ['route' => 'admin.users.index', 'label' => 'İstifadəçilər', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['route' => 'admin.reservations.index', 'label' => 'Rezervasiyalar', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['route' => 'admin.contracts.index', 'label' => 'Müqavilələr', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['route' => 'admin.payments.index', 'label' => 'Ödənişlər', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['route' => 'admin.penalties.index', 'label' => 'Cərimələr', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                    ['route' => 'admin.reports.index', 'label' => 'Hesabatlar', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['route' => 'admin.settings.index', 'label' => 'Parametrlər', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'admin.support.index', 'label' => 'Dəstək', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'routes' => 'admin.support.*'],
                ];
            @endphp

            @php
                $adminUnread = \App\Models\SupportMessage::where('sender_role', 'customer')->whereNull('read_at')->count();
            @endphp
            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['route']) || (isset($item['routes']) && request()->routeIs($item['routes'])); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ $isActive ? 'sidebar-active text-indigo-300' : 'text-gray-500 sidebar-hover hover:text-gray-200' }}">
                    <div class="relative shrink-0">
                        <svg class="w-[18px] h-[18px] {{ $isActive ? 'text-indigo-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive ? '2.5' : '1.8' }}" d="{{ $item['icon'] }}"/>
                        </svg>
                        @if($item['route'] === 'admin.support.index' && $adminUnread > 0)
                            <span class="absolute -top-1.5 -right-1.5 w-3.5 h-3.5 rounded-full text-white flex items-center justify-center font-bold" style="background:#ef4444;font-size:8px;">{{ $adminUnread > 9 ? '9+' : $adminUnread }}</span>
                        @endif
                    </div>
                    <span class="text-sm font-medium truncate" x-show="sidebarOpen">{{ $item['label'] }}</span>
                    @if($isActive)
                    <span class="ml-auto w-1 h-4 bg-indigo-400 rounded-full shrink-0" x-show="sidebarOpen"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- User + Logout --}}
        <div class="px-3 py-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-3 mb-3 px-1" x-show="sidebarOpen">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-gray-200 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-gray-600 truncate">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-gray-600 hover:text-red-400 hover:bg-red-500/10 transition-all duration-200 text-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span x-show="sidebarOpen">Çıxış</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex flex-col flex-1 overflow-hidden">

        {{-- Top Navbar --}}
        <header class="header-glass flex items-center justify-between px-6 py-3.5">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-600 hover:text-gray-300 hover:bg-white/5 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-sm font-semibold text-white leading-none">@yield('title', 'Dashboard')</h1>
                    <p class="text-[10px] text-gray-600 mt-0.5">{{ config('app.name') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.toggleTheme()" title="Tema dəyiş"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-gray-300 hover:bg-white/5 transition-all duration-200">
                    <svg class="dark-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="light-icon w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-medium text-gray-300">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-gray-600">Admin</p>
                </div>
                <div class="avatar-ring w-8 h-8 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                    {{ auth()->user()->initials() }}
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="content-bg flex-1 overflow-y-auto p-6">
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
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
