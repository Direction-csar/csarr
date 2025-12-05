@extends('layouts.dg-modern')

@section('title', 'Consultation des Demandes')
@section('page-title', 'Demandes CSAR - Vue DG')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">
                            <i class="fas fa-clipboard-list me-2 text-primary"></i>
                            Consultation des Demandes
                        </h1>
                        <p class="text-muted mb-0 small">
                            Vue d'ensemble des demandes CSAR - {{ now()->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary-modern btn-modern btn-sm" onclick="refreshData()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                        <button class="btn btn-success-modern btn-modern btn-sm" onclick="exportRequests()">
                            <i class="fas fa-file-excel me-1"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques des demandes -->
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-primary); width: 50px; height: 50px;">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number">{{ $stats['total'] ?? 0 }}</h3>
                        <p class="stats-label">📋 Total Demandes</p>
                        <small class="text-muted">Toutes catégories</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-warning); width: 50px; height: 50px;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number">{{ $stats['pending'] ?? 0 }}</h3>
                        <p class="stats-label">⏳ En Attente</p>
                        <small class="text-warning">Nécessite attention</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-success); width: 50px; height: 50px;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number">{{ $stats['approved'] ?? 0 }}</h3>
                        <p class="stats-label">✅ Approuvées</p>
                        <small class="text-success">Traitées</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stats-card">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="background: var(--gradient-danger); width: 50px; height: 50px;">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="stats-number">{{ $stats['rejected'] ?? 0 }}</h3>
                        <p class="stats-label">❌ Rejetées</p>
                        <small class="text-danger">Non éligibles</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-2">
                        <label class="form-label small fw-bold">Rechercher</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Nom, email, objet..." id="searchInput">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-2">
                        <label class="form-label small fw-bold">Statut</label>
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">Tous les statuts</option>
                            <option value="pending">En attente</option>
                            <option value="approved">Approuvé</option>
                            <option value="rejected">Rejeté</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-2">
                        <label class="form-label small fw-bold">Type</label>
                        <select class="form-select form-select-sm" id="typeFilter">
                            <option value="">Tous les types</option>
                            <option value="aide_alimentaire">Aide Alimentaire</option>
                            <option value="aide_urgence">Aide Urgence</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-2">
                        <label class="form-label small fw-bold">Date</label>
                        <select class="form-select form-select-sm" id="dateFilter">
                            <option value="">Toutes les dates</option>
                            <option value="today">Aujourd'hui</option>
                            <option value="week">Cette semaine</option>
                            <option value="month">Ce mois</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-2 d-flex align-items-end">
                        <button class="btn btn-primary-modern btn-modern btn-sm me-2" onclick="applyFilters()">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Effacer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="row">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Liste des Demandes
                    </h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary">{{ $requests->count() }} résultat(s)</span>
                    </div>
                </div>
                
                @if($requests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Demandeur</th>
                                    <th>Type</th>
                                    <th>Objet</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
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
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $request->subject ?? 'N/A' }}">
                                            {{ $request->subject ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success">Approuvé</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="badge bg-danger">Rejeté</span>
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
                        <h5 class="text-muted">Aucune demande trouvée</h5>
                        <p class="text-muted">Aucune demande ne correspond aux critères de recherche</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function refreshData() {
        location.reload();
    }

    function exportRequests() {
        alert('Export des demandes en cours...');
    }

    function applyFilters() {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const type = document.getElementById('typeFilter').value;
        const date = document.getElementById('dateFilter').value;
        
        // Ici vous pouvez ajouter la logique de filtrage
        console.log('Filtres appliqués:', { search, status, type, date });
    }

    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('typeFilter').value = '';
        document.getElementById('dateFilter').value = '';
    }
</script>
@endsection