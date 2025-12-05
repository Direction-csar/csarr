@extends('layouts.responsable')

@section('title', 'Gestion du Stock - Responsable Entrepôt')
@section('page-title', 'Gestion du Stock')

@section('content')
<!-- Actions rapides -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">
            📦 Gestion du stock
        </h2>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('responsable.stock.create') }}" class="btn-primary">
                ➕ Ajouter une entrée
            </a>
            <a href="{{ route('responsable.stock.out') }}" class="btn-secondary">
                ➖ Enregistrer une sortie
            </a>
        </div>
    </div>
    
    <!-- Statistiques rapides -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div style="background-color: #f8fafc; padding: 1rem; border-radius: 0.375rem; text-align: center;">
            <div style="font-size: 2rem; color: #059669; margin-bottom: 0.5rem;">📦</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b;">{{ $stockData['total_items'] }}</div>
            <div style="font-size: 0.875rem; color: #6b7280;">Articles en stock</div>
        </div>
        
        <div style="background-color: #fef3c7; padding: 1rem; border-radius: 0.375rem; text-align: center;">
            <div style="font-size: 2rem; color: #92400e; margin-bottom: 0.5rem;">⚠️</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #92400e;">{{ $stockData['low_stock_items'] }}</div>
            <div style="font-size: 0.875rem; color: #92400e;">Stock faible</div>
        </div>
        
        <div style="background-color: #fee2e2; padding: 1rem; border-radius: 0.375rem; text-align: center;">
            <div style="font-size: 2rem; color: #991b1b; margin-bottom: 0.5rem;">🚨</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #991b1b;">{{ $stockData['out_of_stock_items'] }}</div>
            <div style="font-size: 0.875rem; color: #991b1b;">Rupture de stock</div>
        </div>
    </div>
</div>

<!-- Stock par catégorie -->
@foreach($stockData['categories'] as $categoryKey => $category)
<div class="card">
    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">
        {{ $category['name'] }}
    </h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f8fafc;">
                    <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Article</th>
                    <th style="padding: 0.75rem; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Quantité</th>
                    <th style="padding: 0.75rem; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Seuil critique</th>
                    <th style="padding: 0.75rem; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Statut</th>
                    <th style="padding: 0.75rem; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($category['items'] as $item)
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 0.75rem; font-weight: 600; color: #1e293b;">{{ $item['name'] }}</td>
                    <td style="padding: 0.75rem; text-align: center; color: #6b7280;">
                        {{ number_format($item['quantity']) }} {{ $item['unit'] }}
                    </td>
                    <td style="padding: 0.75rem; text-align: center; color: #6b7280;">
                        {{ number_format($item['min_threshold']) }} {{ $item['unit'] }}
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">
                        @if($item['status'] === 'success')
                            <span style="background-color: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                                ✅ OK
                            </span>
                        @elseif($item['status'] === 'warning')
                            <span style="background-color: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                                ⚠️ Faible
                            </span>
                        @elseif($item['status'] === 'danger')
                            <span style="background-color: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                                🚨 Critique
                            </span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">
                        <div style="display: flex; gap: 0.5rem; justify-content: center;">
                            <a href="{{ route('responsable.stock.create') }}?product={{ urlencode($item['name']) }}" 
                               class="btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                ➕
                            </a>
                            <a href="{{ route('responsable.stock.out') }}?product={{ urlencode($item['name']) }}" 
                               class="btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                ➖
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

<!-- Historique récent -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">
            📋 Mouvements récents
        </h3>
        <a href="{{ route('responsable.movements') }}" class="btn-secondary">
            Voir tout l'historique
        </a>
    </div>
    
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background-color: #f8fafc; border-radius: 0.375rem;">
            <div>
                <div style="font-weight: 600; color: #1e293b;">Entrée - Riz</div>
                <div style="font-size: 0.875rem; color: #6b7280;">+2,000 kg</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.875rem; color: #059669;">Aujourd'hui 14:30</div>
                <div style="font-size: 0.75rem; color: #6b7280;">Fournisseur ABC</div>
            </div>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background-color: #f8fafc; border-radius: 0.375rem;">
            <div>
                <div style="font-weight: 600; color: #1e293b;">Sortie - Lait en poudre</div>
                <div style="font-size: 0.875rem; color: #6b7280;">-500 kg</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.875rem; color: #dc2626;">Hier 16:45</div>
                <div style="font-size: 0.75rem; color: #6b7280;">Distribution Dakar</div>
            </div>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background-color: #f8fafc; border-radius: 0.375rem;">
            <div>
                <div style="font-weight: 600; color: #1e293b;">Entrée - Kits hygiène</div>
                <div style="font-size: 0.875rem; color: #6b7280;">+50 unités</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.875rem; color: #059669;">Il y a 2 jours</div>
                <div style="font-size: 0.75rem; color: #6b7280;">Fournisseur XYZ</div>
            </div>
        </div>
    </div>
</div>
@endsection 