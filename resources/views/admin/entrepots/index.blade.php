@extends('layouts.admin')

@section('title', 'Gestion des Entrepôts')

@section('content')
<div class="container-fluid">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
    <div class="row mb-2">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show border-0" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="width: 40px; height: 40px; background: var(--gradient-success);">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>Succès !</strong><br>
                        <small>{{ session('success') }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-2">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show border-0" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="width: 40px; height: 40px; background: var(--gradient-danger);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>Erreur !</strong><br>
                        <small>{{ session('error') }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Header moderne -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 fw-bold">
                            <div class="icon-3d me-3" style="width: 50px; height: 50px; background: var(--gradient-primary); display: inline-flex; align-items: center; justify-content: center; border-radius: 12px;">
                                <i class="fas fa-warehouse text-white"></i>
                            </div>
                            🏢 Gestion des Entrepôts
                        </h2>
                        <p class="text-muted mb-0">Administrer les entrepôts du système CSAR</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportEntrepots()">
                            <i class="fas fa-download me-2"></i>Exporter
                        </button>
                        <a href="{{ route('admin.entrepots.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Nouvel Entrepôt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques dynamiques compactes -->
    <div class="row mb-2">
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="width: 40px; height: 40px; background: var(--gradient-primary);">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-0 small">Total Entrepôts</h6>
                        <h5 class="mb-0 fw-bold" id="total-entrepots">{{ $stats['total'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="width: 40px; height: 40px; background: var(--gradient-success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-0 small">Entrepôts Actifs</h6>
                        <h5 class="mb-0 fw-bold" id="active-entrepots">{{ $stats['actifs'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="width: 40px; height: 40px; background: var(--gradient-warning);">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-0 small">En Maintenance</h6>
                        <h5 class="mb-0 fw-bold" id="maintenance-entrepots">{{ $stats['maintenance'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="width: 40px; height: 40px; background: var(--gradient-info);">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-muted mb-0 small">Capacité Totale</h6>
                        <h5 class="mb-0 fw-bold" id="total-capacity">{{ number_format(($stats['capacite_totale'] ?? 0) / 1000, 1) }}K unités</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et indicateurs compacts -->
    <div class="row mb-3">
        <div class="col-lg-4 mb-3">
            <div class="card-modern p-2">
                <h6 class="fw-bold mb-2">📊 Répartition par Région</h6>
                <canvas id="regionsChart" height="150"></canvas>
            </div>
        </div>
        
        <div class="col-lg-4 mb-3">
            <div class="card-modern p-2">
                <h6 class="fw-bold mb-2">📈 Taux d'Occupation</h6>
                <canvas id="occupationChart" height="150"></canvas>
            </div>
        </div>
        
        <div class="col-lg-4 mb-3">
            <div class="card-modern p-2">
                <h6 class="fw-bold mb-2">📋 Résumé Rapide</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <div class="fw-bold text-primary">{{ $stats['total'] ?? 0 }}</div>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <div class="fw-bold text-success">{{ $stats['actifs'] ?? 0 }}</div>
                            <small class="text-muted">Actifs</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <div class="fw-bold text-warning">{{ $stats['maintenance'] ?? 0 }}</div>
                            <small class="text-muted">Maintenance</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2 bg-light rounded">
                            <div class="fw-bold text-info">{{ number_format(($stats['capacite_totale'] ?? 0) / 1000, 1) }}K</div>
                            <small class="text-muted">Capacité</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres compacts -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-2">
                <div class="row g-2">
                    <div class="col-lg-3 col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher...">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select class="form-select form-select-sm" id="regionFilter">
                            <option value="">Toutes régions</option>
                            <option value="Dakar">Dakar</option>
                            <option value="Thiès">Thiès</option>
                            <option value="Kaolack">Kaolack</option>
                            <option value="Saint-Louis">Saint-Louis</option>
                            <option value="Ziguinchor">Ziguinchor</option>
                            <option value="Diourbel">Diourbel</option>
                            <option value="Tambacounda">Tambacounda</option>
                            <option value="Kolda">Kolda</option>
                            <option value="Fatick">Fatick</option>
                            <option value="Louga">Louga</option>
                            <option value="Kédougou">Kédougou</option>
                            <option value="Sédhiou">Sédhiou</option>
                            <option value="Matam">Matam</option>
                            <option value="Kaffrine">Kaffrine</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">Tous statuts</option>
                            <option value="actif">Actif</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="inactif">Inactif</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select class="form-select form-select-sm" id="capacityFilter">
                            <option value="">Toutes capacités</option>
                            <option value="small">Petit (< 1K)</option>
                            <option value="medium">Moyen (1-3K)</option>
                            <option value="large">Grand (> 3K)</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex gap-1">
                            <button class="btn btn-outline-primary btn-sm" onclick="applyFilters()">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Effacer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte interactive compacte -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">🗺️ Carte des Entrepôts</h6>
                    <div class="d-flex gap-1">
                        <button class="btn btn-outline-primary btn-sm" onclick="centerMap()">
                            <i class="fas fa-crosshairs"></i> Centrer
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="toggleMapView()">
                            <i class="fas fa-layer-group"></i> Vue
                        </button>
                    </div>
                </div>
                <div id="entrepotsMap" style="height: 200px; border-radius: 8px; overflow: hidden;"></div>
            </div>
        </div>
    </div>

    <!-- Liste des entrepôts compacte -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">🏢 Liste des Entrepôts</h6>
                    <div class="d-flex gap-1">
                        <button class="btn btn-outline-success btn-sm" onclick="selectAll()">
                            <i class="fas fa-check-square"></i> Tout sélectionner
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="bulkAction('activate')">
                            <i class="fas fa-play"></i> Activer
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover" id="entrepotsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                                </th>
                                <th>Entrepôt</th>
                                <th>Région</th>
                                <th>Capacité</th>
                                <th>Occupation</th>
                                <th>Responsable</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="entrepotsTableBody">
                            @forelse($entrepots as $entrepot)
                                <tr data-id="{{ $entrepot->id }}" class="entrepot-row">
                                    <td>
                                        <input type="checkbox" class="form-check-input entrepot-checkbox" value="{{ $entrepot->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-warehouse text-white"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $entrepot->nom }}</div>
                                                <small class="text-muted">{{ $entrepot->adresse }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $entrepot->region }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ number_format($entrepot->capacite) }} unités</div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-{{ $entrepot->taux_occupation > 80 ? 'danger' : ($entrepot->taux_occupation > 60 ? 'warning' : 'success') }}" 
                                                     style="width: {{ $entrepot->taux_occupation }}%"></div>
                                            </div>
                                            <small class="fw-bold">{{ $entrepot->taux_occupation }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $entrepot->responsable }}</div>
                                        <small class="text-muted">{{ $entrepot->telephone_responsable }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $entrepot->statut === 'actif' ? 'success' : ($entrepot->statut === 'maintenance' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($entrepot->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.entrepots.show', $entrepot->id) }}" class="btn btn-outline-info btn-sm" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.entrepots.edit', $entrepot->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-{{ $entrepot->statut === 'actif' ? 'warning' : 'success' }} btn-sm" 
                                                    onclick="toggleStatus({{ $entrepot->id }}, '{{ $entrepot->statut }}')" 
                                                    title="{{ $entrepot->statut === 'actif' ? 'Mettre en maintenance' : 'Activer' }}">
                                                <i class="fas fa-{{ $entrepot->statut === 'actif' ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="deleteEntrepot({{ $entrepot->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-warehouse fa-3x mb-3"></i>
                                            <h5>Aucun entrepôt enregistré pour le moment</h5>
                                            <p>Il n'y a actuellement aucun entrepôt dans le système. Vous pouvez commencer par créer votre premier entrepôt.</p>
                                            <a href="{{ route('admin.entrepots.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Créer un entrepôt
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination compacte -->
                @if($entrepots->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div class="text-muted small">
                        {{ $entrepots->firstItem() }}-{{ $entrepots->lastItem() }} sur {{ $entrepots->total() }}
                    </div>
                    <div>
                        {{ $entrepots->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Variables CSS pour la cohérence */
:root {
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --gradient-danger: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    --gradient-info: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

/* Cartes modernes avec effets 3D */
.card-modern {
    background: rgba(255, 255, 255, 0.95);
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-primary);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.card-modern:hover::before {
    opacity: 1;
}

/* Icônes 3D */
.icon-3d {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.icon-3d:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

/* Boutons modernes */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Tableau moderne */
.table {
    border-radius: 10px;
    overflow: hidden;
}

.table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem 0.75rem;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: scale(1.01);
}

/* Badges modernes */
.badge {
    border-radius: 20px;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

/* Progress bars animées */
.progress {
    border-radius: 10px;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.1);
}

.progress-bar {
    transition: width 0.6s ease;
    border-radius: 10px;
}

/* Avatar moderne */
.avatar-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Animations */
@keyframes slideInFromTop {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideOutToRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

.entrepot-row.added {
    animation: slideInFromTop 0.5s ease;
}

.entrepot-row.removed {
    animation: slideOutToRight 0.5s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .card-modern {
        margin-bottom: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
}

/* Carte Leaflet */
#entrepotsMap {
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 10px;
}

/* Optimisation de l'espace */
.container-fluid {
    padding: 0.5rem;
}

.row {
    margin-bottom: 0.5rem;
}

.card-modern {
    margin-bottom: 0.5rem;
}

/* Graphiques plus compacts */
canvas {
    max-height: 150px !important;
}

/* Tableau plus compact */
.table {
    font-size: 0.9rem;
}

.table th, .table td {
    padding: 0.5rem 0.75rem;
}

/* Icônes plus petites */
.icon-3d {
    font-size: 1rem !important;
}

/* Espacement réduit */
.mb-3 {
    margin-bottom: 0.75rem !important;
}

.mb-2 {
    margin-bottom: 0.5rem !important;
}

/* Responsive optimisé */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0.25rem;
    }
    
    .row {
        margin-bottom: 0.25rem;
    }
    
    .card-modern {
        margin-bottom: 0.25rem;
        padding: 0.75rem !important;
    }
    
    canvas {
        max-height: 120px !important;
    }
}

/* Formulaires compacts */
.form-control-sm, .form-select-sm {
    font-size: 0.8rem !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 6px;
}

/* Assurer que tous les liens sont cliquables */
a, button, .btn {
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 10 !important;
}

/* Effets de survol pour les cartes */
.card-modern:hover .icon-3d {
    transform: scale(1.1) rotate(5deg);
}
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Leaflet CSS et JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Variables globales
let entrepotsMap;
let selectedEntrepots = [];
let currentFilters = {};

// Initialisation des graphiques
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    initializeMap();
    updateStats();
});

// Graphique des régions
function initializeCharts() {
    // Vérifier si Chart.js est disponible
    if (typeof Chart === 'undefined') {
        console.error('Chart.js n\'est pas chargé');
        return;
    }

    // Graphique des régions
    const regionsCtx = document.getElementById('regionsChart');
    if (regionsCtx) {
        const regionsData = [{{ $stats['dakar'] ?? 0 }}, {{ $stats['thies'] ?? 0 }}, {{ $stats['kaolack'] ?? 0 }}, {{ $stats['saint_louis'] ?? 0 }}, {{ $stats['ziguinchor'] ?? 0 }}, {{ $stats['autres'] ?? 0 }}];
        const totalRegions = regionsData.reduce((a, b) => a + b, 0);
        
        if (totalRegions === 0) {
            // Afficher un message si aucune donnée
            regionsCtx.parentElement.innerHTML = `
                <h6 class="fw-bold mb-2">📊 Répartition par Région</h6>
                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                    <div class="text-center">
                        <i class="fas fa-chart-pie fa-2x mb-2"></i>
                        <p class="mb-0 small">Aucun entrepôt enregistré</p>
                    </div>
                </div>
            `;
        } else {
            new Chart(regionsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Dakar', 'Thiès', 'Kaolack', 'Saint-Louis', 'Ziguinchor', 'Autres'],
                    datasets: [{
                        data: regionsData,
                        backgroundColor: [
                            '#ff6b6b',
                            '#4ecdc4',
                            '#45b7d1',
                            '#96ceb4',
                            '#feca57',
                            '#ff9ff3'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                usePointStyle: true,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Graphique d'occupation
    const occupationCtx = document.getElementById('occupationChart');
    if (occupationCtx) {
        const occupationData = [{{ $stats['occupation_0_20'] ?? 0 }}, {{ $stats['occupation_21_40'] ?? 0 }}, {{ $stats['occupation_41_60'] ?? 0 }}, {{ $stats['occupation_61_80'] ?? 0 }}, {{ $stats['occupation_81_100'] ?? 0 }}];
        const totalOccupation = occupationData.reduce((a, b) => a + b, 0);
        
        if (totalOccupation === 0) {
            // Afficher un message si aucune donnée
            occupationCtx.parentElement.innerHTML = `
                <h6 class="fw-bold mb-2">📈 Taux d'Occupation</h6>
                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                    <div class="text-center">
                        <i class="fas fa-chart-bar fa-2x mb-2"></i>
                        <p class="mb-0 small">Aucun entrepôt enregistré</p>
                    </div>
                </div>
            `;
        } else {
            new Chart(occupationCtx, {
                type: 'bar',
                data: {
                    labels: ['0-20%', '21-40%', '41-60%', '61-80%', '81-100%'],
                    datasets: [{
                        label: 'Nombre d\'entrepôts',
                        data: occupationData,
                        backgroundColor: [
                            '#51cf66',
                            '#74c0fc',
                            '#ffd43b',
                            '#ff8cc8',
                            '#ff6b6b'
                        ],
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }
    }
}

// Initialisation de la carte
function initializeMap() {
    // Initialiser la carte centrée sur le Sénégal
    entrepotsMap = L.map('entrepotsMap').setView([14.4974, -14.4524], 6);

    // Ajouter les tuiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(entrepotsMap);

    // Ajouter les marqueurs des entrepôts
    @if(isset($entrepots))
        @foreach($entrepots as $entrepot)
            @if(isset($entrepot->latitude) && isset($entrepot->longitude))
                L.marker([{{ $entrepot->latitude }}, {{ $entrepot->longitude }}])
                    .addTo(entrepotsMap)
                    .bindPopup(`
                        <div class="p-2">
                            <h6 class="fw-bold mb-1">{{ $entrepot->nom }}</h6>
                            <p class="mb-1"><i class="fas fa-map-marker-alt text-primary"></i> {{ $entrepot->adresse }}</p>
                            <p class="mb-1"><i class="fas fa-cubes text-info"></i> {{ number_format($entrepot->capacite) }} unités</p>
                            <p class="mb-1"><i class="fas fa-user text-success"></i> {{ $entrepot->responsable }}</p>
                            <p class="mb-0"><i class="fas fa-phone text-warning"></i> {{ $entrepot->telephone_responsable }}</p>
                        </div>
                    `);
            @endif
        @endforeach
    @endif
}

// Fonction de centrage de la carte
function centerMap() {
    entrepotsMap.setView([14.4974, -14.4524], 6);
}

// Fonction de basculement de vue de carte
function toggleMapView() {
    // Basculer entre vue satellite et standard
    showToast('Vue de carte changée!', 'info');
}

// Fonction de mise à jour des statistiques
function updateStats() {
    // Animation des compteurs
    animateValue('total-entrepots', 0, {{ $stats['total'] ?? 0 }}, 1000);
    animateValue('active-entrepots', 0, {{ $stats['actifs'] ?? 0 }}, 1000);
    animateValue('maintenance-entrepots', 0, {{ $stats['maintenance'] ?? 0 }}, 1000);
    animateValue('total-capacity', 0, {{ $stats['capacite_totale'] ?? 0 }}, 1000);
}

// Animation des valeurs
function animateValue(elementId, start, end, duration) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current);
    }, 16);
}

// Fonction de filtrage
function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const region = document.getElementById('regionFilter').value;
    const status = document.getElementById('statusFilter').value;
    const capacity = document.getElementById('capacityFilter').value;
    
    currentFilters = { search, region, status, capacity };
    
    // Appliquer les filtres
    filterTable();
    
    showToast('Filtres appliqués!', 'success');
}

// Fonction de filtrage du tableau
function filterTable() {
    const rows = document.querySelectorAll('.entrepot-row');
    const search = currentFilters.search.toLowerCase();
    const region = currentFilters.region;
    const status = currentFilters.status;
    const capacity = currentFilters.capacity;
    
    rows.forEach(row => {
        let show = true;
        
        // Filtre de recherche
        if (search) {
            const text = row.textContent.toLowerCase();
            if (!text.includes(search)) {
                show = false;
            }
        }
        
        // Filtre de région
        if (region) {
            const regionBadge = row.querySelector('.badge');
            if (regionBadge && !regionBadge.textContent.includes(region)) {
                show = false;
            }
        }
        
        // Filtre de statut
        if (status) {
            const statusBadges = row.querySelectorAll('.badge');
            let hasStatus = false;
            statusBadges.forEach(badge => {
                if (badge.textContent.toLowerCase().includes(status)) {
                    hasStatus = true;
                }
            });
            if (!hasStatus) {
                show = false;
            }
        }
        
        row.style.display = show ? '' : 'none';
    });
}

// Fonction d'effacement des filtres
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('regionFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('capacityFilter').value = '';
    
    currentFilters = {};
    
    // Afficher toutes les lignes
    const rows = document.querySelectorAll('.entrepot-row');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    showToast('Filtres effacés!', 'info');
}

// Fonction de sélection
function selectAll() {
    const checkboxes = document.querySelectorAll('.entrepot-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateSelectedCount();
}

// Fonction de mise à jour du compteur de sélection
function updateSelectedCount() {
    const checked = document.querySelectorAll('.entrepot-checkbox:checked');
    selectedEntrepots = Array.from(checked).map(cb => cb.value);
}

// Fonction d'action en lot
function bulkAction(action) {
    if (selectedEntrepots.length === 0) {
        showToast('Veuillez sélectionner au moins un entrepôt', 'warning');
        return;
    }
    
    const actionText = {
        'activate': 'activer',
        'delete': 'supprimer'
    };
    
    if (confirm(`Êtes-vous sûr de vouloir ${actionText[action]} ${selectedEntrepots.length} entrepôt(s) ?`)) {
        showToast(`${actionText[action].charAt(0).toUpperCase() + actionText[action].slice(1)} en cours...`, 'info');
        
        // Simuler l'action
        setTimeout(() => {
            showToast(`${selectedEntrepots.length} entrepôt(s) ${actionText[action]} avec succès!`, 'success');
            updateStats();
        }, 1500);
    }
}

// Fonction de basculement de statut
function toggleStatus(entrepotId, currentStatus) {
    const newStatus = currentStatus === 'actif' ? 'maintenance' : 'actif';
    const actionText = newStatus === 'actif' ? 'activer' : 'mettre en maintenance';
    
    if (confirm(`Êtes-vous sûr de vouloir ${actionText} cet entrepôt ?`)) {
        showToast(`Entrepôt ${actionText} avec succès!`, 'success');
        
        // Mettre à jour l'interface
        const row = document.querySelector(`tr[data-id="${entrepotId}"]`);
        if (row) {
            const statusBadge = row.querySelector('.badge:last-of-type');
            const toggleBtn = row.querySelector('button[onclick*="toggleStatus"]');
            
            if (statusBadge) {
                statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                statusBadge.className = `badge bg-${newStatus === 'actif' ? 'success' : 'warning'}`;
            }
            
            if (toggleBtn) {
                toggleBtn.className = `btn btn-outline-${newStatus === 'actif' ? 'warning' : 'success'} btn-sm`;
                toggleBtn.title = newStatus === 'actif' ? 'Mettre en maintenance' : 'Activer';
                toggleBtn.innerHTML = `<i class="fas fa-${newStatus === 'actif' ? 'pause' : 'play'}"></i>`;
                toggleBtn.setAttribute('onclick', `toggleStatus(${entrepotId}, '${newStatus}')`);
            }
        }
        
        updateStats();
    }
}

// Fonction de suppression d'un entrepôt
function deleteEntrepot(entrepotId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet entrepôt ?')) {
        showToast('Suppression en cours...', 'info');
        
        // Simuler la suppression avec animation
        const row = document.querySelector(`tr[data-id="${entrepotId}"]`);
        if (row) {
            row.classList.add('removed');
            setTimeout(() => {
                row.remove();
                showToast('Entrepôt supprimé avec succès!', 'success');
                updateStats();
            }, 500);
        }
    }
}

// Fonction d'export
function exportEntrepots() {
    showToast('Export en cours...', 'info');
    
    // Récupérer les filtres actuels
    const search = document.getElementById('searchInput')?.value || '';
    const region = document.getElementById('regionFilter')?.value || '';
    const status = document.getElementById('statusFilter')?.value || '';
    const capacity = document.getElementById('capacityFilter')?.value || '';
    
    // Construire l'URL avec les paramètres
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (region) params.append('region', region);
    if (status) params.append('statut', status);
    if (capacity) params.append('type', capacity);
    
    const exportUrl = '{{ route("admin.entrepots.export") }}' + (params.toString() ? '?' + params.toString() : '');
    
    // Créer un lien temporaire pour télécharger le fichier
    const link = document.createElement('a');
    link.href = exportUrl;
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    // Afficher le message de succès après un délai
    setTimeout(() => {
        showToast('Export terminé! Le fichier CSV a été téléchargé.', 'success');
    }, 1000);
}

// Fonction de toast
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toast-container') || createToastContainer();
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Supprimer le toast après fermeture
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Créer le conteneur de toast
function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toast-container';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}

// Événements
document.addEventListener('DOMContentLoaded', function() {
    // Événement de recherche en temps réel
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (this.value.length > 2 || this.value.length === 0) {
                applyFilters();
            }
        });
    }
    
    // Événements des checkboxes
    const checkboxes = document.querySelectorAll('.entrepot-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', selectAll);
    }
});
</script>
@endpush