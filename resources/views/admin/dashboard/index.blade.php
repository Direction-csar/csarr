@extends('layouts.admin')

@section('title', 'Tableau de Bord')
@section('page-title', 'Tableau de Bord Administrateur')

@section('content')
<div class="container-fluid px-3">
    <!-- Header compact -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">📊 Tableau de Bord</h1>
                        <p class="text-muted mb-0 small">Vue d'ensemble du système CSAR - {{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="generateReport()">
                            <i class="fas fa-download me-1"></i>Rapport
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalStats = ($stats['total_users'] ?? 0) + ($stats['total_requests'] ?? 0) + ($stats['total_warehouses'] ?? 0) + ($stats['total_stock'] ?? 0) + ($stats['unread_notifications'] ?? 0) + ($stats['unread_messages'] ?? 0);
        $hasData = $totalStats > 0;
    @endphp

    @if(!$hasData)
        <!-- Message d'état vide -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card-modern p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucune donnée disponible pour le moment</h4>
                        <p class="text-muted mb-4">Votre base de données MySQL est vide. Commencez par ajouter des données via les formulaires ci-dessous.</p>
                    </div>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Ajouter un Utilisateur
                        </a>
                        <a href="{{ route('admin.entrepots.create') }}" class="btn btn-success">
                            <i class="fas fa-warehouse me-2"></i>Ajouter un Entrepôt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistiques principales compactes -->
    <div class="row mb-2">
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-primary); width: 45px; height: 45px;">
                        <i class="fas fa-users" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-primary" id="total-users" data-stat="total_users">{{ $stats['total_users'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">👥 Utilisateurs</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-warning); width: 45px; height: 45px;">
                        <i class="fas fa-clipboard-list" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-warning" id="pending-requests" data-stat="pending_requests">{{ $stats['pending_requests'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">📋 Demandes</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-success); width: 45px; height: 45px;">
                        <i class="fas fa-warehouse" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-success" id="total-warehouses" data-stat="total_warehouses">{{ $stats['total_warehouses'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">🏢 Entrepôts</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-danger); width: 45px; height: 45px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-danger" id="low-stock" data-stat="low_stock_items">{{ $stats['low_stock_items'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">⚠️ Stock Faible</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques secondaires compactes -->
    <div class="row mb-2">
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-secondary); width: 45px; height: 45px;">
                        <i class="fas fa-gas-pump" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-info" id="fuel-available" data-stat="fuel_available">{{ $stats['fuel_available'] ?? 0 }}L</h4>
                        <p class="text-muted mb-0 small">⛽ Carburant</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-primary); width: 45px; height: 45px;">
                        <i class="fas fa-bell" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-primary" id="unread-notifications">{{ session('notifications', collect())->where('read', false)->count() }}</h4>
                        <p class="text-muted mb-0 small">🔔 Notifications</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-success); width: 45px; height: 45px;">
                        <i class="fas fa-envelope" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-success" id="unread-messages">{{ session('messages', collect())->where('read', false)->count() }}</h4>
                        <p class="text-muted mb-0 small">💬 Messages</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-warning); width: 45px; height: 45px;">
                        <i class="fas fa-chart-line" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-warning" id="total-stock" data-stat="total_stock">{{ $stats['total_stock'] ?? 0 }}</h4>
                        <p class="text-muted mb-0 small">📦 Stock Total</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques compacts -->
    <div class="row mb-2">
        <div class="col-lg-6 mb-2">
            <div class="card-modern p-2">
                <h6 class="mb-1 fw-bold">📈 Demandes (7j)</h6>
                <div class="chart-container" style="position: relative; height: 180px;">
                    @if($hasData)
                        <canvas id="requestsChart"></canvas>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <div class="text-center">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <p class="mb-0 small">Aucune donnée disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-2">
            <div class="card-modern p-2">
                <h6 class="mb-1 fw-bold">📊 Entrepôts</h6>
                <div class="chart-container" style="position: relative; height: 180px;">
                    @if($hasData)
                        <canvas id="warehousesChart"></canvas>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <div class="text-center">
                                <i class="fas fa-warehouse fa-2x mb-2"></i>
                                <p class="mb-0 small">Aucune donnée disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-lg-6 mb-2">
            <div class="card-modern p-2">
                <h6 class="mb-1 fw-bold">⛽ Carburant</h6>
                <div class="chart-container" style="position: relative; height: 180px;">
                    @if($hasData)
                        <canvas id="fuelChart"></canvas>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <div class="text-center">
                                <i class="fas fa-gas-pump fa-2x mb-2"></i>
                                <p class="mb-0 small">Aucune donnée disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-2">
            <div class="card-modern p-2">
                <h6 class="mb-1 fw-bold">📦 Stock</h6>
                <div class="chart-container" style="position: relative; height: 180px;">
                    @if($hasData)
                        <canvas id="stockChart"></canvas>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <div class="text-center">
                                <i class="fas fa-boxes fa-2x mb-2"></i>
                                <p class="mb-0 small">Aucune donnée disponible</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activités récentes et actions rapides -->
    <div class="row mb-2">
        <div class="col-lg-8 mb-2">
            <div class="card-modern p-2">
                <h6 class="mb-2 fw-bold">📋 Demandes Récentes</h6>
                <div class="list-group list-group-flush">
                    @forelse($recentActivities['recent_requests'] ?? [] as $request)
                        <div class="list-group-item border-0 px-0 py-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 small">{{ $request->reference ?? 'REQ-' . $request->id }}</h6>
                                    <p class="mb-0 text-muted small">{{ $request->user->name ?? 'Utilisateur' }}</p>
                                    <small class="text-muted">{{ $request->created_at->diffForHumans() ?? 'Il y a 2h' }}</small>
                                </div>
                                <span class="badge bg-{{ $request->status === 'approved' ? 'success' : ($request->status === 'rejected' ? 'danger' : 'warning') }} small">
                                    {{ ucfirst($request->status ?? 'En attente') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-2 text-muted">
                            <i class="fas fa-clipboard-list fa-lg mb-1"></i>
                            <p class="small mb-0">Aucune demande récente</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-2">
            <div class="card-modern p-2">
                <h6 class="mb-2 fw-bold">⚡ Actions Rapides</h6>
                <div class="d-grid gap-1">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-user-plus me-1"></i>Nouvel Utilisateur
                    </a>
                    <a href="{{ route('admin.entrepots.create') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-warehouse me-1"></i>Nouvel Entrepôt
                    </a>
                    <button class="btn btn-warning btn-sm" onclick="generateReport()">
                        <i class="fas fa-file-pdf me-1"></i>Générer Rapport
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte interactive avec filtres et géolocalisation des demandes -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">🗺️ Carte des Entrepôts et Demandes d'Aide Alimentaire</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-info" onclick="toggleFilters()">
                            <i class="fas fa-filter me-1"></i>Filtres
                        </button>
                        <button class="btn btn-sm btn-success" onclick="exportMapToPDF()">
                            <i class="fas fa-file-pdf me-1"></i>Export PDF
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="refreshMapData()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                    </div>
                </div>

                <!-- Panneau de filtres (masqué par défaut) -->
                <div id="filterPanel" class="card border mb-3" style="display: none;">
                    <div class="card-body p-2">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label class="form-label small mb-1">Année</label>
                                <select id="filterYear" class="form-select form-select-sm" onchange="applyMapFilters()">
                                    <option value="">Toutes</option>
                                    @if(isset($chartsData['availableYears']) && count($chartsData['availableYears']) > 0)
                                        @foreach($chartsData['availableYears'] as $year)
                                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    @else
                                        @for($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small mb-1">Mois</label>
                                <select id="filterMonth" class="form-select form-select-sm" onchange="applyMapFilters()">
                                    <option value="">Tous</option>
                                    <option value="1">Janvier</option>
                                    <option value="2">Février</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Août</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small mb-1">Région</label>
                                <select id="filterRegion" class="form-select form-select-sm" onchange="applyMapFilters()">
                                    <option value="">Toutes</option>
                                    @if(isset($chartsData['availableRegions']) && count($chartsData['availableRegions']) > 0)
                                        @foreach($chartsData['availableRegions'] as $region)
                                            <option value="{{ $region }}">{{ $region }}</option>
                                        @endforeach
                                    @else
                                        <option value="Dakar">Dakar</option>
                                        <option value="Saint-Louis">Saint-Louis</option>
                                        <option value="Thiès">Thiès</option>
                                        <option value="Diourbel">Diourbel</option>
                                        <option value="Kaolack">Kaolack</option>
                                        <option value="Ziguinchor">Ziguinchor</option>
                                        <option value="Louga">Louga</option>
                                        <option value="Fatick">Fatick</option>
                                        <option value="Kolda">Kolda</option>
                                        <option value="Matam">Matam</option>
                                        <option value="Tambacounda">Tambacounda</option>
                                        <option value="Kaffrine">Kaffrine</option>
                                        <option value="Kédougou">Kédougou</option>
                                        <option value="Sédhiou">Sédhiou</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small mb-1">Statut</label>
                                <select id="filterStatus" class="form-select form-select-sm" onchange="applyMapFilters()">
                                    <option value="">Tous</option>
                                    <option value="en_attente">En attente</option>
                                    <option value="traitee">Traitée</option>
                                    <option value="rejetee">Rejetée</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small mb-1">Type</label>
                                <select id="filterType" class="form-select form-select-sm" onchange="applyMapFilters()">
                                    <option value="all">Tout afficher</option>
                                    <option value="warehouses">Entrepôts uniquement</option>
                                    <option value="demandes">Demandes uniquement</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-sm btn-secondary w-100" onclick="resetMapFilters()">
                                    <i class="fas fa-redo me-1"></i>Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques rapides de la carte -->
                <div class="row g-2 mb-2">
                    <div class="col-md-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-2 text-center">
                                <i class="fas fa-warehouse text-primary mb-1"></i>
                                <h6 class="mb-0" id="mapStatWarehouses">0</h6>
                                <small class="text-muted">Entrepôts</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-2 text-center">
                                <i class="fas fa-map-marker-alt text-danger mb-1"></i>
                                <h6 class="mb-0" id="mapStatDemandes">0</h6>
                                <small class="text-muted">Demandes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-2 text-center">
                                <i class="fas fa-clock text-warning mb-1"></i>
                                <h6 class="mb-0" id="mapStatPending">0</h6>
                                <small class="text-muted">En attente</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-2 text-center">
                                <i class="fas fa-check-circle text-success mb-1"></i>
                                <h6 class="mb-0" id="mapStatApproved">0</h6>
                                <small class="text-muted">Traitées</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte avec légende intégrée -->
                <div class="position-relative">
                    <div id="warehouseMap" style="height: 500px; border-radius: 8px; overflow: hidden; z-index: 1;"></div>
                    
                    <!-- Légende flottante -->
                    <div id="mapLegend" class="position-absolute bg-white p-2 shadow-sm" 
                         style="top: 10px; right: 10px; border-radius: 8px; z-index: 1000; font-size: 0.85rem;">
                        <h6 class="mb-2 small fw-bold">Légende</h6>
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-warehouse text-primary me-2"></i>
                            <span class="small">Entrepôts CSAR</span>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <img src="{{ asset('images/logos/LOGO CSAR vectoriel-01.png') }}" width="16" height="16" class="me-2" alt="Logo CSAR">
                            <span class="small">Demandes d'aide</span>
                        </div>
                        <hr class="my-2">
                        <div class="small">
                            <div class="mb-1"><span class="badge bg-warning">En attente</span></div>
                            <div class="mb-1"><span class="badge bg-success">Traitée</span></div>
                            <div class="mb-1"><span class="badge bg-danger">Rejetée</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes système compactes -->
    @if(!empty($alerts))
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-2">
                <h6 class="mb-2 fw-bold">🚨 Alertes Système</h6>
                <div class="row">
                    @foreach($alerts as $alert)
                        <div class="col-md-6 mb-1">
                            <div class="alert alert-{{ $alert['type'] ?? 'info' }} alert-dismissible fade show border-0 py-1" role="alert" style="border-radius: 8px;">
                                <div class="d-flex align-items-center">
                                    <div class="icon-3d me-2" style="width: 25px; height: 25px; background: var(--gradient-{{ $alert['type'] ?? 'primary' }});">
                                        <i class="fas fa-{{ $alert['icon'] ?? 'info-circle' }}" style="font-size: 10px;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong style="font-size: 0.8rem;">{{ $alert['title'] ?? 'Alerte' }}</strong><br>
                                        <small style="font-size: 0.7rem;">{{ $alert['message'] ?? '' }}</small>
                                    </div>
                                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

@endsection

@push('styles')
<style>
  /* Optimisation de l'espace */
  .container-fluid { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
  
  /* Cards plus compactes */
  .card-modern { margin-bottom: 0.5rem !important; }
  
  /* Graphiques optimisés */
  .chart-container { position: relative; height: 180px; }
  .chart-container canvas { width: 100% !important; height: 100% !important; }
  
  /* Réduction des marges */
  .row { margin-bottom: 0.5rem !important; }
  .mb-2 { margin-bottom: 0.5rem !important; }
  .mb-3 { margin-bottom: 0.75rem !important; }
  
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
  
  /* Assurer que tous les liens sont cliquables */
  a { 
    cursor: pointer !important; 
    pointer-events: auto !important; 
    z-index: 10 !important;
  }
  
  /* Listes plus compactes */
  .list-group-item { padding: 0.25rem 0 !important; }
  
  /* Alertes plus petites */
  .alert { padding: 0.5rem !important; margin-bottom: 0.25rem !important; }
  
  /* S'assurer que les cartes sont cliquables */
  .card-modern {
    cursor: default !important;
    pointer-events: auto !important;
  }
  
  /* Boutons d'action dans les cartes */
  .card-modern .btn {
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 15 !important;
  }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<!-- jsPDF et html2canvas pour l'export PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<!-- Leaflet.markercluster pour grouper les marqueurs -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
// Graphique des demandes (moderne)
let requestsChart, warehousesChart, fuelChart, stockChart;
const requestsCtx = document.getElementById('requestsChart');
if (requestsCtx) {
    // Utiliser les vraies données du contrôleur
    const chartData = @json($chartsData['last7Days'] ?? []);
    const labels = chartData.map(item => item.label);
    const data = chartData.map(item => item.requests);
    
    requestsChart = new Chart(requestsCtx, {
        type: 'line',
        data: {
            labels: labels.length > 0 ? labels : ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Demandes',
                data: data.length > 0 ? data : [0, 0, 0, 0, 0, 0, 0],
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

// Graphique des entrepôts (camembert)
const warehousesCtx = document.getElementById('warehousesChart');
if (warehousesCtx) {
    // Utiliser les vraies données du contrôleur
    const entrepotsData = @json($chartsData['entrepotsByRegion'] ?? []);
    const labels = Object.keys(entrepotsData);
    const data = Object.values(entrepotsData);
    
    warehousesChart = new Chart(warehousesCtx, {
        type: 'doughnut',
        data: {
            labels: labels.length > 0 ? labels : ['Aucune donnée'],
            datasets: [{
                data: data.length > 0 ? data : [1],
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe'
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

// Graphique du carburant (barres)
const fuelCtx = document.getElementById('fuelChart');
if (fuelCtx) {
    // Utiliser les vraies données du contrôleur
    const fuelData = @json($chartsData['fuelByEntrepot'] ?? []);
    const labels = Object.keys(fuelData);
    const data = Object.values(fuelData);
    
    fuelChart = new Chart(fuelCtx, {
        type: 'bar',
        data: {
            labels: labels.length > 0 ? labels : ['Aucune donnée'],
            datasets: [{
                label: 'Carburant (L)',
                data: data.length > 0 ? data : [0],
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(118, 75, 162, 0.8)',
                    'rgba(240, 147, 251, 0.8)',
                    'rgba(245, 87, 108, 0.8)',
                    'rgba(79, 172, 254, 0.8)'
                ],
                borderColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe'
                ],
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
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
                    },
                    ticks: {
                        maxTicksLimit: 5
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

// Graphique des stocks (barres horizontales)
const stockCtx = document.getElementById('stockChart');
if (stockCtx) {
    // Utiliser les vraies données du contrôleur
    const stockData = @json($chartsData['stockByCategory'] ?? []);
    const labels = Object.keys(stockData);
    const data = Object.values(stockData);
    
    stockChart = new Chart(stockCtx, {
        type: 'bar',
        data: {
            labels: labels.length > 0 ? labels : ['Aucune donnée'],
            datasets: [{
                label: 'Stock (tonnes)',
                data: data.length > 0 ? data : [0],
                backgroundColor: [
                    'rgba(79, 172, 254, 0.8)',
                    'rgba(0, 242, 254, 0.8)',
                    'rgba(67, 233, 123, 0.8)',
                    'rgba(56, 249, 215, 0.8)',
                    'rgba(250, 112, 154, 0.8)'
                ],
                borderColor: [
                    '#4facfe',
                    '#00f2fe',
                    '#43e97b',
                    '#38f9d7',
                    '#fa709a'
                ],
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        maxTicksLimit: 5
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// ==================== CARTE INTERACTIVE AVEC GÉOLOCALISATION ====================
let warehouseMap;
let markersLayer;
let currentMapData = [];

// Icônes personnalisées
const warehouseIcon = L.divIcon({
    className: 'custom-div-icon',
    html: '<div style="background-color: #3b82f6; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-warehouse" style="color: white; font-size: 14px;"></i></div>',
    iconSize: [30, 30],
    iconAnchor: [15, 15]
});

// Icône personnalisée avec logo CSAR pour les demandes
const demandeIcon = L.divIcon({
    className: 'custom-div-icon',
    html: '<div style="background-color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #dc3545; box-shadow: 0 3px 10px rgba(220,53,69,0.5);"><img src="{{ asset("images/logos/LOGO CSAR vectoriel-01.png") }}" style="width: 22px; height: 22px; object-fit: contain;" /></div>',
    iconSize: [35, 35],
    iconAnchor: [17, 17]
});

function initWarehouseMap() {
    // Centré sur le Sénégal
    warehouseMap = L.map('warehouseMap').setView([14.4974, -14.4524], 7);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | CSAR Admin'
    }).addTo(warehouseMap);
    
    // Initialiser le groupe de marqueurs avec clustering
    markersLayer = L.markerClusterGroup({
        chunkedLoading: true,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        maxClusterRadius: 50
    });
    
    // Charger les données initiales
    const initialData = @json($chartsData['mapData'] ?? []);
    currentMapData = initialData;
    renderMapMarkers(initialData);
    updateMapStats(initialData);
}

function renderMapMarkers(data) {
    // Effacer les marqueurs existants
    if (markersLayer) {
        markersLayer.clearLayers();
    }
    
    // Compteurs pour les statistiques
    let warehouseCount = 0;
    let demandeCount = 0;
    let pendingCount = 0;
    let approvedCount = 0;
    
    if (data && data.length > 0) {
        data.forEach(item => {
            let icon, popupContent, statusBadge;
            
            if (item.type === 'warehouse') {
                warehouseCount++;
                icon = warehouseIcon;
                statusBadge = '<span class="badge bg-primary">Entrepôt</span>';
                popupContent = `
                    <div style="min-width: 200px;">
                        <h6 class="mb-2 fw-bold text-primary">
                            <i class="fas fa-warehouse me-1"></i>${item.name}
                        </h6>
                        ${statusBadge}
                        <hr class="my-2">
                        <p class="mb-1 small"><strong>Région:</strong> ${item.region}</p>
                        <p class="mb-1 small"><strong>Adresse:</strong> ${item.address}</p>
                        <p class="mb-2 small"><strong>Statut:</strong> ${item.status || 'Actif'}</p>
                        <a href="/admin/entrepots" class="btn btn-sm btn-primary w-100">
                            <i class="fas fa-eye me-1"></i>Voir détails
                        </a>
                </div>
                `;
            } else if (item.type === 'demande') {
                demandeCount++;
                icon = demandeIcon;
                
                // Déterminer le badge de statut
                let badgeClass = 'warning';
                let statusText = item.status;
                if (item.status === 'traitee' || item.status === 'approved') {
                    badgeClass = 'success';
                    statusText = 'Traitée';
                    approvedCount++;
                } else if (item.status === 'rejetee' || item.status === 'rejected') {
                    badgeClass = 'danger';
                    statusText = 'Rejetée';
                } else if (item.status === 'en_attente' || item.status === 'pending') {
                    badgeClass = 'warning';
                    statusText = 'En attente';
                    pendingCount++;
                }
                
                statusBadge = `<span class="badge bg-${badgeClass}">${statusText}</span>`;
                
                popupContent = `
                    <div style="min-width: 220px;">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ asset("images/logos/LOGO CSAR vectoriel-01.png") }}" width="24" height="24" class="me-2" />
                            <h6 class="mb-0 fw-bold text-danger">Demande d'aide</h6>
                        </div>
                        ${statusBadge}
                        <hr class="my-2">
                        <p class="mb-1 small"><strong>Demandeur:</strong> ${item.name}</p>
                        <p class="mb-1 small"><strong>Région:</strong> ${item.region}</p>
                        <p class="mb-1 small"><strong>Date:</strong> ${item.created_at}</p>
                        <p class="mb-2 small"><strong>Adresse:</strong> ${item.address}</p>
                        <a href="/admin/demandes/${item.id}" class="btn btn-sm btn-danger w-100">
                            <i class="fas fa-eye me-1"></i>Voir la demande
                        </a>
                    </div>
                `;
            }
            
            const marker = L.marker([item.lat, item.lng], { icon: icon });
            marker.bindPopup(popupContent);
            markersLayer.addLayer(marker);
        });
        
        warehouseMap.addLayer(markersLayer);
        
        // Ajuster la vue pour englober tous les marqueurs
        if (markersLayer.getLayers().length > 0) {
            warehouseMap.fitBounds(markersLayer.getBounds(), { padding: [50, 50] });
        }
    } else {
        // Afficher un message si aucune donnée
        const centerMarker = L.marker([14.4974, -14.4524], { icon: warehouseIcon });
        centerMarker.bindPopup(`
            <div class="text-center p-2">
                <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                <h6>Aucune donnée disponible</h6>
                <p class="mb-0 small">Ajustez vos filtres ou ajoutez des données</p>
            </div>
        `);
        markersLayer.addLayer(centerMarker);
        warehouseMap.addLayer(markersLayer);
    }
    
    // Mettre à jour les statistiques
    document.getElementById('mapStatWarehouses').textContent = warehouseCount;
    document.getElementById('mapStatDemandes').textContent = demandeCount;
    document.getElementById('mapStatPending').textContent = pendingCount;
    document.getElementById('mapStatApproved').textContent = approvedCount;
}

function updateMapStats(data) {
    if (!data) return;
    
    const warehouses = data.filter(item => item.type === 'warehouse').length;
    const demandes = data.filter(item => item.type === 'demande').length;
    const pending = data.filter(item => 
        item.type === 'demande' && 
        (item.status === 'en_attente' || item.status === 'pending')
    ).length;
    const approved = data.filter(item => 
        item.type === 'demande' && 
        (item.status === 'traitee' || item.status === 'approved')
    ).length;
    
    document.getElementById('mapStatWarehouses').textContent = warehouses;
    document.getElementById('mapStatDemandes').textContent = demandes;
    document.getElementById('mapStatPending').textContent = pending;
    document.getElementById('mapStatApproved').textContent = approved;
}

// ==================== FILTRES ====================
function toggleFilters() {
    const panel = document.getElementById('filterPanel');
    if (panel.style.display === 'none') {
        panel.style.display = 'block';
    } else {
        panel.style.display = 'none';
    }
}

function applyMapFilters() {
    const year = document.getElementById('filterYear').value;
    const month = document.getElementById('filterMonth').value;
    const region = document.getElementById('filterRegion').value;
    const status = document.getElementById('filterStatus').value;
    const type = document.getElementById('filterType').value;
    
    // Afficher un loader
    showToast('Application des filtres...', 'info');
    
    // Appeler l'API avec les filtres
    fetch('{{ route("admin.dashboard.filter-map") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            year: year,
            month: month,
            region: region,
            status: status,
            type: type
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentMapData = data.data;
            renderMapMarkers(data.data);
            showToast(`Filtres appliqués: ${data.count} éléments trouvés`, 'success');
        } else {
            showToast('Erreur lors du filtrage', 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors du filtrage des données', 'danger');
    });
}

function resetMapFilters() {
    document.getElementById('filterYear').value = '';
    document.getElementById('filterMonth').value = '';
    document.getElementById('filterRegion').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterType').value = 'all';
    
    // Recharger les données initiales
    const initialData = @json($chartsData['mapData'] ?? []);
    currentMapData = initialData;
    renderMapMarkers(initialData);
    showToast('Filtres réinitialisés', 'success');
}

function refreshMapData() {
    applyMapFilters();
}

// ==================== EXPORT PDF ====================
function exportMapToPDF() {
    showToast('Génération du PDF en cours...', 'info');
    
    // Cacher temporairement les contrôles de la carte pour un PDF propre
    const legend = document.getElementById('mapLegend');
    const legendDisplay = legend.style.display;
    legend.style.display = 'none';
    
    // Capturer la carte avec html2canvas
    html2canvas(document.getElementById('warehouseMap'), {
        useCORS: true,
        logging: false,
        scale: 2
    }).then(canvas => {
        // Restaurer la légende
        legend.style.display = legendDisplay;
        
        const imgData = canvas.toDataURL('image/png');
        
        // Créer le PDF
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('l', 'mm', 'a4'); // landscape, millimeters, A4
        
        // Dimensions
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();
        const imgWidth = pdfWidth - 20;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        
        // En-tête
        pdf.setFontSize(18);
        pdf.setTextColor(59, 130, 246);
        pdf.text('CSAR - Carte des Entrepots et Demandes', pdfWidth / 2, 15, { align: 'center' });
        
        // Date et filtres
        pdf.setFontSize(10);
        pdf.setTextColor(100, 100, 100);
        const dateStr = new Date().toLocaleDateString('fr-FR', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        pdf.text('Genere le: ' + dateStr, 10, 22);
        
        // Statistiques
        const warehouses = currentMapData.filter(item => item.type === 'warehouse').length;
        const demandes = currentMapData.filter(item => item.type === 'demande').length;
        pdf.text('Entrepots: ' + warehouses + ' | Demandes: ' + demandes, pdfWidth - 10, 22, { align: 'right' });
        
        // Image de la carte
        pdf.addImage(imgData, 'PNG', 10, 28, imgWidth, Math.min(imgHeight, pdfHeight - 40));
        
        // Légende
        let legendY = Math.min(imgHeight + 35, pdfHeight - 35);
        pdf.setFontSize(12);
        pdf.setTextColor(0, 0, 0);
        pdf.text('Legende:', 10, legendY);
        
        pdf.setFontSize(10);
        pdf.setTextColor(59, 130, 246);
        pdf.text('- Entrepots CSAR', 15, legendY + 6);
        pdf.setTextColor(220, 53, 69);
        pdf.text('- Demandes d\'aide alimentaire', 50, legendY + 6);
        
        // Statuts
        pdf.setFontSize(9);
        pdf.setTextColor(100, 100, 100);
        pdf.text('Statuts:', 10, legendY + 12);
        pdf.setTextColor(255, 193, 7);
        pdf.text('- En attente', 15, legendY + 17);
        pdf.setTextColor(40, 167, 69);
        pdf.text('- Traitee', 45, legendY + 17);
        pdf.setTextColor(220, 53, 69);
        pdf.text('- Rejetee', 70, legendY + 17);
        
        // Pied de page
        pdf.setFontSize(8);
        pdf.setTextColor(150, 150, 150);
        pdf.text('(c) CSAR - Commissariat a la Securite Alimentaire et a la Resilience', pdfWidth / 2, pdfHeight - 5, { align: 'center' });
        
        // Télécharger le PDF
        const filename = `carte_csar_${new Date().getTime()}.pdf`;
        pdf.save(filename);
        
        showToast('PDF genere avec succes!', 'success');
    }).catch(error => {
        legend.style.display = legendDisplay;
        console.error('Erreur lors de la generation du PDF:', error);
        showToast('Erreur lors de la generation du PDF', 'danger');
    });
}

// Actualisation des statistiques dynamiques
function updateStats() {
    fetch('{{ route("admin.dashboard.realtime-stats") }}')
        .then(response => response.json())
        .then(data => {
            // Update stats cards avec animation
            document.querySelectorAll('[data-stat]').forEach(element => {
                const statName = element.getAttribute('data-stat');
                if (data[statName] !== undefined) {
                    animateValue(element, parseInt(element.textContent), data[statName], 1000);
                }
            });
            
            // Update notifications and messages
            const unreadNotificationsEl = document.getElementById('unread-notifications');
            const unreadMessagesEl = document.getElementById('unread-messages');
            
            if (unreadNotificationsEl) {
                animateValue(unreadNotificationsEl, parseInt(unreadNotificationsEl.textContent), data.unread_notifications || 0, 1000);
            }
            if (unreadMessagesEl) {
                animateValue(unreadMessagesEl, parseInt(unreadMessagesEl.textContent), data.unread_messages || 0, 1000);
            }
            
            // Mettre à jour les graphiques avec les nouvelles données
            updateCharts(data);
            
            // Mettre à jour la carte
            updateMap(data.map_data || []);
            
        })
        .catch(error => console.error('Erreur lors de la mise à jour des statistiques:', error));
}

// Animation des valeurs
function animateValue(element, start, end, duration) {
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

// Actualisation complète du dashboard
function refreshDashboard() {
    const refreshBtn = document.querySelector('button[onclick="refreshDashboard()"]');
    const icon = refreshBtn.querySelector('i');
    
    // Animation de rotation
    icon.style.animation = 'spin 1s linear infinite';
    
    // Actualiser les données
    updateStats();
    
    // Actualiser les graphiques
    setTimeout(() => {
        if (typeof requestsChart !== 'undefined') requestsChart.update();
        if (typeof warehousesChart !== 'undefined') warehousesChart.update();
        if (typeof fuelChart !== 'undefined') fuelChart.update();
        if (typeof stockChart !== 'undefined') stockChart.update();
        
        // Arrêter l'animation
        icon.style.animation = '';
        
        // Afficher un message de succès
        showToast('Dashboard actualisé avec succès!', 'success');
    }, 1000);
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

// Fonction pour mettre à jour les graphiques
function updateCharts(data) {
    // Mettre à jour le graphique des demandes
    if (typeof requestsChart !== 'undefined' && data.last7Days) {
        const labels = data.last7Days.map(item => item.label);
        const chartData = data.last7Days.map(item => item.requests);
        requestsChart.data.labels = labels;
        requestsChart.data.datasets[0].data = chartData;
        requestsChart.update('none'); // Mise à jour sans animation pour éviter les clignotements
    }
    
    // Mettre à jour le graphique des entrepôts
    if (typeof warehousesChart !== 'undefined' && data.entrepotsByRegion) {
        const labels = Object.keys(data.entrepotsByRegion);
        const chartData = Object.values(data.entrepotsByRegion);
        warehousesChart.data.labels = labels;
        warehousesChart.data.datasets[0].data = chartData;
        warehousesChart.update('none');
    }
    
    // Mettre à jour le graphique du carburant
    if (typeof fuelChart !== 'undefined' && data.fuelByEntrepot) {
        const labels = Object.keys(data.fuelByEntrepot);
        const chartData = Object.values(data.fuelByEntrepot);
        fuelChart.data.labels = labels;
        fuelChart.data.datasets[0].data = chartData;
        fuelChart.update('none');
    }
    
    // Mettre à jour le graphique des stocks
    if (typeof stockChart !== 'undefined' && data.stockByCategory) {
        const labels = Object.keys(data.stockByCategory);
        const chartData = Object.values(data.stockByCategory);
        stockChart.data.labels = labels;
        stockChart.data.datasets[0].data = chartData;
        stockChart.update('none');
    }
}

// Fonction pour mettre à jour la carte
function updateMap(mapData) {
    if (typeof warehouseMap !== 'undefined' && mapData) {
        currentMapData = mapData;
        renderMapMarkers(mapData);
        updateMapStats(mapData);
    }
}

// Initialiser la carte et les mises à jour automatiques
document.addEventListener('DOMContentLoaded', function() {
    initWarehouseMap();
    
    // Mise à jour automatique toutes les 30 secondes
    setInterval(updateStats, 30000);
    
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

// Générer un rapport
function generateReport(type = 'dashboard', format = 'pdf') {
    console.log('Début de génération du rapport...');
    
    // Afficher un indicateur de chargement
    showToast('Génération du rapport en cours...', 'info');
    
    // Message de debug visible
    alert('Début de génération du rapport - Vérifiez la console (F12) pour les détails');
    
    // Vérifier que la route existe
    const url = '{{ route("admin.dashboard.generate-report") }}';
    console.log('URL de la route:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: type,
            format: format,
            period: 'month'
        })
    })
    .then(response => {
        console.log('Réponse reçue:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            showToast('Rapport généré avec succès!', 'success');
            // Télécharger le rapport
            if (data.download_url) {
                console.log('Téléchargement du fichier:', data.download_url);
                // Créer un lien de téléchargement temporaire
                const link = document.createElement('a');
                link.href = data.download_url;
                link.download = data.filename || 'rapport.pdf';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            } else {
                showToast('Rapport généré mais URL de téléchargement manquante', 'warning');
            }
        } else {
            showToast('Erreur lors de la génération du rapport: ' + (data.message || 'Erreur inconnue'), 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);
        showToast('Erreur lors de la génération du rapport: ' + error.message, 'danger');
    });
}


// Générer un rapport avec options
function generateReportWithOptions() {
    const type = document.getElementById('report-type')?.value || 'dashboard';
    const format = document.getElementById('report-format')?.value || 'pdf';
    const period = document.getElementById('report-period')?.value || 'month';
    
    generateReport(type, format, period);
}
</script>
@endpush