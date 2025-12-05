@extends('layouts.responsable')

@section('title', 'Historique des Mouvements - Responsable Entrepôt')
@section('page-title', 'Historique des Mouvements')

@section('content')
<!-- Filtres -->
<div class="card">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">
        📋 Historique des mouvements de stock
    </h2>
    
    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <select style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
            <option value="">Tous les types</option>
            <option value="in">Entrées</option>
            <option value="out">Sorties</option>
        </select>
        
        <select style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
            <option value="">Tous les produits</option>
            <option value="riz">Riz</option>
            <option value="lait">Lait en poudre</option>
            <option value="huile">Huile</option>
            <option value="materiel">Matériel</option>
        </select>
        
        <input type="date" style="padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
        
        <button class="btn-primary">
            🔍 Filtrer
        </button>
        
        <button class="btn-secondary">
            📥 Exporter
        </button>
    </div>
</div>

<!-- Liste des mouvements -->
<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f8fafc;">
                    <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Date</th>
                    <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Type</th>
                    <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Produit</th>
                    <th style="padding: 0.75rem; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Quantité</th>
                    <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Source/Destination</th>
                    <th style="padding: 0.75rem; text-align: left; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Notes</th>
                    <th style="padding: 0.75rem; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movements as $movement)
                <tr style="border-bottom: 1px solid #f3f4f6;">
                    <td style="padding: 0.75rem; color: #6b7280; font-size: 0.875rem;">
                        {{ \Carbon\Carbon::parse($movement['date'])->format('d/m/Y H:i') }}
                    </td>
                    <td style="padding: 0.75rem;">
                        @if($movement['type'] === 'in')
                            <span style="background-color: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                                ➕ Entrée
                            </span>
                        @else
                            <span style="background-color: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                                ➖ Sortie
                            </span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem; font-weight: 600; color: #1e293b;">
                        {{ $movement['product'] }}
                    </td>
                    <td style="padding: 0.75rem; text-align: center; font-weight: 600;">
                        @if($movement['type'] === 'in')
                            <span style="color: #059669;">+{{ number_format($movement['quantity']) }} {{ $movement['unit'] }}</span>
                        @else
                            <span style="color: #dc2626;">-{{ number_format($movement['quantity']) }} {{ $movement['unit'] }}</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem; color: #6b7280;">
                        @if($movement['type'] === 'in')
                            {{ $movement['supplier'] ?? 'N/A' }}
                        @else
                            {{ $movement['destination'] ?? 'N/A' }}
                        @endif
                    </td>
                    <td style="padding: 0.75rem; color: #6b7280; font-size: 0.875rem;">
                        {{ $movement['notes'] ?? '-' }}
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">
                        <button class="btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                            👁️ Voir
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-top: 2rem;">
        <button class="btn-secondary" style="padding: 0.5rem 1rem;">
            ← Précédent
        </button>
        
        <div style="display: flex; gap: 0.5rem;">
            <button style="background-color: #059669; color: white; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem; font-weight: 600;">1</button>
            <button style="background-color: #f3f4f6; color: #6b7280; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem;">2</button>
            <button style="background-color: #f3f4f6; color: #6b7280; padding: 0.5rem 0.75rem; border: none; border-radius: 0.375rem;">3</button>
        </div>
        
        <button class="btn-secondary" style="padding: 0.5rem 1rem;">
            Suivant →
        </button>
    </div>
</div>

<!-- Statistiques des mouvements -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
            📊 Statistiques du mois
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280;">Entrées totales</span>
                <span style="font-weight: 600; color: #059669;">+15,250 kg</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280;">Sorties totales</span>
                <span style="font-weight: 600; color: #dc2626;">-8,750 kg</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280;">Solde net</span>
                <span style="font-weight: 600; color: #059669;">+6,500 kg</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280;">Nombre de mouvements</span>
                <span style="font-weight: 600; color: #1e293b;">45</span>
            </div>
        </div>
    </div>
    
    <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
            🏆 Produits les plus actifs
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280;">1. Riz</span>
                <span style="font-weight: 600; color: #1e293b;">12 mouvements</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280;">2. Lait en poudre</span>
                <span style="font-weight: 600; color: #1e293b;">8 mouvements</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280;">3. Huile</span>
                <span style="font-weight: 600; color: #1e293b;">6 mouvements</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #6b7280;">4. Matériel</span>
                <span style="font-weight: 600; color: #1e293b;">5 mouvements</span>
            </div>
        </div>
    </div>
</div>
@endsection 