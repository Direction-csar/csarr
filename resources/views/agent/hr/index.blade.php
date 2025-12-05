@extends('layouts.agent')

@section('title', 'Module RH - Interface Agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-users-cog me-2"></i>
                    Mon Espace RH
                </h4>
            </div>
        </div>
    </div>

    <!-- Statistiques personnelles -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Documents Actifs">Documents Actifs</h5>
                            <h3 class="mt-3 mb-3">{{ $stats['documents_actifs'] }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="fas fa-file-alt font-20 text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Documents Expirés">Documents Expirés</h5>
                            <h3 class="mt-3 mb-3">{{ $stats['documents_expires'] }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded">
                                <i class="fas fa-exclamation-triangle font-20 text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Bulletins ce mois">Bulletins ce mois</h5>
                            <h3 class="mt-3 mb-3">{{ $stats['bulletins_ce_mois'] }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded">
                                <i class="fas fa-money-bill-wave font-20 text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Présences ce mois">Présences ce mois</h5>
                            <h3 class="mt-3 mb-3">{{ $stats['presences_ce_mois'] }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded">
                                <i class="fas fa-clock font-20 text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Accès rapide</h5>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('agent.hr.documents.index') }}" class="btn btn-primary btn-block w-100">
                                <i class="fas fa-file-alt me-2"></i>Mes Documents
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('agent.hr.salary-slips.index') }}" class="btn btn-success btn-block w-100">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Mes Bulletins
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('agent.hr.attendance.index') }}" class="btn btn-info btn-block w-100">
                                <i class="fas fa-clock me-2"></i>Ma Présence
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('agent.hr.statistics') }}" class="btn btn-warning btn-block w-100">
                                <i class="fas fa-chart-bar me-2"></i>Mes Statistiques
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents récents -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Mes Documents Récents</h5>
                        <a href="{{ route('agent.hr.documents.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documentsRecents as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            <div>
                                                <h6 class="mb-0">{{ $document->titre }}</h6>
                                                <small class="text-muted">{{ $document->description }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $document->type_label }}</span>
                                    </td>
                                    <td>{{ $document->date_emission->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $document->statut === 'actif' ? 'success' : ($document->statut === 'expire' ? 'danger' : 'secondary') }}">
                                            {{ $document->statut_label }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun document récent</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulletins récents -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Mes Bulletins Récents</h5>
                        <a href="{{ route('agent.hr.salary-slips.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Bulletin</th>
                                    <th>Période</th>
                                    <th>Salaire Net</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bulletinsRecents as $bulletin)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-file-invoice-dollar text-warning me-2"></i>
                                            <div>
                                                <h6 class="mb-0">{{ $bulletin->numero_bulletin }}</h6>
                                                <small class="text-muted">{{ $bulletin->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $bulletin->periode_label }}</td>
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($bulletin->salaire_net, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $bulletin->statut === 'valide' ? 'success' : ($bulletin->statut === 'paye' ? 'info' : 'warning') }}">
                                            {{ $bulletin->statut_label }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun bulletin récent</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations personnelles -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Mes Informations</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nom complet</label>
                                <p class="form-control-plaintext">{{ $personnel->prenoms_nom }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Matricule</label>
                                <p class="form-control-plaintext">{{ $personnel->matricule }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Poste actuel</label>
                                <p class="form-control-plaintext">{{ $personnel->poste_actuel }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Direction/Service</label>
                                <p class="form-control-plaintext">{{ $personnel->direction_service }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de recrutement</label>
                                <p class="form-control-plaintext">{{ $personnel->date_recrutement_csar->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Statut</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $personnel->statut_validation === 'Validé' ? 'success' : ($personnel->statut_validation === 'En attente' ? 'warning' : 'danger') }}">
                                        {{ $personnel->statut_validation }}
                                    </span>
                                </p>
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

.avatar-sm {
    height: 3rem;
    width: 3rem;
}

.avatar-title {
    align-items: center;
    display: flex;
    font-weight: 500;
    height: 100%;
    justify-content: center;
    width: 100%;
}

.bg-soft-primary {
    background-color: rgba(114,124,245,.25)!important;
}

.bg-soft-success {
    background-color: rgba(10,207,151,.25)!important;
}

.bg-soft-info {
    background-color: rgba(57,175,209,.25)!important;
}

.bg-soft-warning {
    background-color: rgba(255,188,0,.25)!important;
}

.text-primary {
    color: #727cf5!important;
}

.text-success {
    color: #0acf97!important;
}

.text-info {
    color: #39afd1!important;
}

.text-warning {
    color: #ffbc00!important;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
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

.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    color: #6c757d;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

.fw-bold {
    font-weight: 700 !important;
}
</style>
@endsection 