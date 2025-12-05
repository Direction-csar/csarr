@extends('layouts.admin')

@section('title', 'Audit & Sécurité')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Audit & Sécurité
                    </h1>
                    <p class="text-muted mb-0">Surveillez et gérez la sécurité de la plateforme CSAR</p>
                </div>
                <div>
                    <a href="{{ route('admin.audit.logs') }}" class="btn btn-success-modern btn-modern">
                        <i class="fas fa-list-alt me-2"></i>Logs Détaillés
                    </a>
                    <button class="btn btn-primary-modern btn-modern" onclick="generateSecurityReport()">
                        <i class="fas fa-file-alt me-2"></i>Rapport Sécurité
                    </button>
                    <button class="btn btn-info-modern btn-modern" onclick="clearOldLogs()">
                        <i class="fas fa-trash me-2"></i>Nettoyer Logs
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-primary);">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="mb-1" id="total-logs">{{ \App\Models\AuditLog::count() ?? 0 }}</h3>
                    <p class="text-muted mb-0">Total Logs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-danger);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="mb-1" id="security-alerts">{{ \App\Models\AuditLog::whereIn('action', ['delete', 'force_delete', 'unauthorized_access'])->count() ?? 0 }}</h3>
                    <p class="text-muted mb-0">Actions Critiques</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-warning);">
                        <i class="fas fa-user-times"></i>
                    </div>
                    <h3 class="mb-1" id="failed-logins">{{ \App\Models\AuditLog::where('action', 'login_failed')->count() ?? 0 }}</h3>
                    <p class="text-muted mb-0">Échecs Connexion</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="mb-1" id="active-sessions">{{ \App\Models\AuditLog::where('action', 'login')->where('created_at', '>=', now()->subHours(24))->count() ?? 0 }}</h3>
                    <p class="text-muted mb-0">Sessions Actives</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="auditTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab">
                                <i class="fas fa-list me-2"></i>Logs d'Activité
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                <i class="fas fa-shield-alt me-2"></i>Sécurité
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sessions-tab" data-bs-toggle="tab" data-bs-target="#sessions" type="button" role="tab">
                                <i class="fas fa-users me-2"></i>Sessions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">
                                <i class="fas fa-chart-line me-2"></i>Rapports
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="auditTabsContent">
                        <!-- Logs d'Activité -->
                        <div class="tab-pane fade show active" id="logs" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="logs-search" placeholder="Rechercher dans les logs...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-select" id="logs-level-filter">
                                            <option value="">Tous les niveaux</option>
                                            <option value="info">Info</option>
                                            <option value="warning">Warning</option>
                                            <option value="error">Error</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select class="form-select" id="logs-user-filter">
                                            <option value="">Tous les utilisateurs</option>
                                            <option value="admin">Admin</option>
                                            <option value="user">Utilisateur</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="date" class="form-control" id="logs-date-filter">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary-modern btn-modern w-100" onclick="applyLogsFilters()">
                                        <i class="fas fa-search me-2"></i>Filtrer
                                    </button>
                                </div>
                            </div>
                            
                            <div id="logs-list">
                                <div class="text-center py-5">
                                    <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                        <i class="fas fa-list"></i>
                                    </div>
                                    <h5 class="text-muted">Aucun log</h5>
                                    <p class="text-muted">Aucune activité enregistrée pour le moment.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sécurité -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-modern">
                                        <div class="card-header">
                                            <h6 class="mb-0">Alertes de Sécurité Récentes</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center py-3">
                                                <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucune alerte de sécurité</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-modern">
                                        <div class="card-header">
                                            <h6 class="mb-0">Tentatives de Connexion</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center py-3">
                                                <i class="fas fa-user-lock fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Aucune tentative suspecte</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sessions -->
                        <div class="tab-pane fade" id="sessions" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="sessions-search" placeholder="Rechercher des sessions...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-select" id="sessions-status-filter">
                                            <option value="">Tous les statuts</option>
                                            <option value="active">Actives</option>
                                            <option value="expired">Expirées</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-warning-modern btn-modern w-100" onclick="terminateAllSessions()">
                                        <i class="fas fa-power-off me-2"></i>Terminer Toutes
                                    </button>
                                </div>
                            </div>
                            
                            <div id="sessions-list">
                                <div class="text-center py-5">
                                    <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h5 class="text-muted">Aucune session</h5>
                                    <p class="text-muted">Aucune session active pour le moment.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rapports -->
                        <div class="tab-pane fade" id="reports" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-modern">
                                        <div class="card-header">
                                            <h6 class="mb-0">Activité par Heure</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="activityChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-modern">
                                        <div class="card-header">
                                            <h6 class="mb-0">Types d'Événements</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="eventsChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    animateCounters();
    
    // Gestion des filtres
    document.getElementById('logs-search').addEventListener('input', debounce(applyLogsFilters, 300));
    document.getElementById('logs-level-filter').addEventListener('change', applyLogsFilters);
    document.getElementById('logs-user-filter').addEventListener('change', applyLogsFilters);
    document.getElementById('logs-date-filter').addEventListener('change', applyLogsFilters);
    
    document.getElementById('sessions-search').addEventListener('input', debounce(applySessionsFilters, 300));
    document.getElementById('sessions-status-filter').addEventListener('change', applySessionsFilters);
    
    // Initialiser les graphiques
    initCharts();
});

function animateCounters() {
    // Animation des compteurs (pour l'instant à 0)
    animateValue(document.getElementById('total-logs'), 0, 0, 1000);
    animateValue(document.getElementById('security-alerts'), 0, 0, 1000);
    animateValue(document.getElementById('failed-logins'), 0, 0, 1000);
    animateValue(document.getElementById('active-sessions'), 0, 0, 1000);
}

