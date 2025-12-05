/* ========================================
   CSAR PLATFORM - RESPONSIVE MOBILE JAVASCRIPT
   Gestion des interactions mobiles et burger menu
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {
    
    // === BURGER MENU === //
    const burgerMenu = document.getElementById('burgerMenu');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (burgerMenu && sidebar) {
        // Toggle sidebar
        burgerMenu.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
            burgerMenu.classList.toggle('active');
            
            // Empêcher le scroll du body quand la sidebar est ouverte
            if (sidebar.classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
        
        // Fermer sidebar en cliquant sur l'overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            burgerMenu.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Fermer sidebar avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('active');
                burgerMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
    
    // === RESPONSIVE TABLES === //
    function handleResponsiveTables() {
        const tables = document.querySelectorAll('.data-table');
        const isMobile = window.innerWidth < 768;
        
        tables.forEach(table => {
            const tableElement = table.querySelector('table');
            const tableCardMode = table.querySelector('.table-card-mode');
            
            if (isMobile && tableElement && !tableCardMode) {
                // Convertir le tableau en mode carte pour mobile
                convertTableToCards(table, tableElement);
            } else if (!isMobile && tableCardMode) {
                // Restaurer le tableau normal pour desktop
                restoreTableFromCards(table, tableCardMode);
            }
        });
    }
    
    function convertTableToCards(container, table) {
        const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent.trim());
        const rows = Array.from(table.querySelectorAll('tbody tr'));
        
        const cardContainer = document.createElement('div');
        cardContainer.className = 'table-card-mode';
        
        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            const card = document.createElement('div');
            card.className = 'table-card fade-in-up';
            
            // Header de la carte
            const cardHeader = document.createElement('div');
            cardHeader.className = 'table-card-header';
            cardHeader.textContent = cells[0]?.textContent || 'Élément';
            card.appendChild(cardHeader);
            
            // Contenu de la carte
            headers.forEach((header, index) => {
                if (index > 0 && cells[index]) { // Skip première colonne (utilisée pour le header)
                    const cardRow = document.createElement('div');
                    cardRow.className = 'table-card-row';
                    
                    const label = document.createElement('div');
                    label.className = 'table-card-label';
                    label.textContent = header;
                    
                    const value = document.createElement('div');
                    value.className = 'table-card-value';
                    value.innerHTML = cells[index].innerHTML;
                    
                    cardRow.appendChild(label);
                    cardRow.appendChild(value);
                    card.appendChild(cardRow);
                }
            });
            
            cardContainer.appendChild(card);
        });
        
        table.style.display = 'none';
        container.appendChild(cardContainer);
    }
    
    function restoreTableFromCards(container, cardMode) {
        const table = container.querySelector('table');
        if (table) {
            table.style.display = '';
            cardMode.remove();
        }
    }
    
    // === DETECTION RESPONSIVE === //
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            handleResponsiveTables();
            handleSidebarResize();
        }, 250);
    });
    
    function handleSidebarResize() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const burgerMenu = document.getElementById('burgerMenu');
        
        if (window.innerWidth >= 768) {
            // Desktop/Tablet - sidebar toujours visible
            if (sidebar) {
                sidebar.classList.remove('open');
                document.body.style.overflow = '';
            }
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            if (burgerMenu) {
                burgerMenu.classList.remove('active');
            }
        }
    }
    
    // === TOUCH GESTURES === //
    let touchStartX = 0;
    let touchStartY = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
    }, { passive: true });
    
    document.addEventListener('touchend', function(e) {
        if (!touchStartX || !touchStartY) return;
        
        const touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        
        const diffX = touchStartX - touchEndX;
        const diffY = touchStartY - touchEndY;
        
        // Swipe horizontal plus important que vertical
        if (Math.abs(diffX) > Math.abs(diffY)) {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const burgerMenu = document.getElementById('burgerMenu');
            
            // Swipe vers la droite (ouvrir sidebar)
            if (diffX < -50 && touchStartX < 50 && window.innerWidth < 768) {
                if (sidebar && !sidebar.classList.contains('open')) {
                    sidebar.classList.add('open');
                    sidebarOverlay.classList.add('active');
                    burgerMenu.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }
            // Swipe vers la gauche (fermer sidebar)
            else if (diffX > 50 && sidebar && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                sidebarOverlay.classList.remove('active');
                burgerMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
        
        touchStartX = 0;
        touchStartY = 0;
    }, { passive: true });
    
    // === SCROLL BEHAVIOR === //
    let lastScrollTop = 0;
    const mobileNavbar = document.querySelector('.mobile-navbar');
    
    window.addEventListener('scroll', function() {
        if (window.innerWidth < 768 && mobileNavbar) {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scroll vers le bas - cacher la navbar
                mobileNavbar.style.transform = 'translateY(-100%)';
            } else {
                // Scroll vers le haut - montrer la navbar
                mobileNavbar.style.transform = 'translateY(0)';
            }
            
            lastScrollTop = scrollTop;
        }
    }, { passive: true });
    
    // === ANIMATIONS D'ENTRÉE === //
    function animateOnScroll() {
        const elements = document.querySelectorAll('.fade-in-up, .slide-in-right');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });
        
        elements.forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    }
    
    // === INITIALISATION === //
    handleResponsiveTables();
    animateOnScroll();
    
    // === PERFORMANCE === //
    // Optimisation pour les appareils mobiles
    if ('ontouchstart' in window) {
        document.body.classList.add('touch-device');
        
        // Réduire les animations sur les appareils lents
        const isSlowDevice = navigator.hardwareConcurrency < 4 || 
                            navigator.deviceMemory < 4;
        
        if (isSlowDevice) {
            document.body.classList.add('reduced-animations');
        }
    }
    
    // === LAZY LOADING === //
    if ('IntersectionObserver' in window) {
        const lazyElements = document.querySelectorAll('[data-lazy]');
        
        const lazyObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const src = element.getAttribute('data-lazy');
                    
                    if (element.tagName === 'IMG') {
                        element.src = src;
                    } else {
                        element.style.backgroundImage = `url(${src})`;
                    }
                    
                    element.removeAttribute('data-lazy');
                    lazyObserver.unobserve(element);
                }
            });
        });
        
        lazyElements.forEach(el => lazyObserver.observe(el));
    }
    
    // === PWA SUPPORT === //
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('SW registered: ', registration);
                })
                .catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
        });
    }
});

// === UTILITAIRES === //
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    toast.style.cssText = `
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        color: white;
        font-weight: 600;
        z-index: 3000;
        animation: toastSlideUp 0.3s ease-out;
    `;
    
    const colors = {
        success: '#22c55e',
        warning: '#f59e0b',
        error: '#ef4444',
        info: '#3b82f6'
    };
    
    toast.style.background = colors[type] || colors.info;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'toastSlideDown 0.3s ease-in forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// === CSS ANIMATIONS === //
const style = document.createElement('style');
style.textContent = `
    @keyframes toastSlideUp {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(100%);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }
    
    @keyframes toastSlideDown {
        from {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        to {
            opacity: 0;
            transform: translateX(-50%) translateY(100%);
        }
    }
    
    .touch-device .hover-effect:hover {
        transform: none !important;
    }
    
    .reduced-animations * {
        animation-duration: 0.1s !important;
        transition-duration: 0.1s !important;
    }
`;

document.head.appendChild(style);

