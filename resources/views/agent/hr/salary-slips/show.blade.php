@extends('layouts.agent')

@section('title', 'Détails Bulletin de Salaire - Interface Agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Détails du Bulletin de Salaire
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">{{ $salarySlip->numero_bulletin }}</h5>
                        <div class="btn-group" role="group">
                            @if($salarySlip->fichier_pdf)
                            <a href="{{ route('agent.hr.salary-slips.download', $salarySlip) }}" class="btn btn-success">
                                <i class="fas fa-file-pdf me-2"></i>Télécharger PDF
                            </a>
                            @endif
                            <a href="{{ route('agent.hr.salary-slips.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Informations du bulletin -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Période</label>
                                        <p class="form-control-plaintext">{{ $salarySlip->periode_label }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Statut</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-{{ $salarySlip->statut === 'valide' ? 'success' : ($salarySlip->statut === 'paye' ? 'info' : ($salarySlip->statut === 'annule' ? 'danger' : 'warning')) }}">
                                                {{ $salarySlip->statut_label }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Salaire et indemnités -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0">Salaire et Indemnités</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label class="form-label fw-bold">Salaire brut</label>
                                                <p class="form-control-plaintext">{{ number_format($salarySlip->salaire_brut, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold">Indemnité logement</label>
                                                <p class="form-control-plaintext">{{ number_format($salarySlip->indemnite_logement, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold">Indemnité transport</label>
                                                <p class="form-control-plaintext">{{ number_format($salarySlip->indemnite_transport, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold">Indemnité fonction</label>
                                                <p class="form-control-plaintext">{{ number_format($salarySlip->indemnite_fonction, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold">Autres indemnités</label>
                                                <p class="form-control-plaintext">{{ number_format($salarySlip->autres_indemnites, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <hr>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold text-success">Total indemnités</label>
                                                <p class="form-control-plaintext text-success">{{ number_format($salarySlip->total_indemnites, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0">Déductions</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label class="form-label fw-bold">CNSS</label>
                                                <p class="form-control-plaintext">{{ number_format($salarySlip->cnss, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold">Impôt</label>
                                                <p class="form-control-plaintext">{{ number_format($salarySlip->impot, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold">Autres déductions</label>
                                                <p class="form-control-plaintext">{{ number_format($salarySlip->autres_deductions, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <hr>
                                            <div class="mb-2">
                                                <label class="form-label fw-bold text-danger">Total déductions</label>
                                                <p class="form-control-plaintext text-danger">{{ number_format($salarySlip->total_deductions, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Salaire net -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h4>Salaire Net</h4>
                                            <h2>{{ number_format($salarySlip->salaire_net, 0, ',', ' ') }} FCFA</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jours de travail -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0">Jours de travail</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <h5 class="text-success">{{ $salarySlip->jours_travailles }}</h5>
                                                        <p class="text-muted">Jours travaillés</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <h5 class="text-info">{{ $salarySlip->jours_conges }}</h5>
                                                        <p class="text-muted">Jours de congé</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-center">
                                                        <h5 class="text-warning">{{ $salarySlip->jours_absences }}</h5>
                                                        <p class="text-muted">Jours d'absence</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($salarySlip->commentaires)
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Commentaires</label>
                                        <p class="form-control-plaintext">{{ $salarySlip->commentaires }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
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

                            <!-- Informations de validation -->
                            <div class="card bg-light mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Informations de validation
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Créé par</label>
                                        <p class="form-control-plaintext">{{ $salarySlip->createur->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Date de création</label>
                                        <p class="form-control-plaintext">{{ $salarySlip->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @if($salarySlip->valide_par)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Validé par</label>
                                        <p class="form-control-plaintext">{{ $salarySlip->validateur->name }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Date de validation</label>
                                        <p class="form-control-plaintext">{{ $salarySlip->date_validation->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @endif
                                    @if($salarySlip->date_paiement)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Date de paiement</label>
                                        <p class="form-control-plaintext">{{ $salarySlip->date_paiement->format('d/m/Y H:i') }}</p>
                                    </div>
                                    @endif
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

.fw-bold {
    font-weight: 700 !important;
}

.text-center h5 {
    margin-bottom: 0.5rem;
}
</style>
@endsection 