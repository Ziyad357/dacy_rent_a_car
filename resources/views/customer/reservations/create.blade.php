@extends('customer.layout')
@section('title', 'Yeni Rezervasiya')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6" x-data="reservationWizard()">

    {{-- Page header --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('customer.reservations.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl text-gray-500 hover:text-gray-300 transition-all" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-white">Yeni Rezervasiya</h1>
            <p class="text-sm text-gray-500">Tarixin seçin, avtomobil seçin, təsdiq edin</p>
        </div>
    </div>

    {{-- Progress steps --}}
    <div class="flex items-center gap-0 mb-8">
        @foreach([1=>'Tarix seçin', 2=>'Avtomobil seçin', 3=>'Təsdiq'] as $num => $label)
        <div class="flex items-center {{ $num < 3 ? 'flex-1' : '' }}">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300"
                     :class="step >= {{ $num }} ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'text-gray-600'" style="{{ 'background: rgba(255,255,255,0.07)' }}">
                    <span x-show="step > {{ $num }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    <span x-show="step <= {{ $num }}">{{ $num }}</span>
                </div>
                <span class="text-sm font-medium hidden sm:block"
                      :class="step >= {{ $num }} ? 'text-white' : 'text-gray-600'">{{ $label }}</span>
            </div>
            @if($num < 3)
            <div class="flex-1 h-0.5 mx-3 rounded-full transition-all duration-500"
                 :class="step > {{ $num }} ? 'bg-blue-500' : 'bg-gray-800'"></div>
            @endif
        </div>
        @endforeach
    </div>

    @if(session('error'))
    <div class="mb-4 rounded-xl px-4 py-3 flex items-center gap-2 text-sm text-red-400" style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- STEP 1: Date selection --}}
    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="theme-card rounded-2xl p-6 max-w-lg">
            <h2 class="text-base font-semibold text-gray-200 mb-5 flex items-center gap-2">
                <span class="w-7 h-7 text-blue-400 rounded-lg flex items-center justify-center text-xs font-bold" style="background:rgba(59,130,246,0.15);">1</span>
                İcarə tarixlərini seçin
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-400">Başlama tarixi</label>
                    <input type="date" x-model="startDate"
                           :min="today"
                           class="w-full bg-gray-800 border border-gray-700 px-3 py-2.5 rounded-xl text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-400">Bitmə tarixi</label>
                    <input type="date" x-model="endDate"
                           :min="startDate || today"
                           class="w-full bg-gray-800 border border-gray-700 px-3 py-2.5 rounded-xl text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                </div>
            </div>

            <div x-show="startDate && endDate && days > 0" x-transition
                 class="mt-4 rounded-xl px-4 py-3 flex items-center gap-3" style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);">
                <svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-blue-400 font-medium"><span x-text="days"></span> günlük icarə</p>
            </div>

            <button type="button" @click="searchCars()"
                    :disabled="!startDate || !endDate || days <= 0"
                    class="mt-5 w-full py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-blue-500/20 hover:-translate-y-0.5">
                Avtomobilləri göstər
            </button>
        </div>
    </div>

    {{-- STEP 2: Car selection --}}
    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

        {{-- Date summary bar --}}
        <div class="theme-card rounded-xl px-4 py-3 mb-5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-4 text-sm">
                <div class="flex items-center gap-1.5 text-gray-400">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span x-text="formatDate(startDate)"></span>
                    <span class="text-gray-600">→</span>
                    <span x-text="formatDate(endDate)"></span>
                </div>
                <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded-full text-xs font-medium"><span x-text="days"></span> gün</span>
            </div>
            <button type="button" @click="step = 1"
                    class="text-xs text-blue-600 hover:text-blue-700 font-medium hover:underline transition-colors">
                Tarixi dəyiş
            </button>
        </div>

        {{-- Filters --}}
        <div class="flex flex-wrap gap-2 mb-5">
            <button type="button" @click="filterTier = ''"
                    :class="filterTier === '' ? 'bg-gray-700 text-white' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5'"
                    style="border:1px solid rgba(255,255,255,0.08);"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all">Hamısı</button>
            <button type="button" @click="filterTier = 'budget'"
                    :class="filterTier === 'budget' ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5'"
                    style="border:1px solid rgba(255,255,255,0.08);"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5">
                <span class="text-xs font-bold opacity-75">₼</span> Büdcə
            </button>
            <button type="button" @click="filterTier = 'mid'"
                    :class="filterTier === 'mid' ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5'"
                    style="border:1px solid rgba(255,255,255,0.08);"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5">
                <span class="text-xs font-bold opacity-75">₼₼</span> Orta
            </button>
            <button type="button" @click="filterTier = 'premium'"
                    :class="filterTier === 'premium' ? 'bg-violet-600 text-white' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5'"
                    style="border:1px solid rgba(255,255,255,0.08);"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5">
                <span class="text-xs font-bold opacity-75">₼₼₼</span> Premium
            </button>
            <button type="button" @click="filterTier = 'lux'"
                    :class="filterTier === 'lux' ? 'bg-amber-600 text-white' : 'text-gray-400 hover:text-gray-200 hover:bg-white/5'"
                    style="border:1px solid rgba(255,255,255,0.08);"
                    class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all flex items-center gap-1.5">
                <span class="text-xs font-bold opacity-75">₼₼₼₼</span> Lüks
            </button>
        </div>

        @if($cars->isEmpty())
        <div class="theme-card rounded-2xl p-12 text-center">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background:rgba(255,255,255,0.05);">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-gray-500 font-medium">Bu tarixlər üçün boş avtomobil yoxdur</p>
            
            <button type="button" @click="step = 1" class="mt-4 text-sm text-blue-600 hover:underline">Tarixi dəyiş</button>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($cars as $car)
            @php
                $rate = $car->daily_rate;
                $tier = $rate <= 80 ? 'budget' : ($rate <= 150 ? 'mid' : ($rate <= 300 ? 'premium' : 'lux'));
                $tierColor = ['budget'=>'green','mid'=>'blue','premium'=>'purple','lux'=>'yellow'][$tier];
                $tierLabel = ['budget' => 'Büdcə', 'mid' => 'Orta', 'premium' => 'Premium', 'lux' => 'Lüks'][$tier];
                $fuelIcon = ['petrol'=>'⛽','diesel'=>'🛢️','hybrid'=>'🔋','electric'=>'⚡'][$car->fuel_type] ?? '⛽';
            @endphp
            <div x-show="filterTier === '' || filterTier === '{{ $tier }}'"
                 @click="selectCar({{ $car->id }}, '{{ addslashes($car->brand . ' ' . $car->model) }}', {{ $car->daily_rate }})"
                 :class="selectedCarId === {{ $car->id }} ? 'ring-2 ring-blue-500' : 'hover:-translate-y-0.5'" style="background:rgba(13,15,30,0.9);border:1px solid rgba(255,255,255,0.07);"
                 class="car-card relative border rounded-2xl p-5 cursor-pointer transition-all duration-200 shadow-sm group">

                {{-- Selected badge --}}
                <div x-show="selectedCarId === {{ $car->id }}"
                     class="absolute top-3 right-3 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                {{-- Car icon --}}
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform" style="background:rgba(255,255,255,0.06);">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4"/>
                    </svg>
                </div>

                <p class="font-bold text-gray-200 text-sm">{{ $car->brand }} {{ $car->model }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $car->year }} · {{ $car->color }} · {{ $fuelIcon }}</p>

                <div class="flex items-center justify-between mt-3">
                    <div>
                        <span class="text-lg font-bold text-white">{{ $car->daily_rate }} ₼</span>
                        <span class="text-xs text-gray-500">/gün</span>
                    </div>
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full
                        {{ $tierColor === 'green' ? 'bg-emerald-500/20 text-emerald-400' : '' }}
                        {{ $tierColor === 'blue' ? 'bg-blue-500/20 text-blue-400' : '' }}
                        {{ $tierColor === 'purple' ? 'bg-purple-500/20 text-purple-400' : '' }}
                        {{ $tierColor === 'yellow' ? 'bg-amber-500/20 text-amber-400' : '' }}">
                        {{ $tierLabel }}
                    </span>
                </div>

                <div class="mt-3 pt-3 border-t border-gray-800 flex gap-3 text-xs text-gray-500">
                    <span>{{ $car->transmission === 'automatic' ? '🔄 Avtomat' : '⚙️ Mexaniki' }}</span>
                    <span>{{ $car->seats }} oturacaq</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-between items-center">
            <button type="button" @click="step = 1"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-400 hover:text-gray-200 transition-all" style="border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);">
                ← Geri
            </button>
            <button type="button" @click="goToConfirm()"
                    :disabled="!selectedCarId"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-blue-500/20 hover:-translate-y-0.5">
                Davam et →
            </button>
        </div>
        @endif
    </div>

    {{-- STEP 3: Confirm + form --}}
    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Form --}}
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('customer.reservations.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="car_id" :value="selectedCarId">
                    <input type="hidden" name="start_date" :value="startDate">
                    <input type="hidden" name="end_date" :value="endDate">

                    <div class="theme-card rounded-2xl p-6">
                        <h3 class="text-sm font-semibold text-gray-200 mb-4">Yer məlumatları</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-gray-400">Götürmə yeri</label>
                                <select name="pickup_location"
                                        class="w-full bg-gray-800 border border-gray-700 px-3 py-2.5 rounded-xl text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                                    @foreach(['Bakı Hava Limanı','Bakı Şəhər Mərkəzi','28 May Metro','Nəriman Nərimanov Metro','İçərişəhər','Sahil Metro','Nizami Küçəsi','Heydar Əliyev Mərkəzi','Müştərinin ünvanı'] as $loc)
                                    <option value="{{ $loc }}" {{ old('pickup_location') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                                @error('pickup_location')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-gray-400">Qaytarma yeri</label>
                                <select name="return_location"
                                        class="w-full bg-gray-800 border border-gray-700 px-3 py-2.5 rounded-xl text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all">
                                    @foreach(['Bakı Hava Limanı','Bakı Şəhər Mərkəzi','28 May Metro','Nəriman Nərimanov Metro','İçərişəhər','Sahil Metro','Nizami Küçəsi','Heydar Əliyev Mərkəzi','Müştərinin ünvanı'] as $loc)
                                    <option value="{{ $loc }}" {{ old('return_location') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @endforeach
                                </select>
                                @error('return_location')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="theme-card rounded-2xl p-6">
                        <h3 class="text-sm font-semibold text-gray-200 mb-4">Əlavə qeydlər <span class="text-gray-600 font-normal">(istəyə görə)</span></h3>
                        <textarea name="notes" rows="3" placeholder="Hər hansı xüsusi tələb və ya qeydləriniz..."
                                  class="w-full bg-gray-800 border border-gray-700 px-3 py-2.5 rounded-xl text-sm text-gray-200 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all resize-none">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="step = 2"
                                class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-400 hover:text-gray-200 transition-all" style="border:1px solid rgba(255,255,255,0.1);background:rgba(255,255,255,0.04);">
                            ← Geri
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-blue-500/20 hover:-translate-y-0.5">
                            ✓ Rezervasiyanı Təsdiqlə
                        </button>
                    </div>
                </form>
            </div>

            {{-- Price summary --}}
            <div class="lg:col-span-1">
                <div class="theme-card rounded-2xl p-6 sticky top-24">
                    <h3 class="text-sm font-semibold text-gray-200 mb-4">Xülasə</h3>

                    <div class="flex items-center gap-3 pb-4 mb-4 border-b border-gray-800">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(59,130,246,0.15);">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2.5 1M13 6l2 8h4l2-4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-200 text-sm" x-text="selectedCarName || 'Avtomobil seçilməyib'"></p>
                            <p class="text-xs text-gray-500"><span x-text="formatDate(startDate)"></span> → <span x-text="formatDate(endDate)"></span></p>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Günlük qiymət</span>
                            <span class="text-gray-300" x-text="dailyRate + ' ₼'"></span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Gün sayı</span>
                            <span class="text-gray-300" x-text="days + ' gün'"></span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Depozit</span>
                            <span class="text-gray-300" x-text="(dailyRate * 5) + ' ₼'"></span>
                        </div>
                        <div class="flex justify-between font-bold text-white pt-2.5 border-t border-gray-800 text-base">
                            <span>Cəmi</span>
                            <span x-text="(dailyRate * days) + ' ₼'" class="text-blue-400"></span>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl px-3 py-2.5 text-xs text-amber-400 flex items-start gap-2" style="background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.2);">
                        <svg class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Agent tərəfindən təsdiqləndikdən sonra aktiv olacaq
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function reservationWizard() {
    return {
        step: {{ $start && $end && $cars->isNotEmpty() ? 2 : 1 }},
        startDate: '{{ $start?->format('Y-m-d') ?? '' }}',
        endDate:   '{{ $end?->format('Y-m-d') ?? '' }}',
        selectedCarId:   {{ $selectedCar?->id ?? 'null' }},
        selectedCarName: '{{ $selectedCar ? addslashes($selectedCar->brand . ' ' . $selectedCar->model) : '' }}',
        dailyRate: {{ $selectedCar?->daily_rate ?? 0 }},
        filterTier: '',
        today: new Date().toISOString().split('T')[0],

        get days() {
            if (!this.startDate || !this.endDate) return 0;
            const diff = (new Date(this.endDate) - new Date(this.startDate)) / 86400000;
            return diff > 0 ? Math.ceil(diff) : 0;
        },

        formatDate(d) {
            if (!d) return '—';
            return new Date(d).toLocaleDateString('az-AZ', { day:'2-digit', month:'short', year:'numeric' });
        },

        searchCars() {
            if (!this.startDate || !this.endDate || this.days <= 0) return;
            const url = new URL(window.location.href.split('?')[0]);
            url.searchParams.set('start_date', this.startDate);
            url.searchParams.set('end_date', this.endDate);
            window.location.href = url.toString();
        },

        selectCar(id, name, rate) {
            this.selectedCarId   = id;
            this.selectedCarName = name;
            this.dailyRate       = rate;
        },

        goToConfirm() {
            if (this.selectedCarId) this.step = 3;
        }
    };
}
</script>
@endpush
@endsection
