@extends('layouts.public')

@section('title', 'Rapports Officiels - CSAR')

@section('content')
<!-- Hero Section Modernisé -->
<section class="hero-section fade-in" style="background: linear-gradient(135deg, rgba(5, 150, 105, 0.95) 0%, rgba(4, 120, 87, 0.9) 50%, rgba(16, 185, 129, 0.9) 100%), url('{{ asset('img/1.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 60vh; display: flex; align-items: center; position: relative; overflow: hidden;">
    
    <!-- Grid pattern animé -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 60px 60px; animation: gridMove 20s linear infinite; opacity: 0.2;"></div>
    
    <!-- Particules flottantes -
    <div style="position: absolute; top: 15%; left: 10%; width: 8px; height: 8px; background: rgba(255,255,255,0.8); border-radius: 50%; animation: float 6s ease-in-out infinite; box-shadow: 0 0 20px rgba(255,255,255,0.6);"></div>
    <div style="position: absolute; top: 25%; right: 15%; width: 6px; height: 6px; background: rgba(255,255,255,0.7); border-radius: 50%; animation: float 8s ease-in-out infinite reverse; box-shadow: 0 0 15px rgba(255,255,255,0.6);"></div>
    <div style="position: absolute; bottom: 20%; left: 20%; width: 10px; height: 10px; background: rgba(255,255,255,0.6); border-radius: 50%; animation: float 7s ease-in-out infinite; box-shadow: 0 0 25px rgba(255,255,255,0.6);"></div>
    
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px; text-align: center; position: relative; z-index: 2;">
        <!-- Badge moderne -->
        <div style="display: inline-block; background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; padding: 12px 25px; margin-bottom: 30px;">
            <span style="color: #fff; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">📊 Rapports Officiels</span>
        </div>
        
        <h1 class="main-title" style="font-size: 4rem; font-weight: 900; color: #fff; margin-bottom: 25px; letter-spacing: -2px; line-height: 1.1; text-shadow: 0 6px 12px rgba(0,0,0,0.4);">
            Rapports Officiels
        </h1>
        <p class="main-subtitle" style="font-size: 1.4rem; color: rgba(255,255,255,0.9); max-width: 700px; margin: 0 auto; line-height: 1.7; text-shadow: 0 3px 6px rgba(0,0,0,0.3);">
            Consultez les rapports officiels et documents de référence du Commissariat à la Sécurité Alimentaire et à la Résilience
        </p>
    </div>
</section>

