@extends('layouts.agent')

@section('title', 'Détails Document RH - Interface Agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-file-alt me-2"></i>
                    Détails du Document RH
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">{{ $document->titre }}</h5>
                        <div class="btn-group" role="group">
                            <a href="{{ route('agent.hr.documents.download', $document) }}" class="btn btn-success">
                                <i class="fas fa-download me-2"></i>Télécharger
                            </a>
                            <a href="{{ route('agent.hr.documents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Informations du document -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Type de document</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-info">{{ $document->type_label }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Statut</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-{{ $document->statut === 'actif' ? 'success' : ($document->statut === 'expire' ? 'danger' : 'secondary') }}">
                                                {{ $document->statut_label }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Date d'émission</label>
                                        <p class="form-control-plaintext">{{ $document->date_emission->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Date d'expiration</label>
                                        <p class="form-control-plaintext">
                                            @if($document->date_expiration)
                                                {{ $document->date_expiration->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">Non définie</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($document->description)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <p class="form-control-plaintext">{{ $document->description }}</p>
                            </div>
                            @endif

                            @if($document->commentaires)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Commentaires</label>
                                <p class="form-control-plaintext">{{ $document->commentaires }}</p>
                            </div>
                            @endif

                            <!-- Informations du fichier -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nom du fichier</label>
                                        <p class="form-control-plaintext">{{ $document->fichier }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Extension</label>
                                        <p class="form-control-plaintext">{{ strtoupper($document->extension) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Taille</label>
                                        <p class="form-control-plaintext">{{ $document->taille_formatee }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Informations du personnel -->
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-user me-2"></i>Mes Informations
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Nom complet</label>
                                        <p class="form-control-plaintext">{{ $personnel->prenoms_nom }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Matricule</label>
                                        <p class="form-control-plaintext">{{ $personnel->matricule }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Poste</label>
                                        <p class="form-control-plaintext">{{ $personnel->poste_actuel }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Direction</label>
                                        <p class="form-control-plaintext">{{ $personnel->direction_service }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de création -->
                            <div class="card bg-light mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Informations de création
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Créé par</label>
                                        <p class="form-control-plaintext">{{ $document->createur->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Date de création</label>
                                        <p class="form-control-plaintext">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Dernière modification</label>
                                        <p class="form-control-plaintext">{{ $document->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
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

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control-plaintext {
    padding: 0.375rem 0;
    margin-bottom: 0;
    color: #6c757d;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.btn-group .btn {
    margin-right: 5px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
}

.card-header h6 {
    margin: 0;
    color: #495057;
}
</style>
@endsection 