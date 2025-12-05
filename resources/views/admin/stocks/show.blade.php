@extends('layouts.admin')

@section('title', 'Détails du Stock')
@section('page-title', 'Détails du Stock')

@section('content')
<div class="page-header">
    <h1 class="page-title">Détails du Stock</h1>
    <p class="page-subtitle">Informations détaillées sur l'article en stock</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-box me-2"></i>Informations du Stock
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID :</strong></td>
                                <td>{{ $stock->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nom :</strong></td>
                                <td>{{ $stock->nom }}</td>
                            </tr>
                            <tr>
                                <td><strong>Quantité :</strong></td>
                                <td>{{ $stock->quantite }} {{ $stock->unite }}</td>
                            </tr>
                            <tr>
                                <td><strong>Entrepôt :</strong></td>
                                <td>{{ $stock->entrepot }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Seuil Minimum :</strong></td>
                                <td>{{ $stock->seuil_minimum }} {{ $stock->unite }}</td>
                            </tr>
                            <tr>
                                <td><strong>Statut :</strong></td>
                                <td>
                                    @if($stock->statut == 'faible')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Faible
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Normal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dernière mise à jour :</strong></td>
                                <td>{{ now()->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('admin.stocks.edit', $stock->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                    <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <h3 class="text-primary">{{ $stock->quantite }}</h3>
                        <p class="text-muted">Quantité actuelle</p>
                    </div>
                    
                    <div class="mb-3">
                        <h3 class="text-warning">{{ $stock->seuil_minimum }}</h3>
                        <p class="text-muted">Seuil minimum</p>
                    </div>
                    
                    <div class="progress mb-3" style="height: 20px;">
                        @php
                            $percentage = min(100, ($stock->quantite / $stock->seuil_minimum) * 100);
                        @endphp
                        <div class="progress-bar {{ $percentage < 100 ? 'bg-warning' : 'bg-success' }}" 
                             style="width: {{ $percentage }}%">
                            {{ number_format($percentage, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



