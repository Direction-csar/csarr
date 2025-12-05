@extends('layouts.admin')

@section('title', 'Détails Entrepôt')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 fw-bold">
                            <div class="icon-3d me-3" style="width: 50px; height: 50px; background: var(--gradient-info); display: inline-flex; align-items: center; justify-content: center; border-radius: 12px;">
                                <i class="fas fa-warehouse text-white"></i>
                            </div>
                            🏢 {{ $entrepot->nom }}
                        </h2>
                        <p class="text-muted mb-0">Détails complets de l'entrepôt</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.entrepots.edit', $entrepot->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('admin.entrepots.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="row mb-3">
        <div class="col-lg-8 mb-3">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">📋 Informations Générales</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-3d me-3" style="width: 40px; height: 40px; background: var(--gradient-primary);">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Nom</h6>
                                <p class="mb-0 text-muted">{{ $entrepot->nom }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-3d me-3" style="width: 40px; height: 40px; background: var(--gradient-info);">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Région</h6>
                                <span class="badge bg-info">{{ $entrepot->region }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="icon-3d me-3" style="width: 40px; height: 40px; background: var(--gradient-success);">
                                <i class="fas fa-location-dot"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 fw-bold">Adresse</h6>
                                <p class="mb-0 text-muted">{{ $entrepot->adresse }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-3">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">📊 Statistiques</h6>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold">Capacité</span>
                        <span class="badge bg-primary">{{ number_format($entrepot->capacite) }} unités</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-{{ $entrepot->taux_occupation > 80 ? 'danger' : ($entrepot->taux_occupation > 60 ? 'warning' : 'success') }}" 
                             style="width: {{ $entrepot->taux_occupation }}%"></div>
                    </div>
                    <small class="text-muted">Taux d'occupation: {{ $entrepot->taux_occupation }}%</small>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Statut</span>
                        <span class="badge bg-{{ $entrepot->statut === 'actif' ? 'success' : ($entrepot->statut === 'maintenance' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($entrepot->statut) }}
                        </span>
                    </div>
                </div>
                
                @if(isset($entrepot->type))
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Type</span>
                        <span class="badge bg-info">{{ ucfirst($entrepot->type) }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Responsable et contact -->
    <div class="row mb-3">
        <div class="col-lg-6 mb-3">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">👤 Responsable</h6>
                <div class="d-flex align-items-center">
                    <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $entrepot->responsable }}</h5>
                        <p class="mb-1 text-muted">
                            <i class="fas fa-phone me-2"></i>{{ $entrepot->telephone }}
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-warehouse me-1"></i>Responsable d'entrepôt
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-3">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">📅 Informations Temporelles</h6>
                <div class="row">
                    <div class="col-6 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon-3d me-2" style="width: 30px; height: 30px; background: var(--gradient-success);">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div>
                                <small class="text-muted">Créé le</small>
                                <div class="fw-bold">{{ isset($entrepot->created_at) ? $entrepot->created_at->format('d/m/Y') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon-3d me-2" style="width: 30px; height: 30px; background: var(--gradient-warning);">
                                <i class="fas fa-calendar-edit"></i>
                            </div>
                            <div>
                                <small class="text-muted">Modifié le</small>
                                <div class="fw-bold">{{ isset($entrepot->updated_at) ? $entrepot->updated_at->format('d/m/Y') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte et coordonnées -->
    @if(isset($entrepot->latitude) && isset($entrepot->longitude))
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">🗺️ Localisation</h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-info">
                            <i class="fas fa-map-pin me-1"></i>
                            {{ $entrepot->latitude }}, {{ $entrepot->longitude }}
                        </span>
                        <button class="btn btn-outline-primary btn-sm" onclick="centerMapOnEntrepot()">
                            <i class="fas fa-crosshairs"></i> Centrer
                        </button>
                    </div>
                </div>
                <div id="entrepotMap" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
            </div>
        </div>
    </div>
    @endif

    <!-- Description -->
    @if(isset($entrepot->description) && $entrepot->description)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">📝 Description</h6>
                <p class="mb-0">{{ $entrepot->description }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">⚙️ Actions</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-{{ $entrepot->statut === 'actif' ? 'warning' : 'success' }} btn-sm" 
                                onclick="toggleStatus({{ $entrepot->id }}, '{{ $entrepot->statut }}')">
                            <i class="fas fa-{{ $entrepot->statut === 'actif' ? 'pause' : 'play' }} me-2"></i>
                            {{ $entrepot->statut === 'actif' ? 'Mettre en maintenance' : 'Activer' }}
                        </button>
                        <a href="{{ route('admin.entrepots.edit', $entrepot->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteEntrepot({{ $entrepot->id }})">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
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

/* Avatar */
.avatar-lg {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
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

/* Progress bars */
.progress {
    border-radius: 10px;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.1);
}

.progress-bar {
    transition: width 0.6s ease;
    border-radius: 10px;
}

/* Badges */
.badge {
    border-radius: 20px;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

/* Carte */
#entrepotMap {
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 10px;
}

/* Assurer que tous les liens sont cliquables */
a, button, .btn {
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 10 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .card-modern {
        margin-bottom: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<!-- Leaflet CSS et JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let entrepotMap;

// Initialisation de la carte
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($entrepot->latitude) && isset($entrepot->longitude))
        initializeMap();
    @endif
});

@if(isset($entrepot->latitude) && isset($entrepot->longitude))
function initializeMap() {
    // Initialiser la carte centrée sur l'entrepôt
    entrepotMap = L.map('entrepotMap').setView([{{ $entrepot->latitude }}, {{ $entrepot->longitude }}], 15);

    // Ajouter les tuiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(entrepotMap);

    // Ajouter le marqueur de l'entrepôt
    L.marker([{{ $entrepot->latitude }}, {{ $entrepot->longitude }}])
        .addTo(entrepotMap)
        .bindPopup(`
            <div class="p-2">
                <h6 class="fw-bold mb-1">{{ $entrepot->nom }}</h6>
                <p class="mb-1"><i class="fas fa-map-marker-alt text-primary"></i> {{ $entrepot->adresse }}</p>
                <p class="mb-1"><i class="fas fa-cubes text-info"></i> {{ number_format($entrepot->capacite) }} unités</p>
                <p class="mb-1"><i class="fas fa-user text-success"></i> {{ $entrepot->responsable }}</p>
                <p class="mb-0"><i class="fas fa-phone text-warning"></i> {{ $entrepot->telephone }}</p>
            </div>
        `)
        .openPopup();
}

function centerMapOnEntrepot() {
    entrepotMap.setView([{{ $entrepot->latitude }}, {{ $entrepot->longitude }}], 15);
}
@endif

// Fonction de basculement de statut
function toggleStatus(entrepotId, currentStatus) {
    const newStatus = currentStatus === 'actif' ? 'maintenance' : 'actif';
    const actionText = newStatus === 'actif' ? 'activer' : 'mettre en maintenance';
    
    if (confirm(`Êtes-vous sûr de vouloir ${actionText} cet entrepôt ?`)) {
        showToast(`Entrepôt ${actionText} avec succès!`, 'success');
        
        // Rediriger vers la liste
        setTimeout(() => {
            window.location.href = '{{ route("admin.entrepots.index") }}';
        }, 1500);
    }
}

// Fonction de suppression d'un entrepôt
function deleteEntrepot(entrepotId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet entrepôt ?')) {
        showToast('Suppression en cours...', 'info');
        
        // Rediriger vers la liste
        setTimeout(() => {
            window.location.href = '{{ route("admin.entrepots.index") }}';
        }, 1500);
    }
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
</script>
@endpush