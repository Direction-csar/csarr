// Système de notifications temps réel
class NotificationSystem {
    constructor() {
        this.echo = null;
        this.unreadCount = 0;
        this.notifications = [];
        this.init();
    }

    init() {
        // Initialiser Echo si disponible
        if (window.Echo) {
            this.echo = window.Echo;
            this.setupListeners();
        }

        // Charger les notifications initiales
        this.loadNotifications();
        
        // Mettre à jour le compteur toutes les 30 secondes
        setInterval(() => {
            this.updateUnreadCount();
        }, 30000);
    }

    setupListeners() {
        const userId = document.querySelector('meta[name="user-id"]')?.content;
        
        if (userId && this.echo) {
            // Écouter les notifications privées
            this.echo.private(`notifications.${userId}`)
                .listen('.notification.sent', (e) => {
                    this.handleNewNotification(e.notification);
                    this.updateUnreadCount();
                    this.showToast(e.notification);
                });
        }
    }

    async loadNotifications() {
        try {
            const response = await fetch('/notifications');
            const data = await response.json();
            
            this.notifications = data.notifications;
            this.unreadCount = data.unread_count;
            this.updateUI();
        } catch (error) {
            console.error('Erreur lors du chargement des notifications:', error);
        }
    }

    async updateUnreadCount() {
        try {
            const response = await fetch('/notifications/unread-count');
            const data = await response.json();
            
            this.unreadCount = data.count;
            this.updateUI();
        } catch (error) {
            console.error('Erreur lors de la mise à jour du compteur:', error);
        }
    }

    updateUI() {
        // Mettre à jour le compteur dans le header
        const counter = document.getElementById('notification-counter');
        if (counter) {
            counter.textContent = this.unreadCount;
            counter.style.display = this.unreadCount > 0 ? 'block' : 'none';
        }

        // Mettre à jour la liste des notifications
        this.updateNotificationList();
    }

    updateNotificationList() {
        const container = document.getElementById('notification-list');
        if (!container) return;

        if (this.notifications.length === 0) {
            container.innerHTML = '<div class="text-center text-gray-500 py-4">Aucune notification</div>';
            return;
        }

        container.innerHTML = this.notifications.map(notification => `
            <div class="notification-item ${!notification.is_read ? 'unread' : ''}" 
                 data-id="${notification.id}">
                <div class="notification-content">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">${this.formatTime(notification.created_at)}</div>
                </div>
                <div class="notification-actions">
                    <button class="mark-read-btn" onclick="notificationSystem.markAsRead(${notification.id})">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    async markAsRead(id) {
        try {
            const response = await fetch(`/notifications/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                // Mettre à jour localement
                const notification = this.notifications.find(n => n.id === id);
                if (notification) {
                    notification.is_read = true;
                    notification.read_at = new Date().toISOString();
                }
                
                this.updateUI();
            }
        } catch (error) {
            console.error('Erreur lors du marquage comme lu:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                this.notifications.forEach(notification => {
                    notification.is_read = true;
                    notification.read_at = new Date().toISOString();
                });
                
                this.updateUI();
            }
        } catch (error) {
            console.error('Erreur lors du marquage de toutes les notifications:', error);
        }
    }

    handleNewNotification(notification) {
        // Ajouter la nouvelle notification en haut de la liste
        this.notifications.unshift(notification);
        
        // Limiter à 10 notifications
        if (this.notifications.length > 10) {
            this.notifications = this.notifications.slice(0, 10);
        }
    }

    showToast(notification) {
        // Créer un toast de notification
        const toast = document.createElement('div');
        toast.className = 'notification-toast';
        toast.innerHTML = `
            <div class="toast-content">
                <div class="toast-title">${notification.title}</div>
                <div class="toast-message">${notification.message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;

        // Ajouter au DOM
        document.body.appendChild(toast);

        // Animation d'entrée
        setTimeout(() => toast.classList.add('show'), 100);

        // Suppression automatique après 5 secondes
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    formatTime(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;

        if (diff < 60000) { // Moins d'une minute
            return 'À l\'instant';
        } else if (diff < 3600000) { // Moins d'une heure
            return `Il y a ${Math.floor(diff / 60000)} min`;
        } else if (diff < 86400000) { // Moins d'un jour
            return `Il y a ${Math.floor(diff / 3600000)} h`;
        } else {
            return date.toLocaleDateString('fr-FR');
        }
    }
}

// Initialiser le système de notifications
const notificationSystem = new NotificationSystem();

// Exposer globalement pour les boutons
window.notificationSystem = notificationSystem;
