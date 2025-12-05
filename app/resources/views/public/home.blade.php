@extends('layouts.public')

@section('title', 'Accueil - CSAR')

@section('content')
<style>
/* Hero Section moderne */
.hero-section {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.hero-content {
    text-align: center;
    z-index: 10;
    position: relative;
    max-width: 800px;
    padding: 0 2rem;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    color: white;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.3rem;
    color: white;
    margin-bottom: 2.5rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    line-height: 1.6;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-hero {
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.btn-primary-hero {
    background: #22c55e;
    color: white;
    border: none;
}

.btn-primary-hero:hover {
    background: #16a34a;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34, 197, 94, 0.4);
}

.btn-secondary-hero {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-secondary-hero:hover {
    background: white;
    color: #1f2937;
    transform: translateY(-2px);
}

/* Services Section */
.services-section {
    padding: 5rem 0;
    background: #f8fafc;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
}

.service-card {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.service-icon {
    width: 80px;
    height: 80px;
    background: #22c55e;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
}

.service-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 1rem;
}

.service-description {
    color: #6b7280;
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Hero Section with Dynamic Background -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Commissariat à la Sécurité Alimentaire et à la Résilience</h1>
        <p class="hero-subtitle">
            Le Commissariat à la Sécurité Alimentaire et à la Résilience œuvre pour garantir l'accès à une alimentation suffisante et nutritive pour tous les Sénégalais, tout en renforçant leur capacité à faire face aux crises et aux défis climatiques
        </p>
        <div class="hero-buttons">
            <a href="{{ '/demande' }}" class="btn-hero btn-primary-hero">
                <i class="fas fa-file-alt"></i>
                Effectuer une demande
            </a>
            <a href="{{ route('about') }}" class="btn-hero btn-secondary-hero">
                <i class="fas fa-info-circle"></i>
                Découvrir le CSAR
            </a>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section">
    <div class="container">
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3 class="service-title">Distributions alimentaires</h3>
                <p class="service-description">
                    Nos équipes distribuent des denrées alimentaires aux populations dans le besoin à travers tout le Sénégal
                </p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <h3 class="service-title">Magasins de stockage CSAR</h3>
                <p class="service-description">
                    Notre réseau de magasins de stockage stratégiques assure le stockage et la distribution des denrées alimentaires
                </p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="service-title">Suivre ma demande</h3>
                <p class="service-description">
                    Consultez l'état d'avancement de votre demande avec votre code de suivi unique
                </p>
            </div>
        </div>
    </div>
</section>

@endsection