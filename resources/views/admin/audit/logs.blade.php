@extends('layouts.admin')

@section('title', 'Logs d\'Audit Détaillés')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-list-alt me-2"></i>Logs d'Audit Détaillés
                    </h1>
                    <p class="text-muted mb-0">Traçage complet des activités et adresses IP</p>
                </div>
                <div>
                    <button class="btn btn-primary-modern btn-modern" onclick="exportLogs()">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                    <button class="btn btn-info-modern btn-modern" onclick="refreshLogs()">
                        <i class="fas fa-sync me-2"></i>Actualiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">
                    <form id="filters-form" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="IP, action, description...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Niveau</label>
                            <select class="form-select" id="level" name="level">
                                <option value="">Tous</option>
                                <option value="info">Info</option>
                                <option value="warning">Warning</option>
                                <option value="error">Error</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Action</label>
                            <select class="form-select" id="action" name="action">
                                <option value="">Toutes</option>
                                <option value="login">Connexion</option>
                                <option value="login_failed">Échec connexion</option>
                                <option value="logout">Déconnexion</option>
                                <option value="create">Création</option>
                                <option value="update">Modification</option>
                                <option value="delete">Suppression</option>
                                <option value="security_breach">Violation sécurité</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Utilisateur</label>
                            <select class="form-select" id="user" name="user">
                                <option value="">Tous</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary-modern btn-modern w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des logs -->
    <div class="row">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-header">
                    <h6 class="mb-0">Logs d'Activité</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="logs-table">
                            <thead>
                                <tr>
                                    <th>Date/Heure</th>
                                    <th>Niveau</th>
                                    <th>Action</th>
                                    <th>Utilisateur</th>
                                    <th>Adresse IP</th>
                                    <th>Localisation</th>
                                    <th>User Agent</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="logs-tbody">
                                <!-- Les logs seront chargés via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div id="logs-info"></div>
                        <nav id="logs-pagination"></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour détails -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="log-details-content">
                <!-- Contenu chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadLogs();
    
    // Gestion des filtres
    document.getElementById('filters-form').addEventListener('submit', function(e) {
        e.preventDefault();
        loadLogs();
    });
    
    // Actualisation automatique toutes les 30 secondes
    setInterval(loadLogs, 30000);
});

function loadLogs(page = 1) {
    const formData = new FormData(document.getElementById('filters-form'));
    formData.append('page', page);
    
    fetch('{{ route("admin.audit.logs.data") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayLogs(data.data);
            updatePagination(data.data);
        } else {
            showToast('Erreur lors du chargement des logs', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    });
}

