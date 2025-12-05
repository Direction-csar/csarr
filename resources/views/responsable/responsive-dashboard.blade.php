@extends('layouts.responsive-admin', ['role' => 'responsable'])

@section('title', 'Tableau de Bord Entrepôt - CSAR Responsive')

@section('page-title', 'Tableau de Bord Entrepôt')
@section('page-subtitle', 'Gestion de votre entrepôt CSAR')

@section('sidebar-navigation')
<!-- Navigation principale -->
<div class="nav-section">
    <div class="nav-section-title">Gestion de l'entrepôt</div>
    
    <a href="{{ route('responsable.dashboard') }}" class="nav-item {{ request()->routeIs('responsable.dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <span class="nav-text">Tableau de bord</span>
    </a>
    
    <a href="{{ route('responsable.stocks.index') }}" class="nav-item {{ request()->routeIs('responsable.stocks.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-boxes"></i>
        <span class="nav-text">Gestion des stocks</span>
    </a>
    
    <a href="{{ route('responsable.movements.index') }}" class="nav-item {{ request()->routeIs('responsable.movements.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-exchange-alt"></i>
        <span class="nav-text">Mouvements de stock</span>
    </a>
    
    <a href="{{ route('responsable.alerts.index') }}" class="nav-item {{ request()->routeIs('responsable.alerts.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-exclamation-triangle"></i>
        <span class="nav-text">Alertes</span>
        @php($activeAlerts = \App\Models\StockAlert::where('warehouse_id', auth()->user()->warehouse_id)->where('status', 'active')->count())
        @if($activeAlerts)
            <span class="nav-badge">{{ $activeAlerts }}</span>
        @endif
    </a>
</div>

<!-- Personnel -->
<div class="nav-section">
    <div class="nav-section-title">Personnel</div>
    
    <a href="{{ route('responsable.team.index') }}" class="nav-item {{ request()->routeIs('responsable.team.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <span class="nav-text">Équipe</span>
    </a>
    
    <a href="{{ route('responsable.attendance.index') }}" class="nav-item {{ request()->routeIs('responsable.attendance.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-clock"></i>
        <span class="nav-text">Pointage</span>
    </a>
</div>

<!-- Rapports -->
<div class="nav-section">
    <div class="nav-section-title">Rapports</div>
    
    <a href="{{ route('responsable.reports.stock') }}" class="nav-item {{ request()->routeIs('responsable.reports.stock') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-bar"></i>
        <span class="nav-text">Rapport de stock</span>
    </a>
    
    <a href="{{ route('responsable.reports.movements') }}" class="nav-item {{ request()->routeIs('responsable.reports.movements') ? 'active' : '' }}">
        <i class="nav-icon fas fa-chart-line"></i>
        <span class="nav-text">Rapport de mouvements</span>
    </a>
</div>
@endsection

@section('mobile-navigation')
<a href="{{ route('responsable.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('responsable.dashboard') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-home"></i>
    <span>Accueil</span>
</a>
<a href="{{ route('responsable.stocks.index') }}" class="mobile-nav-item {{ request()->routeIs('responsable.stocks.*') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-boxes"></i>
    <span>Stocks</span>
</a>
<a href="{{ route('responsable.movements.index') }}" class="mobile-nav-item {{ request()->routeIs('responsable.movements.*') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-exchange-alt"></i>
    <span>Mouvements</span>
</a>
<a href="{{ route('responsable.alerts.index') }}" class="mobile-nav-item {{ request()->routeIs('responsable.alerts.*') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-exclamation-triangle"></i>
    <span>Alertes</span>
</a>
<a href="{{ route('responsable.team.index') }}" class="mobile-nav-item {{ request()->routeIs('responsable.team.*') ? 'active' : '' }}">
    <i class="mobile-nav-icon fas fa-users"></i>
    <span>Équipe</span>
</a>
@endsection

@section('header-actions')
<div class="flex items-center space-x-4">
    <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-600">
        <i class="fas fa-warehouse"></i>
        <span>{{ auth()->user()->warehouse->name ?? 'Entrepôt' }}</span>
    </div>
    <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-600">
        <i class="fas fa-map-marker-alt"></i>
        <span>{{ auth()->user()->warehouse->city ?? 'Localisation' }}</span>
    </div>
</div>
@endsection

@section('content')
<!-- Warehouse Info -->
<div class="responsive-card mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-csar-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-warehouse text-2xl text-csar-green-600"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ auth()->user()->warehouse->name ?? 'Entrepôt CSAR' }}</h2>
                <p class="text-gray-600">{{ auth()->user()->warehouse->address ?? 'Adresse non définie' }}</p>
            </div>
        </div>
        <div class="hidden sm:flex items-center space-x-4 text-sm text-gray-600">
            <div class="text-center">
                <div class="font-semibold text-gray-900">{{ auth()->user()->warehouse->capacity ?? 0 }}</div>
                <div>Capacité max</div>
            </div>
            <div class="text-center">
                <div class="font-semibold text-csar-green-600">{{ $stats['occupation_rate'] ?? 0 }}%</div>
                <div>Taux occupation</div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards Grid -->
