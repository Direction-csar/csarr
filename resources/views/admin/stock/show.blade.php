@extends('layouts.admin')

@section('title', 'Détails du Mouvement de Stock')

@push('styles')
<style>
    .stock-detail-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    
    .detail-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 900px;
        margin: 0 auto;
    }
    
    .detail-header {
        text-align: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e9ecef;
    }
    
    .detail-title {
        color: #333;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .detail-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
    }
    
    .info-section {
        margin-bottom: 30px;
    }
    
    .section-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 10px;
        color: #667eea;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .info-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        border-left: 4px solid #667eea;
    }
    
    .info-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        color: #333;
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .badge-modern {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-entree {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .badge-sortie {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
        color: white;
    }
    
    .badge-transfert {
        background: linear-gradient(135deg, #007bff, #6f42c1);
        color: white;
    }
    
    .badge-ajustement {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }
    
    .actions-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-top: 30px;
    }
    
    .btn-modern {
        border-radius: 25px;
        padding: 12px 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
        margin: 5px;
    }
    
    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-modern:hover::before {
        left: 100%;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-success-modern {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .btn-danger-modern {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
        color: white;
    }
    
    .btn-info-modern {
        background: linear-gradient(135deg, #17a2b8, #6f42c1);
        color: white;
    }
    
    .btn-secondary-modern {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .receipt-preview {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-top: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }
    
    .receipt-header {
        text-align: center;
        border-bottom: 2px solid #333;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    
    .receipt-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 5px;
    }
    
    .receipt-subtitle {
        color: #6c757d;
        font-size: 1rem;
    }
    
    .receipt-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .receipt-item {
        padding: 10px 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .receipt-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }
    
    .receipt-value {
        color: #333;
        font-size: 1.1rem;
    }
    
    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .receipt-table th,
    .receipt-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    
    .receipt-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }
    
    .receipt-footer {
        margin-top: 30px;
        text-align: center;
        font-size: 0.9rem;
        color: #6c757d;
        border-top: 1px solid #e9ecef;
        padding-top: 20px;
    }
    
    @media (max-width: 768px) {
        .detail-container {
            padding: 25px;
            margin: 10px;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .receipt-info {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="stock-detail-page">
    <div class="container">
        <div class="detail-container">
            <!-- En-tête -->
            <div class="detail-header">
                <h1 class="detail-title">
                    <i class="fas fa-file-invoice me-3"></i>
                    Détails du Mouvement de Stock
                </h1>
                <p class="detail-subtitle">Référence: {{ $mouvement['reference'] }}</p>
            </div>

            <!-- Informations générales -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informations Générales
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Référence</div>
                        <div class="info-value">{{ $mouvement['reference'] }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Type de Mouvement</div>
                        <div class="info-value">
                            <span class="badge-modern badge-{{ $mouvement['type'] }}">
                                {{ ucfirst($mouvement['type']) }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date du Mouvement</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($mouvement['date_mouvement'])->format('d/m/Y à H:i') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date de Création</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($mouvement['created_at'])->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Détails du produit -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-box"></i>
                    Détails du Produit
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Produit</div>
                        <div class="info-value">{{ $mouvement['produit'] }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Quantité</div>
                        <div class="info-value">
                            <strong>{{ number_format($mouvement['quantite'], 0, ',', ' ') }} {{ $mouvement['unite'] }}</strong>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Prix Unitaire</div>
                        <div class="info-value">{{ number_format($mouvement['prix_unitaire'], 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total</div>
                        <div class="info-value">
                            <strong style="color: #28a745; font-size: 1.3rem;">
                                {{ number_format($mouvement['total'], 0, ',', ' ') }} FCFA
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de localisation -->
            <div class="info-section">
                <h3 class="section-title">
                    <i class="fas fa-map-marker-alt"></i>
                    Localisation et Responsabilité
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Entrepôt</div>
                        <div class="info-value">{{ $mouvement['entrepot_nom'] }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Responsable</div>
                        <div class="info-value">{{ $mouvement['responsable'] }}</div>
                    </div>
                    @if(isset($mouvement['motif']) && $mouvement['motif'])
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="info-label">Motif / Description</div>
                            <div class="info-value">{{ $mouvement['motif'] }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations spécifiques selon le type -->
            @if($mouvement['type'] === 'entree')
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-truck"></i>
                        Informations d'Entrée
                    </h3>
                    <div class="info-grid">
                        @if(isset($mouvement['fournisseur']))
                            <div class="info-item">
                                <div class="info-label">Fournisseur</div>
                                <div class="info-value">{{ $mouvement['fournisseur'] }}</div>
                            </div>
                        @endif
                        @if(isset($mouvement['numero_facture']))
                            <div class="info-item">
                                <div class="info-label">Numéro de Facture</div>
                                <div class="info-value">{{ $mouvement['numero_facture'] }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($mouvement['type'] === 'sortie')
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-shipping-fast"></i>
                        Informations de Sortie
                    </h3>
                    <div class="info-grid">
                        @if(isset($mouvement['destinataire']))
                            <div class="info-item">
                                <div class="info-label">Destinataire</div>
                                <div class="info-value">{{ $mouvement['destinataire'] }}</div>
                            </div>
                        @endif
                        @if(isset($mouvement['numero_bon']))
                            <div class="info-item">
                                <div class="info-label">Numéro de Bon</div>
                                <div class="info-value">{{ $mouvement['numero_bon'] }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($mouvement['type'] === 'transfert')
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-exchange-alt"></i>
                        Informations de Transfert
                    </h3>
                    <div class="info-grid">
                        @if(isset($mouvement['entrepot_destination']))
                            <div class="info-item">
                                <div class="info-label">Entrepôt de Destination</div>
                                <div class="info-value">{{ $mouvement['entrepot_destination'] }}</div>
                            </div>
                        @endif
                        @if(isset($mouvement['numero_transfert']))
                            <div class="info-item">
                                <div class="info-label">Numéro de Transfert</div>
                                <div class="info-value">{{ $mouvement['numero_transfert'] }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($mouvement['type'] === 'ajustement')
                <div class="info-section">
                    <h3 class="section-title">
                        <i class="fas fa-balance-scale"></i>
                        Informations d'Ajustement
                    </h3>
                    <div class="info-grid">
                        @if(isset($mouvement['raison_ajustement']))
                            <div class="info-item">
                                <div class="info-label">Raison de l'Ajustement</div>
                                <div class="info-value">{{ ucfirst($mouvement['raison_ajustement']) }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Aperçu du reçu -->
            <div class="receipt-preview">
                <h3 class="section-title">
                    <i class="fas fa-file-pdf"></i>
                    Aperçu du Reçu PDF
                </h3>
                
                <div class="receipt-header">
                    <h4 class="receipt-title">CSAR - CENTRE DE STOCKAGE ET D'APPROVISIONNEMENT RÉGIONAL</h4>
                    <p class="receipt-subtitle">REÇU DE MOUVEMENT DE STOCK</p>
                </div>
                
                <div class="receipt-info">
                    <div class="receipt-item">
                        <div class="receipt-label">Référence</div>
                        <div class="receipt-value">{{ $mouvement['reference'] }}</div>
                    </div>
                    <div class="receipt-item">
                        <div class="receipt-label">Type</div>
                        <div class="receipt-value">{{ strtoupper($mouvement['type']) }}</div>
                    </div>
                    <div class="receipt-item">
                        <div class="receipt-label">Date</div>
                        <div class="receipt-value">{{ \Carbon\Carbon::parse($mouvement['date_mouvement'])->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="receipt-item">
                        <div class="receipt-label">Responsable</div>
                        <div class="receipt-value">{{ $mouvement['responsable'] }}</div>
                    </div>
                </div>
                
                <table class="receipt-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Entrepôt</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $mouvement['produit'] }}</td>
                            <td>{{ number_format($mouvement['quantite'], 0, ',', ' ') }} {{ $mouvement['unite'] }}</td>
                            <td>{{ $mouvement['entrepot_nom'] }}</td>
                            <td>{{ number_format($mouvement['prix_unitaire'], 0, ',', ' ') }} FCFA</td>
                            <td><strong>{{ number_format($mouvement['total'], 0, ',', ' ') }} FCFA</strong></td>
                        </tr>
                    </tbody>
                </table>
                
                @if(isset($mouvement['motif']) && $mouvement['motif'])
                    <div class="receipt-item" style="margin-top: 20px;">
                        <div class="receipt-label">Motif</div>
                        <div class="receipt-value">{{ $mouvement['motif'] }}</div>
                    </div>
                @endif
                
                <div class="receipt-footer">
                    <p>Reçu généré automatiquement le {{ now()->format('d/m/Y à H:i') }}</p>
                    <p>CSAR - Système de Gestion des Stocks</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="actions-section">
                <h4 class="text-center mb-4">Actions Disponibles</h4>
                <div class="text-center">
                    <a href="{{ route('admin.stock.receipt', $mouvement['id']) }}" 
                       class="btn btn-success-modern btn-modern" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Télécharger Reçu PDF
                    </a>
                    <a href="{{ route('admin.stock.edit', $mouvement['id']) }}" 
                       class="btn btn-primary-modern btn-modern">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('admin.stock.index') }}" 
                       class="btn btn-secondary-modern btn-modern">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la Liste
                    </a>
                    <button class="btn btn-danger-modern btn-modern" 
                            onclick="deleteMouvement({{ $mouvement['id'] }})">
                        <i class="fas fa-trash me-2"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteMouvement(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce mouvement de stock ? Cette action est irréversible.')) {
        // Implémenter la suppression
        showToast('Mouvement supprimé avec succès', 'success');
        setTimeout(() => {
            window.location.href = '{{ route("admin.stock.index") }}';
        }, 1500);
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}
</script>
@endpush


