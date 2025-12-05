@extends('layouts.drh')

@section('title', 'Présence - DRH')

@section('content')
<!-- Page title -->
<div class="page-title-box">
    <h4>Gestion de la Présence - Direction des Ressources Humaines</h4>
</div>

<!-- Actions -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-body">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <a href="{{ route('drh.attendance.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>Enregistrer Présence
            </a>
            
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="date" class="form-control" style="width: 150px;" value="{{ date('Y-m-d') }}">
                <select class="form-control" style="width: 150px;">
                    <option value="">Tous les statuts</option>
                    <option value="present">Présent</option>
                    <option value="absent">Absent</option>
                    <option value="retard">Retard</option>
                    <option value="congé">Congé</option>
                    <option value="maladie">Maladie</option>
                    <option value="formation">Formation</option>
                    <option value="mission">Mission</option>
                </select>
                <button class="btn btn-outline-primary">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Attendance List -->
<div class="card">
    <div class="card-header">
        <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Liste des Présences</h5>
    </div>
    <div class="card-body">
        @if($attendance->count() > 0)
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Personnel</th>
                            <th>Heure d'arrivée</th>
                            <th>Heure de départ</th>
                            <th>Statut</th>
                            <th>Heures travaillées</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendance as $record)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                                <td>{{ $record->personnel->prenoms_nom ?? 'N/A' }}</td>
                                <td>
                                    @if($record->heure_arrivee)
                                        {{ \Carbon\Carbon::parse($record->heure_arrivee)->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->heure_depart)
                                        {{ \Carbon\Carbon::parse($record->heure_depart)->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'present' => '#22c55e',
                                            'absent' => '#ef4444',
                                            'retard' => '#f59e0b',
                                            'congé' => '#3b82f6',
                                            'maladie' => '#ef4444',
                                            'formation' => '#8b5cf6',
                                            'mission' => '#06b6d4'
                                        ];
                                        $statusLabels = [
                                            'present' => 'Présent',
                                            'absent' => 'Absent',
                                            'retard' => 'Retard',
                                            'congé' => 'Congé',
                                            'maladie' => 'Maladie',
                                            'formation' => 'Formation',
                                            'mission' => 'Mission'
                                        ];
                                    @endphp
                                    <span class="badge" style="background: {{ $statusColors[$record->statut] ?? '#6b7280' }}; color: white;">
                                        {{ $statusLabels[$record->statut] ?? ucfirst($record->statut) }}
                                    </span>
                                </td>
                                <td>
                                    @if($record->heure_arrivee && $record->heure_depart)
                                        @php
                                            $start = \Carbon\Carbon::parse($record->heure_arrivee);
                                            $end = \Carbon\Carbon::parse($record->heure_depart);
                                            $hours = $start->diffInMinutes($end) / 60;
                                        @endphp
                                        {{ number_format($hours, 1) }}h
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="{{ route('drh.attendance.edit', $record) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('drh.attendance.destroy', $record) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet enregistrement ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
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
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $attendance->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-clock" style="font-size: 48px; color: #d1d5db; margin-bottom: 20px;"></i>
                <h5 style="color: #6b7280; margin-bottom: 10px;">Aucun enregistrement de présence</h5>
                <p style="color: #9ca3af;">Commencez par enregistrer la présence du personnel.</p>
                <a href="{{ route('drh.attendance.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>Enregistrer présence
                </a>
            </div>
        @endif
    </div>
</div>

<style>
@media (max-width: 768px) {
    .page-title-box h4 {
        font-size: 20px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .table {
        font-size: 14px;
    }
    
    .btn {
        padding: 6px 10px;
        font-size: 12px;
    }
    
    .form-control {
        width: 100% !important;
        margin-bottom: 10px;
    }
}
</style>
@endsection
