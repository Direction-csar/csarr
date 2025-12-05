@extends('layouts.admin')

@section('title', 'Modifier le Mouvement de Stock')

@push('styles')
<style>
    .stock-form-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    
    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-title {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-weight: 700;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }
    
    .form-control, .form-select {
        border-radius: 15px;
        border: 2px solid #e9ecef;
        padding: 12px 20px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
    }
    
    .btn-modern {
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
        font-size: 16px;
    }
    
    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-modern:hover::before {
        left: 100%;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-secondary-modern {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .btn-success-modern {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .type-selection {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .type-option {
        position: relative;
        cursor: pointer;
    }
    
    .type-option input[type="radio"] {
        display: none;
    }
    
    .type-card {
        background: white;
        border: 3px solid #e9ecef;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .type-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    }
    
    .type-option input[type="radio"]:checked + .type-card {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
    }
    
    .type-icon {
        font-size: 2rem;
        margin-bottom: 10px;
        display: block;
    }
    
    .type-label {
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .alert-modern {
        border-radius: 15px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 25px;
    }
    
    .alert-danger-modern {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(253, 126, 20, 0.1));
        color: #dc3545;
        border-left: 4px solid #dc3545;
    }
    
    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }
    
    .required {
        color: #dc3545;
    }
    
    .help-text {
        font-size: 0.9rem;
        color: #6c757d;
        margin-top: 5px;
    }
    
    .current-info {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
        border-left: 4px solid #667eea;
    }
    
    .current-info h5 {
        color: #333;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .current-info .info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        padding: 5px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .current-info .info-item:last-child {
        border-bottom: none;
    }
    
    .current-info .info-label {
        font-weight: 600;
        color: #555;
    }
    
    .current-info .info-value {
        color: #333;
    }
    
    @media (max-width: 768px) {
        .form-container {
            padding: 25px;
            margin: 10px;
        }
        
        .type-selection {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="stock-form-page">
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">
                <i class="fas fa-edit me-3"></i>
                Modifier le Mouvement de Stock
            </h2>

            <!-- Informations actuelles -->
            <div class="current-info">
                <h5><i class="fas fa-info-circle me-2"></i>Informations Actuelles</h5>
                <div class="info-item">
                    <span class="info-label">Référence:</span>
                    <span class="info-value">{{ $mouvement['reference'] }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Type:</span>
                    <span class="info-value">{{ ucfirst($mouvement['type']) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Produit:</span>
                    <span class="info-value">{{ $mouvement['produit'] }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Quantité:</span>
                    <span class="info-value">{{ number_format($mouvement['quantite'], 0, ',', ' ') }} {{ $mouvement['unite'] }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date de création:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($mouvement['created_at'])->format('d/m/Y à H:i') }}</span>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger-modern alert-modern">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation :</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.stock.update', $mouvement['id']) }}" id="stockForm">
                @csrf
                @method('PUT')
                
                <!-- Sélection du type de mouvement -->
                <div class="form-group">
                    <label class="form-label">Type de Mouvement <span class="required">*</span></label>
                    <div class="type-selection">
                        <div class="type-option">
                            <input type="radio" name="type" value="in" id="type_in" {{ old('type', $mouvement->type) == 'in' ? 'checked' : '' }}>
                            <label for="type_in" class="type-card">
                                <i class="fas fa-arrow-down type-icon text-success"></i>
                                <p class="type-label">Entrée</p>
                            </label>
                        </div>
                        <div class="type-option">
                            <input type="radio" name="type" value="out" id="type_out" {{ old('type', $mouvement->type) == 'out' ? 'checked' : '' }}>
                            <label for="type_out" class="type-card">
                                <i class="fas fa-arrow-up type-icon text-danger"></i>
                                <p class="type-label">Sortie</p>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Référence -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference" class="form-label">Référence <span class="required">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="reference" 
                                   name="reference" 
                                   value="{{ old('reference', $mouvement->reference) }}"
                                   required>
                            <div class="help-text">Référence unique du mouvement</div>
                        </div>
                    </div>

                    <!-- Date du mouvement -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_mouvement" class="form-label">Date du Mouvement <span class="required">*</span></label>
                            <input type="datetime-local" 
                                   class="form-control" 
                                   id="date_mouvement" 
                                   name="date_mouvement" 
                                   value="{{ old('date_mouvement', $mouvement->created_at->format('Y-m-d\TH:i')) }}"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Entrepôt -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="warehouse_id" class="form-label">Entrepôt <span class="required">*</span></label>
                            <select class="form-select" id="warehouse_id" name="warehouse_id" required>
                                <option value="">Sélectionner un entrepôt</option>
                                @foreach($entrepots as $entrepot)
                                    <option value="{{ $entrepot->id }}" {{ old('warehouse_id', $mouvement->warehouse_id) == $entrepot->id ? 'selected' : '' }}>
                                        {{ $entrepot->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Quantité -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity" class="form-label">Quantité <span class="required">*</span></label>
                            <input type="number" 
                                   class="form-control" 
                                   id="quantity" 
                                   name="quantity" 
                                   step="0.01" 
                                   min="0.01"
                                   value="{{ old('quantity', $mouvement->quantity) }}"
                                   required>
                            <div class="help-text">Quantité en unités</div>
                        </div>
                    </div>
                </div>

                <!-- Motif -->
                <div class="form-group">
                    <label for="reason" class="form-label">Motif / Description <span class="required">*</span></label>
                    <textarea class="form-control" 
                              id="reason" 
                              name="reason" 
                              rows="3" 
                              placeholder="Décrivez le motif de ce mouvement..."
                              required>{{ old('reason', $mouvement->reason) }}</textarea>
                    <div class="help-text">Description détaillée du mouvement</div>
                </div>


                <!-- Actions -->
                <div class="form-actions">
                    <a href="{{ route('admin.stock.index') }}" class="btn btn-secondary-modern btn-modern">
                        <i class="fas fa-arrow-left me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-success-modern btn-modern">
                        <i class="fas fa-save me-2"></i>Mettre à Jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation du formulaire
    document.getElementById('stockForm').addEventListener('submit', function(e) {
        const type = document.querySelector('input[name="type"]:checked');
        if (!type) {
            e.preventDefault();
            showToast('Veuillez sélectionner un type de mouvement', 'error');
            return;
        }

        const quantity = parseFloat(document.getElementById('quantity').value);
        if (quantity <= 0) {
            e.preventDefault();
            showToast('La quantité doit être supérieure à 0', 'error');
            return;
        }
    });
});

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}
</script>
@endpush
