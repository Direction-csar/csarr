@extends('layouts.drh')

@section('title', 'Nouveau Document RH - DRH')

@section('content')
<!-- Page title -->
<div class="page-title-box">
    <h4>Nouveau Document RH</h4>
</div>

<!-- Form -->
<div class="card">
    <div class="card-header">
        <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Créer un nouveau document RH</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('drh.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="personnel_id" class="form-label">Personnel *</label>
                    <select name="personnel_id" id="personnel_id" class="form-control" required>
                        <option value="">Sélectionner le personnel</option>
                        @foreach($personnel as $person)
                            <option value="{{ $person->id }}" {{ old('personnel_id') == $person->id ? 'selected' : '' }}>
                                {{ $person->prenoms_nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('personnel_id')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="type" class="form-label">Type de document *</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="">Sélectionner le type</option>
                        <option value="contrat_travail" {{ old('type') == 'contrat_travail' ? 'selected' : '' }}>Contrat de travail</option>
                        <option value="bulletin_salaire" {{ old('type') == 'bulletin_salaire' ? 'selected' : '' }}>Bulletin de salaire</option>
                        <option value="certificat_medical" {{ old('type') == 'certificat_medical' ? 'selected' : '' }}>Certificat médical</option>
                        <option value="arret_maladie" {{ old('type') == 'arret_maladie' ? 'selected' : '' }}>Arrêt maladie</option>
                        <option value="attestation_travail" {{ old('type') == 'attestation_travail' ? 'selected' : '' }}>Attestation de travail</option>
                        <option value="certificat_formation" {{ old('type') == 'certificat_formation' ? 'selected' : '' }}>Certificat de formation</option>
                        <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('type')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="titre" class="form-label">Titre du document *</label>
                <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre') }}" required>
                @error('titre')
                    <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="date_emission" class="form-label">Date d'émission *</label>
                    <input type="date" name="date_emission" id="date_emission" class="form-control" value="{{ old('date_emission', date('Y-m-d')) }}" required>
                    @error('date_emission')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="date_expiration" class="form-label">Date d'expiration</label>
                    <input type="date" name="date_expiration" id="date_expiration" class="form-control" value="{{ old('date_expiration') }}">
                    @error('date_expiration')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="fichier" class="form-label">Fichier (PDF, DOC, DOCX, PPT, PPTX) - Max 20MB</label>
                <input type="file" name="fichier" id="fichier" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx">
                @error('fichier')
                    <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="commentaires" class="form-label">Commentaires</label>
                <textarea name="commentaires" id="commentaires" class="form-control" rows="2">{{ old('commentaires') }}</textarea>
                @error('commentaires')
                    <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <a href="{{ route('drh.documents.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    border-color: #059669;
    box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
    outline: none;
}

@media (max-width: 768px) {
    .page-title-box h4 {
        font-size: 20px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .form-control {
        font-size: 16px; /* Prevent zoom on iOS */
    }
    
    .btn {
        padding: 8px 15px;
        font-size: 14px;
    }
}
</style>
@endsection
