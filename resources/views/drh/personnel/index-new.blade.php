@extends('layouts.drh')

@section('title', 'Gestion du Personnel - DRH')

@section('content')
<div class="drh-interface">
    <!-- Page Title -->
    <div class="drh-page-title">
        <h1>Gestion du Personnel</h1>
        <p>Direction des Ressources Humaines - Gestion centralisée du personnel CSAR</p>
    </div>

    <!-- Statistics Cards -->
    <div class="drh-stats-grid">
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">{{ $totalPersonnel ?? 0 }}</div>
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
                    <div class="drh-stat-number">{{ $validatedCount ?? 0 }}</div>
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
                    <div class="drh-stat-number">{{ $pendingCount ?? 0 }}</div>
                    <div class="drh-stat-label">En Attente</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">{{ $rejectedCount ?? 0 }}</div>
                    <div class="drh-stat-label">Rejetés</div>
                </div>
                <div class="drh-stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions and Filters -->
    <div class="drh-card drh-fade-in">
        <div class="drh-card-header">
            <div class="drh-card-title">
                <i class="fas fa-tools"></i>
                Actions DRH
            </div>
            <div class="flex gap-2">
                <a href="{{ route('drh.personnel.create') }}" class="drh-btn drh-btn-primary drh-btn-sm">
                    <i class="fas fa-user-plus"></i>
                    Ajouter du Personnel
                </a>
                <a href="{{ route('drh.personnel.export-pdf') }}" class="drh-btn drh-btn-success drh-btn-sm">
                    <i class="fas fa-file-pdf"></i>
                    Export PDF
                </a>
                <a href="{{ route('drh.personnel.export-excel') }}" class="drh-btn drh-btn-warning drh-btn-sm">
                    <i class="fas fa-file-excel"></i>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="drh-card-body">
            <!-- Search and Filters -->
            <form method="GET" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="drh-form-group">
                        <label class="drh-form-label">Rechercher</label>
                        <input type="text" name="search" 
                               value="{{ request('search') }}" 
                               class="drh-form-input" 
                               placeholder="Nom, matricule, email...">
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Direction</label>
                        <select name="direction" class="drh-form-input">
                            <option value="">Toutes directions</option>
                            @foreach($directions ?? [] as $direction)
                                <option value="{{ $direction }}" {{ request('direction') == $direction ? 'selected' : '' }}>
                                    {{ $direction }} ({{ $directionCounts[$direction] ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Poste</label>
                        <select name="poste" class="drh-form-input">
                            <option value="">Tous postes</option>
                            @foreach($postes ?? [] as $poste)
                                <option value="{{ $poste }}" {{ request('poste') == $poste ? 'selected' : '' }}>
                                    {{ $poste }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">Trier par</label>
                        <select name="sort" class="drh-form-input">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                            <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date recrutement</option>
                            <option value="poste" {{ request('sort') == 'poste' ? 'selected' : '' }}>Poste</option>
                        </select>
                    </div>

                    <div class="drh-form-group">
                        <label class="drh-form-label">&nbsp;</label>
                        <div class="flex gap-2">
                            <button type="submit" class="drh-btn drh-btn-primary drh-btn-sm">
                                <i class="fas fa-search"></i>
                                Filtrer
                            </button>
                            <a href="{{ route('drh.personnel.index') }}" class="drh-btn drh-btn-secondary drh-btn-sm">
                                <i class="fas fa-refresh"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Personnel Table -->
    <div class="drh-card drh-fade-in">
        <div class="drh-card-header">
            <div class="drh-card-title">
                <i class="fas fa-users"></i>
                Liste du Personnel
                <span class="drh-badge drh-badge-primary ml-2">{{ $personnel->total() ?? 0 }} employé(s)</span>
            </div>
        </div>
        <div class="drh-card-body p-0">
            @if(isset($personnel) && $personnel->count() > 0)
                <div class="drh-table-container">
                    <table class="drh-table">
                        <thead>
                            <tr>
                                <th class="text-center">Photo</th>
                                <th>Informations Personnelles</th>
                                <th>Poste & Direction</th>
                                <th>Contact</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personnel as $personne)
                            <tr class="hover:bg-gray-50">
                                <td class="text-center">
                                    <div class="relative">
                                        @if($personne->photo_personnelle)
                                            <img src="{{ Storage::url('personnel/' . $personne->photo_personnelle) }}" 
                                                 alt="Photo de {{ $personne->prenoms_nom }}" 
                                                 class="w-12 h-12 rounded-full object-cover mx-auto border-2 border-gray-200">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center mx-auto border-2 border-gray-200">
                                                <i class="fas fa-user text-gray-500 text-lg"></i>
                                            </div>
                                        @endif
                                        @if($personne->statut_validation == 'Valide')
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="space-y-1">
                                        <h4 class="font-semibold text-gray-900 text-base">{{ $personne->prenoms_nom ?? 'N/A' }}</h4>
                                        <div class="flex items-center gap-2">
                                            <span class="drh-text-small text-gray-500">
                                                <i class="fas fa-id-card mr-1"></i>
                                                {{ $personne->matricule ?? 'N/A' }}
                                            </span>
                                        </div>
                                        @if($personne->date_recrutement_csar)
                                            <div class="drh-text-small text-gray-400">
                                                <i class="fas fa-calendar mr-1"></i>
                                                Recruté le {{ \Carbon\Carbon::parse($personne->date_recrutement_csar)->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-briefcase text-gray-400 text-sm"></i>
                                            <span class="font-medium text-gray-900">{{ $personne->poste_actuel ?? 'Non défini' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-building text-gray-400 text-sm"></i>
                                            <span class="drh-text-small text-gray-600">{{ $personne->direction_service ?? 'Non défini' }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
                                    <div class="space-y-1">
                                        @if($personne->email)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-envelope text-gray-400 text-sm"></i>
                                                <a href="mailto:{{ $personne->email }}" class="drh-text-small text-blue-600 hover:text-blue-800">
                                                    {{ $personne->email }}
                                                </a>
                                            </div>
                                        @endif
                                        @if($personne->contact_telephonique)
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-phone text-gray-400 text-sm"></i>
                                                <a href="tel:{{ $personne->contact_telephonique }}" class="drh-text-small text-gray-600">
                                                    {{ $personne->contact_telephonique }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <td>
                                    @php
                                        $statusClass = match($personne->statut_validation) {
                                            'Valide' => 'drh-badge-success',
                                            'En attente' => 'drh-badge-warning',
                                            'Rejeté' => 'drh-badge-danger',
                                            default => 'drh-badge-secondary'
                                        };
                                        $statusText = $personne->statut_validation ?? 'En attente';
                                    @endphp
                                    <span class="drh-badge {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                
                                <td class="text-center">
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('drh.personnel.show', $personne) }}" 
                                           class="drh-btn drh-btn-sm drh-btn-secondary" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('drh.personnel.edit', $personne) }}" 
                                           class="drh-btn drh-btn-sm drh-btn-primary" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('drh.personnel.export-fiche-pdf', $personne) }}" 
                                           class="drh-btn drh-btn-sm drh-btn-warning" 
                                           title="Exporter PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <form action="{{ route('drh.personnel.destroy', $personne) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $personne->prenoms_nom }} ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="drh-btn drh-btn-sm drh-btn-danger" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($personnel) && $personnel->hasPages())
                    <div class="drh-card-footer">
                        <div class="flex justify-center">
                            {{ $personnel->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="mb-6">
                        <i class="fas fa-users text-6xl text-gray-300"></i>
                    </div>
                    <h3 class="drh-heading-3 text-gray-600 mb-2">Aucun personnel trouvé</h3>
                    <p class="drh-text-large text-gray-500 mb-6">
                        @if(request()->hasAny(['search', 'direction', 'poste']))
                            Aucun personnel ne correspond à vos critères de recherche.
                        @else
                            Commencez par ajouter du personnel à votre équipe.
                        @endif
                    </p>
                    <a href="{{ route('drh.personnel.create') }}" class="drh-btn drh-btn-primary drh-btn-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        Ajouter le premier employé
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Améliorations spécifiques pour la page personnel */
.drh-table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--professional-text-primary);
    padding: 1rem 1.5rem;
}

.drh-table td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
}

.drh-table tbody tr {
    transition: all 0.2s ease;
}

.drh-table tbody tr:hover {
    background: var(--professional-gray-50);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .drh-stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .drh-table-container {
        overflow-x: auto;
    }
    
    .drh-table {
        min-width: 800px;
    }
    
    .drh-table th,
    .drh-table td {
        padding: 0.75rem 1rem;
    }
}

@media (max-width: 480px) {
    .drh-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .drh-page-title h1 {
        font-size: 1.5rem;
    }
    
    .drh-page-title p {
        font-size: 0.875rem;
    }
}
</style>
@endsection
