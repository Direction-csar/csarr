@extends('layouts.public')

@section('title', 'Répartition Régionale - SIM CSAR')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 0;">
    <!-- En-tête -->
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 40px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('sim.dashboard') }}" style="color: white; text-decoration: none; margin-right: 16px;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <h1 style="font-size: 2.5rem; font-weight: 700; margin: 0;">🗺️ Répartition Régionale</h1>
        </div>
        <p style="font-size: 1.2rem; opacity: 0.9; margin: 0;">Analyse des disparités de prix entre les différentes régions du Sénégal</p>
    </div>

    <!-- Carte du Sénégal -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Carte du Sénégal</h2>
        
        <div style="background: #f0fdf4; border-radius: 12px; padding: 40px; text-align: center; border: 2px solid #bbf7d0;">
            <div style="font-size: 8rem; margin-bottom: 24px;">🇸🇳</div>
            <div style="font-weight: 600; color: #047857; font-size: 1.5rem; margin-bottom: 12px;">République du Sénégal</div>
            <div style="color: #059669; font-size: 1.1rem; margin-bottom: 20px;">Analyse des disparités régionales des prix</div>
            
            <!-- Légende -->
            <div style="display: flex; justify-content: center; gap: 32px; margin-top: 24px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #dc2626; border-radius: 4px;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Prix élevés</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #f59e0b; border-radius: 4px;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Prix moyens</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #059669; border-radius: 4px;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Prix bas</span>
                </div>
            </div>
        </div>
    </div>

    @if($latestReport && $latestReport->key_trends && isset($latestReport->key_trends['disparites_regionales']))
        <!-- Disparités observées -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Disparités observées</h2>
            
            <div style="background: #f8fafc; border-radius: 12px; padding: 24px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.3rem; font-weight: 600; margin-bottom: 16px;">Analyse des écarts régionaux</h3>
                <p style="color: #374151; line-height: 1.6; font-size: 1.1rem; margin: 0;">{{ $latestReport->key_trends['disparites_regionales'] }}</p>
            </div>
        </div>
    @endif

    <!-- Zones à surveiller -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Zones à surveiller</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #fef2f2; border-radius: 12px; padding: 24px; border-left: 4px solid #dc2626;">
                <h3 style="color: #dc2626; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🚨 Régions à prix élevés</h3>
                <p style="color: #374151; margin-bottom: 16px;">Zones où les prix des produits de base sont significativement plus élevés que la moyenne nationale.</p>
                <div style="background: #fecaca; border-radius: 8px; padding: 12px;">
                    <div style="font-weight: 600; color: #dc2626; margin-bottom: 8px;">Produits concernés :</div>
                    <ul style="color: #374151; margin: 0; padding-left: 20px;">
                        <li>Mil</li>
                        <li>Riz local</li>
                        <li>Arachide</li>
                        <li>Tomate</li>
                    </ul>
                </div>
            </div>

            <div style="background: #fef2f2; border-radius: 12px; padding: 24px; border-left: 4px solid #dc2626;">
                <h3 style="color: #dc2626; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">⚠️ Pression sur l'approvisionnement</h3>
                <p style="color: #374151; margin-bottom: 16px;">Régions où l'approvisionnement est insuffisant, particulièrement en période de soudure.</p>
                <div style="background: #fecaca; border-radius: 8px; padding: 12px;">
                    <div style="font-weight: 600; color: #dc2626; margin-bottom: 8px;">Facteurs identifiés :</div>
                    <ul style="color: #374151; margin: 0; padding-left: 20px;">
                        <li>Période de soudure</li>
                        <li>Difficultés de transport</li>
                        <li>Récoltes insuffisantes</li>
                        <li>Demande élevée</li>
                    </ul>
                </div>
            </div>

            <div style="background: #f0fdf4; border-radius: 12px; padding: 24px; border-left: 4px solid #059669;">
                <h3 style="color: #059669; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">✅ Zones d'approvisionnement stable</h3>
                <p style="color: #374151; margin-bottom: 16px;">Régions où l'approvisionnement et les prix restent relativement stables.</p>
                <div style="background: #bbf7d0; border-radius: 8px; padding: 12px;">
                    <div style="font-weight: 600; color: #047857; margin-bottom: 8px;">Caractéristiques :</div>
                    <ul style="color: #374151; margin: 0; padding-left: 20px;">
                        <li>Production locale suffisante</li>
                        <li>Infrastructures de transport</li>
                        <li>Marchés bien approvisionnés</li>
                        <li>Prix compétitifs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyse par région -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Analyse par région</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
            <!-- Dakar -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🏙️ Dakar</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix moyens</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Capitale économique avec des prix généralement stables grâce aux importations.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Importations, infrastructures</div>
                    <div><strong>Défis :</strong> Dépendance aux importations</div>
                </div>
            </div>

            <!-- Thiès -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🌾 Thiès</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #059669; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix bas</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Zone agricole importante avec une production locale diversifiée.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Production locale, diversité</div>
                    <div><strong>Défis :</strong> Saisonnalité</div>
                </div>
            </div>

            <!-- Saint-Louis -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🌊 Saint-Louis</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #dc2626; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix élevés</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Région éloignée avec des coûts de transport élevés.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Pêche, riziculture</div>
                    <div><strong>Défis :</strong> Transport, isolement</div>
                </div>
            </div>

            <!-- Kaolack -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🥜 Kaolack</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix moyens</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Bassin arachidier avec une production importante d'arachides.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Arachides, céréales</div>
                    <div><strong>Défis :</strong> Mono-culture</div>
                </div>
            </div>

            <!-- Tambacounda -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🌿 Tambacounda</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #dc2626; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix élevés</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Région enclavée avec des difficultés d'approvisionnement.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Élevage, céréales</div>
                    <div><strong>Défis :</strong> Enclavement, transport</div>
                </div>
            </div>

            <!-- Ziguinchor -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🌴 Ziguinchor</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #059669; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix bas</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Région agricole avec une production diversifiée et des prix compétitifs.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Diversité agricole, riz</div>
                    <div><strong>Défis :</strong> Accès aux marchés</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Recommandations</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🚚 Améliorer le transport</h3>
                <p style="color: #374151; font-size: 0.95rem;">Renforcer les infrastructures de transport pour réduire les coûts et améliorer l'approvisionnement des régions enclavées.</p>
            </div>

            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🏪 Développer les marchés</h3>
                <p style="color: #374151; font-size: 0.95rem;">Créer et moderniser les marchés locaux pour faciliter l'échange et réduire les disparités de prix.</p>
            </div>

            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">📊 Surveillance continue</h3>
                <p style="color: #374151; font-size: 0.95rem;">Maintenir un suivi régulier des prix par région pour identifier rapidement les anomalies.</p>
            </div>

            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🤝 Coordination régionale</h3>
                <p style="color: #374151; font-size: 0.95rem;">Encourager la coordination entre les régions pour optimiser l'approvisionnement et réduire les disparités.</p>
            </div>
        </div>
    </div>

    <!-- Retour au tableau de bord -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('sim.dashboard') }}" style="background: #059669; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
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

