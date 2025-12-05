<!-- Composant Toast pour les confirmations de formulaire -->
<div id="form-toast-container" class="fixed top-4 right-4 z-50 space-y-2">
    <!-- Les toasts seront ajoutés ici dynamiquement -->
</div>

<style>
.form-toast {
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    padding: 16px 20px;
    min-width: 300px;
    max-width: 400px;
    border-left: 4px solid;
    transform: translateX(100%);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.form-toast.show {
    transform: translateX(0);
}

.form-toast.success {
    border-left-color: #10b981;
}

.form-toast.error {
    border-left-color: #ef4444;
}

.form-toast.warning {
    border-left-color: #f59e0b;
}

.form-toast.info {
    border-left-color: #3b82f6;
}

.toast-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.toast-title {
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.toast-message {
    color: #6b7280;
    font-size: 14px;
    line-height: 1.4;
}

.toast-close {
    background: none;
    border: none;
    font-size: 18px;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.toast-close:hover {
    color: #6b7280;
    background-color: #f3f4f6;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background-color: currentColor;
    opacity: 0.3;
    transition: width linear;
}

/* Animations */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.form-toast.animate-in {
    animation: slideInRight 0.3s ease-out;
}

.form-toast.animate-out {
    animation: slideOutRight 0.3s ease-in;
}
</style>

<script>
class FormToast {
    static show(message, type = 'success', duration = 5000) {
        const container = document.getElementById('form-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `form-toast ${type}`;
        
        const icon = this.getIcon(type);
        const title = this.getTitle(type);
        
        toast.innerHTML = `
            <div class="toast-header">
                <div class="toast-title">
                    <i class="${icon}"></i>
                    ${title}
                </div>
                <button class="toast-close" onclick="FormToast.remove(this.parentElement.parentElement)">×</button>
            </div>
            <div class="toast-message">${message}</div>
            <div class="toast-progress" style="width: 100%; transition-duration: ${duration}ms;"></div>
        `;

        container.appendChild(toast);
        
        // Animation d'entrée
        setTimeout(() => {
            toast.classList.add('show', 'animate-in');
        }, 100);

        // Animation de la barre de progression
        setTimeout(() => {
            const progress = toast.querySelector('.toast-progress');
            if (progress) {
                progress.style.width = '0%';
            }
        }, 100);

        // Suppression automatique
        setTimeout(() => {
            this.remove(toast);
        }, duration);
    }

    static remove(toast) {
        if (!toast) return;
        
        toast.classList.add('animate-out');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    }

    static getIcon(type) {
        const icons = {
            success: 'fas fa-check-circle text-green-500',
            error: 'fas fa-times-circle text-red-500',
            warning: 'fas fa-exclamation-triangle text-yellow-500',
            info: 'fas fa-info-circle text-blue-500'
        };
        return icons[type] || icons.info;
    }

    static getTitle(type) {
        const titles = {
            success: 'Succès',
            error: 'Erreur',
            warning: 'Attention',
            info: 'Information'
        };
        return titles[type] || 'Information';
    }

    // Méthodes de convenance
    static success(message, duration = 5000) {
        this.show(message, 'success', duration);
    }

    static error(message, duration = 7000) {
        this.show(message, 'error', duration);
    }

    static warning(message, duration = 6000) {
        this.show(message, 'warning', duration);
    }

    static info(message, duration = 5000) {
        this.show(message, 'info', duration);
    }
}

// Exposer globalement
window.FormToast = FormToast;
</script>
