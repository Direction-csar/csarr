@extends('layouts.admin')

@section('title', 'Communications - Admin CSAR')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">📱 Communications</h1>
            <p class="text-muted">Gestion centralisée des communications internes et externes</p>
        </div>
        <div>
            <button class="btn btn-primary" id="refreshStats">
                <i class="fas fa-sync-alt"></i> Actualiser
            </button>
        </div>
    </div>

    <!-- Statistiques Globales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Communications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-total-communications">
                                {{ $stats['total_communications'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aujourd'hui
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-today-communications">
                                {{ $stats['today_communications'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Messages Non Lus
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-unread-messages">
                                {{ $stats['unread_messages'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Notifications Non Lues
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-unread-notifications">
                                {{ $stats['unread_notifications'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets de Navigation -->
    <ul class="nav nav-tabs mb-4" id="communicationTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab">
                <i class="fas fa-envelope"></i> Messages ({{ $stats['total_messages'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                <i class="fas fa-bell"></i> Notifications ({{ $stats['total_notifications'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="newsletter-tab" data-bs-toggle="tab" data-bs-target="#newsletter" type="button" role="tab">
                <i class="fas fa-newspaper"></i> Newsletter ({{ $stats['total_newsletters'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="subscribers-tab" data-bs-toggle="tab" data-bs-target="#subscribers" type="button" role="tab">
                <i class="fas fa-users"></i> Abonnés ({{ $stats['total_subscribers'] }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="audit-tab" data-bs-toggle="tab" data-bs-target="#audit" type="button" role="tab">
                <i class="fas fa-history"></i> Audit
            </button>
        </li>
    </ul>

    <!-- Contenu des Onglets -->
    <div class="tab-content" id="communicationTabsContent">
        <!-- Messages -->
        <div class="tab-pane fade show active" id="messages" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Messages Récents</h6>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Total</div>
                                <div class="stat-value">{{ $stats['total_messages'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Non lus</div>
                                <div class="stat-value text-danger">{{ $stats['unread_messages'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Aujourd'hui</div>
                                <div class="stat-value text-success">{{ $stats['today_messages'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Cette semaine</div>
                                <div class="stat-value text-info">{{ $stats['week_messages'] }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Expéditeur</th>
                                    <th>Destinataire</th>
                                    <th>Objet</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentMessages as $message)
                                <tr class="{{ !$message->lu ? 'table-info' : '' }}">
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $message->expediteur_nom ?? 'N/A' }}</td>
                                    <td>{{ $message->destinataire_nom ?? 'N/A' }}</td>
                                    <td>{{ $message->objet ?? 'Sans objet' }}</td>
                                    <td>
                                        @if($message->lu)
                                            <span class="badge badge-success">Lu</span>
                                        @else
                                            <span class="badge badge-warning">Non lu</span>
                                        @endif
                                    </td>
                                    <td>{{ $message->created_at ? \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.messages.show', $message->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucun message récent</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="tab-pane fade" id="notifications" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Notifications Récentes</h6>
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Total</div>
                                <div class="stat-value">{{ $stats['total_notifications'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Non lues</div>
                                <div class="stat-value text-danger">{{ $stats['unread_notifications'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Aujourd'hui</div>
                                <div class="stat-value text-success">{{ $stats['today_notifications'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Cette semaine</div>
                                <div class="stat-value text-info">{{ $stats['week_notifications'] }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Message</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentNotifications as $notification)
                                <tr class="{{ !$notification->read ? 'table-info' : '' }}">
                                    <td>{{ $notification->id }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $notification->type ?? 'Info' }}</span>
                                    </td>
                                    <td>{{ $notification->message ?? 'N/A' }}</td>
                                    <td>
                                        @if($notification->read)
                                            <span class="badge badge-success">Lue</span>
                                        @else
                                            <span class="badge badge-warning">Non lue</span>
                                        @endif
                                    </td>
                                    <td>{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="markAsRead({{ $notification->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucune notification récente</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="tab-pane fade" id="newsletter" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Campagnes Newsletter</h6>
                    <a href="{{ route('admin.newsletter.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Total</div>
                                <div class="stat-value">{{ $stats['total_newsletters'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Envoyées</div>
                                <div class="stat-value text-success">{{ $stats['sent_newsletters'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">En attente</div>
                                <div class="stat-value text-warning">{{ $stats['pending_newsletters'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Aujourd'hui</div>
                                <div class="stat-value text-info">{{ $stats['today_newsletters'] }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Sujet</th>
                                    <th>Statut</th>
                                    <th>Destinataires</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentNewsletters as $newsletter)
                                <tr>
                                    <td>{{ $newsletter->id }}</td>
                                    <td>{{ $newsletter->title ?? 'N/A' }}</td>
                                    <td>{{ $newsletter->subject ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($newsletter->status) {
                                                'sent' => 'success',
                                                'sending' => 'info',
                                                'pending' => 'warning',
                                                'draft' => 'secondary',
                                                'failed' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $statusClass }}">{{ ucfirst($newsletter->status) }}</span>
                                    </td>
                                    <td>{{ $newsletter->recipients_count ?? 0 }}</td>
                                    <td>{{ $newsletter->created_at ? \Carbon\Carbon::parse($newsletter->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucune campagne récente</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Abonnés -->
        <div class="tab-pane fade" id="subscribers" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Abonnés Newsletter</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Total</div>
                                <div class="stat-value">{{ $stats['total_subscribers'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Actifs</div>
                                <div class="stat-value text-success">{{ $stats['active_subscribers'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Aujourd'hui</div>
                                <div class="stat-value text-info">{{ $stats['today_subscribers'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-box">
                                <div class="stat-label">Cette semaine</div>
                                <div class="stat-value text-primary">{{ $stats['week_subscribers'] }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Nom</th>
                                    <th>Statut</th>
                                    <th>Date inscription</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSubscribers as $subscriber)
                                <tr>
                                    <td>{{ $subscriber->id }}</td>
                                    <td>{{ $subscriber->email ?? 'N/A' }}</td>
                                    <td>{{ $subscriber->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($subscriber->status === 'subscribed')
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($subscriber->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $subscriber->subscribed_at ? \Carbon\Carbon::parse($subscriber->subscribed_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                    <td>{{ $subscriber->source ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Aucun abonné récent</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit -->
        <div class="tab-pane fade" id="audit" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Logs d'Audit des Communications</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Action</th>
                                    <th>Utilisateur</th>
                                    <th>Détails</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $log->action ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $log->user_name ?? 'Système' }}</td>
                                    <td>{{ $log->details ?? 'N/A' }}</td>
                                    <td>{{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun log d'audit récent</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-box {
    background: #f8f9fc;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    border-left: 4px solid #4e73df;
}

.stat-label {
    font-size: 0.85rem;
    color: #858796;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #5a5c69;
}

.table-info {
    background-color: rgba(78, 115, 223, 0.05);
}

.nav-tabs .nav-link {
    color: #4e73df;
    font-weight: 600;
}

.nav-tabs .nav-link.active {
    color: #4e73df;
    border-color: #dee2e6 #dee2e6 #fff;
}

.nav-tabs .nav-link:hover {
    border-color: #e3e6f0 #e3e6f0 #dee2e6;
}
</style>

<script>
// Rafraîchir les statistiques en temps réel
document.getElementById('refreshStats').addEventListener('click', function() {
    // Ajouter animation de chargement
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualisation...';
    this.disabled = true;
    
    // Appel AJAX pour récupérer les statistiques
    fetch('{{ route("admin.communications.realtime-stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour les statistiques
                document.getElementById('stat-total-communications').textContent = data.stats.total_communications;
                document.getElementById('stat-today-communications').textContent = data.stats.today_communications;
                document.getElementById('stat-unread-messages').textContent = data.stats.unread_messages;
                document.getElementById('stat-unread-notifications').textContent = data.stats.unread_notifications;
                
                // Recharger la page pour afficher les nouvelles données
                setTimeout(() => {
                    location.reload();
                }, 500);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'actualisation des statistiques');
        })
        .finally(() => {
            this.innerHTML = '<i class="fas fa-sync-alt"></i> Actualiser';
            this.disabled = false;
        });
});

// Marquer une notification comme lue
function markAsRead(notificationId) {
    if (confirm('Marquer cette notification comme lue ?')) {
        // Implémenter l'appel AJAX
        console.log('Marquer comme lue:', notificationId);
        // TODO: Ajouter l'appel AJAX
    }
}

// Auto-rafraîchissement toutes les 30 secondes
setInterval(function() {
    fetch('{{ route("admin.communications.realtime-stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('stat-total-communications').textContent = data.stats.total_communications;
                document.getElementById('stat-today-communications').textContent = data.stats.today_communications;
                document.getElementById('stat-unread-messages').textContent = data.stats.unread_messages;
                document.getElementById('stat-unread-notifications').textContent = data.stats.unread_notifications;
            }
        })
        .catch(error => console.error('Erreur auto-refresh:', error));
}, 30000); // 30 secondes
</script>
@endsection

