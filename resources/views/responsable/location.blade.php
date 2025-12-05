@extends('layouts.responsable')

@section('title', 'Localisation - Responsable Entrepôt')
@section('page-title', 'Localisation de l\'entrepôt')

@section('content')
<!-- Informations actuelles -->
<div class="card">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">
        📍 Localisation de l'entrepôt
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
        <!-- Coordonnées actuelles -->
        <div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                Coordonnées actuelles
            </h3>
            
            <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem;">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <div style="font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Latitude</div>
                        <div style="color: #6b7280; font-family: monospace;">{{ $location['latitude'] }}</div>
                    </div>
                    
                    <div>
                        <div style="font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Longitude</div>
                        <div style="color: #6b7280; font-family: monospace;">{{ $location['longitude'] }}</div>
                    </div>
                    
                    <div>
                        <div style="font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Adresse</div>
                        <div style="color: #6b7280;">{{ $location['address'] }}</div>
                    </div>
                    
                    <div>
                        <div style="font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Dernière mise à jour</div>
                        <div style="color: #6b7280;">{{ \Carbon\Carbon::parse($location['last_updated'])->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Carte -->
        <div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                Carte de localisation
            </h3>
            
            <div style="background-color: #f8fafc; padding: 2rem; border-radius: 0.5rem; text-align: center; min-height: 300px; display: flex; align-items: center; justify-content: center;">
                <div>
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🗺️</div>
                    <div style="color: #6b7280; font-size: 0.875rem;">
                        Carte interactive à implémenter<br>
                        Coordonnées: {{ $location['latitude'] }}, {{ $location['longitude'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mise à jour de la localisation -->
<div class="card">
    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">
        🔄 Mettre à jour la localisation
    </h3>
    
    <form action="{{ route('responsable.location.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label for="latitude" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                    Latitude *
                </label>
                <input type="number" 
                       id="latitude" 
                       name="latitude" 
                       value="{{ $location['latitude'] }}"
                       step="0.000001"
                       min="-90" 
                       max="90"
                       required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                @error('latitude')
                    <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>
            
            <div>
                <label for="longitude" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                    Longitude *
                </label>
                <input type="number" 
                       id="longitude" 
                       name="longitude" 
                       value="{{ $location['longitude'] }}"
                       step="0.000001"
                       min="-180" 
                       max="180"
                       required
                       style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white;">
                @error('longitude')
                    <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label for="address" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.5rem;">
                Adresse complète *
            </label>
            <textarea id="address" 
                      name="address" 
                      rows="3"
                      required
                      style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: white; resize: vertical;">{{ $location['address'] }}</textarea>
            @error('address')
                <div style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
            @enderror
        </div>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="submit" class="btn-primary">
                💾 Sauvegarder les coordonnées
            </button>
            
            <button type="button" class="btn-secondary" onclick="getCurrentLocation()">
                📍 Utiliser ma position actuelle
            </button>
            
            <button type="button" class="btn-secondary" onclick="resetLocation()">
                🔄 Réinitialiser
            </button>
        </div>
    </form>
</div>

<!-- Instructions -->
<div class="card">
    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">
        ℹ️ Instructions
    </h3>
    
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #1e40af; margin-bottom: 0.5rem;">📍 Position actuelle</div>
            <p style="color: #1e40af; margin: 0; font-size: 0.875rem;">
                Cliquez sur "Utiliser ma position actuelle" pour récupérer automatiquement vos coordonnées GPS.
            </p>
        </div>
        
        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #166534; margin-bottom: 0.5rem;">🎯 Précision</div>
            <p style="color: #166534; margin: 0; font-size: 0.875rem;">
                Pour une meilleure précision, utilisez au moins 6 décimales pour les coordonnées GPS.
            </p>
        </div>
        
        <div style="background-color: #fef3c7; border: 1px solid #fde68a; padding: 1rem; border-radius: 0.375rem;">
            <div style="font-weight: 600; color: #92400e; margin-bottom: 0.5rem;">⚠️ Important</div>
            <p style="color: #92400e; margin: 0; font-size: 0.875rem;">
                La localisation de l'entrepôt est utilisée pour les interventions d'urgence et la planification logistique.
                Assurez-vous que les coordonnées sont exactes.
            </p>
        </div>
    </div>
</div>

<script>
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                
                // Optionnel : récupérer l'adresse via une API de géocodage inverse
                // fetch(`https://api.example.com/reverse-geocode?lat=${position.coords.latitude}&lng=${position.coords.longitude}`)
                //     .then(response => response.json())
                //     .then(data => {
                //         document.getElementById('address').value = data.address;
                //     });
            },
            function(error) {
                alert('Erreur lors de la récupération de la position : ' + error.message);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
    }
}

function resetLocation() {
    document.getElementById('latitude').value = '{{ $location['latitude'] }}';
    document.getElementById('longitude').value = '{{ $location['longitude'] }}';
    document.getElementById('address').value = '{{ $location['address'] }}';
}
</script>
@endsection 