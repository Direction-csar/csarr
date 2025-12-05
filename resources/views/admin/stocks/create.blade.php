@extends('layouts.admin')

@section('title', 'Nouveau Stock')
@section('page-title', 'Créer un Nouveau Stock')

@section('content')
<div class="page-header">
    <h1 class="page-title">Nouveau Stock</h1>
    <p class="page-subtitle">Ajouter un nouvel article en stock</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus me-2"></i>Informations du Stock
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.stocks.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom de l'article *</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="quantite" class="form-label">Quantité *</label>
                            <input type="number" class="form-control" id="quantite" name="quantite" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unite" class="form-label">Unité *</label>
                            <select class="form-select" id="unite" name="unite" required>
                                <option value="">Sélectionner une unité</option>
                                <option value="kg">Kilogramme (kg)</option>
                                <option value="g">Gramme (g)</option>
                                <option value="L">Litre (L)</option>
                                <option value="mL">Millilitre (mL)</option>
                                <option value="unité">Unité</option>
                                <option value="sac">Sac</option>
                                <option value="carton">Carton</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="entrepot" class="form-label">Entrepôt *</label>
                            <select class="form-select" id="entrepot" name="entrepot" required>
                                <option value="">Sélectionner un entrepôt</option>
                                <option value="Dakar">Dakar</option>
                                <option value="Thiès">Thiès</option>
                                <option value="Kaolack">Kaolack</option>
                                <option value="Saint-Louis">Saint-Louis</option>
                                <option value="Ziguinchor">Ziguinchor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="seuil_minimum" class="form-label">Seuil Minimum *</label>
                            <input type="number" class="form-control" id="seuil_minimum" name="seuil_minimum" required>
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
                        <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Annuler
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
                    <strong>Seuil Minimum :</strong> Quantité en dessous de laquelle une alerte sera déclenchée.
                </p>
                <p class="text-muted">
                    <strong>Unités disponibles :</strong> kg, g, L, mL, unité, sac, carton
                </p>
                <p class="text-muted">
                    <strong>Entrepôts :</strong> Dakar, Thiès, Kaolack, Saint-Louis, Ziguinchor
                </p>
            </div>
        </div>
    </div>
</div>
@endsection



