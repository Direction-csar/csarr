/* ========================================
   SCRIPT ULTRA-AGRESSIF - SUPPRESSION DE TOUTES LES TRANSITIONS
   ======================================== */

console.log('🚨 SUPPRESSION DE TOUTES LES TRANSITIONS - CHARGEMENT...');

// Fonction ultra-agressive pour supprimer toutes les transitions
function killAllTransitions() {
    console.log('💀 Suppression de toutes les transitions...');
    
    // Créer un style global pour supprimer TOUTES les transitions
    const killStyle = document.createElement('style');
    killStyle.id = 'kill-transitions-style';
    killStyle.textContent = `
        /* SUPPRESSION ULTRA-AGRESSIVE DE TOUTES LES TRANSITIONS */
        *, *::before, *::after {
            animation-duration: 0s !important;
            animation-delay: 0s !important;
            animation-iteration-count: 1 !important;
            animation-fill-mode: none !important;
            transition-duration: 0s !important;
            transition-delay: 0s !important;
            transition-timing-function: linear !important;
            transform: none !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
        }
        
        /* Supprimer toutes les animations */
        @keyframes fadeIn { from { opacity: 1; } to { opacity: 1; } }
        @keyframes slideIn { from { transform: none; } to { transform: none; } }
        @keyframes fadeInUp { from { opacity: 1; transform: none; } to { opacity: 1; transform: none; } }
        @keyframes slideInUp { from { opacity: 1; transform: none; } to { opacity: 1; transform: none; } }
        @keyframes fadeInSlide { from { opacity: 1; transform: none; } to { opacity: 1; transform: none; } }
        @keyframes fadeOutSlide { from { opacity: 1; transform: none; } to { opacity: 1; transform: none; } }
        @keyframes shimmer { from { background-position: 0 0; } to { background-position: 0 0; } }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(0deg); } }
        @keyframes pulse { from { opacity: 1; } to { opacity: 1; } }
        @keyframes bounce { from { transform: none; } to { transform: none; } }
        @keyframes shake { from { transform: none; } to { transform: none; } }
        @keyframes zoomIn { from { transform: scale(1); } to { transform: scale(1); } }
        @keyframes zoomOut { from { transform: scale(1); } to { transform: scale(1); } }
        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(0deg); } }
        @keyframes scale { from { transform: scale(1); } to { transform: scale(1); } }
        @keyframes translate { from { transform: translate(0); } to { transform: translate(0); } }
        
        /* Supprimer toutes les classes d'animation */
        .fade-in, .fade-in-up, .slide-in, .slide-in-up, .fade-in-slide, .fade-out-slide,
        .shimmer, .spin, .pulse, .bounce, .shake, .zoom-in, .zoom-out, .rotate, .scale, .translate,
        .animate, .animated, .animation, .transition, .transitions,
        .dashboard-container, .page-header, .stats-row, .stat-card, .dashboard-grid, .dashboard-card,
        .hero-bg-slide, .hero-nav, .hero-indicator, .partner-card-pro, .gradient-orb,
        .admin-card, .dg-card, .admin-nav-item, .dg-nav-item,
        .admin-btn, .dg-btn, .admin-table, .dg-table,
        .admin-form-input, .dg-form-input, .admin-status, .dg-status {
            animation: none !important;
            transition: none !important;
            transform: none !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
        }
        
        /* Supprimer tous les loaders et spinners */
        .loader, .spinner, .loading, [class*="loading"], [class*="spinner"],
        .loading-spinner, .loading-overlay, .loading-screen, .loading-indicator {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        /* Forcer l'affichage immédiat */
        .dashboard-container, .dashboard-container *, .page-header, .stats-row, .stat-card,
        .dashboard-grid, .dashboard-card, .admin-content, .dg-content,
        .admin-main, .dg-main, .admin-sidebar, .dg-sidebar {
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
            animation: none !important;
            transition: none !important;
            display: block !important;
        }
        
        /* Supprimer les effets hover */
        *:hover {
            transform: none !important;
            transition: none !important;
            animation: none !important;
        }
        
        /* Supprimer les effets focus */
        *:focus {
            transform: none !important;
            transition: none !important;
            animation: none !important;
        }
        
        /* Supprimer les effets active */
        *:active {
            transform: none !important;
            transition: none !important;
            animation: none !important;
        }
    `;
    
    // Ajouter le style au head
    document.head.appendChild(killStyle);
    
    console.log('✅ Style de suppression ajouté');
}

// Fonction pour forcer l'affichage de tous les éléments
function forceDisplayAll() {
    console.log('💪 Forçage de l\'affichage de tous les éléments...');
    
    // Sélectionner tous les éléments
    const allElements = document.querySelectorAll('*');
    
    allElements.forEach(element => {
        // Forcer l'affichage
        element.style.opacity = '1';
        element.style.visibility = 'visible';
        element.style.transform = 'none';
        element.style.animation = 'none';
        element.style.transition = 'none';
        element.style.display = element.style.display || 'block';
        
        // Supprimer toutes les classes d'animation
        element.classList.remove(
            'fade-in', 'fade-in-up', 'slide-in', 'slide-in-up', 'fade-in-slide', 'fade-out-slide',
            'shimmer', 'spin', 'pulse', 'bounce', 'shake', 'zoom-in', 'zoom-out', 'rotate', 'scale', 'translate',
            'animate', 'animated', 'animation', 'transition', 'transitions'
        );
    });
    
    console.log('✅ Tous les éléments forcés à l\'affichage');
}

// Fonction pour supprimer tous les loaders
function removeAllLoaders() {
    console.log('🗑️ Suppression de tous les loaders...');
    
    const loaders = document.querySelectorAll(
        '.loader, .spinner, .loading, [class*="loading"], [class*="spinner"], ' +
        '.loading-spinner, .loading-overlay, .loading-screen, .loading-indicator'
    );
    
    loaders.forEach(loader => {
        loader.style.display = 'none';
        loader.style.opacity = '0';
        loader.style.visibility = 'hidden';
        loader.remove();
    });
    
    console.log('✅ Tous les loaders supprimés');
}

// Exécuter immédiatement
killAllTransitions();
forceDisplayAll();
removeAllLoaders();

// Exécuter plusieurs fois pour être sûr
setTimeout(() => {
    killAllTransitions();
    forceDisplayAll();
    removeAllLoaders();
}, 0);

setTimeout(() => {
    killAllTransitions();
    forceDisplayAll();
    removeAllLoaders();
}, 10);

setTimeout(() => {
    killAllTransitions();
    forceDisplayAll();
    removeAllLoaders();
}, 50);

setTimeout(() => {
    killAllTransitions();
    forceDisplayAll();
    removeAllLoaders();
}, 100);

setTimeout(() => {
    killAllTransitions();
    forceDisplayAll();
    removeAllLoaders();
}, 500);

// Exécuter au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM chargé, suppression des transitions...');
    killAllTransitions();
    forceDisplayAll();
    removeAllLoaders();
});

// Exécuter au chargement complet
window.addEventListener('load', function() {
    console.log('🔄 Page chargée, suppression finale des transitions...');
    killAllTransitions();
    forceDisplayAll();
    removeAllLoaders();
});

// Exécuter toutes les secondes pour être sûr
setInterval(function() {
    killAllTransitions();
    forceDisplayAll();
    removeAllLoaders();
}, 1000);

console.log('🎉 SCRIPT DE SUPPRESSION DES TRANSITIONS CHARGÉ !');