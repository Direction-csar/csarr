@extends('layouts.public')

@section('title', 'Magasins de Stockage CSAR - CSAR')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 0;">
    <!-- En-tête -->
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 40px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('sim.index') }}" style="color: white; text-decoration: none; margin-right: 16px;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <h1 style="font-size: 2.5rem; font-weight: 700; margin: 0;">Magasins de Stockage CSAR</h1>
        </div>
        <p style="font-size: 1.2rem; opacity: 0.9; margin: 0;">Notre réseau de magasins de stockage stratégiques assure le stockage et la distribution des denrées alimentaires</p>
    </div>

    <!-- Statistiques globales -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">23</div>
            <div style="color: #6b7280; font-size: 0.9rem;">Magasins opérationnels</div>
        </div>
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">2,450</div>
            <div style="color: #6b7280; font-size: 0.9rem;">Tonnes de capacité</div>
        </div>
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">1,847</div>
            <div style="color: #6b7280; font-size: 0.9rem;">Tonnes stockées</div>
        </div>
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">75%</div>
            <div style="color: #6b7280; font-size: 0.9rem;">Taux d'occupation</div>
        </div>
    </div>

    <!-- Carte des magasins -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Réseau des Magasins</h2>
        <div style="background: #f0fdf4; border-radius: 12px; padding: 40px; text-align: center; border: 2px solid #bbf7d0;">
            <div style="font-size: 4rem; margin-bottom: 20px;">🏗️</div>
            <div style="font-weight: 600; color: #047857; font-size: 1.5rem; margin-bottom: 12px;">République du Sénégal</div>
            <div style="color: #059669; font-size: 1.1rem; margin-bottom: 20px;">Carte du réseau de magasins de stockage CSAR</div>
            <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 16px; height: 16px; background: #dc2626; border-radius: 50%;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Magasins principaux</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 16px; height: 16px; background: #f59e0b; border-radius: 50%;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Magasins secondaires</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 16px; height: 16px; background: #059669; border-radius: 50%;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Centres de distribution</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Types de magasins -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Types de Magasins</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #fef2f2; border-radius: 12px; padding: 20px; border-left: 4px solid #dc2626;">
                <h3 style="color: #dc2626; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🏢 Magasins Principaux</h3>
                <p style="color: #374151; line-height: 1.5; margin-bottom: 12px;">Infrastructures de grande capacité (200-500 tonnes) situées dans les capitales régionales.</p>
                <div style="color: #6b7280; font-size: 0.9rem;">8 magasins - 2,800 tonnes de capacité</div>
            </div>
            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🏪 Magasins Secondaires</h3>
                <p style="color: #374151; line-height: 1.5; margin-bottom: 12px;">Structures de capacité moyenne (50-150 tonnes) dans les départements.</p>
                <div style="color: #6b7280; font-size: 0.9rem;">12 magasins - 1,200 tonnes de capacité</div>
            </div>
            <div style="background: #fef3c7; border-radius: 12px; padding: 20px; border-left: 4px solid #f59e0b;">
                <h3 style="color: #d97706; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🚚 Centres de Distribution</h3>
                <p style="color: #374151; line-height: 1.5; margin-bottom: 12px;">Points de distribution temporaires pour les opérations d'urgence.</p>
                <div style="color: #6b7280; font-size: 0.9rem;">3 centres - 450 tonnes de capacité</div>
            </div>
        </div>
    </div>

    <!-- Magasins par région -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Magasins par Région</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Dakar</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 2 centres de distribution</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 650 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Thiès</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 1 magasin secondaire</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 350 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Saint-Louis</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 2 magasins secondaires</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 400 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Kaolack</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 1 magasin secondaire</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 300 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Tambacounda</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 1 magasin secondaire</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 250 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Ziguinchor</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 1 magasin secondaire</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 200 tonnes</div>
            </div>
        </div>
    </div>

    <!-- Équipements et sécurité -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Équipements et Sécurité</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 2rem; margin-bottom: 8px;">🌡️</div>
                <div style="font-weight: 600; color: #374151;">Contrôle de température</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Maintenance optimale</div>
            </div>
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 2rem; margin-bottom: 8px;">🔒</div>
                <div style="font-weight: 600; color: #374151;">Système de sécurité</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Surveillance 24h/24</div>
            </div>
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 2rem; margin-bottom: 8px;">🚛</div>
                <div style="font-weight: 600; color: #374151;">Équipements de manutention</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Chariots élévateurs</div>
            </div>
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 2rem; margin-bottom: 8px;">📊</div>
                <div style="font-weight: 600; color: #374151;">Gestion informatisée</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Suivi en temps réel</div>
            </div>
        </div>
    </div>

    <!-- Retour au tableau de bord -->
    <div style="text-align: center;">
        <a href="{{ route('sim.dashboard') }}" style="background: #059669; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Retour au tableau de bord
        </a>
    </div>
