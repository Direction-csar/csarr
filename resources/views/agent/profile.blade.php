@extends('layouts.agent')

@section('title', 'Mon Profil - Agent CSAR')

@section('content')
<div class="container-fluid">
    <div class="profile-header">
        <h1><i class="fas fa-user-circle"></i> Mon Profil</h1>
        <p>Informations personnelles et professionnelles</p>
    </div>

    <div class="profile-content">
        <!-- Informations principales -->
        <div class="profile-section">
            <div class="section-header">
                <h2><i class="fas fa-id-card"></i> Informations principales</h2>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Matricule</label>
                    <span class="value">{{ $personnel->matricule ?? 'Non assigné' }}</span>
                </div>
                <div class="info-item">
                    <label>Nom complet</label>
                    <span class="value">{{ $personnel->prenoms_nom }}</span>
                </div>
                <div class="info-item">
                    <label>Poste actuel</label>
                    <span class="value">{{ $personnel->poste_actuel }}</span>
                </div>
                <div class="info-item">
                    <label>Direction/Service</label>
                    <span class="value">{{ $personnel->direction_service }}</span>
                </div>
                <div class="info-item">
                    <label>Date de recrutement</label>
                    <span class="value">
                        @if($personnel->date_recrutement_csar)
                            {{ \Carbon\Carbon::parse($personnel->date_recrutement_csar)->format('d/m/Y') }}
                        @else
                            Non spécifiée
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <label>Statut</label>
                    <span class="value status-{{ $personnel->statut_validation }}">
                        @if($personnel->statut_validation === 'valide')
                            <i class="fas fa-check-circle"></i> Validé
                        @elseif($personnel->statut_validation === 'en_attente')
                            <i class="fas fa-clock"></i> En attente
                        @else
                            <i class="fas fa-times-circle"></i> Non validé
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Informations de contact -->
        <div class="profile-section">
            <div class="section-header">
                <h2><i class="fas fa-address-book"></i> Informations de contact</h2>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Email professionnel</label>
                    <span class="value">{{ $personnel->email ?? $user->email }}</span>
                </div>
                <div class="info-item">
                    <label>Numéro de téléphone</label>
                    <span class="value">{{ $personnel->contact_telephonique ?? 'Non spécifié' }}</span>
                </div>
                <div class="info-item">
                    <label>Adresse complète</label>
                    <span class="value">{{ $personnel->adresse_complete ?? 'Non spécifiée' }}</span>
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="profile-section">
            <div class="section-header">
                <h2><i class="fas fa-user"></i> Informations personnelles</h2>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Date de naissance</label>
                    <span class="value">
                        @if($personnel->date_naissance)
                            {{ \Carbon\Carbon::parse($personnel->date_naissance)->format('d/m/Y') }}
                        @else
                            Non spécifiée
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <label>Lieu de naissance</label>
                    <span class="value">{{ $personnel->lieu_naissance ?? 'Non spécifié' }}</span>
                </div>
                <div class="info-item">
                    <label>Nationalité</label>
                    <span class="value">{{ $personnel->nationalite ?? 'Non spécifiée' }}</span>
                </div>
                <div class="info-item">
                    <label>Sexe</label>
                    <span class="value">{{ $personnel->sexe ?? 'Non spécifié' }}</span>
                </div>
                <div class="info-item">
                    <label>Situation matrimoniale</label>
                    <span class="value">{{ $personnel->situation_matrimoniale ?? 'Non spécifiée' }}</span>
                </div>
                <div class="info-item">
                    <label>Groupe sanguin</label>
                    <span class="value">{{ $personnel->groupe_sanguin ?? 'Non spécifié' }}</span>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div class="profile-section">
            <div class="section-header">
                <h2><i class="fas fa-briefcase"></i> Informations professionnelles</h2>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Type de contrat</label>
                    <span class="value">{{ $personnel->statut ?? 'Non spécifié' }}</span>
                </div>
                <div class="info-item">
                    <label>Date de prise de service</label>
                    <span class="value">
                        @if($personnel->date_prise_service_csar)
                            {{ \Carbon\Carbon::parse($personnel->date_prise_service_csar)->format('d/m/Y') }}
                        @else
                            Non spécifiée
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <label>Localisation (région)</label>
                    <span class="value">{{ $personnel->localisation_region ?? 'Non spécifiée' }}</span>
                </div>
                <div class="info-item">
                    <label>Dernier poste avant CSAR</label>
                    <span class="value">{{ $personnel->dernier_poste_avant_csar ?? 'Non spécifié' }}</span>
                </div>
            </div>
        </div>

        <!-- Formation et compétences -->
        <div class="profile-section">
            <div class="section-header">
                <h2><i class="fas fa-graduation-cap"></i> Formation et compétences</h2>
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <label>Diplôme académique</label>
                    <span class="value">{{ $personnel->diplome_academique ?? 'Non spécifié' }}</span>
                </div>
                <div class="info-item">
                    <label>Formations professionnelles</label>
                    <span class="value">{{ $personnel->formations_professionnelles ?? 'Non spécifiées' }}</span>
                </div>
                <div class="info-item">
                    <label>Langues parlées</label>
                    <span class="value">
                        @if($personnel->langues_parlees)
                            @foreach(json_decode($personnel->langues_parlees) as $langue)
                                <span class="badge">{{ $langue }}</span>
                            @endforeach
                        @else
                            Non spécifiées
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <label>Logiciels maîtrisés</label>
                    <span class="value">
                        @if($personnel->logiciels_maitrises)
                            @foreach(json_decode($personnel->logiciels_maitrises) as $logiciel)
                                <span class="badge">{{ $logiciel }}</span>
                            @endforeach
                        @else
                            Non spécifiés
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="profile-actions">
            <div class="action-buttons">
                <a href="{{ route('agent.profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier mon profil
                </a>
                <a href="{{ route('agent.profile.change-password') }}" class="btn btn-secondary">
                    <i class="fas fa-key"></i> Changer mot de passe
                </a>
                <a href="{{ route('agent.profile.pdf') }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Télécharger fiche PDF
                </a>
                <a href="{{ route('agent.hr.index') }}" class="btn btn-info">
                    <i class="fas fa-folder"></i> Mes documents RH
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.container-fluid {
    padding: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.profile-header {
    text-align: center;
    margin-bottom: 2rem;
    color: white;
}

.profile-header h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.profile-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

.profile-content {
    max-width: 1200px;
    margin: 0 auto;
}

.profile-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.section-header {
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 1rem;
}

.section-header h2 {
    color: #2d3748;
    font-size: 1.5rem;
    margin: 0;
    font-weight: 600;
}

.section-header h2 i {
    color: #667eea;
    margin-right: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item label {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item .value {
    color: #2d3748;
    font-size: 1.1rem;
    font-weight: 600;
    padding: 0.75rem;
    background: #f7fafc;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

.status-valide {
    color: #38a169 !important;
}

.status-en_attente {
    color: #d69e2e !important;
}

.status-rejete {
    color: #e53e3e !important;
}

.badge {
    display: inline-block;
    background: #667eea;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    margin: 0.25rem;
}

.profile-actions {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5a67d8;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #718096;
    color: white;
}

.btn-secondary:hover {
    background: #4a5568;
    transform: translateY(-2px);
}

.btn-success {
    background: #38a169;
    color: white;
}

.btn-success:hover {
    background: #2f855a;
    transform: translateY(-2px);
}

.btn-info {
    background: #3182ce;
    color: white;
}

.btn-info:hover {
    background: #2c5aa0;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .profile-header h1 {
        font-size: 2rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}
</style>
@endsection 