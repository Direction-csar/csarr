@extends('layouts.agent')

@section('title', 'Ma Présence au Travail - Interface Agent')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-clock me-2"></i>
                    Ma Présence au Travail
                </h4>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('agent.hr.attendance.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="present" {{ request('statut') == 'present' ? 'selected' : '' }}>Présent</option>
                                <option value="absent" {{ request('statut') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="retard" {{ request('statut') == 'retard' ? 'selected' : '' }}>Retard</option>
                                <option value="congé" {{ request('statut') == 'congé' ? 'selected' : '' }}>Congé</option>
                                <option value="maladie" {{ request('statut') == 'maladie' ? 'selected' : '' }}>Maladie</option>
                                <option value="formation" {{ request('statut') == 'formation' ? 'selected' : '' }}>Formation</option>
                                <option value="mission" {{ request('statut') == 'mission' ? 'selected' : '' }}>Mission</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date début</label>
                            <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date fin</label>
                            <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Actions</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                                <a href="{{ route('agent.hr.attendance.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Statistiques de présence</h5>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-success">{{ $attendance->where('statut', 'present')->count() }}</h4>
                                <p class="text-muted">Présences</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-danger">{{ $attendance->where('statut', 'absent')->count() }}</h4>
                                <p class="text-muted">Absences</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $attendance->where('statut', 'retard')->count() }}</h4>
                                <p class="text-muted">Retards</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-info">{{ $attendance->where('statut', 'congé')->count() }}</h4>
                                <p class="text-muted">Congés</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $attendance->where('statut', 'maladie')->count() }}</h4>
                                <p class="text-muted">Maladies</p>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-center">
                                <h4 class="text-secondary">{{ $attendance->sum('heures_travaillees') / 60 }}</h4>
                                <p class="text-muted">Heures totales</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des présences -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Historique de présence</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Heure d'arrivée</th>
                                    <th>Heure de départ</th>
                                    <th>Heures travaillées</th>
                                    <th>Justification</th>
                                    <th>Validation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendance as $presence)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $presence->date->format('d/m/Y') }}</h6>
                                            <small class="text-muted">{{ $presence->date->format('l') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $presence->statut === 'present' ? 'success' : ($presence->statut === 'absent' ? 'danger' : ($presence->statut === 'retard' ? 'warning' : 'info')) }}">
                                            {{ $presence->statut_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($presence->heure_arrivee)
                                            {{ \Carbon\Carbon::parse($presence->heure_arrivee)->format('H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($presence->heure_depart)
                                            {{ \Carbon\Carbon::parse($presence->heure_depart)->format('H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($presence->heures_travaillees > 0)
                                            <span class="badge bg-info">{{ $presence->heures_travaillees_formatees }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($presence->justification)
                                            <small class="text-muted">{{ $presence->justification }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($presence->valide)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Validé
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>En attente
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Aucune donnée de présence trouvée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $attendance->links() }}
                    </div>
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

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
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
@endsection 