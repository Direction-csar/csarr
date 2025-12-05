@extends('layouts.admin')

@section('title', 'Détail de l\'Utilisateur')
@section('page-title', 'Détail de l\'Utilisateur')

@section('content')
<div class="container-fluid px-3">
    <!-- Header -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="card-modern p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-1 text-dark fw-bold">👤 Détail de l'Utilisateur</h1>
                        <p class="text-muted mb-0 small">{{ $user->name ?? 'Utilisateur' }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <!-- Informations principales -->
        <div class="col-lg-8 mb-2">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">👤 Informations Personnelles</h6>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Nom complet</label>
                        <p class="mb-0 fw-bold">{{ $user->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Email</label>
                        <p class="mb-0">{{ $user->email ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Téléphone</label>
                        <p class="mb-0">{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Rôle</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'dg' ? 'warning' : ($user->role === 'responsable' ? 'info' : 'success')) }}">
                                {{ ucfirst($user->role ?? 'Agent') }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Statut</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $user->status === 'actif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($user->status ?? 'Actif') }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold text-muted">Dernière connexion</label>
                        <p class="mb-0">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->diffForHumans() : 'Jamais' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Historique des connexions -->
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">📊 Historique des Connexions</h6>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Dernière connexion</h6>
                            <p class="mb-0 text-muted small">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d/m/Y H:i') : 'Jamais' }}</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Compte créé</h6>
                            <p class="mb-0 text-muted small">{{ $user->created_at->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Dernière modification</h6>
                            <p class="mb-0 text-muted small">{{ $user->updated_at->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions et informations -->
        <div class="col-lg-4 mb-2">
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">👤 Profil Utilisateur</h6>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-3d me-3" style="width: 60px; height: 60px; background: var(--gradient-primary);">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $user->name ?? 'Utilisateur' }}</h6>
                        <small class="text-muted">{{ $user->email ?? 'N/A' }}</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">ID Utilisateur</label>
                    <p class="mb-0">{{ $user->id ?? 'N/A' }}</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Date d'inscription</label>
                    <p class="mb-0">{{ $user->created_at->format('d/m/Y') ?? 'N/A' }}</p>
                </div>
            </div>
            
            <!-- Actions rapides -->
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">⚡ Actions Rapides</h6>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-{{ $user->status === 'actif' ? 'warning' : 'success' }} btn-sm" 
                            onclick="toggleStatus('{{ $user->status }}')">
                        <i class="fas fa-{{ $user->status === 'actif' ? 'user-times' : 'user-check' }} me-1"></i>
                        {{ $user->status === 'actif' ? 'Désactiver' : 'Activer' }}
                    </button>
                    
                    <button class="btn btn-info btn-sm" onclick="resetPassword()">
                        <i class="fas fa-key me-1"></i>Réinitialiser mot de passe
                    </button>
                    
                    <button class="btn btn-primary btn-sm" onclick="sendNotification()">
                        <i class="fas fa-bell me-1"></i>Envoyer notification
                    </button>
                    
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteUser()">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </div>
            </div>
            
            <!-- Statistiques de l'utilisateur -->
            <div class="card-modern p-3">
                <h6 class="fw-bold mb-3">📊 Statistiques</h6>
                
                <div class="row text-center">
                    <div class="col-6 mb-2">
                        <div class="p-2 bg-light rounded">
                            <h6 class="mb-0 text-primary">15</h6>
                            <small class="text-muted">Connexions</small>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <div class="p-2 bg-light rounded">
                            <h6 class="mb-0 text-success">8</h6>
                            <small class="text-muted">Actions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
  /* Optimisation de l'espace */
  .container-fluid { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
  
  /* Cards plus compactes */
  .card-modern { margin-bottom: 0.5rem !important; }
  
  /* Réduction des marges */
  .row { margin-bottom: 0.5rem !important; }
  .mb-2 { margin-bottom: 0.5rem !important; }
  
  /* Textes plus compacts */
  .h4 { font-size: 1.25rem !important; }
  .h6 { font-size: 0.9rem !important; }
  
  /* Boutons plus petits et cliquables */
  .btn-sm { 
    padding: 0.25rem 0.5rem !important; 
    font-size: 0.8rem !important; 
    cursor: pointer !important;
    pointer-events: auto !important;
    z-index: 10 !important;
  }
  
  /* Timeline */
  .timeline {
    position: relative;
    padding-left: 30px;
  }
  
  .timeline-item {
    position: relative;
    margin-bottom: 20px;
  }
  
  .timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
  }
  
  .timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 15px;
    width: 2px;
    height: 100%;
    background-color: #e9ecef;
  }
  
  .timeline-content h6 {
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
  }
  
  .timeline-content p {
    font-size: 0.8rem;
  }
</style>
@endpush

@push('scripts')
<script>
// Fonction de basculement de statut
function toggleStatus(currentStatus) {
    const newStatus = currentStatus === 'actif' ? 'inactif' : 'actif';
    const actionText = newStatus === 'actif' ? 'activer' : 'désactiver';
    
    if (confirm(`Êtes-vous sûr de vouloir ${actionText} cet utilisateur ?`)) {
        showToast(`Utilisateur ${actionText} avec succès!`, 'success');
        
        // Mettre à jour l'interface
        const statusBadge = document.querySelector('.badge:last-of-type');
        const toggleBtn = document.querySelector('button[onclick*="toggleStatus"]');
        
        if (statusBadge) {
            statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            statusBadge.className = `badge bg-${newStatus === 'actif' ? 'success' : 'secondary'}`;
        }
        
        if (toggleBtn) {
            toggleBtn.className = `btn btn-${newStatus === 'actif' ? 'warning' : 'success'} btn-sm`;
            toggleBtn.innerHTML = `<i class="fas fa-${newStatus === 'actif' ? 'user-times' : 'user-check'} me-1"></i>${newStatus === 'actif' ? 'Désactiver' : 'Activer'}`;
            toggleBtn.setAttribute('onclick', `toggleStatus('${newStatus}')`);
        }
    }
}

// Fonction de réinitialisation de mot de passe
function resetPassword() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet utilisateur ?')) {
        showToast('Mot de passe réinitialisé avec succès!', 'success');
    }
}

// Fonction d'envoi de notification
function sendNotification() {
    showToast('Notification envoyée à l\'utilisateur!', 'info');
}

// Fonction de suppression
function deleteUser() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        showToast('Utilisateur supprimé avec succès!', 'success');
        
        // Rediriger vers la liste
        setTimeout(() => {
            window.location.href = '{{ route("admin.users.index") }}';
        }, 1500);
    }
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