/**
 * CSAR Admin Dashboard - JavaScript Professionnel
 * Gestion des graphiques, carte et temps réel
 */

class AdminDashboard {
    constructor() {
        this.isRealtimeActive = false;
        this.updateInterval = null;
        this.charts = {};

        this.init();
    }

    init() {
        this.initRealtimeToggle();
        this.initCharts();
        this.initMap();
        this.startAutoRefresh();
    }

    /**
     * Gestion du bouton Temps Réel
     */
    initRealtimeToggle() {
        const realtimeBtn = document.getElementById('realtimeBtn');
        if (!realtimeBtn) return;

        realtimeBtn.addEventListener('click', () => {
            this.toggleRealtime();
        });
    }

    toggleRealtime() {
        const realtimeBtn = document.getElementById('realtimeBtn');
        this.isRealtimeActive = !this.isRealtimeActive;

        realtimeBtn.classList.toggle('active', this.isRealtimeActive);

        if (this.isRealtimeActive) {
            this.updateDashboard();
            this.updateInterval = setInterval(() => {
                this.updateDashboard();
            }, 30000); // 30 secondes
        } else {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
                this.updateInterval = null;
            }
        }
    }

    /**
     * Démarrage automatique du temps réel
     */
    startAutoRefresh() {
        // Démarrer automatiquement après 1 seconde
        setTimeout(() => {
            if (!this.isRealtimeActive) {
                this.toggleRealtime();
            }
        }, 1000);
    }

    /**
     * Initialisation des graphiques Chart.js
     */
    initCharts() {
        this.initRequestsChart();
        this.initStocksChart();
    }

    initRequestsChart() {
        const ctx = document.getElementById('requestsChart');
        if (!ctx) return;

        this.charts.requests = new Chart(ctx, {
            type: 'line',
            data: {
                labels: window.dashboardData?.requestsEvolution?.labels || ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Demandes',
                    data: window.dashboardData?.requestsEvolution?.data || [0, 0, 0, 0, 0, 0],
                    borderColor: 'var(--primary-color)',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: 'var(--primary-color)',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'var(--neutral-900)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'var(--neutral-700)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'var(--neutral-200)'
                        },
                        ticks: {
                            color: 'var(--neutral-600)'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'var(--neutral-200)'
                        },
                        ticks: {
                            color: 'var(--neutral-600)'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    initStocksChart() {
        const ctx = document.getElementById('stocksChart');
        if (!ctx) return;

        const stockData = window.dashboardData?.stockDistribution || {
            labels: ['Type 1', 'Type 2', 'Type 3'],
            data: [30, 45, 25],
            colors: ['#10b981', '#f59e0b', '#3b82f6']
        };

        this.charts.stocks = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: stockData.labels,
                datasets: [{
                    data: stockData.data,
                    backgroundColor: stockData.colors,
                    borderColor: 'white',
                    borderWidth: 3,
                    hoverBorderWidth: 4,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            color: 'var(--neutral-700)',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'var(--neutral-900)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed * 100) / total);
                                return `${context.label}: ${context.parsed} unités (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    /**
     * Initialisation de la carte Leaflet
     */
    initMap() {
        const mapContainer = document.getElementById('warehousesMap');
        if (!mapContainer) return;

        // Initialiser la carte centrée sur le Sénégal
        this.map = L.map('warehousesMap').setView([14.6937, -17.44406], 7);

        // Tiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(this.map);

        // Ajouter les entrepôts
        this.addWarehousesToMap();
    }

    addWarehousesToMap() {
        const warehouses = window.dashboardData?.warehousesMap || [];

        warehouses.forEach(warehouse => {
            const color = this.getWarehouseColor(warehouse.status);
            const icon = L.divIcon({
                html: `<div style="width: 20px; height: 20px; background: ${color}; border: 3px solid white; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>`,
                className: 'warehouse-marker',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            const marker = L.marker([warehouse.latitude, warehouse.longitude], { icon });

            marker.bindPopup(`
                <div class="map-popup">
                    <h4 style="margin: 0 0 8px 0; color: var(--primary-color);">${warehouse.name}</h4>
                    <p style="margin: 4px 0; color: var(--neutral-700);"><strong>Stock:</strong> ${warehouse.stock} unités</p>
                    <p style="margin: 4px 0; color: var(--neutral-700);"><strong>Capacité:</strong> ${warehouse.capacity}</p>
                    <p style="margin: 4px 0; color: var(--neutral-700);"><strong>Taux:</strong> ${warehouse.fill_rate}%</p>
                    <p style="margin: 4px 0; color: var(--neutral-700);">${warehouse.address}</p>
                </div>
            `);

            marker.addTo(this.map);
        });
    }

    getWarehouseColor(status) {
        switch (status) {
            case 'low': return 'var(--danger-color)';
            case 'high': return 'var(--accent-color)';
            default: return 'var(--secondary-color)';
        }
    }

    /**
     * Mise à jour du dashboard depuis l'API
     */
    async updateDashboard() {
        try {
            const response = await fetch('/admin/api/realtime');
            const data = await response.json();

            if (data.success && data.data) {
                this.updateKPIs(data.data);
                this.updateTimestamp();
            }
        } catch (error) {
            console.error('Erreur mise à jour dashboard:', error);
        }
    }

    /**
     * Mise à jour des KPIs
     */
    updateKPIs(data) {
        const kpiMappings = {
            'totalRequests': { element: 'totalRequests', format: 'number' },
            'activeWarehouses': { element: 'activeWarehouses', format: 'number' },
            'totalStock': { element: 'totalStock', format: 'formatted' },
            'totalBeneficiaries': { element: 'totalBeneficiaries', format: 'formatted' },
            'executionRate': { element: 'executionRate', format: 'percentage' },
            'activeUsers': { element: 'activeUsers', format: 'number' }
        };

        Object.entries(kpiMappings).forEach(([key, config]) => {
            const element = document.getElementById(config.element);
            if (element && data[key] !== undefined) {
                let value = data[key];

                switch (config.format) {
                    case 'formatted':
                        value = this.formatNumber(value);
                        break;
                    case 'percentage':
                        value = value + '%';
                        break;
                }

                element.textContent = value;
            }
        });

        // Mise à jour de la barre de progression
        this.updateProgressBar(data.fill_rate);
    }

    updateProgressBar(fillRate) {
        const fillRateBar = document.getElementById('fillRateBar');
        if (fillRateBar && fillRate !== undefined) {
            fillRateBar.style.width = fillRate + '%';
        }
    }

    updateTimestamp() {
        const timestampElement = document.getElementById('lastUpdate');
        if (timestampElement) {
            timestampElement.textContent = new Date().toLocaleTimeString('fr-FR');
        }
    }

    /**
     * Utilitaires
     */
    formatNumber(number) {
        return new Intl.NumberFormat('fr-FR').format(number);
    }

    refreshDashboard() {
        window.location.reload();
    }
}

/**
 * Initialisation quand le DOM est prêt
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le dashboard
    window.adminDashboard = new AdminDashboard();

    // Gestionnaire d'erreur global pour les graphiques
    window.addEventListener('error', function(e) {
        console.error('Erreur graphique:', e.error);
    });
});

/**
 * Exposer les fonctions globales nécessaires
 */
window.refreshDashboard = function() {
    window.adminDashboard.refreshDashboard();
};

// Préparer les données pour les templates
@if(isset($charts) && isset($warehousesMap))
<script>
window.dashboardData = {
    requestsEvolution: {!! json_encode($charts['requests_evolution'] ?? ['labels' => [], 'data' => []]) !!},
    stockDistribution: {!! json_encode($charts['stock_distribution'] ?? ['labels' => [], 'data' => [], 'colors' => []]) !!},
    warehousesMap: {!! json_encode($warehousesMap ?? []) !!}
};
</script>
@endif
