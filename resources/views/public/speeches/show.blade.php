@extends('layouts.public')

@section('title', $speech->title)

@section('content')
<style>
    .speech-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
    }

    .speech-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.3);
        position: relative;
        overflow: hidden;
    }

    .speech-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .speech-hero-content {
        position: relative;
        z-index: 1;
    }

    .speech-meta {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .speech-portrait {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .speech-portrait:hover {
        transform: scale(1.05);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .speech-author-info {
        flex: 1;
        min-width: 200px;
    }

    .speech-author {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .speech-date {
        font-size: 1rem;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .speech-title {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .speech-excerpt {
        font-size: 1.2rem;
        font-style: italic;
        opacity: 0.9;
        line-height: 1.6;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 0.75rem;
        border-left: 4px solid #60a5fa;
        backdrop-filter: blur(10px);
    }

    .speech-content-wrapper {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.1);
    }

    .speech-content-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .content-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .content-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }

    .speech-content {
        padding: 3rem 2rem;
        line-height: 1.8;
        font-size: 1.1rem;
        color: #374151;
    }

    .speech-content p {
        margin-bottom: 1.5rem;
        text-align: justify;
    }

    .speech-content h1, .speech-content h2, .speech-content h3 {
        color: #1e293b;
        font-weight: 700;
        margin: 2rem 0 1rem 0;
    }

    .speech-content h1 {
        font-size: 1.8rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }

    .speech-content h2 {
        font-size: 1.5rem;
        color: #0f172a;
    }

    .speech-content h3 {
        font-size: 1.3rem;
        color: #1e293b;
    }

    .speech-content ul, .speech-content ol {
        margin: 1.5rem 0;
        padding-left: 2rem;
    }

    .speech-content li {
        margin-bottom: 0.75rem;
        line-height: 1.6;
    }

    .speech-content strong {
        color: #0f172a;
        font-weight: 700;
    }

    .speech-content em {
        color: #6b7280;
        font-style: italic;
    }

    .speech-actions {
        background: #f8fafc;
        padding: 2rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.4);
        color: white;
        text-decoration: none;
    }

    .speech-footer {
        margin-top: 2rem;
        padding: 2rem;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-radius: 1rem;
        border: 1px solid #bbf7d0;
    }

    .footer-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .footer-content {
        color: #6b7280;
        line-height: 1.6;
    }

    .reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: #e2e8f0;
        z-index: 1000;
    }

    .reading-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        width: 0%;
        transition: width 0.3s ease;
    }

    .table-of-contents {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(15, 23, 42, 0.1);
    }

    .toc-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .toc-item {
        margin-bottom: 0.5rem;
    }

    .toc-link {
        color: #6b7280;
        text-decoration: none;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
        display: block;
    }

    .toc-link:hover {
        background: #f1f5f9;
        color: #1e293b;
        text-decoration: none;
    }

    .toc-link.active {
        background: #0f172a;
        color: white;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .slide-in-left {
        animation: slideInLeft 0.6s ease-out;
    }

    @media (max-width: 768px) {
        .speech-container {
            padding: 1rem;
        }
        
        .speech-hero {
            padding: 2rem 1rem;
        }
        
        .speech-title {
            font-size: 2rem;
        }
        
        .speech-meta {
            flex-direction: column;
            text-align: center;
        }
        
        .speech-content {
            padding: 2rem 1rem;
            font-size: 1rem;
        }
        
        .speech-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Barre de progression de lecture -->
<div class="reading-progress">
    <div class="reading-progress-bar" id="readingProgress"></div>
</div>

<div class="speech-container">
    <!-- En-tête du discours -->
    <div class="speech-hero fade-in-up">
        <div class="speech-hero-content">
            <div class="speech-meta">
                <div style="position: relative;">
                    @if($speech->portrait)
                        <img src="{{ asset('storage/'.$speech->portrait) }}" 
                             alt="Portrait de {{ $speech->author }}" 
                             class="speech-portrait"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($speech->author) }}&background=059669&color=ffffff&size=240&bold=true&format=png'">
                    @else
                        <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #059669 0%, #047857 100%); display: flex; align-items: center; justify-content: center; border: 4px solid rgba(255, 255, 255, 0.2); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($speech->author) }}&background=059669&color=ffffff&size=240&bold=true&format=png" 
                                 alt="Portrait de {{ $speech->author }}" 
                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        </div>
                    @endif
                    <div style="position: absolute; bottom: 5px; right: 5px; background: rgba(255,255,255,0.9); color: #059669; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="speech-author-info">
                    <div class="speech-author">{{ $speech->author }}</div>
                    <div class="speech-date">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $speech->date ? \Carbon\Carbon::parse($speech->date)->format('d/m/Y') : 'Date non spécifiée' }}
                    </div>
                    @if($speech->function)
                        <div style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-top: 8px;">
                            <i class="fas fa-briefcase" style="margin-right: 8px;"></i>
                            {{ $speech->function }}
                        </div>
                    @endif
                </div>
            </div>
            
            <h1 class="speech-title">{{ $speech->title }}</h1>
            
            @if($speech->excerpt)
                <div class="speech-excerpt">
                    <i class="fas fa-quote-left" style="margin-right: 0.5rem;"></i>
                    {{ $speech->excerpt }}
                    <i class="fas fa-quote-right" style="margin-left: 0.5rem;"></i>
                </div>
            @endif
        </div>
    </div>

    <!-- Table des matières (si le contenu est long) -->
    @if(strlen($speech->content) > 1000)
        <div class="table-of-contents slide-in-left">
            <div class="toc-title">
                <i class="fas fa-list"></i>
                Table des matières
            </div>
            <ul class="toc-list" id="tocList">
                <!-- Généré par JavaScript -->
            </ul>
        </div>
    @endif

    <!-- Contenu principal -->
    <div class="speech-content-wrapper fade-in-up">
        <div class="speech-content-header">
            <div class="content-title">
                <i class="fas fa-file-text"></i>
                Discours complet
            </div>
            <div class="content-subtitle">
                Lecture estimée : {{ ceil(strlen($speech->content) / 200) }} minutes
            </div>
        </div>
        
        <div class="speech-content" id="speechContent">
            {!! nl2br(e($speech->content)) !!}
        </div>
        
        <div class="speech-actions">
            <a href="{{ route('speeches') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
            
            <div style="display: flex; gap: 1rem;">
                <button class="btn btn-primary" onclick="shareSpeech()">
                    <i class="fas fa-share"></i>
                    Partager
                </button>
                <button class="btn btn-primary" onclick="printSpeech()">
                    <i class="fas fa-print"></i>
                    Imprimer
                </button>
            </div>
        </div>
    </div>

    <!-- Pied de page informatif -->
    <div class="speech-footer fade-in-up">
        <div class="footer-title">
            <i class="fas fa-info-circle"></i>
            À propos de ce discours
        </div>
        <div class="footer-content">
            Ce discours officiel fait partie de la collection des interventions publiques du CSAR. 
            Il reflète l'engagement de nos dirigeants pour la sécurité alimentaire et la résilience au Sénégal.
        </div>
    </div>
