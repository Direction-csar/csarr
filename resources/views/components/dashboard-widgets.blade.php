{{-- Widgets de tableau de bord interactifs pour CSAR --}}

{{-- Widget Statistiques Rapides --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 widget-card" data-widget="stats">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Demandes Aujourd'hui
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="today-requests">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Chargement...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i> +12% par rapport à hier
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 widget-card" data-widget="stats">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Stock Disponible
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="available-stock">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Chargement...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-info">
                        <i class="fas fa-info-circle"></i> 5 entrepôts actifs
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 widget-card" data-widget="stats">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Alertes Actives
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="active-alerts">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Chargement...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-warning">
                        <i class="fas fa-clock"></i> 3 nécessitent une attention
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 widget-card" data-widget="stats">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Personnel Actif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="active-personnel">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Chargement...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-success">
                        <i class="fas fa-check-circle"></i> 95% de présence
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Widget Graphique de Performance --}}
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4 widget-card" data-widget="chart">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Performance des Demandes</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
                        <a class="dropdown-item" href="#" onclick="changeChartPeriod('7d')">7 derniers jours</a>
                        <a class="dropdown-item" href="#" onclick="changeChartPeriod('30d')">30 derniers jours</a>
                        <a class="dropdown-item" href="#" onclick="changeChartPeriod('90d')">3 derniers mois</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4 widget-card" data-widget="chart">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Répartition par Région</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="regionChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Dakar
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Thiès
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Kaolack
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Widget Activités Récentes --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow mb-4 widget-card" data-widget="activities">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Activités Récentes</h6>
                <button class="btn btn-sm btn-primary" onclick="refreshActivities()">
                    <i class="fas fa-sync-alt"></i> Actualiser
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="activitiesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Heure</th>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>Détails</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody id="activitiesTableBody">
                            <tr>
                                <td colspan="5" class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Chargement des activités...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Widget Notifications en Temps Réel --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow mb-4 widget-card" data-widget="notifications">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Notifications en Temps Réel</h6>
                <div class="btn-group">
                    <button class="btn btn-sm btn-success" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i> Tout marquer comme lu
                    </button>
                    <button class="btn btn-sm btn-info" onclick="refreshNotifications()">
                        <i class="fas fa-sync-alt"></i> Actualiser
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="notificationsList">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Chargement des notifications...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Styles pour les widgets --}}
<style>
.widget-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.widget-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.widget-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.widget-card[data-widget="stats"] .card-header {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.widget-card[data-widget="chart"] .card-header {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.widget-card[data-widget="activities"] .card-header {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.widget-card[data-widget="notifications"] .card-header {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    color: #333;
}

.border-left-primary {
    border-left: 4px solid #4e73df !important;
}

.border-left-success {
    border-left: 4px solid #1cc88a !important;
}

.border-left-info {
    border-left: 4px solid #36b9cc !important;
}

.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}

.chart-area {
    position: relative;
    height: 300px;
}

.chart-pie {
    position: relative;
    height: 200px;
}

.notification-item {
    padding: 12px;
    margin-bottom: 8px;
    border-radius: 8px;
    border-left: 4px solid #ddd;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.notification-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.notification-item.unread {
    background: #fff3cd;
    border-left-color: #ffc107;
}

.notification-item.success {
    border-left-color: #28a745;
}

.notification-item.warning {
    border-left-color: #ffc107;
}

.notification-item.danger {
    border-left-color: #dc3545;
}

.notification-item.info {
    border-left-color: #17a2b8;
}

.activity-item {
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-time {
    font-size: 12px;
    color: #6c757d;
}

.activity-user {
    font-weight: 600;
    color: #495057;
}

.activity-action {
    color: #007bff;
}

.activity-status {
    font-size: 12px;
    padding: 2px 8px;
    border-radius: 12px;
}

.status-success {
    background: #d4edda;
    color: #155724;
}

.status-warning {
    background: #fff3cd;
    color: #856404;
}

.status-danger {
    background: #f8d7da;
    color: #721c24;
}
</style>

{{-- Scripts pour les widgets --}}
<script>
// Configuration des widgets
let performanceChart, regionChart;
let refreshInterval;

document.addEventListener('DOMContentLoaded', function() {
    initializeWidgets();
    startAutoRefresh();
});

function initializeWidgets() {
    loadStats();
    loadActivities();
    loadNotifications();
    initializeCharts();
}

function loadStats() {
    // Simulation de données - remplacer par des appels API réels
    setTimeout(() => {
        document.getElementById('today-requests').innerHTML = '24';
        document.getElementById('available-stock').innerHTML = '1,250 tonnes';
        document.getElementById('active-alerts').innerHTML = '3';
        document.getElementById('active-personnel').innerHTML = '47';
    }, 1000);
}

function loadActivities() {
    // Simulation de données - remplacer par des appels API réels
    const activities = [
        {
            time: '12:35',
            user: 'Admin CSAR',
            action: 'Nouvelle demande traitée',
            details: 'Demande #CSAR000124 validée',
            status: 'success'
        },
        {
            time: '12:30',
            user: 'Agent Thiès',
            action: 'Mise à jour stock',
            details: 'Entrepôt Thiès: +50 tonnes riz',
            status: 'success'
        },
        {
            time: '12:25',
            user: 'Système',
            action: 'Alerte prix',
            details: 'Prix du mil en hausse de 15%',
            status: 'warning'
        },
        {
            time: '12:20',
            user: 'DG CSAR',
            action: 'Rapport généré',
            details: 'Rapport mensuel SIM créé',
            status: 'success'
        }
    ];

    const tbody = document.getElementById('activitiesTableBody');
    tbody.innerHTML = '';

    activities.forEach(activity => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="activity-time">${activity.time}</td>
            <td class="activity-user">${activity.user}</td>
            <td class="activity-action">${activity.action}</td>
            <td>${activity.details}</td>
            <td><span class="activity-status status-${activity.status}">${activity.status}</span></td>
        `;
        tbody.appendChild(row);
    });
}

function loadNotifications() {
    // Simulation de données - remplacer par des appels API réels
    const notifications = [
        {
            id: 1,
            title: 'Nouvelle demande urgente',
            message: 'Demande #CSAR000125 nécessite une attention immédiate',
            type: 'warning',
            time: 'Il y a 5 minutes',
            unread: true
        },
        {
            id: 2,
            title: 'Stock faible',
            message: 'Entrepôt Kaolack: stock de maïs en dessous du seuil',
            type: 'danger',
            time: 'Il y a 15 minutes',
            unread: true
        },
        {
            id: 3,
            title: 'Rapport généré',
            message: 'Rapport SIM mensuel prêt pour téléchargement',
            type: 'success',
            time: 'Il y a 1 heure',
            unread: false
        }
    ];

    const container = document.getElementById('notificationsList');
    container.innerHTML = '';

    notifications.forEach(notification => {
        const item = document.createElement('div');
        item.className = `notification-item ${notification.type} ${notification.unread ? 'unread' : ''}`;
        item.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">${notification.title}</h6>
                    <p class="mb-1">${notification.message}</p>
                    <small class="text-muted">${notification.time}</small>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-primary" onclick="markAsRead(${notification.id})">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(item);
    });
}

function initializeCharts() {
    // Graphique de performance
    const perfCtx = document.getElementById('performanceChart').getContext('2d');
    performanceChart = new Chart(perfCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Demandes traitées',
                data: [12, 19, 15, 25, 22, 18, 24],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'Demandes en attente',
                data: [5, 8, 12, 7, 9, 6, 4],
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
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

    // Graphique des régions
    const regionCtx = document.getElementById('regionChart').getContext('2d');
    regionChart = new Chart(regionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Dakar', 'Thiès', 'Kaolack', 'Saint-Louis', 'Ziguinchor'],
            datasets: [{
                data: [35, 25, 20, 12, 8],
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444',
                    '#8B5CF6'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function changeChartPeriod(period) {
    // Fonction pour changer la période du graphique
    console.log('Changement de période:', period);
    // Ici, vous feriez un appel API pour récupérer les nouvelles données
}

function refreshActivities() {
    loadActivities();
}

function refreshNotifications() {
    loadNotifications();
}

function markAsRead(notificationId) {
    // Fonction pour marquer une notification comme lue
    console.log('Marquer comme lu:', notificationId);
    // Ici, vous feriez un appel API
}

function markAllAsRead() {
    // Fonction pour marquer toutes les notifications comme lues
    console.log('Marquer toutes comme lues');
    // Ici, vous feriez un appel API
}

function startAutoRefresh() {
    // Actualisation automatique toutes les 30 secondes
    refreshInterval = setInterval(() => {
        loadStats();
        loadActivities();
        loadNotifications();
    }, 30000);
}

// Nettoyer l'intervalle quand la page est fermée
window.addEventListener('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>






