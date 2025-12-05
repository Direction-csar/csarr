@extends('layouts.drh')

@section('title', 'Statistiques RH - DRH')

@section('content')
<div class="drh-interface">
    <!-- Page Title -->
    <div class="drh-page-title">
        <h1>Statistiques RH</h1>
        <p>Direction des Ressources Humaines - Tableaux de bord et analyses</p>
    </div>

    <!-- Statistics Overview -->
    <div class="drh-stats-grid">
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">{{ $stats['total_personnel'] ?? 6 }}</div>
                    <div class="drh-stat-label">Total Personnel</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">{{ $stats['personnel_valide'] ?? 0 }}</div>
                    <div class="drh-stat-label">Personnel Validé</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
        
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">{{ $stats['documents_actifs'] ?? 0 }}</div>
                    <div class="drh-stat-label">Documents Actifs</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">{{ $stats['documents_expires'] ?? 0 }}</div>
                    <div class="drh-stat-label">Documents Expirés</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Personnel by Department -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-chart-pie"></i>
                    Personnel par Direction
                </div>
            </div>
            <div class="drh-card-body">
                <div class="text-center py-12">
                    <i class="fas fa-chart-pie text-6xl text-gray-300 mb-4"></i>
                    <h3 class="drh-heading-3 text-gray-600 mb-2">Graphique en cours de développement</h3>
                    <p class="drh-text-large text-gray-500">
                        Les statistiques détaillées seront bientôt disponibles.
                    </p>
                </div>
            </div>
        </div>

        <!-- Personnel by Status -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-chart-bar"></i>
                    Personnel par Statut
                </div>
            </div>
            <div class="drh-card-body">
                <div class="text-center py-12">
                    <i class="fas fa-chart-bar text-6xl text-gray-300 mb-4"></i>
                    <h3 class="drh-heading-3 text-gray-600 mb-2">Graphique en cours de développement</h3>
                    <p class="drh-text-large text-gray-500">
                        Les statistiques détaillées seront bientôt disponibles.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Attendance Statistics -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-calendar-day"></i>
                    Présence par jour - {{ date('m/Y') }}
                </div>
            </div>
            <div class="drh-card-body">
                <div class="text-center py-8">
                    <i class="fas fa-calendar-day text-4xl text-gray-300 mb-4"></i>
                    <p class="drh-text-large text-gray-500">
                        Aucune donnée de présence pour ce mois
                    </p>
                </div>
            </div>
        </div>

        <!-- Documents by Type -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-file-alt"></i>
                    Documents par type
                </div>
            </div>
            <div class="drh-card-body">
                <div class="text-center py-8">
                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                    <p class="drh-text-large text-gray-500">
                        Aucun document enregistré
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="drh-card drh-fade-in">
        <div class="drh-card-header">
            <div class="drh-card-title">
                <i class="fas fa-download"></i>
                Exports et Rapports
            </div>
        </div>
        <div class="drh-card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="drh-btn drh-btn-primary drh-btn-block">
                    <i class="fas fa-file-pdf"></i>
                    Export PDF
                </button>
                <button class="drh-btn drh-btn-success drh-btn-block">
                    <i class="fas fa-file-excel"></i>
                    Export Excel
                </button>
                <button class="drh-btn drh-btn-secondary drh-btn-block">
                    <i class="fas fa-chart-line"></i>
                    Rapport Détaillé
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Icônes plus visibles pour les statistiques */
.drh-stat-card:nth-child(1) .drh-stat-icon {
    background: linear-gradient(135deg, #059669, #10b981) !important;
    box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
    border: 3px solid rgba(255, 255, 255, 0.2);
}

.drh-stat-card:nth-child(2) .drh-stat-icon {
    background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    border: 3px solid rgba(255, 255, 255, 0.2);
}

.drh-stat-card:nth-child(3) .drh-stat-icon {
    background: linear-gradient(135deg, #f59e0b, #fbbf24) !important;
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    border: 3px solid rgba(255, 255, 255, 0.2);
}

.drh-stat-card:nth-child(4) .drh-stat-icon {
    background: linear-gradient(135deg, #dc2626, #ef4444) !important;
    box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    border: 3px solid rgba(255, 255, 255, 0.2);
}

.drh-stat-card:nth-child(1)::before {
    background: linear-gradient(90deg, #059669, #10b981);
}

.drh-stat-card:nth-child(2)::before {
    background: linear-gradient(90deg, #2563eb, #3b82f6);
}

.drh-stat-card:nth-child(3)::before {
    background: linear-gradient(90deg, #f59e0b, #fbbf24);
}

.drh-stat-card:nth-child(4)::before {
    background: linear-gradient(90deg, #dc2626, #ef4444);
}

/* Icônes des cartes plus grandes et plus visibles */
.drh-card-title i {
    font-size: 1.5rem;
    color: #059669;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* Icônes dans le contenu des cartes */
.drh-card-body .fas,
.drh-card-body .fa {
    font-size: 4rem !important;
    color: #059669 !important;
    opacity: 1 !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    display: block !important;
    visibility: visible !important;
}

/* Icônes dans les titres de cartes */
.drh-card-title i {
    font-size: 1.5rem !important;
    color: #059669 !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Icônes des boutons d'export */
.drh-btn i {
    font-size: 1rem;
    margin-right: 0.5rem;
}

/* Amélioration des statistiques */
.drh-stat-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: auto;
}

.drh-stat-icon i {
    font-size: 2.5rem !important;
    color: white !important;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    font-weight: 900;
}

.drh-stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1f2937;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.drh-stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
}

/* Espacement amélioré */
.drh-card-body {
    min-height: 200px;
}

/* Amélioration des titres */
.drh-heading-3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #374151;
}

.drh-text-large {
    font-size: 1.125rem;
    color: #6b7280;
}

/* Effet hover pour les cartes */
.drh-stat-card:hover .drh-stat-icon {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

.drh-stat-card:hover .drh-stat-number {
    color: #059669;
    transition: color 0.2s ease;
}

/* Animation de pulsation pour les icônes */
.drh-stat-icon {
    animation: iconPulse 2s infinite;
}

@keyframes iconPulse {
    0% {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
    50% {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    100% {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
}

/* Assurer que les icônes sont toujours visibles */
.drh-stat-icon i {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>
@endsection
