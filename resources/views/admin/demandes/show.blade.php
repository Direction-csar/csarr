@extends('layouts.admin')

@section('title', 'Détail de la Demande')
@section('page-title', 'Détail de la Demande')

@section('content')
<div class="container-fluid px-3">
    <!-- Header -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">📋 Détail de la Demande</h1>
                        <p class="text-muted mb-0 small">Code de suivi: {{ $demande->tracking_code ?? 'CSAR-' . $demande->id }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.demandes.edit', $demande->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Traiter
                        </a>
                        <a href="{{ route('admin.demandes.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="row mb-2">
        <div class="col-lg-8">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">📋 Informations de la Demande</h6>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Demandeur</label>
                            <p class="mb-0">{{ $demande->nom }} {{ $demande->prenom }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Email</label>
                            <p class="mb-0">{{ $demande->email }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Téléphone</label>
                            <p class="mb-0">{{ $demande->telephone }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Objet</label>
                            <p class="mb-0">{{ $demande->objet }}</p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Type de demande</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ ucfirst($demande->type_demande ?? 'Non spécifié') }}</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Statut</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $demande->statut === 'traitee' ? 'success' : ($demande->statut === 'rejetee' ? 'danger' : ($demande->statut === 'en_cours' ? 'info' : 'warning')) }}">
                                    {{ str_replace('_', ' ', ucfirst($demande->statut)) }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Date de demande</label>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($demande->created_at)->format('d/m/Y à H:i') }}</p>
                        </div>
                        
                        @if($demande->date_traitement)
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Date de traitement</label>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($demande->date_traitement)->format('d/m/Y à H:i') }}</p>
                        </div>
                        @endif
                        
                        @if($demande->tracking_code)
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Code de suivi</label>
                            <p class="mb-0"><span class="badge bg-primary">{{ $demande->tracking_code }}</span></p>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($demande->description)
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Description</label>
                    <div class="bg-light p-3 rounded">
                        {{ $demande->description }}
                    </div>
                </div>
                @endif
                
                @if($demande->reponse)
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Réponse administrateur</label>
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        {{ $demande->reponse }}
                    </div>
                </div>
                @endif
                
                @if($demande->pj)
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Pièce jointe</label>
                    <p class="mb-0">
                        <a href="{{ asset('storage/' . $demande->pj) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-file me-1"></i>Télécharger
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar avec actions -->
        <div class="col-lg-4">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">⚡ Actions Rapides</h6>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.demandes.edit', $demande->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i>Traiter la demande
                    </a>
                    
                    @if($demande->statut === 'en_attente')
                    <form action="{{ route('admin.demandes.update', $demande->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check me-1"></i>Approuver
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.demandes.update', $demande->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times me-1"></i>Rejeter
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            
            <!-- Informations de suivi -->
            <div class="card-modern p-3 mt-2">
                <h6 class="fw-bold mb-3">📊 Informations de Suivi</h6>
                
                @if($demande->tracking_code)
                <div class="mb-2">
                    <small class="text-muted">Code de suivi:</small>
                    <p class="mb-0">
                        <span class="badge bg-primary">{{ $demande->tracking_code }}</span>
                    </p>
                </div>
                @endif
                
                <div class="mb-2">
                    <small class="text-muted">Date de création:</small>
                    <p class="mb-0 small">{{ \Carbon\Carbon::parse($demande->created_at)->format('d/m/Y à H:i') }}</p>
                </div>
                
                @if($demande->date_traitement)
                <div class="mb-2">
                    <small class="text-muted">Date de traitement:</small>
                    <p class="mb-0 small">{{ \Carbon\Carbon::parse($demande->date_traitement)->format('d/m/Y à H:i') }}</p>
                </div>
                @endif
                
                @if($demande->traite_par)
                <div class="mb-2">
                    <small class="text-muted">Traité par:</small>
                    <p class="mb-0 small">Utilisateur #{{ $demande->traite_par }}</p>
                </div>
                @endif
                
                <div class="mb-2">
                    <small class="text-muted">Consentement RGPD:</small>
                    <span class="badge bg-{{ $demande->consentement ? 'success' : 'danger' }}">
                        {{ $demande->consentement ? 'Oui' : 'Non' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection