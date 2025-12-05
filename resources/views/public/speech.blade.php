@extends('layouts.public')

@section('title', $speech->title . ' - CSAR')

@section('content')
<!-- Hero Section -->
<section class="hero" style="min-height: 40vh; padding: 80px 20px 40px;">
    <div class="hero-content">
        <h2>{{ $speech->title }}</h2>
        <p>Discours officiel du CSAR</p>
    </div>
</section>

<!-- Speech Content Section -->
<section class="section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <!-- Speech Header -->
            <div class="card" style="margin-bottom: 30px;">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    @if($speech->portrait)
                        <img src="{{ asset($speech->portrait) }}" alt="{{ $speech->author }}" 
                             style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-right: 25px; border: 4px solid #22c55e;">
                    @else
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 25px;">
                            <i class="fas fa-user-tie" style="font-size: 40px; color: white;"></i>
                        </div>
                    @endif
                    
                    <div>
                        <h3 style="color: #1f2937; margin-bottom: 8px; font-size: 24px;">{{ $speech->author }}</h3>
                        @if($speech->function)
                            <p style="color: #6b7280; font-size: 16px; margin-bottom: 8px;">{{ $speech->function }}</p>
                        @endif
                        <p style="color: #6b7280; font-size: 14px;">
                            <i class="fas fa-calendar"></i> {{ $speech->formatted_date }}
                        </p>
                        @if($speech->location)
                            <p style="color: #6b7280; font-size: 14px; margin-top: 5px;">
                                <i class="fas fa-map-marker-alt"></i> {{ $speech->location }}
                            </p>
                        @endif
                    </div>
                </div>
                
                @if($speech->summary)
                    <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border-left: 4px solid #059669;">
                        <h4 style="color: #1f2937; margin-bottom: 10px; font-size: 16px;">Résumé</h4>
                        <p style="color: #6b7280; font-style: italic; margin: 0;">{{ $speech->summary }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Speech Content -->
            <div class="card">
                <div style="font-size: 16px; line-height: 1.8; color: #374151;">
                    {!! nl2br(e($speech->content)) !!}
                </div>
            </div>
            
            <!-- Navigation -->
            <div style="display: flex; justify-content: space-between; margin-top: 30px; flex-wrap: wrap; gap: 15px;">
                <a href="{{ route('speeches') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour aux discours
                </a>
                
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="fas fa-print"></i> Imprimer
                    </button>
                    <button onclick="downloadPDF()" class="btn btn-primary">
                        <i class="fas fa-download"></i> Télécharger PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Speeches Section -->
@if(isset($relatedSpeeches) && $relatedSpeeches->count() > 0)
<section class="section" style="background: #f8fafc;">
    <div class="container">
        <h2 class="section-title">Autres discours</h2>
        <p class="section-subtitle">Discours récents du CSAR</p>
        
        <div class="cards-grid">
            @foreach($relatedSpeeches as $relatedSpeech)
                <div class="card">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        @if($relatedSpeech->portrait)
                            <img src="{{ asset($relatedSpeech->portrait) }}" alt="{{ $relatedSpeech->author }}" 
                                 style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 15px; border: 2px solid #22c55e;">
                        @else
                            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                <i class="fas fa-user-tie" style="font-size: 20px; color: white;"></i>
                            </div>
                        @endif
                        <div>
                            <h3 style="font-size: 16px; margin: 0 0 5px 0;">{{ $relatedSpeech->author }}</h3>
                            <p style="color: #6b7280; font-size: 12px; margin: 0;">{{ $relatedSpeech->formatted_date }}</p>
                        </div>
                    </div>
                    
                    <h4 style="font-size: 16px; margin-bottom: 10px; color: #059669;">{{ $relatedSpeech->title }}</h4>
                    
                    <p style="font-size: 14px; color: #374151;">{{ Str::limit($relatedSpeech->content, 100) }}</p>
                    
                    <div style="margin-top: 15px;">
                        <a href="{{ route('speech', $relatedSpeech->id) }}" class="btn btn-secondary" style="font-size: 14px; padding: 8px 16px;">
                            Lire plus
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@section('scripts')
<script>
function downloadPDF() {
    // Cette fonction peut être implémentée pour générer un PDF
    // Pour l'instant, on utilise la fonction d'impression du navigateur
    window.print();
}

// Améliorer l'impression
window.addEventListener('beforeprint', function() {
    // Masquer les éléments non nécessaires à l'impression
    document.querySelectorAll('.btn, .hero').forEach(el => {
        el.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    // Restaurer l'affichage
    document.querySelectorAll('.btn, .hero').forEach(el => {
        el.style.display = '';
    });
});
</script>
@endsection

@section('styles')
<style>
@media print {
    .hero, .btn, .section:last-child {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    body {
        font-size: 12pt;
        line-height: 1.4;
    }
}
</style>
@endsection 