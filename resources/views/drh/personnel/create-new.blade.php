@extends('layouts.drh')

@section('title', 'Ajouter du Personnel - DRH')

@section('content')
<div class="drh-interface">
    <!-- Page Title -->
    <div class="drh-page-title">
        <div class="flex justify-between items-center">
            <div>
                <h1>Ajouter du Personnel</h1>
                <p>Enregistrement d'un nouveau membre du personnel CSAR</p>
            </div>
            <a href="{{ route('drh.personnel.index') }}" class="drh-btn drh-btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <form action="{{ route('drh.personnel.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Informations personnelles -->
        <div class="drh-card drh-fade-in mb-6">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-user"></i>
                    Informations Personnelles
                </div>
            </div>
            <div class="drh-card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="drh-form-group">
                        <label class="drh-form-label required">Nom et Prénoms</label>
                        <input type="text" name="prenoms_nom" value="{{ old('prenoms_nom') }}" 
                               class="drh-form-input @error('prenoms_nom') error @enderror" required>
                        @error('prenoms_nom')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" 
                               class="drh-form-input @error('date_naissance') error @enderror">
                        @error('date_naissance')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Lieu de naissance</label>
                        <input type="text" name="lieu_naissance" value="{{ old('lieu_naissance') }}" 
                               class="drh-form-input @error('lieu_naissance') error @enderror">
                        @error('lieu_naissance')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Sexe</label>
                        <select name="sexe" class="drh-form-input @error('sexe') error @enderror">
                            <option value="">Sélectionner...</option>
                            <option value="Masculin" {{ old('sexe') == 'Masculin' ? 'selected' : '' }}>Masculin</option>
                            <option value="Féminin" {{ old('sexe') == 'Féminin' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('sexe')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Situation matrimoniale</label>
                        <select name="situation_matrimoniale" class="drh-form-input @error('situation_matrimoniale') error @enderror">
                            <option value="">Sélectionner...</option>
                            <option value="Célibataire" {{ old('situation_matrimoniale') == 'Célibataire' ? 'selected' : '' }}>Célibataire</option>
                            <option value="Marié(e)" {{ old('situation_matrimoniale') == 'Marié(e)' ? 'selected' : '' }}>Marié(e)</option>
                            <option value="Divorcé(e)" {{ old('situation_matrimoniale') == 'Divorcé(e)' ? 'selected' : '' }}>Divorcé(e)</option>
                            <option value="Veuf/Veuve" {{ old('situation_matrimoniale') == 'Veuf/Veuve' ? 'selected' : '' }}>Veuf/Veuve</option>
                        </select>
                        @error('situation_matrimoniale')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Nationalité</label>
                        <input type="text" name="nationalite" value="{{ old('nationalite', 'Sénégalaise') }}" 
                               class="drh-form-input @error('nationalite') error @enderror">
                        @error('nationalite')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Numéro CNI</label>
                        <input type="text" name="numero_cni" value="{{ old('numero_cni') }}" 
                               class="drh-form-input @error('numero_cni') error @enderror">
                        @error('numero_cni')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Groupe sanguin</label>
                        <select name="groupe_sanguin" class="drh-form-input @error('groupe_sanguin') error @enderror">
                            <option value="">Sélectionner...</option>
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
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Photo personnelle</label>
                        <input type="file" name="photo_personnelle" accept="image/*" 
                               class="drh-form-input @error('photo_personnelle') error @enderror">
                        @error('photo_personnelle')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="drh-form-help">
                            Formats acceptés : JPEG, PNG, JPG (max 5MB)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de contact -->
        <div class="drh-card drh-fade-in mb-6">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-address-book"></i>
                    Informations de Contact
                </div>
            </div>
            <div class="drh-card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="drh-form-group">
                        <label class="drh-form-label required">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="drh-form-input @error('email') error @enderror" required>
                        @error('email')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label required">Téléphone</label>
                        <input type="tel" name="contact_telephonique" value="{{ old('contact_telephonique') }}" 
                               class="drh-form-input @error('contact_telephonique') error @enderror" required>
                        @error('contact_telephonique')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group md:col-span-2">
                        <label class="drh-form-label">Adresse complète</label>
                        <textarea name="adresse_complete" rows="3" 
                                  class="drh-form-input @error('adresse_complete') error @enderror">{{ old('adresse_complete') }}</textarea>
                        @error('adresse_complete')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Contact d'urgence - Nom</label>
                        <input type="text" name="contact_urgence_nom" value="{{ old('contact_urgence_nom') }}" 
                               class="drh-form-input @error('contact_urgence_nom') error @enderror">
                        @error('contact_urgence_nom')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Contact d'urgence - Téléphone</label>
                        <input type="tel" name="contact_urgence_telephone" value="{{ old('contact_urgence_telephone') }}" 
                               class="drh-form-input @error('contact_urgence_telephone') error @enderror">
                        @error('contact_urgence_telephone')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Lien de parenté</label>
                        <input type="text" name="contact_urgence_lien_parente" value="{{ old('contact_urgence_lien_parente') }}" 
                               class="drh-form-input @error('contact_urgence_lien_parente') error @enderror">
                        @error('contact_urgence_lien_parente')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="drh-card drh-fade-in mb-6">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-briefcase"></i>
                    Informations Professionnelles
                </div>
            </div>
            <div class="drh-card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="drh-form-group">
                        <label class="drh-form-label required">Poste actuel</label>
                        <input type="text" name="poste_actuel" value="{{ old('poste_actuel') }}" 
                               class="drh-form-input @error('poste_actuel') error @enderror" required>
                        @error('poste_actuel')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label required">Direction/Service</label>
                        <select name="direction_service" class="drh-form-input @error('direction_service') error @enderror" required>
                            <option value="">Sélectionner...</option>
                            <option value="Direction Générale" {{ old('direction_service') == 'Direction Générale' ? 'selected' : '' }}>Direction Générale</option>
                            <option value="Direction des Ressources Humaines" {{ old('direction_service') == 'Direction des Ressources Humaines' ? 'selected' : '' }}>Direction des Ressources Humaines</option>
                            <option value="Direction Financière" {{ old('direction_service') == 'Direction Financière' ? 'selected' : '' }}>Direction Financière</option>
                            <option value="Direction Technique" {{ old('direction_service') == 'Direction Technique' ? 'selected' : '' }}>Direction Technique</option>
                            <option value="Direction Commerciale" {{ old('direction_service') == 'Direction Commerciale' ? 'selected' : '' }}>Direction Commerciale</option>
                            <option value="Service Entrepôt" {{ old('direction_service') == 'Service Entrepôt' ? 'selected' : '' }}>Service Entrepôt</option>
                        </select>
                        @error('direction_service')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Statut</label>
                        <select name="statut" class="drh-form-input @error('statut') error @enderror">
                            <option value="">Sélectionner...</option>
                            <option value="Actif" {{ old('statut') == 'Actif' ? 'selected' : '' }}>Actif</option>
                            <option value="En congé" {{ old('statut') == 'En congé' ? 'selected' : '' }}>En congé</option>
                            <option value="Suspendu" {{ old('statut') == 'Suspendu' ? 'selected' : '' }}>Suspendu</option>
                            <option value="Démissionnaire" {{ old('statut') == 'Démissionnaire' ? 'selected' : '' }}>Démissionnaire</option>
                        </select>
                        @error('statut')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Date de prise de service</label>
                        <input type="date" name="date_prise_service_csar" value="{{ old('date_prise_service_csar') }}" 
                               class="drh-form-input @error('date_prise_service_csar') error @enderror">
                        @error('date_prise_service_csar')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Date de recrutement</label>
                        <input type="date" name="date_recrutement_csar" value="{{ old('date_recrutement_csar') }}" 
                               class="drh-form-input @error('date_recrutement_csar') error @enderror">
                        @error('date_recrutement_csar')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Diplôme académique</label>
                        <input type="text" name="diplome_academique" value="{{ old('diplome_academique') }}" 
                               class="drh-form-input @error('diplome_academique') error @enderror">
                        @error('diplome_academique')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group md:col-span-2">
                        <label class="drh-form-label">Formations professionnelles</label>
                        <textarea name="formations_professionnelles" rows="3" 
                                  class="drh-form-input @error('formations_professionnelles') error @enderror">{{ old('formations_professionnelles') }}</textarea>
                        @error('formations_professionnelles')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Logiciels maîtrisés</label>
                        <input type="text" name="logiciels_maitrises" value="{{ old('logiciels_maitrises') }}" 
                               class="drh-form-input @error('logiciels_maitrises') error @enderror">
                        @error('logiciels_maitrises')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Langues parlées</label>
                        <input type="text" name="langues_parlees" value="{{ old('langues_parlees') }}" 
                               class="drh-form-input @error('langues_parlees') error @enderror">
                        @error('langues_parlees')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="drh-form-group md:col-span-3">
                        <label class="drh-form-label">Observations personnelles</label>
                        <textarea name="observations_personnelles" rows="4" 
                                  class="drh-form-input @error('observations_personnelles') error @enderror">{{ old('observations_personnelles') }}</textarea>
                        @error('observations_personnelles')
                            <div class="drh-form-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('drh.personnel.index') }}" class="drh-btn drh-btn-secondary">
                <i class="fas fa-times"></i>
                Annuler
            </a>
            <button type="submit" class="drh-btn drh-btn-primary drh-btn-lg">
                <i class="fas fa-save"></i>
                Enregistrer le personnel
            </button>
        </div>
    </form>
</div>
@endsection
