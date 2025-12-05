/**
 * CORRECTION ANIMATION COMPTEURS - PAGE D'ACCUEIL
 * ===============================================
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔢 Initialisation des animations de compteurs');
    
    // Configuration
    const config = {
        animationDuration: 2000,
        delayBetweenCounters: 200,
        threshold: 0.2,
        easing: 'easeOutQuart'
    };
    
    // Fonction d'animation améliorée
    function animateCounter(element, start, end, duration) {
        const startTime = performance.now();
        const range = end - start;
        
        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Fonction d'easing pour une animation plus fluide
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const current = Math.floor(start + (range * easeOutQuart));
            
            element.textContent = current.toLocaleString('fr-FR');
            element.classList.add('counting');
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = end.toLocaleString('fr-FR');
                element.classList.remove('counting');
                element.classList.add('completed');
                
                // Effet de completion
                element.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 200);
            }
        }
        
        requestAnimationFrame(updateCounter);
    }
    
    // Détecter si on est sur mobile
    function isMobile() {
        return window.innerWidth <= 768 || /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    // Fonction pour démarrer les animations
    function startCounterAnimations() {
        const counters = document.querySelectorAll('.counter[data-target]');
        
        if (counters.length === 0) {
            console.warn('⚠️ Aucun compteur trouvé avec data-target');
            return;
        }
        
        console.log(`🎯 ${counters.length} compteurs trouvés`);
        
        counters.forEach((counter, index) => {
            const target = parseInt(counter.getAttribute('data-target'));
            
            if (isNaN(target)) {
                console.warn('⚠️ Valeur data-target invalide:', counter.getAttribute('data-target'));
                return;
            }
            
            // Délai entre chaque compteur
            setTimeout(() => {
                console.log(`🚀 Animation compteur ${index + 1}: ${target}`);
                animateCounter(counter, 0, target, config.animationDuration);
            }, index * config.delayBetweenCounters);
        });
    }
    
    // Intersection Observer amélioré
    function setupIntersectionObserver() {
        const options = {
            threshold: config.threshold,
            rootMargin: '50px'
        };
        
        let hasAnimated = false;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasAnimated) {
                    hasAnimated = true;
                    console.log('✅ Section des statistiques visible, démarrage des animations');
                    startCounterAnimations();
                }
            });
        }, options);
        
        // Observer la section des statistiques
        const statsSection = document.querySelector('.stats-section-ultra');
        if (statsSection) {
            observer.observe(statsSection);
            console.log('👀 Observer configuré pour .stats-section-ultra');
        } else {
            // Fallback: chercher d'autres sélecteurs possibles
            const alternativeSelectors = [
                '.stats-section',
                '[class*="stats"]',
                '.counter[data-target]'
            ];
            
            for (const selector of alternativeSelectors) {
                const element = document.querySelector(selector);
                if (element) {
                    observer.observe(element.closest('section') || element);
                    console.log(`👀 Observer configuré pour ${selector}`);
                    break;
                }
            }
        }
        
        return observer;
    }
    
    // Fallback pour mobile ou si l'observer ne fonctionne pas
    function setupFallbackAnimation() {
        setTimeout(() => {
            const counters = document.querySelectorAll('.counter[data-target]');
            const hasStarted = Array.from(counters).some(counter => 
                counter.classList.contains('counting') || counter.classList.contains('completed')
            );
            
            if (!hasStarted && counters.length > 0) {
                console.log('🔄 Démarrage animation fallback');
                startCounterAnimations();
            }
        }, 2000);
    }
    
    // Animation au scroll pour mobile
    function setupScrollAnimation() {
        let hasAnimated = false;
        
        function checkScroll() {
            if (hasAnimated) return;
            
            const counters = document.querySelectorAll('.counter[data-target]');
            if (counters.length === 0) return;
            
            const firstCounter = counters[0];
            const rect = firstCounter.getBoundingClientRect();
            const isVisible = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (isVisible) {
                hasAnimated = true;
                console.log('📱 Animation déclenchée par scroll mobile');
                startCounterAnimations();
                window.removeEventListener('scroll', checkScroll);
            }
        }
        
        if (isMobile()) {
            window.addEventListener('scroll', checkScroll, { passive: true });
            // Vérifier immédiatement au cas où les éléments sont déjà visibles
            setTimeout(checkScroll, 500);
        }
    }
    
    // Forcer l'animation si nécessaire
    function forceAnimation() {
        const counters = document.querySelectorAll('.counter[data-target]');
        if (counters.length > 0) {
            console.log('🔧 Animation forcée');
            startCounterAnimations();
        }
    }
    
    // Initialisation
    function init() {
        // Attendre que tout soit chargé
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
            return;
        }
        
        console.log('🎬 Initialisation des compteurs');
        
        // Configuration principale
        setupIntersectionObserver();
        
        // Fallbacks
        setupScrollAnimation();
        setupFallbackAnimation();
        
        // Animation immédiate si les éléments sont déjà visibles
        setTimeout(() => {
            const counters = document.querySelectorAll('.counter[data-target]');
            if (counters.length > 0) {
                const firstCounter = counters[0];
                const rect = firstCounter.getBoundingClientRect();
                
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    console.log('👁️ Éléments déjà visibles, animation immédiate');
                    startCounterAnimations();
                }
            }
        }, 1000);
    }
    
    // Exposer les fonctions pour le debug
    window.CounterAnimation = {
        start: startCounterAnimations,
        force: forceAnimation,
        config: config
    };
    
    // Démarrer
    init();
    
    console.log('✅ Script d\'animation des compteurs chargé');
});
