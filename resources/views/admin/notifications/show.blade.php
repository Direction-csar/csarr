@extends('layouts.admin')

@section('title', 'Détails de la Notification')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-bell me-2"></i>Détails de la Notification
                    </h1>
                    <p class="text-muted mb-0">Informations complètes de la notification</p>
                </div>
                <div>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary-modern btn-modern">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Détails de la notification -->
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations de la Notification
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Type</label>
                                <div>
                                    <span class="badge bg-{{ $notification->type === 'success' ? 'success' : ($notification->type === 'warning' ? 'warning' : ($notification->type === 'error' ? 'danger' : 'primary')) }}">
                                        {{ ucfirst($notification->type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Date de création</label>
                                <p class="form-control-plaintext">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Titre</label>
                        <p class="form-control-plaintext">{{ $notification->title }}</p>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Message</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $notification->message }}
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Statut</label>
                        <div>
                            @if($notification->read)
                                <span class="badge bg-success">Lue</span>
                                <small class="text-muted ms-2">Lue le {{ $notification->read_at->format('d/m/Y H:i') }}</small>
                            @else
                                <span class="badge bg-warning">Non lue</span>
                            @endif
                        </div>
                    </div>

                    @if($notification->data)
                        <div class="form-group mb-3">
                            <label class="form-label fw-bold">Données supplémentaires</label>
                            <pre class="border rounded p-3 bg-light">{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Actions -->
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if(!$notification->read)
                        <form method="POST" action="{{ route('admin.notifications.mark-read') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $notification->id }}">
                            <button type="submit" class="btn btn-success-modern btn-modern w-100 mb-2">
                                <i class="fas fa-check me-2"></i>Marquer comme lue
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger-modern btn-modern w-100">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="card-modern mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="mb-1">{{ $notification->read ? 'Oui' : 'Non' }}</h4>
                                <small class="text-muted">Lue</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-1">{{ $notification->type }}</h4>
                            <small class="text-muted">Type</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