<!-- Section Filtres et Recherche -->
<section style="background: #fff; padding: 40px 0; border-bottom: 1px solid #e5e7eb;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: center; justify-content: space-between;">
            <!-- Barre de recherche -->
            <div style="flex: 1; min-width: 300px; max-width: 500px;">
                <div style="position: relative;">
                    <input type="text" id="searchReports" placeholder="Rechercher dans les rapports..." 
                           style="width: 100%; padding: 15px 50px 15px 20px; border: 2px solid #e5e7eb; border-radius: 15px; font-size: 1rem; transition: all 0.3s ease; background: #f8fafc;"
                           onfocus="this.style.borderColor='#059669'; this.style.background='#fff'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.1)'"
                           onblur="this.style.borderColor='#e5e7eb'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                    <div style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6b7280;">
                        <i class="fas fa-search" style="font-size: 16px;"></i>
                    </div>
                </div>
            </div>
            
            <!-- Filtres par type -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button class="filter-btn active" data-filter="all" 
                        style="padding: 12px 20px; border: 2px solid #059669; background: #059669; color: #fff; border-radius: 25px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    Tous
                </button>
                <button class="filter-btn" data-filter="annuel"
                        style="padding: 12px 20px; border: 2px solid #e5e7eb; background: #fff; color: #6b7280; border-radius: 25px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    Rapports Annuels
                </button>
                <button class="filter-btn" data-filter="social"
                        style="padding: 12px 20px; border: 2px solid #e5e7eb; background: #fff; color: #6b7280; border-radius: 25px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    Bilans Sociaux
                </button>
                <button class="filter-btn" data-filter="financier"
                        style="padding: 12px 20px; border: 2px solid #e5e7eb; background: #fff; color: #6b7280; border-radius: 25px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.3s ease;">
                    Rapports Financiers
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Rapports Section -->
<section class="reports-section fade-in" style="background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%); padding: 80px 0; position: relative; overflow: hidden;">
    <!-- Décoration arrière-plan -->
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: radial-gradient(circle at 20% 80%, rgba(5,150,105,0.03) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(59,130,246,0.03) 0%, transparent 50%);"></div>
    
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px; position: relative; z-index: 2;">
        <div style="text-align: center; margin-bottom: 60px;">
            <div style="display: inline-block; background: rgba(5,150,105,0.1); color: #059669; padding: 8px 20px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;">
                📊 Documents Officiels
            </div>
            <h2 class="section-title" style="font-size: 3rem; font-weight: 800; color: #1f2937; margin-bottom: 20px; background: linear-gradient(135deg, #1f2937 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                Rapports CSAR
            </h2>
            <p class="section-subtitle" style="font-size: 1.3rem; color: #6b7280; max-width: 700px; margin: 0 auto; line-height: 1.6;">
                Accédez aux rapports annuels, bilans sociaux et autres documents officiels du CSAR
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(380px, 1fr)); gap: 35px;">
            <!-- Rapport Annuel 2024 -->
            <div class="report-card modern-card" data-type="annuel" style="background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid rgba(229,231,235,0.6); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; text-decoration: none; display: block; cursor: pointer; backdrop-filter: blur(10px);">
                
                <!-- Effet de brillance au survol -->
                <div class="shine-effect" style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); transition: left 0.6s ease; z-index: 4; pointer-events: none;"></div>
                
                <!-- Header avec gradient -->
                <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 35px; position: relative; overflow: hidden;">
                    <!-- Décoration de fond -->
                    <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 1;"></div>
                    <div style="position: absolute; bottom: -30px; left: -30px; width: 80px; height: 80px; background: rgba(255,255,255,0.08); border-radius: 50%; z-index: 1;"></div>
                    
                    <div style="position: relative; z-index: 2;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                            <div style="display: flex; align-items: center;">
                                <div style="background: rgba(255,255,255,0.2); padding: 18px; border-radius: 18px; margin-right: 20px; backdrop-filter: blur(10px);">
                                    <i class="fas fa-file-pdf" style="font-size: 2.2rem; color: white;"></i>
                                </div>
                                <div>
                                    <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 8px;">
                                        Rapport Annuel 2024
                                    </h3>
                                    <p style="color: rgba(255,255,255,0.8); font-size: 0.95rem; font-weight: 500;">Document officiel</p>
                                </div>
                            </div>
                            <span style="background: rgba(255,255,255,0.2); color: white; font-size: 0.8rem; font-weight: 600; padding: 10px 18px; border-radius: 25px; backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(255,255,255,0.1);">
                                Nouveau
                            </span>
                        </div>
                        <p style="color: rgba(255,255,255,0.9); line-height: 1.6; margin-bottom: 0; font-size: 1rem;">
                            Rapport annuel complet du CSAR pour l'année 2024, incluant les activités, les réalisations et les perspectives pour l'année à venir.
                        </p>
                    </div>
                </div>
                
                <!-- Contenu -->
                <div style="padding: 35px;">
                    <!-- Métadonnées -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; color: #6b7280; font-size: 0.9rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(5,150,105,0.1) 0%, rgba(16,185,129,0.1) 100%); border: 2px solid rgba(5,150,105,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <i class="fas fa-calendar-alt" style="color: #059669; font-size: 16px;"></i>
                            </div>
                            <div>
                                <div style="color: #1f2937; font-size: 0.9rem; font-weight: 600;">Décembre 2024</div>
                                <div style="color: #9ca3af; font-size: 0.8rem;">Date de publication</div>
                            </div>
                        </div>
                        
                        <div style="background: rgba(5,150,105,0.1); color: #059669; padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-file-pdf" style="margin-right: 6px;"></i>
                            PDF - 2.5 MB
                        </div>
                    </div>
                    
                    <!-- Bouton de téléchargement -->
                    <a href="{{ asset('rapport/Rapport Annuel CSAR2024 VF.pdf') }}" 
                       target="_blank"
                       class="download-btn" style="display: inline-flex; align-items: center; justify-content: center; gap: 12px; width: 100%; padding: 18px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; text-decoration: none; border-radius: 15px; font-weight: 600; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);">
                        <i class="fas fa-download" style="font-size: 16px;"></i>
                        Télécharger le rapport
                    </a>
                </div>
            </div>

            <!-- Bilan Social 2024 -->
            <div class="report-card modern-card" data-type="social" style="background: #fff; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.08); border: 1px solid rgba(229,231,235,0.6); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; text-decoration: none; display: block; cursor: pointer; backdrop-filter: blur(10px);">
                
                <!-- Effet de brillance au survol -->
                <div class="shine-effect" style="position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); transition: left 0.6s ease; z-index: 4; pointer-events: none;"></div>
                
                <!-- Header avec gradient -->
                <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 35px; position: relative; overflow: hidden;">
                    <!-- Décoration de fond -->
                    <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%; z-index: 1;"></div>
                    <div style="position: absolute; bottom: -30px; left: -30px; width: 80px; height: 80px; background: rgba(255,255,255,0.08); border-radius: 50%; z-index: 1;"></div>
                    
                    <div style="position: relative; z-index: 2;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                            <div style="display: flex; align-items: center;">
                                <div style="background: rgba(255,255,255,0.2); padding: 18px; border-radius: 18px; margin-right: 20px; backdrop-filter: blur(10px);">
                                    <i class="fas fa-users" style="font-size: 2.2rem; color: white;"></i>
                                </div>
                                <div>
                                    <h3 style="font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 8px;">
                                        Bilan Social 2024
                                    </h3>
                                    <p style="color: rgba(255,255,255,0.8); font-size: 0.95rem; font-weight: 500;">Ressources humaines</p>
                                </div>
                            </div>
                            <span style="background: rgba(255,255,255,0.2); color: white; font-size: 0.8rem; font-weight: 600; padding: 10px 18px; border-radius: 25px; backdrop-filter: blur(10px); box-shadow: 0 4px 15px rgba(255,255,255,0.1);">
                                Nouveau
                            </span>
                        </div>
                        <p style="color: rgba(255,255,255,0.9); line-height: 1.6; margin-bottom: 0; font-size: 1rem;">
                            Bilan social détaillé présentant les effectifs, les formations, les conditions de travail et les perspectives RH du CSAR.
                        </p>
                    </div>
                </div>
                
                <!-- Contenu -->
                <div style="padding: 35px;">
                    <!-- Métadonnées -->
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                        <div style="display: flex; align-items: center; color: #6b7280; font-size: 0.9rem;">
                            <div style="width: 40px; height: 40px; background: linear-gradient(135deg, rgba(5,150,105,0.1) 0%, rgba(16,185,129,0.1) 100%); border: 2px solid rgba(5,150,105,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <i class="fas fa-calendar-alt" style="color: #059669; font-size: 16px;"></i>
                            </div>
                            <div>
                                <div style="color: #1f2937; font-size: 0.9rem; font-weight: 600;">Décembre 2024</div>
                                <div style="color: #9ca3af; font-size: 0.8rem;">Date de publication</div>
                            </div>
                        </div>
                        
                        <div style="background: rgba(5,150,105,0.1); color: #059669; padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-file-pdf" style="margin-right: 6px;"></i>
                            PDF - 1.8 MB
                        </div>
                    </div>
                    
                    <!-- Bouton de téléchargement -->
                    <a href="{{ asset('rapport/BILAN SOCIAL CSAR2024 VF.pdf') }}" 
                       target="_blank"
                       class="download-btn" style="display: inline-flex; align-items: center; justify-content: center; gap: 12px; width: 100%; padding: 18px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; text-decoration: none; border-radius: 15px; font-weight: 600; font-size: 1rem; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);">
                        <i class="fas fa-download" style="font-size: 16px;"></i>
                        Télécharger le rapport
                    </a>
                </div>
            </div>
        </div>


        <!-- Informations Section -->
        <div class="info-section fade-in" style="background: #f9fafb; border-radius: 20px; padding: 50px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-top: 60px;">
            <div style="text-align: center; margin-bottom: 50px;">
                <h3 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 16px;">
                    Informations sur les Rapports
                </h3>
                <p style="color: #6b7280; max-width: 800px; margin: 0 auto; line-height: 1.6; font-size: 1.1rem;">
                    Les rapports officiels du CSAR sont publiés annuellement et présentent un aperçu complet des activités, réalisations et perspectives de l'institution.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div class="info-card zoom-hover" style="text-align: center; padding: 30px; background: #fff; border-radius: 15px; border: 1px solid #f3f4f6; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 20px; border-radius: 15px; display: inline-block; margin-bottom: 20px; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-eye" style="font-size: 2rem; color: #fff;"></i>
                    </div>
                    <h4 style="font-weight: 700; color: #1f2937; margin-bottom: 12px; font-size: 1.2rem;">Transparence</h4>
                    <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.6;">
                        Accès libre aux informations publiques et aux rapports officiels
                    </p>
                </div>

                <div class="info-card zoom-hover" style="text-align: center; padding: 30px; background: #fff; border-radius: 15px; border: 1px solid #f3f4f6; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 20px; border-radius: 15px; display: inline-block; margin-bottom: 20px; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-shield-alt" style="font-size: 2rem; color: #fff;"></i>
                    </div>
                    <h4 style="font-weight: 700; color: #1f2937; margin-bottom: 12px; font-size: 1.2rem;">Fiabilité</h4>
                    <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.6;">
                        Données vérifiées et validées par les services compétents
                    </p>
                </div>

                <div class="info-card zoom-hover" style="text-align: center; padding: 30px; background: #fff; border-radius: 15px; border: 1px solid #f3f4f6; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 20px; border-radius: 15px; display: inline-block; margin-bottom: 20px; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-clock" style="font-size: 2rem; color: #fff;"></i>
                    </div>
                    <h4 style="font-weight: 700; color: #1f2937; margin-bottom: 12px; font-size: 1.2rem;">Actualité</h4>
                    <p style="color: #6b7280; font-size: 0.95rem; line-height: 1.6;">
                        Rapports mis à jour régulièrement avec les dernières données
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes gridMove {
    0% { transform: translate(0, 0); }
    100% { transform: translate(60px, 60px); }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

.fade-in {
    animation: fadeIn 0.8s ease-out;
}

.modern-card {
    animation: fadeIn 0.6s ease forwards;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.modern-card:hover {
    transform: translateY(-12px) scale(1.02) !important;
    box-shadow: 0 30px 80px rgba(0,0,0,0.15) !important;
}

.modern-card:hover .shine-effect {
    left: 100% !important;
}

.download-btn:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 30px rgba(5, 150, 105, 0.4) !important;
}

.info-card:hover, .stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.filter-btn {
    transition: all 0.3s ease !important;
}

.filter-btn:hover {
    border-color: #059669 !important;
    color: #059669 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(5,150,105,0.2);
}

.filter-btn.active {
    background: #059669 !important;
    border-color: #059669 !important;
    color: #fff !important;
    box-shadow: 0 4px 15px rgba(5,150,105,0.4);
}

@media (max-width: 768px) {
    .main-title { font-size: 2.5rem !important; }
    .section-title { font-size: 2rem !important; }
    .container { padding: 0 15px !important; }
    .modern-card { margin-bottom: 20px; }
}
</style>

<!-- JavaScript pour les interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.modern-card');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('searchReports');
    let currentFilter = 'all';
    let searchTerm = '';
    
    // Animation d'apparition progressive des cartes
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Gestion des filtres
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Retirer la classe active de tous les boutons
            filterBtns.forEach(b => {
                b.classList.remove('active');
                b.style.background = '#fff';
                b.style.borderColor = '#e5e7eb';
                b.style.color = '#6b7280';
            });
            
            // Ajouter la classe active au bouton cliqué
            this.classList.add('active');
            this.style.background = '#059669';
            this.style.borderColor = '#059669';
            this.style.color = '#fff';
            
            currentFilter = this.dataset.filter;
            filterCards();
        });
    });
    
    // Gestion de la recherche
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchTerm = this.value.toLowerCase();
            filterCards();
        });
    }
    
    // Fonction de filtrage
    function filterCards() {
        cards.forEach(card => {
            const cardType = card.dataset.type;
            const cardTitle = card.querySelector('h3').textContent.toLowerCase();
            const cardContent = card.querySelector('p').textContent.toLowerCase();
            
            const matchesFilter = currentFilter === 'all' || cardType === currentFilter;
            const matchesSearch = searchTerm === '' || 
                                cardTitle.includes(searchTerm) || 
                                cardContent.includes(searchTerm);
            
            if (matchesFilter && matchesSearch) {
                card.style.display = 'block';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Observation des éléments pour les animations au scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Observer tous les éléments animables
    document.querySelectorAll('.modern-card, .section-title, .section-subtitle, .info-card, .stat-card').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endsection