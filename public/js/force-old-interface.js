/* ========================================
   FORCE ANCIENNE INTERFACE - SCRIPT D'URGENCE
   ======================================== */

console.log('🔄 FORCE ANCIENNE INTERFACE - CHARGEMENT...');

// Fonction pour forcer l'application des styles de l'ancienne interface
function forceOldInterface() {
    console.log('💪 Forçage de l\'ancienne interface...');
    
    // Vérifier si on est sur la page admin
    if (window.location.pathname.includes('/admin')) {
        console.log('🔵 Application du style Admin ancien...');
        
        // Forcer les classes admin
        document.body.classList.add('admin-layout');
        document.body.classList.remove('dg-layout');
        
        // Forcer les styles admin
        const adminStyle = document.createElement('style');
        adminStyle.id = 'force-admin-old-style';
        adminStyle.textContent = `
            /* FORCE ADMIN ANCIENNE INTERFACE */
            body {
                background: #f8fafc !important;
                font-family: 'Inter', sans-serif !important;
            }
            
            .admin-sidebar {
                background: #1e293b !important;
                width: 280px !important;
                position: fixed !important;
                height: 100vh !important;
                left: 0 !important;
                top: 0 !important;
                z-index: 1000 !important;
                color: #ffffff !important;
                border-right: 1px solid #334155 !important;
            }
            
            .admin-sidebar-header {
                padding: 24px 20px !important;
                border-bottom: 1px solid #334155 !important;
                background: #1e293b !important;
            }
            
            .admin-logo-text h2 {
                color: #ffffff !important;
                font-size: 20px !important;
                font-weight: 700 !important;
                margin: 0 !important;
            }
            
            .admin-logo-text p {
                color: #cbd5e1 !important;
                font-size: 14px !important;
                margin: 0 !important;
            }
            
            .admin-nav-section-title {
                color: #94a3b8 !important;
                font-size: 12px !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                padding: 0 20px 12px !important;
                margin-bottom: 8px !important;
                border-bottom: 1px solid #334155 !important;
            }
            
            .admin-nav-item {
                display: flex !important;
                align-items: center !important;
                gap: 12px !important;
                padding: 12px 20px !important;
                color: #e2e8f0 !important;
                text-decoration: none !important;
                border-left: 3px solid transparent !important;
            }
            
            .admin-nav-item:hover {
                background: rgba(255, 255, 255, 0.1) !important;
                color: #ffffff !important;
                border-left-color: #3b82f6 !important;
            }
            
            .admin-nav-item.active {
                background: rgba(16, 185, 129, 0.15) !important;
                color: #ffffff !important;
                border-left-color: #10b981 !important;
            }
            
            .admin-nav-icon {
                width: 20px !important;
                height: 20px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 16px !important;
            }
            
            .admin-nav-text {
                font-size: 14px !important;
                font-weight: 500 !important;
            }
            
            .admin-main {
                margin-left: 280px !important;
                background: #f8fafc !important;
                min-height: 100vh !important;
            }
            
            .admin-content {
                padding: 24px !important;
            }
            
            .page-header {
                background: #ffffff !important;
                padding: 24px !important;
                border-radius: 12px !important;
                margin-bottom: 24px !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
                border: 1px solid #e2e8f0 !important;
            }
            
            .page-title {
                font-size: 28px !important;
                font-weight: 700 !important;
                color: #1e293b !important;
                margin: 0 0 8px 0 !important;
            }
            
            .page-subtitle {
                color: #64748b !important;
                font-size: 16px !important;
                margin: 0 !important;
            }
            
            .dashboard-card, .stat-card {
                background: #ffffff !important;
                border-radius: 12px !important;
                padding: 24px !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
                border: 1px solid #e2e8f0 !important;
            }
            
            .stat-icon {
                width: 48px !important;
                height: 48px !important;
                border-radius: 12px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 20px !important;
                color: #ffffff !important;
            }
            
            .stat-icon.blue { background: #3b82f6 !important; }
            .stat-icon.green { background: #10b981 !important; }
            .stat-icon.orange { background: #f59e0b !important; }
            .stat-icon.red { background: #ef4444 !important; }
            
            .stat-number {
                font-size: 24px !important;
                font-weight: 700 !important;
                color: #1e293b !important;
                margin: 0 0 4px 0 !important;
            }
            
            .stat-label {
                color: #64748b !important;
                font-size: 14px !important;
                margin: 0 !important;
            }
            
            .stat-change {
                font-size: 12px !important;
                font-weight: 600 !important;
                color: #10b981 !important;
                margin-top: 4px !important;
            }
        `;
        document.head.appendChild(adminStyle);
        
    } else if (window.location.pathname.includes('/dg')) {
        console.log('🟢 Application du style DG ancien...');
        
        // Forcer les classes DG
        document.body.classList.add('dg-layout');
        document.body.classList.remove('admin-layout');
        
        // Forcer les styles DG
        const dgStyle = document.createElement('style');
        dgStyle.id = 'force-dg-old-style';
        dgStyle.textContent = `
            /* FORCE DG ANCIENNE INTERFACE */
            body {
                background: #f0fdf4 !important;
                font-family: 'Inter', sans-serif !important;
            }
            
            .dg-sidebar {
                background: #064e3b !important;
                width: 280px !important;
                position: fixed !important;
                height: 100vh !important;
                left: 0 !important;
                top: 0 !important;
                z-index: 1000 !important;
                color: #ffffff !important;
                border-right: 1px solid #065f46 !important;
            }
            
            .dg-sidebar-header {
                padding: 24px 20px !important;
                border-bottom: 1px solid #065f46 !important;
                background: #064e3b !important;
            }
            
            .dg-logo-text h2 {
                color: #ffffff !important;
                font-size: 20px !important;
                font-weight: 700 !important;
                margin: 0 !important;
            }
            
            .dg-logo-text p {
                color: #a7f3d0 !important;
                font-size: 14px !important;
                margin: 0 !important;
            }
            
            .dg-nav-section-title {
                color: #a7f3d0 !important;
                font-size: 12px !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                padding: 0 20px 12px !important;
                margin-bottom: 8px !important;
                border-bottom: 1px solid #065f46 !important;
            }
            
            .dg-nav-item {
                display: flex !important;
                align-items: center !important;
                gap: 12px !important;
                padding: 12px 20px !important;
                color: #d1fae5 !important;
                text-decoration: none !important;
                border-left: 3px solid transparent !important;
            }
            
            .dg-nav-item:hover {
                background: rgba(255, 255, 255, 0.1) !important;
                color: #ffffff !important;
                border-left-color: #10b981 !important;
            }
            
            .dg-nav-item.active {
                background: rgba(16, 185, 129, 0.15) !important;
                color: #ffffff !important;
                border-left-color: #10b981 !important;
            }
            
            .dg-nav-icon {
                width: 20px !important;
                height: 20px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 16px !important;
            }
            
            .dg-nav-text {
                font-size: 14px !important;
                font-weight: 500 !important;
            }
            
            .dg-main {
                margin-left: 280px !important;
                background: #f0fdf4 !important;
                min-height: 100vh !important;
            }
            
            .dg-content {
                padding: 24px !important;
            }
            
            .page-header {
                background: #ffffff !important;
                padding: 24px !important;
                border-radius: 12px !important;
                margin-bottom: 24px !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
                border: 1px solid #d1fae5 !important;
            }
            
            .page-title {
                font-size: 28px !important;
                font-weight: 700 !important;
                color: #064e3b !important;
                margin: 0 0 8px 0 !important;
            }
            
            .page-subtitle {
                color: #6b7280 !important;
                font-size: 16px !important;
                margin: 0 !important;
            }
            
            .dashboard-card, .stat-card {
                background: #ffffff !important;
                border-radius: 12px !important;
                padding: 24px !important;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
                border: 1px solid #d1fae5 !important;
            }
            
            .stat-icon {
                width: 48px !important;
                height: 48px !important;
                border-radius: 12px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                font-size: 20px !important;
                color: #ffffff !important;
            }
            
            .stat-icon.blue { background: #3b82f6 !important; }
            .stat-icon.green { background: #10b981 !important; }
            .stat-icon.orange { background: #f59e0b !important; }
            .stat-icon.red { background: #ef4444 !important; }
            
            .stat-number {
                font-size: 24px !important;
                font-weight: 700 !important;
                color: #064e3b !important;
                margin: 0 0 4px 0 !important;
            }
            
            .stat-label {
                color: #6b7280 !important;
                font-size: 14px !important;
                margin: 0 !important;
            }
            
            .stat-change {
                font-size: 12px !important;
                font-weight: 600 !important;
                color: #10b981 !important;
                margin-top: 4px !important;
            }
        `;
        document.head.appendChild(dgStyle);
    }
    
    console.log('✅ Ancienne interface forcée !');
}

// Exécuter immédiatement
forceOldInterface();

// Exécuter au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 DOM chargé, force ancienne interface...');
    forceOldInterface();
});

// Exécuter au chargement complet
window.addEventListener('load', function() {
    console.log('🔄 Page chargée, force finale ancienne interface...');
    forceOldInterface();
});

// Exécuter toutes les 2 secondes pour être sûr
setInterval(function() {
    forceOldInterface();
}, 2000);

console.log('🎉 SCRIPT FORCE ANCIENNE INTERFACE CHARGÉ !');







