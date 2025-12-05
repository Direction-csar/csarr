@extends('layouts.admin')

@section('title', 'Entrepôts')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-list me-2"></i>Entrepôts
                    </h1>
                    <p class="text-muted mb-0">Gérez les Entrepôts du CSAR</p>
                </div>
                <div>
                    <button class="btn btn-primary-modern btn-modern">
                        <i class="fas fa-plus me-2"></i>Ajouter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">
                    <div class="text-center py-5">
                        <div class="icon-3d mb-3" style="background: var(--gradient-primary); width: 80px; height: 80px; margin: 0 auto;">
                            <i class="fas fa-list"></i>
                        </div>
                        <h5 class="text-muted">Module Entrepôts</h5>
                        <p class="text-muted">Ce module sera bientôt disponible.</p>
                        <button class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-plus me-2"></i>Commencer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection