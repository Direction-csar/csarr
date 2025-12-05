@extends('layouts.drh')

@section('title', 'Gestion du Personnel - DRH')

@section('content')
<!-- Page title -->
<div class="page-title-box">
    <h4>Gestion du Personnel - Direction des Ressources Humaines</h4>
</div>

    <!-- Statistiques Personnel -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">Total Personnel</h5>
                            <h3 class="mt-3 mb-3">{{ $totalPersonnel }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="fas fa-users font-20 text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">Validés</h5>
                            <h3 class="mt-3 mb-3">{{ $validatedCount }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded">
                                <i class="fas fa-user-check font-20 text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">En Attente</h5>
                            <h3 class="mt-3 mb-3">{{ $pendingCount }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded">
                                <i class="fas fa-user-clock font-20 text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0">Rejetés</h5>
                            <h3 class="mt-3 mb-3">{{ $rejectedCount }}</h3>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-danger rounded">
                                <i class="fas fa-user-times font-20 text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions DRH -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Actions DRH</h5>
                        <div>
                            <a href="{{ route('drh.personnel.create') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Ajouter du Personnel
                            </a>
                            <a href="{{ route('drh.personnel.export-pdf') }}" class="btn btn-success">
                                <i class="fas fa-file-pdf me-2"></i>Export PDF
                            </a>
                            <a href="{{ route('drh.personnel.export-excel') }}" class="btn btn-info">
                                <i class="fas fa-file-excel me-2"></i>Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Filtres et recherche -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="direction" class="form-control">
                                    <option value="">Toutes directions</option>
                                    @foreach($directions as $direction)
                                        <option value="{{ $direction }}" {{ request('direction') == $direction ? 'selected' : '' }}>
                                            {{ $direction }} ({{ $directionCounts[$direction] ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="poste" class="form-control">
                                    <option value="">Tous postes</option>
                                    @foreach($postes as $poste)
                                        <option value="{{ $poste }}" {{ request('poste') == $poste ? 'selected' : '' }}>{{ $poste }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="sort" class="form-control">
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date recrutement</option>
                                    <option value="poste" {{ request('sort') == 'poste' ? 'selected' : '' }}>Poste</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                <a href="{{ route('drh.personnel.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau du personnel -->
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Matricule</th>
                                    <th>Poste</th>
                                    <th>Direction</th>
                                    <th>Email</th>
                                    <th>Statut</th>
                                    <th>Actions DRH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($personnel as $personne)
                                <tr>
                                    <td>
                                        @if($personne->photo_personnelle)
                                            <img src="{{ Storage::url('personnel/' . $personne->photo_personnelle) }}" 
                                                 alt="Photo" class="rounded-circle" width="40" height="40">
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <h5 class="m-0">{{ $personne->prenoms_nom }}</h5>
                                            <small class="text-muted">{{ $personne->matricule }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $personne->matricule }}</td>
                                    <td>{{ $personne->poste_actuel }}</td>
                                    <td>{{ $personne->direction_service }}</td>
                                    <td>{{ $personne->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $personne->statut_validation == 'Valide' ? 'success' : 'warning' }}">
                                            {{ $personne->statut_validation ?? 'En attente' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('drh.personnel.show', $personne) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('drh.personnel.edit', $personne) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('drh.personnel.export-fiche-pdf', $personne) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <form action="{{ route('drh.personnel.destroy', $personne) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce personnel ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aucun personnel trouvé</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $personnel->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