</div>

<script>
// Barre de progression de lecture
function updateReadingProgress() {
    const content = document.getElementById('speechContent');
    const progressBar = document.getElementById('readingProgress');
    
    if (content && progressBar) {
        const scrollTop = window.pageYOffset;
        const contentTop = content.offsetTop;
        const contentHeight = content.offsetHeight;
        const windowHeight = window.innerHeight;
        
        const scrollProgress = Math.min(
            Math.max((scrollTop - contentTop + windowHeight) / contentHeight, 0),
            1
        );
        
        progressBar.style.width = (scrollProgress * 100) + '%';
    }
}

// Table des matières
function generateTableOfContents() {
    const content = document.getElementById('speechContent');
    const tocList = document.getElementById('tocList');
    
    if (!content || !tocList) return;
    
    const headings = content.querySelectorAll('h1, h2, h3');
    const toc = [];
    
    headings.forEach((heading, index) => {
        const id = `heading-${index}`;
        heading.id = id;
        
        const level = parseInt(heading.tagName.charAt(1));
        const text = heading.textContent;
        
        toc.push({
            id: id,
            text: text,
            level: level
        });
    });
    
    if (toc.length > 0) {
        tocList.innerHTML = toc.map(item => `
            <li class="toc-item">
                <a href="#${item.id}" class="toc-link" style="padding-left: ${(item.level - 1) * 1.5}rem;">
                    ${item.text}
                </a>
            </li>
        `).join('');
    }
}

// Navigation fluide pour les liens de la table des matières
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('toc-link')) {
        e.preventDefault();
        const targetId = e.target.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
});

// Mise à jour de la table des matières active
function updateActiveTocItem() {
    const headings = document.querySelectorAll('#speechContent h1, #speechContent h2, #speechContent h3');
    const tocLinks = document.querySelectorAll('.toc-link');
    
    let activeId = '';
    
    headings.forEach(heading => {
        const rect = heading.getBoundingClientRect();
        if (rect.top <= 100 && rect.bottom >= 100) {
            activeId = heading.id;
        }
    });
    
    tocLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + activeId) {
            link.classList.add('active');
        }
    });
}

// Partage du discours
function shareSpeech() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $speech->title }}',
            text: '{{ $speech->excerpt ?: $speech->title }}',
            url: window.location.href
        });
    } else {
        // Fallback pour les navigateurs qui ne supportent pas l'API de partage
        const url = window.location.href;
        const text = '{{ $speech->title }}';
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(`${text}\n\n${url}`).then(() => {
                alert('Lien copié dans le presse-papiers !');
            });
        } else {
            // Fallback pour les anciens navigateurs
            const textArea = document.createElement('textarea');
            textArea.value = `${text}\n\n${url}`;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Lien copié dans le presse-papiers !');
        }
    }
}

// Impression du discours
function printSpeech() {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>{{ $speech->title }}</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; margin: 2rem; }
                    h1 { color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; }
                    h2 { color: #0f172a; }
                    h3 { color: #1e293b; }
                    .meta { color: #6b7280; margin-bottom: 2rem; }
                    .excerpt { font-style: italic; color: #6b7280; margin: 2rem 0; padding: 1rem; background: #f8fafc; border-left: 4px solid #3b82f6; }
                    @media print { body { margin: 1rem; } }
                </style>
            </head>
            <body>
                <h1>{{ $speech->title }}</h1>
                <div class="meta">
                    <strong>{{ $speech->author }}</strong><br>
                    {{ $speech->date ? \Carbon\Carbon::parse($speech->date)->format('d/m/Y') : 'Date non spécifiée' }}
                </div>
                ${@if($speech->excerpt)
                    '<div class="excerpt">'{{ $speech->excerpt }}'</div>'
                @endif}
                <div class="content">
                    {!! nl2br(e($speech->content)) !!}
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    generateTableOfContents();
    
    // Événements de scroll
    window.addEventListener('scroll', function() {
        updateReadingProgress();
        updateActiveTocItem();
    });
    
    // Mise à jour initiale
    updateReadingProgress();
});
</script>
@endsection 