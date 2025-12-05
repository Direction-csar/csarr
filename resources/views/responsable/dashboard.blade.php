@extends('layouts.responsable')

@section('title', 'Tableau de bord - Responsable Entrepôt')
@section('page-title', 'Tableau de bord')

@section('content')
<style>
    .responsable-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .responsable-header {
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(15, 118, 110, 0.2);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-info h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .header-info p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }

    .status-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(15, 118, 110, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
    }

    .stat-icon.primary { background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); }
    .stat-icon.success { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
    .stat-icon.warning { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); }
    .stat-icon.info { background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); }

    .stat-title {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .stat-subtitle {
        font-size: 0.875rem;
        color: #059669;
        font-weight: 600;
    }

    .alerts-section {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-item {
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        border-left: 4px solid;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .alert-item.warning {
        background: #fef3c7;
        border-left-color: #f59e0b;
    }

    .alert-item.danger {
        background: #fee2e2;
        border-left-color: #dc2626;
    }

    .alert-icon {
        font-size: 1.5rem;
        margin-top: 0.25rem;
    }

    .alert-content h4 {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .alert-content p {
        margin: 0;
        font-size: 0.875rem;
    }

    .alert-time {
        font-size: 0.75rem;
        opacity: 0.7;
    }

    .recent-movements {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .movement-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid #059669;
        transition: transform 0.2s ease;
    }

    .movement-item:hover {
        transform: translateX(5px);
    }

    .movement-item.out {
        border-left-color: #dc2626;
    }

    .movement-info h4 {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .movement-amount {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .movement-amount.in {
        color: #059669;
    }

    .movement-amount.out {
        color: #dc2626;
    }

    .movement-time {
        text-align: right;
        font-size: 0.875rem;
    }

    .movement-time.recent {
        color: #059669;
    }

    .movement-time.old {
        color: #dc2626;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .action-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(15, 118, 110, 0.1);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .action-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }

    .action-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .action-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .action-btn {
        background: linear-gradient(135deg, #0f766e 0%, #115e59 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 600;
        transition: transform 0.2s ease;
        display: inline-block;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        color: white;
    }

    @media (max-width: 768px) {
        .responsable-container {
            padding: 1rem;
        }
        
        .header-content {
            flex-direction: column;
            text-align: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="responsable-container">
    <!-- En-tête avec informations de l'entrepôt -->
    <div class="responsable-header">
        <div class="header-content">
            <div class="header-info">
                <h1>🏢 {{ $warehouseData['name'] ?? 'Entrepôt CSAR' }}</h1>
                <p>Type: {{ $warehouseData['type'] ?? 'Denrées alimentaires et matériel humanitaire' }}</p>
                <p>📍 Adresse: {{ $warehouseData['location'] ?? 'Zone Industrielle, Dakar' }}</p>
            </div>
            <div class="status-badge">
                ✅ {{ $warehouseData['status'] ?? 'Actif' }}
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div>
                    <div class="stat-title">Capacité totale</div>
                    <div class="stat-value">{{ $warehouseData['capacity'] ?? '5,000' }}</div>
                    <div class="stat-subtitle">tonnes</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <div class="stat-title">Total produits</div>
                    <div class="stat-value">{{ $stockStats['total_items'] ?? '0' }}</div>
                    <div class="stat-subtitle">produits</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon warning">
                    <i class="fas fa-archive"></i>
                </div>
                <div>
                    <div class="stat-title">Stock total</div>
                    <div class="stat-value">{{ number_format($stockStats['total_stock'] ?? 0) }}</div>
                    <div class="stat-subtitle">unités</div>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon info">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <div class="stat-title">Mouvements</div>
                    <div class="stat-value">24</div>
                    <div class="stat-subtitle">ce mois</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes de stock -->
    <div class="alerts-section">
        <h3 class="section-title">
            <i class="fas fa-exclamation-triangle"></i>
            Alertes de stock
        </h3>
        
        <div class="alert-item warning">
            <div class="alert-icon">⚠️</div>
            <div class="alert-content">
                <h4>Attention - Stock critique</h4>
                <p>Le stock de riz est en dessous du seuil critique (500 kg restants)</p>
                <div class="alert-time">Il y a 2 heures</div>
            </div>
        </div>
        
        <div class="alert-item danger">
            <div class="alert-icon">🚨</div>
            <div class="alert-content">
                <h4>Urgent - Stock épuisé</h4>
                <p>Stock de lait en poudre épuisé - Réapprovisionnement requis</p>
                <div class="alert-time">Il y a 1 jour</div>
            </div>
        </div>
    </div>

    <!-- Mouvements récents -->
    <div class="recent-movements">
        <h3 class="section-title">
            <i class="fas fa-exchange-alt"></i>
            Mouvements récents
        </h3>
        
        <div class="movement-item">
            <div class="movement-info">
                <h4>Entrée - Riz</h4>
                <div class="movement-amount in">+2,000 kg</div>
            </div>
            <div class="movement-time recent">Aujourd'hui 14:30</div>
        </div>
        
        <div class="movement-item out">
            <div class="movement-info">
                <h4>Sortie - Lait</h4>
                <div class="movement-amount out">-500 kg</div>
            </div>
            <div class="movement-time old">Hier 16:45</div>
        </div>
        
        <div class="movement-item">
            <div class="movement-info">
                <h4>Entrée - Matériel</h4>
                <div class="movement-amount in">+50 unités</div>
            </div>
            <div class="movement-time recent">Hier 10:15</div>
        </div>
        
        <div class="movement-item out">
            <div class="movement-info">
                <h4>Sortie - Couvertures</h4>
                <div class="movement-amount out">-200 unités</div>
            </div>
            <div class="movement-time old">Il y a 2 jours</div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="quick-actions">
        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-plus"></i>
            </div>
            <h4 class="action-title">Ajouter du stock</h4>
            <p class="action-description">Enregistrer une nouvelle entrée de stock</p>
            <a href="{{ route('responsable.stock.create') }}" class="action-btn">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-minus"></i>
            </div>
            <h4 class="action-title">Sortie de stock</h4>
            <p class="action-description">Enregistrer une sortie de stock</p>
            <a href="{{ route('responsable.stock.out') }}" class="action-btn">
                <i class="fas fa-minus"></i> Sortie
            </a>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-list"></i>
            </div>
            <h4 class="action-title">Gérer le stock</h4>
            <p class="action-description">Voir et gérer l'inventaire</p>
            <a href="{{ route('responsable.stock') }}" class="action-btn">
                <i class="fas fa-list"></i> Inventaire
            </a>
        </div>

        <div class="action-card">
            <div class="action-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h4 class="action-title">Mouvements</h4>
            <p class="action-description">Historique des mouvements</p>
            <a href="{{ route('responsable.movements') }}" class="action-btn">
                <i class="fas fa-chart-bar"></i> Historique
            </a>
        </div>
    </div>
</div>

<style>
/* RESPONSIVE - DASHBOARDS INTERNES */
@media (max-width: 1200px) {
    .dashboard-grid { grid-template-columns: repeat(2, 1fr) !important; }
    .stats-grid { grid-template-columns: repeat(2, 1fr) !important; }
}

@media (max-width: 992px) {
    .dashboard-grid { grid-template-columns: 1fr !important; }
    .widget-card { padding: 1.5rem !important; }
    .chart-container { height: 300px !important; }
    .sidebar-content { width: 100% !important; margin-top: 2rem; }
}

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr !important; }
    .stat-card { padding: 1.5rem !important; margin-bottom: 1rem; }
    .page-header { flex-direction: column !important; align-items: flex-start !important; gap: 1rem; }
    .page-title { font-size: 1.5rem !important; }
    .page-actions { width: 100%; flex-direction: column; gap: 0.5rem; }
    .page-actions .btn { width: 100%; justify-content: center; }
    .chart-container { height: 250px !important; }
    .table-responsive { overflow-x: auto; }
    .table { font-size: 0.85rem; }
    .card-body { padding: 1rem !important; }
}

@media (max-width: 480px) {
    .page-title { font-size: 1.3rem !important; }
    .stat-value { font-size: 1.8rem !important; }
    .stat-card { padding: 1rem !important; }
}
</style>
@endsection 