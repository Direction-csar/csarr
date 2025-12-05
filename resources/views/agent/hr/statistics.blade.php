@extends('layouts.agent')

@section('title', 'Mes Statistiques RH - Interface Agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-chart-bar me-2"></i>
                    Mes Statistiques RH
                </h4>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('agent.hr.statistics') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Année</label>
                            <select name="annee" class="form-select">
                                @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ $annee == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mois</label>
                            <select name="mois" class="form-select">
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $mois == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Actions</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques de présence -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques de présence - {{ \Carbon\Carbon::create()->month($mois)->format('F') }} {{ $annee }}</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $stats['jours_presents'] }}</h4>
                                <p class="text-muted">Jours présents</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-danger">{{ $stats['jours_absents'] }}</h4>
                                <p class="text-muted">Jours absents</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $stats['jours_conges'] }}</h4>
                                <p class="text-muted">Jours de congé</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $stats['retards'] }}</h4>
                                <p class="text-muted">Retards</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $stats['jours_maladie'] }}</h4>
                                <p class="text-muted">Jours de maladie</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-secondary">{{ $stats['jours_formation'] }}</h4>
                                <p class="text-muted">Jours de formation</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-dark">{{ $stats['jours_mission'] }}</h4>
                                <p class="text-muted">Jours de mission</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="text-center">
                                <h4 class="text-info">{{ number_format($stats['total_heures'] / 60, 1) }}</h4>
                                <p class="text-muted">Heures totales travaillées</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <h4 class="text-success">{{ number_format($stats['moyenne_quotidienne'] / 60, 1) }}</h4>
                                <p class="text-muted">Moyenne quotidienne (heures)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques salariales -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques salariales - {{ $annee }}</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $statsSalariales['nombre_bulletins'] }}</h4>
                                <p class="text-muted">Nombre de bulletins</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ number_format($statsSalariales['total_salaire_net'], 0, ',', ' ') }} FCFA</h4>
                                <p class="text-muted">Total salaire net</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ number_format($statsSalariales['moyenne_salaire_net'], 0, ',', ' ') }} FCFA</h4>
                                <p class="text-muted">Moyenne salaire net</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ number_format($statsSalariales['total_indemnites'], 0, ',', ' ') }} FCFA</h4>
                                <p class="text-muted">Total indemnités</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-danger">{{ number_format($statsSalariales['total_cnss'], 0, ',', ' ') }} FCFA</h4>
                                <p class="text-muted">Total CNSS</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-secondary">{{ number_format($statsSalariales['total_impot'], 0, ',', ' ') }} FCFA</h4>
                                <p class="text-muted">Total Impôt</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h4 class="text-dark">{{ number_format($statsSalariales['total_salaire_brut'], 0, ',', ' ') }} FCFA</h4>
                                <p class="text-muted">Total salaire brut</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique de présence -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Évolution de la présence - {{ \Carbon\Carbon::create()->month($mois)->format('F') }} {{ $annee }}</h5>
                    <canvas id="presenceChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0 35px 0 rgba(154,161,171,.15);
    margin-bottom: 24px;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-select, .form-control {
    border-radius: 0.375rem;
    border: 1px solid #d1d3e2;
}

.form-select:focus, .form-control:focus {
    border-color: #727cf5;
    box-shadow: 0 0 0 0.2rem rgba(114,124,245,.25);
}

.text-center h4 {
    margin-bottom: 0.5rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique de présence
    const presenceData = @json($presencesParJour);
    
    const labels = presenceData.map(item => item.date);
    const presentData = presenceData.map(item => item.statut === 'present' ? 1 : 0);
    const absentData = presenceData.map(item => item.statut === 'absent' ? 1 : 0);
    const retardData = presenceData.map(item => item.statut === 'retard' ? 1 : 0);
    const congeData = presenceData.map(item => item.statut === 'congé' ? 1 : 0);

    const ctx = document.getElementById('presenceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Présent',
                    data: presentData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Absent',
                    data: absentData,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Retard',
                    data: retardData,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Congé',
                    data: congeData,
                    borderColor: '#17a2b8',
                    backgroundColor: 'rgba(23, 162, 184, 0.1)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 1,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
});
</script>
@endsection 