@extends('layouts.drh')

@section('title', 'Nouveau Bulletin de Paie - DRH')

@section('content')
<!-- Page title -->
<div class="page-title-box">
    <h4>Nouveau Bulletin de Paie</h4>
</div>

<!-- Form -->
<div class="card">
    <div class="card-header">
        <h5 style="margin: 0; font-weight: 600; color: #2c3e50;">Créer un nouveau bulletin de paie</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('drh.salary-slips.store') }}" method="POST">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="personnel_id" class="form-label">Personnel *</label>
                    <select name="personnel_id" id="personnel_id" class="form-control" required onchange="calculateNetSalary()">
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
                    <label for="statut" class="form-label">Statut *</label>
                    <select name="statut" id="statut" class="form-control" required>
                        <option value="">Sélectionner le statut</option>
                        <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="paye" {{ old('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                    </select>
                    @error('statut')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="periode_debut" class="form-label">Période début *</label>
                    <input type="date" name="periode_debut" id="periode_debut" class="form-control" value="{{ old('periode_debut') }}" required>
                    @error('periode_debut')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="periode_fin" class="form-label">Période fin *</label>
                    <input type="date" name="periode_fin" id="periode_fin" class="form-control" value="{{ old('periode_fin') }}" required>
                    @error('periode_fin')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label for="salaire_brut" class="form-label">Salaire Brut * (FCFA)</label>
                    <input type="number" name="salaire_brut" id="salaire_brut" class="form-control" value="{{ old('salaire_brut') }}" min="0" step="1" required onchange="calculateNetSalary()">
                    @error('salaire_brut')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="prime" class="form-label">Prime (FCFA)</label>
                    <input type="number" name="prime" id="prime" class="form-control" value="{{ old('prime', 0) }}" min="0" step="1" onchange="calculateNetSalary()">
                    @error('prime')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div>
                    <label for="deduction" class="form-label">Déduction (FCFA)</label>
                    <input type="number" name="deduction" id="deduction" class="form-control" value="{{ old('deduction', 0) }}" min="0" step="1" onchange="calculateNetSalary()">
                    @error('deduction')
                        <div class="text-danger" style="font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <div class="card" style="background: #f8f9fa; border: 1px solid #e9ecef;">
                    <div class="card-body">
                        <h6 style="margin: 0 0 10px 0; color: #495057;">Calcul du Salaire Net</h6>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; font-size: 14px;">
                            <div>
                                <span style="color: #6b7280;">Salaire Brut:</span><br>
                                <strong id="display_brut">0 FCFA</strong>
                            </div>
                            <div>
                                <span style="color: #6b7280;">+ Prime:</span><br>
                                <strong id="display_prime">0 FCFA</strong>
                            </div>
                            <div>
                                <span style="color: #6b7280;">- Déduction:</span><br>
                                <strong id="display_deduction">0 FCFA</strong>
                            </div>
                        </div>
                        <hr style="margin: 15px 0;">
                        <div style="text-align: center;">
                            <span style="color: #059669; font-weight: 600; font-size: 16px;">Salaire Net:</span><br>
                            <strong id="display_net" style="color: #059669; font-size: 20px;">0 FCFA</strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <a href="{{ route('drh.salary-slips.index') }}" class="btn btn-outline-secondary">
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
function calculateNetSalary() {
    const brut = parseFloat(document.getElementById('salaire_brut').value) || 0;
    const prime = parseFloat(document.getElementById('prime').value) || 0;
    const deduction = parseFloat(document.getElementById('deduction').value) || 0;
    
    const net = brut + prime - deduction;
    
    // Update display
    document.getElementById('display_brut').textContent = formatCurrency(brut);
    document.getElementById('display_prime').textContent = formatCurrency(prime);
    document.getElementById('display_deduction').textContent = formatCurrency(deduction);
    document.getElementById('display_net').textContent = formatCurrency(net);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateNetSalary();
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
    
    .form-control {
        width: 100% !important;
        margin-bottom: 10px;
    }
}
</style>
@endsection