</div>

<style>
/* RESPONSIVE - PAGES SIM */
@media (max-width: 1024px) {
    .sim-grid, .data-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 1.5rem; }
    .chart-container { height: 300px !important; }
}

@media (max-width: 768px) {
    .sim-grid, .data-grid { grid-template-columns: 1fr !important; gap: 1rem; }
    .sim-hero, .dashboard-hero { min-height: 200px !important; padding: 40px 0 !important; }
    .sim-hero h1, .dashboard-hero h1 { font-size: 1.8rem !important; }
    .filter-bar { flex-direction: column !important; }
    .filter-bar > * { width: 100% !important; }
    .chart-container { height: 250px !important; }
    .stat-card { padding: 1.5rem !important; }
    .data-table-wrapper { overflow-x: auto; }
    .data-table { min-width: 600px; }
}

@media (max-width: 480px) {
    .sim-hero h1 { font-size: 1.5rem !important; }
    .stat-card { padding: 1rem !important; }
    .btn-group { flex-direction: column !important; gap: 0.5rem; }
    .btn-group .btn { width: 100%; }
}
</style>
@endsection 

@section('title', 'Magasins de Stockage CSAR - CSAR')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 0;">
    <!-- En-tête -->
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 40px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('sim.index') }}" style="color: white; text-decoration: none; margin-right: 16px;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <h1 style="font-size: 2.5rem; font-weight: 700; margin: 0;">Magasins de Stockage CSAR</h1>
        </div>
        <p style="font-size: 1.2rem; opacity: 0.9; margin: 0;">Notre réseau de magasins de stockage stratégiques assure le stockage et la distribution des denrées alimentaires</p>
    </div>

    <!-- Statistiques globales -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">23</div>
            <div style="color: #6b7280; font-size: 0.9rem;">Magasins opérationnels</div>
        </div>
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">2,450</div>
            <div style="color: #6b7280; font-size: 0.9rem;">Tonnes de capacité</div>
        </div>
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">1,847</div>
            <div style="color: #6b7280; font-size: 0.9rem;">Tonnes stockées</div>
        </div>
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 24px; text-align: center;">
            <div style="font-size: 2rem; font-weight: 800; color: #059669; margin-bottom: 8px;">75%</div>
            <div style="color: #6b7280; font-size: 0.9rem;">Taux d'occupation</div>
        </div>
    </div>

    <!-- Carte des magasins -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Réseau des Magasins</h2>
        <div style="background: #f0fdf4; border-radius: 12px; padding: 40px; text-align: center; border: 2px solid #bbf7d0;">
            <div style="font-size: 4rem; margin-bottom: 20px;">🏗️</div>
            <div style="font-weight: 600; color: #047857; font-size: 1.5rem; margin-bottom: 12px;">République du Sénégal</div>
            <div style="color: #059669; font-size: 1.1rem; margin-bottom: 20px;">Carte du réseau de magasins de stockage CSAR</div>
            <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 16px; height: 16px; background: #dc2626; border-radius: 50%;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Magasins principaux</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 16px; height: 16px; background: #f59e0b; border-radius: 50%;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Magasins secondaires</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 16px; height: 16px; background: #059669; border-radius: 50%;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Centres de distribution</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Types de magasins -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Types de Magasins</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #fef2f2; border-radius: 12px; padding: 20px; border-left: 4px solid #dc2626;">
                <h3 style="color: #dc2626; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🏢 Magasins Principaux</h3>
                <p style="color: #374151; line-height: 1.5; margin-bottom: 12px;">Infrastructures de grande capacité (200-500 tonnes) situées dans les capitales régionales.</p>
                <div style="color: #6b7280; font-size: 0.9rem;">8 magasins - 2,800 tonnes de capacité</div>
            </div>
            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🏪 Magasins Secondaires</h3>
                <p style="color: #374151; line-height: 1.5; margin-bottom: 12px;">Structures de capacité moyenne (50-150 tonnes) dans les départements.</p>
                <div style="color: #6b7280; font-size: 0.9rem;">12 magasins - 1,200 tonnes de capacité</div>
            </div>
            <div style="background: #fef3c7; border-radius: 12px; padding: 20px; border-left: 4px solid #f59e0b;">
                <h3 style="color: #d97706; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🚚 Centres de Distribution</h3>
                <p style="color: #374151; line-height: 1.5; margin-bottom: 12px;">Points de distribution temporaires pour les opérations d'urgence.</p>
                <div style="color: #6b7280; font-size: 0.9rem;">3 centres - 450 tonnes de capacité</div>
            </div>
        </div>
    </div>

    <!-- Magasins par région -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Magasins par Région</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Dakar</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 2 centres de distribution</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 650 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Thiès</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 1 magasin secondaire</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 350 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Saint-Louis</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 2 magasins secondaires</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 400 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Kaolack</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 1 magasin secondaire</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 300 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Tambacounda</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 1 magasin secondaire</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 250 tonnes</div>
            </div>
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h4 style="color: #047857; font-size: 1.1rem; font-weight: 600; margin-bottom: 8px;">Ziguinchor</h4>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 8px;">Magasin principal + 1 magasin secondaire</p>
                <div style="color: #374151; font-size: 0.9rem;">Capacité: 200 tonnes</div>
            </div>
        </div>
    </div>

    <!-- Équipements et sécurité -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Équipements et Sécurité</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 2rem; margin-bottom: 8px;">🌡️</div>
                <div style="font-weight: 600; color: #374151;">Contrôle de température</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Maintenance optimale</div>
            </div>
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 2rem; margin-bottom: 8px;">🔒</div>
                <div style="font-weight: 600; color: #374151;">Système de sécurité</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Surveillance 24h/24</div>
            </div>
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 2rem; margin-bottom: 8px;">🚛</div>
                <div style="font-weight: 600; color: #374151;">Équipements de manutention</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Chariots élévateurs</div>
            </div>
            <div style="background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; border: 1px solid #e5e7eb;">
                <div style="font-size: 2rem; margin-bottom: 8px;">📊</div>
                <div style="font-weight: 600; color: #374151;">Gestion informatisée</div>
                <div style="color: #6b7280; font-size: 0.9rem;">Suivi en temps réel</div>
            </div>
        </div>
    </div>

    <!-- Retour au tableau de bord -->
    <div style="text-align: center;">
        <a href="{{ route('sim.dashboard') }}" style="background: #059669; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Retour au tableau de bord
        </a>
    </div>
</div>

<style>
/* RESPONSIVE - PAGES SIM */
@media (max-width: 1024px) {
    .sim-grid, .data-grid { grid-template-columns: repeat(2, 1fr) !important; gap: 1.5rem; }
    .chart-container { height: 300px !important; }
}

@media (max-width: 768px) {
    .sim-grid, .data-grid { grid-template-columns: 1fr !important; gap: 1rem; }
    .sim-hero, .dashboard-hero { min-height: 200px !important; padding: 40px 0 !important; }
    .sim-hero h1, .dashboard-hero h1 { font-size: 1.8rem !important; }
    .filter-bar { flex-direction: column !important; }
    .filter-bar > * { width: 100% !important; }
    .chart-container { height: 250px !important; }
    .stat-card { padding: 1.5rem !important; }
    .data-table-wrapper { overflow-x: auto; }
    .data-table { min-width: 600px; }
}

@media (max-width: 480px) {
    .sim-hero h1 { font-size: 1.5rem !important; }
    .stat-card { padding: 1rem !important; }
    .btn-group { flex-direction: column !important; gap: 0.5rem; }
    .btn-group .btn { width: 100%; }
}
</style>
@endsection 
 
 
 
 
 
 