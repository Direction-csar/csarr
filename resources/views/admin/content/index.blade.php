@extends('layouts.admin')

@section('title', 'Gestion du Contenu')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-edit me-2"></i>Gestion du Contenu
                    </h1>
                    <p class="text-muted mb-0">Gérez le contenu du site web et les pages publiques</p>
                </div>
                <div>
                    <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#newContentModal">
                        <i class="fas fa-plus me-2"></i>Nouveau Contenu
                    </button>
                    <button class="btn btn-info-modern btn-modern" onclick="previewSite()">
                        <i class="fas fa-eye me-2"></i>Aperçu du Site
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-primary);">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="mb-1" id="total-pages">0</h3>
                    <p class="text-muted mb-0">Pages</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-success);">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="mb-1" id="published-content">0</h3>
                    <p class="text-muted mb-0">Publiés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-warning);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="mb-1" id="draft-content">0</h3>
                    <p class="text-muted mb-0">Brouillons</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-info);">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <h3 class="mb-1" id="scheduled-content">0</h3>
                    <p class="text-muted mb-0">Programmés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Recherche</label>
                                <input type="text" class="form-control" id="search-input" placeholder="Titre, contenu...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select class="form-select" id="type-filter">
                                    <option value="">Tous</option>
                                    <option value="page">Page</option>
                                    <option value="article">Article</option>
                                    <option value="announcement">Annonce</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Statut</label>
                                <select class="form-select" id="status-filter">
                                    <option value="">Tous</option>
                                    <option value="published">Publié</option>
                                    <option value="draft">Brouillon</option>
                                    <option value="scheduled">Programmé</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" id="category-filter">
                                    <option value="">Toutes</option>
                                    <option value="general">Général</option>
                                    <option value="news">Actualités</option>
                                    <option value="announcements">Annonces</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary-modern btn-modern" onclick="applyFilters()">
                                        <i class="fas fa-search me-2"></i>Filtrer
                                    </button>
                                    <button class="btn btn-secondary-modern btn-modern" onclick="clearFilters()">
                                        <i class="fas fa-times me-2"></i>Effacer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste du contenu -->
    <div class="row">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Contenu du Site
                        </h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                <i class="fas fa-check-square me-1"></i>Tout sélectionner
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="publishSelected()">
                                <i class="fas fa-check me-1"></i>Publier
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="draftSelected()">
                                <i class="fas fa-edit me-1"></i>Brouillon
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSelected()">
                                <i class="fas fa-trash me-1"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="content-list">
                        <!-- Liste vide par défaut -->
                        <div class="text-center py-5">
                            <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h5 class="text-muted">Aucun contenu</h5>
                            <p class="text-muted">Commencez par créer du contenu pour votre site.</p>
                            <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#newContentModal">
                                <i class="fas fa-plus me-2"></i>Créer du contenu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouveau Contenu -->
<div class="modal fade" id="newContentModal" tabindex="-1" aria-labelledby="newContentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newContentModalLabel">
                    <i class="fas fa-plus me-2"></i>Nouveau Contenu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="contentForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Sélectionner un type</option>
                                    <option value="page">Page</option>
                                    <option value="article">Article</option>
                                    <option value="announcement">Annonce</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="category" class="form-label">Catégorie</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="general">Général</option>
                                    <option value="news">Actualités</option>
                                    <option value="announcements">Annonces</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="draft">Brouillon</option>
                                    <option value="published">Publié</option>
                                    <option value="scheduled">Programmé</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Contenu <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="slug" class="form-label">URL (slug)</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="url-automatique">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="publish_date" class="form-label">Date de publication</label>
                                <input type="datetime-local" class="form-control" id="publish_date" name="publish_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs
    animateCounters();
    
    // Gestion du formulaire
    document.getElementById('contentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addContent();
    });
    
    // Génération automatique du slug
    document.getElementById('title').addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        document.getElementById('slug').value = slug;
    });
    
    // Gestion des filtres
    document.getElementById('search-input').addEventListener('input', debounce(applyFilters, 300));
    document.getElementById('type-filter').addEventListener('change', applyFilters);
    document.getElementById('status-filter').addEventListener('change', applyFilters);
    document.getElementById('category-filter').addEventListener('change', applyFilters);
});

function animateCounters() {
    // Animation des compteurs (pour l'instant à 0)
    animateValue(document.getElementById('total-pages'), 0, 0, 1000);
    animateValue(document.getElementById('published-content'), 0, 0, 1000);
    animateValue(document.getElementById('draft-content'), 0, 0, 1000);
    animateValue(document.getElementById('scheduled-content'), 0, 0, 1000);
}

function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.innerHTML = Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

function addContent() {
    const formData = new FormData(document.getElementById('contentForm'));
    
    // Simulation d'ajout (à remplacer par une vraie requête AJAX)
    showToast('Contenu créé avec succès !', 'success');
    document.getElementById('contentForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('newContentModal')).hide();
    
    // Mettre à jour la liste
    loadContent();
}

function applyFilters() {
    // Simulation de filtrage (à remplacer par une vraie requête AJAX)
    showToast('Filtres appliqués', 'info');
}

function clearFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('type-filter').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('category-filter').value = '';
    applyFilters();
}

function loadContent() {
    // Simulation de chargement (à remplacer par une vraie requête AJAX)
    // Pour l'instant, la liste reste vide
}

function selectAll() {
    showToast('Tous les éléments sélectionnés', 'info');
}

function publishSelected() {
    showToast('Contenu publié', 'success');
}

function draftSelected() {
    showToast('Contenu mis en brouillon', 'warning');
}

function deleteSelected() {
    if (confirm('Êtes-vous sûr de vouloir supprimer les éléments sélectionnés ?')) {
        showToast('Contenu supprimé', 'success');
    }
}

function previewSite() {
    showToast('Ouverture de l\'aperçu du site...', 'info');
    // Ouvrir l'aperçu dans un nouvel onglet
    window.open('/preview', '_blank');
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}
</script>
@endpush
