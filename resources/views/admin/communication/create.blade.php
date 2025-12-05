@extends('layouts.admin')

@section('title', 'Nouvelle Communication')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus me-2"></i>
                Nouvelle Communication
            </h1>
            <p class="text-muted">Créer une nouvelle communication</p>
        </div>
        <div>
            <a href="{{ route('admin.communication.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Détails de la communication</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.communication.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Type de communication *</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Sélectionner un type</option>
                                <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="sms" {{ old('type') == 'sms' ? 'selected' : '' }}>SMS</option>
                                <option value="notification" {{ old('type') == 'notification' ? 'selected' : '' }}>Notification</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="recipients" class="form-label">Destinataires *</label>
                            <select class="form-control @error('recipients') is-invalid @enderror" id="recipients" name="recipients[]" multiple required>
                                <option value="all">Tous les utilisateurs</option>
                                <option value="admins">Administrateurs</option>
                                <option value="responsables">Responsables</option>
                                <option value="agents">Agents</option>
                            </select>
                            @error('recipients')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject" class="form-label">Sujet *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="content" class="form-label">Contenu *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="priority" class="form-label">Priorité</label>
                            <select class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="schedule" name="schedule" value="1" {{ old('schedule') ? 'checked' : '' }}>
                                <label class="form-check-label" for="schedule">
                                    Programmer l'envoi
                                </label>
                            </div>
                        </div>

                        <div id="schedule-datetime" class="form-group mb-3" style="display: none;">
                            <label for="scheduled_at" class="form-label">Date et heure d'envoi</label>
                            <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.communication.index') }}" class="btn btn-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aide</h6>
                </div>
                <div class="card-body">
                    <h6>Types de communication :</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope text-primary me-2"></i><strong>Email :</strong> Pour les communications détaillées</li>
                        <li><i class="fas fa-sms text-success me-2"></i><strong>SMS :</strong> Pour les alertes urgentes</li>
                        <li><i class="fas fa-bell text-warning me-2"></i><strong>Notification :</strong> Pour les notifications système</li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Conseils :</h6>
                    <ul class="small">
                        <li>Utilisez un sujet clair et concis</li>
                        <li>Adaptez le contenu au type de communication</li>
                        <li>Les SMS sont limités à 160 caractères</li>
                        <li>Vérifiez les destinataires avant l'envoi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('schedule').addEventListener('change', function() {
    const scheduleDatetime = document.getElementById('schedule-datetime');
    if (this.checked) {
        scheduleDatetime.style.display = 'block';
    } else {
        scheduleDatetime.style.display = 'none';
    }
});
</script>
@endsection



