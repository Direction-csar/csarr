@extends('layouts.admin')

@section('title', 'Communication')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-comments me-2"></i>Communication
                    </h1>
                    <p class="text-muted mb-0">Gérez la communication interne et externe du CSAR</p>
                </div>
                <div>
                    <button class="btn btn-info-modern btn-modern" onclick="sendBroadcast()">
                        <i class="fas fa-bullhorn me-2"></i>Diffusion
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-primary);">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="mb-1" id="total-messages">{{ \App\Models\Message::count() ?? 0 }}</h3>
                    <p class="text-muted mb-0">Messages Envoyés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="mb-1" id="delivered-messages">{{ \App\Models\Message::where('lu', true)->count() ?? 0 }}</h3>
                    <p class="text-muted mb-0">Livrés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-warning);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="mb-1" id="pending-messages">{{ \App\Models\Message::where('lu', false)->count() ?? 0 }}</h3>
                    <p class="text-muted mb-0">En Attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-info);">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="mb-1" id="active-channels">3</h3>
                    <p class="text-muted mb-0">Canaux Actifs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="communicationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab">
                                <i class="fas fa-envelope me-2"></i>Messages
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="channels-tab" data-bs-toggle="tab" data-bs-target="#channels" type="button" role="tab">
                                <i class="fas fa-broadcast-tower me-2"></i>Canaux
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button" role="tab">
                                <i class="fas fa-file-alt me-2"></i>Modèles
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="analytics-tab" data-bs-toggle="tab" data-bs-target="#analytics" type="button" role="tab">
                                <i class="fas fa-chart-line me-2"></i>Analytiques
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="communicationTabsContent">
                        <!-- Messages -->
                        <div class="tab-pane fade show active" id="messages" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="message-search" placeholder="Rechercher des messages...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="form-select" id="message-filter">
                                            <option value="">Tous les messages</option>
                                            <option value="sent">Envoyés</option>
                                            <option value="received">Reçus</option>
                                            <option value="draft">Brouillons</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary-modern btn-modern w-100" onclick="applyMessageFilters()">
                                        <i class="fas fa-search me-2"></i>Filtrer
                                    </button>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-success-modern btn-modern w-100" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div id="messages-list">
                                @if($communications && $communications->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="50px"></th>
                                                    <th>Expéditeur</th>
                                                    <th>Sujet</th>
                                                    <th>Date</th>
                                                    <th>Statut</th>
                                                    <th width="100px">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($communications as $message)
                                                <tr class="{{ !$message->lu ? 'fw-bold' : '' }}">
                                                    <td>
                                                        <div class="icon-3d" style="background: var(--gradient-primary); width: 35px; height: 35px;">
                                                            <i class="fas fa-envelope"></i>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>{{ $message->expediteur }}</div>
                                                        <small class="text-muted">{{ $message->email_expediteur }}</small>
                                                    </td>
                                                    <td>
                                                        {{ $message->sujet }}
                                                        <br><small class="text-muted">{{ Str::limit($message->contenu, 50) }}</small>
                                                    </td>
                                                    <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        @if($message->lu)
                                                            <span class="badge bg-success">Lu</span>
                                                        @else
                                                            <span class="badge bg-warning">Non lu</span>
                                                        @endif
                                                        @if($message->reponse)
                                                            <span class="badge bg-info">Répondu</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary" onclick="viewMessage({{ $message->id }})" title="Voir">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        @if(!$message->reponse)
                                                        <button class="btn btn-sm btn-success" onclick="replyMessage({{ $message->id }})" title="Répondre">
                                                            <i class="fas fa-reply"></i>
                                                        </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $communications->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <h5 class="text-muted">Aucun message</h5>
                                        <p class="text-muted">Commencez par envoyer un message.</p>
                                        <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#newMessageModal">
                                            <i class="fas fa-plus me-2"></i>Nouveau message
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Canaux -->
                        <div class="tab-pane fade" id="channels" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="channel-search" placeholder="Rechercher des canaux...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-success-modern btn-modern w-100" data-bs-toggle="modal" data-bs-target="#newChannelModal">
                                        <i class="fas fa-plus me-2"></i>Nouveau Canal
                                    </button>
                                </div>
                            </div>
                            
                            <div id="channels-list">
                                <div class="text-center py-5">
                                    <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                        <i class="fas fa-broadcast-tower"></i>
                                    </div>
                                    <h5 class="text-muted">Aucun canal</h5>
                                    <p class="text-muted">Créez des canaux de communication.</p>
                                    <button class="btn btn-success-modern btn-modern" data-bs-toggle="modal" data-bs-target="#newChannelModal">
                                        <i class="fas fa-plus me-2"></i>Créer un canal
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modèles -->
                        <div class="tab-pane fade" id="templates" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="template-search" placeholder="Rechercher des modèles...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-info-modern btn-modern w-100" data-bs-toggle="modal" data-bs-target="#newTemplateModal">
                                        <i class="fas fa-plus me-2"></i>Nouveau Modèle
                                    </button>
                                </div>
                            </div>
                            
                            <div id="templates-list">
                                <div class="text-center py-5">
                                    <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h5 class="text-muted">Aucun modèle</h5>
                                    <p class="text-muted">Créez des modèles de messages.</p>
                                    <button class="btn btn-info-modern btn-modern" data-bs-toggle="modal" data-bs-target="#newTemplateModal">
                                        <i class="fas fa-plus me-2"></i>Créer un modèle
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Analytiques -->
                        <div class="tab-pane fade" id="analytics" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-modern">
                                        <div class="card-header">
                                            <h6 class="mb-0">Messages par Mois</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="messagesChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-modern">
                                        <div class="card-header">
                                            <h6 class="mb-0">Canaux les Plus Utilisés</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="channelsChart" height="200"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouveau Message -->
<div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newMessageModalLabel">
                    <i class="fas fa-plus me-2"></i>Nouveau Message
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="messageForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="recipient" class="form-label">Destinataire <span class="text-danger">*</span></label>
                                <select class="form-select" id="recipient" name="recipient" required>
                                    <option value="">Sélectionner un destinataire</option>
                                    <option value="all">Tous les utilisateurs</option>
                                    <option value="admin">Administrateurs</option>
                                    <option value="personnel">Personnel</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="channel" class="form-label">Canal</label>
                                <select class="form-select" id="channel" name="channel">
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="notification">Notification</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="subject" class="form-label">Sujet <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="message_content" class="form-label">Message <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message_content" name="message_content" rows="6" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="urgent" name="urgent">
                                <label class="form-check-label" for="urgent">
                                    Message urgent
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="schedule" name="schedule">
                                <label class="form-check-label" for="schedule">
                                    Programmer l'envoi
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3" id="schedule-date" style="display: none;">
                        <label for="schedule_date" class="form-label">Date d'envoi</label>
                        <input type="datetime-local" class="form-control" id="schedule_date" name="schedule_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nouveau Canal -->
<div class="modal fade" id="newChannelModal" tabindex="-1" aria-labelledby="newChannelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newChannelModalLabel">
                    <i class="fas fa-broadcast-tower me-2"></i>Nouveau Canal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="channelForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="channel_name" class="form-label">Nom du canal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="channel_name" name="channel_name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="channel_type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="channel_type" name="channel_type" required>
                            <option value="">Sélectionner un type</option>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="notification">Notification</option>
                            <option value="webhook">Webhook</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="channel_description" class="form-label">Description</label>
                        <textarea class="form-control" id="channel_description" name="channel_description" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="channel_active" name="channel_active" checked>
                        <label class="form-check-label" for="channel_active">
                            Canal actif
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success-modern">
                        <i class="fas fa-save me-2"></i>Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nouveau Modèle -->
<div class="modal fade" id="newTemplateModal" tabindex="-1" aria-labelledby="newTemplateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTemplateModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Nouveau Modèle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="templateForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="template_name" class="form-label">Nom du modèle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="template_name" name="template_name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="template_subject" class="form-label">Sujet <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="template_subject" name="template_subject" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="template_content" class="form-label">Contenu <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="template_content" name="template_content" rows="8" required></textarea>
                        <div class="form-text">Utilisez {nom}, {email}, etc. pour les variables</div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="template_category" class="form-label">Catégorie</label>
                        <select class="form-select" id="template_category" name="template_category">
                            <option value="general">Général</option>
                            <option value="notification">Notification</option>
                            <option value="alert">Alerte</option>
                            <option value="welcome">Bienvenue</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info-modern">
                        <i class="fas fa-save me-2"></i>Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    animateCounters();
    
    // Gestion des formulaires
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });
    
    document.getElementById('channelForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createChannel();
    });
    
    document.getElementById('templateForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createTemplate();
    });
    
    // Gestion de la programmation
    document.getElementById('schedule').addEventListener('change', function() {
        const scheduleDate = document.getElementById('schedule-date');
        scheduleDate.style.display = this.checked ? 'block' : 'none';
    });
    
    // Initialiser les graphiques
    initCharts();
});