@section('title', 'Répartition Régionale - SIM CSAR')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 0;">
    <!-- En-tête -->
    <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 16px; padding: 40px; margin-bottom: 32px;">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <a href="{{ route('sim.dashboard') }}" style="color: white; text-decoration: none; margin-right: 16px;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <h1 style="font-size: 2.5rem; font-weight: 700; margin: 0;">🗺️ Répartition Régionale</h1>
        </div>
        <p style="font-size: 1.2rem; opacity: 0.9; margin: 0;">Analyse des disparités de prix entre les différentes régions du Sénégal</p>
    </div>

    <!-- Carte du Sénégal -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Carte du Sénégal</h2>
        
        <div style="background: #f0fdf4; border-radius: 12px; padding: 40px; text-align: center; border: 2px solid #bbf7d0;">
            <div style="font-size: 8rem; margin-bottom: 24px;">🇸🇳</div>
            <div style="font-weight: 600; color: #047857; font-size: 1.5rem; margin-bottom: 12px;">République du Sénégal</div>
            <div style="color: #059669; font-size: 1.1rem; margin-bottom: 20px;">Analyse des disparités régionales des prix</div>
            
            <!-- Légende -->
            <div style="display: flex; justify-content: center; gap: 32px; margin-top: 24px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #dc2626; border-radius: 4px;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Prix élevés</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #f59e0b; border-radius: 4px;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Prix moyens</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 20px; height: 20px; background: #059669; border-radius: 4px;"></div>
                    <span style="color: #374151; font-size: 0.9rem;">Prix bas</span>
                </div>
            </div>
        </div>
    </div>

    @if($latestReport && $latestReport->key_trends && isset($latestReport->key_trends['disparites_regionales']))
        <!-- Disparités observées -->
        <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
            <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Disparités observées</h2>
            
            <div style="background: #f8fafc; border-radius: 12px; padding: 24px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.3rem; font-weight: 600; margin-bottom: 16px;">Analyse des écarts régionaux</h3>
                <p style="color: #374151; line-height: 1.6; font-size: 1.1rem; margin: 0;">{{ $latestReport->key_trends['disparites_regionales'] }}</p>
            </div>
        </div>
    @endif

    <!-- Zones à surveiller -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Zones à surveiller</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #fef2f2; border-radius: 12px; padding: 24px; border-left: 4px solid #dc2626;">
                <h3 style="color: #dc2626; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🚨 Régions à prix élevés</h3>
                <p style="color: #374151; margin-bottom: 16px;">Zones où les prix des produits de base sont significativement plus élevés que la moyenne nationale.</p>
                <div style="background: #fecaca; border-radius: 8px; padding: 12px;">
                    <div style="font-weight: 600; color: #dc2626; margin-bottom: 8px;">Produits concernés :</div>
                    <ul style="color: #374151; margin: 0; padding-left: 20px;">
                        <li>Mil</li>
                        <li>Riz local</li>
                        <li>Arachide</li>
                        <li>Tomate</li>
                    </ul>
                </div>
            </div>

            <div style="background: #fef2f2; border-radius: 12px; padding: 24px; border-left: 4px solid #dc2626;">
                <h3 style="color: #dc2626; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">⚠️ Pression sur l'approvisionnement</h3>
                <p style="color: #374151; margin-bottom: 16px;">Régions où l'approvisionnement est insuffisant, particulièrement en période de soudure.</p>
                <div style="background: #fecaca; border-radius: 8px; padding: 12px;">
                    <div style="font-weight: 600; color: #dc2626; margin-bottom: 8px;">Facteurs identifiés :</div>
                    <ul style="color: #374151; margin: 0; padding-left: 20px;">
                        <li>Période de soudure</li>
                        <li>Difficultés de transport</li>
                        <li>Récoltes insuffisantes</li>
                        <li>Demande élevée</li>
                    </ul>
                </div>
            </div>

            <div style="background: #f0fdf4; border-radius: 12px; padding: 24px; border-left: 4px solid #059669;">
                <h3 style="color: #059669; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">✅ Zones d'approvisionnement stable</h3>
                <p style="color: #374151; margin-bottom: 16px;">Régions où l'approvisionnement et les prix restent relativement stables.</p>
                <div style="background: #bbf7d0; border-radius: 8px; padding: 12px;">
                    <div style="font-weight: 600; color: #047857; margin-bottom: 8px;">Caractéristiques :</div>
                    <ul style="color: #374151; margin: 0; padding-left: 20px;">
                        <li>Production locale suffisante</li>
                        <li>Infrastructures de transport</li>
                        <li>Marchés bien approvisionnés</li>
                        <li>Prix compétitifs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyse par région -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Analyse par région</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
            <!-- Dakar -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🏙️ Dakar</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix moyens</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Capitale économique avec des prix généralement stables grâce aux importations.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Importations, infrastructures</div>
                    <div><strong>Défis :</strong> Dépendance aux importations</div>
                </div>
            </div>

            <!-- Thiès -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🌾 Thiès</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #059669; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix bas</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Zone agricole importante avec une production locale diversifiée.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Production locale, diversité</div>
                    <div><strong>Défis :</strong> Saisonnalité</div>
                </div>
            </div>

            <!-- Saint-Louis -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🌊 Saint-Louis</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #dc2626; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix élevés</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Région éloignée avec des coûts de transport élevés.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Pêche, riziculture</div>
                    <div><strong>Défis :</strong> Transport, isolement</div>
                </div>
            </div>

            <!-- Kaolack -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🥜 Kaolack</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #f59e0b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix moyens</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Bassin arachidier avec une production importante d'arachides.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Arachides, céréales</div>
                    <div><strong>Défis :</strong> Mono-culture</div>
                </div>
            </div>

            <!-- Tambacounda -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🌿 Tambacounda</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #dc2626; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix élevés</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Région enclavée avec des difficultés d'approvisionnement.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Élevage, céréales</div>
                    <div><strong>Défis :</strong> Enclavement, transport</div>
                </div>
            </div>

            <!-- Ziguinchor -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb;">
                <h3 style="color: #374151; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🌴 Ziguinchor</h3>
                <div style="margin-bottom: 12px;">
                    <span style="background: #059669; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">Prix bas</span>
                </div>
                <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 12px;">Région agricole avec une production diversifiée et des prix compétitifs.</p>
                <div style="font-size: 0.85rem; color: #374151;">
                    <div><strong>Points forts :</strong> Diversité agricole, riz</div>
                    <div><strong>Défis :</strong> Accès aux marchés</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 16px #e5e7eb; padding: 32px; margin-bottom: 32px;">
        <h2 style="color: #059669; font-size: 1.8rem; font-weight: 700; margin-bottom: 24px;">Recommandations</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🚚 Améliorer le transport</h3>
                <p style="color: #374151; font-size: 0.95rem;">Renforcer les infrastructures de transport pour réduire les coûts et améliorer l'approvisionnement des régions enclavées.</p>
            </div>

            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🏪 Développer les marchés</h3>
                <p style="color: #374151; font-size: 0.95rem;">Créer et moderniser les marchés locaux pour faciliter l'échange et réduire les disparités de prix.</p>
            </div>

            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">📊 Surveillance continue</h3>
                <p style="color: #374151; font-size: 0.95rem;">Maintenir un suivi régulier des prix par région pour identifier rapidement les anomalies.</p>
            </div>

            <div style="background: #f0fdf4; border-radius: 12px; padding: 20px; border-left: 4px solid #059669;">
                <h3 style="color: #047857; font-size: 1.2rem; font-weight: 600; margin-bottom: 12px;">🤝 Coordination régionale</h3>
                <p style="color: #374151; font-size: 0.95rem;">Encourager la coordination entre les régions pour optimiser l'approvisionnement et réduire les disparités.</p>
            </div>
        </div>
    </div>

    <!-- Retour au tableau de bord -->
    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('sim.dashboard') }}" style="background: #059669; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 8px;">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
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
 
 
 
 
 
 