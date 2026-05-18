<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <title>Müqavilə {{ $contract->contract_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }
        .page { padding: 30px 40px; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #2563eb; padding-bottom: 14px; margin-bottom: 18px; }
        .company-name { font-size: 18px; font-weight: bold; color: #2563eb; }
        .company-info { font-size: 10px; color: #6b7280; margin-top: 3px; }
        .contract-badge { text-align: right; }
        .contract-number { font-size: 15px; font-weight: bold; font-family: monospace; color: #1e40af; }
        .contract-date { font-size: 10px; color: #6b7280; margin-top: 3px; }

        /* Title */
        .title { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin: 18px 0 16px; color: #1e3a8a; }

        /* Parties */
        .parties { display: flex; gap: 16px; margin-bottom: 16px; }
        .party { flex: 1; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; background: #f9fafb; }
        .party-title { font-size: 10px; font-weight: bold; text-transform: uppercase; color: #6b7280; margin-bottom: 6px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
        .party-row { display: flex; justify-content: space-between; font-size: 10px; margin-top: 4px; }
        .party-label { color: #6b7280; }
        .party-value { font-weight: bold; text-align: right; max-width: 55%; }

        /* Car + Period */
        .section { margin-bottom: 14px; }
        .section-title { font-size: 11px; font-weight: bold; color: #1e40af; border-left: 3px solid #2563eb; padding-left: 7px; margin-bottom: 8px; }
        .grid-2 { display: flex; gap: 14px; }
        .grid-2 > * { flex: 1; }
        .info-box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; background: #f9fafb; }
        .info-row { display: flex; justify-content: space-between; font-size: 10px; padding: 3px 0; border-bottom: 1px solid #f3f4f6; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #6b7280; }
        .info-value { font-weight: bold; }

        /* Price Table */
        .price-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .price-table th { background: #1e40af; color: #fff; font-size: 10px; padding: 7px 10px; text-align: left; }
        .price-table td { padding: 6px 10px; font-size: 10px; border-bottom: 1px solid #f3f4f6; }
        .price-table tr:last-child td { font-weight: bold; background: #eff6ff; }
        .text-right { text-align: right; }

        /* Penalties */
        .penalties-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .penalties-table th { background: #dc2626; color: #fff; font-size: 10px; padding: 6px 10px; text-align: left; }
        .penalties-table td { padding: 5px 10px; font-size: 10px; border-bottom: 1px solid #fee2e2; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }

        /* Conditions */
        .conditions { border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; background: #fffbeb; margin-bottom: 14px; font-size: 10px; color: #78350f; }
        .conditions ol { padding-left: 14px; }
        .conditions li { margin-top: 3px; }

        /* Signatures */
        .signatures { display: flex; gap: 20px; margin-top: 18px; }
        .sig-box { flex: 1; border-top: 2px solid #1e40af; padding-top: 8px; }
        .sig-label { font-size: 10px; font-weight: bold; color: #1e40af; text-transform: uppercase; }
        .sig-name { font-size: 11px; font-weight: bold; margin-top: 12px; }
        .sig-sub { font-size: 9px; color: #6b7280; margin-top: 2px; }
        .sig-line { border-bottom: 1px dashed #9ca3af; margin-top: 22px; }

        /* Footer */
        .footer { text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px; margin-top: 14px; }
        .status-open { color: #d97706; font-weight: bold; }
        .status-closed { color: #16a34a; font-weight: bold; }
    </style>
</head>
<body>
<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <div>
            <div class="company-name">{{ config('app.company_name', config('app.name')) }}</div>
            <div class="company-info">
                @if(config('app.company_address')) {{ config('app.company_address') }}<br>@endif
                @if(config('app.company_phone')) Tel: {{ config('app.company_phone') }} &nbsp; @endif
                @if(config('app.company_email')) Email: {{ config('app.company_email') }}@endif
            </div>
        </div>
        <div class="contract-badge">
            <div class="contract-number">{{ $contract->contract_number }}</div>
            <div class="contract-date">İmzalandı: {{ $contract->signed_at?->format('d.m.Y H:i') }}</div>
            <div class="contract-date">
                Vəziyyət:
                @if($contract->returned_at)
                    <span class="status-closed">Bağlandı — {{ $contract->returned_at->format('d.m.Y') }}</span>
                @else
                    <span class="status-open">Açıqdır</span>
                @endif
            </div>
        </div>
    </div>

    <div class="title">Avtomobil İcarə Müqaviləsi</div>

    {{-- PARTIES --}}
    <div class="parties">
        <div class="party">
            <div class="party-title">İcarəçi (Müştəri)</div>
            <div class="party-row"><span class="party-label">Ad Soyad</span><span class="party-value">{{ $contract->reservation?->customer?->full_name }}</span></div>
            <div class="party-row"><span class="party-label">FIN</span><span class="party-value">{{ $contract->reservation?->customer?->id_number }}</span></div>
            <div class="party-row"><span class="party-label">Sürücülük vəsiqəsi</span><span class="party-value">{{ $contract->reservation?->customer?->license_number }}</span></div>
            <div class="party-row"><span class="party-label">Telefon</span><span class="party-value">{{ $contract->reservation?->customer?->phone }}</span></div>
        </div>
        <div class="party">
            <div class="party-title">İcarəyə verən (Agent)</div>
            <div class="party-row"><span class="party-label">Ad Soyad</span><span class="party-value">{{ $contract->reservation?->agent?->name ?? config('app.company_name', config('app.name')) }}</span></div>
            <div class="party-row"><span class="party-label">Email</span><span class="party-value">{{ $contract->reservation?->agent?->email ?? config('app.company_email') }}</span></div>
            <div class="party-row"><span class="party-label">Müqavilə tarixi</span><span class="party-value">{{ $contract->signed_at?->format('d.m.Y') }}</span></div>
        </div>
    </div>

    {{-- CAR + PERIOD --}}
    <div class="section">
        <div class="section-title">Avtomobil və İcarə Dövrü</div>
        <div class="grid-2">
            <div class="info-box">
                <div class="info-row"><span class="info-label">Marka / Model</span><span class="info-value">{{ $contract->reservation?->car?->brand }} {{ $contract->reservation?->car?->model }}</span></div>
                <div class="info-row"><span class="info-label">Dövlət nömrəsi</span><span class="info-value">{{ $contract->reservation?->car?->plate_number }}</span></div>
                <div class="info-row"><span class="info-label">Rəng</span><span class="info-value">{{ $contract->reservation?->car?->color }}</span></div>
                <div class="info-row"><span class="info-label">Yanacaq növü</span><span class="info-value">{{ $contract->reservation?->car?->fuel_type }}</span></div>
            </div>
            <div class="info-box">
                <div class="info-row"><span class="info-label">Başlama tarixi</span><span class="info-value">{{ $contract->reservation?->start_date?->format('d.m.Y') }}</span></div>
                <div class="info-row"><span class="info-label">Bitmə tarixi</span><span class="info-value">{{ $contract->reservation?->end_date?->format('d.m.Y') }}</span></div>
                <div class="info-row"><span class="info-label">Gün sayı</span><span class="info-value">{{ $contract->reservation?->total_days }} gün</span></div>
                <div class="info-row"><span class="info-label">Götürmə yeri</span><span class="info-value">{{ $contract->reservation?->pickup_location }}</span></div>
                <div class="info-row"><span class="info-label">Qaytarma yeri</span><span class="info-value">{{ $contract->reservation?->return_location }}</span></div>
            </div>
        </div>
    </div>

    {{-- VEHICLE CONDITION --}}
    <div class="section">
        <div class="section-title">Avtomobilin Vəziyyəti</div>
        <div class="grid-2">
            <div class="info-box">
                <div class="info-row"><span class="info-label">Yanacaq (verilən)</span><span class="info-value">{{ $contract->fuel_level_out }}</span></div>
                <div class="info-row"><span class="info-label">Km (verilən)</span><span class="info-value">{{ number_format($contract->mileage_out) }} km</span></div>
                <div class="info-row"><span class="info-label">Vəziyyəti</span><span class="info-value">{{ $contract->condition_out ?? '—' }}</span></div>
            </div>
            <div class="info-box">
                <div class="info-row"><span class="info-label">Yanacaq (qaytarılan)</span><span class="info-value">{{ $contract->fuel_level_in ?? '—' }}</span></div>
                <div class="info-row"><span class="info-label">Km (qaytarılan)</span><span class="info-value">{{ $contract->mileage_in ? number_format($contract->mileage_in).' km' : '—' }}</span></div>
                <div class="info-row"><span class="info-label">Vəziyyəti</span><span class="info-value">{{ $contract->condition_in ?? '—' }}</span></div>
            </div>
        </div>
    </div>

    {{-- PRICE TABLE --}}
    <div class="section">
        <div class="section-title">Maliyyə Hesabı</div>
        <table class="price-table">
            <thead>
                <tr>
                    <th>Qiymət elementi</th>
                    <th class="text-right">Məbləğ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gündəlik qiymət ({{ number_format($contract->reservation?->daily_rate, 2) }} ₼ × {{ $contract->reservation?->total_days }} gün)</td>
                    <td class="text-right">{{ number_format($contract->reservation?->subtotal, 2) }} ₼</td>
                </tr>
                @if($contract->reservation?->discount_amount > 0)
                <tr>
                    <td>Endirim ({{ $contract->reservation?->discount_percent }}%)</td>
                    <td class="text-right" style="color:#16a34a">-{{ number_format($contract->reservation?->discount_amount, 2) }} ₼</td>
                </tr>
                @endif
                @if($contract->penalties->count())
                <tr>
                    <td>Cərimələr cəmi</td>
                    <td class="text-right" style="color:#dc2626">+{{ number_format($contract->penalties->sum('amount'), 2) }} ₼</td>
                </tr>
                @endif
                <tr>
                    <td><strong>CƏMİ ÖDƏMƏ</strong></td>
                    <td class="text-right"><strong>{{ number_format($contract->reservation?->total_amount + $contract->penalties->sum('amount'), 2) }} ₼</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- PENALTIES --}}
    @if($contract->penalties->count())
    <div class="section">
        <div class="section-title">Cərimələr</div>
        <table class="penalties-table">
            <thead>
                <tr>
                    <th>Növ</th>
                    <th>Açıqlama</th>
                    <th class="text-right">Məbləğ</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contract->penalties as $penalty)
                <tr>
                    <td>{{ $penalty->type }}</td>
                    <td>{{ $penalty->description }}</td>
                    <td class="text-right" style="color:#dc2626;font-weight:bold">{{ number_format($penalty->amount, 2) }} ₼</td>
                    <td>
                        @if($penalty->paid)
                            <span class="badge badge-green">Ödənilib</span>
                        @else
                            <span class="badge badge-red">Ödənilməyib</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- CONDITIONS --}}
    <div class="conditions">
        <strong style="font-size:10px;">Müqavilə şərtləri:</strong>
        <ol>
            <li>İcarəçi avtomobili müqavilədə göstərilən tarixdə geri qaytarmağı öhdəsinə götürür.</li>
            <li>Gecikdirmə halında gündəlik tarif üzərindən 50% əlavə ödəniş tutulur.</li>
            <li>Yanacaq çatışmazlığı üçün müvafiq cərimə tətbiq edilir.</li>
            <li>Avtomobilə dəyən hər hansı zərər icarəçi tərəfindən ödənilir.</li>
            <li>Müqavilə hər iki tərəf tərəfindən imzalanmaqla qüvvəyə minir.</li>
        </ol>
    </div>

    {{-- SIGNATURES --}}
    <div class="signatures">
        <div class="sig-box">
            <div class="sig-label">İcarəyə verən</div>
            <div class="sig-name">{{ $contract->reservation?->agent?->name ?? config('app.company_name', config('app.name')) }}</div>
            <div class="sig-sub">{{ now()->format('d.m.Y') }}</div>
            <div class="sig-line"></div>
        </div>
        <div class="sig-box">
            <div class="sig-label">İcarəçi</div>
            <div class="sig-name">{{ $contract->reservation?->customer?->full_name }}</div>
            <div class="sig-sub">FIN: {{ $contract->reservation?->customer?->id_number }}</div>
            <div class="sig-line"></div>
        </div>
    </div>

    <div class="footer">
        {{ config('app.company_name', config('app.name')) }} · Müqavilə № {{ $contract->contract_number }} · {{ now()->format('d.m.Y H:i') }}
    </div>

</div>
</body>
</html>
