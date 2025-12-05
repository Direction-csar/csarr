@extends('layouts.drh')

@section('title', 'Fiche Personnel - DRH')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="page-title">
                        <i class="fas fa-user me-2"></i>
                        Fiche Personnel - DRH
                    </h4>
                    <div>
                        <a href="{{ route('drh.personnel.edit', $personnel) }}" class="btn btn-success">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('drh.personnel.export-fiche-pdf', $personnel) }}" class="btn btn-info">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                        <a href="{{ route('drh.personnel.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Photo et infos principales -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($personnel->photo_personnelle)
                        <img src="{{ Storage::url('personnel/' . $personnel->photo_personnelle) }}" 
                             alt="Photo" class="rounded-circle mb-3" width="150" height="150">
                    @else
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $personnel->prenoms_nom }}</h4>
                    <p class="text-muted mb-3">{{ $personnel->matricule }}</p>
                    
                    <div class="mb-3">
                        <span class="badge bg-{{ $personnel->statut_validation == 'Valide' ? 'success' : 'warning' }} fs-6">
                            {{ $personnel->statut_validation ?? 'En attente' }}
                        </span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('drh.personnel.edit', $personnel) }}" class="btn btn-success">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('drh.personnel.export-fiche-pdf', $personnel) }}" class="btn btn-info">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                        @if($personnel->photo_personnelle)
                        <form action="{{ route('drh.personnel.deletePhoto', $personnel) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('Supprimer la photo ?')">
                                <i class="fas fa-trash me-2"></i>Supprimer photo
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations détaillées -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations Personnelles</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date de naissance:</strong> {{ $personnel->date_naissance->format('d/m/Y') }}</p>
                            <p><strong>Lieu de naissance:</strong> {{ $personnel->lieu_naissance }}</p>
                            <p><strong>Nationalité:</strong> {{ $personnel->nationalite }}</p>
                            <p><strong>Sexe:</strong> {{ $personnel->sexe }}</p>
                            <p><strong>Situation matrimoniale:</strong> {{ $personnel->situation_matrimoniale }}</p>
                            <p><strong>Nombre d'enfants:</strong> {{ $personnel->nombre_enfants }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Numéro CNI:</strong> {{ $personnel->numero_cni }}</p>
                            <p><strong>Contact téléphonique:</strong> {{ $personnel->contact_telephonique }}</p>
                            <p><strong>Email:</strong> {{ $personnel->email }}</p>
                            <p><strong>Groupe sanguin:</strong> {{ $personnel->groupe_sanguin }}</p>
                            <p><strong>Tranche d'âge:</strong> {{ $personnel->tranche_age }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5 class="card-title">Adresse</h5>
                    <p>{{ $personnel->adresse_complete }}</p>

                    <hr>

                    <h5 class="card-title">Informations Professionnelles</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date de recrutement CSAR:</strong> {{ $personnel->date_recrutement_csar->format('d/m/Y') }}</p>
                            <p><strong>Date de prise de service:</strong> {{ $personnel->date_prise_service_csar->format('d/m/Y') }}</p>
                            <p><strong>Statut:</strong> {{ $personnel->statut }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Poste actuel:</strong> {{ $personnel->poste_actuel }}</p>
                            <p><strong>Direction/Service:</strong> {{ $personnel->direction_service }}</p>
                            <p><strong>Localisation:</strong> {{ $personnel->localisation_region ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>

                    @if($personnel->dernier_poste_avant_csar)
                    <p><strong>Dernier poste avant CSAR:</strong> {{ $personnel->dernier_poste_avant_csar }}</p>
                    @endif

                    <hr>

                    <h5 class="card-title">Historique</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Créé le:</strong> {{ $personnel->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Modifié le:</strong> {{ $personnel->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    @if($personnel->validateur)
                    <p><strong>Validé par:</strong> {{ $personnel->validateur->name }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
