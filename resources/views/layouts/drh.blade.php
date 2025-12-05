<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CSAR DRH')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/csar-logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/csar-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/csar-logo.png') }}">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    
    <!-- DRH UI Enhancements -->
    <link rel="stylesheet" href="{{ asset('css/drh-ui-enhancements.css') }}">
    
    <!-- DRH Professional Colors -->
    <link rel="stylesheet" href="{{ asset('css/drh-professional-colors.css') }}">
    
    <!-- DRH Text Readability -->
    <link rel="stylesheet" href="{{ asset('css/drh-text-readability.css') }}">
    
    <!-- Professional CSS -->
    <link rel="stylesheet" href="{{ asset('css/professional-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/drh-professional.css') }}">
    <link rel="stylesheet" href="{{ asset('css/drh-enhanced.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-complete.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-mobile-first.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-helpers.css') }}">
    
    <!-- Custom Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #F5F7FA;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Arrière-plan avec logo CSAR */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(34, 197, 94, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(251, 191, 36, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(59, 130, 246, 0.03) 0%, transparent 50%);
            z-index: -2;
        }
        
        /* Logo CSAR en arrière-plan */
        body::after {
            content: '';
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-5deg);
            width: 600px;
            height: 600px;
            background-image: url('{{ asset("images/logos/LOGO CSAR vectoriel-01.png") }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.02;
            z-index: -1;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(-50%, -50%) rotate(-5deg) translateY(0px); }
            50% { transform: translate(-50%, -50%) rotate(-5deg) translateY(-20px); }
        }
        
        /* Sidebar */
        .drh-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #059669 0%, #047857 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
        }
        
        .brand-info h2 {
            font-size: 18px;
            font-weight: 700;
            color: white;
            margin-bottom: 2px;
        }
        
        .brand-info p {
            font-size: 12px;
            opacity: 0.7;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }
        
        /* Navigation */
        .sidebar-nav {
            padding: 24px 0;
        }
        
        .nav-section {
            margin-bottom: 32px;
        }
        
        .nav-section-title {
            padding: 0 20px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.6;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            margin: 0 8px;
            border-radius: 8px;
        }
        
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }
        
        /* Administration Dropdown Styles */
        .admin-toggle {
            cursor: pointer;
            user-select: none;
        }
        
        .admin-chevron {
            margin-left: auto;
            transition: transform 0.3s ease;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .admin-dropdown {
            display: none;
            margin-left: 20px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 8px 0;
            margin-top: 5px;
        }
        
        .admin-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }
        
        .admin-sub-item {
            padding: 8px 20px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border-radius: 6px;
            margin: 2px 8px;
        }
        
        .admin-sub-item:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }
        
        .admin-sub-item .nav-icon {
            font-size: 12px;
            width: 16px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .admin-sub-item:hover .nav-icon {
            color: white;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-left: 3px solid rgba(255, 255, 255, 0.8);
        }
        
        .nav-icon {
            width: 20px;
            margin-right: 12px;
            text-align: center;
            font-size: 16px;
        }
        
        .nav-text {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }
        
        .nav-badge {
            background: #ef4444;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            font-weight: 600;
        }
        
        /* Main Content */
        .drh-main {
            margin-left: 280px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            background: #F5F7FA;
        }
        
        /* Content Area */
        .drh-content {
            padding: 24px;
            min-height: 100vh;
        }
        
        /* Cards et éléments UI */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            background: white;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            line-height: 1.4;
            position: relative;
            overflow: hidden;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.3);
        }
        
        .btn:active {
            transform: translateY(1px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(34, 197, 94, 0.2);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
        }
        
        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        
        .btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        }
        
        .btn-info:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }
        
        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid #059669;
            color: #059669;
            background: transparent;
            position: relative;
        }
        
        .btn-outline-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: #059669;
            transition: width 0.3s ease;
            z-index: -1;
        }
        
        .btn-outline-primary:hover {
            color: white;
            border-color: #047857;
        }
        
        .btn-outline-primary:hover::before {
            width: 100%;
        }
        
        .btn-outline-success {
            border: 2px solid #22c55e;
            color: #22c55e;
            background: transparent;
            position: relative;
        }
        
        .btn-outline-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: #22c55e;
            transition: width 0.3s ease;
            z-index: -1;
        }
        
        .btn-outline-success:hover {
            color: white;
            border-color: #16a34a;
        }
        
        .btn-outline-success:hover::before {
            width: 100%;
        }
        
        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
            border-radius: 6px;
        }
        
        .btn-lg {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 10px;
        }
        
        .btn-block {
            width: 100%;
        }
        
        .btn i {
            font-size: inherit;
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 15px;
            border-color: #f1f3f4;
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
        }
        
        .alert {
            border: none;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            padding: 12px 15px;
            font-size: 14px;
            transition: border-color 0.2s ease;
        }
        
        .form-control:focus {
            border-color: #059669;
            box-shadow: 0 0 0 0.2rem rgba(5, 150, 105, 0.25);
            outline: none;
        }
        
        .page-title-box {
            margin-bottom: 30px;
        }
        
        .page-title-box h4 {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
            font-size: 24px;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.2s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-number {
            font-size: 28px;
            font-weight: 700;
            color: #059669;
        }
        
        .stats-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }
        
        .bg-soft-success { background-color: rgba(34, 197, 94, 0.1) !important; }
        .bg-soft-primary { background-color: rgba(5, 150, 105, 0.1) !important; }
        .bg-soft-info { background-color: rgba(59, 130, 246, 0.1) !important; }
        .bg-soft-warning { background-color: rgba(245, 158, 11, 0.1) !important; }
        
        .text-success { color: #22c55e !important; }
        .text-primary { color: #059669 !important; }
        .text-info { color: #3b82f6 !important; }
        .text-warning { color: #f59e0b !important; }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .drh-sidebar {
                transform: translateX(-100%);
            }
            
            .drh-sidebar.open {
                transform: translateX(0);
            }
            
            .drh-main {
                margin-left: 0;
            }
        }
        
        @media (max-width: 768px) {
            .drh-sidebar {
                width: 100%;
            }
            
            .drh-content {
                padding: 16px;
            }
        }
        
        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        .sidebar-overlay.open {
            display: block;
        }
        
        /* Advanced Interactions */
        .clickable {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .clickable:hover {
            transform: scale(1.02);
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-item:hover::after {
            width: 80%;
        }
        
        .nav-item.active::after {
            width: 80%;
        }
        
        /* Loading states */
        .btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        .btn.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Focus states */
        .btn:focus,
        .nav-item:focus,
        a:focus {
            outline: 2px solid #059669;
            outline-offset: 2px;
        }
        
        /* Disabled states */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .btn:disabled:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Ripple effect */
        .btn {
            position: relative;
            overflow: hidden;
        }
        
        .btn .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        /* Responsive button improvements */
        @media (max-width: 768px) {
            .btn {
                padding: 8px 16px;
                font-size: 13px;
            }
            
            .btn-sm {
                padding: 6px 12px;
                font-size: 11px;
            }
            
            .btn-lg {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
        
        /* Custom styles for dashboard */
        @yield('styles')
    </style>
</head>

<body class="interface-drh">
    {{-- Navbar Mobile --}}
    @include('components.mobile-navbar', ['interface' => 'Ressources Humaines'])
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <aside class="drh-sidebar sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="logo">
                <img src="{{ asset('images/logos/LOGO CSAR vectoriel-01.png') }}" alt="CSAR Logo">
            </div>
            <div class="brand-info">
                <h2>CSAR</h2>
                <p>Interface DRH</p>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="sidebar-nav">
            <!-- TABLEAU DE BORD -->
            <div class="nav-section">
                <div class="nav-section-title">TABLEAU DE BORD</div>
                
                <a href="{{ route('drh.dashboard') }}" class="nav-item {{ request()->routeIs('drh.dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <span class="nav-text">Vue d'ensemble</span>
                </a>
            </div>
            
            <!-- GESTION DU PERSONNEL -->
            <div class="nav-section">
                <div class="nav-section-title">GESTION DU PERSONNEL</div>
                
                <a href="{{ route('drh.personnel.index') }}" class="nav-item {{ request()->routeIs('drh.personnel.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <span class="nav-text">Personnel</span>
                </a>
                
                <a href="{{ route('drh.personnel.create') }}" class="nav-item">
                    <i class="nav-icon fas fa-user-plus"></i>
                    <span class="nav-text">Ajouter du Personnel</span>
                </a>
            </div>
            
            <!-- DOCUMENTS RH -->
            <div class="nav-section">
                <div class="nav-section-title">DOCUMENTS RH</div>
                
                <a href="{{ route('drh.documents.index') }}" class="nav-item {{ request()->routeIs('drh.documents.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-alt"></i>
                    <span class="nav-text">Documents</span>
                </a>
                
                <a href="{{ route('drh.documents.create') }}" class="nav-item">
                    <i class="nav-icon fas fa-plus"></i>
                    <span class="nav-text">Nouveau Document</span>
                </a>
            </div>
            
            <!-- PRÉSENCE -->
            <div class="nav-section">
                <div class="nav-section-title">PRÉSENCE</div>
                
                <a href="{{ route('drh.attendance.index') }}" class="nav-item {{ request()->routeIs('drh.attendance.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clock"></i>
                    <span class="nav-text">Présence</span>
                </a>
                
                <a href="{{ route('drh.attendance.create') }}" class="nav-item">
                    <i class="nav-icon fas fa-plus"></i>
                    <span class="nav-text">Enregistrer Présence</span>
                </a>
            </div>
            
            <!-- BULLETINS DE PAIE -->
            <div class="nav-section">
                <div class="nav-section-title">BULLETINS DE PAIE</div>
                
                <a href="{{ route('drh.salary-slips.index') }}" class="nav-item {{ request()->routeIs('drh.salary-slips.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-money-bill-wave"></i>
                    <span class="nav-text">Bulletins</span>
                </a>
                
                <a href="{{ route('drh.salary-slips.create') }}" class="nav-item">
                    <i class="nav-icon fas fa-plus"></i>
                    <span class="nav-text">Nouveau Bulletin</span>
                </a>
            </div>
            
            <!-- RAPPORTS -->
            <div class="nav-section">
                <div class="nav-section-title">RAPPORTS</div>
                
                <a href="{{ route('drh.statistics') }}" class="nav-item {{ request()->routeIs('drh.statistics') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <span class="nav-text">Statistiques RH</span>
                </a>
            </div>
            
            <!-- Administration -->
            <div class="nav-section">
                <div class="nav-section-title">ADMINISTRATION</div>
                
                <div class="nav-item admin-toggle" onclick="toggleAdminDropdown()">
                    <i class="nav-icon fas fa-user-cog"></i>
                    <span class="nav-text">Administration</span>
                    <i class="fas fa-chevron-down admin-chevron" id="adminChevron"></i>
                </div>
                
                <div id="adminDropdown" class="admin-dropdown">
                    <a href="{{ route('admin.profile') }}" class="nav-item admin-sub-item">
                        <i class="nav-icon fas fa-user-edit"></i>
                        <span class="nav-text">Mon profil</span>
                    </a>
                    
                    <a href="{{ route('drh.settings') }}" class="nav-item admin-sub-item">
                        <i class="nav-icon fas fa-cog"></i>
                        <span class="nav-text">Paramètres</span>
                    </a>
                    
                    <a href="#" class="nav-item admin-sub-item" onclick="event.preventDefault(); document.getElementById('drhLogoutForm').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <span class="nav-text">Déconnexion</span>
                    </a>
                </div>
                
                <form id="drhLogoutForm" action="{{ route('drh.logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="drh-main">
        <!-- Content Area -->
        <div class="drh-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('drhSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('open');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            });
        }
        
        // Close sidebar on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            }
        });
        
        // Advanced button interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Ripple effect for buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Loading state for form submissions
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        submitBtn.classList.add('loading');
                        submitBtn.disabled = true;
                    }
                });
            });
            
            // Smooth scrolling for anchor links
            const anchorLinks = document.querySelectorAll('a[href^="#"]');
            anchorLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Enhanced click feedback for cards
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('click', function() {
                    if (this.querySelector('a') && !this.querySelector('button')) {
                        this.style.transform = 'scale(0.98)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);
                    }
                });
            });
            
            // Keyboard navigation improvements
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close any open modals or dropdowns
                    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                    openDropdowns.forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                }
            });
            
            // Focus management for accessibility
            const focusableElements = document.querySelectorAll('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
            focusableElements.forEach(element => {
                element.addEventListener('focus', function() {
                    this.style.outline = '2px solid #059669';
                    this.style.outlineOffset = '2px';
                });
                
                element.addEventListener('blur', function() {
                    this.style.outline = '';
                    this.style.outlineOffset = '';
                });
            });
        });
        
        // Utility functions
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type}`;
            notification.textContent = message;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.minWidth = '300px';
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
        
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
        
        // Toggle admin dropdown
        function toggleAdminDropdown() {
            const dropdown = document.getElementById('adminDropdown');
            const chevron = document.getElementById('adminChevron');
            
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                dropdown.classList.add('show');
                chevron.style.transform = 'rotate(180deg)';
            }
        }
    </script>
    
    <!-- Validation Script -->
    <script src="{{ asset('js/validation.js') }}"></script>
    
    <!-- Mobile Navigation Script -->
    <script src="{{ asset('js/mobile-navigation.js') }}"></script>
    
    {{-- JavaScript Responsive --}}
    <script src="{{ asset('js/responsive-mobile.js') }}"></script>
    <script>
    // Wrap tables for horizontal scroll on small screens in DRH layout
    (function(){
        const container = document.querySelector('.drh-content');
        if (!container) return;
        container.querySelectorAll('table').forEach(function(table) {
            if (!table.closest('.table-responsive-wrapper')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive-wrapper';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });
    })();
    </script>
    
    @stack('scripts')
</body>
</html>
