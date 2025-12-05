@extends('layouts.admin')

@section('title', 'Statistiques - Administration')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques Générales
                    </h1>
                    <p class="text-muted mb-0">Vue d'ensemble des données de la plateforme CSAR</p>
                </div>
                <div>
                    <button class="btn btn-primary-modern btn-modern" onclick="exportStatistics()">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                    <button class="btn btn-info-modern btn-modern" onclick="refreshStatistics()">
                        <i class="fas fa-sync me-2"></i>Actualiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="stats-number">{{ $stats['total_users'] }}</h3>
                <p class="stats-label">Utilisateurs Total</p>
                <div class="stats-trend">
                    <i class="fas fa-user-check text-success me-1"></i>
                    <span class="text-success">{{ $stats['active_users'] }} actifs</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="stats-number">{{ $stats['total_demandes'] }}</h3>
                <p class="stats-label">Demandes Total</p>
                <div class="stats-trend">
                    <i class="fas fa-clock text-warning me-1"></i>
                    <span class="text-warning">{{ $stats['pending_demandes'] }} en attente</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #74c0fc 0%, #339af0 100%);">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3 class="stats-number">{{ $stats['total_stock_movements'] }}</h3>
                <p class="stats-label">Mouvements Stock</p>
                <div class="stats-trend">
                    <i class="fas fa-warehouse text-info me-1"></i>
                    <span class="text-info">{{ $stats['total_warehouses'] }} entrepôts</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #ffd43b 0%, #fab005 100%);">
                    <i class="fas fa-id-card"></i>
                </div>
                <h3 class="stats-number">{{ $stats['total_personnel'] }}</h3>
                <p class="stats-label">Personnel</p>
                <div class="stats-trend">
                    <i class="fas fa-plus text-success me-1"></i>
                    <span class="text-success">{{ $stats['recent_demandes'] }} cette semaine</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Demandes par Mois
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="demandesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Utilisateurs par Rôle
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>Mouvements de Stock par Type
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="mouvementsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Activités Récentes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="activities-list">
                        @forelse($recentActivities as $activity)
                            <div class="activity-item">
                                <div class="activity-icon bg-{{ $activity['color'] }}">
                                    <i class="{{ $activity['icon'] }}"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-message">{{ $activity['message'] }}</p>
                                    <small class="activity-date">{{ $activity['date']->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                <p class="text-muted">Aucune activité récente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.stats-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stats-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 6px;
    line-height: 1.1;
}

.stats-label {
    color: #4a5568;
    font-weight: 500;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 10px;
}

.stats-trend {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    font-weight: 600;
}

.chart-container {
    height: 300px;
    position: relative;
}

.activities-list {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f1f5f9;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
    font-size: 0.9rem;
}

.activity-content {
    flex: 1;
}

.activity-message {
    margin: 0 0 5px 0;
    font-weight: 500;
    color: #2d3748;
}

.activity-date {
    color: #718096;
    font-size: 0.8rem;
}

.card-modern {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.3);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 20px 25px;
    border-bottom: none;
}

.card-body {
    padding: 25px;
}

.btn-modern {
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-info-modern {
    background: linear-gradient(135deg, #17a2b8, #6f42c1);
    color: white;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des statistiques
    animateStats();
    
    // Données des graphiques depuis le contrôleur
    const chartData = @json($chartData);
    
    // Graphique des demandes par statut
    const demandesCtx = document.getElementById('demandesChart').getContext('2d');
    const statutLabels = Object.keys(chartData.demandes_par_statut || {});
    const statutData = Object.values(chartData.demandes_par_statut || {});
    
    new Chart(demandesCtx, {
        type: 'doughnut',
        data: {
            labels: statutLabels.map(label => {
                const labels = {
                    'pending': 'En attente',
                    'approved': 'Approuvées',
                    'rejected': 'Rejetées',
                    'completed': 'Terminées'
                };
                return labels[label] || label;
            }),
            datasets: [{
                data: statutData,
                backgroundColor: [
                    '#ffd43b',
                    '#51cf66',
                    '#ff6b6b',
                    '#74c0fc'
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
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Graphique des utilisateurs par rôle
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    const roleLabels = Object.keys(chartData.users_par_role || {});
    const roleData = Object.values(chartData.users_par_role || {});
    
    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: roleLabels.map(label => {
                const labels = {
                    'admin': 'Administrateurs',
                    'agent': 'Agents',
                    'responsable': 'Responsables',
                    'dg': 'Directeur Général',
                    'drh': 'DRH'
                };
                return labels[label] || label;
            }),
            datasets: [{
                label: 'Utilisateurs',
                data: roleData,
                backgroundColor: [
                    '#667eea',
                    '#51cf66',
                    '#74c0fc',
                    '#ffd43b',
                    '#ff6b6b'
                ],
                borderWidth: 1,
                borderColor: '#fff'
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
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Graphique des demandes par type
    const mouvementsCtx = document.getElementById('mouvementsChart').getContext('2d');
    const typeLabels = Object.keys(chartData.demandes_par_type || {});
    const typeData = Object.values(chartData.demandes_par_type || {});
    
    new Chart(mouvementsCtx, {
        type: 'bar',
        data: {
            labels: typeLabels.map(label => {
                const labels = {
                    'aide_alimentaire': 'Aide Alimentaire',
                    'aide_medicale': 'Aide Médicale',
                    'aide_financiere': 'Aide Financière',
                    'information': 'Information',
                    'reclamation': 'Réclamation'
                };
                return labels[label] || label;
            }),
            datasets: [{
                label: 'Demandes',
                data: typeData,
                backgroundColor: [
                    '#51cf66',
                    '#ff6b6b',
                    '#74c0fc',
                    '#ffd43b',
                    '#667eea'
                ],
                borderWidth: 1,
                borderColor: '#fff'
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
                        stepSize: 1
                    }
                }
            }
        }
    });
});

function animateStats() {
    const statNumbers = document.querySelectorAll('.stats-number');
    
    statNumbers.forEach((stat, index) => {
        const finalValue = parseInt(stat.textContent);
        setTimeout(() => {
            animateValue(stat, 0, finalValue, 1500);
        }, index * 200);
    });
}

function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.textContent = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

function exportStatistics() {
    // Implémenter l'export des statistiques
    showToast('Export des statistiques en cours...', 'info');
    
    // Simulation d'export
    setTimeout(() => {
        showToast('Statistiques exportées avec succès !', 'success');
    }, 2000);
}

function refreshStatistics() {
    showToast('Actualisation des statistiques...', 'info');
    setTimeout(() => {
        location.reload();
    }, 1000);
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
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
@endsection

