<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $actualite->titre }} - Actualités CSAR</title>
    <meta name="description" content="{{ Str::limit(strip_tags($actualite->contenu), 160) }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --dark-bg: #2c3e50;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 40px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .article-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-top: -50px;
            position: relative;
            z-index: 10;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .article-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .article-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: var(--light-bg);
            border-radius: 10px;
        }
        
        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
        }
        
        .article-content h1, .article-content h2, .article-content h3 {
            color: var(--primary-color);
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .document-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-left: 5px solid var(--success-color);
        }
        
        .document-preview {
            width: 100%;
            height: 200px;
            background: var(--light-bg);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }
        
        .document-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .document-preview .document-icon {
            font-size: 4rem;
            color: var(--accent-color);
        }
        
        .related-articles {
            background: var(--light-bg);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 3rem;
        }
        
        .related-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .related-card:hover {
            transform: translateY(-5px);
        }
        
        .related-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        
        .btn-download {
            background: linear-gradient(135deg, var(--success-color) 0%, #2ecc71 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
            color: white;
        }
        
        .video-container {
            position: relative;
            width: 100%;
            height: 400px;
            border-radius: 15px;
            overflow: hidden;
            margin: 2rem 0;
        }
        
        .video-container iframe {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                <i class="fas fa-home me-2"></i>CSAR
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('home', ['locale' => app()->getLocale()]) }}">Accueil</a>
                <a class="nav-link" href="{{ route('public.actualites') }}">Actualités</a>
                <a class="nav-link" href="{{ route('contact', ['locale' => app()->getLocale()]) }}">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">{{ $actualite->titre }}</h1>
                    <p class="lead mb-0">Actualité CSAR - {{ $actualite->published_at ? $actualite->published_at->format('d/m/Y') : $actualite->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="{{ route('public.actualites') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Retour aux actualités
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="article-header">
                    <!-- Couverture principale -->
                    @php
                        $coverChoice = $actualite->cover_choice ?? 'auto';
                        $showVideo = false;
                        $showImage = false;
                        
                        // Déterminer ce qui doit être affiché selon le choix
                        $hasImage = !empty($actualite->cover_image) || !empty($actualite->featured_image);
                        $hasVideo = !empty($actualite->youtube_url);
                        
                        switch ($coverChoice) {
                            case 'video':
                                $showVideo = $hasVideo;
                                $showImage = !$showVideo && $hasImage;
                                break;
                            case 'image':
                                $showImage = $hasImage;
                                $showVideo = !$showImage && $hasVideo;
                                break;
                            case 'auto':
                            default:
                                // Logique automatique : vidéo en priorité
                                $showVideo = $hasVideo;
                                $showImage = !$showVideo && $hasImage;
                                break;
                        }
                    @endphp

                    <!-- Image de couverture -->
                    @if($showImage)
                        @php
                            // Utiliser cover_image en priorité, sinon featured_image
                            $imagePath = $actualite->cover_image ?? $actualite->featured_image;
                            $imageUrl = $imagePath ? asset('storage/' . $imagePath) : null;
                        @endphp
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $actualite->titre }}" class="article-image">
                        @endif
                    @endif

                    <!-- Vidéo -->
                    @if($showVideo)
                        <div class="video-container">
                            @php
                                // Convertir l'URL YouTube en URL d'embed
                                $embedUrl = $actualite->youtube_url;
                                if (strpos($embedUrl, 'youtube.com/watch?v=') !== false) {
                                    $embedUrl = str_replace('youtube.com/watch?v=', 'youtube.com/embed/', $embedUrl);
                                } elseif (strpos($embedUrl, 'youtu.be/') !== false) {
                                    $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $embedUrl);
                                }
                                // Supprimer les paramètres supplémentaires
                                $embedUrl = strtok($embedUrl, '&');
                            @endphp
                            <iframe src="{{ $embedUrl }}" 
                                    title="{{ $actualite->titre }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen></iframe>
                        </div>
                    @endif

                    <!-- Article Meta -->
                    <div class="article-meta">
                        <div>
                            <i class="fas fa-user text-primary me-2"></i>
                            <strong>{{ $actualite->auteur }}</strong>
                        </div>
                        <div>
                            <i class="fas fa-calendar text-primary me-2"></i>
                            {{ $actualite->published_at ? $actualite->published_at->format('d/m/Y à H:i') : $actualite->created_at->format('d/m/Y à H:i') }}
                        </div>
                        <div>
                            <i class="fas fa-eye text-primary me-2"></i>
                            {{ $actualite->vues }} vues
                        </div>
                        <div>
                            <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $actualite->categorie)) }}</span>
                        </div>
                    </div>

                    <!-- Article Content -->
                    <div class="article-content">
                        {!! $actualite->contenu !!}
                    </div>

                    <!-- Document associé -->
                    @if($actualite->document_file)
                        <div class="document-card">
                            <h4 class="mb-3">
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                Document associé
                            </h4>
                            
                            <div class="document-preview">
                                @php
                                    $extension = strtolower(pathinfo($actualite->document_file, PATHINFO_EXTENSION));
                                @endphp
                                
                                @if($actualite->document_cover_image && \Storage::disk('public')->exists($actualite->document_cover_image))
                                    <!-- Afficher l'image de couverture personnalisée -->
                                    <img src="{{ asset('storage/' . $actualite->document_cover_image) }}" 
                                         alt="Couverture du document" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @elseif($extension === 'pdf')
                                    <!-- Afficher l'icône PDF par défaut -->
                                    <div class="document-icon">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    </div>
                                @else
                                    <!-- Pour d'autres types de documents, afficher l'icône -->
                                    <div class="document-icon">
                                        <i class="fas fa-file-alt text-secondary"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">{{ $actualite->document_title ?? 'Document associé' }}</h5>
                                    <small class="text-muted">
                                        <i class="fas fa-download me-1"></i>
                                        {{ $actualite->downloads_count ?? 0 }} téléchargement(s)
                                    </small>
                                </div>
                                <a href="{{ route('public.actualites.download', $actualite->id) }}" class="btn-download">
                                    <i class="fas fa-download"></i>
                                    Télécharger
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actualités similaires -->
                @if(isset($related) && $related->count() > 0)
                    <div class="related-articles">
                        <h4 class="mb-4">
                            <i class="fas fa-newspaper me-2"></i>
                            Actualités similaires
                        </h4>
                        
                        @foreach($related as $relatedArticle)
                            <div class="related-card mb-3">
                                @if($relatedArticle->image)
                                    <img src="{{ $relatedArticle->image }}" alt="{{ $relatedArticle->titre }}" class="related-image">
                                @endif
                                <div class="p-3">
                                    <h6 class="mb-2">
                                        <a href="{{ route('public.actualites.show', $relatedArticle->id) }}" class="text-decoration-none">
                                            {{ $relatedArticle->titre }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $relatedArticle->published_at ? $relatedArticle->published_at->format('d/m/Y') : $relatedArticle->created_at->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>CSAR</h5>
                    <p>Commissariat à la Sécurité Alimentaire et à la Résilience</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; {{ date('Y') }} CSAR. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

