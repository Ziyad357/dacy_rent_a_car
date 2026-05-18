<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Giriş' }} — DaCy Rent a Car</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white antialiased font-sans">

<div class="flex min-h-screen">

    {{-- Left: Brand Panel --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 flex-col justify-between p-12">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-500/10 rounded-full translate-y-1/2 -translate-x-1/3 blur-3xl"></div>
        <div class="absolute inset-0 opacity-5"
             style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

        {{-- Logo --}}
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-xl tracking-tight">DaCy <span class="text-blue-400">Rent a Car</span></p>
                    <p class="text-slate-400 text-xs">İdarəetmə Sistemi</p>
                </div>
            </div>
        </div>

        {{-- Center content --}}
        <div class="relative z-10 space-y-6">
            <h1 class="text-4xl xl:text-5xl font-bold text-white leading-tight">
                Avtomobil icarəsini<br>
                <span class="text-blue-400">asanlaşdırın</span>
            </h1>
            <p class="text-slate-400 text-lg max-w-md leading-relaxed">
                Rezervasiyaları izləyin, müqavilələri idarə edin, hesabatları analiz edin — hamısı bir yerdə.
            </p>

            {{-- Feature pills --}}
            <div class="flex flex-wrap gap-2 pt-2">
                @foreach(['Rezervasiya idarəsi', 'Müqavilə yaratma', 'Hesabatlar', 'Cərimə sistemi'] as $f)
                <span class="px-3 py-1.5 bg-white/10 text-slate-300 text-sm rounded-full border border-white/10 backdrop-blur-sm">
                    {{ $f }}
                </span>
                @endforeach
            </div>
        </div>

        {{-- Bottom stats --}}
        <div class="relative z-10 flex gap-8">
            @foreach([['129+','Avtomobil'], ['50+','Müştəri'], ['100+','Rezervasiya']] as [$num, $label])
            <div>
                <p class="text-white font-bold text-2xl">{{ $num }}</p>
                <p class="text-slate-400 text-sm">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Right: Form Panel --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 sm:px-12 xl:px-16 bg-gray-50">
        {{-- Mobile logo --}}
        <div class="lg:hidden mb-8 text-center">
            <div class="inline-flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4"/>
                    </svg>
                </div>
                <span class="text-slate-900 font-bold text-xl">DaCy <span class="text-blue-600">Rent a Car</span></span>
            </div>
        </div>

        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</div>

</body>
</html>
