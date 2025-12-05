@extends('layouts.admin')

@section('title', 'Modifier le Chiffre Clé')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Modifier le Chiffre Clé
                    </h1>
                    <p class="text-muted mb-0">{{ $chiffreCle->title }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.chiffres-cles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Formulaire -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Informations du Chiffre Clé
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.chiffres-cles.update', $chiffreCle->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre *</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $chiffreCle->title) }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value" class="form-label">Valeur *</label>
                                    <input type="text" 
                                           class="form-control @error('value') is-invalid @enderror" 
                                           id="value" 
                                           name="value" 
                                           value="{{ old('value', $chiffreCle->value) }}" 
                                           required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <input type="text" 
                                   class="form-control @error('description') is-invalid @enderror" 
                                   id="description" 
                                   name="description" 
                                   value="{{ old('description', $chiffreCle->description) }}" 
                                   required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">Icône FontAwesome *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i id="iconPreview" class="{{ old('icon', $chiffreCle->icon) }}"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('icon') is-invalid @enderror" 
                                               id="icon" 
                                               name="icon" 
                                               value="{{ old('icon', $chiffreCle->icon) }}" 
                                               placeholder="fas fa-users" 
                                               required>
                                        @error('icon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Exemples: fas fa-users, fas fa-warehouse, fas fa-chart-bar
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="color" class="form-label">Couleur *</label>
                                    <div class="input-group">
                                        <input type="color" 
                                               class="form-control form-control-color @error('color') is-invalid @enderror" 
                                               id="color" 
                                               name="color" 
                                               value="{{ old('color', $chiffreCle->color) }}" 
                                               required>
                                        <input type="text" 
                                               class="form-control" 
                                               value="{{ old('color', $chiffreCle->color) }}" 
                                               readonly>
                                        @error('color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Ordre d'affichage *</label>
                                    <input type="number" 
                                           class="form-control @error('order') is-invalid @enderror" 
                                           id="order" 
                                           name="order" 
                                           value="{{ old('order', $chiffreCle->order) }}" 
                                           min="1" 
                                           max="10" 
                                           required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Statut</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               {{ old('is_active', $chiffreCle->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Actif (affiché sur les pages publiques)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Notes additionnelles...">{{ old('notes', $chiffreCle->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.chiffres-cles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Sauvegarder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Aperçu -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Aperçu en Temps Réel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="stats-icon mb-3" id="previewIcon" style="color: {{ $chiffreCle->color }}">
                            <i class="{{ $chiffreCle->icon }} fa-4x"></i>
                        </div>
                        <div class="stats-number h2 mb-2" id="previewValue" style="color: {{ $chiffreCle->color }}">
                            {{ number_format($chiffreCle->value, 0, ',', ' ') }}
                        </div>
                        <div class="stats-label text-muted" id="previewDescription">
                            {{ $chiffreCle->description }}
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small text-muted">
                        <strong>Informations techniques:</strong><br>
                        <strong>Clé:</strong> {{ $chiffreCle->key }}<br>
                        <strong>Section:</strong> {{ $chiffreCle->section }}<br>
                        <strong>Ordre:</strong> {{ $chiffreCle->order }}<br>
                        <strong>Statut:</strong> 
                        <span class="badge {{ $chiffreCle->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $chiffreCle->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aperçu en temps réel de l'icône
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('iconPreview');
    
    iconInput.addEventListener('input', function() {
        iconPreview.className = this.value || 'fas fa-question';
    });
    
    // Aperçu en temps réel de la couleur
    const colorInput = document.getElementById('color');
    const previewIcon = document.getElementById('previewIcon');
    const previewValue = document.getElementById('previewValue');
    
    colorInput.addEventListener('change', function() {
        const color = this.value;
        previewIcon.style.color = color;
        previewValue.style.color = color;
    });
    
    // Aperçu en temps réel de la valeur
    const valueInput = document.getElementById('value');
    const previewValueElement = document.getElementById('previewValue');
    
    valueInput.addEventListener('input', function() {
        const value = parseInt(this.value) || 0;
        previewValueElement.textContent = new Intl.NumberFormat('fr-FR').format(value);
    });
    
    // Aperçu en temps réel de la description
    const descriptionInput = document.getElementById('description');
    const previewDescription = document.getElementById('previewDescription');
    
    descriptionInput.addEventListener('input', function() {
        previewDescription.textContent = this.value;
    });
});
</script>
@endsection
