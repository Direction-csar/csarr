/**
 * CORRECTIFS RESPONSIVE POUR LE TABLEAU DE BORD
 * =============================================
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 Initialisation des correctifs responsive du tableau de bord');
    
    // Forcer l'affichage des éléments
    forceElementsVisibility();
    
    // Corriger la structure responsive
    fixResponsiveStructure();
    
    // Ajouter les classes nécessaires
    addRequiredClasses();
    
    // Corriger les dimensions
    fixDimensions();
    
    // Gérer le redimensionnement
    handleResize();
    
    console.log('✅ Correctifs responsive appliqués');
});

/**
 * Forcer l'affichage des éléments cachés
 */
function forceElementsVisibility() {
    const elementsToShow = [
        '.dashboard-container',
        '.stats-row',
        '.stat-card',
        '.dashboard-grid',
        '.dashboard-card'
    ];
    
    elementsToShow.forEach(selector => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(element => {
            element.style.display = '';
            element.style.visibility = 'visible';
            element.style.opacity = '1';
            element.style.transform = 'none';
            element.style.transition = 'none';
        });
    });
}

/**
 * Corriger la structure responsive
 */
function fixResponsiveStructure() {
    // Corriger la grille des statistiques
    const statsRow = document.querySelector('.stats-row');
    if (statsRow) {
        statsRow.style.display = 'grid';
        statsRow.style.gridTemplateColumns = 'repeat(auto-fit, minmax(280px, 1fr))';
        statsRow.style.gap = '16px';
        statsRow.style.width = '100%';
        statsRow.style.marginBottom = '24px';
    }
    
    // Corriger la grille principale
    const dashboardGrid = document.querySelector('.dashboard-grid');
    if (dashboardGrid) {
        dashboardGrid.style.display = 'grid';
        dashboardGrid.style.gridTemplateColumns = '1fr';
        dashboardGrid.style.gap = '20px';
        dashboardGrid.style.width = '100%';
    }
    
    // Corriger les cartes de statistiques
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.style.display = 'flex';
        card.style.alignItems = 'center';
        card.style.width = '100%';
        card.style.minHeight = '120px';
        card.style.padding = '20px';
        card.style.boxSizing = 'border-box';
    });
}

/**
 * Ajouter les classes nécessaires
 */
function addRequiredClasses() {
    // Ajouter les classes de couleur aux cartes
    const cardMappings = [
        { selector: '.stat-card:nth-child(1)', class: 'requests-card' },
        { selector: '.stat-card:nth-child(2)', class: 'warehouses-card' },
        { selector: '.stat-card:nth-child(3)', class: 'fuel-card' },
        { selector: '.stat-card:nth-child(4)', class: 'messages-card' }
    ];
    
    cardMappings.forEach(mapping => {
        const card = document.querySelector(mapping.selector);
        if (card && !card.classList.contains(mapping.class)) {
            card.classList.add(mapping.class);
        }
    });
}

/**
 * Corriger les dimensions
 */
function fixDimensions() {
    // Corriger le conteneur principal
    const container = document.querySelector('.dashboard-container');
    if (container) {
        container.style.width = '100%';
        container.style.maxWidth = '100%';
        container.style.padding = '16px';
        container.style.boxSizing = 'border-box';
    }
    
    // Corriger les graphiques
    const chartContainers = document.querySelectorAll('.chart-container');
    chartContainers.forEach(container => {
        container.style.position = 'relative';
        container.style.height = '300px';
        container.style.width = '100%';
    });
}

/**
 * Gérer le redimensionnement de la fenêtre
 */
function handleResize() {
    function applyResponsiveChanges() {
        const width = window.innerWidth;
        
        // Mobile (≤ 768px)
        if (width <= 768) {
            applyMobileStyles();
        }
        // Tablette (≤ 1200px)
        else if (width <= 1200) {
            applyTabletStyles();
        }
        // Desktop
        else {
            applyDesktopStyles();
        }
    }
    
    // Appliquer immédiatement
    applyResponsiveChanges();
    
    // Appliquer lors du redimensionnement
    window.addEventListener('resize', debounce(applyResponsiveChanges, 250));
}

/**
 * Styles pour mobile
 */
function applyMobileStyles() {
    const statsRow = document.querySelector('.stats-row');
    if (statsRow) {
        statsRow.style.gridTemplateColumns = '1fr';
        statsRow.style.gap = '12px';
    }
    
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.style.padding = '16px';
        card.style.minHeight = '100px';
    });
    
    const statIcons = document.querySelectorAll('.stat-icon');
    statIcons.forEach(icon => {
        icon.style.width = '50px';
        icon.style.height = '50px';
        icon.style.fontSize = '20px';
    });
}

/**
 * Styles pour tablette
 */
function applyTabletStyles() {
    const statsRow = document.querySelector('.stats-row');
    if (statsRow) {
        statsRow.style.gridTemplateColumns = 'repeat(2, 1fr)';
        statsRow.style.gap = '16px';
    }
}

/**
 * Styles pour desktop
 */
function applyDesktopStyles() {
    const statsRow = document.querySelector('.stats-row');
    if (statsRow) {
        statsRow.style.gridTemplateColumns = 'repeat(auto-fit, minmax(280px, 1fr))';
        statsRow.style.gap = '20px';
    }
}

/**
 * Fonction de debounce pour optimiser les performances
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Mode debug pour visualiser les éléments
 */
function enableDebugMode() {
    document.body.classList.add('debug-mode');
    console.log('🐛 Mode debug activé - bordures visibles sur les éléments');
}

/**
 * Désactiver le mode debug
 */
function disableDebugMode() {
    document.body.classList.remove('debug-mode');
    console.log('✅ Mode debug désactivé');
}

// Exposer les fonctions globalement pour le debug
window.DashboardFix = {
    enableDebugMode,
    disableDebugMode,
    forceElementsVisibility,
    fixResponsiveStructure,
    addRequiredClasses,
    fixDimensions
};

// Auto-correction si des éléments ne s'affichent pas
setTimeout(() => {
    const hiddenElements = document.querySelectorAll('.dashboard-container [style*="display: none"], .dashboard-container [style*="visibility: hidden"]');
    if (hiddenElements.length > 0) {
        console.warn('⚠️ Éléments cachés détectés, application des correctifs...');
        forceElementsVisibility();
        fixResponsiveStructure();
    }
}, 1000);
