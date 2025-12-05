@extends('layouts.public')
@section('title', 'Nos missions en images - CSAR')
@section('content')
<div style="max-width:1200px;margin:40px auto 60px auto;">
    <h1 style="font-size:2rem;font-weight:700;margin-bottom:24px;color:#0d9488;text-align:center;">Nos missions en images</h1>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(270px,1fr));gap:28px;">
        @foreach ([
            [
                'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80',
                'titre' => 'Distribution alimentaire',
                'desc' => 'Aide alimentaire d’urgence dans la région de Diourbel.'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=600&q=80',
                'titre' => 'Soutien aux agriculteurs',
                'desc' => 'Programme de résilience agricole à Saint-Louis.'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=600&q=80',
                'titre' => 'Accès à l’eau potable',
                'desc' => 'Installation de puits dans la région de Kaffrine.'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?auto=format&fit=crop&w=600&q=80',
                'titre' => 'Sensibilisation nutritionnelle',
                'desc' => 'Ateliers de nutrition pour les familles vulnérables.'
            ],
        ] as $mission)
        <div style="background:#fff;border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,0.07);overflow:hidden;display:flex;flex-direction:column;">
            <img src="{{ $mission['image'] }}" alt="{{ $mission['titre'] }}" style="width:100%;height:180px;object-fit:cover;">
            <div style="padding:18px 16px 12px 16px;flex:1;display:flex;flex-direction:column;">
                <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:8px;color:#0284c7;">{{ $mission['titre'] }}</h3>
                <p style="font-size:0.98rem;color:#334155;flex:1;">{{ $mission['desc'] }}</p>
            </div>
        </div>
        @endforeach
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
