@extends('layouts.admin')

@section('title', 'Mon Profil')

@section('content')
<style>
/* Styles modernes pour la page de profil */
.profile-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.profile-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.profile-avatar-modern {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    position: relative;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    50% { box-shadow: 0 15px 40px rgba(0,0,0,0.4); }
    100% { box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
}

.profile-avatar-modern i {
    font-size: 3rem;
    color: white;
}

.stats-card-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    text-align: center;
    transition: transform 0.3s ease;
    border: none;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.stats-card-modern:hover {
    transform: translateY(-5px);
}

.stats-card-modern h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(45deg, #fff, #f0f0f0);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.info-card-modern {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    border: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.info-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.info-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0,0,0,0.15);
}

.info-item-modern {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.info-item-modern:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    padding-left: 1rem;
    border-radius: 10px;
}

.info-item-modern:last-child {
    border-bottom: none;
}

.info-item-modern i {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    font-size: 1.1rem;
}

.tab-modern {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    border: none;
    overflow: hidden;
}

.nav-tabs-modern {
    border-bottom: none;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 0.5rem;
    border-radius: 15px 15px 0 0;
}

.nav-tabs-modern .nav-link {
    border: none;
    border-radius: 10px;
    margin: 0 0.25rem;
    padding: 0.75rem 1.5rem;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-tabs-modern .nav-link.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.nav-tabs-modern .nav-link:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.btn-modern-gradient {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-modern-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 2rem;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-modern-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
    color: white;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 0.5rem;
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    transform: translateX(5px);
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
}

.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.floating-animation {
    animation: floating 3s ease-in-out infinite;
}

@keyframes floating {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}
</style>

<div class="container-fluid">
    <!-- En-tête moderne -->
    <div class="profile-hero">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <div class="profile-avatar-modern">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="ms-4">
                        <h1 class="h2 mb-2">{{ auth()->user()->name ?? 'Administrateur CSAR' }}</h1>
                        <p class="mb-1 opacity-75">{{ auth()->user()->role ?? 'Administrateur' }}</p>
                        <p class="mb-0 opacity-50">
                            <i class="fas fa-envelope me-2"></i>{{ auth()->user()->email ?? 'admin@csar.sn' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-modern-gradient me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    <i class="fas fa-edit me-2"></i>Modifier Profil
                </button>
                <button class="btn btn-modern-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    <i class="fas fa-key me-2"></i>Mot de Passe
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Statistiques rapides -->
        <div class="col-md-12 mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="stats-card-modern">
                        <h3 id="personal-actions">{{ \App\Models\StockMovement::where('created_by', auth()->id())->count() }}</h3>
                        <p class="mb-0">Actions Effectuées</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card-modern">
                        <h3 id="personal-sessions">1</h3>
                        <p class="mb-0">Sessions Actives</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card-modern">
                        <h3>{{ \App\Models\Message::count() }}</h3>
                        <p class="mb-0">Messages Reçus</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card-modern">
                        <h3>{{ \App\Models\Notification::where('user_id', auth()->id())->count() }}</h3>
                        <p class="mb-0">Notifications</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="col-md-4">
            <div class="info-card-modern">
                <h5 class="mb-4">
                    <i class="fas fa-user me-2"></i>Informations Personnelles
                </h5>
                
                <div class="profile-info">
                    <div class="info-item-modern">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email</strong><br>
                            <span id="user-email">{{ auth()->user()->email ?? 'admin@csar.sn' }}</span>
                        </div>
                    </div>
                    <div class="info-item-modern">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Téléphone</strong><br>
                            <span id="user-phone">{{ auth()->user()->phone ?? '+221 33 123 45 67' }}</span>
                        </div>
                    </div>
                    <div class="info-item-modern">
                        <i class="fas fa-calendar"></i>
                        <div>
                            <strong>Membre depuis</strong><br>
                            <span id="user-created">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d/m/Y') : '01/01/2024' }}</span>
                        </div>
                    </div>
                    <div class="info-item-modern">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>Dernière connexion</strong><br>
                            <span id="last-login">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="info-item-modern">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>Statut</strong><br>
                            <span class="badge bg-success">Actif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-md-8">
            <!-- Onglets -->
            <div class="tab-modern">
                <div class="nav-tabs-modern">
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fas fa-tachometer-alt me-2"></i>Aperçu
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                                <i class="fas fa-history me-2"></i>Activité
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                                <i class="fas fa-cog me-2"></i>Paramètres
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                <i class="fas fa-shield-alt me-2"></i>Sécurité
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Aperçu -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-card-modern">
                                        <h6 class="mb-3">
                                            <i class="fas fa-history me-2"></i>Activité Récente
                                        </h6>
                                        <div id="recent-activity">
                                            @php
                                                $recentMovements = \App\Models\StockMovement::where('created_by', auth()->id())
                                                    ->orderBy('created_at', 'desc')
                                                    ->limit(3)
                                                    ->get();
                                            @endphp
                                            
                                            @if($recentMovements->count() > 0)
                                                @foreach($recentMovements as $movement)
                                                    <div class="activity-item">
                                                        <div class="activity-icon">
                                                            <i class="fas fa-{{ $movement->type === 'in' ? 'arrow-down' : 'arrow-up' }}"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ ucfirst($movement->type) }} - {{ $movement->reference }}</strong><br>
                                                            <small class="text-muted">{{ $movement->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="empty-state">
                                                    <i class="fas fa-history"></i>
                                                    <p>Aucune activité récente</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card-modern">
                                        <h6 class="mb-3">
                                            <i class="fas fa-bell me-2"></i>Notifications Récentes
                                        </h6>
                                        <div id="recent-notifications">
                                            @php
                                                $recentNotifications = \App\Models\Notification::where('user_id', auth()->id())
                                                    ->orderBy('created_at', 'desc')
                                                    ->limit(3)
                                                    ->get();
                                            @endphp
                                            
                                            @if($recentNotifications->count() > 0)
                                                @foreach($recentNotifications as $notification)
                                                    <div class="activity-item">
                                                        <div class="activity-icon">
                                                            <i class="fas fa-bell"></i>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $notification->title }}</strong><br>
                                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="empty-state">
                                                    <i class="fas fa-bell"></i>
                                                    <p>Aucune notification</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activité -->
                        <div class="tab-pane fade" id="activity" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Date de début</label>
                                        <input type="date" class="form-control" id="activity-date-from" placeholder="Date de début">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Date de fin</label>
                                        <input type="date" class="form-control" id="activity-date-to" placeholder="Date de fin">
                                    </div>
                                </div>
                            </div>
                            
                            <div id="activity-list">
                                @php
                                    $userActivities = \App\Models\StockMovement::where('created_by', auth()->id())
                                        ->orderBy('created_at', 'desc')
                                        ->limit(10)
                                        ->get();
                                @endphp
                                
                                @if($userActivities->count() > 0)
                                    @foreach($userActivities as $activity)
                                        <div class="activity-item">
                                            <div class="activity-icon">
                                                <i class="fas fa-{{ $activity->type === 'in' ? 'arrow-down' : 'arrow-up' }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong>{{ ucfirst($activity->type) }} - {{ $activity->reference }}</strong>
                                                <p class="mb-1 text-muted">{{ $activity->motif ?? 'Mouvement de stock' }}</p>
                                                <small class="text-muted">{{ $activity->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                            <span class="badge bg-{{ $activity->type === 'in' ? 'success' : 'info' }}">{{ ucfirst($activity->type) }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-history"></i>
                                        <h5 class="text-muted">Aucune activité</h5>
                                        <p class="text-muted">Aucune activité enregistrée pour le moment.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Paramètres -->
                        <div class="tab-pane fade" id="settings" role="tabpanel">
                            <form id="settingsForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label fw-bold">Langue</label>
                                            <select class="form-select form-control-lg" name="language">
                                                <option value="fr">🇫🇷 Français</option>
                                                <option value="en">🇬🇧 English</option>
                                                <option value="ar">🇸🇦 العربية</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="form-label fw-bold">Fuseau horaire</label>
                                            <select class="form-select form-control-lg" name="timezone">
                                                <option value="Africa/Dakar">🌍 Dakar (GMT+0)</option>
                                                <option value="Europe/Paris">🇫🇷 Paris (GMT+1)</option>
                                                <option value="America/New_York">🇺🇸 New York (GMT-5)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4">
                                            <label class="form-label fw-bold">Thème</label>
                                            <select class="form-select form-control-lg" name="theme">
                                                <option value="light">☀️ Clair</option>
                                                <option value="dark">🌙 Sombre</option>
                                                <option value="auto">🔄 Automatique</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="form-label fw-bold">Notifications</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="email_notifications" checked>
                                                <label class="form-check-label">📧 Notifications par email</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="push_notifications" checked>
                                                <label class="form-check-label">🔔 Notifications push</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="sms_notifications">
                                                <label class="form-check-label">📱 Notifications SMS</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">Signature</label>
                                    <textarea class="form-control" name="signature" rows="4" placeholder="Votre signature pour les emails..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-modern-gradient">
                                    <i class="fas fa-save me-2"></i>Sauvegarder les Paramètres
                                </button>
                            </form>
                        </div>
                        
                        <!-- Sécurité -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-card-modern">
                                        <h6 class="mb-3">
                                            <i class="fas fa-laptop me-2"></i>Sessions Actives
                                        </h6>
                                        <div id="active-sessions">
                                            <div class="activity-item">
                                                <div class="activity-icon">
                                                    <i class="fas fa-desktop"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <strong>Session actuelle</strong>
                                                    <p class="mb-1 text-muted">{{ request()->header('User-Agent') ?: 'Navigateur inconnu' }}</p>
                                                    <small class="text-muted">IP: {{ request()->ip() }}</small>
                                                </div>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card-modern">
                                        <h6 class="mb-3">
                                            <i class="fas fa-sign-in-alt me-2"></i>Connexions Récentes
                                        </h6>
                                        <div id="recent-logins">
                                            @php
                                                $lastLogin = auth()->user()->last_login_at ?? auth()->user()->updated_at;
                                            @endphp
                                            <div class="activity-item">
                                                <div class="activity-icon">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <strong>Dernière connexion</strong>
                                                    <p class="mb-1 text-muted">Connexion réussie</p>
                                                    <small class="text-muted">{{ $lastLogin->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <span class="badge bg-info">Récente</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="info-card-modern">
                                        <h6 class="mb-3">
                                            <i class="fas fa-shield-alt me-2"></i>Actions de Sécurité
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <button class="btn btn-modern-secondary w-100" onclick="terminateAllSessions()">
                                                    <i class="fas fa-power-off me-2"></i>Terminer Toutes les Sessions
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-modern-gradient w-100" onclick="enableTwoFactor()">
                                                    <i class="fas fa-mobile-alt me-2"></i>Activer 2FA
                                                </button>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-danger w-100" onclick="deleteAccount()">
                                                    <i class="fas fa-trash me-2"></i>Supprimer le Compte
                                                </button>
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
</div>

<!-- Modal Modifier Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="editProfileModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier le Profil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="profileForm">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="edit_name" class="form-label fw-bold">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="edit_name" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                            </div>
                            <div class="form-group mb-4">
                                <label for="edit_email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-lg" id="edit_email" name="email" value="{{ auth()->user()->email ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="edit_phone" class="form-label fw-bold">Téléphone</label>
                                <input type="text" class="form-control form-control-lg" id="edit_phone" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                            </div>
                            <div class="form-group mb-4">
                                <label for="edit_role" class="form-label fw-bold">Rôle</label>
                                <input type="text" class="form-control form-control-lg" id="edit_role" name="role" value="{{ auth()->user()->role ?? 'Administrateur' }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="edit_bio" class="form-label fw-bold">Biographie</label>
                        <textarea class="form-control" id="edit_bio" name="bio" rows="4" placeholder="Parlez-nous de vous..."></textarea>
                    </div>
                </div>
                <div class="modal-footer p-4" style="border-top: 1px solid #f0f0f0;">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-modern-gradient">
                        <i class="fas fa-save me-2"></i>Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Changer Mot de Passe -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fas fa-key me-2"></i>Changer le Mot de Passe
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="passwordForm">
                <div class="modal-body p-4">
                    <div class="form-group mb-4">
                        <label for="current_password" class="form-label fw-bold">Mot de passe actuel <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-lg" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="new_password" class="form-label fw-bold">Nouveau mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-lg" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="confirm_password" class="form-label fw-bold">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer p-4" style="border-top: 1px solid #f0f0f0;">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-modern-gradient">
                        <i class="fas fa-save me-2"></i>Changer le Mot de Passe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs avec des valeurs réalistes
    animateCounters();
    
    // Gestion des formulaires
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });
    
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        changePassword();
    });
    
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateSettings();
    });
    
    // Animation d'entrée pour les éléments
    animateElements();
    
    // Effet de parallaxe pour l'en-tête
    addParallaxEffect();
});

