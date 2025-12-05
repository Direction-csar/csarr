/**
 * Browser Compatibility Checker for CSAR Platform
 * Ensures optimal experience across all supported browsers
 */

class BrowserCompatibility {
    constructor() {
        this.browser = this.detectBrowser();
        this.capabilities = this.checkCapabilities();
        this.init();
    }
    
    init() {
        this.addBrowserClasses();
        this.applyBrowserFixes();
        this.checkFeatureSupport();
        this.setupPolyfills();
    }
    
    detectBrowser() {
        const userAgent = navigator.userAgent;
        const browsers = {
            chrome: /Chrome\/(\d+)/.test(userAgent),
            firefox: /Firefox\/(\d+)/.test(userAgent),
            safari: /Safari\/(\d+)/.test(userAgent) && !/Chrome/.test(userAgent),
            edge: /Edg\/(\d+)/.test(userAgent),
            ie: /Trident\/(\d+)/.test(userAgent)
        };
        
        for (const [browser, isMatch] of Object.entries(browsers)) {
            if (isMatch) {
                const version = userAgent.match(new RegExp(`${browser === 'edge' ? 'Edg' : browser === 'ie' ? 'Trident' : browser}\\/(\\d+)`));
                return {
                    name: browser,
                    version: version ? parseInt(version[1]) : null
                };
            }
        }
        
        return { name: 'unknown', version: null };
    }
    
    checkCapabilities() {
        return {
            // CSS Grid support
            cssGrid: CSS.supports('display', 'grid'),
            
            // Flexbox support
            flexbox: CSS.supports('display', 'flex'),
            
            // CSS Variables support
            cssVariables: CSS.supports('color', 'var(--test)'),
            
            // Intersection Observer support
            intersectionObserver: 'IntersectionObserver' in window,
            
            // Service Worker support
            serviceWorker: 'serviceWorker' in navigator,
            
            // WebP support
            webp: this.checkWebPSupport(),
            
            // Touch support
            touch: 'ontouchstart' in window || navigator.maxTouchPoints > 0,
            
            // Local Storage support
            localStorage: typeof Storage !== 'undefined',
            
            // Fetch API support
            fetch: 'fetch' in window,
            
            // ES6 support
            es6: this.checkES6Support(),
            
            // Canvas support
            canvas: !!document.createElement('canvas').getContext,
            
            // WebGL support
            webgl: this.checkWebGLSupport()
        };
    }
    
    checkWebPSupport() {
        const canvas = document.createElement('canvas');
        canvas.width = 1;
        canvas.height = 1;
        return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    }
    
    checkES6Support() {
        try {
            eval('class Test {}');
            eval('const test = () => {}');
            eval('let test = 1');
            return true;
        } catch (e) {
            return false;
        }
    }
    
    checkWebGLSupport() {
        try {
            const canvas = document.createElement('canvas');
            return !!(canvas.getContext('webgl') || canvas.getContext('experimental-webgl'));
        } catch (e) {
            return false;
        }
    }
    
    addBrowserClasses() {
        const body = document.body;
        body.classList.add(`browser-${this.browser.name}`);
        
        if (this.browser.version) {
            body.classList.add(`browser-${this.browser.name}-${this.browser.version}`);
        }
        
        // Add capability classes
        Object.entries(this.capabilities).forEach(([capability, supported]) => {
            body.classList.add(supported ? `supports-${capability}` : `no-${capability}`);
        });
        
        // Add device type classes
        if (this.capabilities.touch) {
            body.classList.add('touch-device');
        } else {
            body.classList.add('no-touch');
        }
        
        // Add screen size classes
        this.addScreenSizeClasses();
    }
    
    addScreenSizeClasses() {
        const updateScreenSizeClasses = () => {
            const body = document.body;
            body.classList.remove('screen-xs', 'screen-sm', 'screen-md', 'screen-lg', 'screen-xl');
            
            if (window.innerWidth < 576) {
                body.classList.add('screen-xs');
            } else if (window.innerWidth < 768) {
                body.classList.add('screen-sm');
            } else if (window.innerWidth < 1024) {
                body.classList.add('screen-md');
            } else if (window.innerWidth < 1280) {
                body.classList.add('screen-lg');
            } else {
                body.classList.add('screen-xl');
            }
        };
        
        updateScreenSizeClasses();
        window.addEventListener('resize', updateScreenSizeClasses);
    }
    
