/* ========================================
   SCRIPT D'URGENCE CARTE ADMIN
   ======================================== */

console.log('🚨 SCRIPT D\'URGENCE CARTE - CHARGEMENT...');

// Fonction d'urgence pour forcer l'affichage de la carte
function emergencyMapFix() {
    console.log('🔧 Début du fix d\'urgence...');
    
    const mapElement = document.getElementById('map');
    if (!mapElement) {
        console.error('❌ Élément map non trouvé !');
        return;
    }
    
    console.log('✅ Élément map trouvé');
    
    // Vérifier si Leaflet est chargé
    if (typeof L === 'undefined') {
        console.error('❌ Leaflet non chargé !');
        mapElement.innerHTML = `
            <div style="
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
                color: #10b981;
                text-align: center;
                background: #1e293b;
                border-radius: 12px;
                border: 2px solid #10b981;
            ">
                <div>
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px; color: #f59e0b;"></i>
                    <p style="margin: 0; font-size: 16px; font-weight: 600;">Leaflet non chargé</p>
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #94a3b8;">Vérifiez votre connexion internet</p>
                </div>
            </div>
        `;
        return;
    }
    
    console.log('✅ Leaflet chargé');
    
    // Créer la carte d'urgence
    try {
        console.log('🗺️ Création de la carte d\'urgence...');
        
        // Nettoyer l'élément map
        mapElement.innerHTML = '';
        
        // Créer la carte
        const map = L.map('map', {
            center: [14.4974, -14.4524],
            zoom: 7,
            zoomControl: true,
            attributionControl: true
        });
        
        console.log('✅ Carte créée');
        
        // Ajouter les tuiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        console.log('✅ Tuiles ajoutées');
        
        // Ajouter un marqueur de test
        const testMarker = L.marker([14.4974, -14.4524]).addTo(map);
        testMarker.bindPopup(`
            <div style="text-align: center; font-family: Inter, sans-serif;">
                <h4 style="margin: 0 0 8px 0; color: #1f2937; font-size: 16px;">
                    <i class="fas fa-warehouse" style="color: #10b981; margin-right: 8px;"></i>
                    Test Entrepôt CSAR
                </h4>
                <p style="margin: 0 0 6px 0; color: #6b7280; font-size: 13px;">
                    <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                    Dakar, Sénégal
                </p>
                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 13px;">
                    <i class="fas fa-boxes" style="color: #f59e0b; margin-right: 6px;"></i>
                    Capacité: 1000 tonnes
                </p>
                <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                    <span style="
                        display: inline-block;
                        padding: 4px 10px;
                        border-radius: 6px;
                        font-size: 11px;
                        font-weight: 600;
                        background: #d1fae5;
                        color: #065f46;
                    ">
                        <i class="fas fa-circle" style="font-size: 6px; margin-right: 4px;"></i>
                        ACTIF
                    </span>
                </div>
            </div>
        `);
        
        console.log('✅ Marqueur de test ajouté');
        
        // Ouvrir automatiquement le popup
        testMarker.openPopup();
        
        console.log('🎉 CARTE D\'URGENCE CRÉÉE AVEC SUCCÈS !');
        
    } catch (error) {
        console.error('❌ Erreur lors de la création de la carte:', error);
        mapElement.innerHTML = `
            <div style="
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100%;
                color: #ef4444;
                text-align: center;
                background: #1e293b;
                border-radius: 12px;
                border: 2px solid #ef4444;
            ">
                <div>
                    <i class="fas fa-exclamation-circle" style="font-size: 24px; margin-bottom: 10px;"></i>
                    <p style="margin: 0; font-size: 16px; font-weight: 600;">Erreur de carte</p>
                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #94a3b8;">${error.message}</p>
                </div>
            </div>
        `;
    }
}

// Exécuter le fix d'urgence
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM chargé, lancement du fix d\'urgence...');
    
    // Attendre un peu que tout soit chargé
    setTimeout(function() {
        emergencyMapFix();
    }, 1000);
});

// Aussi au chargement complet de la page
window.addEventListener('load', function() {
    console.log('🔄 Page complètement chargée, relance du fix...');
    
    setTimeout(function() {
        emergencyMapFix();
    }, 2000);
});

// Fix d'urgence toutes les 5 secondes si la carte n'est pas visible
setInterval(function() {
    const mapElement = document.getElementById('map');
    if (mapElement && !mapElement.querySelector('.leaflet-container')) {
        console.log('🚨 Carte non visible, relance du fix d\'urgence...');
        emergencyMapFix();
    }
}, 5000);







