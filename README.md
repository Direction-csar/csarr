# 🏛️ CSAR Platform 2025 - Plateforme Administrative Complète

## 📋 Description
Plateforme administrative complète pour le Commissariat à la Sécurité Alimentaire et à la Résilience (CSAR) du Sénégal. Cette application web moderne permet la gestion administrative, la surveillance des stocks, la gestion des ressources humaines, et la communication institutionnelle.

## ✨ Fonctionnalités Principales

### 🔐 Système d'Authentification Multi-Rôles
- **Interface Admin** : Gestion complète de la plateforme
- **Interface DRH** : Direction des Ressources Humaines
- **Interface DG** : Direction Générale avec accès aux rapports et données
- **Interface Agent** : Accès aux ressources humaines et documents
- **Interface Responsable** : Gestion des stocks et mouvements d'entrepôt

### 👥 Gestion du Personnel (DRH)
- Création et gestion complète des fiches de personnel
- Upload et affichage des photos de profil
- Génération de PDFs personnalisés
- Système de validation des données
- Gestion des bulletins de paie
- Statistiques RH détaillées
- Suivi des présences et absences

### 📊 Tableaux de Bord Spécialisés
- **Dashboard Admin** : Vue d'ensemble complète
- **Dashboard DRH** : Statistiques RH et gestion du personnel
- **Dashboard DG** : Rapports consolidés et performance
- **Dashboard Agent** : Missions terrain et coordination
- **Dashboard Responsable** : Gestion des stocks et entrepôts

### 🏭 Gestion des Stocks et Entrepôts
- Surveillance des stocks en temps réel
- Mouvements d'entrée/sortie
- Localisation GPS des entrepôts
- Rapports d'inventaire
- Alertes de seuils minimaux

### 📱 Monitoring SIM et Alertes
- Surveillance des cartes SIM
- Alertes de prix automatiques
- Rapports de consommation
- Notifications SMS/Email

### 🖼️ Interface Publique
- Site institutionnel moderne
- Carousel responsive pour les missions
- Carte interactive des entrepôts
- Galerie de partenaires
- Monitoring SIM public

## 🚀 Technologies Utilisées
- **Backend** : Laravel 12.x
- **Frontend** : Blade Templates, CSS3, JavaScript, Tailwind CSS
- **Base de données** : MySQL/SQLite
- **Serveur** : Apache (XAMPP)
- **Versioning** : Git & GitHub
- **Déploiement** : Hostinger Ready

## 📦 Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL/SQLite
- XAMPP (recommandé pour développement)

### Étapes d'installation

1. **Cloner le dépôt**
```bash
git clone https://github.com/sultan2096/Csar2025.git
cd Csar2025
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
- Créer une base de données MySQL
- Configurer les paramètres dans `.env`

5. **Migration et données de test**
```bash
php artisan migrate
php artisan db:seed --class=TestUsersSeeder
```

6. **Lien symbolique pour le stockage**
```bash
php artisan storage:link
```

7. **Démarrer le serveur**
```bash
php artisan serve
```

## 🔑 Identifiants de Connexion

| Rôle | Email | Mot de passe | Interface |
|------|-------|--------------|-----------|
| **Admin** | admin@csar.sn | admin123 | `/admin` |
| **DRH** | drh@csar.sn | drh123 | `/drh` |
| **DG** | dg@csar.sn | dg123 | `/dg` |
| **Agent** | agent@csar.sn | agent123 | `/agent` |
| **Responsable** | responsable@csar.sn | resp123 | `/entrepot` |

## 📱 URLs d'Accès

### Interfaces Internes
- **Admin** : `http://localhost:8000/admin`
- **DRH** : `http://localhost:8000/drh`
- **DG** : `http://localhost:8000/dg`
- **Agent** : `http://localhost:8000/agent`
- **Responsable** : `http://localhost:8000/entrepot`

### Interface Publique
- **Page d'accueil** : `http://localhost:8000/`
- **À propos** : `http://localhost:8000/about`
- **Carte** : `http://localhost:8000/map`
- **Partenaires** : `http://localhost:8000/partners`
- **Monitoring SIM** : `http://localhost:8000/sim`

