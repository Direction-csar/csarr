/**
 * Analytics avancés avec métriques détaillées pour CSAR
 * Tableaux de bord interactifs, rapports, et visualisations
 */

class AdvancedAnalytics {
    constructor() {
        this.metrics = {};
        this.charts = {};
        this.reports = {};
        this.filters = {};
        this.init();
    }

    init() {
        this.setupAnalyticsDashboard();
        this.loadInitialData();
        this.setupEventTracking();
        this.setupExportFunctions();
        console.log('📊 Analytics avancés initialisés');
    }

    // Configuration du tableau de bord analytics
    setupAnalyticsDashboard() {
        this.createMetricsOverview();
        this.createPerformanceCharts();
        this.createTrendAnalysis();
        this.createUserBehaviorMetrics();
        this.createSystemHealthMetrics();
    }

    // Vue d'ensemble des métriques
    createMetricsOverview() {
        const container = document.getElementById('analytics-overview');
        if (!container) return;

        container.innerHTML = `
            <div class="analytics-grid">
                <div class="metric-card">
                    <div class="metric-icon">📈</div>
                    <div class="metric-content">
                        <h3>Performance Générale</h3>
                        <div class="metric-value" id="overall-performance">0%</div>
                        <div class="metric-change positive">+12% vs mois dernier</div>
                    </div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-icon">👥</div>
                    <div class="metric-content">
                        <h3>Utilisateurs Actifs</h3>
                        <div class="metric-value" id="active-users">0</div>
                        <div class="metric-change positive">+8% vs semaine dernière</div>
                    </div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-icon">⚡</div>
                    <div class="metric-content">
                        <h3>Temps de Réponse</h3>
                        <div class="metric-value" id="response-time">0ms</div>
                        <div class="metric-change negative">+15ms vs hier</div>
                    </div>
                </div>
                
                <div class="metric-card">
                    <div class="metric-icon">🎯</div>
                    <div class="metric-content">
                        <h3>Taux de Réussite</h3>
                        <div class="metric-value" id="success-rate">0%</div>
                        <div class="metric-change positive">+3% vs hier</div>
                    </div>
                </div>
            </div>
        `;
    }

    // Graphiques de performance
    createPerformanceCharts() {
        this.createResponseTimeChart();
        this.createThroughputChart();
        this.createErrorRateChart();
        this.createResourceUsageChart();
    }