function displayLogs(logs) {
    const tbody = document.getElementById('logs-tbody');
    tbody.innerHTML = '';
    
    if (logs.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun log trouvé</p>
                </td>
            </tr>
        `;
        return;
    }
    
    logs.data.forEach(log => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <small class="text-muted">${formatDateTime(log.created_at)}</small>
            </td>
            <td>
                <span class="badge bg-${getLevelColor(log.level)}">${log.level}</span>
            </td>
            <td>
                <span class="badge bg-${getActionColor(log.action)}">${log.action}</span>
            </td>
            <td>
                ${log.user ? log.user.name : '<em>Anonyme</em>'}
            </td>
            <td>
                <code>${log.ip_address || 'N/A'}</code>
            </td>
            <td>
                <small class="text-muted">${getLocationFromIP(log.ip_address)}</small>
            </td>
            <td>
                <small class="text-muted" title="${log.user_agent || 'N/A'}">
                    ${truncateText(log.user_agent || 'N/A', 30)}
                </small>
            </td>
            <td>
                <small>${log.description || 'N/A'}</small>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="showLogDetails(${log.id})">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updatePagination(logs) {
    const info = document.getElementById('logs-info');
    const pagination = document.getElementById('logs-pagination');
    
    // Info
    const start = (logs.current_page - 1) * logs.per_page + 1;
    const end = Math.min(logs.current_page * logs.per_page, logs.total);
    info.innerHTML = `Affichage de ${start} à ${end} sur ${logs.total} entrées`;
    
    // Pagination
    let paginationHTML = '<ul class="pagination pagination-sm mb-0">';
    
    // Page précédente
    if (logs.current_page > 1) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadLogs(${logs.current_page - 1})">Précédent</a></li>`;
    }
    
    // Pages
    for (let i = 1; i <= logs.last_page; i++) {
        if (i === logs.current_page) {
            paginationHTML += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
        } else {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadLogs(${i})">${i}</a></li>`;
        }
    }
    
    // Page suivante
    if (logs.current_page < logs.last_page) {
        paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="loadLogs(${logs.current_page + 1})">Suivant</a></li>`;
    }
    
    paginationHTML += '</ul>';
    pagination.innerHTML = paginationHTML;
}

function showLogDetails(logId) {
    fetch(`/admin/audit/logs/${logId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const log = data.data;
            const content = document.getElementById('log-details-content');
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations Générales</h6>
                        <table class="table table-sm">
                            <tr><td><strong>ID:</strong></td><td>${log.id}</td></tr>
                            <tr><td><strong>Action:</strong></td><td><span class="badge bg-${getActionColor(log.action)}">${log.action}</span></td></tr>
                            <tr><td><strong>Niveau:</strong></td><td><span class="badge bg-${getLevelColor(log.level)}">${log.level}</span></td></tr>
                            <tr><td><strong>Statut:</strong></td><td><span class="badge bg-${getStatusColor(log.status)}">${log.status}</span></td></tr>
                            <tr><td><strong>Date:</strong></td><td>${formatDateTime(log.created_at)}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Informations de Traçage</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Utilisateur:</strong></td><td>${log.user ? log.user.name : 'Anonyme'}</td></tr>
                            <tr><td><strong>Adresse IP:</strong></td><td><code>${log.ip_address || 'N/A'}</code></td></tr>
                            <tr><td><strong>Localisation:</strong></td><td>${getLocationFromIP(log.ip_address)}</td></tr>
                            <tr><td><strong>User Agent:</strong></td><td><small>${log.user_agent || 'N/A'}</small></td></tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Description</h6>
                        <p class="text-muted">${log.description || 'Aucune description'}</p>
                    </div>
                </div>
                ${log.data ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Données Supplémentaires</h6>
                        <pre class="bg-light p-3 rounded"><code>${JSON.stringify(log.data, null, 2)}</code></pre>
                    </div>
                </div>
                ` : ''}
            `;
            
            const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
            modal.show();
        } else {
            showToast('Erreur lors du chargement des détails', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    });
}

function getLevelColor(level) {
    const colors = {
        'info': 'info',
        'warning': 'warning',
        'error': 'danger',
        'critical': 'danger'
    };
    return colors[level] || 'secondary';
}

function getActionColor(action) {
    const colors = {
        'login': 'success',
        'login_failed': 'danger',
        'logout': 'warning',
        'create': 'primary',
        'update': 'info',
        'delete': 'danger',
        'security_breach': 'danger'
    };
    return colors[action] || 'secondary';
}

function getStatusColor(status) {
    const colors = {
        'success': 'success',
        'failed': 'danger',
        'pending': 'warning'
    };
    return colors[status] || 'secondary';
}

function getLocationFromIP(ip) {
    // Simulation de géolocalisation
    if (!ip || ip === '127.0.0.1' || ip === '::1') {
        return 'Local';
    }
    return 'Dakar, Sénégal'; // À remplacer par une vraie API de géolocalisation
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('fr-FR');
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

function exportLogs() {
    showToast('Export en cours...', 'info');
    // Implémentation de l'export
}

function refreshLogs() {
    loadLogs();
    showToast('Logs actualisés', 'success');
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
        toast.remove();
    }, 5000);
}
</script>
@endpush
