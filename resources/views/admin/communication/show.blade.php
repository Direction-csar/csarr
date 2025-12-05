@extends('layouts.admin')

@section('title', 'Détails Communication')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-comments me-2"></i>
                Détails Communication
            </h1>
            <p class="text-muted">Communication #{{ $communication['id'] }}</p>
        </div>
        <div>
            <a href="{{ route('admin.communication.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Détails de la communication -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Détails</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Type :</strong> 
                            @if($communication['type'] == 'email')
                                <i class="fas fa-envelope text-primary"></i> Email
                            @elseif($communication['type'] == 'sms')
                                <i class="fas fa-sms text-success"></i> SMS
                            @else
                                <i class="fas fa-bell text-warning"></i> Notification
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Statut :</strong> 
                            @if($communication['status'] == 'sent')
                                <span class="badge badge-success">Envoyé</span>
                            @elseif($communication['status'] == 'delivered')
                                <span class="badge badge-primary">Livré</span>
                            @else
                                <span class="badge badge-warning">En attente</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Destinataire :</strong> {{ $communication['recipient'] }}
                        </div>
                        <div class="col-md-6">
                            <strong>Priorité :</strong> 
                            @if($communication['priority'] == 'high')
                                <span class="badge badge-danger">Haute</span>
                            @elseif($communication['priority'] == 'medium')
                                <span class="badge badge-warning">Moyenne</span>
                            @else
                                <span class="badge badge-info">Basse</span>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5>{{ $communication['subject'] }}</h5>
                    <div class="communication-content">
                        <p>{{ $communication['content'] }}</p>
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
                            <td>{{ $communication['id'] }}</td>
                        </tr>
                        <tr>
                            <td><strong>Envoyée le :</strong></td>
                            <td>{{ $communication['created_at']->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Type :</strong></td>
                            <td>{{ ucfirst($communication['type']) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Priorité :</strong></td>
                            <td>{{ ucfirst($communication['priority']) }}</td>
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
                        <button class="btn btn-primary" onclick="resendCommunication()">
                            <i class="fas fa-redo me-2"></i>Renvoyer
                        </button>
                        
                        <button class="btn btn-info" onclick="viewLogs()">
                            <i class="fas fa-list me-2"></i>Voir les logs
                        </button>
                        
                        <button class="btn btn-warning" onclick="editCommunication()">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function resendCommunication() {
    if (confirm('Renvoyer cette communication ?')) {
        // Logique de renvoi
        alert('Communication renvoyée avec succès');
    }
}

function viewLogs() {
    // Logique pour voir les logs
    alert('Fonctionnalité de logs à implémenter');
}

function editCommunication() {
    // Logique de modification
    alert('Fonctionnalité de modification à implémenter');
}
</script>
@endsection