    createResponseTimeChart() {
        const ctx = document.getElementById('response-time-chart');
        if (!ctx) return;

        this.charts.responseTime = new Chart(ctx, {
            type: 'line',
            data: {
                labels: this.generateTimeLabels(24),
                datasets: [{
                    label: 'Temps de réponse (ms)',
                    data: this.generateResponseTimeData(24),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
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
                        title: {
                            display: true,
                            text: 'Temps (ms)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Heure'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Temps: ${context.parsed.y}ms`;
                            }
                        }
                    }
                }
            }
        });
    }

    createThroughputChart() {
        const ctx = document.getElementById('throughput-chart');
        if (!ctx) return;

        this.charts.throughput = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: this.generateTimeLabels(7),
                datasets: [{
                    label: 'Requêtes/heure',
                    data: this.generateThroughputData(7),
                    backgroundColor: 'rgba(78, 205, 196, 0.8)',
                    borderColor: '#4ECDC4',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Requêtes'
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

    createErrorRateChart() {
        const ctx = document.getElementById('error-rate-chart');
        if (!ctx) return;

        this.charts.errorRate = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Succès', 'Erreurs 4xx', 'Erreurs 5xx', 'Timeouts'],
                datasets: [{
                    data: [85, 10, 3, 2],
                    backgroundColor: [
                        '#4CAF50',
                        '#FF9800',
                        '#F44336',
                        '#9C27B0'
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
                        position: 'bottom'
                    }
                }
            }
        });
    }

    createResourceUsageChart() {
        const ctx = document.getElementById('resource-usage-chart');
        if (!ctx) return;

        this.charts.resourceUsage = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['CPU', 'Mémoire', 'Disque', 'Réseau', 'Base de données'],
                datasets: [{
                    label: 'Utilisation (%)',
                    data: [65, 78, 45, 32, 88],
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
                        max: 100
                    }
                }
            }
        });
    }

    // Analyse des tendances
    createTrendAnalysis() {
        this.createTrendChart();
        this.createSeasonalityAnalysis();
        this.createForecasting();
    }

    createTrendChart() {
        const ctx = document.getElementById('trend-chart');
        if (!ctx) return;

        const data = this.generateTrendData(30);
        
        this.charts.trend = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Demandes',
                    data: data.values,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Tendance',
                    data: data.trend,
                    borderColor: '#f093fb',
                    backgroundColor: 'rgba(240, 147, 251, 0.1)',
                    tension: 0.4,
                    borderDash: [5, 5]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // Métriques de comportement utilisateur
    createUserBehaviorMetrics() {
        this.createUserFlowChart();
        this.createSessionAnalysis();
        this.createFeatureUsageChart();
    }

    createUserFlowChart() {
        const ctx = document.getElementById('user-flow-chart');
        if (!ctx) return;

        // Diagramme de flux utilisateur (Sankey)
        this.charts.userFlow = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Accueil', 'Connexion', 'Dashboard', 'Demandes', 'Rapports', 'Déconnexion'],
                datasets: [{
                    label: 'Utilisateurs',
                    data: [1000, 850, 720, 650, 400, 200],
                    backgroundColor: [
                        '#667eea',
                        '#f093fb',
                        '#4ECDC4',
                        '#45B7D1',
                        '#96CEB4',
                        '#FFEAA7'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Métriques de santé système
    createSystemHealthMetrics() {
        this.createHealthScore();
        this.createUptimeChart();
        this.createPerformanceScore();
    }

    createHealthScore() {
        const container = document.getElementById('health-score');
        if (!container) return;

        const score = this.calculateHealthScore();
        
        container.innerHTML = `
            <div class="health-score-container">
                <div class="health-score-circle">
                    <div class="score-value">${score}</div>
                    <div class="score-label">Score de Santé</div>
                </div>
                <div class="health-details">
                    <div class="health-item">
                        <span class="health-label">Uptime</span>
                        <span class="health-value">99.9%</span>
                    </div>
                    <div class="health-item">
                        <span class="health-label">Performance</span>
                        <span class="health-value">Excellent</span>
                    </div>
                    <div class="health-item">
                        <span class="health-label">Erreurs</span>
                        <span class="health-value">Faible</span>
                    </div>
                </div>
            </div>
        `;
    }

    // Chargement des données initiales
    async loadInitialData() {
        try {
            const response = await fetch('/api/analytics/data');
            if (response.ok) {
                const data = await response.json();
                this.updateMetrics(data);
            } else {
                this.loadMockData();
            }
        } catch (error) {
            console.log('Chargement des données analytics:', error.message);
            this.loadMockData();
        }
    }

    loadMockData() {
        this.metrics = {
            overallPerformance: 87,
            activeUsers: 1247,
            responseTime: 245,
            successRate: 94.2,
            errorRate: 2.1,
            uptime: 99.9
        };
        
        this.updateMetricsDisplay();
    }

    updateMetrics(data) {
        this.metrics = { ...this.metrics, ...data };
        this.updateMetricsDisplay();
    }

    updateMetricsDisplay() {
        Object.entries(this.metrics).forEach(([key, value]) => {
            const element = document.getElementById(key.replace(/([A-Z])/g, '-$1').toLowerCase());
            if (element) {
                this.animateValue(element, value);
            }
        });
    }

    // Configuration du suivi des événements
    setupEventTracking() {
        this.trackPageViews();
        this.trackUserInteractions();
        this.trackPerformanceMetrics();
    }

    trackPageViews() {
        // Suivi des vues de page
        this.trackEvent('page_view', {
            page: window.location.pathname,
            timestamp: Date.now(),
            user_agent: navigator.userAgent
        });
    }

    trackUserInteractions() {
        // Suivi des clics
        document.addEventListener('click', (event) => {
            this.trackEvent('click', {
                element: event.target.tagName,
                id: event.target.id,
                class: event.target.className,
                timestamp: Date.now()
            });
        });

        // Suivi des formulaires
        document.addEventListener('submit', (event) => {
            this.trackEvent('form_submit', {
                form_id: event.target.id,
                timestamp: Date.now()
            });
        });
    }

    trackPerformanceMetrics() {
        // Métriques de performance
        window.addEventListener('load', () => {
            const perfData = performance.getEntriesByType('navigation')[0];
            this.trackEvent('performance', {
                load_time: perfData.loadEventEnd - perfData.loadEventStart,
                dom_ready: perfData.domContentLoadedEventEnd - perfData.domContentLoadedEventStart,
                timestamp: Date.now()
            });
        });
    }

    trackEvent(eventType, data) {
        // Envoi des événements au serveur
        fetch('/api/analytics/track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                event_type: eventType,
                data: data,
                timestamp: Date.now()
            })
        }).catch(error => {
            console.log('Erreur tracking:', error.message);
        });
    }

    // Fonctions d'export
    setupExportFunctions() {
        this.setupExportButtons();
        this.setupReportGeneration();
    }

    setupExportButtons() {
        document.querySelectorAll('.export-analytics').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.exportData(btn.dataset.format);
            });
        });
    }

    async exportData(format) {
        const data = await this.generateReportData();
        
        switch (format) {
            case 'pdf':
                this.exportToPDF(data);
                break;
            case 'excel':
                this.exportToExcel(data);
                break;
            case 'csv':
                this.exportToCSV(data);
                break;
            case 'json':
                this.exportToJSON(data);
                break;
        }
    }

    exportToPDF(data) {
        // Génération PDF (nécessite une bibliothèque comme jsPDF)
        console.log('Export PDF:', data);
        this.showNotification('Export PDF en cours...', 'info');
    }

    exportToExcel(data) {
        // Génération Excel (nécessite une bibliothèque comme SheetJS)
        console.log('Export Excel:', data);
        this.showNotification('Export Excel en cours...', 'info');
    }

    exportToCSV(data) {
        const csv = this.convertToCSV(data);
        this.downloadFile(csv, 'analytics.csv', 'text/csv');
    }

    exportToJSON(data) {
        const json = JSON.stringify(data, null, 2);
        this.downloadFile(json, 'analytics.json', 'application/json');
    }

    downloadFile(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
    }

    // Génération de rapports
    setupReportGeneration() {
        this.createReportTemplates();
        this.setupScheduledReports();
    }

    createReportTemplates() {
        this.reports = {
            daily: this.generateDailyReport,
            weekly: this.generateWeeklyReport,
            monthly: this.generateMonthlyReport,
            custom: this.generateCustomReport
        };
    }

    async generateReportData() {
        return {
            metrics: this.metrics,
            charts: this.charts,
            timestamp: new Date().toISOString(),
            period: this.getCurrentPeriod()
        };
    }

    // Fonctions utilitaires
    generateTimeLabels(hours) {
        const labels = [];
        const now = new Date();
        for (let i = hours - 1; i >= 0; i--) {
            const time = new Date(now.getTime() - i * 60 * 60 * 1000);
            labels.push(time.getHours() + ':00');
        }
        return labels;
    }

    generateResponseTimeData(hours) {
        const data = [];
        for (let i = 0; i < hours; i++) {
            data.push(Math.floor(Math.random() * 200) + 100);
        }
        return data;
    }

    generateThroughputData(days) {
        const data = [];
        for (let i = 0; i < days; i++) {
            data.push(Math.floor(Math.random() * 1000) + 500);
        }
        return data;
    }

    generateTrendData(days) {
        const labels = [];
        const values = [];
        const trend = [];
        
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString());
            
            const value = Math.floor(Math.random() * 100) + 50;
            values.push(value);
            trend.push(value + (days - i) * 2); // Tendance croissante
        }
        
        return { labels, values, trend };
    }

    calculateHealthScore() {
        const factors = {
            uptime: this.metrics.uptime || 99.9,
            performance: this.metrics.overallPerformance || 87,
            errorRate: this.metrics.errorRate || 2.1
        };
        
        const score = (factors.uptime * 0.4) + (factors.performance * 0.4) + ((100 - factors.errorRate) * 0.2);
        return Math.round(score);
    }

    animateValue(element, targetValue) {
        const currentValue = parseFloat(element.textContent) || 0;
        const increment = (targetValue - currentValue) / 30;
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

    getCurrentPeriod() {
        const now = new Date();
        return {
            start: new Date(now.getFullYear(), now.getMonth(), 1),
            end: now
        };
    }

    showNotification(message, type = 'info') {
        if (window.dashboardWidgets) {
            window.dashboardWidgets.showNotification(message, type);
        }
    }

    // Méthodes publiques
    refreshData() {
        this.loadInitialData();
        this.updateMetricsDisplay();
    }

    destroy() {
        Object.values(this.charts).forEach(chart => {
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
        });
    }
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', function() {
    window.advancedAnalytics = new AdvancedAnalytics();
});

// Export pour utilisation externe
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AdvancedAnalytics;
}


