@extends('layouts.admin')

@section('title', 'Modifier le Stock')
@section('page-title', 'Modifier le Stock')

@section('content')
<div class="page-header">
    <h1 class="page-title">Modifier le Stock</h1>
    <p class="page-subtitle">Modifier les informations de l'article en stock</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Modifier le Stock
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stocks.update', $stock->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom de l'article *</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="{{ $stock->nom }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="quantite" class="form-label">Quantité *</label>
                            <input type="number" class="form-control" id="quantite" name="quantite" value="{{ $stock->quantite }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unite" class="form-label">Unité *</label>
                            <select class="form-select" id="unite" name="unite" required>
                                <option value="">Sélectionner une unité</option>
                                <option value="kg" {{ $stock->unite == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                                <option value="g" {{ $stock->unite == 'g' ? 'selected' : '' }}>Gramme (g)</option>
                                <option value="L" {{ $stock->unite == 'L' ? 'selected' : '' }}>Litre (L)</option>
                                <option value="mL" {{ $stock->unite == 'mL' ? 'selected' : '' }}>Millilitre (mL)</option>
                                <option value="unité" {{ $stock->unite == 'unité' ? 'selected' : '' }}>Unité</option>
                                <option value="sac" {{ $stock->unite == 'sac' ? 'selected' : '' }}>Sac</option>
                                <option value="carton" {{ $stock->unite == 'carton' ? 'selected' : '' }}>Carton</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="entrepot" class="form-label">Entrepôt *</label>
                            <select class="form-select" id="entrepot" name="entrepot" required>
                                <option value="">Sélectionner un entrepôt</option>
                                <option value="Dakar" {{ $stock->entrepot == 'Dakar' ? 'selected' : '' }}>Dakar</option>
                                <option value="Thiès" {{ $stock->entrepot == 'Thiès' ? 'selected' : '' }}>Thiès</option>
                                <option value="Kaolack" {{ $stock->entrepot == 'Kaolack' ? 'selected' : '' }}>Kaolack</option>
                                <option value="Saint-Louis" {{ $stock->entrepot == 'Saint-Louis' ? 'selected' : '' }}>Saint-Louis</option>
                                <option value="Ziguinchor" {{ $stock->entrepot == 'Ziguinchor' ? 'selected' : '' }}>Ziguinchor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="seuil_minimum" class="form-label">Seuil Minimum *</label>
                            <input type="number" class="form-control" id="seuil_minimum" name="seuil_minimum" value="{{ $stock->seuil_minimum }}" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="prix_unitaire" class="form-label">Prix Unitaire (FCFA)</label>
                            <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" step="0.01">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                        <a href="{{ route('admin.stocks.show', $stock->id) }}" class="btn btn-secondary">
                            <i class="fas fa-eye me-2"></i>Voir
                        </a>
                        <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informations
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    <strong>ID :</strong> {{ $stock->id }}
                </p>
                <p class="text-muted">
                    <strong>Statut actuel :</strong> 
                    @if($stock->statut == 'faible')
                        <span class="badge bg-warning">Faible</span>
                    @else
                        <span class="badge bg-success">Normal</span>
                    @endif
                </p>
                <p class="text-muted">
                    <strong>Dernière mise à jour :</strong> {{ now()->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection



