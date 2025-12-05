/**
 * Performance Optimizer for CSAR Platform
 * Implements lazy loading, image optimization, and performance monitoring
 */

class PerformanceOptimizer {
    constructor() {
        this.observers = new Map();
        this.imageCache = new Map();
        this.loadedImages = new Set();
        this.init();
    }
    
    init() {
        this.setupLazyLoading();
        this.optimizeImages();
        this.setupServiceWorker();
        this.monitorPerformance();
        this.optimizeAnimations();
    }
    
    // Lazy Loading Implementation
    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        this.loadImage(img);
                        imageObserver.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            // Observe all images with data-src
            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
            
            this.observers.set('images', imageObserver);
        } else {
            // Fallback for browsers without IntersectionObserver
            document.querySelectorAll('img[data-src]').forEach(img => {
                this.loadImage(img);
            });
        }
    }
    
    loadImage(img) {
        if (this.loadedImages.has(img.src)) return;
        
        const src = img.dataset.src;
        if (!src) return;
        
        const loadingImg = new Image();
        loadingImg.onload = () => {
            img.src = src;
            img.classList.add('loaded');
            this.loadedImages.add(src);
        };
        loadingImg.onerror = () => {
            img.classList.add('error');
        };
        loadingImg.src = src;
        
        // Remove data-src to prevent reloading
        img.removeAttribute('data-src');
    }
    
    // Image Optimization
    optimizeImages() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            // Add loading="lazy" for native lazy loading support
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }
            
            // Add decoding="async" for better performance
            if (!img.hasAttribute('decoding')) {
                img.setAttribute('decoding', 'async');
            }
            
            // Optimize image sizes based on viewport
            this.optimizeImageSize(img);
        });
    }
    
    optimizeImageSize(img) {
        const viewportWidth = window.innerWidth;
        let optimizedSrc = img.src;
        
        // Mobile optimization
        if (viewportWidth < 768 && img.dataset.mobileSrc) {
            optimizedSrc = img.dataset.mobileSrc;
        }
        // Tablet optimization
        else if (viewportWidth < 1024 && img.dataset.tabletSrc) {
            optimizedSrc = img.dataset.tabletSrc;
        }
        
        if (optimizedSrc !== img.src) {
            img.src = optimizedSrc;
        }
    }
    
    // Service Worker Setup
    setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('SW registered: ', registration);
                    })
                    .catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    }
    
    // Performance Monitoring
    monitorPerformance() {
        // Monitor Core Web Vitals
        if ('PerformanceObserver' in window) {
            // Largest Contentful Paint (LCP)
            const lcpObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                this.reportMetric('LCP', lastEntry.startTime);
            });
            lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
            
            // First Input Delay (FID)
            const fidObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    this.reportMetric('FID', entry.processingStart - entry.startTime);
                });
            });
            fidObserver.observe({ entryTypes: ['first-input'] });
            
            // Cumulative Layout Shift (CLS)
            let clsValue = 0;
            const clsObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                });
                this.reportMetric('CLS', clsValue);
            });
            clsObserver.observe({ entryTypes: ['layout-shift'] });
        }
        
        // Monitor page load time
        window.addEventListener('load', () => {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            this.reportMetric('PageLoad', loadTime);
        });
    }
    
    reportMetric(name, value) {
        // Send to analytics or logging service
        console.log(`Performance Metric - ${name}: ${value}ms`);
        
        // Alert if performance is poor
        if (name === 'PageLoad' && value > 3000) {
            console.warn('Slow page load detected:', value + 'ms');
        }
        
        if (name === 'LCP' && value > 2500) {
            console.warn('Poor LCP detected:', value + 'ms');
        }
        
        if (name === 'FID' && value > 100) {
            console.warn('Poor FID detected:', value + 'ms');
        }
        
        if (name === 'CLS' && value > 0.1) {
            console.warn('Poor CLS detected:', value);
        }
    }
    
    // Animation Optimization
    optimizeAnimations() {
        // Reduce animations on low-end devices
        if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
            document.documentElement.style.setProperty('--animation-duration', '0.2s');
        }
        
        // Reduce animations for users who prefer reduced motion
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.documentElement.style.setProperty('--animation-duration', '0.01s');
        }
        
        // Pause animations when page is not visible
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                document.body.style.animationPlayState = 'paused';
            } else {
                document.body.style.animationPlayState = 'running';
            }
        });
    }
    
    // Preload Critical Resources
    preloadCriticalResources() {
        const criticalResources = [
            '/css/app.css',
            '/js/app.js',
            '/images/logos/LOGO CSAR vectoriel-01.png'
        ];
        
        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = resource;
            
            if (resource.endsWith('.css')) {
                link.as = 'style';
            } else if (resource.endsWith('.js')) {
                link.as = 'script';
            } else if (resource.match(/\.(png|jpg|jpeg|webp|svg)$/)) {
                link.as = 'image';
            }
            
            document.head.appendChild(link);
        });
    }
    
    // Optimize Fonts
    optimizeFonts() {
        // Preload critical fonts
        const fontLink = document.createElement('link');
        fontLink.rel = 'preload';
        fontLink.href = 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap';
        fontLink.as = 'style';
        document.head.appendChild(fontLink);
        
        // Use font-display: swap for better performance
        const style = document.createElement('style');
        style.textContent = `
            @font-face {
                font-family: 'Inter';
                font-display: swap;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Cleanup
    destroy() {
        this.observers.forEach(observer => {
            observer.disconnect();
        });
        this.observers.clear();
    }
}

// Accessibility Optimizer
class AccessibilityOptimizer {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupKeyboardNavigation();
        this.setupScreenReaderSupport();
        this.setupFocusManagement();
        this.setupColorContrast();
        this.setupARIALabels();
    }
    
    setupKeyboardNavigation() {
        // Skip links
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.textContent = 'Aller au contenu principal';
        skipLink.className = 'skip-link';
        document.body.insertBefore(skipLink, document.body.firstChild);
        
        // Keyboard navigation for custom components
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
    }
    
    setupScreenReaderSupport() {
        // Add screen reader only text
        const srOnlyStyle = document.createElement('style');
        srOnlyStyle.textContent = `
            .sr-only {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }
            .sr-only:focus {
                position: static;
                width: auto;
                height: auto;
                padding: inherit;
                margin: inherit;
                overflow: visible;
                clip: auto;
                white-space: normal;
            }
        `;
        document.head.appendChild(srOnlyStyle);
    }
    
    setupFocusManagement() {
        // Focus management for modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.querySelector('.modal.open');
                if (modal) {
                    this.closeModal(modal);
                }
            }
        });
        
        // Trap focus in modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                const modal = document.querySelector('.modal.open');
                if (modal) {
                    this.trapFocus(modal, e);
                }
            }
        });
    }
    
    trapFocus(element, event) {
        const focusableElements = element.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];
        
        if (event.shiftKey) {
            if (document.activeElement === firstFocusable) {
                lastFocusable.focus();
                event.preventDefault();
            }
        } else {
            if (document.activeElement === lastFocusable) {
                firstFocusable.focus();
                event.preventDefault();
            }
        }
    }
    
    setupColorContrast() {
        // Check for high contrast mode
        if (window.matchMedia('(prefers-contrast: high)').matches) {
            document.body.classList.add('high-contrast');
        }
        
        // Monitor contrast changes
        window.matchMedia('(prefers-contrast: high)').addEventListener('change', (e) => {
            if (e.matches) {
                document.body.classList.add('high-contrast');
            } else {
                document.body.classList.remove('high-contrast');
            }
        });
    }
    
    setupARIALabels() {
        // Add ARIA labels to interactive elements
        document.querySelectorAll('button:not([aria-label])').forEach(button => {
            if (!button.textContent.trim()) {
                button.setAttribute('aria-label', 'Bouton');
            }
        });
        
        document.querySelectorAll('input:not([aria-label])').forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`);
            if (label) {
                input.setAttribute('aria-label', label.textContent);
            }
        });
    }
    
    closeModal(modal) {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        
        // Return focus to trigger element
        const trigger = document.querySelector(`[aria-controls="${modal.id}"]`);
        if (trigger) {
            trigger.focus();
        }
    }
}

// Initialize optimizers when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.performanceOptimizer = new PerformanceOptimizer();
    window.accessibilityOptimizer = new AccessibilityOptimizer();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { PerformanceOptimizer, AccessibilityOptimizer };
}

