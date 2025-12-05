@extends('layouts.public')

@section('title', 'Discours Officiels')

@section('content')
<div class="container" style="max-width: 900px; margin: 0 auto;">
    <h1 class="section-title">Discours Officiels</h1>
    <p class="section-subtitle">Messages de la Direction Générale et du Ministère</p>
    <div class="speeches-list" style="display: flex; flex-direction: column; gap: 2rem; margin-top: 2rem;">
        @forelse($speeches as $speech)
        <div class="speech-card" style="display: flex; gap: 2rem; align-items: flex-start; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #e5e7eb; padding: 1.5rem; transition: all 0.3s ease; border: 1px solid #f3f4f6;">
            <div style="position: relative; flex-shrink: 0;">
                @if($speech->portrait)
                    <img src="{{ asset('storage/'.$speech->portrait) }}" 
                         alt="Portrait de {{ $speech->author }}" 
                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; border: 3px solid #e5e7eb; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.3s ease;"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($speech->author) }}&background=059669&color=ffffff&size=200&bold=true&format=png'">
                @else
                    <div style="width: 100px; height: 100px; border-radius: 12px; background: linear-gradient(135deg, #059669 0%, #047857 100%); display: flex; align-items: center; justify-content: center; border: 3px solid #e5e7eb; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($speech->author) }}&background=059669&color=ffffff&size=200&bold=true&format=png" 
                             alt="Portrait de {{ $speech->author }}" 
                             style="width: 100%; height: 100%; border-radius: 9px; object-fit: cover;">
                    </div>
                @endif
                <div style="position: absolute; bottom: -5px; right: -5px; background: #059669; color: white; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 10px; box-shadow: 0 2px 8px rgba(5,150,105,0.3);">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <div style="flex: 1;">
                <h2 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; color: #059669; font-weight: 700;">{{ $speech->title }}</h2>
                <div style="color: #374151; font-weight: 600; margin-bottom: 0.25rem; font-size: 1rem;">{{ $speech->author }}</div>
                <div style="color: #6b7280; font-size: 0.95rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-calendar-alt" style="margin-right: 8px; color: #059669;"></i>
                    {{ $speech->date ? \Carbon\Carbon::parse($speech->date)->format('d/m/Y') : 'Date non spécifiée' }}
                </div>
                @if($speech->function)
                    <div style="color: #059669; font-size: 0.9rem; margin-bottom: 0.5rem; font-weight: 600;">
                        <i class="fas fa-briefcase" style="margin-right: 8px;"></i>
                        {{ $speech->function }}
                    </div>
                @endif
                @if($speech->excerpt)
                    <blockquote style="font-style: italic; color: #374151; border-left: 4px solid #059669; padding-left: 1rem; margin-bottom: 1rem; background: #f8fafc; padding: 1rem; border-radius: 8px;">"{{ $speech->excerpt }}"</blockquote>
                @endif
                <a href="{{ route('speech', $speech->id) }}" class="btn btn-primary" style="margin-top: 0.5rem; display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(5,150,105,0.3);">
                    <i class="fas fa-book-open"></i>
                    Lire le discours complet
                </a>
            </div>
        </div>
        @empty
        <div style="text-align: center; color: #6b7280; padding: 2rem;">Aucun discours officiel pour le moment.</div>
        @endforelse
    </div>
</div>

<style>
/* RESPONSIVE GLOBAL - TOUTES LES PAGES */
@media (max-width: 1024px) {
    .container { max-width: 100%; padding-left: 1.5rem; padding-right: 1.5rem; }
    div[style*="grid-template-columns"] { grid-template-columns: 1fr !important; gap: 1.5rem !important; }
    .hero, .hero-section, .partners-hero { min-height: 50vh !important; padding: 50px 0 !important; }
    .main-title, h1 { font-size: 2.2rem !important; }
    .section-title, h2 { font-size: 1.8rem !important; }
}

@media (max-width: 768px) {
    .container { padding-left: 1rem; padding-right: 1rem; }
    section, .section { padding: 50px 0 !important; }
    .main-title, h1 { font-size: 1.8rem !important; }
    .section-title, h2 { font-size: 1.5rem !important; }
    .main-subtitle, .section-subtitle { font-size: 1rem !important; }
    div[style*="grid-template-columns"],
    div[style*="display: grid"] { grid-template-columns: 1fr !important; gap: 1rem !important; }
    div[style*="display: flex"],
    .flex-row { flex-direction: column !important; gap: 1rem !important; }
    .card, .news-card, .partner-card { margin-bottom: 1rem; padding: 1.5rem !important; }
    .btn, button[type="submit"] { width: 100%; justify-content: center; }
    .search-box, input[type="text"], input[type="search"] { width: 100% !important; }
    .filter-btn { font-size: 0.85rem; padding: 10px 15px; }
}

@media (max-width: 480px) {
    .main-title, h1 { font-size: 1.5rem !important; }
    .section-title, h2 { font-size: 1.3rem !important; }
    h3 { font-size: 1.1rem !important; }
    .card { padding: 1rem !important; }
}
</style>
@endsection 