function animateCounters() {
    // Animation des compteurs (pour l'instant à 0)
    animateValue(document.getElementById('total-messages'), 0, 0, 1000);
    animateValue(document.getElementById('delivered-messages'), 0, 0, 1000);
    animateValue(document.getElementById('pending-messages'), 0, 0, 1000);
    animateValue(document.getElementById('active-channels'), 0, 0, 1000);
}

function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

function sendMessage() {
    const formData = new FormData(document.getElementById('messageForm'));
    
    // Simulation d'envoi (à remplacer par une vraie requête AJAX)
    showToast('Message envoyé avec succès !', 'success');
    document.getElementById('messageForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('newMessageModal')).hide();
    
    // Mettre à jour la liste
    loadMessages();
}

function createChannel() {
    const formData = new FormData(document.getElementById('channelForm'));
    
    // Simulation de création (à remplacer par une vraie requête AJAX)
    showToast('Canal créé avec succès !', 'success');
    document.getElementById('channelForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('newChannelModal')).hide();
    
    // Mettre à jour la liste
    loadChannels();
}

function createTemplate() {
    const formData = new FormData(document.getElementById('templateForm'));
    
    // Simulation de création (à remplacer par une vraie requête AJAX)
    showToast('Modèle créé avec succès !', 'success');
    document.getElementById('templateForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('newTemplateModal')).hide();
    
    // Mettre à jour la liste
    loadTemplates();
}

function applyMessageFilters() {
    // Simulation de filtrage (à remplacer par une vraie requête AJAX)
    showToast('Filtres appliqués', 'info');
}

function loadMessages() {
    // Simulation de chargement (à remplacer par une vraie requête AJAX)
    // Pour l'instant, la liste reste vide
}

function loadChannels() {
    // Simulation de chargement des canaux
}

function loadTemplates() {
    // Simulation de chargement des modèles
}

function sendBroadcast() {
    showToast('Diffusion en cours...', 'info');
}

function initCharts() {
    // Graphique des messages par mois
    const messagesCtx = document.getElementById('messagesChart').getContext('2d');
    new Chart(messagesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Messages',
                data: [0, 0, 0, 0, 0, 0],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Graphique des canaux
    const channelsCtx = document.getElementById('channelsChart').getContext('2d');
    new Chart(channelsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Email', 'SMS', 'Notification'],
            datasets: [{
                data: [0, 0, 0],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 205, 86, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
</script>
@endpush