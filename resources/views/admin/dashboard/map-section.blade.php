<!-- Section Carte des Entrepôts -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Carte des Entrepôts</h3>
                <p class="text-sm text-gray-600 mt-1">Visualisation en temps réel des entrepôts CSAR</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span>Actif</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                        <span>Maintenance</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span>Plein</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                        <span>Inactif</span>
                    </div>
                </div>
                <button id="refresh-map" class="btn btn-sm btn-outline">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Actualiser
                </button>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-full">
                        <i class="fas fa-warehouse text-green-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">Entrepôts Actifs</p>
                        <p class="text-2xl font-bold text-green-900" id="active-warehouses">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-full">
                        <i class="fas fa-boxes text-blue-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">Stock Total</p>
                        <p class="text-2xl font-bold text-blue-900" id="total-stock">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-full">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">Alertes</p>
                        <p class="text-2xl font-bold text-yellow-900" id="warehouse-alerts">0</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-full">
                        <i class="fas fa-percentage text-purple-600"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-purple-800">Taux d'Occupation</p>
                        <p class="text-2xl font-bold text-purple-900" id="occupation-rate">0%</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conteneur de la carte -->
        <div id="warehouse-map" class="w-full h-96 rounded-lg border border-gray-200 bg-gray-100">
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <i class="fas fa-map-marked-alt text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600">Chargement de la carte...</p>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button id="add-warehouse-btn" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un entrepôt
                </button>
                <button id="export-warehouses-btn" class="btn btn-outline">
                    <i class="fas fa-download mr-2"></i>
                    Exporter
                </button>
            </div>
            
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <i class="fas fa-info-circle"></i>
                <span>Dernière mise à jour: <span id="last-update">--</span></span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser la carte
    const warehouseMap = new LeafletMapSystem('warehouse-map', {
        center: [14.6928, -17.4467], // Dakar
        zoom: 10
    });
    
    // Exposer globalement pour les callbacks
    window.warehouseMap = warehouseMap;
    
    // Bouton d'actualisation
    document.getElementById('refresh-map').addEventListener('click', function() {
        warehouseMap.loadWarehouses();
        updateMapStats();
    });
    
    // Bouton d'ajout d'entrepôt
    document.getElementById('add-warehouse-btn').addEventListener('click', function() {
        window.location.href = '/admin/warehouses/create';
    });
    
    // Bouton d'export
    document.getElementById('export-warehouses-btn').addEventListener('click', function() {
        exportWarehouses();
    });
    
    // Mettre à jour les statistiques
    function updateMapStats() {
        fetch('/api/warehouses/stats')
            .then(response => response.json())
            .then(stats => {
                document.getElementById('active-warehouses').textContent = stats.active_warehouses || 0;
                document.getElementById('total-stock').textContent = stats.total_stock || 0;
                document.getElementById('warehouse-alerts').textContent = stats.alerts || 0;
                document.getElementById('occupation-rate').textContent = (stats.occupation_rate || 0) + '%';
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString('fr-FR');
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour des statistiques:', error);
            });
    }
    
    // Fonction d'export
    function exportWarehouses() {
        window.location.href = '/admin/warehouses/export';
    }
    
    // Mettre à jour les statistiques au chargement
    updateMapStats();
    
    // Mettre à jour les statistiques toutes les 30 secondes
    setInterval(updateMapStats, 30000);
});
</script>
