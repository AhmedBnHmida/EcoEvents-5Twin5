<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Statistiques Sponsorings - {{ date('Y-m-d') }}</title>
    <style>
        @page {
            margin: 20px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.5;
        }

        .page {
            padding: 20px;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            margin: -20px -20px 20px -20px;
            text-align: center;
        }

        .header h1 {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header .subtitle {
            font-size: 11pt;
            opacity: 0.9;
        }

        /* KPI Cards */
        .kpi-container {
            display: table;
            width: 100%;
            margin: 20px 0;
            border-spacing: 10px;
        }

        .kpi-row {
            display: table-row;
        }

        .kpi-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            border: 2px solid #e0e0e0;
        }

        .kpi-card.primary {
            background: #e8eaf6;
            border-color: #667eea;
        }

        .kpi-card.success {
            background: #e8f5e9;
            border-color: #4caf50;
        }

        .kpi-card.info {
            background: #e1f5fe;
            border-color: #03a9f4;
        }

        .kpi-card.warning {
            background: #fff3e0;
            border-color: #ff9800;
        }

        .kpi-label {
            font-size: 8pt;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .kpi-value {
            font-size: 20pt;
            font-weight: bold;
            color: #222;
        }

        /* Section */
        .section {
            margin: 25px 0;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14pt;
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table thead {
            background: #f5f5f5;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            color: #555;
            border-bottom: 2px solid #667eea;
        }

        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 9pt;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: #667eea;
            color: white;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Two columns layout */
        .two-columns {
            display: table;
            width: 100%;
            margin: 15px 0;
        }

        .column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 10px;
        }

        .column:first-child {
            padding-right: 15px;
        }

        .column:last-child {
            padding-left: 15px;
        }

        /* Chart representation (simple bars) */
        .chart-bar {
            margin: 8px 0;
        }

        .chart-label {
            font-size: 9pt;
            margin-bottom: 3px;
            color: #555;
        }

        .chart-bar-bg {
            background: #e0e0e0;
            height: 20px;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
        }

        .chart-bar-fill {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            border-radius: 10px;
        }

        .chart-value {
            position: absolute;
            right: 10px;
            top: 2px;
            font-size: 8pt;
            color: white;
            font-weight: bold;
        }

        /* Highlight box */
        .highlight-box {
            background: #f8f9fa;
            border-left: 5px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #888;
            font-size: 8pt;
        }

        .footer .date {
            margin-top: 5px;
            font-style: italic;
        }

        /* Page break */
        .page-break {
            page-break-after: always;
        }

        /* Number formatting */
        .text-success {
            color: #4caf50;
            font-weight: bold;
        }

        .text-primary {
            color: #667eea;
            font-weight: bold;
        }

        .text-muted {
            color: #999;
        }

        /* Ranking */
        .ranking {
            font-weight: bold;
            color: #667eea;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Header -->
        <div class="header">
            <h1>📊 Statistiques Sponsorings</h1>
            <div class="subtitle">Rapport Complet - EcoEvents Platform</div>
        </div>

        <!-- KPI Cards -->
        <div class="kpi-container">
            <div class="kpi-row">
                <div class="kpi-card primary">
                    <div class="kpi-label">Total Sponsorings</div>
                    <div class="kpi-value">{{ $stats['total_sponsorings'] }}</div>
                </div>
                <div class="kpi-card success">
                    <div class="kpi-label">Montant Total</div>
                    <div class="kpi-value" style="font-size: 16pt;">{{ number_format($stats['total_montant'], 0, ',', ' ') }} €</div>
                </div>
                <div class="kpi-card info">
                    <div class="kpi-label">Montant Moyen</div>
                    <div class="kpi-value" style="font-size: 16pt;">{{ number_format($stats['average_montant'], 0, ',', ' ') }} €</div>
                </div>
                <div class="kpi-card warning">
                    <div class="kpi-label">Partenaires</div>
                    <div class="kpi-value">{{ $stats['top_partners']->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Répartition par Type -->
        <div class="section">
            <div class="section-title">📈 Répartition par Type de Sponsoring</div>
            
            @php
                $maxTotal = $stats['by_type']->max('total') ?: 1;
            @endphp
            
            @foreach($stats['by_type'] as $type)
                @php
                    $percentage = $stats['total_montant'] > 0 ? ($type->total / $stats['total_montant']) * 100 : 0;
                    $barWidth = ($type->total / $maxTotal) * 100;
                @endphp
                <div class="chart-bar">
                    <div class="chart-label">
                        <strong>{{ \App\TypeSponsoring::from($type->type_sponsoring)->label() }}</strong> 
                        - {{ $type->count }} sponsoring(s) ({{ number_format($percentage, 1) }}%)
                    </div>
                    <div class="chart-bar-bg">
                        <div class="chart-bar-fill" style="width: {{ $barWidth }}%;"></div>
                        <div class="chart-value">{{ number_format($type->total, 0, ',', ' ') }} €</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Two Columns: Top Partners & Top Events -->
        <div class="two-columns">
            <div class="column">
                <div class="section">
                    <div class="section-title">🏆 Top 5 Partenaires</div>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Partenaire</th>
                                <th style="text-align: center;">Nb</th>
                                <th style="text-align: right;">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['top_partners'] as $index => $partner)
                            <tr>
                                <td><span class="ranking">{{ $index + 1 }}</span></td>
                                <td>
                                    <strong>{{ Str::limit($partner->nom, 20) }}</strong><br>
                                    <span class="text-muted" style="font-size: 8pt;">{{ $partner->type }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge">{{ $partner->sponsorings_count }}</span>
                                </td>
                                <td style="text-align: right;">
                                    <span class="text-success">{{ number_format($partner->sponsorings_sum_montant ?? 0, 0) }} €</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="column">
                <div class="section">
                    <div class="section-title">⭐ Top 5 Événements</div>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Événement</th>
                                <th style="text-align: center;">Nb</th>
                                <th style="text-align: right;">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['top_events'] as $index => $event)
                            <tr>
                                <td><span class="ranking">{{ $index + 1 }}</span></td>
                                <td>
                                    <strong style="font-size: 8pt;">{{ Str::limit($event->title, 25) }}</strong>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge">{{ $event->sponsorings_count }}</span>
                                </td>
                                <td style="text-align: right;">
                                    <span class="text-success">{{ number_format($event->sponsorings_sum_montant ?? 0, 0) }} €</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Évolution Mensuelle -->
        @if($stats['monthly_trend']->count() > 0)
        <div class="section">
            <div class="section-title">📅 Évolution Mensuelle (6 derniers mois)</div>
            
            @php
                $maxMonthTotal = $stats['monthly_trend']->max('total') ?: 1;
            @endphp
            
            @foreach($stats['monthly_trend'] as $month)
                @php
                    $barWidth = ($month->total / $maxMonthTotal) * 100;
                @endphp
                <div class="chart-bar">
                    <div class="chart-label">
                        <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F Y') }}</strong> 
                        - {{ $month->count }} sponsoring(s)
                    </div>
                    <div class="chart-bar-bg">
                        <div class="chart-bar-fill" style="width: {{ $barWidth }}%; background: linear-gradient(90deg, #4caf50 0%, #8bc34a 100%);"></div>
                        <div class="chart-value">{{ number_format($month->total, 0, ',', ' ') }} €</div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <div class="page-break"></div>

        <!-- Recent Sponsorings -->
        <div class="section">
            <div class="section-title">🕐 Derniers Sponsorings (10 plus récents)</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Partenaire</th>
                        <th>Événement</th>
                        <th>Type</th>
                        <th style="text-align: right;">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats['recent_sponsorings'] as $sponsoring)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($sponsoring->date)->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ Str::limit($sponsoring->partner->nom, 20) }}</strong><br>
                            <span class="text-muted" style="font-size: 7pt;">{{ $sponsoring->partner->type }}</span>
                        </td>
                        <td style="font-size: 8pt;">{{ Str::limit($sponsoring->event->title, 30) }}</td>
                        <td>
                            <span class="badge">{{ $sponsoring->type_sponsoring->label() }}</span>
                        </td>
                        <td style="text-align: right;">
                            <span class="text-success">{{ number_format($sponsoring->montant, 2, ',', ' ') }} €</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Résumé Global -->
        <div class="highlight-box">
            <strong style="font-size: 11pt; color: #667eea;">💡 Analyse Globale</strong>
            <div style="margin-top: 10px; line-height: 1.7;">
                <p style="margin-bottom: 8px;">
                    • <strong>Total des sponsorings :</strong> {{ $stats['total_sponsorings'] }} contrats signés
                </p>
                <p style="margin-bottom: 8px;">
                    • <strong>Valeur totale :</strong> {{ number_format($stats['total_montant'], 2, ',', ' ') }} € collectés
                </p>
                <p style="margin-bottom: 8px;">
                    • <strong>Partenaires actifs :</strong> {{ $stats['top_partners']->count() }} entreprises engagées
                </p>
                <p style="margin-bottom: 8px;">
                    • <strong>Événements sponsorisés :</strong> {{ $stats['top_events']->count() }} événements bénéficiaires
                </p>
                @if($stats['by_type']->count() > 0)
                    @php
                        $topType = $stats['by_type']->sortByDesc('total')->first();
                    @endphp
                    <p>
                        • <strong>Type dominant :</strong> {{ \App\TypeSponsoring::from($topType->type_sponsoring)->label() }} 
                        ({{ number_format(($topType->total / $stats['total_montant']) * 100, 1) }}% du total)
                    </p>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div><strong>EcoEvents Platform</strong> - Plateforme d'Événements Écologiques</div>
            <div>📧 contact@ecoevents.com | 📞 +33 1 23 45 67 89 | 🌐 www.ecoevents.com</div>
            <div class="date">
                Rapport généré le {{ now()->format('d/m/Y à H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>

