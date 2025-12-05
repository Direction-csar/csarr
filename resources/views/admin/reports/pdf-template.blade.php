<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport CSAR - {{ ucfirst($type) }}</title>
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
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
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
            color: #007bff;
            margin-top: 0;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .summary-item {
            background: white;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .summary-item h4 {
            margin: 0 0 10px 0;
            color: #007bff;
            font-size: 14px;
            text-transform: uppercase;
        }
        .summary-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .details-section {
            margin-top: 30px;
        }
        .details-section h3 {
            color: #007bff;
            border-bottom: 2px solid #007bff;
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
            background: #007bff;
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
            background: #e3f2fd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport CSAR</h1>
        <h2>{{ ucfirst($type) }} - {{ $dateFrom }} au {{ $dateTo }}</h2>
    </div>

    <div class="period-info">
        <strong>Période du rapport :</strong> {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
        <br>
        <strong>Généré le :</strong> {{ now()->format('d/m/Y à H:i') }}
        <br>
        <strong>Type de rapport :</strong> {{ ucfirst($type) }}
    </div>

    @if(isset($data['summary']) && !empty($data['summary']))
        <div class="info-section">
            <h3>Résumé Exécutif</h3>
            <div class="summary-grid">
                @foreach($data['summary'] as $key => $value)
                    @if(!is_array($value) && $value !== null)
                        <div class="summary-item">
                            <h4>{{ ucfirst(str_replace('_', ' ', $key)) }}</h4>
                            <div class="value">{{ number_format($value) }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    @if(isset($data['details']) && !empty($data['details']))
        <div class="details-section">
            <h3>Détails du Rapport</h3>
            
            @if(isset($data['details']['message']))
                <div class="no-data">
                    {{ $data['details']['message'] }}
                </div>
            @else
                @foreach($data['details'] as $section => $sectionData)
                    @if(is_array($sectionData) && !empty($sectionData))
                        <h4 style="color: #495057; margin-top: 25px;">{{ ucfirst(str_replace('_', ' ', $section)) }}</h4>
                        
                        @if(isset($sectionData[0]) && is_array($sectionData[0]))
                            {{-- Tableau de données --}}
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        @foreach(array_keys($sectionData[0]) as $header)
                                            <th>{{ ucfirst(str_replace('_', ' ', $header)) }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sectionData as $row)
                                        <tr>
                                            @foreach($row as $cell)
                                                <td>{{ is_array($cell) ? json_encode($cell) : $cell }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            {{-- Données simples --}}
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                                @foreach($sectionData as $key => $value)
                                    @if(!is_array($value))
                                        <div style="margin-bottom: 8px;">
                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                            {{ is_numeric($value) ? number_format($value) : $value }}
                                        </div>
                                    @else
                                        <div style="margin-bottom: 8px;">
                                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                            <ul style="margin: 5px 0 0 20px;">
                                                @foreach($value as $subKey => $subValue)
                                                    <li>{{ $subKey }}: {{ is_numeric($subValue) ? number_format($subValue) : $subValue }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endforeach
            @endif
        </div>
    @else
        <div class="no-data">
            Aucune donnée disponible pour cette période.
        </div>
    @endif

    <div class="footer">
        <p>Rapport généré automatiquement par le système CSAR</p>
        <p>Plateforme de Gestion des Stocks et Approvisionnements</p>
    </div>
</body>
</html>