    applyBrowserFixes() {
        // Internet Explorer fixes
        if (this.browser.name === 'ie') {
            this.applyIEFixes();
        }
        
        // Safari fixes
        if (this.browser.name === 'safari') {
            this.applySafariFixes();
        }
        
        // Firefox fixes
        if (this.browser.name === 'firefox') {
            this.applyFirefoxFixes();
        }
        
        // Edge fixes
        if (this.browser.name === 'edge') {
            this.applyEdgeFixes();
        }
    }
    
    applyIEFixes() {
        // Add IE-specific styles
        const style = document.createElement('style');
        style.textContent = `
            .ie-fallback {
                display: block !important;
            }
            .ie-hidden {
                display: none !important;
            }
            .responsive-grid {
                display: -ms-grid !important;
            }
            .responsive-grid-2 {
                -ms-grid-columns: 1fr 1fr !important;
            }
            .responsive-grid-3 {
                -ms-grid-columns: 1fr 1fr 1fr !important;
            }
            .responsive-grid-4 {
                -ms-grid-columns: 1fr 1fr 1fr 1fr !important;
            }
        `;
        document.head.appendChild(style);
        
        // Add IE-specific classes
        document.body.classList.add('ie-browser');
    }
    
    applySafariFixes() {
        // Safari-specific fixes
        document.body.classList.add('safari-browser');
        
        // Fix for Safari flexbox issues
        const style = document.createElement('style');
        style.textContent = `
            .safari-flex-fix {
                -webkit-flex: 1;
                -webkit-box-flex: 1;
            }
            .safari-grid-fix {
                display: -webkit-grid;
            }
        `;
        document.head.appendChild(style);
    }
    
    applyFirefoxFixes() {
        // Firefox-specific fixes
        document.body.classList.add('firefox-browser');
        
        // Fix for Firefox scrollbar issues
        const style = document.createElement('style');
        style.textContent = `
            .firefox-scroll-fix {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e0 #f7fafc;
            }
        `;
        document.head.appendChild(style);
    }
    
    applyEdgeFixes() {
        // Edge-specific fixes
        document.body.classList.add('edge-browser');
    }
    
    checkFeatureSupport() {
        // Check for critical features and provide fallbacks
        if (!this.capabilities.cssGrid) {
            this.provideGridFallback();
        }
        
        if (!this.capabilities.flexbox) {
            this.provideFlexboxFallback();
        }
        
        if (!this.capabilities.cssVariables) {
            this.provideCSSVariablesFallback();
        }
        
        if (!this.capabilities.intersectionObserver) {
            this.provideIntersectionObserverFallback();
        }
        
        if (!this.capabilities.fetch) {
            this.provideFetchFallback();
        }
    }
    
    provideGridFallback() {
        const style = document.createElement('style');
        style.textContent = `
            .responsive-grid {
                display: block !important;
            }
            .responsive-grid > * {
                display: block !important;
                width: 100% !important;
                margin-bottom: 1rem !important;
            }
            @media (min-width: 768px) {
                .responsive-grid-2 > * {
                    display: inline-block !important;
                    width: 48% !important;
                    margin-right: 2% !important;
                }
                .responsive-grid-2 > *:nth-child(even) {
                    margin-right: 0 !important;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    provideFlexboxFallback() {
        const style = document.createElement('style');
        style.textContent = `
            .flex-fallback {
                display: block !important;
            }
            .flex-fallback > * {
                display: block !important;
                width: 100% !important;
                margin-bottom: 0.5rem !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    provideCSSVariablesFallback() {
        // Fallback for browsers without CSS variables support
        const style = document.createElement('style');
        style.textContent = `
            :root {
                --csar-blue: #1e3a8a;
                --csar-green: #059669;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;
            }
        `;
        document.head.appendChild(style);
    }
    
    provideIntersectionObserverFallback() {
        // Simple fallback for IntersectionObserver
        window.IntersectionObserver = window.IntersectionObserver || function(callback, options) {
            return {
                observe: function(element) {
                    // Simple implementation that always triggers
                    setTimeout(() => callback([{ target: element, isIntersecting: true }]), 100);
                },
                unobserve: function() {},
                disconnect: function() {}
            };
        };
    }
    
    provideFetchFallback() {
        // Fetch polyfill for older browsers
        if (!window.fetch) {
            window.fetch = function(url, options) {
                return new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open(options?.method || 'GET', url);
                    
                    if (options?.headers) {
                        Object.entries(options.headers).forEach(([key, value]) => {
                            xhr.setRequestHeader(key, value);
                        });
                    }
                    
                    xhr.onload = () => {
                        resolve({
                            ok: xhr.status >= 200 && xhr.status < 300,
                            status: xhr.status,
                            json: () => Promise.resolve(JSON.parse(xhr.responseText)),
                            text: () => Promise.resolve(xhr.responseText)
                        });
                    };
                    
                    xhr.onerror = () => reject(new Error('Network error'));
                    xhr.send(options?.body);
                });
            };
        }
    }
    
