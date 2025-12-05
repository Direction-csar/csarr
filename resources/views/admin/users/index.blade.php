@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid px-3">
    <!-- Header moderne -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">👥 Gestion des Utilisateurs</h1>
                        <p class="text-muted mb-0 small">Administrer les utilisateurs du système CSAR</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshUsers()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="exportUsers()">
                            <i class="fas fa-download me-1"></i>Exporter
                        </button>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-user-plus me-1"></i>Nouvel Utilisateur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Statistiques modernes avec icônes 3D -->
    <div class="row mb-2">
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-primary); width: 45px; height: 45px;">
                        <i class="fas fa-users" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-primary" id="total-users">{{ $stats['total'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">👥 Total Utilisateurs</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-success); width: 45px; height: 45px;">
                        <i class="fas fa-user-check" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-success" id="active-users">{{ $stats['active'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">✅ Utilisateurs Actifs</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-warning); width: 45px; height: 45px;">
                        <i class="fas fa-user-times" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-warning" id="inactive-users">{{ $stats['inactive'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">❌ Utilisateurs Inactifs</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-danger); width: 45px; height: 45px;">
                        <i class="fas fa-user-shield" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-danger" id="admin-users">{{ $stats['admin'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">🛡️ Administrateurs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et indicateurs visuels -->
    <div class="row mb-2">
        <div class="col-lg-6 mb-2">
            <div class="card-modern p-2">
                <h6 class="mb-1 fw-bold">📊 Répartition par Rôle</h6>
                <div class="chart-container" style="position: relative; height: 180px;">
                    <canvas id="rolesChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-2">
            <div class="card-modern p-2">
                <h6 class="mb-1 fw-bold">📈 Évolution des Inscriptions (7j)</h6>
                <div class="chart-container" style="position: relative; height: 180px;">
                    <canvas id="registrationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche modernes -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-2">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-6 mb-2">
                        <label class="form-label small fw-bold">🔍 Recherche</label>
                        <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Rechercher un utilisateur...">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-2">
                        <label class="form-label small fw-bold">👤 Rôle</label>
                        <select class="form-select form-select-sm" id="roleFilter">
                            <option value="">Tous les rôles</option>
                            <option value="admin">Administrateur</option>
                            <option value="dg">Directeur Général</option>
                            <option value="responsable">Responsable</option>
                            <option value="agent">Agent</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-2">
                        <label class="form-label small fw-bold">📊 Statut</label>
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">Tous les statuts</option>
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-2">
                        <label class="form-label small fw-bold">📅 Date</label>
                        <select class="form-select form-select-sm" id="dateFilter">
                            <option value="">Toutes les dates</option>
                            <option value="today">Aujourd'hui</option>
                            <option value="week">Cette semaine</option>
                            <option value="month">Ce mois</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-2">
                        <label class="form-label small fw-bold">&nbsp;</label>
                        <div class="d-flex gap-1">
                            <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Effacer
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="applyFilters()">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des utilisateurs moderne -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 fw-bold">👥 Liste des Utilisateurs</h6>
                    <div class="d-flex gap-1">
                        <button class="btn btn-outline-primary btn-sm" onclick="selectAll()">
                            <i class="fas fa-check-square"></i> Tout sélectionner
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="bulkAction('activate')">
                            <i class="fas fa-user-check"></i> Activer
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="bulkAction('deactivate')">
                            <i class="fas fa-user-times"></i> Désactiver
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="bulkAction('delete')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                                </th>
                                <th>Utilisateur</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Dernière connexion</th>
                                <th>Inscription</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            @forelse($users as $user)
                                <tr data-id="{{ $user->id }}" class="user-row">
                                    <td>
                                        <input type="checkbox" class="form-check-input user-checkbox" value="{{ $user->id }}">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                            </div>
                                            <div>
                                                <strong class="text-primary">{{ $user->name }}</strong>
                                                <br><small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'dg' ? 'warning' : ($user->role === 'responsable' ? 'info' : 'success')) }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->diffForHumans() : 'Jamais' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</small>
                                        <br><small class="text-muted">{{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-primary" title="Voir" onclick="showUser({{ $user->id }})">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-warning" title="Modifier" onclick="editUser({{ $user->id }})">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" 
                                                    onclick="toggleStatus({{ $user->id }}, {{ $user->is_active ? 'true' : 'false' }})" 
                                                    title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'user-times' : 'user-check' }}"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="resetPassword({{ $user->id }})" title="Réinitialiser mot de passe">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteUser({{ $user->id }})" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <h5>Aucun utilisateur trouvé</h5>
                                            <p>Il n'y a actuellement aucun utilisateur dans le système.</p>
                                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                                <i class="fas fa-user-plus me-2"></i>Créer un utilisateur
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination moderne -->
                @if($users->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                    </div>
                    <div>
                        {{ $users->links() }}
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
  /* Optimisation de l'espace pour les utilisateurs */
  .container-fluid { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
  
  /* Cards plus compactes */
  .card-modern { margin-bottom: 0.5rem !important; }
  
  /* Graphiques optimisés */
  .chart-container { position: relative; height: 180px; }
  .chart-container canvas { width: 100% !important; height: 100% !important; }
  
  /* Réduction des marges */
  .row { margin-bottom: 0.5rem !important; }
  .mb-2 { margin-bottom: 0.5rem !important; }
  
  /* Icônes plus petites */
  .icon-3d { width: 45px !important; height: 45px !important; }
  .icon-3d i { font-size: 18px !important; }
  
  /* Textes plus compacts */
  .h4 { font-size: 1.25rem !important; }
  .h6 { font-size: 0.9rem !important; }
  
  /* Boutons plus petits et cliquables */
  .btn-sm { 
    padding: 0.25rem 0.5rem !important; 
    font-size: 0.8rem !important; 
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 10 !important;
  }
  
  /* Tableau moderne */
  .table {
    margin-bottom: 0 !important;
  }
  
  .table th {
    border-top: none !important;
    font-weight: 600 !important;
    font-size: 0.8rem !important;
    color: #6c757d !important;
  }
  
  .table td {
    vertical-align: middle !important;
    font-size: 0.85rem !important;
  }
  
  .user-row:hover {
    background-color: rgba(102, 126, 234, 0.05) !important;
    transform: translateY(-1px);
    transition: all 0.2s ease;
  }
  
  /* Avatar moderne */
  .avatar-sm {
    width: 32px !important;
    height: 32px !important;
  }
  
  /* Badges modernes */
  .badge {
    font-size: 0.7rem !important;
    padding: 0.35em 0.65em !important;
  }
  
  /* Formulaires compacts */
  .form-control-sm, .form-select-sm {
    font-size: 0.8rem !important;
    padding: 0.25rem 0.5rem !important;
  }
  
  /* Animations de chargement */
  .loading {
    opacity: 0.6;
    pointer-events: none;
  }
  
  .loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  
  /* Effets de survol pour les boutons d'action */
  .btn-group .btn {
    transition: all 0.2s ease;
  }
  
  .btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  }
  
  /* Animation pour l'ajout/suppression d'utilisateurs */
  .user-row.added {
    animation: slideInFromTop 0.5s ease;
  }
  
  .user-row.removed {
    animation: slideOutToRight 0.5s ease;
  }
  
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
</style>
@endpush

@push('scripts')
<script>
// Graphique des rôles
const rolesCtx = document.getElementById('rolesChart');
if (rolesCtx) {
    const rolesChart = new Chart(rolesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Administrateurs', 'DG', 'DRH', 'Entrepôt', 'Agents'],
            datasets: [{
                data: [
                    {{ $stats['admin'] ?? 0 }},
                    {{ $stats['dg'] ?? 0 }},
                    {{ $stats['drh'] ?? 0 }},
                    {{ $stats['entrepot'] ?? 0 }},
                    {{ $stats['agent'] ?? 0 }}
                ],
                backgroundColor: [
                    '#ff6b6b',
                    '#ffd43b',
                    '#74c0fc',
                    '#51cf66'
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
                        padding: 15,
                        usePointStyle: true,
                        boxWidth: 12
                    }
                }
            }
        }
    });
}

