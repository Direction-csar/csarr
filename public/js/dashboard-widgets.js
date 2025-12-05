/**
 * Widgets de tableau de bord interactifs pour CSAR
 * Fonctionnalités: Graphiques, métriques en temps réel, notifications
 */

class DashboardWidgets {
    constructor() {
        this.charts = {};
        this.refreshInterval = null;
        this.notificationSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBjuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBS13yO/eizEIHWq+8+OWT');
        this.init();
    }

    init() {
        this.initializeCharts();
        this.setupRealTimeUpdates();
        this.setupNotifications();
        this.setupInteractiveElements();
        console.log('🎯 Widgets de tableau de bord initialisés');
    }

    // Initialisation des graphiques Chart.js
    initializeCharts() {
        // Graphique des stocks par région
        this.createStockChart();
        
        // Graphique des demandes par mois
        this.createRequestsChart();
        
        // Graphique des performances
        this.createPerformanceChart();
        
        // Graphique des alertes
        this.createAlertsChart();
    }

    createStockChart() {
        const ctx = document.getElementById('stockChart');
        if (!ctx) return;

        this.charts.stock = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Dakar', 'Thiès', 'Kaolack', 'Saint-Louis', 'Ziguinchor'],
                datasets: [{
                    data: [45, 30, 25, 20, 15],
                    backgroundColor: [
                        '#FF6B6B',
                        '#4ECDC4',
                        '#45B7D1',
                        '#96CEB4',
                        '#FFEAA7'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
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
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' tonnes';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 2000
                }
            }
        });
    }

    createRequestsChart() {
        const ctx = document.getElementById('requestsChart');
        if (!ctx) return;

        this.charts.requests = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Demandes traitées',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Demandes en attente',
                    data: [2, 3, 20, 5, 1, 4],
                    borderColor: '#f093fb',
                    backgroundColor: 'rgba(240, 147, 251, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    createPerformanceChart() {
        const ctx = document.getElementById('performanceChart');
        if (!ctx) return;

        this.charts.performance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Efficacité', 'Rapidité', 'Qualité', 'Satisfaction'],
                datasets: [{
                    label: 'Score (%)',
                    data: [85, 92, 78, 88],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(240, 147, 251, 0.8)',
                        'rgba(255, 107, 107, 0.8)',
                        'rgba(78, 205, 196, 0.8)'
                    ],
                    borderColor: [
                        '#667eea',
                        '#f093fb',
                        '#FF6B6B',
                        '#4ECDC4'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    createAlertsChart() {
        const ctx = document.getElementById('alertsChart');
        if (!ctx) return;

        this.charts.alerts = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Stock bas', 'Délais', 'Qualité', 'Sécurité', 'Maintenance'],
                datasets: [{
                    label: 'Niveau d\'alerte',
                    data: [65, 59, 80, 81, 56],
                    backgroundColor: 'rgba(255, 107, 107, 0.2)',
                    borderColor: '#FF6B6B',
                    pointBackgroundColor: '#FF6B6B',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#FF6B6B'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Mise à jour en temps réel
    setupRealTimeUpdates() {
        this.refreshInterval = setInterval(() => {
            this.updateMetrics();
            this.updateCharts();
            this.checkAlerts();
        }, 30000); // Mise à jour toutes les 30 secondes
    }

    async updateMetrics() {
        try {
            const response = await fetch('/api/dashboard/metrics');
            if (response.ok) {
                const data = await response.json();
                this.updateMetricCards(data);
            }
        } catch (error) {
            console.log('Mise à jour des métriques:', error.message);
        }
    }

    updateMetricCards(data) {
        const metrics = {
            'total-requests': data.totalRequests || 0,
            'pending-requests': data.pendingRequests || 0,
            'completed-requests': data.completedRequests || 0,
            'total-stock': data.totalStock || 0,
            'low-stock-alerts': data.lowStockAlerts || 0,
            'active-users': data.activeUsers || 0
        };

        Object.entries(metrics).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                this.animateNumber(element, value);
            }
        });
    }

    animateNumber(element, targetValue) {
        const currentValue = parseInt(element.textContent) || 0;
        const increment = (targetValue - currentValue) / 20;
        let current = currentValue;

        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= targetValue) || 
                (increment < 0 && current <= targetValue)) {
                current = targetValue;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 50);
    }

    updateCharts() {
        // Simulation de nouvelles données
        if (this.charts.stock) {
            const newData = this.charts.stock.data.datasets[0].data.map(value => 
                value + (Math.random() - 0.5) * 5
            );
            this.charts.stock.data.datasets[0].data = newData;
            this.charts.stock.update('none');
        }
    }

    // Système de notifications
    setupNotifications() {
        this.notificationContainer = document.getElementById('notification-container');
        if (!this.notificationContainer) {
            this.createNotificationContainer();
        }
    }

    createNotificationContainer() {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(container);
        this.notificationContainer = container;
    }

    showNotification(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            background: ${this.getNotificationColor(type)};
            color: white;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            cursor: pointer;
        `;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 18px; cursor: pointer;">×</button>
            </div>
        `;

        this.notificationContainer.appendChild(notification);

        // Animation d'entrée
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Suppression automatique
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, duration);

        // Son de notification
        this.playNotificationSound();
    }

    getNotificationColor(type) {
        const colors = {
            success: '#4CAF50',
            error: '#F44336',
            warning: '#FF9800',
            info: '#2196F3'
        };
        return colors[type] || colors.info;
    }

    playNotificationSound() {
        try {
            this.notificationSound.currentTime = 0;
            this.notificationSound.play();
        } catch (error) {
            console.log('Impossible de jouer le son de notification');
        }
    }

    // Vérification des alertes
    checkAlerts() {
        // Simulation d'alertes
        const alerts = [
            { message: 'Stock faible détecté à Dakar', type: 'warning' },
            { message: 'Nouvelle demande urgente reçue', type: 'info' },
            { message: 'Maintenance programmée demain', type: 'info' }
        ];

        if (Math.random() < 0.1) { // 10% de chance d'alerte
            const alert = alerts[Math.floor(Math.random() * alerts.length)];
            this.showNotification(alert.message, alert.type);
        }
    }

    // Éléments interactifs
    setupInteractiveElements() {
        // Boutons de rafraîchissement
        document.querySelectorAll('.refresh-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                this.refreshData();
                btn.style.transform = 'rotate(360deg)';
                setTimeout(() => {
                    btn.style.transform = 'rotate(0deg)';
                }, 500);
            });
        });

        // Filtres de date
        document.querySelectorAll('.date-filter').forEach(filter => {
            filter.addEventListener('change', () => {
                this.applyDateFilter(filter.value);
            });
        });

        // Boutons d'export
        document.querySelectorAll('.export-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.exportData(btn.dataset.format);
            });
        });
    }

    async refreshData() {
        this.showNotification('Actualisation des données...', 'info', 2000);
        await this.updateMetrics();
        await this.updateCharts();
        this.showNotification('Données actualisées', 'success', 2000);
    }

    applyDateFilter(period) {
        console.log('Filtre de période appliqué:', period);
        // Logique de filtrage des données
        this.showNotification(`Filtre appliqué: ${period}`, 'info', 2000);
    }

    exportData(format) {
        this.showNotification(`Export ${format} en cours...`, 'info', 2000);
        // Logique d'export
        setTimeout(() => {
            this.showNotification(`Export ${format} terminé`, 'success', 2000);
        }, 2000);
    }

    // Méthodes publiques
    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        Object.values(this.charts).forEach(chart => {
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
        });
    }
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', function() {
    window.dashboardWidgets = new DashboardWidgets();
});

// Export pour utilisation externe
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DashboardWidgets;
}


