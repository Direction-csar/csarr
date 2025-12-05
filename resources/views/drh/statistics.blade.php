@extends('layouts.drh')

@section('title', 'Statistiques RH - DRH')

@section('content')
<!-- Page title -->
<div class="page-title-box">
    <h4>Statistiques RH - Direction des Ressources Humaines</h4>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stats-card">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="stats-icon bg-soft-success">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stats-number">{{ $stats['total_personnel'] ?? 0 }}</div>
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
                <div class="stats-number">{{ $stats['personnel_valide'] ?? 0 }}</div>
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
                <div class="stats-number">{{ $stats['documents_actifs'] ?? 0 }}</div>
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
                <div class="stats-number">{{ $stats['documents_expires'] ?? 0 }}</div>
                <div class="stats-label">Documents Expirés</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
    <!-- Présence par jour -->
    <div class="card">
        <div class="card-header">
            <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Présence par jour - {{ $annee }}/{{ str_pad($mois, 2, '0', STR_PAD_LEFT) }}</h5>
        </div>
        <div class="card-body">
            @if($presencesParJour->count() > 0)
                <div style="height: 300px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                    <canvas id="presenceChart" width="400" height="200"></canvas>
                </div>
            @else
                <p style="text-align: center; color: #6b7280; margin: 20px 0;">Aucune donnée de présence pour ce mois</p>
            @endif
        </div>
    </div>

    <!-- Documents par type -->
    <div class="card">
        <div class="card-header">
            <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Documents par type</h5>
        </div>
        <div class="card-body">
            @if($documentsParType->count() > 0)
                <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                    <canvas id="documentsChart" width="400" height="200"></canvas>
                </div>
            @else
                <p style="text-align: center; color: #6b7280; margin: 20px 0;">Aucun document enregistré</p>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Actions rapides</h5>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <a href="{{ route('drh.personnel.index') }}" class="btn btn-primary">
                <i class="fas fa-users"></i>Gestion du Personnel
            </a>
            <a href="{{ route('drh.documents.index') }}" class="btn btn-success">
                <i class="fas fa-file-alt"></i>Documents RH
            </a>
            <a href="{{ route('drh.attendance.index') }}" class="btn btn-info">
                <i class="fas fa-clock"></i>Présence
            </a>
            <a href="{{ route('drh.salary-slips.index') }}" class="btn btn-warning">
                <i class="fas fa-money-bill-wave"></i>Bulletins de Paie
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart des présences
    @if($presencesParJour->count() > 0)
    const presenceCtx = document.getElementById('presenceChart').getContext('2d');
    new Chart(presenceCtx, {
        type: 'line',
        data: {
            labels: [
                @foreach($presencesParJour as $presence)
                    '{{ \Carbon\Carbon::parse($presence->date)->format('d/m') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Nombre de présences',
                data: [
                    @foreach($presencesParJour as $presence)
                        {{ $presence->total }},
                    @endforeach
                ],
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    @endif

    // Chart des documents
    @if($documentsParType->count() > 0)
    const documentsCtx = document.getElementById('documentsChart').getContext('2d');
    new Chart(documentsCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($documentsParType as $type)
                    '{{ ucfirst(str_replace('_', ' ', $type->type)) }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($documentsParType as $type)
                        {{ $type->total }},
                    @endforeach
                ],
                backgroundColor: [
                    '#059669',
                    '#22c55e',
                    '#3b82f6',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6',
                    '#06b6d4'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    @endif
});
</script>

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