// Graphique des inscriptions
const registrationsCtx = document.getElementById('registrationsChart');
if (registrationsCtx) {
    const registrationsChart = new Chart(registrationsCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Nouvelles inscriptions',
                data: {!! json_encode(array_values($chartData['evolution'] ?? [])) !!},
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
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
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Variables globales
let currentFilters = {};
let selectedUsers = [];

// Fonction d'actualisation
function refreshUsers() {
    const refreshBtn = document.querySelector('button[onclick="refreshUsers()"]');
    const icon = refreshBtn.querySelector('i');
    
    // Animation de rotation
    icon.style.animation = 'spin 1s linear infinite';
    
    // Simuler le rechargement
    setTimeout(() => {
        // Arrêter l'animation
        icon.style.animation = '';
        
        // Mettre à jour les statistiques
        updateStats();
        
        // Actualiser les graphiques
        if (typeof rolesChart !== 'undefined') rolesChart.update();
        if (typeof registrationsChart !== 'undefined') registrationsChart.update();
        
        // Afficher un message de succès
        showToast('Utilisateurs actualisés avec succès!', 'success');
    }, 1000);
}

// Fonction d'export
function exportUsers() {
    showToast('Export en cours...', 'info');
    
    // Récupérer les filtres actuels
    const search = document.querySelector('input[name="search"]')?.value || '';
    const role = document.querySelector('select[name="role"]')?.value || '';
    const status = document.querySelector('select[name="status"]')?.value || '';
    const dateFrom = document.querySelector('input[name="date_from"]')?.value || '';
    const dateTo = document.querySelector('input[name="date_to"]')?.value || '';
    
    // Construire l'URL avec les paramètres
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (role) params.append('role', role);
    if (status) params.append('status', status);
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    
    const exportUrl = '{{ route("admin.users.export") }}' + (params.toString() ? '?' + params.toString() : '');
    
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

// Fonction de mise à jour des statistiques
function updateStats() {
    // Animation des compteurs
    animateValue('total-users', 0, {{ $stats['total'] ?? 0 }}, 1000);
    animateValue('active-users', 0, {{ $stats['active'] ?? 0 }}, 1000);
    animateValue('inactive-users', 0, {{ $stats['inactive'] ?? 0 }}, 1000);
    animateValue('admin-users', 0, {{ $stats['admin'] ?? 0 }}, 1000);
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
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    const date = document.getElementById('dateFilter').value;
    
    currentFilters = { search, role, status, date };
    
    // Appliquer les filtres (simulation)
    filterTable();
    
    showToast('Filtres appliqués!', 'success');
}

// Fonction de filtrage du tableau
function filterTable() {
    const rows = document.querySelectorAll('.user-row');
    const search = currentFilters.search.toLowerCase();
    const role = currentFilters.role;
    const status = currentFilters.status;
    
    rows.forEach(row => {
        let show = true;
        
        // Filtre de recherche
        if (search) {
            const text = row.textContent.toLowerCase();
            if (!text.includes(search)) {
                show = false;
            }
        }
        
        // Filtre de rôle
        if (role) {
            const roleBadge = row.querySelector('.badge');
            if (roleBadge && !roleBadge.textContent.toLowerCase().includes(role)) {
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
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    
    currentFilters = {};
    
    // Afficher toutes les lignes
    const rows = document.querySelectorAll('.user-row');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    showToast('Filtres effacés!', 'info');
}

// Fonction de sélection
function selectAll() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateSelectedCount();
}

// Fonction de mise à jour du compteur de sélection
function updateSelectedCount() {
    const checked = document.querySelectorAll('.user-checkbox:checked');
    selectedUsers = Array.from(checked).map(cb => cb.value);
}

// Fonction d'action en lot
function bulkAction(action) {
    if (selectedUsers.length === 0) {
        showToast('Veuillez sélectionner au moins un utilisateur', 'warning');
        return;
    }
    
    const actionText = {
        'activate': 'activer',
        'deactivate': 'désactiver',
        'delete': 'supprimer'
    };
    
    if (confirm(`Êtes-vous sûr de vouloir ${actionText[action]} ${selectedUsers.length} utilisateur(s) ?`)) {
        showToast(`${actionText[action].charAt(0).toUpperCase() + actionText[action].slice(1)} en cours...`, 'info');
        
        // Simuler l'action
        setTimeout(() => {
            showToast(`${selectedUsers.length} utilisateur(s) ${actionText[action]} avec succès!`, 'success');
            updateStats();
        }, 1500);
    }
}

// Fonction de basculement de statut
function toggleStatus(userId, currentStatus) {
    const newStatus = currentStatus === true ? false : true;
    const actionText = newStatus === true ? 'activer' : 'désactiver';
    
    if (confirm(`Êtes-vous sûr de vouloir ${actionText} cet utilisateur ?`)) {
        showToast(`Utilisateur ${actionText} avec succès!`, 'success');
        
        // Mettre à jour l'interface
        const row = document.querySelector(`tr[data-id="${userId}"]`);
        if (row) {
            const statusBadge = row.querySelector('.badge:last-of-type');
            const toggleBtn = row.querySelector('button[onclick*="toggleStatus"]');
            
            if (statusBadge) {
                statusBadge.textContent = newStatus === true ? 'Actif' : 'Inactif';
                statusBadge.className = `badge bg-${newStatus === true ? 'success' : 'secondary'}`;
            }
            
            if (toggleBtn) {
                toggleBtn.className = `btn btn-outline-${newStatus === true ? 'warning' : 'success'}`;
                toggleBtn.title = newStatus === true ? 'Désactiver' : 'Activer';
                toggleBtn.innerHTML = `<i class="fas fa-${newStatus === true ? 'user-times' : 'user-check'}"></i>`;
                toggleBtn.setAttribute('onclick', `toggleStatus(${userId}, ${newStatus})`);
            }
        }
        
        updateStats();
    }
}

// Fonction de réinitialisation de mot de passe
function resetPassword(userId) {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ?')) {
        showToast('Mot de passe réinitialisé avec succès!', 'success');
    }
}

// Fonction de suppression d'un utilisateur
function deleteUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        showToast('Suppression en cours...', 'info');
        
        // Simuler la suppression avec animation
        const row = document.querySelector(`tr[data-id="${userId}"]`);
        if (row) {
            row.classList.add('removed');
            setTimeout(() => {
                row.remove();
                showToast('Utilisateur supprimé avec succès!', 'success');
                updateStats();
            }, 500);
        }
    }
}

// Fonction d'affichage d'un utilisateur
function showUser(userId) {
    showToast('Ouverture des détails de l\'utilisateur...', 'info');
}

// Fonction d'édition d'un utilisateur
function editUser(userId) {
    showToast('Ouverture de l\'édition de l\'utilisateur...', 'info');
}

// Fonction pour afficher des toasts
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Supprimer le toast après 3 secondes
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Écouter les changements de sélection
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('user-checkbox')) {
            updateSelectedCount();
        }
    });
    
    // Écouter la recherche en temps réel
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            currentFilters.search = this.value;
            filterTable();
        });
    }
    
    // Mise à jour automatique toutes les 30 secondes
    setInterval(() => {
        updateStats();
    }, 30000);
    
    // Animation CSS pour la rotation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush