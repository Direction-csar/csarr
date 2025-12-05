/**
 * Navigation mobile pour la plateforme CSAR
 * Gestion du menu hamburger et navigation responsive
 */

class MobileNavigation {
    constructor() {
        this.init();
    }

    init() {
        this.createMobileMenu();
        this.bindEvents();
        this.handleResize();
    }

    createMobileMenu() {
        // Créer le bouton hamburger
        const hamburger = document.createElement('div');
        hamburger.className = 'hamburger lg:hidden';
        hamburger.innerHTML = `
            <span></span>
            <span></span>
            <span></span>
        `;
        
        // Créer le menu mobile
        const mobileMenu = document.createElement('div');
        mobileMenu.className = 'mobile-menu';
        mobileMenu.innerHTML = this.getMobileMenuContent();
        
        // Créer l'overlay
        const overlay = document.createElement('div');
        overlay.className = 'mobile-menu-overlay';
        
        // Ajouter au DOM
        document.body.appendChild(hamburger);
        document.body.appendChild(mobileMenu);
        document.body.appendChild(overlay);
        
        // Stocker les références
        this.hamburger = hamburger;
        this.mobileMenu = mobileMenu;
        this.overlay = overlay;
    }

    getMobileMenuContent() {
        return `
            <div class="mobile-menu-header">
                <div class="flex items-center justify-between p-4">
                    <img src="${this.getLogoPath()}" alt="CSAR" class="h-8 w-auto">
                    <button class="mobile-menu-close text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="mobile-menu-content">
                <nav class="mobile-nav">
                    ${this.getNavigationItems()}
                </nav>
                
                <div class="mobile-menu-footer p-4">
                    <div class="border-t border-gray-700 pt-4">
                        <div class="flex items-center space-x-3 text-gray-300">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span>Utilisateur connecté</span>
                        </div>
                        <button class="mobile-logout w-full mt-4 text-left text-gray-300 hover:text-white">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Déconnexion
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    getLogoPath() {
        // Déterminer le logo selon l'interface
        const path = window.location.pathname;
        if (path.includes('/admin')) {
            return '/images/logos/LOGO CSAR vectoriel-01.png';
        } else if (path.includes('/drh')) {
            return '/images/logos/LOGO CSAR vectoriel-01.png';
        } else if (path.includes('/dg')) {
            return '/images/logos/LOGO CSAR vectoriel-01.png';
        }
        return '/images/logos/LOGO CSAR vectoriel-01.png';
    }

    getNavigationItems() {
        const path = window.location.pathname;
        
        if (path.includes('/admin')) {
            return this.getAdminNavigation();
        } else if (path.includes('/drh')) {
            return this.getDrhNavigation();
        } else if (path.includes('/dg')) {
            return this.getDgNavigation();
        } else {
            return this.getPublicNavigation();
        }
    }

    getAdminNavigation() {
        return `
            <a href="/admin/dashboard" class="mobile-nav-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="/admin/personnel" class="mobile-nav-item">
                <i class="fas fa-users"></i>
                <span>Personnel</span>
            </a>
            <a href="/admin/warehouses" class="mobile-nav-item">
                <i class="fas fa-warehouse"></i>
                <span>Entrepôts</span>
            </a>
            <a href="/admin/stocks" class="mobile-nav-item">
                <i class="fas fa-boxes"></i>
                <span>Stocks</span>
            </a>
            <a href="/admin/reports" class="mobile-nav-item">
                <i class="fas fa-chart-bar"></i>
                <span>Rapports</span>
            </a>
        `;
    }

    getDrhNavigation() {
        return `
            <a href="/drh/dashboard" class="mobile-nav-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="/drh/personnel" class="mobile-nav-item">
                <i class="fas fa-users"></i>
                <span>Gestion du Personnel</span>
            </a>
            <a href="/drh/documents" class="mobile-nav-item">
                <i class="fas fa-file-alt"></i>
                <span>Documents RH</span>
            </a>
            <a href="/drh/attendance" class="mobile-nav-item">
                <i class="fas fa-clock"></i>
                <span>Présence</span>
            </a>
            <a href="/drh/salary-slips" class="mobile-nav-item">
                <i class="fas fa-money-bill-wave"></i>
                <span>Bulletins de Paie</span>
            </a>
            <a href="/drh/statistics" class="mobile-nav-item">
                <i class="fas fa-chart-bar"></i>
                <span>Statistiques RH</span>
            </a>
        `;
    }

    getDgNavigation() {
        return `
            <a href="/dg/dashboard" class="mobile-nav-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="/dg/reports" class="mobile-nav-item">
                <i class="fas fa-chart-line"></i>
                <span>Rapports</span>
            </a>
            <a href="/dg/analytics" class="mobile-nav-item">
                <i class="fas fa-analytics"></i>
                <span>Analyses</span>
            </a>
        `;
    }

    getPublicNavigation() {
        return `
            <a href="/" class="mobile-nav-item">
                <i class="fas fa-home"></i>
                <span>Accueil</span>
            </a>
            <a href="/about" class="mobile-nav-item">
                <i class="fas fa-info-circle"></i>
                <span>À propos</span>
            </a>
            <a href="/news" class="mobile-nav-item">
                <i class="fas fa-newspaper"></i>
                <span>Actualités</span>
            </a>
            <a href="/partners" class="mobile-nav-item">
                <i class="fas fa-handshake"></i>
                <span>Partenaires</span>
            </a>
            <a href="/contact" class="mobile-nav-item">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
        `;
    }

    bindEvents() {
        // Toggle menu
        this.hamburger.addEventListener('click', () => {
            this.toggleMenu();
        });

        // Fermer menu
        this.overlay.addEventListener('click', () => {
            this.closeMenu();
        });

        // Bouton de fermeture
        const closeBtn = this.mobileMenu.querySelector('.mobile-menu-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.closeMenu();
            });
        }

        // Gestion de la déconnexion
        const logoutBtn = this.mobileMenu.querySelector('.mobile-logout');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => {
                this.handleLogout();
            });
        }

        // Fermer menu lors du redimensionnement
        window.addEventListener('resize', () => {
            this.handleResize();
        });

        // Fermer menu lors de la navigation
        const navItems = this.mobileMenu.querySelectorAll('.mobile-nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', () => {
                this.closeMenu();
            });
        });
    }

    toggleMenu() {
        this.hamburger.classList.toggle('active');
        this.mobileMenu.classList.toggle('active');
        this.overlay.classList.toggle('active');
        
        // Empêcher le scroll du body
        document.body.style.overflow = this.mobileMenu.classList.contains('active') ? 'hidden' : '';
    }

    closeMenu() {
        this.hamburger.classList.remove('active');
        this.mobileMenu.classList.remove('active');
        this.overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    handleResize() {
        // Fermer le menu mobile sur desktop
        if (window.innerWidth >= 1024) {
            this.closeMenu();
        }
    }

    handleLogout() {
        const path = window.location.pathname;
        let logoutUrl = '/logout';
        
        if (path.includes('/admin')) {
            logoutUrl = '/admin/logout';
        } else if (path.includes('/drh')) {
            logoutUrl = '/drh/logout';
        } else if (path.includes('/dg')) {
            logoutUrl = '/dg/logout';
        }
        
        // Créer et soumettre le formulaire de déconnexion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = logoutUrl;
        
        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        form.appendChild(token);
        document.body.appendChild(form);
        form.submit();
    }
}

// CSS pour le menu mobile
const mobileMenuCSS = `
<style>
.hamburger {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1002;
    background: white;
    border-radius: 8px;
    padding: 0.75rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all 0.3s ease;
}

.hamburger:hover {
    transform: scale(1.05);
}

.hamburger span {
    display: block;
    width: 25px;
    height: 3px;
    background: #059669;
    margin: 4px 0;
    transition: 0.3s;
    border-radius: 2px;
}

.hamburger.active span:nth-child(1) {
    transform: rotate(-45deg) translate(-5px, 6px);
}

.hamburger.active span:nth-child(2) {
    opacity: 0;
}

.hamburger.active span:nth-child(3) {
    transform: rotate(45deg) translate(-5px, -6px);
}

.mobile-menu {
    position: fixed;
    top: 0;
    left: -100%;
    width: 280px;
    height: 100vh;
    background: #059669;
    color: white;
    transition: left 0.3s ease;
    z-index: 1001;
    overflow-y: auto;
}

.mobile-menu.active {
    left: 0;
}

.mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.mobile-menu-overlay.active {
    opacity: 1;
    visibility: visible;
}

.mobile-nav-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.mobile-nav-item:hover {
    background: rgba(255, 255, 255, 0.1);
    padding-left: 2rem;
}

.mobile-nav-item i {
    width: 20px;
    text-align: center;
}

.mobile-menu-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.mobile-menu-close:hover {
    background: rgba(255, 255, 255, 0.1);
}

@media (min-width: 1024px) {
    .hamburger {
        display: none;
    }
    
    .mobile-menu {
        display: none;
    }
    
    .mobile-menu-overlay {
        display: none;
    }
}
</style>
`;

// Ajouter le CSS au head
document.head.insertAdjacentHTML('beforeend', mobileMenuCSS);

// Initialiser la navigation mobile
document.addEventListener('DOMContentLoaded', () => {
    new MobileNavigation();
});

// Export pour utilisation dans d'autres fichiers
window.MobileNavigation = MobileNavigation;
