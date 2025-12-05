@extends('layouts.admin')

@section('title', 'Modifier Entrepôt')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 fw-bold">
                            <div class="icon-3d me-3" style="width: 50px; height: 50px; background: var(--gradient-warning); display: inline-flex; align-items: center; justify-content: center; border-radius: 12px;">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            🏢 Modifier Entrepôt
                        </h2>
                        <p class="text-muted mb-0">Modifier les informations de l'entrepôt : {{ $entrepot->nom }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.entrepots.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <form action="{{ route('admin.entrepots.update', $entrepot->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <!-- Informations générales -->
                        <div class="col-lg-6 mb-3">
                            <h6 class="fw-bold mb-3">📋 Informations Générales</h6>
                            
                            <div class="mb-3">
                                <label for="nom" class="form-label small fw-bold">Nom de l'entrepôt *</label>
                                <input type="text" class="form-control form-control-sm" id="nom" name="nom" value="{{ old('nom', $entrepot->nom) }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="region" class="form-label small fw-bold">Région *</label>
                                <select class="form-select form-select-sm" id="region" name="region" required>
                                    <option value="">Sélectionner une région</option>
                                    <option value="Dakar" {{ old('region', $entrepot->region) == 'Dakar' ? 'selected' : '' }}>Dakar</option>
                                    <option value="Thiès" {{ old('region', $entrepot->region) == 'Thiès' ? 'selected' : '' }}>Thiès</option>
                                    <option value="Kaolack" {{ old('region', $entrepot->region) == 'Kaolack' ? 'selected' : '' }}>Kaolack</option>
                                    <option value="Saint-Louis" {{ old('region', $entrepot->region) == 'Saint-Louis' ? 'selected' : '' }}>Saint-Louis</option>
                                    <option value="Ziguinchor" {{ old('region', $entrepot->region) == 'Ziguinchor' ? 'selected' : '' }}>Ziguinchor</option>
                                    <option value="Diourbel" {{ old('region', $entrepot->region) == 'Diourbel' ? 'selected' : '' }}>Diourbel</option>
                                    <option value="Tambacounda" {{ old('region', $entrepot->region) == 'Tambacounda' ? 'selected' : '' }}>Tambacounda</option>
                                    <option value="Kolda" {{ old('region', $entrepot->region) == 'Kolda' ? 'selected' : '' }}>Kolda</option>
                                    <option value="Fatick" {{ old('region', $entrepot->region) == 'Fatick' ? 'selected' : '' }}>Fatick</option>
                                    <option value="Louga" {{ old('region', $entrepot->region) == 'Louga' ? 'selected' : '' }}>Louga</option>
                                    <option value="Kédougou" {{ old('region', $entrepot->region) == 'Kédougou' ? 'selected' : '' }}>Kédougou</option>
                                    <option value="Sédhiou" {{ old('region', $entrepot->region) == 'Sédhiou' ? 'selected' : '' }}>Sédhiou</option>
                                    <option value="Matam" {{ old('region', $entrepot->region) == 'Matam' ? 'selected' : '' }}>Matam</option>
                                    <option value="Kaffrine" {{ old('region', $entrepot->region) == 'Kaffrine' ? 'selected' : '' }}>Kaffrine</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="ville" class="form-label small fw-bold">Ville *</label>
                                <input type="text" class="form-control form-control-sm" id="ville" name="ville" value="{{ old('ville', $entrepot->ville ?? '') }}" placeholder="Ex: Dakar, Tambacounda, etc." required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="adresse" class="form-label small fw-bold">Adresse complète *</label>
                                <textarea class="form-control form-control-sm" id="adresse" name="adresse" rows="3" required>{{ old('adresse', $entrepot->adresse) }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Capacité et caractéristiques -->
                        <div class="col-lg-6 mb-3">
                            <h6 class="fw-bold mb-3">📦 Capacité et Caractéristiques</h6>
                            
                            <div class="mb-3">
                                <label for="capacite" class="form-label small fw-bold">Capacité (unités) *</label>
                                <input type="number" class="form-control form-control-sm" id="capacite" name="capacite" value="{{ old('capacite', $entrepot->capacite ?? $entrepot->capacite_max ?? 0) }}" min="1" step="0.01" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="unite_capacite" class="form-label small fw-bold">Unité de capacité *</label>
                                <select class="form-select form-select-sm" id="unite_capacite" name="unite_capacite" required>
                                    <option value="">Sélectionner une unité</option>
                                    <option value="m²" {{ old('unite_capacite', $entrepot->unite_capacite ?? '') == 'm²' ? 'selected' : '' }}>m² (mètres carrés)</option>
                                    <option value="m³" {{ old('unite_capacite', $entrepot->unite_capacite ?? '') == 'm³' ? 'selected' : '' }}>m³ (mètres cubes)</option>
                                    <option value="litres" {{ old('unite_capacite', $entrepot->unite_capacite ?? '') == 'litres' ? 'selected' : '' }}>Litres</option>
                                    <option value="tonnes" {{ old('unite_capacite', $entrepot->unite_capacite ?? '') == 'tonnes' ? 'selected' : '' }}>Tonnes</option>
                                    <option value="palettes" {{ old('unite_capacite', $entrepot->unite_capacite ?? '') == 'palettes' ? 'selected' : '' }}>Palettes</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="type" class="form-label small fw-bold">Type d'entrepôt *</label>
                                <select class="form-select form-select-sm" id="type" name="type" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="principal" {{ old('type', $entrepot->type ?? '') == 'principal' ? 'selected' : '' }}>Principal</option>
                                    <option value="secondaire" {{ old('type', $entrepot->type ?? '') == 'secondaire' ? 'selected' : '' }}>Secondaire</option>
                                    <option value="depot" {{ old('type', $entrepot->type ?? '') == 'depot' ? 'selected' : '' }}>Dépôt</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="statut" class="form-label small fw-bold">Statut *</label>
                                <select class="form-select form-select-sm" id="statut" name="statut" required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="actif" {{ old('statut', $entrepot->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="maintenance" {{ old('statut', $entrepot->statut) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="inactif" {{ old('statut', $entrepot->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date_creation" class="form-label small fw-bold">Date de création *</label>
                                <input type="date" class="form-control form-control-sm" id="date_creation" name="date_creation" value="{{ old('date_creation', $entrepot->date_creation ? $entrepot->date_creation->format('Y-m-d') : date('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Coordonnées géographiques -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold mb-3">🗺️ Coordonnées Géographiques</h6>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="mb-3">
                                <label for="latitude" class="form-label small fw-bold">Latitude</label>
                                <input type="number" class="form-control form-control-sm" id="latitude" name="latitude" value="{{ old('latitude', $entrepot->latitude ?? '') }}" step="0.000001" placeholder="Ex: 14.7167">
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="mb-3">
                                <label for="longitude" class="form-label small fw-bold">Longitude</label>
                                <input type="number" class="form-control form-control-sm" id="longitude" name="longitude" value="{{ old('longitude', $entrepot->longitude ?? '') }}" step="0.000001" placeholder="Ex: -17.4677">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Responsable -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold mb-3">👤 Responsable</h6>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="mb-3">
                                <label for="responsable" class="form-label small fw-bold">Nom du responsable *</label>
                                <input type="text" class="form-control form-control-sm" id="responsable" name="responsable" value="{{ old('responsable', $entrepot->responsable) }}" required>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="mb-3">
                                <label for="telephone_responsable" class="form-label small fw-bold">Téléphone *</label>
                                <input type="tel" class="form-control form-control-sm" id="telephone_responsable" name="telephone_responsable" value="{{ old('telephone_responsable', $entrepot->telephone_responsable ?? '') }}" placeholder="Ex: 221784257743" required>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-3">
                            <div class="mb-3">
                                <label for="email_responsable" class="form-label small fw-bold">Email *</label>
                                <input type="email" class="form-control form-control-sm" id="email_responsable" name="email_responsable" value="{{ old('email_responsable', $entrepot->email_responsable ?? '') }}" placeholder="Ex: responsable@csar.sn" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold mb-3">📝 Description</h6>
                            <div class="mb-3">
                                <label for="description" class="form-label small fw-bold">Description de l'entrepôt</label>
                                <textarea class="form-control form-control-sm" id="description" name="description" rows="4" placeholder="Décrivez les caractéristiques particulières de cet entrepôt...">{{ old('description', $entrepot->description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.entrepots.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Mettre à jour
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Variables CSS pour la cohérence */
:root {
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --gradient-warning: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --gradient-danger: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    --gradient-info: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

/* Cartes modernes avec effets 3D */
.card-modern {
    background: rgba(255, 255, 255, 0.95);
    border: none;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--gradient-primary);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.card-modern:hover::before {
    opacity: 1;
}

/* Icônes 3D */
.icon-3d {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.icon-3d:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

/* Boutons modernes */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Formulaires */
.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Formulaires compacts */
.form-control-sm, .form-select-sm {
    font-size: 0.8rem !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 6px;
}

/* Assurer que tous les liens sont cliquables */
a, button, .btn {
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 10 !important;
}

/* Responsive */
@media (max-width: 768px) {
    .card-modern {
        margin-bottom: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush