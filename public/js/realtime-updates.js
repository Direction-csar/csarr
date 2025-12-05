/**
 * Système de mises à jour en temps réel pour CSAR
 * Utilise WebSockets et polling pour les mises à jour automatiques
 */

class RealtimeUpdates {
    constructor() {
        this.websocket = null;
        this.pollingInterval = null;
        this.connectionStatus = 'disconnected';
        this.retryCount = 0;
        this.maxRetries = 5;
        this.init();
    }

    init() {
        this.setupWebSocket();
        this.setupPolling();
        this.setupEventListeners();
        this.setupConnectionIndicator();
        console.log('🔄 Système de mises à jour en temps réel initialisé');
    }

    // Configuration WebSocket
    setupWebSocket() {
        try {
            const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
            const wsUrl = `${protocol}//${window.location.host}/ws`;
            
            this.websocket = new WebSocket(wsUrl);
            
            this.websocket.onopen = () => {
                console.log('🔌 WebSocket connecté');
                this.connectionStatus = 'connected';
                this.retryCount = 0;
                this.updateConnectionIndicator();
                this.sendHeartbeat();
            };

            this.websocket.onmessage = (event) => {
                this.handleMessage(JSON.parse(event.data));
            };

            this.websocket.onclose = () => {
                console.log('🔌 WebSocket déconnecté');
                this.connectionStatus = 'disconnected';
                this.updateConnectionIndicator();
                this.scheduleReconnect();
            };

            this.websocket.onerror = (error) => {
                console.error('❌ Erreur WebSocket:', error);
                this.connectionStatus = 'error';
                this.updateConnectionIndicator();
            };

        } catch (error) {
            console.log('⚠️ WebSocket non disponible, utilisation du polling');
            this.connectionStatus = 'polling';
            this.updateConnectionIndicator();
        }
    }

    // Configuration du polling de secours
    setupPolling() {
        this.pollingInterval = setInterval(() => {
            if (this.connectionStatus !== 'connected') {
                this.pollForUpdates();
            }
        }, 10000); // Polling toutes les 10 secondes
    }

