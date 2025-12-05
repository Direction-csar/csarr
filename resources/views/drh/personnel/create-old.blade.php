@extends('layouts.drh')

@section('title', 'Ajouter du Personnel - DRH')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="page-title">
                        <i class="fas fa-user-plus me-2"></i>
                        Ajouter du Personnel - DRH
                    </h4>
                    <a href="{{ route('drh.personnel.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('drh.personnel.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Informations personnelles -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="card-title">Informations Personnelles</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="prenoms_nom" class="form-label">Prénoms et Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('prenoms_nom') is-invalid @enderror" 
                                           id="prenoms_nom" name="prenoms_nom" value="{{ old('prenoms_nom') }}" required>
                                    @error('prenoms_nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_naissance" class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                                    @error('date_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="lieu_naissance" class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                           id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance') }}" required>
                                    @error('lieu_naissance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tranche_age" class="form-label">Tranche d'âge <span class="text-danger">*</span></label>
                                    <select class="form-control @error('tranche_age') is-invalid @enderror" id="tranche_age" name="tranche_age" required>
                                        <option value="">Sélectionner</option>
                                        <option value="18-25" {{ old('tranche_age') == '18-25' ? 'selected' : '' }}>18-25 ans</option>
                                        <option value="26-35" {{ old('tranche_age') == '26-35' ? 'selected' : '' }}>26-35 ans</option>
                                        <option value="36-45" {{ old('tranche_age') == '36-45' ? 'selected' : '' }}>36-45 ans</option>
                                        <option value="46-55" {{ old('tranche_age') == '46-55' ? 'selected' : '' }}>46-55 ans</option>
                                        <option value="56-60" {{ old('tranche_age') == '56-60' ? 'selected' : '' }}>56-60 ans</option>
                                    </select>
                                    @error('tranche_age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nationalite" class="form-label">Nationalité <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nationalite') is-invalid @enderror" 
                                           id="nationalite" name="nationalite" value="{{ old('nationalite', 'Sénégalaise') }}" required>
                                    @error('nationalite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_cni" class="form-label">Numéro CNI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('numero_cni') is-invalid @enderror" 
                                           id="numero_cni" name="numero_cni" value="{{ old('numero_cni') }}" required>
                                    @error('numero_cni')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sexe" class="form-label">Sexe <span class="text-danger">*</span></label>
                                    <select class="form-control @error('sexe') is-invalid @enderror" id="sexe" name="sexe" required>
                                        <option value="">Sélectionner</option>
                                        <option value="Masculin" {{ old('sexe') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                                        <option value="Féminin" {{ old('sexe') == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                    @error('sexe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="situation_matrimoniale" class="form-label">Situation matrimoniale <span class="text-danger">*</span></label>
                                    <select class="form-control @error('situation_matrimoniale') is-invalid @enderror" id="situation_matrimoniale" name="situation_matrimoniale" required>
                                        <option value="">Sélectionner</option>
                                        <option value="Celibataire" {{ old('situation_matrimoniale') == 'Celibataire' ? 'selected' : '' }}>Célibataire</option>
                                        <option value="Marie" {{ old('situation_matrimoniale') == 'Marie' ? 'selected' : '' }}>Marié(e)</option>
                                        <option value="Divorce" {{ old('situation_matrimoniale') == 'Divorce' ? 'selected' : '' }}>Divorcé(e)</option>
                                        <option value="Veuf" {{ old('situation_matrimoniale') == 'Veuf' ? 'selected' : '' }}>Veuf</option>
                                        <option value="Veuve" {{ old('situation_matrimoniale') == 'Veuve' ? 'selected' : '' }}>Veuve</option>
                                    </select>
                                    @error('situation_matrimoniale')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre_enfants" class="form-label">Nombre d'enfants <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('nombre_enfants') is-invalid @enderror" 
                                           id="nombre_enfants" name="nombre_enfants" value="{{ old('nombre_enfants', 0) }}" min="0" max="10" required>
                                    @error('nombre_enfants')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="groupe_sanguin" class="form-label">Groupe sanguin <span class="text-danger">*</span></label>
                                    <select class="form-control @error('groupe_sanguin') is-invalid @enderror" id="groupe_sanguin" name="groupe_sanguin" required>
                                        <option value="">Sélectionner</option>
                                        <option value="A+" {{ old('groupe_sanguin') == 'A+' ? 'selected' : '' }}>A+</option>
                                        <option value="A-" {{ old('groupe_sanguin') == 'A-' ? 'selected' : '' }}>A-</option>
                                        <option value="B+" {{ old('groupe_sanguin') == 'B+' ? 'selected' : '' }}>B+</option>
                                        <option value="B-" {{ old('groupe_sanguin') == 'B-' ? 'selected' : '' }}>B-</option>
                                        <option value="AB+" {{ old('groupe_sanguin') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                        <option value="AB-" {{ old('groupe_sanguin') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        <option value="O+" {{ old('groupe_sanguin') == 'O+' ? 'selected' : '' }}>O+</option>
                                        <option value="O-" {{ old('groupe_sanguin') == 'O-' ? 'selected' : '' }}>O-</option>
                                    </select>
                                    @error('groupe_sanguin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_telephonique" class="form-label">Contact téléphonique <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('contact_telephonique') is-invalid @enderror" 
                                           id="contact_telephonique" name="contact_telephonique" value="{{ old('contact_telephonique') }}" required>
                                    @error('contact_telephonique')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="adresse_complete" class="form-label">Adresse complète <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('adresse_complete') is-invalid @enderror" 
                                      id="adresse_complete" name="adresse_complete" rows="3" required>{{ old('adresse_complete') }}</textarea>
                            @error('adresse_complete')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo_personnelle" class="form-label">Photo personnelle</label>
                            <input type="file" class="form-control @error('photo_personnelle') is-invalid @enderror" 
                                   id="photo_personnelle" name="photo_personnelle" accept="image/*">
                            @error('photo_personnelle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Informations professionnelles -->
                        <div class="row mb-4 mt-4">
                            <div class="col-12">
                                <h5 class="card-title">Informations Professionnelles</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_recrutement_csar" class="form-label">Date de recrutement CSAR <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_recrutement_csar') is-invalid @enderror" 
                                           id="date_recrutement_csar" name="date_recrutement_csar" value="{{ old('date_recrutement_csar') }}" required>
                                    @error('date_recrutement_csar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_prise_service_csar" class="form-label">Date de prise de service CSAR <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_prise_service_csar') is-invalid @enderror" 
                                           id="date_prise_service_csar" name="date_prise_service_csar" value="{{ old('date_prise_service_csar') }}" required>
                                    @error('date_prise_service_csar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-control @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                        <option value="">Sélectionner</option>
                                        <option value="Fonctionnaire" {{ old('statut') == 'Fonctionnaire' ? 'selected' : '' }}>Fonctionnaire</option>
                                        <option value="Contractuel" {{ old('statut') == 'Contractuel' ? 'selected' : '' }}>Contractuel</option>
                                        <option value="Stagiaire" {{ old('statut') == 'Stagiaire' ? 'selected' : '' }}>Stagiaire</option>
                                        <option value="Journalier" {{ old('statut') == 'Journalier' ? 'selected' : '' }}>Journalier</option>
                                        <option value="Autre" {{ old('statut') == 'Autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="poste_actuel" class="form-label">Poste actuel <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('poste_actuel') is-invalid @enderror" 
                                           id="poste_actuel" name="poste_actuel" value="{{ old('poste_actuel') }}" required>
                                    @error('poste_actuel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direction_service" class="form-label">Direction/Service <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('direction_service') is-invalid @enderror" 
                                           id="direction_service" name="direction_service" value="{{ old('direction_service') }}" required>
                                    @error('direction_service')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="localisation_region" class="form-label">Localisation/Région</label>
                                    <input type="text" class="form-control @error('localisation_region') is-invalid @enderror" 
                                           id="localisation_region" name="localisation_region" value="{{ old('localisation_region') }}">
                                    @error('localisation_region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="dernier_poste_avant_csar" class="form-label">Dernier poste avant CSAR</label>
                            <input type="text" class="form-control @error('dernier_poste_avant_csar') is-invalid @enderror" 
                                   id="dernier_poste_avant_csar" name="dernier_poste_avant_csar" value="{{ old('dernier_poste_avant_csar') }}">
                            @error('dernier_poste_avant_csar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('drh.personnel.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
