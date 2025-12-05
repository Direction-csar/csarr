@extends('layouts.public')

@section('title', 'Tableau de bord SIM - CSAR')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 0;">
    <h1 class="main-title" style="font-size: 2.5rem; font-weight: 800; color: #059669; margin-bottom: 24px;">Tableau de bord du Système d'Information des Marchés (SIM)</h1>
    <p style="font-size: 1.2rem; color: #374151; margin-bottom: 40px;">Bienvenue sur le tableau de bord SIM. Retrouvez ici les indicateurs clés sur les prix, l'approvisionnement et la répartition régionale.</p>

    <!-- Indicateurs clés -->
    @if($latestReport && $priceStats)
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">{{ $priceStats['total_products'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Produits suivis</div>
        </div>
        <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">{{ $priceStats['price_increases'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Hausses de prix</div>
        </div>
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">{{ $priceStats['price_decreases'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Baisses de prix</div>
        </div>
        <div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">{{ $priceStats['stable_prices'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Prix stables</div>
        </div>
    </div>
    @endif

    <!-- Section principale -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 32px; margin-bottom: 40px;">
        
        <!-- Suivi des Prix -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin: 0;">📊 Suivi des Prix</h2>
                <a href="{{ route('sim.prices') }}" style="background: #059669; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">Voir plus</a>
            </div>
            <p style="color: #6b7280; margin-bottom: 24px; line-height: 1.5;">Surveillance régulière des prix de détail des produits agricoles sur les marchés sénégalais</p>
            
            @if($latestReport && $latestReport->price_analysis)
                <!-- Plus forte hausse -->
                @if($priceStats && $priceStats['highest_increase']['product'])
                <div style="background: #fef2f2; border-radius: 12px; padding: 16px; margin-bottom: 20px; border-left: 4px solid #dc2626;">
                    <div style="font-weight: 600; color: #dc2626; margin-bottom: 4px;">Plus forte hausse</div>
                    <div style="color: #374151;">{{ str_replace('_', ' ', $priceStats['highest_increase']['product']) }} : +{{ number_format($priceStats['highest_increase']['percentage'], 1) }}%</div>
                </div>
                @endif

                <!-- Plus forte baisse -->
                @if($priceStats && $priceStats['highest_decrease']['product'])
                <div style="background: #f0fdf4; border-radius: 12px; padding: 16px; margin-bottom: 20px; border-left: 4px solid #059669;">
                    <div style="font-weight: 600; color: #059669; margin-bottom: 4px;">Plus forte baisse</div>
                    <div style="color: #374151;">{{ str_replace('_', ' ', $priceStats['highest_decrease']['product']) }} : {{ number_format($priceStats['highest_decrease']['percentage'], 1) }}%</div>
                </div>
                @endif

                <!-- Résumé des catégories -->
                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                    <h4 style="color: #374151; font-size: 1rem; font-weight: 600; margin-bottom: 12px;">Résumé par catégorie</h4>
                    @foreach($priceStats['categories'] as $category => $stats)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; {{ !$loop->last ? 'border-bottom: 1px solid #e5e7eb;' : '' }}">
                        <span style="color: #374151; text-transform: capitalize; font-size: 0.9rem;">{{ str_replace('_', ' ', $category) }}</span>
                        <div style="display: flex; gap: 12px; font-size: 0.8rem;">
                            <span style="color: #dc2626;">{{ $stats['increases'] }} hausses</span>
                            <span style="color: #059669;">{{ $stats['decreases'] }} baisses</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p style="color: #6b7280; text-align: center; padding: 40px;">Aucune donnée de prix disponible</p>
            @endif
        </div>

        <!-- Niveau d'Approvisionnement -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin: 0;">📦 Niveau d'Approvisionnement</h2>
                <a href="{{ route('sim.supply') }}" style="background: #059669; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">Voir plus</a>
            </div>
            <p style="color: #6b7280; margin-bottom: 24px; line-height: 1.5;">Évaluation des volumes disponibles sur les marchés et analyse des tendances d'approvisionnement</p>
            
            @if($latestReport && $latestReport->supply_level)
                <!-- Statistiques d'approvisionnement -->
                @php
                    $totalSupply = 0;
                    $totalIncrease = 0;
                    $totalDecrease = 0;
                    foreach($latestReport->supply_level as $category => $products) {
                        foreach($products as $product => $data) {
                            $totalSupply += $data['juillet'];
                            if($data['variation'] > 0) $totalIncrease++;
                            elseif($data['variation'] < 0) $totalDecrease++;
                        }
                    }
                @endphp

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #bbf7d0;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ number_format($totalSupply, 0) }}</div>
                        <div style="font-size: 0.8rem; color: #047857;">Tonnes totales</div>
                    </div>
                    <div style="background: #fef2f2; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #fecaca;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #dc2626;">{{ $totalIncrease }}</div>
                        <div style="font-size: 0.8rem; color: #b91c1c;">Augmentations</div>
                    </div>
                    <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #bbf7d0;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ $totalDecrease }}</div>
                        <div style="font-size: 0.8rem; color: #047857;">Diminutions</div>
                    </div>
                </div>

                <!-- Top 3 des produits avec plus forte augmentation -->
                <h4 style="color: #374151; font-size: 1rem; font-weight: 600; margin-bottom: 12px;">Top 3 - Plus fortes augmentations</h4>
                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                    @php
                        $topIncreases = [];
                        foreach($latestReport->supply_level as $category => $products) {
                            foreach($products as $product => $data) {
                                if($data['variation'] > 0) {
                                    $topIncreases[] = [
                                        'product' => $product,
                                        'variation' => $data['variation'],
                                        'category' => $category
                                    ];
                                }
                            }
                        }
                        usort($topIncreases, function($a, $b) {
                            return $b['variation'] <=> $a['variation'];
                        });
                        $topIncreases = array_slice($topIncreases, 0, 3);
                    @endphp

                    @foreach($topIncreases as $index => $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; {{ $index < count($topIncreases) - 1 ? 'border-bottom: 1px solid #e5e7eb;' : '' }}">
                        <div>
                            <div style="font-weight: 500; color: #374151; text-transform: capitalize; font-size: 0.9rem;">{{ str_replace('_', ' ', $item['product']) }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280; text-transform: capitalize;">{{ str_replace('_', ' ', $item['category']) }}</div>
                        </div>
                        <div style="font-weight: 600; color: #059669; font-size: 0.9rem;">+{{ number_format($item['variation'], 0) }}%</div>
                    </div>
                    @endforeach
                </div>
            @else
                <p style="color: #6b7280; text-align: center; padding: 40px;">Aucune donnée d'approvisionnement disponible</p>
            @endif
        </div>

        <!-- Répartition Régionale -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin: 0;">🗺️ Répartition Régionale</h2>
                <a href="{{ route('sim.regional') }}" style="background: #059669; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">Voir plus</a>
            </div>
            <p style="color: #6b7280; margin-bottom: 24px; line-height: 1.5;">Analyse des disparités de prix entre les différentes régions du Sénégal</p>
            
            <!-- Carte du Sénégal avec indicateurs -->
            <div style="background: #f0fdf4; border-radius: 12px; padding: 24px; margin-bottom: 20px; text-align: center; border: 1px solid #bbf7d0;">
                <div style="font-size: 3rem; margin-bottom: 16px;">🇸🇳</div>
                <div style="font-weight: 600; color: #047857; margin-bottom: 8px;">Sénégal</div>
                <div style="color: #059669; font-size: 0.9rem;">Analyse des disparités régionales</div>
            </div>

            <!-- Indicateurs régionaux -->
            @if($latestReport && $latestReport->key_trends && isset($latestReport->key_trends['disparites_regionales']))
            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border-left: 4px solid #059669;">
                <h4 style="color: #047857; font-size: 1rem; font-weight: 600; margin-bottom: 8px;">Disparités observées</h4>
                <p style="color: #374151; line-height: 1.4; margin: 0; font-size: 0.9rem;">{{ Str::limit($latestReport->key_trends['disparites_regionales'], 120) }}</p>
            </div>
            @endif

            <!-- Zones à surveiller -->
            <div style="margin-top: 16px;">
                <h4 style="color: #374151; font-size: 1rem; font-weight: 600; margin-bottom: 12px;">Zones à surveiller</h4>
                <div style="display: grid; gap: 8px;">
                    <div style="background: #fef2f2; border-radius: 6px; padding: 10px; border-left: 3px solid #dc2626;">
                        <div style="font-weight: 600; color: #dc2626; font-size: 0.85rem;">Régions à prix élevés</div>
                        <div style="color: #6b7280; font-size: 0.75rem;">Mil, riz local, arachide</div>
                    </div>
                    <div style="background: #fef2f2; border-radius: 6px; padding: 10px; border-left: 3px solid #dc2626;">
                        <div style="font-weight: 600; color: #dc2626; font-size: 0.85rem;">Pression sur l'approvisionnement</div>
                        <div style="color: #6b7280; font-size: 0.75rem;">Période de soudure</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernier rapport -->
    @if($latestReport)
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin: 0;">📋 Dernier rapport publié</h2>
            <a href="{{ route('sim.show', $latestReport) }}" style="background: #059669; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 500;">Voir le rapport</a>
        </div>
        <div style="background: #f8fafc; border-radius: 12px; padding: 20px;">
            <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">{{ $latestReport->title }}</h3>
            <p style="color: #6b7280; margin-bottom: 16px;">{{ $latestReport->summary }}</p>
            <div style="display: flex; gap: 20px; font-size: 0.9rem; color: #6b7280;">
                <span>📅 {{ $latestReport->period }}</span>
                <span>📊 {{ $priceStats ? $priceStats['total_products'] : 0 }} produits analysés</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Rapports récents -->
    @if($recentReports && $recentReports->count() > 0)
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px;">
        <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin-bottom: 24px;">📚 Rapports récents</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            @foreach($recentReports as $report)
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #059669; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">
                    <a href="{{ route('sim.show', $report) }}" style="color: inherit; text-decoration: none;">
                        {{ $report->title }}
                    </a>
                </h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">{{ $report->period }}</p>
                <p style="color: #374151; line-height: 1.4; margin: 0; font-size: 0.95rem;">
                    {{ Str::limit($report->summary, 100) }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
/* RESPONSIVE - PAGES SIM */
@media (max-width: 1024px) {
    .sim-grid, .data-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 1.5rem; }
    .chart-container { height: 300px !important; }
}

@media (max-width: 768px) {
    .sim-grid, .data-grid { grid-template-columns: 1fr !important; gap: 1rem; }
    .sim-hero, .dashboard-hero { min-height: 200px !important; padding: 40px 0 !important; }
    .sim-hero h1, .dashboard-hero h1 { font-size: 1.8rem !important; }
    .filter-bar { flex-direction: column !important; }
    .filter-bar > * { width: 100% !important; }
    .chart-container { height: 250px !important; }
    .stat-card { padding: 1.5rem !important; }
    .data-table-wrapper { overflow-x: auto; }
    .data-table { min-width: 600px; }
}

@media (max-width: 480px) {
    .sim-hero h1 { font-size: 1.5rem !important; }
    .stat-card { padding: 1rem !important; }
    .btn-group { flex-direction: column !important; gap: 0.5rem; }
    .btn-group .btn { width: 100%; }
}
</style>
@endsection

@section('title', 'Tableau de bord SIM - CSAR')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 0;">
    <h1 class="main-title" style="font-size: 2.5rem; font-weight: 800; color: #059669; margin-bottom: 24px;">Tableau de bord du Système d'Information des Marchés (SIM)</h1>
    <p style="font-size: 1.2rem; color: #374151; margin-bottom: 40px;">Bienvenue sur le tableau de bord SIM. Retrouvez ici les indicateurs clés sur les prix, l'approvisionnement et la répartition régionale.</p>

    <!-- Indicateurs clés -->
    @if($latestReport && $priceStats)
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 40px;">
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">{{ $priceStats['total_products'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Produits suivis</div>
        </div>
        <div style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">{{ $priceStats['price_increases'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Hausses de prix</div>
        </div>
        <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">{{ $priceStats['price_decreases'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Baisses de prix</div>
        </div>
        <div style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%); color: white; border-radius: 16px; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; margin-bottom: 8px;">{{ $priceStats['stable_prices'] }}</div>
            <div style="font-size: 0.9rem; opacity: 0.9;">Prix stables</div>
        </div>
    </div>
    @endif

    <!-- Section principale -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 32px; margin-bottom: 40px;">
        
        <!-- Suivi des Prix -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin: 0;">📊 Suivi des Prix</h2>
                <a href="{{ route('sim.prices') }}" style="background: #059669; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">Voir plus</a>
            </div>
            <p style="color: #6b7280; margin-bottom: 24px; line-height: 1.5;">Surveillance régulière des prix de détail des produits agricoles sur les marchés sénégalais</p>
            
            @if($latestReport && $latestReport->price_analysis)
                <!-- Plus forte hausse -->
                @if($priceStats && $priceStats['highest_increase']['product'])
                <div style="background: #fef2f2; border-radius: 12px; padding: 16px; margin-bottom: 20px; border-left: 4px solid #dc2626;">
                    <div style="font-weight: 600; color: #dc2626; margin-bottom: 4px;">Plus forte hausse</div>
                    <div style="color: #374151;">{{ str_replace('_', ' ', $priceStats['highest_increase']['product']) }} : +{{ number_format($priceStats['highest_increase']['percentage'], 1) }}%</div>
                </div>
                @endif

                <!-- Plus forte baisse -->
                @if($priceStats && $priceStats['highest_decrease']['product'])
                <div style="background: #f0fdf4; border-radius: 12px; padding: 16px; margin-bottom: 20px; border-left: 4px solid #059669;">
                    <div style="font-weight: 600; color: #059669; margin-bottom: 4px;">Plus forte baisse</div>
                    <div style="color: #374151;">{{ str_replace('_', ' ', $priceStats['highest_decrease']['product']) }} : {{ number_format($priceStats['highest_decrease']['percentage'], 1) }}%</div>
                </div>
                @endif

                <!-- Résumé des catégories -->
                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                    <h4 style="color: #374151; font-size: 1rem; font-weight: 600; margin-bottom: 12px;">Résumé par catégorie</h4>
                    @foreach($priceStats['categories'] as $category => $stats)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; {{ !$loop->last ? 'border-bottom: 1px solid #e5e7eb;' : '' }}">
                        <span style="color: #374151; text-transform: capitalize; font-size: 0.9rem;">{{ str_replace('_', ' ', $category) }}</span>
                        <div style="display: flex; gap: 12px; font-size: 0.8rem;">
                            <span style="color: #dc2626;">{{ $stats['increases'] }} hausses</span>
                            <span style="color: #059669;">{{ $stats['decreases'] }} baisses</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p style="color: #6b7280; text-align: center; padding: 40px;">Aucune donnée de prix disponible</p>
            @endif
        </div>

        <!-- Niveau d'Approvisionnement -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin: 0;">📦 Niveau d'Approvisionnement</h2>
                <a href="{{ route('sim.supply') }}" style="background: #059669; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">Voir plus</a>
            </div>
            <p style="color: #6b7280; margin-bottom: 24px; line-height: 1.5;">Évaluation des volumes disponibles sur les marchés et analyse des tendances d'approvisionnement</p>
            
            @if($latestReport && $latestReport->supply_level)
                <!-- Statistiques d'approvisionnement -->
                @php
                    $totalSupply = 0;
                    $totalIncrease = 0;
                    $totalDecrease = 0;
                    foreach($latestReport->supply_level as $category => $products) {
                        foreach($products as $product => $data) {
                            $totalSupply += $data['juillet'];
                            if($data['variation'] > 0) $totalIncrease++;
                            elseif($data['variation'] < 0) $totalDecrease++;
                        }
                    }
                @endphp

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #bbf7d0;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ number_format($totalSupply, 0) }}</div>
                        <div style="font-size: 0.8rem; color: #047857;">Tonnes totales</div>
                    </div>
                    <div style="background: #fef2f2; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #fecaca;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #dc2626;">{{ $totalIncrease }}</div>
                        <div style="font-size: 0.8rem; color: #b91c1c;">Augmentations</div>
                    </div>
                    <div style="background: #f0fdf4; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #bbf7d0;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ $totalDecrease }}</div>
                        <div style="font-size: 0.8rem; color: #047857;">Diminutions</div>
                    </div>
                </div>

                <!-- Top 3 des produits avec plus forte augmentation -->
                <h4 style="color: #374151; font-size: 1rem; font-weight: 600; margin-bottom: 12px;">Top 3 - Plus fortes augmentations</h4>
                <div style="background: #f9fafb; border-radius: 8px; padding: 16px;">
                    @php
                        $topIncreases = [];
                        foreach($latestReport->supply_level as $category => $products) {
                            foreach($products as $product => $data) {
                                if($data['variation'] > 0) {
                                    $topIncreases[] = [
                                        'product' => $product,
                                        'variation' => $data['variation'],
                                        'category' => $category
                                    ];
                                }
                            }
                        }
                        usort($topIncreases, function($a, $b) {
                            return $b['variation'] <=> $a['variation'];
                        });
                        $topIncreases = array_slice($topIncreases, 0, 3);
                    @endphp

                    @foreach($topIncreases as $index => $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 0; {{ $index < count($topIncreases) - 1 ? 'border-bottom: 1px solid #e5e7eb;' : '' }}">
                        <div>
                            <div style="font-weight: 500; color: #374151; text-transform: capitalize; font-size: 0.9rem;">{{ str_replace('_', ' ', $item['product']) }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280; text-transform: capitalize;">{{ str_replace('_', ' ', $item['category']) }}</div>
                        </div>
                        <div style="font-weight: 600; color: #059669; font-size: 0.9rem;">+{{ number_format($item['variation'], 0) }}%</div>
                    </div>
                    @endforeach
                </div>
            @else
                <p style="color: #6b7280; text-align: center; padding: 40px;">Aucune donnée d'approvisionnement disponible</p>
            @endif
        </div>

        <!-- Répartition Régionale -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin: 0;">🗺️ Répartition Régionale</h2>
                <a href="{{ route('sim.regional') }}" style="background: #059669; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 500;">Voir plus</a>
            </div>
            <p style="color: #6b7280; margin-bottom: 24px; line-height: 1.5;">Analyse des disparités de prix entre les différentes régions du Sénégal</p>
            
            <!-- Carte du Sénégal avec indicateurs -->
            <div style="background: #f0fdf4; border-radius: 12px; padding: 24px; margin-bottom: 20px; text-align: center; border: 1px solid #bbf7d0;">
                <div style="font-size: 3rem; margin-bottom: 16px;">🇸🇳</div>
                <div style="font-weight: 600; color: #047857; margin-bottom: 8px;">Sénégal</div>
                <div style="color: #059669; font-size: 0.9rem;">Analyse des disparités régionales</div>
            </div>

            <!-- Indicateurs régionaux -->
            @if($latestReport && $latestReport->key_trends && isset($latestReport->key_trends['disparites_regionales']))
            <div style="background: #f8fafc; border-radius: 12px; padding: 16px; border-left: 4px solid #059669;">
                <h4 style="color: #047857; font-size: 1rem; font-weight: 600; margin-bottom: 8px;">Disparités observées</h4>
                <p style="color: #374151; line-height: 1.4; margin: 0; font-size: 0.9rem;">{{ Str::limit($latestReport->key_trends['disparites_regionales'], 120) }}</p>
            </div>
            @endif

            <!-- Zones à surveiller -->
            <div style="margin-top: 16px;">
                <h4 style="color: #374151; font-size: 1rem; font-weight: 600; margin-bottom: 12px;">Zones à surveiller</h4>
                <div style="display: grid; gap: 8px;">
                    <div style="background: #fef2f2; border-radius: 6px; padding: 10px; border-left: 3px solid #dc2626;">
                        <div style="font-weight: 600; color: #dc2626; font-size: 0.85rem;">Régions à prix élevés</div>
                        <div style="color: #6b7280; font-size: 0.75rem;">Mil, riz local, arachide</div>
                    </div>
                    <div style="background: #fef2f2; border-radius: 6px; padding: 10px; border-left: 3px solid #dc2626;">
                        <div style="font-weight: 600; color: #dc2626; font-size: 0.85rem;">Pression sur l'approvisionnement</div>
                        <div style="color: #6b7280; font-size: 0.75rem;">Période de soudure</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernier rapport -->
    @if($latestReport)
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin: 0;">📋 Dernier rapport publié</h2>
            <a href="{{ route('sim.show', $latestReport) }}" style="background: #059669; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: 500;">Voir le rapport</a>
        </div>
        <div style="background: #f8fafc; border-radius: 12px; padding: 20px;">
            <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">{{ $latestReport->title }}</h3>
            <p style="color: #6b7280; margin-bottom: 16px;">{{ $latestReport->summary }}</p>
            <div style="display: flex; gap: 20px; font-size: 0.9rem; color: #6b7280;">
                <span>📅 {{ $latestReport->period }}</span>
                <span>📊 {{ $priceStats ? $priceStats['total_products'] : 0 }} produits analysés</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Rapports récents -->
    @if($recentReports && $recentReports->count() > 0)
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px;">
        <h2 style="color: #059669; font-size: 1.5rem; font-weight: 700; margin-bottom: 24px;">📚 Rapports récents</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            @foreach($recentReports as $report)
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #059669; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">
                    <a href="{{ route('sim.show', $report) }}" style="color: inherit; text-decoration: none;">
                        {{ $report->title }}
                    </a>
                </h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">{{ $report->period }}</p>
                <p style="color: #374151; line-height: 1.4; margin: 0; font-size: 0.95rem;">
                    {{ Str::limit($report->summary, 100) }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
/* RESPONSIVE - PAGES SIM */
@media (max-width: 1024px) {
    .sim-grid, .data-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 1.5rem; }
    .chart-container { height: 300px !important; }
}

@media (max-width: 768px) {
    .sim-grid, .data-grid { grid-template-columns: 1fr !important; gap: 1rem; }
    .sim-hero, .dashboard-hero { min-height: 200px !important; padding: 40px 0 !important; }
    .sim-hero h1, .dashboard-hero h1 { font-size: 1.8rem !important; }
    .filter-bar { flex-direction: column !important; }
    .filter-bar > * { width: 100% !important; }
    .chart-container { height: 250px !important; }
    .stat-card { padding: 1.5rem !important; }
    .data-table-wrapper { overflow-x: auto; }
    .data-table { min-width: 600px; }
}

@media (max-width: 480px) {
    .sim-hero h1 { font-size: 1.5rem !important; }
    .stat-card { padding: 1rem !important; }
    .btn-group { flex-direction: column !important; gap: 0.5rem; }
    .btn-group .btn { width: 100%; }
}
</style>
@endsection
 
 
 
 
 
 