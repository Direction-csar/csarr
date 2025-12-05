@extends('layouts.public')

@section('title', $report->title . ' - Rapports SIM - CSAR')

@section('content')
<div class="container-fluid py-5">
    <!-- En-tête -->
    <div class="row mb-5">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('sim-reports.index') }}">Rapports SIM</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $report->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <!-- Image de couverture -->
                @if($report->cover_image)
                    <img src="{{ asset('storage/' . $report->cover_image) }}" 
                         class="card-img-top" 
                         alt="{{ $report->title }}"
                         style="height: 300px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" 
                         style="height: 300px;">
                        <div class="text-center text-white">
                            <i class="fas fa-chart-line fa-4x mb-3"></i>
                            <h4>{{ $report->title }}</h4>
                        </div>
                    </div>
                @endif

                <div class="card-body">
                    <!-- En-tête du rapport -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <span class="badge bg-{{ $report->report_type === 'financial' ? 'success' : ($report->report_type === 'operational' ? 'info' : ($report->report_type === 'inventory' ? 'warning' : ($report->report_type === 'personnel' ? 'secondary' : 'primary'))) }} fs-6">
                                {{ ucfirst($report->report_type) }}
                            </span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $report->published_at ? \Carbon\Carbon::parse($report->published_at)->format('d/m/Y') : 'N/A' }}
                        </small>
                    </div>

                    <h1 class="card-title text-primary mb-3">{{ $report->title }}</h1>
                    
                    @if($report->description)
                        <p class="lead text-muted mb-4">{{ $report->description }}</p>
                    @endif

                    @if($report->summary)
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Résumé
                            </h6>
                            <p class="mb-0">{{ $report->summary }}</p>
                        </div>
                    @endif

                    <!-- Contenu du rapport -->
                    @if($report->content)
                        <div class="report-content">
                            <h5 class="mb-3">Contenu du rapport</h5>
                            <div class="content-body">
                                {!! $report->content !!}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Contenu du rapport</h5>
                            <p class="text-muted">Le contenu détaillé de ce rapport sera disponible prochainement.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informations du rapport -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <small class="text-muted d-block">Vues</small>
                                <strong class="text-primary fs-5">{{ $report->view_count ?? 0 }}</strong>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Téléchargements</small>
                            <strong class="text-success fs-5">{{ $report->download_count ?? 0 }}</strong>
                        </div>
                    </div>
                    
                    @if($report->file_size)
                        <div class="text-center mb-3">
                            <small class="text-muted d-block">Taille du fichier</small>
                            <strong class="text-info">{{ number_format($report->file_size / 1024 / 1024, 2) }} MB</strong>
                        </div>
                    @endif

                    @if($report->generated_at)
                        <div class="text-center mb-3">
                            <small class="text-muted d-block">Généré le</small>
                            <strong class="text-secondary">{{ \Carbon\Carbon::parse($report->generated_at)->format('d/m/Y H:i') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-download me-2"></i>Télécharger
                    </h6>
                </div>
                <div class="card-body">
                    @if($report->document_file)
                        <a href="{{ route('sim-reports.download', $report->id) }}" 
                           class="btn btn-success w-100 mb-2">
                            <i class="fas fa-{{ pathinfo($report->document_file, PATHINFO_EXTENSION) === 'pdf' ? 'file-pdf' : (in_array(pathinfo($report->document_file, PATHINFO_EXTENSION), ['ppt', 'pptx']) ? 'file-powerpoint' : (in_array(pathinfo($report->document_file, PATHINFO_EXTENSION), ['doc', 'docx']) ? 'file-word' : (in_array(pathinfo($report->document_file, PATHINFO_EXTENSION), ['xls', 'xlsx']) ? 'file-excel' : 'download'))) }} me-2"></i>
                            Télécharger {{ strtoupper(pathinfo($report->document_file, PATHINFO_EXTENSION)) }}
                        </a>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                            <p class="mb-0">Document non disponible</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Retour -->
            <div class="mt-4">
                <a href="{{ route('sim-reports.index') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux rapports
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.report-content {
    line-height: 1.8;
}

.content-body {
    font-size: 1.1rem;
}

.content-body h1, .content-body h2, .content-body h3 {
    color: var(--bs-primary);
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.content-body p {
    margin-bottom: 1.5rem;
}

.content-body ul, .content-body ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.content-body table {
    width: 100%;
    margin-bottom: 1.5rem;
    border-collapse: collapse;
}

.content-body table th,
.content-body table td {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
}

.content-body table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
</style>
@endsection
