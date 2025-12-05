<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience')</title>
    
    <!-- Meta tags pour SEO et performance -->
    <meta name="description" content="Plateforme numérique du CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience du Sénégal">
    <meta name="keywords" content="CSAR, sécurité alimentaire, résilience, Sénégal, entrepôts, stocks">
    <meta name="author" content="CSAR">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience')">
    <meta property="og:description" content="@yield('description', 'Plateforme numérique du CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience du Sénégal')">
    <meta property="og:image" content="@yield('og_image', asset('images/logos/LOGO CSAR vectoriel-01.png'))">
    <meta property="og:site_name" content="CSAR">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience')">
    <meta property="twitter:description" content="@yield('description', 'Plateforme numérique du CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience du Sénégal')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/logos/LOGO CSAR vectoriel-01.png'))">
    <meta property="twitter:site" content="@csar_sn">
    <meta property="twitter:creator" content="@csar_sn">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/csar-logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/csar-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/csar-logo.png') }}">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style">
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-helpers.css') }}">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Custom responsive styles -->
    <style>
        /* Mobile-first responsive design */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Responsive container */
        .responsive-container {
            @apply w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
        }
        
        /* Mobile-first grid system */
        .responsive-grid {
            @apply grid gap-4 sm:gap-6;
            grid-template-columns: 1fr;
        }
        
        .responsive-grid-2 {
            @apply grid gap-4 sm:gap-6;
            grid-template-columns: 1fr;
        }
        
        .responsive-grid-3 {
            @apply grid gap-4 sm:gap-6;
            grid-template-columns: 1fr;
        }
        
        .responsive-grid-4 {
            @apply grid gap-4 sm:gap-6;
            grid-template-columns: 1fr;
        }
        
        /* Tablet breakpoint */
        @media (min-width: 768px) {
            .responsive-grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }
            .responsive-grid-3 {
                grid-template-columns: repeat(2, 1fr);
            }
            .responsive-grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Desktop breakpoint */
        @media (min-width: 1024px) {
            .responsive-grid-3 {
                grid-template-columns: repeat(3, 1fr);
            }
            .responsive-grid-4 {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Large desktop */
        @media (min-width: 1280px) {
            .responsive-grid-4 {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Responsive cards */
        .responsive-card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6;
        }
        
        /* Responsive tables */
        .responsive-table {
            @apply w-full overflow-x-auto;
        }
        
        .responsive-table table {
            @apply w-full min-w-full;
        }
        
        /* Mobile navigation */
        .mobile-nav {
            @apply fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 md:hidden;
        }
        
        .mobile-nav-item {
            @apply flex flex-col items-center justify-center py-2 px-3 text-xs text-gray-600 hover:text-csar-green-600 transition-colors;
        }
        
        .mobile-nav-item.active {
            @apply text-csar-green-600;
        }
        
        /* Sidebar responsive */
        .sidebar {
            @apply fixed left-0 top-0 h-full bg-csar-blue-950 text-white transition-transform duration-300 ease-in-out z-40;
            width: 280px;
        }
        
        .sidebar-mobile {
            @apply -translate-x-full;
        }
        
        .sidebar-desktop {
            @apply translate-x-0;
        }
        
        /* Main content responsive */
        .main-content {
            @apply transition-all duration-300 ease-in-out;
        }
        
        .main-content-mobile {
            @apply w-full;
        }
        
        .main-content-desktop {
            @apply ml-72;
        }
        
        /* Responsive text */
        .responsive-text {
            @apply text-sm sm:text-base lg:text-lg;
        }
        
        .responsive-title {
            @apply text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold;
        }
        
        .responsive-subtitle {
            @apply text-base sm:text-lg lg:text-xl font-semibold;
        }
        
        /* Responsive buttons */
        .responsive-btn {
            @apply px-4 py-2 text-sm sm:text-base font-medium rounded-lg transition-colors duration-200;
        }
        
        .responsive-btn-primary {
            @apply bg-csar-green-600 text-white hover:bg-csar-green-700;
        }
        
        .responsive-btn-secondary {
            @apply bg-gray-200 text-gray-800 hover:bg-gray-300;
        }
        
        /* Responsive forms */
        .responsive-form {
            @apply space-y-4 sm:space-y-6;
        }
        
        .responsive-input {
            @apply w-full px-3 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-csar-green-500 focus:border-csar-green-500;
        }
        
        /* Loading states */
        .loading {
            @apply animate-pulse;
        }
        
        /* Accessibility improvements */
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
        
        .focus-visible {
            @apply focus:outline-none focus:ring-2 focus:ring-csar-green-500 focus:ring-offset-2;
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .dark-mode {
                @apply bg-gray-900 text-white;
            }
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            .print-break {
                page-break-before: always;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="responsive-body">
    <!-- Skip to main content for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-csar-green-600 text-white px-4 py-2 rounded-lg z-50">
        Aller au contenu principal
    </a>
    
    @yield('content')
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Main JavaScript -->
    @vite(['resources/js/app.js'])
    
    <!-- Responsive utilities -->
    <script>
        // Responsive utilities
        class ResponsiveManager {
            constructor() {
                this.isMobile = window.innerWidth < 768;
                this.isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
                this.isDesktop = window.innerWidth >= 1024;
                
                this.init();
            }
            
            init() {
                this.setupResizeListener();
                this.setupTouchGestures();
                this.setupAccessibility();
            }
            
            setupResizeListener() {
                window.addEventListener('resize', this.debounce(() => {
                    this.isMobile = window.innerWidth < 768;
                    this.isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;
                    this.isDesktop = window.innerWidth >= 1024;
                    
                    // Trigger custom event
                    window.dispatchEvent(new CustomEvent('responsiveChange', {
                        detail: {
                            isMobile: this.isMobile,
                            isTablet: this.isTablet,
                            isDesktop: this.isDesktop
                        }
                    }));
                }, 250));
            }
            
            setupTouchGestures() {
                // Swipe gestures for mobile
                let startX, startY;
                
                document.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                });
                
                document.addEventListener('touchend', (e) => {
                    if (!startX || !startY) return;
                    
                    const endX = e.changedTouches[0].clientX;
                    const endY = e.changedTouches[0].clientY;
                    
                    const diffX = startX - endX;
                    const diffY = startY - endY;
                    
                    // Swipe left/right
                    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                        if (diffX > 0) {
                            // Swipe left
                            this.handleSwipeLeft();
                        } else {
                            // Swipe right
                            this.handleSwipeRight();
                        }
                    }
                    
                    startX = null;
                    startY = null;
                });
            }
            
            setupAccessibility() {
                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.closeModals();
                    }
                });
                
                // Focus management
                document.addEventListener('focusin', (e) => {
                    if (e.target.matches('input, textarea, select, button, a')) {
                        e.target.classList.add('focus-visible');
                    }
                });
                
                document.addEventListener('focusout', (e) => {
                    e.target.classList.remove('focus-visible');
                });
            }
            
            handleSwipeLeft() {
                // Close sidebar on mobile
                if (this.isMobile) {
                    const sidebar = document.querySelector('.sidebar');
                    if (sidebar && sidebar.classList.contains('open')) {
                        this.toggleSidebar();
                    }
                }
            }
            
            handleSwipeRight() {
                // Open sidebar on mobile
                if (this.isMobile) {
                    const sidebar = document.querySelector('.sidebar');
                    if (sidebar && !sidebar.classList.contains('open')) {
                        this.toggleSidebar();
                    }
                }
            }
            
            toggleSidebar() {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                
                if (sidebar) {
                    sidebar.classList.toggle('open');
                    if (overlay) {
                        overlay.classList.toggle('open');
                    }
                }
            }
            
            closeModals() {
                // Close all modals
                document.querySelectorAll('.modal.open').forEach(modal => {
                    modal.classList.remove('open');
                });
                
                // Close sidebar
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) {
                    sidebar.classList.remove('open');
                }
            }
            
            debounce(func, wait) {
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
        }
        
        // Initialize responsive manager
        const responsiveManager = new ResponsiveManager();
        
        // Chart.js responsive configuration
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;
        Chart.defaults.plugins.legend.display = true;
        Chart.defaults.plugins.legend.position = 'bottom';
        
        // Mobile-specific optimizations
        if (responsiveManager.isMobile) {
            // Reduce animations on mobile for better performance
            document.documentElement.style.setProperty('--animation-duration', '0.2s');
            
            // Optimize images for mobile
            document.querySelectorAll('img').forEach(img => {
                if (img.dataset.mobileSrc) {
                    img.src = img.dataset.mobileSrc;
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>

