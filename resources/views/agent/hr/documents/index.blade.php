@extends('layouts.agent')

@section('title', 'Mes Documents RH - Interface Agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-file-alt me-2"></i>
                    Mes Documents RH
                </h4>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('agent.hr.documents.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Type de document</label>
                            <select name="type" class="form-select">
                                <option value="">Tous les types</option>
                                <option value="contrat_travail" {{ request('type') == 'contrat_travail' ? 'selected' : '' }}>Contrat de travail</option>
                                <option value="bulletin_salaire" {{ request('type') == 'bulletin_salaire' ? 'selected' : '' }}>Bulletin de salaire</option>
                                <option value="certificat_medical" {{ request('type') == 'certificat_medical' ? 'selected' : '' }}>Certificat médical</option>
                                <option value="arret_maladie" {{ request('type') == 'arret_maladie' ? 'selected' : '' }}>Arrêt maladie</option>
                                <option value="attestation_travail" {{ request('type') == 'attestation_travail' ? 'selected' : '' }}>Attestation de travail</option>
                                <option value="certificat_formation" {{ request('type') == 'certificat_formation' ? 'selected' : '' }}>Certificat de formation</option>
                                <option value="autre" {{ request('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="expire" {{ request('statut') == 'expire' ? 'selected' : '' }}>Expiré</option>
                                <option value="archivé" {{ request('statut') == 'archivé' ? 'selected' : '' }}>Archivé</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Recherche</label>
                            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Filtrer
                            </button>
                            <a href="{{ route('agent.hr.documents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des documents -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Mes Documents</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Type</th>
                                    <th>Date d'émission</th>
                                    <th>Date d'expiration</th>
                                    <th>Statut</th>
                                    <th>Taille</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
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
                                        @if($document->date_expiration)
                                            {{ $document->date_expiration->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $document->statut === 'actif' ? 'success' : ($document->statut === 'expire' ? 'danger' : 'secondary') }}">
                                            {{ $document->statut_label }}
                                        </span>
                                    </td>
                                    <td>{{ $document->taille_formatee }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('agent.hr.documents.show', $document) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('agent.hr.documents.download', $document) }}" class="btn btn-sm btn-outline-success" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucun document trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $documents->links() }}
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
</style>
@endsection 