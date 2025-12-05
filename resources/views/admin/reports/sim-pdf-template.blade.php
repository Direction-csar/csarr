<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport SIM CSAR - {{ $report->title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #28a745;
            margin: 0;
            font-size: 28px;
        }
        .header h2 {
            color: #666;
            margin: 10px 0 0 0;
            font-size: 18px;
            font-weight: normal;
        }
        .info-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .info-section h3 {
            color: #28a745;
            margin-top: 0;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        .summary-section {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
        }
        .summary-section h3 {
            color: #28a745;
            margin-top: 0;
        }
        .recommendations-section {
            background: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #ffc107;
        }
        .recommendations-section h3 {
            color: #856404;
            margin-top: 0;
        }
        .details-section {
            margin-top: 30px;
        }
        .details-section h3 {
            color: #28a745;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .data-table th {
            background: #28a745;
            color: white;
            font-weight: bold;
        }
        .data-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .period-info {
            background: #d4edda;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .indicator-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .indicator-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .indicator-item h4 {
            margin: 0 0 10px 0;
            color: #28a745;
            font-size: 14px;
            text-transform: uppercase;
        }
        .indicator-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport SIM CSAR</h1>
        <h2>{{ $report->title }}</h2>
    </div>

    <div class="period-info">
        <strong>Période du rapport :</strong> {{ $report->period_start->format('d/m/Y') }} au {{ $report->period_end->format('d/m/Y') }}
        <br>
        <strong>Généré le :</strong> {{ $report->generated_at ? $report->generated_at->format('d/m/Y à H:i') : now()->format('d/m/Y à H:i') }}
        <br>
        <strong>Type de rapport :</strong> {{ ucfirst($report->report_type) }}
        @if($report->description)
            <br>
            <strong>Description :</strong> {{ $report->description }}
        @endif
    </div>

    @if(isset($data['summary']) && $data['summary'] !== 'Résumé non disponible')
        <div class="summary-section">
            <h3>Résumé Exécutif</h3>
            <div style="white-space: pre-line;">{{ $data['summary'] }}</div>
        </div>
    @endif

    @if(isset($data['details']) && !empty($data['details']))
        <div class="details-section">
            <h3>Indicateurs Détaillés</h3>
            
            @foreach($data['details'] as $category => $indicators)
                @if(is_array($indicators) && !empty($indicators))
                    <h4 style="color: #495057; margin-top: 25px;">{{ ucfirst(str_replace('_', ' ', $category)) }}</h4>
                    
                    <div class="indicator-grid">
                        @foreach($indicators as $key => $value)
                            @if(!is_array($value) && $value !== null)
                                <div class="indicator-item">
                                    <h4>{{ ucfirst(str_replace('_', ' ', $key)) }}</h4>
                                    <div class="value">{{ is_numeric($value) ? number_format($value) : $value }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    
                    {{-- Afficher les tableaux de données --}}
                    @foreach($indicators as $key => $value)
                        @if(is_array($value) && !empty($value) && !is_numeric(array_keys($value)[0] ?? null))
                            <h5 style="color: #6c757d; margin-top: 20px;">{{ ucfirst(str_replace('_', ' ', $key)) }}</h5>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Élément</th>
                                        <th>Valeur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($value as $subKey => $subValue)
                                        <tr>
                                            <td>{{ ucfirst(str_replace('_', ' ', $subKey)) }}</td>
                                            <td>{{ is_numeric($subValue) ? number_format($subValue) : $subValue }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    @else
        <div class="no-data">
            Aucune donnée disponible pour cette période.
        </div>
    @endif

    @if(isset($data['recommendations']) && $data['recommendations'] !== 'Recommandations non disponibles')
        <div class="recommendations-section">
            <h3>Recommandations</h3>
            <div style="white-space: pre-line;">{{ $data['recommendations'] }}</div>
        </div>
    @endif

    <div class="footer">
        <p>Rapport généré automatiquement par le système SIM CSAR</p>
        <p>Système d'Information de Gestion des Marchés</p>
        <p>Plateforme de Gestion des Stocks et Approvisionnements</p>
    </div>
</body>
</html>
