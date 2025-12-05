@extends('layouts.admin')

@section('page-title', 'Centre de notifications')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="stat-card text-center p-3 bg-primary-soft rounded">
                                <i class="fas fa-bell fa-2x text-primary mb-2"></i>
                                <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                                <p class="text-muted mb-0">Total</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center p-3 bg-warning-soft rounded">
                                <i class="fas fa-envelope fa-2x text-warning mb-2"></i>
                                <h3 class="mb-0">{{ $stats['unread'] ?? 0 }}</h3>
                                <p class="text-muted mb-0">Non lues</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center p-3 bg-success-soft rounded">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h3 class="mb-0">{{ $stats['read'] ?? 0 }}</h3>
                                <p class="text-muted mb-0">Lues</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card text-center p-3 bg-info-soft rounded">
                                <i class="fas fa-calendar-day fa-2x text-info mb-2"></i>
                                <h3 class="mb-0">{{ $stats['new_today'] ?? 0 }}</h3>
                                <p class="text-muted mb-0">Aujourd'hui</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" data-filter="all">
                                <i class="fas fa-list"></i> Toutes
                            </button>
                            <button type="button" class="btn btn-outline-warning" data-filter="unread">
                                <i class="fas fa-envelope"></i> Non lues
                            </button>
                            <button type="button" class="btn btn-outline-success" data-filter="read">
                                <i class="fas fa-check"></i> Lues
                            </button>
                        </div>
                        
                        <button type="button" class="btn btn-primary" onclick="markAllAsRead()">
                            <i class="fas fa-check-double"></i> Tout marquer comme lu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des notifications -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="notifications-list">
                            @foreach($notifications as $notification)
                                <div class="notification-item {{ $notification->read ? 'read' : 'unread' }}" 
                                     data-notification-id="{{ $notification->id }}"
                                     data-status="{{ $notification->read ? 'read' : 'unread' }}">
                                    <div class="notification-icon">
                                        <i class="fas fa-{{ $notification->icon ?? 'bell' }} 
                                           text-{{ $notification->type === 'success' ? 'success' : ($notification->type === 'error' ? 'danger' : ($notification->type === 'warning' ? 'warning' : 'info')) }}"></i>
                                    </div>
                                    <div class="notification-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="notification-title mb-1">
                                                {{ $notification->title }}
                                                @if(!$notification->read)
                                                    <span class="badge bg-primary ms-2">Nouveau</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted">{{ $notification->time_ago }}</small>
                                        </div>
                                        <p class="notification-message mb-2">{{ $notification->message }}</p>
                                        
                                        <div class="notification-actions">
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                            @endif
                                            
                                            @if($notification->read)
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-secondary" 
                                                        onclick="markAsUnread({{ $notification->id }})">
                                                    <i class="fas fa-envelope"></i> Marquer non lu
                                                </button>
                                            @else
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success" 
                                                        onclick="markAsRead({{ $notification->id }})">
                                                    <i class="fas fa-check"></i> Marquer lu
                                                </button>
                                            @endif
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteNotification({{ $notification->id }})">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune notification</h5>
                            <p class="text-muted">Vous n'avez pas encore de notifications.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
.bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
.bg-info-soft { background-color: rgba(13, 202, 240, 0.1); }

.notification-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background-color: rgba(13, 110, 253, 0.05);
    border-left: 3px solid #0d6efd;
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.notification-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: #f8f9fa;
}

.notification-icon i {
    font-size: 1.2rem;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-weight: 600;
    color: #212529;
}

.notification-message {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.notification-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}
</style>

<script>
// Filtrer les notifications
document.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.dataset.filter;
        
        // Mise à jour des boutons actifs
        document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Filtrer les notifications
        document.querySelectorAll('.notification-item').forEach(item => {
            if (filter === 'all') {
                item.style.display = 'flex';
            } else {
                item.style.display = item.dataset.status === filter ? 'flex' : 'none';
            }
        });
    });
});

// Marquer comme lu
function markAsRead(notificationId) {
    fetch(`/admin/api/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
            item.classList.remove('unread');
            item.classList.add('read');
            item.dataset.status = 'read';
            
            // Rafraîchir la page pour mettre à jour les stats
            location.reload();
        }
    })
    .catch(error => console.error('Erreur:', error));
}

// Marquer comme non lu
function markAsUnread(notificationId) {
    fetch(`/admin/api/notifications/${notificationId}/mark-unread`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
            item.classList.remove('read');
            item.classList.add('unread');
            item.dataset.status = 'unread';
            
            // Rafraîchir la page pour mettre à jour les stats
            location.reload();
        }
    })
    .catch(error => console.error('Erreur:', error));
}

// Marquer toutes comme lues
function markAllAsRead() {
    if (!confirm('Voulez-vous vraiment marquer toutes les notifications comme lues ?')) {
        return;
    }
    
    fetch('/admin/api/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Erreur:', error));
}

// Supprimer une notification
function deleteNotification(notificationId) {
    if (!confirm('Voulez-vous vraiment supprimer cette notification ?')) {
        return;
    }
    
    fetch(`/admin/api/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`[data-notification-id="${notificationId}"]`).remove();
            
            // Vérifier s'il reste des notifications
            if (document.querySelectorAll('.notification-item').length === 0) {
                location.reload();
            }
        }
    })
    .catch(error => console.error('Erreur:', error));
}
</script>
@endsection
