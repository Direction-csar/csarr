@extends('layouts.public')

@section('title', 'Niveau d\'Approvisionnement - SIM CSAR')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 0;">
    <!-- En-tête -->
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 40px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('sim.dashboard') }}" style="color: white; text-decoration: none; margin-right: 16px;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <h1 style="font-size: 2.5rem; font-weight: 700; margin: 0;">📦 Niveau d'Approvisionnement</h1>
        </div>
        <p style="font-size: 1.2rem; opacity: 0.9; margin: 0;">Évaluation des volumes disponibles sur les marchés et analyse des tendances d'approvisionnement</p>
    </div>

    @if($latestReport && $latestReport->supply_level)
        <!-- Statistiques globales -->
        @php
            $totalSupply = 0;
            $totalIncrease = 0;
            $totalDecrease = 0;
            $totalStable = 0;
            foreach($latestReport->supply_level as $category => $products) {
                foreach($products as $product => $data) {
                    $totalSupply += $data['juillet'];
                    if($data['variation'] > 0) $totalIncrease++;
                    elseif($data['variation'] < 0) $totalDecrease++;
                    else $totalStable++;
                }
            }
        @endphp

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; margin-bottom: 40px;">
            <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">{{ number_format($totalSupply, 0) }}</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Tonnes totales</div>
            </div>
            <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">{{ $totalIncrease }}</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Augmentations</div>
            </div>
            <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #dc2626; margin-bottom: 8px;">{{ $totalDecrease }}</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Diminutions</div>
            </div>
            <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #6b7280; margin-bottom: 8px;">{{ $totalStable }}</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Stable</div>
            </div>
        </div>

        <!-- Top 10 des plus fortes augmentations -->
        @php
            $topIncreases = [];
            foreach($latestReport->supply_level as $category => $products) {
                foreach($products as $product => $data) {
                    if($data['variation'] > 0) {
                        $topIncreases[] = [
                            'product' => $product,
                            'variation' => $data['variation'],
                            'category' => $category,
                            'juin' => $data['juin'],
                            'juillet' => $data['juillet']
                        ];
                    }
                }
            }
            usort($topIncreases, function($a, $b) {
                return $b['variation'] <=> $a['variation'];
            });
            $topIncreases = array_slice($topIncreases, 0, 10);
        @endphp

        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">📈 Top 10 - Plus fortes augmentations</h2>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: #f9fafb; border-radius: 8px;">
                    <thead>
                        <tr style="background: #e5e7eb;">
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Rang</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Produit</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Catégorie</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juin (t)</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juillet (t)</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Variation (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topIncreases as $index => $item)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 16px; font-weight: 600; color: #374151;">#{{ $index + 1 }}</td>
                            <td style="padding: 16px; font-weight: 500; color: #374151; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item['product']) }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #6b7280; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item['category']) }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($item['juin'], 1) }}</td>
                            <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($item['juillet'], 1) }}</td>
                            <td style="padding: 16px; text-align: center; font-weight: 600; color: #059669;">
                                +{{ number_format($item['variation'], 0) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top 10 des plus fortes diminutions -->
        @php
            $topDecreases = [];
            foreach($latestReport->supply_level as $category => $products) {
                foreach($products as $product => $data) {
                    if($data['variation'] < 0) {
                        $topDecreases[] = [
                            'product' => $product,
                            'variation' => $data['variation'],
                            'category' => $category,
                            'juin' => $data['juin'],
                            'juillet' => $data['juillet']
                        ];
                    }
                }
            }
            usort($topDecreases, function($a, $b) {
                return $a['variation'] <=> $b['variation'];
            });
            $topDecreases = array_slice($topDecreases, 0, 10);
        @endphp

        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #dc2626; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">📉 Top 10 - Plus fortes diminutions</h2>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: #f9fafb; border-radius: 8px;">
                    <thead>
                        <tr style="background: #e5e7eb;">
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Rang</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Produit</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Catégorie</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juin (t)</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juillet (t)</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Variation (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topDecreases as $index => $item)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 16px; font-weight: 600; color: #374151;">#{{ $index + 1 }}</td>
                            <td style="padding: 16px; font-weight: 500; color: #374151; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item['product']) }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #6b7280; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item['category']) }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($item['juin'], 1) }}</td>
                            <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($item['juillet'], 1) }}</td>
                            <td style="padding: 16px; text-align: center; font-weight: 600; color: #dc2626;">
                                {{ number_format($item['variation'], 0) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Analyse détaillée par catégorie -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Analyse détaillée par catégorie</h2>
            
            @foreach($latestReport->supply_level as $category => $products)
            <div style="margin-bottom: 32px;">
                <h3 style="color: #374151; font-size: 1.4rem; font-weight: 600; margin-bottom: 16px; text-transform: capitalize; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                    {{ str_replace('_', ' ', $category) }}
                </h3>
                
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; background: #f9fafb; border-radius: 8px;">
                        <thead>
                            <tr style="background: #e5e7eb;">
                                <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Produit</th>
                                <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juin (tonnes)</th>
                                <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juillet (tonnes)</th>
                                <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Variation (%)</th>
                                <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Tendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product => $data)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 16px; font-weight: 500; color: #374151; text-transform: capitalize;">
                                    {{ str_replace('_', ' ', $product) }}
                                </td>
                                <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($data['juin'], 1) }}</td>
                                <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($data['juillet'], 1) }}</td>
                                <td style="padding: 16px; text-align: center; font-weight: 600; 
                                    color: {{ $data['variation'] > 0 ? '#059669' : ($data['variation'] < 0 ? '#dc2626' : '#374151') }};">
                                    {{ $data['variation'] > 0 ? '+' : '' }}{{ number_format($data['variation'], 0) }}%
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                    @if($data['variation'] > 0)
                                        <span style="background: #f0fdf4; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">📈 Augmentation</span>
                                    @elseif($data['variation'] < 0)
                                        <span style="background: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">📉 Diminution</span>
                                    @else
                                        <span style="background: #f3f4f6; color: #6b7280; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">➡️ Stable</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Résumé par catégorie -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Résumé par catégorie</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                @foreach($latestReport->supply_level as $category => $products)
                @php
                    $categoryTotal = 0;
                    $categoryIncrease = 0;
                    $categoryDecrease = 0;
                    $categoryStable = 0;
                    foreach($products as $product => $data) {
                        $categoryTotal += $data['juillet'];
                        if($data['variation'] > 0) $categoryIncrease++;
                        elseif($data['variation'] < 0) $categoryDecrease++;
                        else $categoryStable++;
                    }
                @endphp
                <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                    <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 16px; text-transform: capitalize;">
                        {{ str_replace('_', ' ', $category) }}
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ number_format($categoryTotal, 0) }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Tonnes totales</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ count($products) }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Produits</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ $categoryIncrease }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Augmentations</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #dc2626;">{{ $categoryDecrease }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Diminutions</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    @else
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 40px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📦</div>
            <h2 style="color: #374151; font-size: 1.5rem; font-weight: 600; margin-bottom: 12px;">Aucune donnée disponible</h2>
            <p style="color: #6b7280;">Les données d'approvisionnement ne sont pas encore disponibles pour cette période.</p>
        </div>
    @endif

    <!-- Retour au tableau de bord -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('sim.dashboard') }}" style="background: #059669; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Retour au tableau de bord
        </a>
    </div>
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

@section('title', 'Niveau d\'Approvisionnement - SIM CSAR')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 0;">
    <!-- En-tête -->
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 40px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('sim.dashboard') }}" style="color: white; text-decoration: none; margin-right: 16px;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <h1 style="font-size: 2.5rem; font-weight: 700; margin: 0;">📦 Niveau d'Approvisionnement</h1>
        </div>
        <p style="font-size: 1.2rem; opacity: 0.9; margin: 0;">Évaluation des volumes disponibles sur les marchés et analyse des tendances d'approvisionnement</p>
    </div>

    @if($latestReport && $latestReport->supply_level)
        <!-- Statistiques globales -->
        @php
            $totalSupply = 0;
            $totalIncrease = 0;
            $totalDecrease = 0;
            $totalStable = 0;
            foreach($latestReport->supply_level as $category => $products) {
                foreach($products as $product => $data) {
                    $totalSupply += $data['juillet'];
                    if($data['variation'] > 0) $totalIncrease++;
                    elseif($data['variation'] < 0) $totalDecrease++;
                    else $totalStable++;
                }
            }
        @endphp

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px; margin-bottom: 40px;">
            <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">{{ number_format($totalSupply, 0) }}</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Tonnes totales</div>
            </div>
            <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">{{ $totalIncrease }}</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Augmentations</div>
            </div>
            <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #dc2626; margin-bottom: 8px;">{{ $totalDecrease }}</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Diminutions</div>
            </div>
            <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
                <div style="font-size: 2rem; font-weight: 800; color: #6b7280; margin-bottom: 8px;">{{ $totalStable }}</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Stable</div>
            </div>
        </div>

        <!-- Top 10 des plus fortes augmentations -->
        @php
            $topIncreases = [];
            foreach($latestReport->supply_level as $category => $products) {
                foreach($products as $product => $data) {
                    if($data['variation'] > 0) {
                        $topIncreases[] = [
                            'product' => $product,
                            'variation' => $data['variation'],
                            'category' => $category,
                            'juin' => $data['juin'],
                            'juillet' => $data['juillet']
                        ];
                    }
                }
            }
            usort($topIncreases, function($a, $b) {
                return $b['variation'] <=> $a['variation'];
            });
            $topIncreases = array_slice($topIncreases, 0, 10);
        @endphp

        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">📈 Top 10 - Plus fortes augmentations</h2>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: #f9fafb; border-radius: 8px;">
                    <thead>
                        <tr style="background: #e5e7eb;">
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Rang</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Produit</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Catégorie</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juin (t)</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juillet (t)</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Variation (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topIncreases as $index => $item)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 16px; font-weight: 600; color: #374151;">#{{ $index + 1 }}</td>
                            <td style="padding: 16px; font-weight: 500; color: #374151; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item['product']) }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #6b7280; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item['category']) }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($item['juin'], 1) }}</td>
                            <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($item['juillet'], 1) }}</td>
                            <td style="padding: 16px; text-align: center; font-weight: 600; color: #059669;">
                                +{{ number_format($item['variation'], 0) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top 10 des plus fortes diminutions -->
        @php
            $topDecreases = [];
            foreach($latestReport->supply_level as $category => $products) {
                foreach($products as $product => $data) {
                    if($data['variation'] < 0) {
                        $topDecreases[] = [
                            'product' => $product,
                            'variation' => $data['variation'],
                            'category' => $category,
                            'juin' => $data['juin'],
                            'juillet' => $data['juillet']
                        ];
                    }
                }
            }
            usort($topDecreases, function($a, $b) {
                return $a['variation'] <=> $b['variation'];
            });
            $topDecreases = array_slice($topDecreases, 0, 10);
        @endphp

        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #dc2626; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">📉 Top 10 - Plus fortes diminutions</h2>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: #f9fafb; border-radius: 8px;">
                    <thead>
                        <tr style="background: #e5e7eb;">
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Rang</th>
                            <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Produit</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Catégorie</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juin (t)</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juillet (t)</th>
                            <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Variation (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topDecreases as $index => $item)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 16px; font-weight: 600; color: #374151;">#{{ $index + 1 }}</td>
                            <td style="padding: 16px; font-weight: 500; color: #374151; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item['product']) }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #6b7280; text-transform: capitalize;">
                                {{ str_replace('_', ' ', $item['category']) }}
                            </td>
                            <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($item['juin'], 1) }}</td>
                            <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($item['juillet'], 1) }}</td>
                            <td style="padding: 16px; text-align: center; font-weight: 600; color: #dc2626;">
                                {{ number_format($item['variation'], 0) }}%
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Analyse détaillée par catégorie -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Analyse détaillée par catégorie</h2>
            
            @foreach($latestReport->supply_level as $category => $products)
            <div style="margin-bottom: 32px;">
                <h3 style="color: #374151; font-size: 1.4rem; font-weight: 600; margin-bottom: 16px; text-transform: capitalize; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px;">
                    {{ str_replace('_', ' ', $category) }}
                </h3>
                
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; background: #f9fafb; border-radius: 8px;">
                        <thead>
                            <tr style="background: #e5e7eb;">
                                <th style="padding: 16px; text-align: left; font-weight: 600; color: #374151;">Produit</th>
                                <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juin (tonnes)</th>
                                <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Juillet (tonnes)</th>
                                <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Variation (%)</th>
                                <th style="padding: 16px; text-align: center; font-weight: 600; color: #374151;">Tendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product => $data)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 16px; font-weight: 500; color: #374151; text-transform: capitalize;">
                                    {{ str_replace('_', ' ', $product) }}
                                </td>
                                <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($data['juin'], 1) }}</td>
                                <td style="padding: 16px; text-align: center; color: #374151;">{{ number_format($data['juillet'], 1) }}</td>
                                <td style="padding: 16px; text-align: center; font-weight: 600; 
                                    color: {{ $data['variation'] > 0 ? '#059669' : ($data['variation'] < 0 ? '#dc2626' : '#374151') }};">
                                    {{ $data['variation'] > 0 ? '+' : '' }}{{ number_format($data['variation'], 0) }}%
                                </td>
                                <td style="padding: 16px; text-align: center;">
                                    @if($data['variation'] > 0)
                                        <span style="background: #f0fdf4; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">📈 Augmentation</span>
                                    @elseif($data['variation'] < 0)
                                        <span style="background: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">📉 Diminution</span>
                                    @else
                                        <span style="background: #f3f4f6; color: #6b7280; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">➡️ Stable</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Résumé par catégorie -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Résumé par catégorie</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                @foreach($latestReport->supply_level as $category => $products)
                @php
                    $categoryTotal = 0;
                    $categoryIncrease = 0;
                    $categoryDecrease = 0;
                    $categoryStable = 0;
                    foreach($products as $product => $data) {
                        $categoryTotal += $data['juillet'];
                        if($data['variation'] > 0) $categoryIncrease++;
                        elseif($data['variation'] < 0) $categoryDecrease++;
                        else $categoryStable++;
                    }
                @endphp
                <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                    <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 16px; text-transform: capitalize;">
                        {{ str_replace('_', ' ', $category) }}
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ number_format($categoryTotal, 0) }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Tonnes totales</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ count($products) }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Produits</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #059669;">{{ $categoryIncrease }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Augmentations</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #dc2626;">{{ $categoryDecrease }}</div>
                            <div style="font-size: 0.8rem; color: #6b7280;">Diminutions</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    @else
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 40px; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 16px;">📦</div>
            <h2 style="color: #374151; font-size: 1.5rem; font-weight: 600; margin-bottom: 12px;">Aucune donnée disponible</h2>
            <p style="color: #6b7280;">Les données d'approvisionnement ne sont pas encore disponibles pour cette période.</p>
        </div>
    @endif

    <!-- Retour au tableau de bord -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('sim.dashboard') }}" style="background: #059669; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Retour au tableau de bord
        </a>
    </div>
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
 
 
 
 
 
 