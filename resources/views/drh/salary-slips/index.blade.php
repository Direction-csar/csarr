@extends('layouts.drh')

@section('title', 'Bulletins de Paie - DRH')

@section('content')
<div class="drh-interface">
    <!-- Page Title -->
    <div class="drh-page-title">
        <h1>Bulletins de Paie</h1>
        <p>Direction des Ressources Humaines - Gestion des bulletins de paie</p>
    </div>

    <!-- Statistics Cards -->
    <div class="drh-stats-grid">
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">0</div>
                    <div class="drh-stat-label">Total Bulletins</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>
        
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">0</div>
                    <div class="drh-stat-label">Payés</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">0</div>
                    <div class="drh-stat-label">En Attente</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions and Search -->
    <div class="drh-card drh-fade-in mb-6">
        <div class="drh-card-header">
            <div class="drh-card-title">
                <i class="fas fa-tools"></i>
                Actions et Recherche
            </div>
        </div>
        <div class="drh-card-body">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <a href="{{ route('drh.salary-slips.create') }}" class="drh-btn drh-btn-primary">
                    <i class="fas fa-plus"></i>
                    Nouveau Bulletin
                </a>
                
                <div class="flex gap-3 flex-1 justify-end">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Rechercher par personnel..." 
                               class="drh-form-input pl-10 w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    
                    <select class="drh-form-input">
                        <option value="">Tous les statuts</option>
                        <option value="paye">Payé</option>
                        <option value="en_attente">En attente</option>
                        <option value="en_cours">En cours</option>
                    </select>
                    
                    <button class="drh-btn drh-btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Salary Slips List -->
    <div class="drh-card drh-fade-in">
        <div class="drh-card-header">
            <div class="drh-card-title">
                <i class="fas fa-file-invoice-dollar"></i>
                Liste des Bulletins de Paie
            </div>
        </div>
        <div class="drh-card-body">
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mb-6">
                    <i class="fas fa-file-invoice-dollar text-6xl text-gray-300"></i>
                </div>
                <h3 class="drh-heading-3 text-gray-600 mb-2">Aucun bulletin de paie</h3>
                <p class="drh-text-large text-gray-500 mb-6">
                    Commencez par créer le premier bulletin de paie.
                </p>
                <a href="{{ route('drh.salary-slips.create') }}" class="drh-btn drh-btn-primary drh-btn-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Créer un bulletin
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Icônes plus visibles pour les bulletins de paie */
.drh-stat-card:nth-child(1) .drh-stat-icon {
    background: linear-gradient(135deg, #059669, #10b981);
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

.drh-stat-card:nth-child(2) .drh-stat-icon {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.drh-stat-card:nth-child(3) .drh-stat-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.drh-stat-card:nth-child(1)::before {
    background: linear-gradient(90deg, #059669, #10b981);
}

.drh-stat-card:nth-child(2)::before {
    background: linear-gradient(90deg, #22c55e, #16a34a);
}

.drh-stat-card:nth-child(3)::before {
    background: linear-gradient(90deg, #f59e0b, #d97706);
}

/* Amélioration des icônes */
.drh-stat-icon i {
    font-size: 1.75rem;
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
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

/* Icônes des cartes */
.drh-card-title i {
    font-size: 1.5rem;
    color: #059669;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* Icônes dans le contenu */
.drh-card-body .fas,
.drh-card-body .fa {
    font-size: 2rem;
    color: #059669;
    opacity: 0.8;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* Effet hover */
.drh-stat-card:hover .drh-stat-icon {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}

.drh-stat-card:hover .drh-stat-number {
    color: #059669;
    transition: color 0.2s ease;
}
</style>
@endsection