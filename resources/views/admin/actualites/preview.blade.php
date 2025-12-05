@extends('layouts.admin')

@section('title', 'Prévisualisation - ' . $actualite->title)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye me-2 text-info"></i>
                Prévisualisation de l'actualité
            </h1>
            <p class="text-muted mb-0">{{ $actualite->title }}</p>
        </div>
        <div>
            <a href="{{ route('admin.actualites.edit', $actualite->id) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.actualites.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- Prévisualisation -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <!-- En-tête de l'actualité -->
                    <div class="mb-4">
                        <h1 class="h2 mb-3 text-primary">{{ $actualite->title }}</h1>
                        
                        <div class="d-flex flex-wrap align-items-center mb-3">
                            <span class="badge bg-primary me-2 mb-1">
                                <i class="fas fa-tag me-1"></i>{{ ucfirst($actualite->category) }}
                            </span>
                            <span class="badge bg-{{ $actualite->status === 'published' ? 'success' : ($actualite->status === 'draft' ? 'warning' : 'info') }} me-2 mb-1">
                                <i class="fas fa-{{ $actualite->status === 'published' ? 'check' : ($actualite->status === 'draft' ? 'edit' : 'clock') }} me-1"></i>
                                {{ ucfirst($actualite->status) }}
                            </span>
                            @if($actualite->is_featured)
                                <span class="badge bg-warning me-2 mb-1">
                                    <i class="fas fa-star me-1"></i>À la une
                                </span>
                            @endif
                            @if($actualite->is_public)
                                <span class="badge bg-success me-2 mb-1">
                                    <i class="fas fa-globe me-1"></i>Public
                                </span>
                            @endif
                        </div>

                        <div class="text-muted small mb-3">
                            <i class="fas fa-user me-1"></i>Par {{ $actualite->author->name ?? 'Administrateur' }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-calendar me-1"></i>{{ $actualite->created_at->format('d/m/Y à H:i') }}
                            @if($actualite->published_at)
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock me-1"></i>Publié le {{ $actualite->published_at->format('d/m/Y à H:i') }}
                            @endif
                        </div>
                    </div>

                    <!-- Extrait -->
                    @if($actualite->excerpt)
                        <div class="alert alert-light border-start border-primary border-4 mb-4">
                            <p class="mb-0 fst-italic">{{ $actualite->excerpt }}</p>
                        </div>
                    @endif

                    <!-- Média principal -->
                    @if($actualite->main_media)
                        <div class="mb-4">
                            @if($actualite->main_media['type'] === 'video')
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ str_replace('watch?v=', 'embed/', $actualite->youtube_url) }}" 
                                            title="{{ $actualite->title }}" 
                                            allowfullscreen></iframe>
                                </div>
                            @else
                                <img src="{{ $actualite->main_media['url'] }}" 
                                     alt="{{ $actualite->main_media['alt'] }}" 
                                     class="img-fluid rounded shadow-sm">
                            @endif
                        </div>
                    @endif

                    <!-- Contenu -->
                    <div class="content-preview">
                        {!! $actualite->content !!}
                    </div>

                    <!-- Document associé -->
                    @if($actualite->hasDocument())
                        <div class="mt-4 p-3 bg-light rounded">
                            <h5 class="mb-3">
                                <i class="fas fa-file-{{ $actualite->document_extension === 'pdf' ? 'pdf' : 'download' }} me-2 text-danger"></i>
                                Document associé
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $actualite->document_title }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-download me-1"></i>{{ $actualite->downloads_count }} téléchargement(s)
                                    </small>
                                </div>
                                <a href="{{ route('admin.actualites.download', $actualite->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-download me-2"></i>Télécharger
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if($actualite->tags && count($actualite->tags) > 0)
                        <div class="mt-4">
                            <h6>Mots-clés :</h6>
                            @foreach($actualite->tags as $tag)
                                <span class="badge bg-secondary me-1 mb-1">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informations -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>ID :</strong></td>
                            <td>{{ $actualite->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Slug :</strong></td>
                            <td><code>{{ $actualite->slug }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Statut :</strong></td>
                            <td>
                                <span class="badge bg-{{ $actualite->status === 'published' ? 'success' : ($actualite->status === 'draft' ? 'warning' : 'info') }}">
                                    {{ ucfirst($actualite->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Vues :</strong></td>
                            <td>{{ $actualite->views_count }}</td>
                        </tr>
                        @if($actualite->hasDocument())
                            <tr>
                                <td><strong>Téléchargements :</strong></td>
                                <td>{{ $actualite->downloads_count }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td><strong>Créé le :</strong></td>
                            <td>{{ $actualite->created_at->format('d/m/Y à H:i') }}</td>
                        </tr>
                        @if($actualite->updated_at && $actualite->updated_at != $actualite->created_at)
                            <tr>
                                <td><strong>Modifié le :</strong></td>
                                <td>{{ $actualite->updated_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.actualites.edit', $actualite->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        @if($actualite->hasDocument())
                            <a href="{{ route('admin.actualites.download', $actualite->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Télécharger le document
                            </a>
                        @endif
                        <a href="{{ route('admin.actualites.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.content-preview {
    line-height: 1.6;
    font-size: 1.1rem;
}

.content-preview h1, .content-preview h2, .content-preview h3 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.content-preview p {
    margin-bottom: 1.5rem;
}

.content-preview img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.content-preview blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0 8px 8px 0;
}

.content-preview ul, .content-preview ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.content-preview li {
    margin-bottom: 0.5rem;
}

.ratio {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.8rem;
}

.table td {
    border: none;
    padding: 0.5rem 0;
}

.table td:first-child {
    width: 40%;
}
</style>
@endsection

