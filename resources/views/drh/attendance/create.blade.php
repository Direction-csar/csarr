@extends('layouts.drh')

@section('title', 'Enregistrer Présence - DRH')

@section('content')
<!-- Page title -->
<div class="page-title-box">
    <h4>Enregistrer Présence</h4>
</div>

<!-- Form -->
<div class="card">
    <div class="card-header">
        <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Nouvel enregistrement de présence</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('drh.attendance.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="personnel_id" class="form-label">Personnel *</label>
                    <select name="personnel_id" id="personnel_id" class="form-control" required>
                        <option value="">Sélectionner le personnel</option>
                        @foreach($personnel as $person)
                            <option value="{{ $person->id }}" {{ old('personnel_id') == $person->id ? 'selected' : '' }}>
                                {{ $person->prenoms_nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('personnel_id')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="date" class="form-label">Date *</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="statut" class="form-label">Statut *</label>
                <select name="statut" id="statut" class="form-control" required onchange="toggleTimeFields()">
                    <option value="">Sélectionner le statut</option>
                    <option value="present" {{ old('statut') == 'present' ? 'selected' : '' }}>Présent</option>
                    <option value="absent" {{ old('statut') == 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="retard" {{ old('statut') == 'retard' ? 'selected' : '' }}>Retard</option>
                    <option value="congé" {{ old('statut') == 'congé' ? 'selected' : '' }}>Congé</option>
                    <option value="maladie" {{ old('statut') == 'maladie' ? 'selected' : '' }}>Maladie</option>
                    <option value="formation" {{ old('statut') == 'formation' ? 'selected' : '' }}>Formation</option>
                    <option value="mission" {{ old('statut') == 'mission' ? 'selected' : '' }}>Mission</option>
                </select>
                @error('statut')
                    <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div id="timeFields" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
                <div>
                    <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                    <input type="time" name="heure_arrivee" id="heure_arrivee" class="form-control" value="{{ old('heure_arrivee') }}">
                    @error('heure_arrivee')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="heure_depart" class="form-label">Heure de départ</label>
                    <input type="time" name="heure_depart" id="heure_depart" class="form-control" value="{{ old('heure_depart') }}">
                    @error('heure_depart')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="commentaires" class="form-label">Commentaires</label>
                <textarea name="commentaires" id="commentaires" class="form-control" rows="3" placeholder="Ajouter des commentaires (optionnel)">{{ old('commentaires') }}</textarea>
                @error('commentaires')
                    <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <a href="{{ route('drh.attendance.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>Retour
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTimeFields() {
    const statut = document.getElementById('statut').value;
    const timeFields = document.getElementById('timeFields');
    const heureArrivee = document.getElementById('heure_arrivee');
    const heureDepart = document.getElementById('heure_depart');
    
    if (statut === 'present' || statut === 'retard') {
        timeFields.style.display = 'grid';
        heureArrivee.required = true;
        heureDepart.required = true;
    } else {
        timeFields.style.display = 'none';
        heureArrivee.required = false;
        heureDepart.required = false;
        heureArrivee.value = '';
        heureDepart.value = '';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleTimeFields();
});
</script>

<style>
.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    border-color: #059669;
    box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
    outline: none;
}

@media (max-width: 768px) {
    .page-title-box h4 {
        font-size: 20px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .form-control {
        font-size: 16px; /* Prevent zoom on iOS */
    }
    
    .btn {
        padding: 8px 15px;
        font-size: 14px;
    }
}
</style>
@endsection
