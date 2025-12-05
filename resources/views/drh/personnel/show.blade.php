@extends('layouts.drh')

@section('title', 'Fiche Personnel - DRH')

@section('content')
<div class="drh-interface">
    <!-- Page Title with Actions -->
    <div class="drh-page-title">
        <div class="flex justify-between items-center">
            <div>
                <h1>Fiche Personnel</h1>
                <p>{{ $personnel->prenoms_nom ?? 'N/A' }} - {{ $personnel->matricule ?? 'N/A' }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('drh.personnel.edit', $personnel) }}" class="drh-btn drh-btn-primary">
                    <i class="fas fa-edit"></i>
                    Modifier
                </a>
                <a href="{{ route('drh.personnel.export-fiche-pdf', $personnel) }}" class="drh-btn drh-btn-warning">
                    <i class="fas fa-file-pdf"></i>
                    Export PDF
                </a>
                <a href="{{ route('drh.personnel.index') }}" class="drh-btn drh-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Photo et infos principales -->
        <div class="lg:col-span-1">
            <div class="drh-card drh-fade-in">
                <div class="drh-card-header">
                    <div class="drh-card-title">
                        <i class="fas fa-user-circle"></i>
                        Photo de Profil
                    </div>
                </div>
                <div class="drh-card-body text-center">
                    <div class="mb-6">
                        @if($personnel->photo_personnelle)
                            <img src="{{ Storage::url('personnel/' . $personnel->photo_personnelle) }}" 
                                 alt="Photo de {{ $personnel->prenoms_nom }}" 
                                 class="w-40 h-40 rounded-full mx-auto object-cover border-4 border-gray-200 shadow-lg">
                        @else
                            <div class="w-40 h-40 rounded-full mx-auto bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center border-4 border-gray-200 shadow-lg">
                                <i class="fas fa-user text-6xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="drh-heading-3 mb-2">{{ $personnel->prenoms_nom ?? 'N/A' }}</h3>
                    <p class="drh-text-small text-gray-500 mb-4">{{ $personnel->matricule ?? 'N/A' }}</p>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="drh-text-small">Statut :</span>
                            @php
                                $statusClass = match($personnel->statut_validation) {
                                    'Valide' => 'drh-badge-success',
                                    'En attente' => 'drh-badge-warning',
                                    'Rejeté' => 'drh-badge-danger',
                                    default => 'drh-badge-secondary'
                                };
                                $statusText = $personnel->statut_validation ?? 'En attente';
                            @endphp
                            <span class="drh-badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        
                        @if($personnel->date_recrutement_csar)
                            <div class="flex justify-between items-center">
                                <span class="drh-text-small">Recruté le :</span>
                                <span class="drh-text-small">{{ \Carbon\Carbon::parse($personnel->date_recrutement_csar)->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        
                        @if($personnel->poste_actuel)
                            <div class="flex justify-between items-center">
                                <span class="drh-text-small">Poste :</span>
                                <span class="drh-text-small font-medium">{{ $personnel->poste_actuel }}</span>
                            </div>
                        @endif
                        
                        @if($personnel->direction_service)
                            <div class="flex justify-between items-center">
                                <span class="drh-text-small">Direction :</span>
                                <span class="drh-text-small">{{ $personnel->direction_service }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations détaillées -->
        <div class="lg:col-span-2">
            <div class="space-y-6">
                <!-- Informations personnelles -->
                <div class="drh-card drh-fade-in">
                    <div class="drh-card-header">
                        <div class="drh-card-title">
                            <i class="fas fa-user"></i>
                            Informations Personnelles
                        </div>
                    </div>
                    <div class="drh-card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="drh-form-label">Nom et Prénoms</label>
                                    <p class="drh-text-primary">{{ $personnel->prenoms_nom ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Date de naissance</label>
                                    <p class="drh-text-primary">
                                        @if($personnel->date_naissance)
                                            {{ \Carbon\Carbon::parse($personnel->date_naissance)->format('d/m/Y') }}
                                        @else
                                            Non renseigné
                                        @endif
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Lieu de naissance</label>
                                    <p class="drh-text-primary">{{ $personnel->lieu_naissance ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Sexe</label>
                                    <p class="drh-text-primary">{{ $personnel->sexe ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="drh-form-label">Situation matrimoniale</label>
                                    <p class="drh-text-primary">{{ $personnel->situation_matrimoniale ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Nationalité</label>
                                    <p class="drh-text-primary">{{ $personnel->nationalite ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Numéro CNI</label>
                                    <p class="drh-text-primary">{{ $personnel->numero_cni ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Groupe sanguin</label>
                                    <p class="drh-text-primary">{{ $personnel->groupe_sanguin ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="drh-card drh-fade-in">
                    <div class="drh-card-header">
                        <div class="drh-card-title">
                            <i class="fas fa-address-book"></i>
                            Informations de Contact
                        </div>
                    </div>
                    <div class="drh-card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="drh-form-label">Email</label>
                                    @if($personnel->email)
                                        <a href="mailto:{{ $personnel->email }}" class="drh-text-primary text-blue-600 hover:text-blue-800">
                                            {{ $personnel->email }}
                                        </a>
                                    @else
                                        <p class="drh-text-primary">Non renseigné</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Téléphone</label>
                                    @if($personnel->contact_telephonique)
                                        <a href="tel:{{ $personnel->contact_telephonique }}" class="drh-text-primary">
                                            {{ $personnel->contact_telephonique }}
                                        </a>
                                    @else
                                        <p class="drh-text-primary">Non renseigné</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="drh-form-label">Adresse complète</label>
                                    <p class="drh-text-primary">{{ $personnel->adresse_complete ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Contact d'urgence</label>
                                    <div class="space-y-2">
                                        <p class="drh-text-small">
                                            <strong>Nom :</strong> {{ $personnel->contact_urgence_nom ?? 'Non renseigné' }}
                                        </p>
                                        <p class="drh-text-small">
                                            <strong>Téléphone :</strong> {{ $personnel->contact_urgence_telephone ?? 'Non renseigné' }}
                                        </p>
                                        <p class="drh-text-small">
                                            <strong>Lien :</strong> {{ $personnel->contact_urgence_lien_parente ?? 'Non renseigné' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations professionnelles -->
                <div class="drh-card drh-fade-in">
                    <div class="drh-card-header">
                        <div class="drh-card-title">
                            <i class="fas fa-briefcase"></i>
                            Informations Professionnelles
                        </div>
                    </div>
                    <div class="drh-card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="drh-form-label">Poste actuel</label>
                                    <p class="drh-text-primary">{{ $personnel->poste_actuel ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Direction/Service</label>
                                    <p class="drh-text-primary">{{ $personnel->direction_service ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Statut</label>
                                    <p class="drh-text-primary">{{ $personnel->statut ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Date de prise de service</label>
                                    <p class="drh-text-primary">
                                        @if($personnel->date_prise_service_csar)
                                            {{ \Carbon\Carbon::parse($personnel->date_prise_service_csar)->format('d/m/Y') }}
                                        @else
                                            Non renseigné
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="drh-form-label">Diplôme académique</label>
                                    <p class="drh-text-primary">{{ $personnel->diplome_academique ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Formations professionnelles</label>
                                    <p class="drh-text-primary">{{ $personnel->formations_professionnelles ?? 'Non renseigné' }}</p>
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Logiciels maîtrisés</label>
                                    @if($personnel->logiciels_maitrises && is_array($personnel->logiciels_maitrises))
                                        <p class="drh-text-primary">{{ implode(', ', $personnel->logiciels_maitrises) }}</p>
                                    @elseif($personnel->logiciels_maitrises)
                                        <p class="drh-text-primary">{{ $personnel->logiciels_maitrises }}</p>
                                    @else
                                        <p class="drh-text-primary">Non renseigné</p>
                                    @endif
                                </div>
                                
                                <div>
                                    <label class="drh-form-label">Langues parlées</label>
                                    @if($personnel->langues_parlees && is_array($personnel->langues_parlees))
                                        <p class="drh-text-primary">{{ implode(', ', $personnel->langues_parlees) }}</p>
                                    @elseif($personnel->langues_parlees)
                                        <p class="drh-text-primary">{{ $personnel->langues_parlees }}</p>
                                    @else
                                        <p class="drh-text-primary">Non renseigné</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observations -->
                @if($personnel->observations_personnelles)
                <div class="drh-card drh-fade-in">
                    <div class="drh-card-header">
                        <div class="drh-card-title">
                            <i class="fas fa-sticky-note"></i>
                            Observations Personnelles
                        </div>
                    </div>
                    <div class="drh-card-body">
                        <p class="drh-text-primary">{{ $personnel->observations_personnelles }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
