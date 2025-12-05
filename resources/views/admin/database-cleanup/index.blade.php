@extends('layouts.admin')

@section('title', 'Nettoyage de la Base de Données')
@section('page-title', 'Nettoyage de la Base de Données')

@section('content')
<div class="container-fluid px-3">
    <!-- En-tête -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">🧹 Nettoyage de la Base de Données</h1>
                        <p class="text-muted mb-0 small">Supprimez les données de test et optimisez votre base de données</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-info btn-sm" onclick="checkConnection()">
                            <i class="fas fa-database me-1"></i>Vérifier Connexion
                        </button>
                        <button class="btn btn-warning btn-sm" onclick="refreshStats()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show border-0" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="width: 40px; height: 40px; background: var(--gradient-success);">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>Succès !</strong><br>
                        <small>{{ session('success') }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show border-0" role="alert" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-3" style="width: 40px; height: 40px; background: var(--gradient-danger);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>Erreur !</strong><br>
                        <small>{{ session('error') }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques actuelles -->
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-primary); width: 45px; height: 45px;">
                        <i class="fas fa-file-alt" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-primary">{{ $stats['total_demandes'] }}</h4>
                        <p class="text-muted mb-0 small">📋 Total Demandes</p>
                        @if($stats['test_demandes'] > 0)
                        <small class="text-warning">⚠️ {{ $stats['test_demandes'] }} de test</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-info); width: 45px; height: 45px;">
                        <i class="fas fa-users" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-info">{{ $stats['total_users'] }}</h4>
                        <p class="text-muted mb-0 small">👥 Total Utilisateurs</p>
                        @if($stats['test_users'] > 0)
                        <small class="text-warning">⚠️ {{ $stats['test_users'] }} de test</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-warning); width: 45px; height: 45px;">
                        <i class="fas fa-bell" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-warning">{{ $stats['total_notifications'] }}</h4>
                        <p class="text-muted mb-0 small">🔔 Total Notifications</p>
                        @if($stats['test_notifications'] > 0)
                        <small class="text-warning">⚠️ {{ $stats['test_notifications'] }} de test</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-2">
            <div class="card-modern p-2 h-100">
                <div class="d-flex align-items-center">
                    <div class="icon-3d me-2" style="background: var(--gradient-success); width: 45px; height: 45px;">
                        <i class="fas fa-database" style="font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-0 fw-bold text-success" id="connection-status">✅ Connecté</h4>
                        <p class="text-muted mb-0 small">🗄️ Base de Données</p>
                        <small class="text-success">MySQL Opérationnel</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de nettoyage -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <h5 class="mb-3 fw-bold">🧹 Options de Nettoyage</h5>
                
                <form method="POST" action="{{ route('admin.database-cleanup.cleanup') }}" id="cleanupForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cleanup_demandes" id="cleanup_demandes" 
                                       {{ $stats['test_demandes'] > 0 ? '' : 'disabled' }}>
                                <label class="form-check-label" for="cleanup_demandes">
                                    <strong>🗑️ Supprimer les demandes de test</strong>
                                    <br><small class="text-muted">{{ $stats['test_demandes'] }} demandes détectées</small>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cleanup_users" id="cleanup_users"
                                       {{ $stats['test_users'] > 0 ? '' : 'disabled' }}>
                                <label class="form-check-label" for="cleanup_users">
                                    <strong>🗑️ Supprimer les utilisateurs de test</strong>
                                    <br><small class="text-muted">{{ $stats['test_users'] }} utilisateurs détectés</small>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cleanup_notifications" id="cleanup_notifications"
                                       {{ $stats['test_notifications'] > 0 ? '' : 'disabled' }}>
                                <label class="form-check-label" for="cleanup_notifications">
                                    <strong>🗑️ Supprimer les notifications de test</strong>
                                    <br><small class="text-muted">{{ $stats['test_notifications'] }} notifications détectées</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Attention :</strong> Cette action est irréversible. Assurez-vous de faire une sauvegarde avant de procéder au nettoyage.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger" id="cleanupBtn" disabled>
                                <i class="fas fa-trash me-2"></i>Nettoyer la Base de Données
                            </button>
                            <button type="button" class="btn btn-secondary ms-2" onclick="clearSelection()">
                                <i class="fas fa-times me-2"></i>Effacer la Sélection
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Informations sur la base de données -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card-modern p-3">
                <h5 class="mb-3 fw-bold">📊 Informations sur la Base de Données</h5>
                
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <h6 class="fw-bold">🔍 Critères de Détection des Données de Test :</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Noms contenant "test" ou "Test"</li>
                            <li><i class="fas fa-check text-success me-2"></i>Emails contenant "test" ou "example"</li>
                            <li><i class="fas fa-check text-success me-2"></i>Descriptions contenant "test"</li>
                            <li><i class="fas fa-check text-success me-2"></i>Codes de suivi contenant "TEST"</li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-6 mb-3">
                        <h6 class="fw-bold">🛡️ Sécurités :</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-shield-alt text-primary me-2"></i>Les administrateurs ne sont jamais supprimés</li>
                            <li><i class="fas fa-shield-alt text-primary me-2"></i>Transaction de base de données pour la sécurité</li>
                            <li><i class="fas fa-shield-alt text-primary me-2"></i>Logs détaillés de toutes les opérations</li>
                            <li><i class="fas fa-shield-alt text-primary me-2"></i>Confirmation obligatoire avant suppression</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Vérifier la connexion à la base de données
function checkConnection() {
    showToast('Vérification de la connexion...', 'info');
    
    fetch('{{ route("admin.database-cleanup.check-connection") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Connexion à la base de données OK !', 'success');
                updateConnectionStatus('✅ Connecté', 'text-success');
            } else {
                showToast('Erreur de connexion: ' + data.message, 'error');
                updateConnectionStatus('❌ Erreur', 'text-danger');
            }
        })
        .catch(error => {
            showToast('Erreur lors de la vérification', 'error');
            updateConnectionStatus('❌ Erreur', 'text-danger');
        });
}

// Mettre à jour le statut de connexion
function updateConnectionStatus(text, className) {
    const statusElement = document.getElementById('connection-status');
    statusElement.textContent = text;
    statusElement.className = 'mb-0 fw-bold ' + className;
}

// Actualiser les statistiques
function refreshStats() {
    showToast('Actualisation des statistiques...', 'info');
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Gérer la sélection des options de nettoyage
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const cleanupBtn = document.getElementById('cleanupBtn');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
            cleanupBtn.disabled = checkedBoxes.length === 0;
        });
    });
    
    // Confirmation avant nettoyage
    document.getElementById('cleanupForm').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
            showToast('Veuillez sélectionner au moins une option de nettoyage', 'warning');
            return;
        }
        
        const confirmMessage = `Êtes-vous sûr de vouloir supprimer ${checkedBoxes.length} type(s) de données de test ? Cette action est irréversible !`;
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });
});

// Effacer la sélection
function clearSelection() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('cleanupBtn').disabled = true;
    showToast('Sélection effacée', 'info');
}

// Fonction pour afficher des toasts
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Supprimer le toast après 3 secondes
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endpush
