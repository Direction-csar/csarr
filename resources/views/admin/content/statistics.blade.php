@extends('layouts.admin')

@section('title', 'Gestion des Statistiques - CSAR Admin')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Gestion des Statistiques
                    </h1>
                    <p class="text-muted mb-0">Gérez les chiffres clés affichés sur la page "À propos du CSAR"</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStatisticModal">
                    <i class="fas fa-plus me-2"></i>Ajouter une Statistique
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques existantes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Statistiques Actuelles
                    </h5>
                </div>
                <div class="card-body">
                    @if($statistics->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
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
                                    @foreach($statistics as $statistic)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $statistic->order }}</span>
                                        </td>
                                        <td>
                                            @if($statistic->icon)
                                                <i class="{{ $statistic->icon }}" style="color: {{ $statistic->color }}; font-size: 1.2rem;"></i>
                                            @else
                                                <i class="fas fa-chart-bar text-muted"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $statistic->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $statistic->key }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info fs-6">{{ $statistic->value }}</span>
                                        </td>
                                        <td>{{ $statistic->description }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: {{ $statistic->color }}; border-radius: 3px;"></div>
                                                <small>{{ $statistic->color }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($statistic->is_active)
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="editStatistic({{ $statistic->id }})"
                                                        title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteStatistic({{ $statistic->id }})"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune statistique configurée</h5>
                            <p class="text-muted">Commencez par ajouter vos premières statistiques.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Aperçu des statistiques -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Aperçu des Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($statistics as $statistic)
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="stats-preview-card text-center p-3 border rounded">
                                @if($statistic->icon)
                                    <div class="stats-icon mb-2" style="color: {{ $statistic->color }};">
                                        <i class="{{ $statistic->icon }}" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                                <div class="stats-number h4 mb-1" style="color: #1f2937; font-weight: 900;">
                                    {{ $statistic->value }}
                                </div>
                                <div class="stats-label text-muted small">
                                    {{ $statistic->description }}
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

<!-- Modal Ajouter Statistique -->
<div class="modal fade" id="addStatisticModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Ajouter une Statistique
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStatisticForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="key" class="form-label">Clé unique *</label>
                                <input type="text" class="form-control" id="key" name="key" required>
                                <div class="form-text">Identifiant unique (ex: agents_count, warehouses_count)</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Titre *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="value" class="form-label">Valeur *</label>
                                <input type="text" class="form-control" id="value" name="value" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icône FontAwesome</label>
                                <input type="text" class="form-control" id="icon" name="icon" placeholder="fas fa-users">
                                <div class="form-text">Ex: fas fa-users, fas fa-warehouse, fas fa-chart-bar</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="color" class="form-label">Couleur</label>
                                <input type="color" class="form-control form-control-color" id="color" name="color" value="#22c55e">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control" id="order" name="order" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="section" class="form-label">Section *</label>
                                <select class="form-select" id="section" name="section" required>
                                    <option value="about">À propos</option>
                                    <option value="home">Accueil</option>
                                    <option value="dashboard">Tableau de bord</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Statistique -->
<div class="modal fade" id="editStatisticModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Modifier la Statistique
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStatisticForm">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Titre *</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_value" class="form-label">Valeur *</label>
                                <input type="text" class="form-control" id="edit_value" name="value" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description *</label>
                                <input type="text" class="form-control" id="edit_description" name="description" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_icon" class="form-label">Icône FontAwesome</label>
                                <input type="text" class="form-control" id="edit_icon" name="icon" placeholder="fas fa-users">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_color" class="form-label">Couleur</label>
                                <input type="color" class="form-control form-control-color" id="edit_color" name="color">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_order" class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control" id="edit_order" name="order" min="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">
                                Statistique active
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variables globales
let statistics = @json($statistics);

// Ajouter une statistique
document.getElementById('addStatisticForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("admin.content.statistics.create") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Erreur lors de la création de la statistique');
    });
});

// Modifier une statistique
function editStatistic(id) {
    const statistic = statistics.find(s => s.id === id);
    if (!statistic) return;
    
    // Remplir le formulaire
    document.getElementById('edit_id').value = statistic.id;
    document.getElementById('edit_title').value = statistic.title;
    document.getElementById('edit_value').value = statistic.value;
    document.getElementById('edit_description').value = statistic.description;
    document.getElementById('edit_icon').value = statistic.icon || '';
    document.getElementById('edit_color').value = statistic.color;
    document.getElementById('edit_order').value = statistic.order;
    document.getElementById('edit_is_active').checked = statistic.is_active;
    document.getElementById('edit_notes').value = statistic.notes || '';
    
    // Afficher le modal
    new bootstrap.Modal(document.getElementById('editStatisticModal')).show();
}

// Soumettre la modification
document.getElementById('editStatisticForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('edit_id').value;
    
    fetch(`/admin/content/statistics/${id}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            location.reload();
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Erreur lors de la mise à jour de la statistique');
    });
});

// Supprimer une statistique
function deleteStatistic(id) {
    const statistic = statistics.find(s => s.id === id);
    if (!statistic) return;
    
    if (confirm(`Êtes-vous sûr de vouloir supprimer la statistique "${statistic.title}" ?`)) {
        fetch(`/admin/content/statistics/${id}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Erreur lors de la suppression de la statistique');
        });
    }
}

// Fonction pour afficher les alertes
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insérer l'alerte en haut de la page
    const container = document.querySelector('.container-fluid');
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        const alert = container.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush

