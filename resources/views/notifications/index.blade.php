@extends('layouts.admin')

@section('title', 'Notifications')

@section('styles')
<style>
    .notification-item {
        transition: all 0.3s ease;
    }
    
    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-modern {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .notification-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .notification-unread {
        position: relative;
    }
    
    .notification-unread::before {
        content: '';
        position: absolute;
        top: 10px;
        right: 10px;
        width: 8px;
        height: 8px;
        background-color: #dc3545;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .toast {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid #e9ecef;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête avec style CSAR -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-bell me-2"></i>
                                Centre de Notifications
                            </h4>
                            <small class="opacity-75">Gérez vos notifications et alertes système</small>
                        </div>
                        <div class="btn-group" role="group">
                            <button id="mark-all-read-btn" 
                                    class="btn btn-success btn-modern">
                                <i class="fas fa-check-double me-2"></i>
                                Tout marquer comme lu
                            </button>
                            <button id="clear-read-btn" 
                                    class="btn btn-warning btn-modern">
                                <i class="fas fa-broom me-2"></i>
                                Nettoyer les lues
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques avec style Bootstrap -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-bell text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Total</h6>
                            <h3 class="mb-0 text-dark">{{ $notifications->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-triangle text-danger fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Non lues</h6>
                            <h3 class="mb-0 text-dark">{{ $unreadCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Lues</h6>
                            <h3 class="mb-0 text-dark">{{ $notifications->count() - $unreadCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des Notifications
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ !$notification->is_read ? 'bg-light border-start border-primary border-4 notification-unread' : '' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="d-flex flex-grow-1">
                                            <div class="flex-shrink-0 me-3">
                                                @if($notification->type === 'success')
                                                    <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-check-circle text-success fs-5"></i>
                                                    </div>
                                                @elseif($notification->type === 'warning')
                                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-exclamation-triangle text-warning fs-5"></i>
                                                    </div>
                                                @elseif($notification->type === 'error')
                                                    <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-times-circle text-danger fs-5"></i>
                                                    </div>
                                                @else
                                                    <div class="bg-info bg-opacity-10 rounded-circle p-2">
                                                        <i class="fas fa-info-circle text-info fs-5"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $notification->title }}</h6>
                                                        <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                                        <div class="d-flex align-items-center text-muted small">
                                                            <i class="fas fa-clock me-1"></i>
                                                            <span class="me-3">{{ $notification->created_at->diffForHumans() }}</span>
                                                            @if($notification->is_read)
                                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                                <span>Lu le {{ $notification->read_at->format('d/m/Y à H:i') }}</span>
                                                            @else
                                                                <span class="badge bg-danger">Non lue</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="btn-group-vertical btn-group-sm" role="group">
                                                        @if(!$notification->is_read)
                                                            <button onclick="markAsRead({{ $notification->id }})" 
                                                                    class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-check me-1"></i>
                                                                Marquer comme lu
                                                            </button>
                                                        @endif
                                                        <button onclick="deleteNotification({{ $notification->id }})" 
                                                                class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-trash me-1"></i>
                                                            Supprimer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-bell-slash text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">Aucune notification</h5>
                            <p class="text-muted mb-0">Vous n'avez pas encore de notifications à afficher.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="notification-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-bell text-primary me-2"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message">
            <!-- Message sera inséré ici -->
        </div>
    </div>
</div>

<script>
// Fonction pour afficher les toasts
function showToast(message, type = 'success') {
    const toastElement = document.getElementById('notification-toast');
    const toastMessage = document.getElementById('toast-message');
    
    // Changer la couleur selon le type
    toastElement.className = `toast ${type === 'error' ? 'bg-danger text-white' : type === 'warning' ? 'bg-warning' : 'bg-success text-white'}`;
    toastMessage.textContent = message;
    
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}

// Fonction pour marquer comme lu avec animation
function markAsRead(id) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    // Vérifier le token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showToast('Token CSRF manquant', 'error');
        return;
    }
    
    // Animation de chargement
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Marquage...';
    button.disabled = true;
    
    console.log('Marquage comme lu - ID:', id);
    console.log('Token CSRF:', csrfToken.content);
    
    fetch(`/notifications/${id}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Réponse HTTP:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            showToast('Notification marquée comme lue', 'success');
            
            // Animation de disparition
            const notificationItem = button.closest('.list-group-item');
            notificationItem.style.transition = 'all 0.3s ease';
            notificationItem.style.opacity = '0.5';
            notificationItem.style.backgroundColor = '#f8f9fa';
            
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            showToast(data.message || 'Erreur lors du marquage', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);
        showToast('Erreur de connexion: ' + error.message, 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Fonction pour supprimer avec confirmation stylée
function deleteNotification(id) {
    // Créer une modal de confirmation personnalisée
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmation de suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer cette notification ?</p>
                    <p class="text-muted small">Cette action est irréversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete(${id})">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Nettoyer la modal après fermeture
    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });
}

// Fonction de confirmation de suppression
function confirmDelete(id) {
    const button = event.target;
    const originalText = button.innerHTML;
    
    // Vérifier le token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showToast('Token CSRF manquant', 'error');
        return;
    }
    
    // Animation de chargement
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Suppression...';
    button.disabled = true;
    
    console.log('Suppression - ID:', id);
    console.log('Token CSRF:', csrfToken.content);
    
    fetch(`/notifications/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Réponse HTTP:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            showToast('Notification supprimée', 'success');
            
            // Fermer la modal
            const modal = button.closest('.modal');
            const bsModal = bootstrap.Modal.getInstance(modal);
            bsModal.hide();
            
            // Animation de disparition
            const notificationItem = document.querySelector(`[onclick*="${id}"]`).closest('.list-group-item');
            notificationItem.style.transition = 'all 0.3s ease';
            notificationItem.style.transform = 'translateX(-100%)';
            notificationItem.style.opacity = '0';
            
            setTimeout(() => {
                location.reload();
            }, 300);
        } else {
            showToast(data.message || 'Erreur lors de la suppression', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);
        showToast('Erreur de connexion: ' + error.message, 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Marquer toutes comme lues
document.getElementById('mark-all-read-btn').addEventListener('click', function() {
    const button = this;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Traitement...';
    button.disabled = true;
    
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Toutes les notifications ont été marquées comme lues', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erreur lors du traitement', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

// Nettoyer les lues
document.getElementById('clear-read-btn').addEventListener('click', function() {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-broom me-2"></i>
                        Nettoyage des notifications
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer toutes les notifications lues ?</p>
                    <p class="text-muted small">Cette action supprimera définitivement toutes les notifications marquées comme lues.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="button" class="btn btn-warning" onclick="confirmClearRead()">
                        <i class="fas fa-broom me-1"></i>Nettoyer
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    modal.addEventListener('hidden.bs.modal', function() {
        modal.remove();
    });
});

// Confirmation de nettoyage
function confirmClearRead() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Nettoyage...';
    button.disabled = true;
    
    fetch('/notifications/clear-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Notifications lues supprimées', 'success');
            
            const modal = button.closest('.modal');
            const bsModal = bootstrap.Modal.getInstance(modal);
            bsModal.hide();
            
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Erreur lors du nettoyage', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur de connexion', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Animation d'entrée pour les notifications
document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('.list-group-item');
    notifications.forEach((notification, index) => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            notification.style.transition = 'all 0.3s ease';
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection
