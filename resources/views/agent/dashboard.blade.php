@extends('layouts.agent')

@section('title', 'Tableau de Bord - Agent CSAR')

@section('content')
<style>
    .agent-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .welcome-section {
        margin-bottom: 2rem;
    }

    .welcome-card {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .welcome-content h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .welcome-details {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .welcome-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .profile-summary {
        margin-bottom: 2rem;
    }

    .summary-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(124, 58, 237, 0.1);
    }

    .summary-card h3 {
        color: #1e293b;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .summary-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        border-left: 4px solid #7c3aed;
    }

    .summary-item .label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        display: block;
        margin-bottom: 0.25rem;
    }

    .summary-item .value {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 600;
    }

    .stats-section {
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(124, 58, 237, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin-bottom: 1rem;
    }

    .stat-content h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .stat-content p {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    .requests-section {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        border: 1px solid rgba(124, 58, 237, 0.1);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .requests-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }

    .request-card {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 0.75rem;
        border-left: 4px solid;
        transition: transform 0.2s ease;
    }

    .request-card:hover {
        transform: translateX(5px);
    }

    .request-card.pending {
        border-left-color: #f59e0b;
    }

    .request-card.approved {
        border-left-color: #059669;
    }

    .request-card.rejected {
        border-left-color: #dc2626;
    }

    .request-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .request-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .request-type {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .request-type.help {
        background: #dbeafe;
        color: #1e40af;
    }

    .request-type.partnership {
        background: #fef3c7;
        color: #92400e;
    }

    .request-type.audience {
        background: #dcfce7;
        color: #166534;
    }

    .request-info {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .request-status {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
    }

    .request-status.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .request-status.approved {
        background: #dcfce7;
        color: #166534;
    }

    .request-status.rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .action-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(124, 58, 237, 0.1);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .action-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .action-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .action-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .action-btn {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 600;
        transition: transform 0.2s ease;
        display: inline-block;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        color: white;
    }

    @media (max-width: 768px) {
        .agent-container {
            padding: 1rem;
        }
        
        .welcome-card {
            flex-direction: column;
            text-align: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .requests-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="agent-container">
    <!-- Message de bienvenue -->
    <div class="welcome-section">
        <div class="welcome-card">
            <div class="welcome-content">
                <h1>{{ $welcomeMessage['greeting'] }}, {{ $welcomeMessage['name'] }} !</h1>
                <p class="welcome-subtitle">{{ $welcomeMessage['full_name'] }}</p>
                <p class="welcome-details">
                    <i class="fas fa-briefcase"></i> {{ $welcomeMessage['poste'] }} | 
                    <i class="fas fa-building"></i> {{ $welcomeMessage['direction'] }}
                </p>
            </div>
            <div class="welcome-avatar">
                @if($user->photo)
                    <img src="{{ asset('storage/' . $user->photo) }}" alt="Photo de profil" class="avatar-img">
                @else
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Résumé du profil -->
    <div class="profile-summary">
        <div class="summary-card">
            <h3><i class="fas fa-user-circle"></i> Résumé de mon profil</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="label">Poste actuel</span>
                    <span class="value">{{ $profileSummary['poste_actuel'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Direction/Service</span>
                    <span class="value">{{ $profileSummary['direction_service'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Date de recrutement</span>
                    <span class="value">{{ $profileSummary['date_recrutement'] }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Contrat</span>
                    <span class="value">{{ $profileSummary['contrat_type'] }} - {{ $profileSummary['contrat_statut'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques personnelles -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['documents_total'] }}</h3>
                    <p>Documents RH</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['bulletins_total'] }}</h3>
                    <p>Bulletins de salaire</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['bulletins_ce_mois'] }}</h3>
                    <p>Bulletins ce mois</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['documents_recents'] }}</h3>
                    <p>Documents récents</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Actions rapides -->
    <div class="quick-actions">
        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-user-edit"></i>
            </div>
            <h4 class="action-title">Mon Profil</h4>
            <p class="action-description">Gérer mes informations personnelles</p>
            <a href="{{ route('agent.profile') }}" class="action-btn">
                <i class="fas fa-user-edit"></i> Profil
            </a>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h4 class="action-title">Documents RH</h4>
            <p class="action-description">Accéder à mes documents</p>
            <a href="{{ route('agent.hr.documents.index') }}" class="action-btn">
                <i class="fas fa-file-alt"></i> Documents
            </a>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <h4 class="action-title">Bulletins de salaire</h4>
            <p class="action-description">Consulter mes bulletins</p>
            <a href="{{ route('agent.hr.salary-slips.index') }}" class="action-btn">
                <i class="fas fa-file-invoice-dollar"></i> Bulletins
            </a>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h4 class="action-title">Statistiques</h4>
            <p class="action-description">Voir mes statistiques</p>
            <a href="{{ route('agent.hr.statistics') }}" class="action-btn">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
        </div>
    </div>
</div>

<style>
/* RESPONSIVE - DASHBOARDS INTERNES */
@media (max-width: 1200px) {
    .dashboard-grid { grid-template-columns: repeat(2, 1fr) !important; }
    .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
}

@media (max-width: 992px) {
    .dashboard-grid { grid-template-columns: 1fr !important; }
    .widget-card { padding: 1.5rem !important; }
    .chart-container { height: 300px !important; }
    .sidebar-content { width: 100% !important; margin-top: 2rem; }
}

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr !important; }
    .stat-card { padding: 1.5rem !important; margin-bottom: 1rem; }
    .page-header { flex-direction: column !important; align-items: flex-start !important; gap: 1rem; }
    .page-title { font-size: 1.5rem !important; }
    .page-actions { width: 100%; flex-direction: column; gap: 0.5rem; }
    .page-actions .btn { width: 100%; justify-content: center; }
    .chart-container { height: 250px !important; }
    .table-responsive { overflow-x: auto; }
    .table { font-size: 0.85rem; }
    .card-body { padding: 1rem !important; }
}

@media (max-width: 480px) {
    .page-title { font-size: 1.3rem !important; }
    .stat-value { font-size: 1.8rem !important; }
    .stat-card { padding: 1rem !important; }
}
</style>
@endsection 