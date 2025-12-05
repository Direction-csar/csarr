@extends('layouts.responsable')

@section('title', 'Ajouter une entrée de stock - Responsable Entrepôt')
@section('page-title', 'Ajouter une entrée de stock')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ Ajouter une entrée de stock</h2>
        <p class="card-subtitle">Enregistrer une nouvelle entrée de stock dans l'entrepôt</p>
    </div>
    
    <form action="{{ route('responsable.stock.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
            <!-- Informations du produit -->
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                    📦 Informations du produit
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label for="product" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Produit *
                        </label>
                        <select id="product" 
                                name="product" 
                                required
                                style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                            <option value="">Sélectionner un produit</option>
                            
                            <optgroup label="Denrées alimentaires">
                                @foreach($products['denrees'] as $product)
                                    <option value="{{ $product }}" {{ request('product') == $product ? 'selected' : '' }}>
                                        {{ $product }}
                                    </option>
                                @endforeach
                            </optgroup>
                            
                            <optgroup label="Matériel humanitaire">
                                @foreach($products['materiel'] as $product)
                                    <option value="{{ $product }}" {{ request('product') == $product ? 'selected' : '' }}>
                                        {{ $product }}
                                    </option>
                                @endforeach
                            </optgroup>
                            
                            <optgroup label="Carburant">
                                @foreach($products['carburant'] as $product)
                                    <option value="{{ $product }}" {{ request('product') == $product ? 'selected' : '' }}>
                                        {{ $product }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                        @error('product')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="quantity" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Quantité *
                        </label>
                        <input type="number" 
                               id="quantity" 
                               name="quantity" 
                               min="0" 
                               step="0.01"
                               required
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                        @error('quantity')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="unit" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Unité *
                        </label>
                        <select id="unit" 
                                name="unit" 
                                required
                                style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                            <option value="">Sélectionner une unité</option>
                            <option value="kg">Kilogrammes (kg)</option>
                            <option value="g">Grammes (g)</option>
                            <option value="L">Litres (L)</option>
                            <option value="ml">Millilitres (ml)</option>
                            <option value="unités">Unités</option>
                            <option value="paires">Paires</option>
                            <option value="kits">Kits</option>
                            <option value="boîtes">Boîtes</option>
                            <option value="sachets">Sachets</option>
                        </select>
                        @error('unit')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Informations de livraison -->
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                    🚚 Informations de livraison
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label for="supplier" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Fournisseur *
                        </label>
                        <input type="text" 
                               id="supplier" 
                               name="supplier" 
                               required
                               placeholder="Nom du fournisseur"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                        @error('supplier')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="date" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Date de réception *
                        </label>
                        <input type="datetime-local" 
                               id="date" 
                               name="date" 
                               value="{{ now()->format('Y-m-d\TH:i') }}"
                               required
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                        @error('date')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="batch_number" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Numéro de lot
                        </label>
                        <input type="text" 
                               id="batch_number" 
                               name="batch_number" 
                               placeholder="Numéro de lot (optionnel)"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                    </div>
                    
                    <div>
                        <label for="expiry_date" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Date d'expiration
                        </label>
                        <input type="date" 
                               id="expiry_date" 
                               name="expiry_date" 
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notes et observations -->
        <div style="margin-bottom: 2rem;">
            <label for="notes" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                Notes et observations
            </label>
            <textarea id="notes" 
                      name="notes" 
                      rows="4"
                      placeholder="Informations supplémentaires, conditions de livraison, remarques..."
                      style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; resize: vertical;"></textarea>
            @error('notes')
                <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Actions -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn-primary">
                💾 Enregistrer l'entrée
            </button>
            
            <a href="{{ route('responsable.stock') }}" class="btn-secondary">
                ❌ Annuler
            </a>
            
            <button type="button" class="btn-secondary" onclick="resetForm()">
                🔄 Réinitialiser
            </button>
        </div>
    </form>
</div>

<!-- Informations importantes -->
<div class="card">
    <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
        ℹ️ Informations importantes
    </h3>
    
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #1e40af; margin-bottom: 0.5rem;">📋 Traçabilité</div>
            <p style="color: #1e40af; margin: 0; font-size: 0.875rem;">
                Toutes les entrées de stock sont tracées et enregistrées dans l'historique. 
                Assurez-vous de fournir des informations précises.
            </p>
        </div>
        
        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #166534; margin-bottom: 0.5rem;">✅ Validation</div>
            <p style="color: #166534; margin: 0; font-size: 0.875rem;">
                Vérifiez la qualité et la quantité des produits avant d'enregistrer l'entrée. 
                Signalez tout problème à l'administration.
            </p>
        </div>
        
        <div style="background-color: #fef3c7; border: 1px solid #fde68a; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #92400e; margin-bottom: 0.5rem;">⚠️ Dates d'expiration</div>
            <p style="color: #92400e; margin: 0; font-size: 0.875rem;">
                Pour les denrées alimentaires, renseignez obligatoirement la date d'expiration 
                pour assurer la rotation des stocks.
            </p>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('product').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('unit').value = '';
    document.getElementById('supplier').value = '';
    document.getElementById('date').value = '{{ now()->format('Y-m-d\TH:i') }}';
    document.getElementById('batch_number').value = '';
    document.getElementById('expiry_date').value = '';
    document.getElementById('notes').value = '';
}

// Auto-sélection de l'unité selon le produit
document.getElementById('product').addEventListener('change', function() {
    const product = this.value.toLowerCase();
    const unitSelect = document.getElementById('unit');
    
    // Réinitialiser la sélection
    unitSelect.value = '';
    
    // Auto-sélection basée sur le produit
    if (product.includes('riz') || product.includes('sucre') || product.includes('farine') || product.includes('haricots')) {
        unitSelect.value = 'kg';
    } else if (product.includes('lait') || product.includes('huile')) {
        unitSelect.value = 'kg';
    } else if (product.includes('essence') || product.includes('gasoil')) {
        unitSelect.value = 'L';
    } else if (product.includes('couverture') || product.includes('tente') || product.includes('moustiquaire')) {
        unitSelect.value = 'unités';
    } else if (product.includes('kit')) {
        unitSelect.value = 'kits';
    }
});
</script>
@endsection 