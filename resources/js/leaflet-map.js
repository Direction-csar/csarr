// Système de carte Leaflet pour le dashboard admin
class LeafletMapSystem {
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.map = null;
        this.markers = [];
        this.warehouses = [];
        this.options = {
            center: [14.6928, -17.4467], // Dakar par défaut
            zoom: 10,
            ...options
        };
        this.init();
    }

    async init() {
        await this.loadLeafletAssets();
        this.createMap();
        await this.loadWarehouses();
        this.setupEventListeners();
    }

    async loadLeafletAssets() {
        // Charger CSS Leaflet
        if (!document.querySelector('link[href*="leaflet.css"]')) {
            const css = document.createElement('link');
            css.rel = 'stylesheet';
            css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(css);
        }

        // Charger JavaScript Leaflet
        if (!window.L) {
            await new Promise((resolve) => {
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.onload = resolve;
                document.head.appendChild(script);
            });
        }
    }

    createMap() {
        const container = document.getElementById(this.containerId);
        if (!container) {
            console.error(`Container ${this.containerId} not found`);
            return;
        }

        this.map = L.map(this.containerId).setView(this.options.center, this.options.zoom);

        // Ajouter les tuiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(this.map);

        // Ajouter un marqueur pour Dakar
        L.marker(this.options.center)
            .addTo(this.map)
            .bindPopup('<b>Dakar, Sénégal</b><br>Siège du CSAR')
            .openPopup();
    }

    async loadWarehouses() {
        try {
            const response = await fetch('/api/warehouses');
            const warehouses = await response.json();
            
            this.warehouses = warehouses;
            this.updateMarkers();
        } catch (error) {
            console.error('Erreur lors du chargement des entrepôts:', error);
            this.showError('Erreur lors du chargement des entrepôts');
        }
    }

    updateMarkers() {
        // Supprimer les anciens marqueurs
        this.markers.forEach(marker => {
            this.map.removeLayer(marker);
        });
        this.markers = [];

        // Ajouter les nouveaux marqueurs
        this.warehouses.forEach(warehouse => {
            if (warehouse.latitude && warehouse.longitude) {
                const marker = this.createWarehouseMarker(warehouse);
                this.markers.push(marker);
                marker.addTo(this.map);
            }
        });

        // Ajuster la vue pour inclure tous les marqueurs
        if (this.markers.length > 0) {
            const group = new L.featureGroup(this.markers);
            this.map.fitBounds(group.getBounds().pad(0.1));
        }
    }

    createWarehouseMarker(warehouse) {
        // Choisir l'icône selon le statut
        const iconColor = this.getWarehouseIconColor(warehouse.status);
        
        const icon = L.divIcon({
            className: 'warehouse-marker',
            html: `
                <div class="warehouse-marker-content" style="background-color: ${iconColor}">
                    <i class="fas fa-warehouse"></i>
                </div>
            `,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -15]
        });

        const marker = L.marker([warehouse.latitude, warehouse.longitude], { icon })
            .bindPopup(this.createWarehousePopup(warehouse));

        return marker;
    }

    getWarehouseIconColor(status) {
        const colors = {
            'active': '#10b981',
            'inactive': '#6b7280',
            'maintenance': '#f59e0b',
            'full': '#ef4444'
        };
        return colors[status] || '#6b7280';
    }

    createWarehousePopup(warehouse) {
        const stockLevel = this.getStockLevel(warehouse);
        const stockColor = this.getStockLevelColor(stockLevel);
        
        return `
            <div class="warehouse-popup">
                <h3 class="warehouse-name">${warehouse.nom}</h3>
                <div class="warehouse-info">
                    <p><strong>Adresse:</strong> ${warehouse.adresse}</p>
                    <p><strong>Responsable:</strong> ${warehouse.responsable_nom || 'Non assigné'}</p>
                    <p><strong>Capacité:</strong> ${warehouse.capacite || 'N/A'}</p>
                    <p><strong>Statut:</strong> 
                        <span class="status-badge status-${warehouse.status}">${this.getStatusText(warehouse.status)}</span>
                    </p>
                    <p><strong>Niveau de stock:</strong> 
                        <span class="stock-level" style="color: ${stockColor}">${stockLevel}%</span>
                    </p>
                </div>
                <div class="warehouse-actions">
                    <button class="btn btn-sm btn-primary" onclick="warehouseMap.viewWarehouse(${warehouse.id})">
                        <i class="fas fa-eye"></i> Voir détails
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="warehouseMap.editWarehouse(${warehouse.id})">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                </div>
            </div>
        `;
    }

    getStockLevel(warehouse) {
        if (!warehouse.stock_total || !warehouse.capacite) return 0;
        return Math.round((warehouse.stock_total / warehouse.capacite) * 100);
    }

    getStockLevelColor(level) {
        if (level >= 90) return '#ef4444'; // Rouge
        if (level >= 70) return '#f59e0b'; // Orange
        if (level >= 30) return '#10b981'; // Vert
        return '#6b7280'; // Gris
    }

    getStatusText(status) {
        const statusTexts = {
            'active': 'Actif',
            'inactive': 'Inactif',
            'maintenance': 'Maintenance',
            'full': 'Plein'
        };
        return statusTexts[status] || 'Inconnu';
    }

    setupEventListeners() {
        // Écouter les mises à jour en temps réel
        if (window.Echo) {
            window.Echo.channel('warehouses')
                .listen('.warehouse.updated', (e) => {
                    this.handleWarehouseUpdate(e.warehouse);
                })
                .listen('.warehouse.created', (e) => {
                    this.handleWarehouseCreated(e.warehouse);
                })
                .listen('.warehouse.deleted', (e) => {
                    this.handleWarehouseDeleted(e.warehouseId);
                });
        }

        // Rafraîchir la carte toutes les 30 secondes
        setInterval(() => {
            this.loadWarehouses();
        }, 30000);
    }

    handleWarehouseUpdate(warehouse) {
        const index = this.warehouses.findIndex(w => w.id === warehouse.id);
        if (index !== -1) {
            this.warehouses[index] = warehouse;
            this.updateMarkers();
        }
    }

    handleWarehouseCreated(warehouse) {
        this.warehouses.push(warehouse);
        this.updateMarkers();
    }

    handleWarehouseDeleted(warehouseId) {
        this.warehouses = this.warehouses.filter(w => w.id !== warehouseId);
        this.updateMarkers();
    }

    // Méthodes publiques
    viewWarehouse(id) {
        window.location.href = `/admin/warehouses/${id}`;
    }

    editWarehouse(id) {
        window.location.href = `/admin/warehouses/${id}/edit`;
    }

    addWarehouse(warehouse) {
        this.warehouses.push(warehouse);
        this.updateMarkers();
    }

    removeWarehouse(id) {
        this.warehouses = this.warehouses.filter(w => w.id !== id);
        this.updateMarkers();
    }

    showError(message) {
        // Afficher une notification d'erreur
        if (window.FormToast) {
            FormToast.error(message);
        } else {
            alert(message);
        }
    }

    // Méthodes utilitaires
    getMapBounds() {
        return this.map.getBounds();
    }

    setView(center, zoom) {
        this.map.setView(center, zoom);
    }

    fitBounds(bounds) {
        this.map.fitBounds(bounds);
    }
}

// Styles CSS pour les marqueurs
const mapStyles = `
<style>
.warehouse-marker-content {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.warehouse-popup {
    min-width: 250px;
}

.warehouse-name {
    margin: 0 0 10px 0;
    color: #1f2937;
    font-size: 16px;
    font-weight: 600;
}

.warehouse-info p {
    margin: 5px 0;
    font-size: 14px;
    color: #6b7280;
}

.status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-active {
    background-color: #dcfce7;
    color: #166534;
}

.status-inactive {
    background-color: #f3f4f6;
    color: #374151;
}

.status-maintenance {
    background-color: #fef3c7;
    color: #92400e;
}

.status-full {
    background-color: #fee2e2;
    color: #991b1b;
}

.warehouse-actions {
    margin-top: 10px;
    display: flex;
    gap: 8px;
}

.warehouse-actions .btn {
    padding: 4px 8px;
    font-size: 12px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
}

.btn:hover {
    opacity: 0.8;
}
</style>
`;

// Ajouter les styles au document
document.head.insertAdjacentHTML('beforeend', mapStyles);

// Exposer globalement
window.LeafletMapSystem = LeafletMapSystem;
