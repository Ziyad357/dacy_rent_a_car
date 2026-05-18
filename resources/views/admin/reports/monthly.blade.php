@extends('admin.layout')
@section('title', 'Aylıq Hesabat')

@section('content')
<div class="max-w-4xl">
    <form method="GET" class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-5 flex flex-wrap gap-3">
        <select name="year" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
            @for($y = now()->year; $y >= now()->year - 4; $y--)
            <option value="{{ $y }}" @selected($year==$y)>{{ $y }}</option>
            @endfor
        </select>
        <select name="month" class="bg-gray-800 border border-gray-700 text-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none">
            @foreach(range(1,12) as $m)
            <option value="{{ $m }}" @selected($month==$m)>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-500 transition-colors">Göstər</button>
        <a href="{{ route('admin.reports.export', ['year'=>$year,'month'=>$month]) }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-500 transition-colors">CSV İxrac</a>
    </form>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-5">
        @php
            $cards = [
                ['label' => 'Ümumi gəlir', 'value' => number_format($data['total_revenue'], 2) . ' ₼'],
                ['label' => 'Cərimə gəliri', 'value' => number_format($data['penalty_revenue'], 2) . ' ₼'],
                ['label' => 'Rezervasiyalar', 'value' => $data['total_reservations']],
                ['label' => 'Tamamlandı', 'value' => $data['completed_reservations']],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-4">
            <p class="text-xs text-gray-500 mb-1">{{ $card['label'] }}</p>
            <p class="text-xl font-bold text-white">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-gray-900 rounded-xl border border-gray-800 p-5">
        <h3 class="text-sm font-semibold text-gray-300 mb-4">Gündəlik gəlir (AZN)</h3>
        <canvas id="monthlyChart" height="80"></canvas>
    </div>
</div>

@push('scripts')
<script>
window.addEventListener('load', function () {
const breakdown = @json($data['daily_breakdown']);
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: breakdown.map(d => d.date),
        datasets: [{ label: 'Gəlir (₼)', data: breakdown.map(d => d.revenue), backgroundColor: '#6366f1' }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { color: '#6b7280' }, grid: { color: '#1f2937' } }, x: { ticks: { color: '#6b7280' }, grid: { color: '#1f2937' } } } }
});
}); // end load
</script>
@endpush
@endsection
