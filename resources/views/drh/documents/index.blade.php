@extends('layouts.drh')

@section('title', 'Documents RH - DRH')

@section('content')
<!-- Page title -->
<div class="page-title-box">
    <h4>Documents RH - Direction des Ressources Humaines</h4>
</div>

<!-- Actions -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <a href="{{ route('drh.documents.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>Nouveau Document
            </a>
            
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="text" class="form-control" placeholder="Rechercher..." style="width: 200px;">
                <select class="form-control" style="width: 150px;">
                    <option value="">Tous les types</option>
                    <option value="contrat_travail">Contrat de travail</option>
                    <option value="bulletin_salaire">Bulletin de salaire</option>
                    <option value="certificat_medical">Certificat médical</option>
                    <option value="arret_maladie">Arrêt maladie</option>
                    <option value="attestation_travail">Attestation de travail</option>
                    <option value="certificat_formation">Certificat de formation</option>
                    <option value="autre">Autre</option>
                </select>
                <button class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Documents List -->
<div class="card">
    <div class="card-header">
        <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Liste des Documents RH</h5>
    </div>
    <div class="card-body">
        @if($documents->count() > 0)
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Personnel</th>
                            <th>Titre</th>
                            <th>Date d'émission</th>
                            <th>Date d'expiration</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                            <tr>
                                <td>
                                    <span class="badge" style="background: #059669; color: white;">
                                        {{ ucfirst(str_replace('_', ' ', $document->type)) }}
                                    </span>
                                </td>
                                <td>{{ $document->personnel->prenoms_nom ?? 'N/A' }}</td>
                                <td>{{ $document->titre }}</td>
                                <td>{{ \Carbon\Carbon::parse($document->date_emission)->format('d/m/Y') }}</td>
                                <td>
                                    @if($document->date_expiration)
                                        {{ \Carbon\Carbon::parse($document->date_expiration)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $isExpired = $document->date_expiration && \Carbon\Carbon::parse($document->date_expiration)->isPast();
                                        $isExpiringSoon = $document->date_expiration && \Carbon\Carbon::parse($document->date_expiration)->diffInDays() <= 30;
                                    @endphp
                                    
                                    @if($isExpired)
                                        <span class="badge" style="background: #ef4444; color: white;">Expiré</span>
                                    @elseif($isExpiringSoon)
                                        <span class="badge" style="background: #f59e0b; color: white;">Expire bientôt</span>
                                    @else
                                        <span class="badge" style="background: #22c55e; color: white;">Actif</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('drh.documents.show', $document) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('drh.documents.edit', $document) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($document->fichier)
                                            <a href="{{ route('drh.documents.download', $document) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('drh.documents.destroy', $document) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $documents->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-file-alt" style="font-size: 48px; color: #d1d5db; margin-bottom: 20px;"></i>
                <h5 style="color: #6b7280; margin-bottom: 10px;">Aucun document trouvé</h5>
                <p style="color: #9ca3af;">Commencez par créer votre premier document RH.</p>
                <a href="{{ route('drh.documents.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>Créer un document
                </a>
            </div>
        @endif
    </div>
</div>

<style>
@media (max-width: 768px) {
    .page-title-box h4 {
        font-size: 20px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .table {
        font-size: 14px;
    }
    
    .btn {
        padding: 6px 10px;
        font-size: 12px;
    }
    
    .form-control {
        width: 100% !important;
        margin-bottom: 10px;
    }
}
</style>
@endsection
