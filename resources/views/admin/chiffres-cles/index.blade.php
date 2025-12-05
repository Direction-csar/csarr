@extends('layouts.admin')

@section('title', 'Gestion des Chiffres Clés')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Gestion des Chiffres Clés
                    </h1>
                    <p class="text-muted mb-0">Modifiez les statistiques affichées sur les pages publiques</p>
                </div>
                <div>
                    <button type="button" class="btn btn-warning me-2" onclick="resetToDefaults()">
                        <i class="fas fa-undo me-1"></i>
                        Réinitialiser
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveAllChanges()">
                        <i class="fas fa-save me-1"></i>
                        Sauvegarder tout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Instructions :</strong> 
        Modifiez les valeurs dans le tableau, puis cliquez sur le bouton vert 💾 pour sauvegarder.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tableau des chiffres clés -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        Chiffres Clés Dynamiques
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ordre</th>
                                    <th>Icône</th>
                                    <th>Titre</th>
                                    <th>Valeur</th>
                                    <th>Description</th>
                                    <th>Couleur</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($chiffresCles as $chiffre)
                                <tr data-id="{{ $chiffre->id }}">
                                    <td>
                                        <input type="number" 
                                               class="form-control form-control-sm" 
                                               name="chiffres[{{ $chiffre->id }}][ordre]" 
                                               value="{{ $chiffre->ordre }}"
                                               min="1">
                                    </td>
                                    <td>
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               name="chiffres[{{ $chiffre->id }}][icone]" 
                                               value="{{ $chiffre->icone }}"
                                               placeholder="fas fa-icon">
                                    </td>
                                    <td>
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               name="chiffres[{{ $chiffre->id }}][titre]" 
                                               value="{{ $chiffre->titre }}">
                                    </td>
                                    <td>
                                        <input type="text" 
                                               class="form-control form-control-sm chiffre-value" 
                                               name="chiffres[{{ $chiffre->id }}][valeur]" 
                                               value="{{ $chiffre->valeur }}" 
                                               data-original="{{ $chiffre->valeur }}"
                                               style="background-color: #f8f9fa; border: 2px solid #007bff;"
                                               placeholder="Entrez la valeur"
                                               id="input-value-{{ $chiffre->id }}"
                                               oninput="updatePreview({{ $chiffre->id }})">
                                    </td>
                                    <td>
                                        <input type="text" 
                                               class="form-control form-control-sm" 
                                               name="chiffres[{{ $chiffre->id }}][description]" 
                                               value="{{ $chiffre->description }}">
                                    </td>
                                    <td>
                                        <input type="color" 
                                               class="form-control form-control-sm" 
                                               name="chiffres[{{ $chiffre->id }}][couleur]" 
                                               value="{{ $chiffre->couleur_complete }}">
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $chiffre->statut === 'Actif' ? 'success' : 'secondary' }}">
                                            {{ $chiffre->statut }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <form method="POST" action="{{ route('admin.chiffres-cles.update', $chiffre->id) }}" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="valeur" value="{{ $chiffre->valeur }}" id="value-{{ $chiffre->id }}">
                                                <input type="hidden" name="titre" value="{{ $chiffre->titre }}">
                                                <input type="hidden" name="description" value="{{ $chiffre->description }}">
                                                <input type="hidden" name="icone" value="{{ $chiffre->icone }}">
                                                <input type="hidden" name="couleur" value="{{ $chiffre->couleur }}">
                                                <input type="hidden" name="ordre" value="{{ $chiffre->ordre }}">
                                                <input type="hidden" name="statut" value="{{ $chiffre->statut }}">
                                                <button type="submit" class="btn btn-success btn-sm" onclick="updateValue({{ $chiffre->id }})">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </form>
                                            <button type="button" 
                                                    class="btn btn-{{ $chiffre->statut === 'Actif' ? 'warning' : 'success' }} btn-sm" 
                                                    onclick="toggleStatus({{ $chiffre->id }})"
                                                    title="{{ $chiffre->statut === 'Actif' ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas fa-{{ $chiffre->statut === 'Actif' ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aperçu en temps réel -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Aperçu en Temps Réel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($chiffresCles as $chiffre)
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3" id="preview-{{ $chiffre->id }}">
                            <div class="stats-card text-center p-3 border rounded">
                                <div class="stats-icon mb-2" style="color: {{ $chiffre->couleur_complete }}">
                                    <i class="{{ $chiffre->icone }} fa-2x"></i>
                                </div>
                                <div class="stats-number h4 mb-1" style="color: {{ $chiffre->couleur_complete }}" id="preview-value-{{ $chiffre->id }}">
                                    {{ $chiffre->valeur }}
                                </div>
                                <div class="stats-label text-muted small">
                                    {{ $chiffre->description }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mettre à jour l'aperçu en temps réel
function updatePreview(id) {
    const inputValue = document.getElementById(`input-value-${id}`);
    const previewElement = document.getElementById(`preview-value-${id}`);
    
    if (inputValue && previewElement) {
        previewElement.textContent = inputValue.value;
        console.log('Aperçu mis à jour en temps réel:', inputValue.value);
    }
}

// Mettre à jour tous les aperçus au chargement de la page
function updateAllPreviews() {
    document.querySelectorAll('.chiffre-value').forEach(input => {
        const id = input.id.replace('input-value-', '');
        updatePreview(id);
    });
}

// Mettre à jour la valeur avant soumission
function updateValue(id) {
    const inputValue = document.getElementById(`input-value-${id}`);
    const hiddenValueInput = document.getElementById(`value-${id}`);
    
    if (inputValue && hiddenValueInput) {
        hiddenValueInput.value = inputValue.value;
        console.log('Valeur mise à jour:', inputValue.value);
        
        // Mettre à jour l'aperçu en temps réel
        updatePreview(id);
    } else {
        console.error('Champs non trouvés pour ID:', id);
    }
}

// Basculer le statut d'un chiffre clé
function toggleStatus(id) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de ce chiffre clé ?')) {
        fetch(`{{ url('admin/chiffres-cles') }}/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors du changement de statut');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du changement de statut');
        });
    }
}

// Sauvegarder tous les changements
function saveAllChanges() {
    if (confirm('Êtes-vous sûr de vouloir sauvegarder tous les changements ?')) {
        const form = document.getElementById('chiffresForm');
        const formData = new FormData(form);

        fetch('{{ route("admin.chiffres-cles.update-batch") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erreur lors de la sauvegarde');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la sauvegarde');
        });
    }
}

// Réinitialiser aux valeurs par défaut
function resetToDefaults() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser tous les chiffres clés aux valeurs par défaut ?')) {
        fetch('{{ route("admin.chiffres-cles.reset") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Erreur lors de la réinitialisation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la réinitialisation');
        });
    }
}

// Écouter les changements de valeur pour l'aperçu en temps réel
document.addEventListener('DOMContentLoaded', function() {
    // Mettre à jour tous les aperçus au chargement
    updateAllPreviews();
    
    document.querySelectorAll('.chiffre-value').forEach(input => {
        input.addEventListener('input', function() {
            const id = this.id.replace('input-value-', '');
            updatePreview(id);
        });
    });
    
    // Écouter les changements de couleur
    document.querySelectorAll('input[type="color"]').forEach(input => {
        input.addEventListener('change', function() {
            const id = this.name.match(/chiffres\[(\d+)\]/)[1];
            const previewElement = document.querySelector(`#preview-${id} .stats-icon`);
            if (previewElement) {
                previewElement.style.color = this.value;
            }
        });
    });
});
</script>
@endsection