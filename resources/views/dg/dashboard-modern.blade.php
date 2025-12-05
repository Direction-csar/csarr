@extends('layouts.dg-modern')

@section('title', 'Tableau de Bord Exécutif')
@section('page-title', 'Tableau de Bord Direction Générale')

@section('content')
<div class="container-fluid">
    <!-- Header avec actions rapides -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2 text-dark fw-bold">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Tableau de Bord Exécutif
                        </h1>
                        <p class="text-muted mb-0">
                            Vue d'ensemble stratégique du système CSAR - {{ now()->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary-modern btn-modern" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt me-2"></i>Actualiser
                        </button>
                        <button class="btn btn-success-modern btn-modern" onclick="generateReport()">
                            <i class="fas fa-download me-2"></i>Rapport PDF
                        </button>
                        <button class="btn btn-info-modern btn-modern" onclick="exportData()">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques principales KPI -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-primary); width: 60px; height: 60px;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number" data-stat="total_users">{{ $stats['total_users'] ?? 0 }}</h3>
                        <p class="stats-label">👥 Utilisateurs Total</p>
                        <small class="text-success">
                            <i class="fas fa-arrow-up me-1"></i>+12% ce mois
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-warning); width: 60px; height: 60px;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number" data-stat="pending_requests">{{ $stats['pending_requests'] ?? 0 }}</h3>
                        <p class="stats-label">📋 Demandes en Attente</p>
                        <small class="text-warning">
                            <i class="fas fa-clock me-1"></i>Nécessite attention
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-info); width: 60px; height: 60px;">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number" data-stat="total_warehouses">{{ $stats['total_warehouses'] ?? 0 }}</h3>
                        <p class="stats-label">🏢 Entrepôts Actifs</p>
                        <small class="text-info">
                            <i class="fas fa-check-circle me-1"></i>Opérationnels
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-danger); width: 60px; height: 60px;">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number" data-stat="low_stock_items">{{ $stats['low_stock_items'] ?? 0 }}</h3>
                        <p class="stats-label">⚠️ Alertes Stock</p>
                        <small class="text-danger">
                            <i class="fas fa-arrow-down me-1"></i>Action requise
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="row mb-4">
        <!-- Graphique des demandes -->
        <div class="col-lg-8 mb-4">
            <div class="card-modern p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-area me-2 text-primary"></i>
                        Évolution des Demandes (7 derniers jours)
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary active" onclick="updateChart('7d')">7j</button>
                        <button class="btn btn-outline-primary" onclick="updateChart('30d')">30j</button>
                        <button class="btn btn-outline-primary" onclick="updateChart('90d')">90j</button>
                    </div>
                </div>
                <canvas id="requestsChart" height="100"></canvas>
            </div>
        </div>
        
        <!-- Métriques de performance -->
        <div class="col-lg-4 mb-4">
            <div class="card-modern p-4">
                <h5 class="mb-3 fw-bold">
                    <i class="fas fa-tachometer-alt me-2 text-success"></i>
                    Performance Opérationnelle
                </h5>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Efficacité</span>
                        <span class="small fw-bold">{{ $stats['efficiency_rate'] ?? '0%' }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Satisfaction</span>
                        <span class="small fw-bold">{{ $stats['satisfaction_rate'] ?? '0/10' }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small">Temps de Réponse</span>
                        <span class="small fw-bold">{{ $stats['response_time'] ?? '0h' }}</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" style="width: 0%"></div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <button class="btn btn-success-modern btn-modern btn-sm w-100" onclick="viewDetailedMetrics()">
                        <i class="fas fa-chart-bar me-2"></i>Métriques Détaillées
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Demandes récentes et actions rapides -->
    <div class="row mb-4">
        <!-- Demandes récentes -->
        <div class="col-lg-8 mb-4">
            <div class="card-modern p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-clock me-2 text-warning"></i>
                        Demandes Récentes
                    </h5>
                    <a href="{{ route('dg.demandes.index') }}" class="btn btn-primary-modern btn-modern btn-sm">
                        <i class="fas fa-eye me-2"></i>Voir toutes
                    </a>
                </div>
                
                @if(isset($recentRequests) && $recentRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Demandeur</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="icon-3d me-2" style="width: 35px; height: 35px; background: var(--gradient-info);">
                                                <i class="fas fa-user" style="font-size: 14px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $request->name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $request->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $request->type ?? 'Autre' }}</span>
                                    </td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success">Approuvé</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $request->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('dg.demandes.show', $request->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="icon-3d mx-auto mb-3" style="width: 80px; height: 80px; background: var(--gradient-secondary);">
                            <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="text-muted">Aucune demande récente</h5>
                        <p class="text-muted">Les nouvelles demandes apparaîtront ici</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="col-lg-4 mb-4">
            <div class="card-modern p-4">
                <h5 class="mb-3 fw-bold">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    Actions Rapides
                </h5>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('dg.demandes.index') }}" class="btn btn-primary-modern btn-modern">
                        <i class="fas fa-clipboard-list me-2"></i>Gérer les Demandes
                    </a>
                    <a href="{{ route('dg.warehouses.index') }}" class="btn btn-info-modern btn-modern">
                        <i class="fas fa-warehouse me-2"></i>Voir les Entrepôts
                    </a>
                    <a href="{{ route('dg.stocks.index') }}" class="btn btn-warning-modern btn-modern">
                        <i class="fas fa-boxes me-2"></i>Contrôler les Stocks
                    </a>
                    <a href="{{ route('dg.reports.index') }}" class="btn btn-success-modern btn-modern">
                        <i class="fas fa-chart-bar me-2"></i>Générer Rapport
                    </a>
                    <a href="{{ route('dg.map') }}" class="btn btn-danger-modern btn-modern">
                        <i class="fas fa-map-marked-alt me-2"></i>Carte Interactive
                    </a>
                </div>
                
                <hr class="my-3">
                
                <h6 class="fw-bold mb-2">📊 Métriques Rapides</h6>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="p-2">
                            <h6 class="mb-0 text-success">{{ $stats['approved_requests'] ?? 0 }}</h6>
                            <small class="text-muted">Approuvées</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2">
                            <h6 class="mb-0 text-danger">{{ $stats['rejected_requests'] ?? 0 }}</h6>
                            <small class="text-muted">Rejetées</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte interactive et alertes -->
    <div class="row">
        <!-- Carte interactive -->
        <div class="col-lg-8 mb-4">
            <div class="card-modern p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-map-marked-alt me-2 text-primary"></i>
                        Carte des Entrepôts et Demandes
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="toggleMapLayer('warehouses')">
                            <i class="fas fa-warehouse me-1"></i>Entrepôts
                        </button>
                        <button class="btn btn-outline-primary" onclick="toggleMapLayer('requests')">
                            <i class="fas fa-map-pin me-1"></i>Demandes
                        </button>
                        <button class="btn btn-outline-primary" onclick="exportMapToPDF()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                    </div>
                </div>
                <div id="interactiveMap" style="height: 400px; border-radius: 10px; overflow: hidden;"></div>
            </div>
        </div>
        
        <!-- Alertes système -->
        <div class="col-lg-4 mb-4">
            <div class="card-modern p-4">
                <h5 class="mb-3 fw-bold">
                    <i class="fas fa-bell me-2 text-warning"></i>
                    Alertes Système
                </h5>
                
                <div class="alert alert-warning d-flex align-items-center mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Stock faible</strong><br>
                        <small>Entrepôt Principal - 0 unités</small>
                    </div>
                </div>
                
                <div class="alert alert-info d-flex align-items-center mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Maintenance programmée</strong><br>
                        <small>Demain 14h - Entrepôt Régional</small>
                    </div>
                </div>
                
                <div class="alert alert-success d-flex align-items-center mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>
                        <strong>Système opérationnel</strong><br>
                        <small>Tous les services fonctionnels</small>
                    </div>
                </div>
                
                <button class="btn btn-outline-primary btn-sm w-100" onclick="viewAllAlerts()">
                    <i class="fas fa-list me-2"></i>Voir toutes les alertes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Graphique des demandes
    const ctx = document.getElementById('requestsChart').getContext('2d');
    const requestsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Demandes',
                data: [12, 19, 3, 5, 2, 3, 8],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
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

    // Carte interactive
    const map = L.map('interactiveMap').setView([14.6928, -17.4467], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Marqueurs d'entrepôts
    const warehouseIcon = L.divIcon({
        className: 'warehouse-marker',
        html: '<i class="fas fa-warehouse" style="color: #667eea; font-size: 20px;"></i>',
        iconSize: [30, 30]
    });

    // Ajouter des marqueurs d'exemple
    L.marker([14.6928, -17.4467], {icon: warehouseIcon})
        .addTo(map)
        .bindPopup('<b>Entrepôt Principal CSAR</b><br>Dakar, Sénégal');

    // Fonctions
    function refreshDashboard() {
        location.reload();
    }

    function generateReport() {
        // Générer un rapport PDF
        alert('Génération du rapport PDF...');
    }

    function exportData() {
        // Exporter les données en Excel
        alert('Export des données en Excel...');
    }

    function updateChart(period) {
        // Mettre à jour le graphique selon la période
        console.log('Mise à jour du graphique pour:', period);
    }

    function viewDetailedMetrics() {
        // Afficher les métriques détaillées
        alert('Ouverture des métriques détaillées...');
    }

    function toggleMapLayer(layer) {
        // Basculer l'affichage des couches de la carte
        console.log('Basculement de la couche:', layer);
    }

    function exportMapToPDF() {
        // Exporter la carte en PDF
        alert('Export de la carte en PDF...');
    }

    function viewAllAlerts() {
        // Voir toutes les alertes
        alert('Ouverture de toutes les alertes...');
    }

    // Mise à jour automatique des statistiques
    setInterval(function() {
        // Simuler une mise à jour des données
        const stats = document.querySelectorAll('[data-stat]');
        stats.forEach(stat => {
            const currentValue = parseInt(stat.textContent);
            const newValue = currentValue + Math.floor(Math.random() * 3) - 1;
            stat.textContent = Math.max(0, newValue);
        });
    }, 30000);
</script>
@endsection
