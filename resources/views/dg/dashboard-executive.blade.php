@extends('layouts.dg-modern')

@section('title', 'Tableau de Bord Exécutif')
@section('page-title', 'Direction Générale - Vue Stratégique')

@section('content')
<div class="container-fluid">
    <!-- Header compact pour DG -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Vue Stratégique CSAR
                        </h1>
                        <p class="text-muted mb-0 small">
                            Tableau de bord exécutif - {{ now()->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary-modern btn-modern btn-sm" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                        <button class="btn btn-success-modern btn-modern btn-sm" onclick="generateExecutiveReport()">
                            <i class="fas fa-file-pdf me-1"></i>Rapport DG
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques KPI essentielles pour DG -->
    <div class="row mb-3">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-primary); width: 50px; height: 50px;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number" data-stat="total_requests">{{ $stats['total_requests'] ?? 0 }}</h3>
                        <p class="stats-label">📋 Total Demandes</p>
                        <small class="text-muted">Toutes catégories</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-warning); width: 50px; height: 50px;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number" data-stat="pending_requests">{{ $stats['pending_requests'] ?? 0 }}</h3>
                        <p class="stats-label">⏳ En Attente</p>
                        <small class="text-warning">Nécessite attention</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-success); width: 50px; height: 50px;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number" data-stat="approved_requests">{{ $stats['approved_requests'] ?? 0 }}</h3>
                        <p class="stats-label">✅ Traitées</p>
                        <small class="text-success">Approuvées</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-info); width: 50px; height: 50px;">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number" data-stat="total_warehouses">{{ $stats['total_warehouses'] ?? 0 }}</h3>
                        <p class="stats-label">🏢 Entrepôts</p>
                        <small class="text-info">Opérationnels</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique compact et métriques -->
    <div class="row mb-3">
        <!-- Graphique compact (hauteur fixe) -->
        <div class="col-xl-8 col-lg-12 col-md-12 mb-3">
            <div class="card-modern p-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                    <h6 class="mb-2 mb-md-0 fw-bold">
                        <i class="fas fa-chart-area me-2 text-primary"></i>
                        Tendance des Demandes (7 jours)
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary btn-sm active" onclick="updateChart('7d')">7j</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="updateChart('30d')">30j</button>
                    </div>
                </div>
                <!-- Graphique optimisé avec hauteur responsive -->
                <div style="height: 180px; position: relative;">
                    <canvas id="requestsChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Métriques de performance compactes -->
        <div class="col-xl-4 col-lg-12 col-md-12 mb-3">
            <div class="card-modern p-3">
                <h6 class="mb-3 fw-bold">
                    <i class="fas fa-tachometer-alt me-2 text-success"></i>
                    Performance
                </h6>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Taux de Réponse</span>
                        <span class="small fw-bold">{{ $stats['efficiency_rate'] ?? '0%' }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Délai Moyen</span>
                        <span class="small fw-bold">{{ $stats['response_time'] ?? '0h' }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Satisfaction</span>
                        <span class="small fw-bold">{{ $stats['satisfaction_rate'] ?? '0/10' }}</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section principale : Vue d'ensemble et actions -->
    <div class="row">
        <!-- Vue d'ensemble des demandes -->
        <div class="col-xl-6 col-lg-12 col-md-12 mb-3">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-eye me-2 text-info"></i>
                        Vue d'Ensemble des Demandes
                    </h6>
                    <a href="{{ route('dg.demandes.index') }}" class="btn btn-primary-modern btn-modern btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i>Voir tout
                    </a>
                </div>
                
                @if(isset($recentRequests) && $recentRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Demandeur</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests->take(5) as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-3d me-2" style="width: 30px; height: 30px; background: var(--gradient-info);">
                                                <i class="fas fa-user" style="font-size: 12px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold small">{{ $request->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $request->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info small">{{ $request->type ?? 'Autre' }}</span>
                                    </td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning small">En attente</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success small">Approuvé</span>
                                        @else
                                            <span class="badge bg-secondary small">{{ $request->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="icon-3d mx-auto mb-2" style="width: 60px; height: 60px; background: var(--gradient-secondary);">
                            <i class="fas fa-inbox" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="text-muted">Aucune demande récente</h6>
                        <p class="text-muted small">Les nouvelles demandes apparaîtront ici</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Actions et alertes essentielles -->
        <div class="col-xl-6 col-lg-12 col-md-12 mb-3">
            <div class="card-modern p-3">
                <h6 class="mb-3 fw-bold">
                    <i class="fas fa-bell me-2 text-warning"></i>
                    Alertes & Actions
                </h6>
                
                <!-- Alertes critiques -->
                <div class="alert alert-warning d-flex align-items-center mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong class="small">Stock faible détecté</strong><br>
                        <small>Entrepôt Principal - Action requise</small>
                    </div>
                </div>
                
                <div class="alert alert-info d-flex align-items-center mb-2">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong class="small">Système opérationnel</strong><br>
                        <small>Tous les services fonctionnels</small>
                    </div>
                </div>
                
                <!-- Actions rapides pour DG -->
                <div class="mt-3">
                    <h6 class="small fw-bold mb-2">Actions Rapides</h6>
                    <div class="d-grid gap-1">
                    <a href="{{ route('dg.demandes.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-clipboard-list me-1"></i>Consulter Demandes
                    </a>
                        <a href="{{ route('dg.reports.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-chart-bar me-1"></i>Générer Rapport
                        </a>
                        <a href="{{ route('dg.warehouses.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-warehouse me-1"></i>Voir Entrepôts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte interactive compacte -->
    <div class="row">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-map-marked-alt me-2 text-primary"></i>
                        Carte des Entrepôts et Demandes
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary btn-sm" onclick="toggleMapLayer('warehouses')">
                            <i class="fas fa-warehouse me-1"></i>Entrepôts
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="toggleMapLayer('requests')">
                            <i class="fas fa-map-pin me-1"></i>Demandes
                        </button>
                    </div>
                </div>
                <!-- Carte avec hauteur fixe -->
                <div id="interactiveMap" style="height: 300px; border-radius: 8px; overflow: hidden;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Graphique optimisé pour DG
    const ctx = document.getElementById('requestsChart').getContext('2d');
    const requestsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Demandes CSAR',
                data: [8, 12, 6, 15, 9, 11, 7],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.15)',
                borderWidth: 3,
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: '#667eea',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 20,
                    grid: {
                        color: 'rgba(0,0,0,0.08)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        color: '#6c757d',
                        stepSize: 5
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        color: '#6c757d'
                    }
                }
            },
            elements: {
                line: {
                    borderJoinStyle: 'round',
                    borderCapStyle: 'round'
                }
            }
        }
    });

    // Carte interactive compacte
    const map = L.map('interactiveMap').setView([14.6928, -17.4467], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marqueurs d'entrepôts
    const warehouseIcon = L.divIcon({
        className: 'warehouse-marker',
        html: '<i class="fas fa-warehouse" style="color: #667eea; font-size: 16px;"></i>',
        iconSize: [25, 25]
    });

    // Ajouter des marqueurs d'exemple
    L.marker([14.6928, -17.4467], {icon: warehouseIcon})
        .addTo(map)
        .bindPopup('<b>Entrepôt Principal CSAR</b><br>Dakar, Sénégal');

    // Fonctions pour DG
    function refreshDashboard() {
        location.reload();
    }

    function generateExecutiveReport() {
        alert('Génération du rapport exécutif...');
    }

    function updateChart(period) {
        // Mise à jour des données selon la période
        let newData;
        if (period === '7d') {
            newData = [8, 12, 6, 15, 9, 11, 7];
        } else if (period === '30d') {
            newData = [45, 52, 38, 61, 47, 55, 42, 48, 39, 44, 51, 37, 43, 49, 35, 41, 46, 40, 45, 38, 42, 47, 36, 44, 50, 39, 43, 48, 37, 41];
        }
        
        requestsChart.data.datasets[0].data = newData;
        requestsChart.update('active');
    }

    function toggleMapLayer(layer) {
        console.log('Basculement de la couche:', layer);
    }

    // Mise à jour automatique des statistiques (moins fréquente pour DG)
    setInterval(function() {
        const stats = document.querySelectorAll('[data-stat]');
        stats.forEach(stat => {
            const currentValue = parseInt(stat.textContent);
            const newValue = currentValue + Math.floor(Math.random() * 2) - 1;
            stat.textContent = Math.max(0, newValue);
        });
    }, 60000); // Mise à jour toutes les minutes
</script>
@endsection
