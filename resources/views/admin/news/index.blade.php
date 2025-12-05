@extends('layouts.admin')

@section('title', 'Gestion des Actualités')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-newspaper me-2"></i>Gestion des Actualités
                    </h1>
                    <p class="text-muted mb-0">Gérez les actualités et articles du site CSAR</p>
                </div>
                <div>
                    <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#newNewsModal">
                        <i class="fas fa-plus me-2"></i>Nouvelle Actualité
                    </button>
                    <button class="btn btn-info-modern btn-modern" onclick="previewNews()">
                        <i class="fas fa-eye me-2"></i>Aperçu Public
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
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="mb-1" id="total-news">0</h3>
                    <p class="text-muted mb-0">Total Actualités</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="mb-1" id="published-news">0</h3>
                    <p class="text-muted mb-0">Publiées</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-warning);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="mb-1" id="draft-news">0</h3>
                    <p class="text-muted mb-0">Brouillons</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-info);">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="mb-1" id="featured-news">0</h3>
                    <p class="text-muted mb-0">À la Une</p>
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
                                <input type="text" class="form-control" id="search-input" placeholder="Titre, contenu, auteur...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select" id="category-filter">
                                    <option value="">Toutes</option>
                                    <option value="general">Général</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="announcement">Annonce</option>
                                    <option value="event">Événement</option>
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
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" id="date-filter">
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

    <!-- Liste des actualités -->
    <div class="row">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Liste des Actualités
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
                            <button class="btn btn-sm btn-outline-info" onclick="featureSelected()">
                                <i class="fas fa-star me-1"></i>À la Une
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSelected()">
                                <i class="fas fa-trash me-1"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="news-list">
                        <!-- Liste vide par défaut -->
                        <div class="text-center py-5">
                            <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <h5 class="text-muted">Aucune actualité</h5>
                            <p class="text-muted">Commencez par créer des actualités pour votre site.</p>
                            <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#newNewsModal">
                                <i class="fas fa-plus me-2"></i>Créer une actualité
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Actualité -->
<div class="modal fade" id="newNewsModal" tabindex="-1" aria-labelledby="newNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newNewsModalLabel">
                    <i class="fas fa-plus me-2"></i>Nouvelle Actualité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="newsForm">
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
                                <label for="category" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="general">Général</option>
                                    <option value="urgent">Urgent</option>
                                    <option value="announcement">Annonce</option>
                                    <option value="event">Événement</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="author" class="form-label">Auteur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="author" name="author" required>
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
                        <label for="excerpt" class="form-label">Résumé</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="2" placeholder="Court résumé de l'actualité..."></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Contenu <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="publish_date" class="form-label">Date de publication</label>
                                <input type="datetime-local" class="form-control" id="publish_date" name="publish_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured">
                                <label class="form-check-label" for="featured">
                                    Mettre à la une
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" checked>
                                <label class="form-check-label" for="allow_comments">
                                    Autoriser les commentaires
                                </label>
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
    document.getElementById('newsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNews();
    });
    
    // Gestion des filtres
    document.getElementById('search-input').addEventListener('input', debounce(applyFilters, 300));
    document.getElementById('category-filter').addEventListener('change', applyFilters);
    document.getElementById('status-filter').addEventListener('change', applyFilters);
    document.getElementById('date-filter').addEventListener('change', applyFilters);
});

function animateCounters() {
    // Animation des compteurs (pour l'instant à 0)
    animateValue(document.getElementById('total-news'), 0, 0, 1000);
    animateValue(document.getElementById('published-news'), 0, 0, 1000);
    animateValue(document.getElementById('draft-news'), 0, 0, 1000);
    animateValue(document.getElementById('featured-news'), 0, 0, 1000);
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

function addNews() {
    const formData = new FormData(document.getElementById('newsForm'));
    
    // Simulation d'ajout (à remplacer par une vraie requête AJAX)
    showToast('Actualité créée avec succès !', 'success');
    document.getElementById('newsForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('newNewsModal')).hide();
    
    // Mettre à jour la liste
    loadNews();
}

function applyFilters() {
    // Simulation de filtrage (à remplacer par une vraie requête AJAX)
    showToast('Filtres appliqués', 'info');
}

function clearFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('category-filter').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('date-filter').value = '';
    applyFilters();
}

function loadNews() {
    // Simulation de chargement (à remplacer par une vraie requête AJAX)
    // Pour l'instant, la liste reste vide
}

function selectAll() {
    showToast('Toutes les actualités sélectionnées', 'info');
}

function publishSelected() {
    showToast('Actualités publiées', 'success');
}

function draftSelected() {
    showToast('Actualités mises en brouillon', 'warning');
}

function featureSelected() {
    showToast('Actualités mises à la une', 'info');
}

function deleteSelected() {
    if (confirm('Êtes-vous sûr de vouloir supprimer les actualités sélectionnées ?')) {
        showToast('Actualités supprimées', 'success');
    }
}

function previewNews() {
    showToast('Ouverture de l\'aperçu public...', 'info');
    // Ouvrir l'aperçu dans un nouvel onglet
    window.open('/news', '_blank');
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
