@extends('layouts.drh')

@section('title', 'Tableau de Bord DRH')

@section('content')
<div class="drh-interface">
    <!-- Page Title -->
    <div class="drh-page-title">
        <h1>Tableau de Bord - Direction des Ressources Humaines</h1>
        <p>Gestion centralisée des ressources humaines du CSAR</p>
    </div>

    <!-- Statistics Cards -->
    <div class="drh-stats-grid">
        <div class="drh-stat-card drh-fade-in">
            <div class="drh-stat-header">
                <div>
                    <div class="drh-stat-number">{{ $stats['total_personnel'] ?? 0 }}</div>
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

    <!-- Quick Actions -->
    <div class="drh-card drh-fade-in">
        <div class="drh-card-header">
            <div class="drh-card-title">
                <i class="fas fa-bolt"></i>
                Actions Rapides DRH
            </div>
        </div>
        <div class="drh-card-body">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('drh.documents.create') }}" class="drh-btn drh-btn-primary">
                    <i class="fas fa-plus"></i>
                    Nouveau Document RH
                </a>
                <a href="{{ route('drh.attendance.create') }}" class="drh-btn drh-btn-success">
                    <i class="fas fa-clock"></i>
                    Enregistrer Présence
                </a>
                <a href="{{ route('drh.salary-slips.create') }}" class="drh-btn drh-btn-warning">
                    <i class="fas fa-file-invoice-dollar"></i>
                    Nouveau Bulletin
                </a>
                <a href="{{ route('drh.statistics') }}" class="drh-btn drh-btn-secondary">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques RH
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <a href="{{ route('drh.personnel.index') }}" class="drh-btn drh-btn-secondary">
                    <i class="fas fa-users"></i>
                    Gestion du Personnel
                </a>
                <a href="{{ route('drh.personnel.create') }}" class="drh-btn drh-btn-success">
                    <i class="fas fa-user-plus"></i>
                    Ajouter du Personnel
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Documents -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-file-alt"></i>
                    Documents RH Récents
                </div>
                <a href="{{ route('drh.documents.index') }}" class="drh-btn drh-btn-sm drh-btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    Voir tout
                </a>
            </div>
            <div class="drh-card-body">
                @if(isset($recentDocuments) && $recentDocuments->count() > 0)
                    <div class="drh-table-container">
                        <table class="drh-table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Personnel</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDocuments as $document)
                                <tr>
                                    <td>
                                        <span class="drh-badge drh-badge-primary">
                                            {{ ucfirst(str_replace('_', ' ', $document->type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $document->personnel->nom ?? 'N/A' }}</td>
                                    <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="drh-badge drh-badge-{{ $document->statut == 'actif' ? 'success' : 'warning' }}">
                                            {{ ucfirst($document->statut) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-500 py-8">
                                        <i class="fas fa-file-alt text-4xl mb-4"></i>
                                        <p>Aucun document récent</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Aucun document récent à afficher.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Salary Slips -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-money-bill-wave"></i>
                    Bulletins de Salaire Récents
                </div>
                <a href="{{ route('drh.salary-slips.index') }}" class="drh-btn drh-btn-sm drh-btn-secondary">
                    <i class="fas fa-arrow-right"></i>
                    Voir tout
                </a>
            </div>
            <div class="drh-card-body">
                @if(isset($recentSalarySlips) && $recentSalarySlips->count() > 0)
                    <div class="drh-table-container">
                        <table class="drh-table">
                            <thead>
                                <tr>
                                    <th>Personnel</th>
                                    <th>Période</th>
                                    <th>Salaire</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSalarySlips as $slip)
                                <tr>
                                    <td>{{ $slip->personnel->nom ?? 'N/A' }}</td>
                                    <td>{{ $slip->periode_debut->format('m/Y') }}</td>
                                    <td>{{ number_format($slip->salaire_brut, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        <span class="drh-badge drh-badge-{{ $slip->statut == 'paye' ? 'success' : 'warning' }}">
                                            {{ ucfirst($slip->statut) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-500 py-8">
                                        <i class="fas fa-money-bill-wave text-4xl mb-4"></i>
                                        <p>Aucun bulletin récent</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-money-bill-wave text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Aucun bulletin récent à afficher.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div class="drh-card drh-fade-in">
        <div class="drh-card-header">
            <div class="drh-card-title">
                <i class="fas fa-calendar-day"></i>
                Présence du jour ({{ date('d/m/Y') }})
            </div>
            <span class="drh-badge drh-badge-info">
                {{ $stats['presence_aujourd_hui'] ?? 0 }} personnes présentes
            </span>
        </div>
        <div class="drh-card-body">
            <div class="drh-alert drh-alert-info">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>{{ $stats['presence_aujourd_hui'] ?? 0 }}</strong> membres du personnel sont présents aujourd'hui.
                </div>
            </div>
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
