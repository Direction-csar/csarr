# 🚀 Guide de Déploiement CSAR Platform 2025 sur Hostinger

## 📋 Prérequis

1. **Compte Hostinger** avec hébergement web
2. **Accès cPanel** ou **File Manager**
3. **Base de données MySQL** créée
4. **PHP 8.1+** activé
5. **Composer** installé (ou accès SSH)

## 🔧 Étapes de Déploiement

### 1. Préparation du Serveur

#### Via cPanel File Manager :
```bash
# 1. Télécharger le projet depuis GitHub
# 2. Extraire dans public_html/
# 3. Configurer les permissions
```

#### Via SSH (si disponible) :
```bash
# Cloner le projet
git clone https://github.com/sultan2096/Csar2025.git
cd Csar2025

# Installer les dépendances
composer install --optimize-autoloader --no-dev

# Configurer les permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 2. Configuration de la Base de Données

#### Créer la base de données :
```sql
-- Dans phpMyAdmin ou MySQL
CREATE DATABASE csar_platform_2025;
CREATE USER 'csar_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON csar_platform_2025.* TO 'csar_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Configuration de l'Environnement

#### Créer le fichier `.env` :
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
DB_PASSWORD=strong_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### 4. Configuration Laravel

#### Générer la clé d'application :
```bash
php artisan key:generate
```

#### Exécuter les migrations :
```bash
php artisan migrate --force
```

#### Créer les utilisateurs par défaut :
```bash
php artisan db:seed --class=TestUsersSeeder
```

#### Optimiser l'application :
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Configuration du Serveur Web

#### Structure des fichiers dans public_html :
```
public_html/
├── csar-platform/          # Dossier racine du projet
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/             # Contenu web accessible
│   │   ├── index.php       # Point d'entrée
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   └── vendor/
```

#### Configuration .htaccess :
Le fichier `.htaccess` est déjà inclus dans le projet.

### 6. Création des Utilisateurs par Défaut

#### Via la commande Artisan :
```bash
php artisan tinker
```

```php
// Créer l'administrateur principal
User::create([
    'name' => 'Administrateur CSAR',
    'email' => 'admin@csar.sn',
    'password' => Hash::make('admin123'),
    'role' => 'admin',
    'is_active' => true,
]);

// Créer le DRH
User::create([
    'name' => 'DRH CSAR',
    'email' => 'drh@csar.sn',
    'password' => Hash::make('drh123'),
    'role' => 'drh',
    'is_active' => true,
]);

// Créer le DG
User::create([
    'name' => 'Directeur Général',
    'email' => 'dg@csar.sn',
    'password' => Hash::make('dg123'),
    'role' => 'dg',
    'is_active' => true,
]);
```

### 7. Accès aux Interfaces

#### URLs d'accès :
- **Public** : `https://yourdomain.com/`
- **Admin** : `https://yourdomain.com/admin`
- **DRH** : `https://yourdomain.com/drh`
- **Agent** : `https://yourdomain.com/agent`
- **DG** : `https://yourdomain.com/dg`
- **Responsable** : `https://yourdomain.com/entrepot`

#### Identifiants par défaut :
- **Admin** : admin@csar.sn / admin123
- **DRH** : drh@csar.sn / drh123
- **DG** : dg@csar.sn / dg123

### 8. Configuration SSL et Sécurité

#### Activer HTTPS :
1. Aller dans cPanel > SSL/TLS
2. Activer "Force HTTPS Redirect"
3. Configurer les certificats SSL

#### Sécurité supplémentaire :
```bash
# Changer les permissions sensibles
chmod 644 .env
chmod -R 755 storage bootstrap/cache
chmod 644 storage/logs/*.log
```

### 9. Optimisation des Performances

#### Configuration PHP (via cPanel) :
```
upload_max_filesize = 64M
post_max_size = 64M
memory_limit = 256M
max_execution_time = 300
max_input_vars = 3000
```

#### Cache et Optimisation :
```bash
# Optimiser l'autoloader
composer dump-autoload --optimize

# Cache des configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 10. Surveillance et Maintenance

#### Logs à surveiller :
- `storage/logs/laravel.log`
- Logs d'erreur du serveur web
- Logs de la base de données

#### Commandes de maintenance :
```bash
# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimiser à nouveau
php artisan optimize
```

## 🔒 Sécurité

### Recommandations importantes :
1. **Changer tous les mots de passe par défaut**
2. **Configurer le firewall**
3. **Activer les backups automatiques**
4. **Surveiller les logs d'accès**
5. **Mettre à jour régulièrement**

### Fichiers sensibles à protéger :
- `.env`
- `storage/`
- `database/`
- `config/`

## 📞 Support

En cas de problème :
1. Vérifier les logs d'erreur
2. Contrôler les permissions de fichiers
3. Valider la configuration de la base de données
4. Tester les URLs d'accès

## 🎯 Fonctionnalités Disponibles

### Interface Publique :
- Page d'accueil
- À propos de CSAR
- Carte interactive des entrepôts
- Partenaires
- Monitoring SIM

### Interface Admin :
- Dashboard complet
- Gestion du personnel
- Rapports SIM
- Alertes de prix
- Notifications SMS

### Interface DRH :
- Gestion du personnel
- Bulletins de paie
- Statistiques RH
- Documents RH

### Interface Agent :
- Dashboard agent
- Suivi des missions
- Rapports terrain

### Interface DG :
- Vue d'ensemble
- Rapports consolidés
- Gestion des entrepôts

### Interface Responsable :
- Gestion des stocks
- Mouvements d'entrepôt
- Localisation GPS

---

**🎉 Votre plateforme CSAR 2025 est maintenant prête pour la production !**
