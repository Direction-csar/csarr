@extends('layouts.agent')

@section('title', 'Mes Bulletins de Salaire - Interface Agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Mes Bulletins de Salaire
                </h4>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('agent.hr.salary-slips.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                                <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                                <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Début période</label>
                            <input type="date" name="periode_debut" class="form-control" value="{{ request('periode_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Fin période</label>
                            <input type="date" name="periode_fin" class="form-control" value="{{ request('periode_fin') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Recherche</label>
                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('agent.hr.salary-slips.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des bulletins -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes Bulletins de Salaire</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>N° Bulletin</th>
                                    <th>Période</th>
                                    <th>Salaire Brut</th>
                                    <th>Salaire Net</th>
                                    <th>Jours Travaillés</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bulletins as $bulletin)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-invoice-dollar text-warning me-2"></i>
                                            <div>
                                                <h6 class="mb-0">{{ $bulletin->numero_bulletin }}</h6>
                                                <small class="text-muted">Créé le {{ $bulletin->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $bulletin->periode_label }}</td>
                                    <td>
                                        <span class="fw-bold">{{ number_format($bulletin->salaire_brut, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($bulletin->salaire_net, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $bulletin->jours_travailles }} jours</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $bulletin->statut === 'valide' ? 'success' : ($bulletin->statut === 'paye' ? 'info' : ($bulletin->statut === 'annule' ? 'danger' : 'warning')) }}">
                                            {{ $bulletin->statut_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('agent.hr.salary-slips.show', $bulletin) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($bulletin->fichier_pdf)
                                            <a href="{{ route('agent.hr.salary-slips.download', $bulletin) }}" class="btn btn-sm btn-outline-success" title="Télécharger PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucun bulletin trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $bulletins->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques personnelles -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques de mes salaires</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $bulletins->count() }}</h4>
                                <p class="text-muted">Total bulletins</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ number_format($bulletins->sum('salaire_net'), 0, ',', ' ') }} FCFA</h4>
                                <p class="text-muted">Total salaire net</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ number_format($bulletins->avg('salaire_net'), 0, ',', ' ') }} FCFA</h4>
                                <p class="text-muted">Moyenne salaire net</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $bulletins->where('statut', 'paye')->count() }}</h4>
                                <p class="text-muted">Bulletins payés</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0 35px 0 rgba(154,161,171,.15);
    margin-bottom: 24px;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-select, .form-control {
    border-radius: 0.375rem;
    border: 1px solid #d1d3e2;
}

.form-select:focus, .form-control:focus {
    border-color: #727cf5;
    box-shadow: 0 0 0 0.2rem rgba(114,124,245,.25);
}

.fw-bold {
    font-weight: 700 !important;
}

.text-center h4 {
    margin-bottom: 0.5rem;
}
</style>
@endsection 