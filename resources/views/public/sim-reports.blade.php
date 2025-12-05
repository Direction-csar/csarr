@extends('layouts.public')

@section('title', 'Rapports SIM - CSAR')

@section('content')
<div class="container-fluid py-5">
    <!-- En-tête -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold text-primary mb-3">
                <i class="fas fa-chart-line me-3"></i>Rapports SIM
            </h1>
            <p class="lead text-muted">
                Consultez les rapports d'information sur les marchés (SIM) du CSAR
            </p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('sim-reports.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Titre ou description...">
                        </div>
                        <div class="col-md-2">
                            <label for="report_type" class="form-label">Type</label>
                            <select class="form-select" id="report_type" name="report_type">
                                <option value="">Tous les types</option>
                                <option value="financial" {{ request('report_type') == 'financial' ? 'selected' : '' }}>Financier</option>
                                <option value="operational" {{ request('report_type') == 'operational' ? 'selected' : '' }}>Opérationnel</option>
                                <option value="inventory" {{ request('report_type') == 'inventory' ? 'selected' : '' }}>Inventaire</option>
                                <option value="personnel" {{ request('report_type') == 'personnel' ? 'selected' : '' }}>Personnel</option>
                                <option value="general" {{ request('report_type') == 'general' ? 'selected' : '' }}>Général</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="region" class="form-label">Région</label>
                            <select class="form-select" id="region" name="region">
                                <option value="">Toutes les régions</option>
                                <option value="dakar" {{ request('region') == 'dakar' ? 'selected' : '' }}>Dakar</option>
                                <option value="thies" {{ request('region') == 'thies' ? 'selected' : '' }}>Thiès</option>
                                <option value="kaolack" {{ request('region') == 'kaolack' ? 'selected' : '' }}>Kaolack</option>
                                <option value="saint-louis" {{ request('region') == 'saint-louis' ? 'selected' : '' }}>Saint-Louis</option>
                                <option value="ziguinchor" {{ request('region') == 'ziguinchor' ? 'selected' : '' }}>Ziguinchor</option>
                                <option value="tambacounda" {{ request('region') == 'tambacounda' ? 'selected' : '' }}>Tambacounda</option>
                                <option value="kolda" {{ request('region') == 'kolda' ? 'selected' : '' }}>Kolda</option>
                                <option value="matam" {{ request('region') == 'matam' ? 'selected' : '' }}>Matam</option>
                                <option value="kaffrine" {{ request('region') == 'kaffrine' ? 'selected' : '' }}>Kaffrine</option>
                                <option value="sedhiou" {{ request('region') == 'sedhiou' ? 'selected' : '' }}>Sédhiou</option>
                                <option value="kedougou" {{ request('region') == 'kedougou' ? 'selected' : '' }}>Kédougou</option>
                                <option value="fatick" {{ request('region') == 'fatick' ? 'selected' : '' }}>Fatick</option>
                                <option value="diourbel" {{ request('region') == 'diourbel' ? 'selected' : '' }}>Diourbel</option>
                                <option value="louga" {{ request('region') == 'louga' ? 'selected' : '' }}>Louga</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="market_sector" class="form-label">Secteur</label>
                            <select class="form-select" id="market_sector" name="market_sector">
                                <option value="">Tous les secteurs</option>
                                <option value="agriculture" {{ request('market_sector') == 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                                <option value="livestock" {{ request('market_sector') == 'livestock' ? 'selected' : '' }}>Élevage</option>
                                <option value="fisheries" {{ request('market_sector') == 'fisheries' ? 'selected' : '' }}>Pêche</option>
                                <option value="manufacturing" {{ request('market_sector') == 'manufacturing' ? 'selected' : '' }}>Industrie</option>
                                <option value="services" {{ request('market_sector') == 'services' ? 'selected' : '' }}>Services</option>
                                <option value="trade" {{ request('market_sector') == 'trade' ? 'selected' : '' }}>Commerce</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('sim-reports.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Effacer
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rapports -->
    <div class="row">
        @if(count($reports) > 0)
            @foreach($reports as $report)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm hover-shadow">
                        <!-- Image de couverture -->
                        @if($report->cover_image)
                            <img src="{{ asset('storage/' . $report->cover_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $report->title }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <div class="text-center text-white">
                                    <i class="fas fa-chart-line fa-3x mb-2"></i>
                                    <h6>{{ $report->title }}</h6>
                                </div>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-{{ $report->report_type === 'financial' ? 'success' : ($report->report_type === 'operational' ? 'info' : ($report->report_type === 'inventory' ? 'warning' : ($report->report_type === 'personnel' ? 'secondary' : 'primary'))) }}">
                                    {{ ucfirst($report->report_type) }}
                                </span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $report->published_at ? \Carbon\Carbon::parse($report->published_at)->format('d/m/Y') : 'N/A' }}
                                </small>
                            </div>
                            
                            <h5 class="card-title text-primary mb-3">
                                {{ $report->title }}
                            </h5>
                            
                            @if($report->description)
                                <p class="card-text text-muted flex-grow-1">
                                    {{ Str::limit($report->description, 120) }}
                                </p>
                            @endif
                            
                            <div class="mt-auto">
                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted d-block">Vues</small>
                                        <strong class="text-primary">{{ $report->view_count ?? 0 }}</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Téléchargements</small>
                                        <strong class="text-success">{{ $report->download_count ?? 0 }}</strong>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted d-block">Taille</small>
                                        <strong class="text-info">{{ $report->file_size ? number_format($report->file_size / 1024 / 1024, 2) . ' MB' : 'N/A' }}</strong>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('sim-reports.show', $report->id) }}" 
                                       class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-2"></i>Consulter
                                    </a>
                                    
                                    @if($report->document_file)
                                        <a href="{{ route('sim-reports.download', $report->id) }}" 
                                           class="btn btn-success">
                                            <i class="fas fa-{{ $report->document_file ? (pathinfo($report->document_file, PATHINFO_EXTENSION) === 'pdf' ? 'file-pdf' : (in_array(pathinfo($report->document_file, PATHINFO_EXTENSION), ['ppt', 'pptx']) ? 'file-powerpoint' : (in_array(pathinfo($report->document_file, PATHINFO_EXTENSION), ['doc', 'docx']) ? 'file-word' : (in_array(pathinfo($report->document_file, PATHINFO_EXTENSION), ['xls', 'xlsx']) ? 'file-excel' : 'download')))) : 'download' }} me-2"></i>
                                            Télécharger {{ strtoupper(pathinfo($report->document_file, PATHINFO_EXTENSION)) }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="icon-3d mb-4" style="background: var(--gradient-secondary); width: 100px; height: 100px; margin: 0 auto;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4 class="text-muted mb-3">Aucune donnée disponible pour le moment</h4>
                    <p class="text-muted mb-4">
                        Les rapports d'information sur les marchés (SIM) seront publiés prochainement. 
                        Veuillez consulter régulièrement cette page pour les dernières informations.
                    </p>
                    <a href="{{ route('sim-reports.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-2"></i>Voir tous les rapports
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination (désactivée pour la version simplifiée) -->
    {{-- @if($reports->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="Pagination des rapports SIM">
                    {{ $reports->links() }}
                </nav>
            </div>
        </div>
    @endif --}}
</div>

<!-- Section d'information -->
<div class="container-fluid bg-light py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h3 class="mb-4">À propos des rapports SIM</h3>
                <p class="lead text-muted mb-4">
                    Les rapports d'information sur les marchés (SIM) du CSAR fournissent des données 
                    essentielles sur l'état des marchés agricoles, les prix des produits, 
                    l'approvisionnement et les tendances régionales.
                </p>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <i class="fas fa-chart-bar fa-2x text-primary mb-2"></i>
                            <h6>Données fiables</h6>
                            <small class="text-muted">Informations vérifiées et actualisées</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <i class="fas fa-map-marked-alt fa-2x text-success mb-2"></i>
                            <h6>Couverture nationale</h6>
                            <small class="text-muted">Données de toutes les régions du Sénégal</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <i class="fas fa-clock fa-2x text-info mb-2"></i>
                            <h6>Mise à jour régulière</h6>
                            <small class="text-muted">Rapports quotidiens, hebdomadaires et mensuels</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.icon-3d {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.card {
    border: none;
    border-radius: 15px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
}
</style>
@endpush