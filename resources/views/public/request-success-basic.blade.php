<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demande soumise avec succès - CSAR</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #10b981 0%, #2563eb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .success-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
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
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100">
            <div class="col-12">
                <div class="text-center">
                    <!-- Carte de succès -->
                    <div class="card border-0 success-card mx-auto" style="max-width: 600px;">
                        <div class="card-body p-5">
                            <!-- Icône de succès -->
                            <div class="success-icon">
                                <i class="fas fa-check text-white" style="font-size: 2.5rem;"></i>
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
                                <a href="/suivre-ma-demande" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-search me-2"></i>
                                    Suivre ma demande
                                </a>
                                @endif
                                <a href="/" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="fas fa-home me-2"></i>
                                    Retour à l'accueil
                                </a>
                                <a href="/demande" class="btn btn-success btn-lg px-4">
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
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