    async pollForUpdates() {
        try {
            const response = await fetch('/api/realtime/updates', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    timestamp: Date.now(),
                    lastUpdate: this.getLastUpdateTime()
                })
            });

            if (response.ok) {
                const data = await response.json();
                if (data.updates && data.updates.length > 0) {
                    this.processUpdates(data.updates);
                }
            }
        } catch (error) {
            console.log('Erreur polling:', error.message);
        }
    }

    // Gestion des messages WebSocket
    handleMessage(data) {
        switch (data.type) {
            case 'notification':
                this.showRealtimeNotification(data);
                break;
            case 'metric_update':
                this.updateMetric(data);
                break;
            case 'chart_update':
                this.updateChart(data);
                break;
            case 'alert':
                this.handleAlert(data);
                break;
            case 'heartbeat':
                this.handleHeartbeat();
                break;
            default:
                console.log('Message non reconnu:', data);
        }
    }

    // Affichage des notifications en temps réel
    showRealtimeNotification(data) {
        if (window.dashboardWidgets) {
            window.dashboardWidgets.showNotification(
                data.message, 
                data.level || 'info', 
                data.duration || 5000
            );
        }
    }

    // Mise à jour des métriques
    updateMetric(data) {
        const element = document.getElementById(data.metricId);
        if (element) {
            this.animateValue(element, data.value, data.animation || 'smooth');
        }
    }

    // Mise à jour des graphiques
    updateChart(data) {
        if (window.dashboardWidgets && window.dashboardWidgets.charts[data.chartId]) {
            const chart = window.dashboardWidgets.charts[data.chartId];
            chart.data = data.chartData;
            chart.update(data.animation || 'none');
        }
    }

    // Gestion des alertes
    handleAlert(data) {
        this.showAlertModal(data);
        this.playAlertSound(data.priority);
    }

    showAlertModal(alert) {
        const modal = document.createElement('div');
        modal.className = 'alert-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        `;

        modal.innerHTML = `
            <div style="
                background: white;
                padding: 30px;
                border-radius: 15px;
                max-width: 500px;
                width: 90%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                animation: slideIn 0.3s ease;
            ">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <div style="
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        background: ${this.getAlertColor(alert.priority)};
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 15px;
                        color: white;
                        font-size: 24px;
                    ">
                        ${this.getAlertIcon(alert.priority)}
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #2c3e50;">${alert.title}</h3>
                        <p style="margin: 5px 0 0; color: #7f8c8d; font-size: 14px;">
                            ${new Date().toLocaleString()}
                        </p>
                    </div>
                </div>
                <p style="margin: 0 0 20px; line-height: 1.6; color: #2c3e50;">
                    ${alert.message}
                </p>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button onclick="this.closest('.alert-modal').remove()" style="
                        padding: 10px 20px;
                        border: 2px solid #ddd;
                        background: white;
                        color: #666;
                        border-radius: 8px;
                        cursor: pointer;
                    ">Fermer</button>
                    ${alert.actionUrl ? `
                        <button onclick="window.open('${alert.actionUrl}', '_blank')" style="
                            padding: 10px 20px;
                            border: none;
                            background: ${this.getAlertColor(alert.priority)};
                            color: white;
                            border-radius: 8px;
                            cursor: pointer;
                        ">Voir détails</button>
                    ` : ''}
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Suppression automatique après 30 secondes
        setTimeout(() => {
            if (modal.parentNode) {
                modal.remove();
            }
        }, 30000);
    }

    getAlertColor(priority) {
        const colors = {
            low: '#4CAF50',
            medium: '#FF9800',
            high: '#F44336',
            critical: '#9C27B0'
        };
        return colors[priority] || colors.medium;
    }

    getAlertIcon(priority) {
        const icons = {
            low: 'ℹ️',
            medium: '⚠️',
            high: '🚨',
            critical: '🔥'
        };
        return icons[priority] || icons.medium;
    }

    playAlertSound(priority) {
        const sounds = {
            low: 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBjuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBS13yO/eizEIHWq+8+OWT',
            medium: 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBjuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBS13yO/eizEIHWq+8+OWT',
            high: 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBjuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBS13yO/eizEIHWq+8+OWT',
            critical: 'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBjuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBS13yO/eizEIHWq+8+OWT'
        };
        
        try {
            const audio = new Audio(sounds[priority] || sounds.medium);
            audio.volume = 0.3;
            audio.play();
        } catch (error) {
            console.log('Impossible de jouer le son d\'alerte');
        }
    }

    // Animation des valeurs
    animateValue(element, targetValue, animation = 'smooth') {
        const currentValue = parseFloat(element.textContent) || 0;
        const duration = animation === 'smooth' ? 1000 : 200;
        const steps = 30;
        const stepValue = (targetValue - currentValue) / steps;
        const stepDuration = duration / steps;
        let current = currentValue;

        const timer = setInterval(() => {
            current += stepValue;
            if ((stepValue > 0 && current >= targetValue) || 
                (stepValue < 0 && current <= targetValue)) {
                current = targetValue;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, stepDuration);
    }

    // Indicateur de connexion
    setupConnectionIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'connection-indicator';
        indicator.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 15px;
            border-radius: 25px;
            color: white;
            font-size: 12px;
            font-weight: 500;
            z-index: 9999;
            transition: all 0.3s ease;
            cursor: pointer;
        `;
        document.body.appendChild(indicator);
        this.updateConnectionIndicator();
    }

    updateConnectionIndicator() {
        const indicator = document.getElementById('connection-indicator');
        if (!indicator) return;

        const statusConfig = {
            connected: { text: '🟢 Connecté', color: '#4CAF50' },
            disconnected: { text: '🔴 Déconnecté', color: '#F44336' },
            error: { text: '⚠️ Erreur', color: '#FF9800' },
            polling: { text: '🔄 Polling', color: '#2196F3' }
        };

        const config = statusConfig[this.connectionStatus] || statusConfig.disconnected;
        indicator.textContent = config.text;
        indicator.style.backgroundColor = config.color;
    }

    // Reconnexion automatique
    scheduleReconnect() {
        if (this.retryCount < this.maxRetries) {
            const delay = Math.pow(2, this.retryCount) * 1000; // Backoff exponentiel
            setTimeout(() => {
                this.retryCount++;
                this.setupWebSocket();
            }, delay);
        }
    }

    // Heartbeat
    sendHeartbeat() {
        if (this.websocket && this.websocket.readyState === WebSocket.OPEN) {
            this.websocket.send(JSON.stringify({
                type: 'heartbeat',
                timestamp: Date.now()
            }));
        }
    }

    handleHeartbeat() {
        // Réponse au heartbeat
        console.log('💓 Heartbeat reçu');
    }

    // Gestion des événements
    setupEventListeners() {
        // Reconnexion manuelle
        document.getElementById('connection-indicator')?.addEventListener('click', () => {
            this.retryCount = 0;
            this.setupWebSocket();
        });

        // Gestion de la visibilité de la page
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pauseUpdates();
            } else {
                this.resumeUpdates();
            }
        });
    }

    pauseUpdates() {
        console.log('⏸️ Mises à jour en pause');
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
    }

    resumeUpdates() {
        console.log('▶️ Mises à jour reprises');
        this.setupPolling();
        if (this.connectionStatus !== 'connected') {
            this.setupWebSocket();
        }
    }

    // Traitement des mises à jour
    processUpdates(updates) {
        updates.forEach(update => {
            this.handleMessage(update);
        });
        this.setLastUpdateTime(Date.now());
    }

    getLastUpdateTime() {
        return localStorage.getItem('lastUpdateTime') || 0;
    }

    setLastUpdateTime(timestamp) {
        localStorage.setItem('lastUpdateTime', timestamp);
    }

    // Méthodes publiques
    sendMessage(type, data) {
        if (this.websocket && this.websocket.readyState === WebSocket.OPEN) {
            this.websocket.send(JSON.stringify({ type, data }));
        }
    }

    destroy() {
        if (this.websocket) {
            this.websocket.close();
        }
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
    }
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', function() {
    window.realtimeUpdates = new RealtimeUpdates();
});

// Export pour utilisation externe
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RealtimeUpdates;
}