<div class="stats-grid">
    <!-- Stock total -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <div class="stat-card-value">{{ $stats['total_stock'] ?? 0 }}</div>
                <div class="stat-card-label">Articles en stock</div>
                <div class="stat-details">
                    <span class="badge badge-success">{{ $stats['available_stock'] ?? 0 }} disponibles</span>
                </div>
            </div>
            <div class="stat-card-icon bg-blue-500">
                <i class="fas fa-boxes"></i>
            </div>
        </div>
    </div>
    
    <!-- Valeur du stock -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <div class="stat-card-value">{{ number_format($stats['stock_value'] ?? 0, 0, ',', ' ') }}</div>
                <div class="stat-card-label">Valeur du stock (FCFA)</div>
                <div class="stat-details">
                    <span class="badge badge-stock">Estimation</span>
                </div>
            </div>
            <div class="stat-card-icon bg-green-500">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    
    <!-- Alertes critiques -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <div class="stat-card-value">{{ $stats['critical_alerts'] ?? 0 }}</div>
                <div class="stat-card-label">Alertes critiques</div>
                <div class="stat-details">
                    <span class="badge badge-pending">À traiter</span>
                </div>
            </div>
            <div class="stat-card-icon bg-red-500">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    
    <!-- Mouvements aujourd'hui -->
    <div class="stat-card">
        <div class="stat-card-content">
            <div class="stat-card-info">
                <div class="stat-card-value">{{ $stats['today_movements'] ?? 0 }}</div>
                <div class="stat-card-label">Mouvements aujourd'hui</div>
                <div class="stat-details">
                    <span class="badge badge-active">En cours</span>
                </div>
            </div>
            <div class="stat-card-icon bg-orange-500">
                <i class="fas fa-exchange-alt"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="responsive-grid-2 gap-6 sm:gap-8 mb-8">
    <!-- Évolution du stock -->
    <div class="chart-container">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Évolution du stock</h3>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-sm text-gray-600">Quantité</span>
            </div>
        </div>
        <canvas id="stockEvolutionChart" width="400" height="200"></canvas>
    </div>
    
    <!-- Répartition par type -->
    <div class="chart-container">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Répartition par type</h3>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm text-gray-600">Articles</span>
            </div>
        </div>
        <canvas id="stockTypeChart" width="400" height="200"></canvas>
    </div>
</div>

<!-- Content Grid -->
<div class="responsive-grid-2 gap-6 sm:gap-8">
    <!-- Alertes critiques -->
    <div class="responsive-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Alertes critiques</h3>
            <a href="{{ route('responsable.alerts.index') }}" class="text-sm text-red-600 hover:text-red-700">
                Voir tout
            </a>
        </div>
        <div class="space-y-4">
            @forelse($critical_alerts ?? [] as $alert)
                <div class="flex items-start space-x-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-red-900">
                            {{ $alert['product_name'] }}
                        </p>
                        <p class="text-xs text-red-700">
                            Stock faible: {{ $alert['current_stock'] }} unités
                        </p>
                        <p class="text-xs text-red-600">
                            {{ $alert['created_at']->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-shield-check text-4xl mb-2"></i>
                    <p>Aucune alerte critique</p>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Mouvements récents -->
    <div class="responsive-card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Mouvements récents</h3>
            <a href="{{ route('responsable.movements.index') }}" class="text-sm text-csar-green-600 hover:text-csar-green-700">
                Voir tout
            </a>
        </div>
        <div class="space-y-4">
            @forelse($recent_movements ?? [] as $movement)
                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-2 h-2 {{ $movement['type'] === 'in' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mt-2"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $movement['product_name'] }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $movement['type'] === 'in' ? 'Entrée' : 'Sortie' }}: {{ $movement['quantity'] }} unités
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $movement['created_at']->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-exchange-alt text-4xl mb-2"></i>
                    <p>Aucun mouvement récent</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="responsive-card mt-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions rapides</h3>
    <div class="responsive-grid-2 lg:responsive-grid-4 gap-4">
        <a href="{{ route('responsable.stocks.create') }}" class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
            <div class="text-center">
                <i class="fas fa-plus text-2xl text-blue-600 mb-2"></i>
                <p class="text-sm font-medium text-blue-900">Ajouter stock</p>
            </div>
        </a>
        
        <a href="{{ route('responsable.movements.create') }}" class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
            <div class="text-center">
                <i class="fas fa-exchange-alt text-2xl text-green-600 mb-2"></i>
                <p class="text-sm font-medium text-green-900">Nouveau mouvement</p>
            </div>
        </a>
        
        <a href="{{ route('responsable.reports.stock') }}" class="flex items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
            <div class="text-center">
                <i class="fas fa-file-pdf text-2xl text-orange-600 mb-2"></i>
                <p class="text-sm font-medium text-orange-900">Rapport stock</p>
            </div>
        </a>
        
        <a href="{{ route('responsable.alerts.index') }}" class="flex items-center justify-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-2xl text-red-600 mb-2"></i>
                <p class="text-sm font-medium text-red-900">Gérer alertes</p>
            </div>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts with responsive configuration
    const stockEvolutionData = @json($stock_evolution_data ?? []);
    const stockTypeData = @json($stock_type_data ?? []);
    
    // Stock Evolution Chart
    const stockEvolutionCtx = document.getElementById('stockEvolutionChart');
    if (stockEvolutionCtx && stockEvolutionData.length > 0) {
        new Chart(stockEvolutionCtx, {
            type: 'line',
            data: {
                labels: stockEvolutionData.map(item => item.date),
                datasets: [{
                    label: 'Stock total',
                    data: stockEvolutionData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
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
                            color: 'rgba(0, 0, 0, 0.1)'
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
    
    // Stock Type Chart
    const stockTypeCtx = document.getElementById('stockTypeChart');
    if (stockTypeCtx && stockTypeData.length > 0) {
        new Chart(stockTypeCtx, {
            type: 'doughnut',
            data: {
                labels: stockTypeData.map(item => item.type),
                datasets: [{
                    data: stockTypeData.map(item => item.count),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection

