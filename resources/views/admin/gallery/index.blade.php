@extends('layouts.admin')

@section('title', 'Gestion de la Galerie')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-images me-2"></i>Gestion de la Galerie
                    </h1>
                    <p class="text-muted mb-0">Gérez les images et médias de la plateforme CSAR</p>
                </div>
                <div>
                    <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload me-2"></i>Uploader des Images
                    </button>
                    <button class="btn btn-info-modern btn-modern" onclick="createAlbum()">
                        <i class="fas fa-folder-plus me-2"></i>Nouvel Album
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
                        <i class="fas fa-images"></i>
                    </div>
                    <h3 class="mb-1" id="total-images">0</h3>
                    <p class="text-muted mb-0">Total Images</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-success);">
                        <i class="fas fa-folder"></i>
                    </div>
                    <h3 class="mb-1" id="total-albums">0</h3>
                    <p class="text-muted mb-0">Albums</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-warning);">
                        <i class="fas fa-hdd"></i>
                    </div>
                    <h3 class="mb-1" id="storage-used">0 MB</h3>
                    <p class="text-muted mb-0">Espace utilisé</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-modern">
                <div class="card-body text-center">
                    <div class="icon-3d mb-3" style="background: var(--gradient-info);">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="mb-1" id="total-views">0</h3>
                    <p class="text-muted mb-0">Vues totales</p>
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
                                <input type="text" class="form-control" id="search-input" placeholder="Nom, description...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Album</label>
                                <select class="form-select" id="album-filter">
                                    <option value="">Tous les albums</option>
                                    <option value="general">Général</option>
                                    <option value="events">Événements</option>
                                    <option value="news">Actualités</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select class="form-select" id="type-filter">
                                    <option value="">Tous</option>
                                    <option value="image">Images</option>
                                    <option value="video">Vidéos</option>
                                    <option value="document">Documents</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-label">Taille</label>
                                <select class="form-select" id="size-filter">
                                    <option value="">Toutes</option>
                                    <option value="small">Petites (< 1MB)</option>
                                    <option value="medium">Moyennes (1-5MB)</option>
                                    <option value="large">Grandes (> 5MB)</option>
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

    <!-- Vue des médias -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="toggleView('grid')" id="grid-view-btn">
                                <i class="fas fa-th me-1"></i>Grille
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="toggleView('list')" id="list-view-btn">
                                <i class="fas fa-list me-1"></i>Liste
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                <i class="fas fa-check-square me-1"></i>Tout sélectionner
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="downloadSelected()">
                                <i class="fas fa-download me-1"></i>Télécharger
                            </button>
                            <button class="btn btn-sm btn-outline-warning" onclick="moveSelected()">
                                <i class="fas fa-folder me-1"></i>Déplacer
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteSelected()">
                                <i class="fas fa-trash me-1"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="gallery-content">
                        <!-- Vue grille par défaut -->
                        <div id="grid-view" class="gallery-grid">
                            <div class="text-center py-5">
                                <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                    <i class="fas fa-images"></i>
                                </div>
                                <h5 class="text-muted">Aucune image</h5>
                                <p class="text-muted">Commencez par uploader des images dans votre galerie.</p>
                                <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="fas fa-upload me-2"></i>Uploader des images
                                </button>
                            </div>
                        </div>
                        
                        <!-- Vue liste (cachée par défaut) -->
                        <div id="list-view" class="gallery-list" style="display: none;">
                            <div class="text-center py-5">
                                <div class="icon-3d mb-3" style="background: var(--gradient-secondary); width: 80px; height: 80px; margin: 0 auto;">
                                    <i class="fas fa-list"></i>
                                </div>
                                <h5 class="text-muted">Aucune image</h5>
                                <p class="text-muted">Commencez par uploader des images dans votre galerie.</p>
                                <button class="btn btn-primary-modern btn-modern" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="fas fa-upload me-2"></i>Uploader des images
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="fas fa-upload me-2"></i>Uploader des Images
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="images" class="form-label">Sélectionner des images <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                        <div class="form-text">Vous pouvez sélectionner plusieurs images à la fois (JPG, PNG, GIF, WebP)</div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="album" class="form-label">Album</label>
                        <select class="form-select" id="album" name="album">
                            <option value="general">Général</option>
                            <option value="events">Événements</option>
                            <option value="news">Actualités</option>
                            <option value="gallery">Galerie</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description des images..."></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="tag1, tag2, tag3...">
                        <div class="form-text">Séparez les tags par des virgules</div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="optimize" name="optimize" checked>
                        <label class="form-check-label" for="optimize">
                            Optimiser automatiquement les images
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-upload me-2"></i>Uploader
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nouvel Album -->
<div class="modal fade" id="albumModal" tabindex="-1" aria-labelledby="albumModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="albumModalLabel">
                    <i class="fas fa-folder-plus me-2"></i>Nouvel Album
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="albumForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="album_name" class="form-label">Nom de l'album <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="album_name" name="album_name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="album_description" class="form-label">Description</label>
                        <textarea class="form-control" id="album_description" name="album_description" rows="3"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="album_cover" class="form-label">Image de couverture</label>
                        <input type="file" class="form-control" id="album_cover" name="album_cover" accept="image/*">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="album_public" name="album_public">
                        <label class="form-check-label" for="album_public">
                            Album public (visible sur le site)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary-modern">
                        <i class="fas fa-save me-2"></i>Créer
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
    
    // Gestion du formulaire d'upload
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        uploadImages();
    });
    
    // Gestion du formulaire d'album
    document.getElementById('albumForm').addEventListener('submit', function(e) {
        e.preventDefault();
        createAlbum();
    });
    
    // Gestion des filtres
    document.getElementById('search-input').addEventListener('input', debounce(applyFilters, 300));
    document.getElementById('album-filter').addEventListener('change', applyFilters);
    document.getElementById('type-filter').addEventListener('change', applyFilters);
    document.getElementById('size-filter').addEventListener('change', applyFilters);
});

