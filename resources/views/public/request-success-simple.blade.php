@extends('layouts.public')

@section('title', 'Demande soumise avec succès - CSAR')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #10b981 0%, #2563eb 100%);">
    <div class="row w-100">
        <div class="col-12">
            <div class="text-center">
                <!-- Carte de succès -->
                <div class="card border-0 shadow-lg mx-auto" style="max-width: 600px; border-radius: 20px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                    <div class="card-body p-5">
                        <!-- Icône de succès -->
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success" style="width: 80px; height: 80px;">
                                <i class="fas fa-check text-white" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>

                        <!-- Titre -->
                        <h1 class="h2 fw-bold text-dark mb-3">
                            🎉 Demande Soumise avec Succès !
                        </h1>

                        <!-- Message principal -->
                        <div class="mb-4">
                            <p class="lead text-muted mb-3">
                                Votre demande a été transmise avec succès au CSAR.
                            </p>
                            
                            @if(session('tracking_code'))
                            <div class="alert alert-info border-0" style="background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-barcode me-3 text-primary"></i>
                                    <div>
                                        <strong>Code de suivi :</strong><br>
                                        <code class="fs-5 fw-bold text-primary">{{ session('tracking_code') }}</code>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Informations importantes -->
                        <div class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 rounded" style="background: #f8f9fa;">
                                        <i class="fas fa-clock text-primary me-3"></i>
                                        <div>
                                            <small class="text-muted d-block">Délai de traitement</small>
                                            <strong>24-48 heures</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 rounded" style="background: #f8f9fa;">
                                        <i class="fas fa-phone text-success me-3"></i>
                                        <div>
                                            <small class="text-muted d-block">Contact</small>
                                            <strong>+221 33 XXX XX XX</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            @if(session('tracking_code'))
                            <a href="{{ route('track') }}" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-search me-2"></i>
                                Suivre ma demande
                            </a>
                            @endif
                            <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fas fa-home me-2"></i>
                                Retour à l'accueil
                            </a>
                            <a href="{{ route('demande.create') }}" class="btn btn-success btn-lg px-4">
                                <i class="fas fa-plus me-2"></i>
                                Nouvelle demande
                            </a>
                        </div>

                        <!-- Message de remerciement -->
                        <div class="mt-4 pt-3 border-top">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-heart text-danger me-1"></i>
                                Merci pour votre confiance envers le CSAR
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Particules flottantes (optionnel) -->
                <div class="position-fixed top-0 start-0 w-100 h-100" style="pointer-events: none; z-index: -1;">
                    <div class="position-absolute" style="top: 20%; left: 10%; animation: float 6s ease-in-out infinite;">
                        <i class="fas fa-star text-white opacity-25" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="position-absolute" style="top: 60%; right: 15%; animation: float 8s ease-in-out infinite reverse;">
                        <i class="fas fa-heart text-white opacity-25" style="font-size: 1.2rem;"></i>
                    </div>
                    <div class="position-absolute" style="top: 40%; left: 80%; animation: float 7s ease-in-out infinite;">
                        <i class="fas fa-check-circle text-white opacity-25" style="font-size: 1.8rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.card {
    animation: fadeInUp 0.8s ease-out;
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

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
</style>
@endsection
