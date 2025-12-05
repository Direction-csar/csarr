@extends('layouts.responsable')

@section('title', 'Enregistrer une sortie de stock - Responsable Entrepôt')
@section('page-title', 'Enregistrer une sortie de stock')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">➖ Enregistrer une sortie de stock</h2>
        <p class="card-subtitle">Enregistrer une sortie de stock de l'entrepôt</p>
    </div>
    
    <form action="{{ route('responsable.stock.out.store') }}" method="POST">
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
                    
                    <div id="stock_info" style="background-color: #f8fafc; padding: 1rem; border-radius: 0.375rem; display: none;">
                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">📊 Stock disponible</div>
                        <div style="color: #6b7280; font-size: 0.875rem;">
                            <span id="available_stock">0</span> <span id="stock_unit"></span> disponibles
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informations de sortie -->
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                    🚚 Informations de sortie
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label for="destination" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Destination *
                        </label>
                        <input type="text" 
                               id="destination" 
                               name="destination" 
                               required
                               placeholder="Destination de la sortie"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                        @error('destination')
                            <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="date" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Date de sortie *
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
                        <label for="recipient" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Destinataire
                        </label>
                        <input type="text" 
                               id="recipient" 
                               name="recipient" 
                               placeholder="Nom du destinataire (optionnel)"
                               style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                    </div>
                    
                    <div>
                        <label for="reason" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                            Motif de sortie
                        </label>
                        <select id="reason" 
                                name="reason" 
                                style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                            <option value="">Sélectionner un motif</option>
                            <option value="distribution">Distribution d'urgence</option>
                            <option value="maintenance">Maintenance/Entretien</option>
                            <option value="transfert">Transfert vers autre entrepôt</option>
                            <option value="perte">Perte/Détérioration</option>
                            <option value="autre">Autre</option>
                        </select>
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
                      placeholder="Informations supplémentaires, conditions de sortie, remarques..."
                      style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; resize: vertical;"></textarea>
            @error('notes')
                <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>
        
        <!-- Actions -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn-primary">
                💾 Enregistrer la sortie
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
        <div style="background-color: #fee2e2; border: 1px solid #fecaca; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #991b1b; margin-bottom: 0.5rem;">⚠️ Vérification du stock</div>
            <p style="color: #991b1b; margin: 0; font-size: 0.875rem;">
                Vérifiez que la quantité demandée est disponible en stock avant d'enregistrer la sortie. 
                Une sortie ne peut pas dépasser le stock disponible.
            </p>
        </div>
        
        <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #1e40af; margin-bottom: 0.5rem;">📋 Traçabilité</div>
            <p style="color: #1e40af; margin: 0; font-size: 0.875rem;">
                Toutes les sorties de stock sont tracées et enregistrées dans l'historique. 
                Assurez-vous de fournir des informations précises sur la destination.
            </p>
        </div>
        
        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #166534; margin-bottom: 0.5rem;">✅ Validation</div>
            <p style="color: #166534; margin: 0; font-size: 0.875rem;">
                Confirmez la réception avec le destinataire et conservez les justificatifs 
                de sortie pour les audits.
            </p>
        </div>
    </div>
</div>

<script>
// Données simulées du stock disponible
const stockData = {
    'Riz': { quantity: 2500, unit: 'kg' },
    'Lait en poudre': { quantity: 0, unit: 'kg' },
    'Huile': { quantity: 800, unit: 'L' },
    'Sucre': { quantity: 1200, unit: 'kg' },
    'Farine': { quantity: 1500, unit: 'kg' },
    'Haricots': { quantity: 600, unit: 'kg' },
    'Couvertures': { quantity: 150, unit: 'unités' },
    'Tentes': { quantity: 25, unit: 'unités' },
    'Kits hygiène': { quantity: 80, unit: 'kits' },
    'Moustiquaires': { quantity: 200, unit: 'unités' },
    'Seaux': { quantity: 50, unit: 'unités' },
    'Essence': { quantity: 500, unit: 'L' },
    'Gasoil': { quantity: 800, unit: 'L' },
    'Huile moteur': { quantity: 100, unit: 'L' }
};

function resetForm() {
    document.getElementById('product').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('unit').value = '';
    document.getElementById('destination').value = '';
    document.getElementById('date').value = '{{ now()->format('Y-m-d\TH:i') }}';
    document.getElementById('recipient').value = '';
    document.getElementById('reason').value = '';
    document.getElementById('notes').value = '';
    document.getElementById('stock_info').style.display = 'none';
}

// Afficher les informations de stock lors de la sélection d'un produit
document.getElementById('product').addEventListener('change', function() {
    const product = this.value;
    const stockInfo = document.getElementById('stock_info');
    const availableStock = document.getElementById('available_stock');
    const stockUnit = document.getElementById('stock_unit');
    
    if (product && stockData[product]) {
        availableStock.textContent = stockData[product].quantity.toLocaleString();
        stockUnit.textContent = stockData[product].unit;
        stockInfo.style.display = 'block';
        
        // Mettre à jour l'unité automatiquement
        document.getElementById('unit').value = stockData[product].unit;
    } else {
        stockInfo.style.display = 'none';
    }
});

// Validation de la quantité
document.getElementById('quantity').addEventListener('input', function() {
    const product = document.getElementById('product').value;
    const quantity = parseFloat(this.value);
    
    if (product && stockData[product] && quantity > stockData[product].quantity) {
        this.style.borderColor = '#dc2626';
        this.style.backgroundColor = '#fef2f2';
        
        // Afficher un avertissement
        if (!document.getElementById('quantity-warning')) {
            const warning = document.createElement('div');
            warning.id = 'quantity-warning';
            warning.style.color = '#dc2626';
            warning.style.fontSize = '0.875rem';
            warning.style.marginTop = '0.25rem';
            warning.textContent = `⚠️ Quantité supérieure au stock disponible (${stockData[product].quantity.toLocaleString()} ${stockData[product].unit})`;
            this.parentNode.appendChild(warning);
        }
    } else {
        this.style.borderColor = '#d1d5db';
        this.style.backgroundColor = 'white';
        
        const warning = document.getElementById('quantity-warning');
        if (warning) {
            warning.remove();
        }
    }
});

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