## 🎨 Fonctionnalités Récentes

### ✨ Interface DRH Complète
- Dashboard professionnel avec statistiques RH
- Gestion complète du personnel avec photos
- Bulletins de paie et documents RH
- Statistiques détaillées et rapports
- Design moderne et responsive

### ✨ Améliorations UI/UX
- Interface de connexion moderne et cohérente
- Photos de personnel plus visuelles
- Carousel responsive pour la galerie
- Notifications de succès/erreur
- Design professionnel avec palette de couleurs CSAR

### 🔧 Corrections Techniques
- Résolution des erreurs 404 sur les dashboards
- Correction des redirections après connexion
- Amélioration de la gestion des photos
- Validation robuste des formulaires
- Optimisation des performances

### 🔒 Sécurité Renforcée
- Middleware de protection des routes
- Gestion sécurisée des sessions
- Protection CSRF sur tous les formulaires
- Validation côté serveur et client
- Chiffrement des données sensibles

## 📊 Structure du Projet

```
csar-platform/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Contrôleurs Admin
│   │   ├── Drh/            # Contrôleurs DRH
│   │   ├── DG/             # Contrôleurs Direction Générale
│   │   ├── Responsable/    # Contrôleurs Entrepôt
│   │   └── Agent/          # Contrôleurs Agent
│   ├── Models/             # Modèles Eloquent
│   ├── Services/           # Services métier
│   └── Mail/               # Notifications email
├── resources/views/
│   ├── admin/              # Vues Admin
│   ├── drh/                # Vues DRH
│   ├── dg/                 # Vues DG
│   ├── responsable/        # Vues Entrepôt
│   ├── agent/              # Vues Agent
│   ├── public/             # Vues publiques
│   └── layouts/            # Layouts communs
├── public/
│   ├── css/                # Styles CSS personnalisés
│   ├── js/                 # Scripts JavaScript
│   └── images/             # Images et médias
├── routes/
│   ├── web.php             # Routes de l'application
│   └── api.php             # Routes API
├── database/
│   ├── migrations/         # Migrations de base de données
│   └── seeders/            # Données de test
└── config/                 # Configuration de l'application
```

## 🚀 Déploiement sur Hostinger

### Installation Rapide
```bash
# Cloner le projet
git clone https://github.com/sultan2096/Csar2025.git
cd Csar2025

# Déploiement automatique
chmod +x deploy_hostinger.sh
./deploy_hostinger.sh
```

### Configuration Requise
- PHP 8.1+ (recommandé 8.2)
- MySQL 5.7+ ou MariaDB 10.3+
- Apache avec mod_rewrite
- Composer

📚 **Documentation complète** : [README_DEPLOYMENT.md](README_DEPLOYMENT.md)

## 🤝 Contribution

1. Fork le projet
2. Créer une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 Changelog

### Version 2.0.0 (2025-01-09)
- ✨ **Interface DRH complète** avec gestion du personnel
- ✨ **Bulletins de paie** et statistiques RH
- ✨ **Gestion des stocks** et entrepôts
- ✨ **Monitoring SIM** et alertes de prix
- ✨ **Interface publique** moderne
- 🔧 **Corrections des erreurs** 404 et redirections
- 🔧 **Amélioration de la gestion** des photos
- 🔒 **Renforcement de la sécurité**
- 📱 **Design responsive** pour tous les appareils

### Version 1.0.0 (2024)
- ✨ Interfaces de connexion modernes
- ✨ Système de profils complet
- ✨ Carousel responsive pour la galerie
- 🔧 Corrections des erreurs 404
- 🔧 Amélioration de la gestion des photos
- 🔒 Renforcement de la sécurité

## 📞 Support

Pour toute question ou problème :
- 📧 Email : support@csar.sn
- 📱 Téléphone : +221 XX XXX XX XX
- 🌐 Site web : https://csar.sn

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

**Développé avec ❤️ pour le CSAR Sénégal**

*Plateforme numérique moderne pour la sécurité alimentaire et la résilience du Sénégal*