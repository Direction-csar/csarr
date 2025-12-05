@extends('layouts.drh')

@section('title', 'Tableau de Bord DRH')

@section('content')
<div class="drh-interface">
    <div class="drh-page-title">
        <h1>Tableau de Bord - Direction des Ressources Humaines</h1>
        <p>Gestion centralisée des ressources humaines du CSAR</p>
    </div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stats-card">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="stats-icon bg-soft-success">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stats-number">{{ $stats['totalPersonnel'] ?? 0 }}</div>
                <div class="stats-label">Total Personnel</div>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="stats-icon bg-soft-primary">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <div class="stats-number">{{ $stats['validatedPersonnel'] ?? 0 }}</div>
                <div class="stats-label">Personnel Validé</div>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="stats-icon bg-soft-info">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <div class="stats-number">{{ $stats['activeDocuments'] ?? 0 }}</div>
                <div class="stats-label">Documents Actifs</div>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="stats-icon bg-soft-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <div class="stats-number">{{ $stats['expiredDocuments'] ?? 0 }}</div>
                <div class="stats-label">Documents Expirés</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-bottom: 30px;">
    <div class="card-header">
        <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Actions rapides DRH</h5>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <a href="{{ route('drh.documents.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>Nouveau Document RH
            </a>
            <a href="{{ route('drh.attendance.create') }}" class="btn btn-success">
                <i class="fas fa-clock"></i>Enregistrer Présence
            </a>
            <a href="{{ route('drh.salary-slips.create') }}" class="btn btn-warning">
                <i class="fas fa-money-bill-wave"></i>Nouveau Bulletin
            </a>
            <a href="{{ route('drh.statistics') }}" class="btn btn-info">
                <i class="fas fa-chart-bar"></i>Statistiques RH
            </a>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
            <a href="{{ route('drh.personnel.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-users"></i>Gestion du Personnel
            </a>
            <a href="{{ route('drh.personnel.create') }}" class="btn btn-outline-success">
                <i class="fas fa-user-plus"></i>Ajouter du Personnel
            </a>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
    <!-- Recent Documents -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Documents RH Récents</h5>
            <a href="{{ route('drh.documents.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-arrow-right"></i>Voir tout
            </a>
        </div>
        <div class="card-body">
            @if(isset($recentDocuments) && $recentDocuments->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Personnel</th>
                                <th>Date</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDocuments as $document)
                                <tr>
                                    <td>{{ $document->type ?? 'N/A' }}</td>
                                    <td>{{ $document->personnel->nom ?? 'N/A' }}</td>
                                    <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge" style="background: {{ $document->status === 'active' ? '#22c55e' : '#f59e0b' }}; color: white;">
                                            {{ $document->status === 'active' ? 'Actif' : 'En attente' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; color: #6b7280; margin: 20px 0;">Aucun document récent</p>
            @endif
        </div>
    </div>

    <!-- Recent Salary Slips -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Bulletins de Salaire Récents</h5>
            <a href="{{ route('drh.salary-slips.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-arrow-right"></i>Voir tout
            </a>
        </div>
        <div class="card-body">
            @if(isset($recentSalarySlips) && $recentSalarySlips->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Personnel</th>
                                <th>Période</th>
                                <th>Salaire</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSalarySlips as $slip)
                                <tr>
                                    <td>{{ $slip->personnel->nom ?? 'N/A' }}</td>
                                    <td>{{ $slip->period ?? 'N/A' }}</td>
                                    <td>{{ number_format($slip->salary ?? 0, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        <span class="badge" style="background: {{ $slip->status === 'paid' ? '#22c55e' : '#f59e0b' }}; color: white;">
                                            {{ $slip->status === 'paid' ? 'Payé' : 'En attente' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; color: #6b7280; margin: 20px 0;">Aucun bulletin récent</p>
            @endif
        </div>
    </div>
</div>

<!-- Daily Attendance -->
<div class="card">
    <div class="card-header">
        <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Présence du jour ({{ now()->format('d/m/Y') }})</h5>
    </div>
    <div class="card-body">
        <div style="display: flex; align-items: center; gap: 30px;">
            <div style="text-align: center;">
                <h3 style="color: #059669; margin: 0; font-size: 32px; font-weight: 700;">{{ $stats['todayAttendance'] ?? 0 }}</h3>
                <p style="color: #6b7280; margin: 5px 0 0 0;">Personnes présentes</p>
            </div>
            <div style="flex: 1;">
                <p style="color: #6b7280; margin: 0;">
                    {{ $stats['todayAttendance'] ?? 0 }} membres du personnel sont présents aujourd'hui.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .page-title-box h4 {
        font-size: 20px;
    }
    
    .stats-card {
        padding: 15px;
    }
    
    .stats-number {
        font-size: 24px;
    }
    
    .btn {
        padding: 8px 15px;
        font-size: 14px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .table {
        font-size: 14px;
    }
    
    .stats-card > div {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .stats-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}
</style>
@endsection