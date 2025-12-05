@extends('layouts.admin')

@section('title', 'Gestion des Stocks')
@section('page-title', 'Gestion des Stocks')

@section('content')
    <div class="page-header">
    <h1 class="page-title">Gestion des Stocks</h1>
    <p class="page-subtitle">Suivi et gestion des stocks d'entrepôts</p>
</div>

<!-- Statistiques des stocks -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);">
                    <i class="fas fa-boxes"></i>
                </div>
            <h3 class="stats-number">{{ count($stocks) }}</h3>
            <p class="stats-label">Articles en Stock</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            <h3 class="stats-number">{{ collect($stocks)->where('statut', 'faible')->count() }}</h3>
            <p class="stats-label">Stock Faible</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #74c0fc 0%, #339af0 100%);">
                <i class="fas fa-warehouse"></i>
            </div>
            <h3 class="stats-number">{{ collect($stocks)->pluck('entrepot')->unique()->count() }}</h3>
            <p class="stats-label">Entrepôts</p>
                        </div>
                    </div>
                    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-icon" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);">
                <i class="fas fa-chart-line"></i>
                    </div>
            <h3 class="stats-number">{{ collect($stocks)->sum('quantite') }}</h3>
            <p class="stats-label">Total Quantité</p>
            </div>
        </div>
    </div>

<!-- Actions rapides -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tools me-2"></i>Actions Rapides
                </h5>
                        </div>
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.stocks.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nouveau Stock
                    </a>
                    <button class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                    <button class="btn btn-info">
                        <i class="fas fa-sync me-2"></i>Actualiser
                    </button>
                                </div>
                            </div>
                                </div>
                            </div>
                        </div>

<!-- Liste des stocks -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Liste des Stocks
                </h5>
                            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Quantité</th>
                                <th>Unité</th>
                                <th>Entrepôt</th>
                                <th>Seuil Minimum</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                            <tr>
                                <td>{{ is_array($stock) ? ($stock['id'] ?? 'N/A') : ($stock->id ?? 'N/A') }}</td>
                                <td>{{ is_array($stock) ? ($stock['nom'] ?? 'N/A') : ($stock->nom ?? 'N/A') }}</td>
                                <td>{{ is_array($stock) ? ($stock['quantite'] ?? 'N/A') : ($stock->quantite ?? 'N/A') }}</td>
                                <td>{{ is_array($stock) ? ($stock['unite'] ?? 'N/A') : ($stock->unite ?? 'N/A') }}</td>
                                <td>{{ is_array($stock) ? ($stock['entrepot'] ?? 'N/A') : ($stock->entrepot ?? 'N/A') }}</td>
                                <td>{{ is_array($stock) ? ($stock['seuil_minimum'] ?? 'N/A') : ($stock->seuil_minimum ?? 'N/A') }}</td>
                                <td>
                                    @php
                                        $statut = is_array($stock) ? ($stock['statut'] ?? 'normal') : ($stock->statut ?? 'normal');
                                    @endphp
                                    @if($statut == 'faible')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Faible
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Normal
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $stockId = is_array($stock) ? ($stock['id'] ?? 1) : ($stock->id ?? 1);
                                    @endphp
                                    <a href="{{ route('admin.stocks.show', $stockId) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.stocks.edit', $stockId) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="deleteStock({{ $stockId }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                            </div>
                        </div>
                    </div>
    </div>
</div>

@push('scripts')
<script>
function deleteStock(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce stock ?')) {
        // Logique de suppression
        console.log('Suppression du stock:', id);
    }
}
</script>
@endpush
@endsection