    setupPolyfills() {
        // Array.from polyfill
        if (!Array.from) {
            Array.from = function(arrayLike) {
                return Array.prototype.slice.call(arrayLike);
            };
        }
        
        // Object.assign polyfill
        if (!Object.assign) {
            Object.assign = function(target) {
                for (let i = 1; i < arguments.length; i++) {
                    const source = arguments[i];
                    for (const key in source) {
                        if (source.hasOwnProperty(key)) {
                            target[key] = source[key];
                        }
                    }
                }
                return target;
            };
        }
        
        // Promise polyfill for older browsers
        if (!window.Promise) {
            // Simple Promise polyfill
            window.Promise = function(executor) {
                const self = this;
                self.state = 'pending';
                self.value = undefined;
                self.handlers = [];
                
                function resolve(result) {
                    if (self.state === 'pending') {
                        self.state = 'fulfilled';
                        self.value = result;
                        self.handlers.forEach(handle);
                        self.handlers = null;
                    }
                }
                
                function reject(error) {
                    if (self.state === 'pending') {
                        self.state = 'rejected';
                        self.value = error;
                        self.handlers.forEach(handle);
                        self.handlers = null;
                    }
                }
                
                function handle(handler) {
                    if (self.state === 'pending') {
                        self.handlers.push(handler);
                    } else {
                        if (self.state === 'fulfilled' && typeof handler.onFulfilled === 'function') {
                            handler.onFulfilled(self.value);
                        }
                        if (self.state === 'rejected' && typeof handler.onRejected === 'function') {
                            handler.onRejected(self.value);
                        }
                    }
                }
                
                self.then = function(onFulfilled, onRejected) {
                    return new Promise((resolve, reject) => {
                        handle({
                            onFulfilled: result => {
                                try {
                                    resolve(onFulfilled ? onFulfilled(result) : result);
                                } catch (ex) {
                                    reject(ex);
                                }
                            },
                            onRejected: error => {
                                try {
                                    resolve(onRejected ? onRejected(error) : error);
                                } catch (ex) {
                                    reject(ex);
                                }
                            }
                        });
                    });
                };
                
                executor(resolve, reject);
            };
        }
    }
    
    // Public methods for checking capabilities
    isSupported(feature) {
        return this.capabilities[feature] || false;
    }
    
    getBrowserInfo() {
        return {
            browser: this.browser,
            capabilities: this.capabilities
        };
    }
    
    // Method to show compatibility warning if needed
    showCompatibilityWarning() {
        if (!this.capabilities.cssGrid || !this.capabilities.flexbox) {
            const warning = document.createElement('div');
            warning.className = 'browser-compatibility-warning';
            warning.innerHTML = `
                <div class="warning-content">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Votre navigateur est obsolète. Pour une meilleure expérience, veuillez mettre à jour votre navigateur.</p>
                    <button onclick="this.parentElement.parentElement.remove()">Fermer</button>
                </div>
            `;
            
            const style = document.createElement('style');
            style.textContent = `
                .browser-compatibility-warning {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    background: #f59e0b;
                    color: white;
                    padding: 1rem;
                    z-index: 1000;
                    text-align: center;
                }
                .warning-content {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 1rem;
                    max-width: 1200px;
                    margin: 0 auto;
                }
                .warning-content button {
                    background: white;
                    color: #f59e0b;
                    border: none;
                    padding: 0.5rem 1rem;
                    border-radius: 4px;
                    cursor: pointer;
                    font-weight: bold;
                }
            `;
            document.head.appendChild(style);
            document.body.appendChild(warning);
        }
    }
}

// Initialize browser compatibility checker
document.addEventListener('DOMContentLoaded', function() {
    window.browserCompatibility = new BrowserCompatibility();
    
    // Show warning for very old browsers
    if (window.browserCompatibility.browser.name === 'ie' && window.browserCompatibility.browser.version < 11) {
        window.browserCompatibility.showCompatibilityWarning();
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BrowserCompatibility;
}