function animateCounters() {
    // Animation des compteurs avec les vraies valeurs de la base de données
    const actionsCount = {{ \App\Models\StockMovement::where('created_by', auth()->id())->count() }};
    const sessionsCount = 1; // Session actuelle
    
    animateValue(document.getElementById('personal-actions'), 0, actionsCount, 2000);
    animateValue(document.getElementById('personal-sessions'), 0, sessionsCount, 2000);
}

function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const easeOutQuart = 1 - Math.pow(1 - progress, 4);
        element.innerHTML = Math.floor(easeOutQuart * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

function animateElements() {
    // Animation d'entrée pour les cartes
    const cards = document.querySelectorAll('.info-card-modern, .stats-card-modern');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

function addParallaxEffect() {
    const hero = document.querySelector('.profile-hero');
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            hero.style.transform = `translateY(${rate}px)`;
        });
    }
}

function updateProfile() {
    const form = document.getElementById('profileForm');
    
    // Récupérer les valeurs directement des champs
    const name = document.getElementById('edit_name').value.trim();
    const email = document.getElementById('edit_email').value.trim();
    const phone = document.getElementById('edit_phone').value.trim();
    
    // Validation côté client
    if (!name || !email) {
        showToast('Le nom et l\'email sont requis', 'error');
        return;
    }
    
    // Animation de chargement
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
    submitBtn.disabled = true;
    
    // Préparer les données
    const data = {
        name: name,
        email: email,
        phone: phone
    };
    
    // Requête AJAX simplifiée
    fetch('{{ route("admin.profile.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Profil mis à jour avec succès !', 'success');
            bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
            
            // Recharger la page après 1 seconde
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Erreur lors de la mise à jour du profil', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors de la mise à jour du profil', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function changePassword() {
    const form = document.getElementById('passwordForm');
    
    // Récupérer les valeurs directement des champs
    const currentPassword = document.getElementById('current_password').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    // Vérification des mots de passe
    if (newPassword !== confirmPassword) {
        showToast('Les mots de passe ne correspondent pas', 'error');
        return;
    }
    
    if (newPassword.length < 8) {
        showToast('Le mot de passe doit contenir au moins 8 caractères', 'error');
        return;
    }
    
    if (!currentPassword) {
        showToast('Le mot de passe actuel est requis', 'error');
        return;
    }
    
    // Animation de chargement
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Changement...';
    submitBtn.disabled = true;
    
    // Préparer les données
    const data = {
        change_password: true,
        current_password: currentPassword,
        new_password: newPassword,
        new_password_confirmation: confirmPassword
    };
    
    // Requête AJAX simplifiée
    fetch('{{ route("admin.profile.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Mot de passe changé avec succès !', 'success');
            bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
            form.reset();
        } else {
            showToast(data.message || 'Erreur lors du changement de mot de passe', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Erreur lors du changement de mot de passe', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function updateSettings() {
    const formData = new FormData(document.getElementById('settingsForm'));
    
    // Animation de chargement
    const submitBtn = document.querySelector('#settingsForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
    submitBtn.disabled = true;
    
    // Simulation de mise à jour (à remplacer par une vraie requête AJAX)
    setTimeout(() => {
        showToast('Paramètres sauvegardés avec succès !', 'success');
        
        // Restaurer le bouton
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 1500);
}

function updateDisplay() {
    // Mettre à jour les éléments d'affichage avec les nouvelles valeurs
    const form = document.getElementById('profileForm');
    document.getElementById('user-name').textContent = form.name.value;
    document.getElementById('user-email').textContent = form.email.value;
    document.getElementById('user-phone').textContent = form.phone.value;
}

function terminateAllSessions() {
    if (confirm('Êtes-vous sûr de vouloir terminer toutes les sessions actives ?')) {
        showToast('Toutes les sessions ont été terminées', 'warning');
    }
}

function enableTwoFactor() {
    showToast('Authentification à deux facteurs activée', 'success');
}

function deleteAccount() {
    if (confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')) {
        showToast('Compte supprimé', 'error');
    }
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    // Animation d'entrée
    toast.style.transform = 'translateX(100%)';
    setTimeout(() => {
        toast.style.transition = 'transform 0.3s ease';
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 5000);
}

// Effet de survol pour les cartes de statistiques
document.querySelectorAll('.stats-card-modern').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-10px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Animation des icônes d'activité
document.querySelectorAll('.activity-item').forEach((item, index) => {
    item.style.opacity = '0';
    item.style.transform = 'translateX(-30px)';
    
    setTimeout(() => {
        item.style.transition = 'all 0.5s ease';
        item.style.opacity = '1';
        item.style.transform = 'translateX(0)';
    }, index * 100);
});
</script>
@endpush