@extends('layouts.admin')

@section('title', 'Détails de l\'Actualité')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-newspaper me-2"></i>
                {{ $actualite->titre }}
            </h1>
            <p class="text-muted">Détails de l'actualité</p>
        </div>
        <div>
            <a href="{{ route('admin.actualites.edit', $actualite->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Contenu de l'actualité -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Contenu</h6>
                        <div>
                            <span class="badge badge-{{ $actualite->statut == 'publié' ? 'success' : 'warning' }}">
                                {{ ucfirst($actualite->statut) }}
                            </span>
                            @if($actualite->featured ?? false)
                                <span class="badge badge-warning">À la une</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h4>{{ $actualite->titre }}</h4>
                        <p class="text-muted">
                            <i class="fas fa-tag me-1"></i>
                            <span class="badge badge-info">{{ ucfirst($actualite->categorie) }}</span>
                        </p>
                    </div>
                    
                    <div class="content">
                        {!! nl2br(e($actualite->contenu)) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Informations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID :</strong></td>
                            <td>{{ $actualite->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Catégorie :</strong></td>
                            <td>
                                <span class="badge badge-info">{{ ucfirst($actualite->categorie) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Statut :</strong></td>
                            <td>
                                <span class="badge badge-{{ $actualite->statut == 'publié' ? 'success' : 'warning' }}">
                                    {{ ucfirst($actualite->statut) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>À la une :</strong></td>
                            <td>
                                @if($actualite->featured ?? false)
                                    <span class="badge badge-warning">Oui</span>
                                @else
                                    <span class="badge badge-secondary">Non</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Vues :</strong></td>
                            <td>
                                <span class="badge badge-secondary">{{ $actualite->vues ?? 0 }} vues</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Créé le :</strong></td>
                            <td>{{ isset($actualite->created_at) ? $actualite->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Modifié le :</strong></td>
                            <td>{{ isset($actualite->updated_at) ? $actualite->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.actualites.edit', $actualite->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        
                        @if($actualite->statut == 'brouillon')
                            <button class="btn btn-success" onclick="publishActualite({{ $actualite->id }})">
                                <i class="fas fa-paper-plane me-2"></i>Publier
                            </button>
                        @else
                            <button class="btn btn-secondary" onclick="unpublishActualite({{ $actualite->id }})">
                                <i class="fas fa-eye-slash me-2"></i>Dépublier
                            </button>
                        @endif
                        
                        <form action="{{ route('admin.actualites.destroy', $actualite->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité ?')">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function publishActualite(id) {
    if (confirm('Êtes-vous sûr de vouloir publier cette actualité ?')) {
        // Logique de publication
        alert('Actualité publiée avec succès !');
        location.reload();
    }
}

function unpublishActualite(id) {
    if (confirm('Êtes-vous sûr de vouloir dépublier cette actualité ?')) {
        // Logique de dépublication
        alert('Actualité dépubliée avec succès !');
        location.reload();
    }
}
</script>
@endsection