function animateCounters() {
    // Animation des compteurs (pour l'instant à 0)
    animateValue(document.getElementById('total-images'), 0, 0, 1000);
    animateValue(document.getElementById('total-albums'), 0, 0, 1000);
    animateValue(document.getElementById('storage-used'), 0, 0, 1000);
    animateValue(document.getElementById('total-views'), 0, 0, 1000);
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

function uploadImages() {
    const formData = new FormData(document.getElementById('uploadForm'));
    
    // Simulation d'upload (à remplacer par une vraie requête AJAX)
    showToast('Images uploadées avec succès !', 'success');
    document.getElementById('uploadForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
    
    // Mettre à jour la galerie
    loadGallery();
}

function createAlbum() {
    const formData = new FormData(document.getElementById('albumForm'));
    
    // Simulation de création (à remplacer par une vraie requête AJAX)
    showToast('Album créé avec succès !', 'success');
    document.getElementById('albumForm').reset();
    bootstrap.Modal.getInstance(document.getElementById('albumModal')).hide();
    
    // Mettre à jour la liste des albums
    loadAlbums();
}

function applyFilters() {
    // Simulation de filtrage (à remplacer par une vraie requête AJAX)
    showToast('Filtres appliqués', 'info');
}

function clearFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('album-filter').value = '';
    document.getElementById('type-filter').value = '';
    document.getElementById('size-filter').value = '';
    applyFilters();
}

function loadGallery() {
    // Simulation de chargement (à remplacer par une vraie requête AJAX)
    // Pour l'instant, la galerie reste vide
}

function loadAlbums() {
    // Simulation de chargement des albums
}

function toggleView(view) {
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const gridBtn = document.getElementById('grid-view-btn');
    const listBtn = document.getElementById('list-view-btn');
    
    if (view === 'grid') {
        gridView.style.display = 'block';
        listView.style.display = 'none';
        gridBtn.classList.remove('btn-outline-primary');
        gridBtn.classList.add('btn-primary');
        listBtn.classList.remove('btn-primary');
        listBtn.classList.add('btn-outline-secondary');
    } else {
        gridView.style.display = 'none';
        listView.style.display = 'block';
        listBtn.classList.remove('btn-outline-secondary');
        listBtn.classList.add('btn-primary');
        gridBtn.classList.remove('btn-primary');
        gridBtn.classList.add('btn-outline-primary');
    }
}

function selectAll() {
    showToast('Toutes les images sélectionnées', 'info');
}

function downloadSelected() {
    showToast('Téléchargement en cours...', 'info');
}

function moveSelected() {
    showToast('Déplacement des images...', 'info');
}

function deleteSelected() {
    if (confirm('Êtes-vous sûr de vouloir supprimer les images sélectionnées ?')) {
        showToast('Images supprimées', 'success');
    }
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

<style>
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    padding: 1rem;
}

.gallery-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.gallery-item:hover {
    transform: translateY(-2px);
}

.gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.gallery-item .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .overlay {
    opacity: 1;
}

.gallery-list {
    padding: 1rem;
}

.gallery-list-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.gallery-list-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
    margin-right: 1rem;
}

.gallery-list-item .info {
    flex: 1;
}

.gallery-list-item .actions {
    display: flex;
    gap: 0.5rem;
}
</style>
@endpush
