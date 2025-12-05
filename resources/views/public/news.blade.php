@extends('layouts.public')

@section('title', 'Actualités')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <!-- En-tête -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="fas fa-newspaper me-3"></i>Actualités
                </h1>
                <p class="lead text-muted">Restez informé de nos dernières activités et initiatives</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filters-card">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label for="categoryFilter" class="form-label">Catégorie</label>
                            <select class="form-select" id="categoryFilter">
                                <option value="">Toutes les catégories</option>
                                <option value="news">Actualités</option>
                                <option value="events">Événements</option>
                                <option value="announcements">Annonces</option>
                                <option value="reports">Rapports</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="yearFilter" class="form-label">Année</label>
                            <select class="form-select" id="yearFilter">
                                <option value="">Toutes les années</option>
                                @for($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="searchInput" class="form-label">Rechercher</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="Rechercher une actualité...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des actualités -->
        <div class="row" id="newsList">
            @forelse($news as $article)
            <div class="col-lg-4 mb-4 news-item" data-category="{{ $article->category }}" data-year="{{ $article->created_at->year }}">
                <div class="news-card">
                    <div class="news-image">
                        @if($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="img-fluid">
                        @else
                        <div class="news-image-placeholder">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        @endif
                        <div class="news-category">
                            <span class="badge bg-primary">{{ ucfirst($article->category ?? 'Actualité') }}</span>
                        </div>
                    </div>
                    
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $article->created_at->format('d/m/Y') }}
                            </span>
                            @if($article->author)
                            <span class="news-author">
                                <i class="fas fa-user me-1"></i>
                                {{ $article->author->name }}
                            </span>
                            @endif
                        </div>
                        
                        <h3 class="news-title">
                            <a href="{{ route('news.show', ['locale' => 'fr', 'id' => $article->id]) }}">{{ $article->title }}</a>
                        </h3>
                        
                        <p class="news-excerpt">{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 150) }}</p>
                        
                        <div class="news-footer">
                            <a href="{{ route('news.show', ['locale' => 'fr', 'id' => $article->id]) }}" class="btn btn-outline-primary btn-sm">
                                Lire la suite <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            <div class="news-stats">
                                <span class="news-views">
                                    <i class="fas fa-eye me-1"></i>{{ $article->views_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h3 class="text-muted">Aucune actualité disponible</h3>
                    <p class="text-muted">Il n'y a actuellement aucune actualité publiée.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($news && $news->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="pagination-wrapper">
                    {{ $news->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.filters-card {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.news-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.news-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.news-card:hover .news-image img {
    transform: scale(1.05);
}

.news-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 3rem;
}

.news-category {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.news-content {
    padding: 1.5rem;
}

.news-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #666;
}

.news-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.news-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.news-title a:hover {
    color: #667eea;
}

.news-excerpt {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.news-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.news-stats {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.news-views {
    color: #666;
    font-size: 0.9rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .news-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .news-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const yearFilter = document.getElementById('yearFilter');
    const searchInput = document.getElementById('searchInput');
    const newsItems = document.querySelectorAll('.news-item');

    function filterNews() {
        const selectedCategory = categoryFilter.value;
        const selectedYear = yearFilter.value;
        const searchTerm = searchInput.value.toLowerCase();

        newsItems.forEach(item => {
            const itemCategory = item.dataset.category;
            const itemYear = item.dataset.year;
            const title = item.querySelector('.news-title a').textContent.toLowerCase();
            const excerpt = item.querySelector('.news-excerpt').textContent.toLowerCase();

            const categoryMatch = !selectedCategory || itemCategory === selectedCategory;
            const yearMatch = !selectedYear || itemYear === selectedYear;
            const searchMatch = !searchTerm || title.includes(searchTerm) || excerpt.includes(searchTerm);

            if (categoryMatch && yearMatch && searchMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    categoryFilter.addEventListener('change', filterNews);
    yearFilter.addEventListener('change', filterNews);
    searchInput.addEventListener('input', filterNews);
});
</script>
@endsection