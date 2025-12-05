@extends('layouts.responsive-base')

@section('title', 'CSAR ' . ucfirst($role ?? 'Admin') . ' - Interface Responsive')

@section('styles')
<style>
    /* Admin-specific responsive styles */
    .admin-layout {
        @apply min-h-screen bg-gray-50;
    }
    
    /* Sidebar responsive */
    .admin-sidebar {
        @apply sidebar sidebar-mobile lg:sidebar-desktop;
        width: 280px;
    }
    
    .admin-sidebar.open {
        @apply translate-x-0;
    }
    
    /* Sidebar overlay */
    .sidebar-overlay {
        @apply fixed inset-0 bg-black bg-opacity-50 z-30 hidden;
    }
    
    .sidebar-overlay.open {
        @apply block lg:hidden;
    }
    
    /* Main content responsive */
    .admin-main {
        @apply main-content main-content-mobile lg:main-content-desktop;
    }
    
    /* Header responsive */
    .admin-header {
        @apply bg-white border-b border-gray-200 px-4 py-3 lg:px-6 lg:py-4;
    }
    
    .admin-header-content {
        @apply flex items-center justify-between;
    }
    
    .admin-header-left {
        @apply flex items-center space-x-4;
    }
    
    .admin-header-right {
        @apply flex items-center space-x-4;
    }
    
    /* Mobile menu button */
    .mobile-menu-btn {
        @apply p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 lg:hidden;
    }
    
    /* Page title responsive */
    .page-title {
        @apply text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900;
    }
    
    .page-subtitle {
        @apply text-sm sm:text-base text-gray-600 mt-1;
    }
    
    /* Stats grid responsive */
    .stats-grid {
        @apply responsive-grid-2 lg:responsive-grid-4 gap-4 sm:gap-6;
    }
    
    .stat-card {
        @apply responsive-card;
    }
    
    .stat-card-content {
        @apply flex items-center justify-between;
    }
    
    .stat-card-info {
        @apply flex-1;
    }
    
    .stat-card-value {
        @apply text-2xl sm:text-3xl font-bold text-gray-900;
    }
    
    .stat-card-label {
        @apply text-sm sm:text-base text-gray-600 mt-1;
    }
    
    .stat-card-icon {
        @apply w-12 h-12 rounded-lg flex items-center justify-center text-white;
    }
    
    /* Charts responsive */
    .chart-container {
        @apply responsive-card;
        height: 300px;
    }
    
    @media (min-width: 768px) {
        .chart-container {
            height: 400px;
        }
    }
    
    /* Tables responsive */
    .table-container {
        @apply responsive-card overflow-hidden;
    }
    
    .table-wrapper {
        @apply responsive-table;
    }
    
    .table-wrapper table {
        @apply w-full;
    }
    
    .table-wrapper th,
    .table-wrapper td {
        @apply px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base;
    }
    
    .table-wrapper th {
        @apply bg-gray-50 font-semibold text-gray-900;
    }
    
    .table-wrapper td {
        @apply border-t border-gray-200;
    }
    
    /* Mobile table cards */
    @media (max-width: 767px) {
        .table-wrapper {
            @apply block;
        }
        
        .table-wrapper table,
        .table-wrapper thead,
        .table-wrapper tbody,
        .table-wrapper th,
        .table-wrapper td,
        .table-wrapper tr {
            @apply block;
        }
        
        .table-wrapper thead tr {
            @apply absolute -top-full left-0 w-full;
        }
        
        .table-wrapper tr {
            @apply border border-gray-200 rounded-lg mb-4 p-4 bg-white;
        }
        
        .table-wrapper td {
            @apply border-0 p-0 mb-2 last:mb-0;
        }
        
        .table-wrapper td:before {
            @apply content-attr(data-label) font-semibold text-gray-900 mr-2;
        }
    }
    
    /* Forms responsive */
    .form-grid {
        @apply responsive-grid-2 gap-4 sm:gap-6;
    }
    
    .form-group {
        @apply space-y-2;
    }
    
    .form-label {
        @apply block text-sm font-medium text-gray-700;
    }
    
    .form-input {
        @apply responsive-input;
    }
    
    .form-select {
        @apply responsive-input appearance-none bg-white;
    }
    
    .form-textarea {
        @apply responsive-input resize-none;
        min-height: 100px;
    }
    
    /* Buttons responsive */
    .btn-group {
        @apply flex flex-col sm:flex-row gap-2 sm:gap-4;
    }
    
    .btn-primary {
        @apply responsive-btn responsive-btn-primary;
    }
    
    .btn-secondary {
        @apply responsive-btn responsive-btn-secondary;
    }
    
    .btn-danger {
        @apply responsive-btn bg-red-600 text-white hover:bg-red-700;
    }
    
    /* Alerts responsive */
    .alert {
        @apply p-4 rounded-lg mb-4;
    }
    
    .alert-success {
        @apply bg-green-50 text-green-800 border border-green-200;
    }
    
    .alert-error {
        @apply bg-red-50 text-red-800 border border-red-200;
    }
    
    .alert-warning {
        @apply bg-yellow-50 text-yellow-800 border border-yellow-200;
    }
    
    .alert-info {
        @apply bg-blue-50 text-blue-800 border border-blue-200;
    }
    
    /* Navigation responsive */
    .nav-section {
        @apply mb-6;
    }
    
    .nav-section-title {
        @apply text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-2;
    }
    
    .nav-item {
        @apply flex items-center px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 transition-colors duration-200;
    }
    
    .nav-item.active {
        @apply text-white bg-csar-green-600;
    }
    
    .nav-icon {
        @apply w-5 h-5 mr-3;
    }
    
    .nav-text {
        @apply flex-1;
    }
    
    .nav-badge {
        @apply bg-red-500 text-white text-xs px-2 py-1 rounded-full;
    }
    
    /* Mobile bottom navigation */
    .mobile-nav {
        @apply fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 lg:hidden;
    }
    
    .mobile-nav-container {
        @apply flex justify-around;
    }
    
    .mobile-nav-item {
        @apply flex flex-col items-center py-2 px-3 text-xs text-gray-600 hover:text-csar-green-600 transition-colors;
    }
    
    .mobile-nav-item.active {
        @apply text-csar-green-600;
    }
    
    .mobile-nav-icon {
        @apply w-5 h-5 mb-1;
    }
    
    /* Loading states */
    .loading-skeleton {
        @apply animate-pulse bg-gray-200 rounded;
    }
    
    /* Responsive utilities */
    .mobile-only {
        @apply block lg:hidden;
    }
    
    .desktop-only {
        @apply hidden lg:block;
    }
    
    .tablet-only {
        @apply hidden md:block lg:hidden;
    }
    
    /* Print styles */
    @media print {
        .admin-sidebar,
        .mobile-nav,
        .no-print {
            display: none !important;
        }
        
        .admin-main {
            margin-left: 0 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="admin-layout">
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Sidebar Header -->
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-csar-green-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shield-alt text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">CSAR</h2>
                    <p class="text-xs text-gray-300">Interface {{ ucfirst($role ?? 'Admin') }}</p>
                </div>
            </div>
        </div>
        
        <!-- User Profile -->
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-csar-green-600 rounded-full flex items-center justify-center">
                    @if(auth()->check() && auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                    @else
                        <i class="fas fa-user text-white"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">
                        {{ auth()->check() ? auth()->user()->name : 'Utilisateur' }}
                    </p>
                    <p class="text-xs text-gray-300 truncate">
                        {{ ucfirst($role ?? 'Admin') }} CSAR
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">
            @yield('sidebar-navigation')
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="px-4 py-4 border-t border-gray-700">
            <a href="{{ route($role . '.logout') }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
               onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                <i class="fas fa-sign-out-alt nav-icon"></i>
                <span class="nav-text">Déconnexion</span>
            </a>
            <form id="logoutForm" action="{{ route($role . '.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="admin-main" id="main-content">
        <!-- Header -->
        <header class="admin-header">
            <div class="admin-header-content">
                <div class="admin-header-left">
                    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Ouvrir le menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="page-title">@yield('page-title', 'Tableau de bord')</h1>
                        @hasSection('page-subtitle')
                            <p class="page-subtitle">@yield('page-subtitle')</p>
                        @endif
                    </div>
                </div>
                <div class="admin-header-right">
                    @yield('header-actions')
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <div class="responsive-container py-6">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('warning') }}
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    {{ session('info') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-nav">
        <div class="mobile-nav-container">
            @yield('mobile-navigation')
        </div>
    </nav>
</div>

<script>
// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    if (mobileMenuBtn && sidebar && overlay) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        });
    }
    
    // Close sidebar on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
        }
    });
    
    // Handle responsive table data labels
    const tables = document.querySelectorAll('.table-wrapper table');
    tables.forEach(table => {
        if (window.innerWidth < 768) {
            const headers = Array.from(table.querySelectorAll('th')).map(th => th.textContent);
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (headers[index]) {
                        cell.setAttribute('data-label', headers[index]);
                    }
                });
            });
        }
    });
    
    // Responsive chart resizing
    window.addEventListener('resize', function() {
        // Trigger chart resize
        if (window.Chart) {
            Chart.helpers.each(Chart.instances, function(chart) {
                chart.resize();
            });
        }
    });
});
</script>
@endsection

