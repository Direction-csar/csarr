# 🚀 CSAR Platform 2025 - Guide de Déploiement

## 📦 Installation Rapide sur Hostinger

### Option 1: Déploiement Automatique (Recommandé)

1. **Télécharger le projet** depuis GitHub :
   ```bash
   git clone https://github.com/sultan2096/Csar2025.git
   cd Csar2025
   ```

2. **Exécuter le script de déploiement** :
   ```bash
   chmod +x deploy_hostinger.sh
   ./deploy_hostinger.sh
   ```

### Option 2: Déploiement Manuel

1. **Créer la base de données** dans cPanel
2. **Configurer le fichier `.env`** avec vos paramètres
3. **Uploader les fichiers** via File Manager
4. **Exécuter les commandes** via Terminal SSH

## 🔧 Configuration Requise

### Serveur
- **PHP 8.1+** (recommandé 8.2)
- **MySQL 5.7+** ou **MariaDB 10.3+**
- **Apache** avec mod_rewrite
- **Composer** (pour les dépendances)
- **Extension PHP** : mbstring, openssl, pdo, tokenizer, xml, ctype, json, bcmath

### Base de Données
```sql
CREATE DATABASE csar_platform_2025;
CREATE USER 'csar_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON csar_platform_2025.* TO 'csar_user'@'localhost';
FLUSH PRIVILEGES;
```

## 📁 Structure de Déploiement

```
public_html/
├── csar-platform/          # Dossier principal
│   ├── app/               # Logique applicative
│   ├── config/            # Configuration
│   ├── database/          # Migrations et seeders
│   ├── public/            # Point d'entrée web
│   │   └── index.php      # Fichier principal
│   ├── resources/         # Vues et assets
│   ├── routes/            # Routes applicatives
│   ├── storage/           # Fichiers de stockage
│   ├── vendor/            # Dépendances Composer
│   ├── .env               # Configuration environnement
│   ├── .htaccess          # Configuration Apache
│   └── artisan            # CLI Laravel
```

## 🔑 Configuration .env

```env
APP_NAME="CSAR Platform 2025"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=csar_platform_2025
DB_USERNAME=csar_user
DB_PASSWORD=your_strong_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
```

## 👥 Utilisateurs par Défaut

| Rôle | Email | Mot de passe | Interface |
|------|-------|--------------|-----------|
| Admin | admin@csar.sn | admin123 | `/admin` |
| DRH | drh@csar.sn | drh123 | `/drh` |
| DG | dg@csar.sn | dg123 | `/dg` |
| Agent | agent@csar.sn | agent123 | `/agent` |
| Responsable | responsable@csar.sn | resp123 | `/entrepot` |

⚠️ **IMPORTANT** : Changez tous les mots de passe après le déploiement !

## 🌐 URLs d'Accès

- **Site Public** : `https://yourdomain.com/`
- **Interface Admin** : `https://yourdomain.com/admin`
- **Interface DRH** : `https://yourdomain.com/drh`
- **Interface DG** : `https://yourdomain.com/dg`
- **Interface Agent** : `https://yourdomain.com/agent`
- **Interface Responsable** : `https://yourdomain.com/entrepot`

## 🛠️ Commandes de Maintenance

```bash
# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimiser l'application
php artisan optimize

# Vérifier les permissions
chmod -R 755 storage bootstrap/cache

# Mettre à jour les dépendances
composer update --no-dev --optimize-autoloader
```

## 📊 Fonctionnalités Disponibles

### 🏢 Interface Publique
- Page d'accueil institutionnelle
- Carte interactive des entrepôts
- Monitoring SIM en temps réel
- Informations sur les partenaires

### 👨‍💼 Interface Admin
- Dashboard complet avec analytics
- Gestion du personnel
- Rapports SIM et alertes de prix
- Configuration système
- Notifications SMS

### 👥 Interface DRH
- Gestion complète du personnel
- Bulletins de paie
- Statistiques RH détaillées
- Documents et contrats
- Suivi des présences

### 🏭 Interface Responsable Entrepôt
- Gestion des stocks
- Mouvements d'entrée/sortie
- Localisation GPS des entrepôts
- Rapports d'inventaire

### 📊 Interface DG
- Vue d'ensemble consolidée
- Rapports de performance
- Gestion des entrepôts
- Tableaux de bord exécutifs

### 🚚 Interface Agent
- Dashboard terrain
- Suivi des missions
- Rapports d'activité
- Coordination opérationnelle

## 🔒 Sécurité

### Recommandations
1. **Changer tous les mots de passe** par défaut
2. **Configurer HTTPS** (SSL/TLS)
3. **Activer le firewall** dans cPanel
4. **Surveiller les logs** d'accès
5. **Faire des backups** réguliers

### Fichiers Sensibles
- `.env` (configuration)
- `storage/logs/` (logs d'application)
- `database/` (migrations et seeders)

## 📞 Support et Maintenance

### En cas de problème
1. Vérifier les logs dans `storage/logs/laravel.log`
2. Contrôler les permissions de fichiers
3. Valider la configuration de la base de données
4. Tester les URLs d'accès

### Maintenance Régulière
- Nettoyer les logs anciens
- Optimiser la base de données
- Mettre à jour les dépendances
- Vérifier les performances

## 🎯 Prochaines Étapes

1. **Configurer le domaine** et SSL
2. **Créer les utilisateurs** réels
3. **Importer les données** existantes
4. **Configurer les notifications** email/SMS
5. **Tester toutes les fonctionnalités**

---

**🎉 Votre plateforme CSAR 2025 est prête pour la production !**

Pour plus de détails, consultez le [Guide de Déploiement Complet](DEPLOYMENT_GUIDE.md).
