@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
    @php
        $cards = [
            ['label' => 'Bu gün icarədə',       'value' => $stats['cars_rented_today'],                    'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z',                'color' => 'blue'],
            ['label' => 'Bu gün dönəcək',       'value' => $stats['returning_today'],                      'icon' => 'M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6',                                       'color' => 'orange'],
            ['label' => 'Aylıq gəlir (AZN)',    'value' => number_format($stats['monthly_revenue'], 2),   'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green'],
            ['label' => 'Ödənilməmiş cərimə',  'value' => number_format($stats['unpaid_penalties'], 2) . ' ₼', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'red'],
            ['label' => 'Boş avtomobil',        'value' => $stats['available_cars'],                       'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                                   'color' => 'teal'],
            ['label' => 'Aktiv müştəri',        'value' => $stats['active_customers'],                     'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'purple'],
        ];
        $colorMap = [
            'blue'   => ['icon' => 'text-sky-400',    'bg' => 'rgba(14,165,233,0.12)',  'glow' => '0 0 16px rgba(14,165,233,0.15)'],
            'orange' => ['icon' => 'text-orange-400', 'bg' => 'rgba(249,115,22,0.12)', 'glow' => '0 0 16px rgba(249,115,22,0.15)'],
            'green'  => ['icon' => 'text-emerald-400','bg' => 'rgba(16,185,129,0.12)', 'glow' => '0 0 16px rgba(16,185,129,0.15)'],
            'red'    => ['icon' => 'text-red-400',    'bg' => 'rgba(239,68,68,0.12)',  'glow' => '0 0 16px rgba(239,68,68,0.15)'],
            'teal'   => ['icon' => 'text-teal-400',   'bg' => 'rgba(20,184,166,0.12)', 'glow' => '0 0 16px rgba(20,184,166,0.15)'],
            'purple' => ['icon' => 'text-violet-400', 'bg' => 'rgba(139,92,246,0.12)', 'glow' => '0 0 16px rgba(139,92,246,0.15)'],
        ];
    @endphp

    @foreach($cards as $card)
    @php $c = $colorMap[$card['color']]; @endphp
    <div class="theme-card rounded-2xl p-5 flex items-center gap-4 group transition-all duration-300 hover:-translate-y-0.5">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $c['icon'] }}"
             style="background: {{ $c['bg'] }}; box-shadow: {{ $c['glow'] }};">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $card['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-600 uppercase tracking-wider">{{ $card['label'] }}</p>
            <p class="text-2xl font-bold text-white mt-0.5">{{ $card['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="theme-card lg:col-span-2 rounded-2xl p-5">
        <h3 class="text-sm font-semibold text-gray-200 mb-4 tracking-wide">Son 30 günün gəliri (AZN)</h3>
        <canvas id="revenueChart" height="100"></canvas>
    </div>
    <div class="theme-card rounded-2xl p-5">
        <h3 class="text-sm font-semibold text-gray-200 mb-4 tracking-wide">Avtomobil statusları</h3>
        <canvas id="statusChart" height="200"></canvas>
        <div id="statusLegend" class="mt-3 space-y-1 text-sm"></div>
    </div>
</div>

{{-- Recent Reservations --}}
<div class="theme-card rounded-2xl p-5">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-sm font-semibold text-gray-200 tracking-wide">Son Rezervasiyalar</h3>
        <a href="{{ route('admin.reservations.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 transition-colors">Hamısına bax →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-800">
                    <th class="pb-3 text-left font-medium text-gray-500">Müştəri</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Avtomobil</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Tarix</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Məbləğ</th>
                    <th class="pb-3 text-left font-medium text-gray-500">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($recentReservations as $r)
                <tr class="hover:bg-gray-800/50 transition-colors">
                    <td class="py-3 font-medium text-gray-200">{{ $r->customer->full_name ?? '-' }}</td>
                    <td class="py-3 text-gray-400">{{ $r->car->brand ?? '' }} {{ $r->car->model ?? '' }}</td>
                    <td class="py-3 text-gray-500">{{ $r->start_date->format('d.m.Y') }} – {{ $r->end_date->format('d.m.Y') }}</td>
                    <td class="py-3 font-medium">{{ number_format($r->total_amount, 2) }} ₼</td>
                    <td class="py-3">
                        @php
                            $badge = ['pending'=>'bg-amber-500/20 text-amber-400','approved'=>'bg-indigo-500/20 text-indigo-400','active'=>'bg-green-500/20 text-green-400','completed'=>'bg-gray-500/20 text-gray-400','cancelled'=>'bg-red-500/20 text-red-400'];
                            $labels = ['pending'=>'Gözləyir','approved'=>'Təsdiqlənib','active'=>'Aktiv','completed'=>'Tamamlandı','cancelled'=>'Ləğv edilib'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge[$r->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $labels[$r->status] ?? $r->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-600">Rezervasiya tapılmadı</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
window.addEventListener('load', function () {
const revenueData = @json($revenueChart);
const statusData  = @json($statusChart);

// Revenue Line Chart
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revenueData.map(d => d.date),
        datasets: [{
            label: 'Gəlir (₼)',
            data: revenueData.map(d => d.revenue),
            borderColor: 'rgba(99,102,241,0.9)',
            backgroundColor: function(context) {
                const ctx = context.chart.ctx;
                const gradient = ctx.createLinearGradient(0, 0, 0, 160);
                gradient.addColorStop(0, 'rgba(99,102,241,0.2)');
                gradient.addColorStop(1, 'rgba(99,102,241,0)');
                return gradient;
            },
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: 'rgba(99,102,241,1)',
            pointBorderColor: 'rgba(13,15,30,1)',
            pointBorderWidth: 2,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#4b5563', font: { size: 11 } }, border: { display: false } },
            x: { grid: { display: false }, ticks: { color: '#4b5563', font: { size: 11 } }, border: { display: false } }
        }
    }
});

// Status Donut Chart
const statusColors = { available: '#22c55e', rented: '#3b82f6', maintenance: '#f59e0b', reserved: '#a855f7' };
const statusLabels = { available: 'Boş', rented: 'İcarədə', maintenance: 'Texniki xidmət', reserved: 'Rezerv' };
const labels = Object.keys(statusData);
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: labels.map(l => statusLabels[l] ?? l),
        datasets: [{ data: labels.map(l => statusData[l]), backgroundColor: labels.map(l => statusColors[l] ?? '#94a3b8') }]
    },
    options: { plugins: { legend: { display: false } }, cutout: '70%' }
});
}); // end load
</script>
@endpush
@endsection