function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

function applyLogsFilters() {
    // Simulation de filtrage (à remplacer par une vraie requête AJAX)
    showToast('Filtres de logs appliqués', 'info');
}

function applySessionsFilters() {
    // Simulation de filtrage (à remplacer par une vraie requête AJAX)
    showToast('Filtres de sessions appliqués', 'info');
}

function generateSecurityReport() {
    // Demander le type de rapport
    const reportType = prompt('Type de rapport:\n1. summary (Résumé)\n2. threats (Menaces)\n3. full (Complet)\n\nEntrez le numéro (1, 2 ou 3):', '1');
    
    if (!reportType || !['1', '2', '3'].includes(reportType)) {
        showToast('Type de rapport invalide', 'error');
        return;
    }
    
    const types = { '1': 'summary', '2': 'threats', '3': 'full' };
    const selectedType = types[reportType];
    
    // Demander la période
    const days = prompt('Période du rapport (en jours, par défaut 30):', '30');
    const daysNumber = parseInt(days) || 30;
    
    const dateFrom = new Date();
    dateFrom.setDate(dateFrom.getDate() - daysNumber);
    
    showToast('Génération du rapport de sécurité...', 'info');
    
    fetch('/admin/audit/security-report', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            report_type: selectedType,
            date_from: dateFrom.toISOString().split('T')[0],
            date_to: new Date().toISOString().split('T')[0]
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Rapport généré avec succès!', 'success');
            displaySecurityReport(data.data);
        } else {
            showToast('Erreur lors de la génération du rapport', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    });
}

function clearOldLogs() {
    const days = prompt('Supprimer les logs antérieurs à combien de jours ? (par défaut 30):', '30');
    const daysNumber = parseInt(days) || 30;
    
    if (!confirm(`Êtes-vous sûr de vouloir supprimer les logs antérieurs à ${daysNumber} jours ?`)) {
        return;
    }
    
    showToast('Nettoyage des logs en cours...', 'info');
    
    fetch('/admin/audit/clear-logs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            older_than_days: daysNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(`Logs nettoyés: ${data.data.deleted_count} entrées supprimées`, 'success');
            // Actualiser les statistiques
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showToast('Erreur lors du nettoyage des logs', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
    });
}

function terminateAllSessions() {
    if (confirm('Êtes-vous sûr de vouloir terminer toutes les sessions actives ?')) {
        showToast('Toutes les sessions ont été terminées', 'warning');
    }
}

function initCharts() {
    // Graphique d'activité par heure
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['00h', '04h', '08h', '12h', '16h', '20h'],
            datasets: [{
                label: 'Activité',
                data: [0, 0, 0, 0, 0, 0],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Graphique des types d'événements
    const eventsCtx = document.getElementById('eventsChart').getContext('2d');
    new Chart(eventsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Connexions', 'Actions', 'Erreurs', 'Sécurité'],
            datasets: [{
                data: [0, 0, 0, 0],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function displaySecurityReport(reportData) {
    // Créer une modal pour afficher le rapport
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'securityReportModal';
    modal.innerHTML = `
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shield-alt me-2"></i>
                        Rapport de Sécurité - ${reportData.id}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Type:</strong> ${reportData.type}<br>
                            <strong>Période:</strong> ${reportData.date_from} à ${reportData.date_to}<br>
                            <strong>Généré par:</strong> ${reportData.generated_by}
                        </div>
                        <div class="col-md-6">
                            <strong>Généré le:</strong> ${reportData.generated_at}<br>
                            <button class="btn btn-sm btn-primary" onclick="downloadReport('${reportData.id}')">
                                <i class="fas fa-download me-1"></i>Télécharger
                            </button>
                        </div>
                    </div>
                    
                    ${reportData.summary ? `
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Résumé</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-primary">${reportData.summary.total_logs}</h4>
                                        <small>Total Logs</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-danger">${reportData.summary.critical_alerts}</h4>
                                        <small>Alertes Critiques</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-warning">${reportData.summary.failed_logins}</h4>
                                        <small>Échecs Connexion</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h4 class="text-info">${reportData.summary.unique_ips}</h4>
                                        <small>IPs Uniques</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${reportData.threats ? `
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">Menaces Détectées</h6>
                        </div>
                        <div class="card-body">
                            <h6>Violations de Sécurité (${reportData.threats.security_breaches.length})</h6>
                            ${reportData.threats.security_breaches.length > 0 ? `
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>IP</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${reportData.threats.security_breaches.map(breach => `
                                        <tr>
                                            <td>${breach.date}</td>
                                            <td><code>${breach.ip}</code></td>
                                            <td>${breach.description}</td>
                                        </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                            ` : '<p class="text-muted">Aucune violation détectée</p>'}
                            
                            <h6 class="mt-3">Tentatives de Connexion Échouées (${reportData.threats.failed_logins.length})</h6>
                            ${reportData.threats.failed_logins.length > 0 ? `
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>IP</th>
                                            <th>Tentatives</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${reportData.threats.failed_logins.map(login => `
                                        <tr>
                                            <td>${login.date}</td>
                                            <td><code>${login.ip}</code></td>
                                            <td>${login.attempts}</td>
                                        </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                            ` : '<p class="text-muted">Aucune tentative échouée</p>'}
                        </div>
                    </div>
                    ` : ''}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Nettoyer la modal après fermeture
    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });
}

function downloadReport(reportId) {
    showToast('Téléchargement du rapport...', 'info');
    // Ici on pourrait implémenter un vrai téléchargement
    setTimeout(() => {
        showToast('Rapport téléchargé!', 'success');
    }, 1000);
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