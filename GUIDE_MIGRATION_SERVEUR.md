# 🚀 Guide de Migration vers Serveur Dédié

## 📊 ÉTAT ACTUEL DE VOTRE PLATEFORME

### ✅ Configuration Actuelle :
```
Base de données : MySQL
Hébergement     : Local (localhost - XAMPP)
Taille BDD      : 1.91 MB
Nombre de tables: 42 tables
État            : ✅ Tout fonctionne correctement
```

### 📋 Tables de la Base de Données (42) :

**Modules Principaux :**
- ✅ `users` (2 utilisateurs)
- ✅ `demandes` (0 demandes)  
- ✅ `public_requests` (1 demande)
- ✅ `warehouses` (3 entrepôts)
- ✅ `stocks` (0 stocks)
- ✅ `stock_movements` (0 mouvements)
- ✅ `news` (1 actualité)
- ✅ `newsletters` (0 newsletters)
- ✅ `newsletter_subscribers` (2 abonnés)
- ✅ `sim_reports` (0 rapports)
- ✅ `personnel` (0 personnels)
- ✅ `products` (8 produits)

**Modules Support :**
- ✅ `notifications` (système de notifications)
- ✅ `audit_logs` (journaux d'audit)
- ✅ `messages` (système de messagerie)
- ✅ `tasks` (gestion des tâches)
- ✅ `chiffres_cles` (chiffres clés)
- ✅ Et 20+ autres tables...

---

## 🎯 OUI, TOUT EST CONNECTÉ À LA BASE DE DONNÉES !

**Tous les modules sont connectés :**
- ✅ Demandes → Table `demandes` + `public_requests`
- ✅ Utilisateurs → Table `users`
- ✅ Entrepôts → Table `warehouses`
- ✅ Stocks → Table `stocks` + `stock_movements`
- ✅ Personnel → Table `personnel`
- ✅ Actualités → Table `news`
- ✅ Newsletter → Table `newsletters` + `newsletter_subscribers`
- ✅ Rapports SIM → Table `sim_reports`
- ✅ Notifications → Table `notifications`
- ✅ Messages → Table `messages`
- ✅ Audit → Table `audit_logs`

**Configuration actuelle :**
```
Host: 127.0.0.1 (localhost)
Port: 3306
Base: csar
User: root
```

---

## 🏢 MIGRATION VERS VOTRE PROPRE SERVEUR

### Option 1 : Serveur Dédié (Recommandé pour Entreprise)

**Exemples de fournisseurs :**
- 🌍 **OVH** (France) - Serveurs dédiés à partir de 50€/mois
- 🌍 **AWS** (Amazon) - RDS MySQL managé
- 🌍 **DigitalOcean** - Droplets à partir de 5$/mois
- 🌍 **Heroku** - Cloud Platform avec PostgreSQL/MySQL
- 🇸🇳 **Sonatel/Orange Sénégal** - Solutions d'hébergement local

### Option 2 : Hébergement Mutualisé
- Plus économique
- Moins de contrôle
- Convient pour démarrage

---

## 📝 ÉTAPES DE MIGRATION (Pas à Pas)

### PHASE 1 : PRÉPARATION (Sur votre ordinateur actuel)

#### 1.1 Exporter la Base de Données

**Méthode A : Via phpMyAdmin** (Plus simple)
```
1. Ouvrir http://localhost/phpmyadmin
2. Cliquer sur la base "csar" dans le menu gauche
3. Cliquer sur l'onglet "Exporter" en haut
4. Choisir "Rapide" ou "Personnalisé"
5. Format : SQL
6. Cliquer sur "Exécuter"
7. Sauvegarder le fichier : csar_backup_2025_10_24.sql
```

**Méthode B : Via Script** (Plus rapide)
```bash
# Exporter toute la base de données
php artisan db:export

# OU via mysqldump
mysqldump -u root -p csar > csar_backup.sql
```

#### 1.2 Sauvegarder les Fichiers Uploadés
```
Copier le dossier : storage/app/public/
Contient : 
  - Photos uploadées
  - Documents
  - Pièces jointes
```

#### 1.3 Noter la Configuration Actuelle
```
✅ Version PHP : 8.2.12
✅ Version MySQL : (vérifier dans phpMyAdmin)
✅ Extensions PHP nécessaires :
   - PDO
   - MySQL
   - mbstring
   - openssl
   - JSON
```

---

### PHASE 2 : CONFIGURATION DU NOUVEAU SERVEUR

#### 2.1 Installer les Prérequis sur le Serveur

**Sur un serveur Linux (Ubuntu/Debian) :**
```bash
# Installer Apache/Nginx
sudo apt update
sudo apt install apache2 nginx

# Installer PHP 8.2+
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml

# Installer MySQL
sudo apt install mysql-server
```

#### 2.2 Créer la Base de Données sur le Nouveau Serveur
```bash
# Se connecter à MySQL
mysql -u root -p

# Créer la base de données
CREATE DATABASE csar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Créer un utilisateur dédié
CREATE USER 'csar_user'@'localhost' IDENTIFIED BY 'mot_de_passe_securise';

# Donner les permissions
GRANT ALL PRIVILEGES ON csar.* TO 'csar_user'@'localhost';
FLUSH PRIVILEGES;

# Quitter
EXIT;
```

#### 2.3 Importer les Données
```bash
# Importer le fichier SQL
mysql -u csar_user -p csar < csar_backup.sql
```

---

### PHASE 3 : CONFIGURATION DE L'APPLICATION

#### 3.1 Transférer les Fichiers de l'Application

**Via FTP/SFTP :**
```
1. Compresser le dossier csar : 
   - Clic droit > Envoyer vers > Dossier compressé
   
2. Uploader via FileZilla ou WinSCP :
   - Hôte : [IP de votre serveur]
   - Utilisateur : [votre utilisateur]
   - Mot de passe : [votre mot de passe]
   - Port : 22 (SFTP) ou 21 (FTP)
   
3. Décompresser sur le serveur
```

#### 3.2 Créer/Modifier le Fichier .env

**Créer le fichier `.env` à la racine du projet :**
```env
APP_NAME="CSAR Platform"
APP_ENV=production
APP_KEY=base64:VOTRE_CLE_ICI
APP_DEBUG=false
APP_URL=https://votre-domaine.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# BASE DE DONNÉES - CONFIGURATION NOUVELLE
DB_CONNECTION=mysql
DB_HOST=localhost
# OU l'IP de votre serveur MySQL : 192.168.1.100
DB_PORT=3306
DB_DATABASE=csar
DB_USERNAME=csar_user
DB_PASSWORD=votre_mot_de_passe_securise

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Si vous utilisez un serveur de mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre@email.com
MAIL_PASSWORD=votre_mot_de_passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@csar.sn
MAIL_FROM_NAME="${APP_NAME}"
```

#### 3.3 Générer une Nouvelle Clé d'Application
```bash
# Se connecter en SSH au serveur
ssh utilisateur@votre-serveur.com

# Aller dans le dossier de l'application
cd /var/www/csar

# Générer la clé
php artisan key:generate

# Nettoyer les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### 3.4 Configurer les Permissions
```bash
# Donner les bonnes permissions
sudo chown -R www-data:www-data /var/www/csar
sudo chmod -R 755 /var/www/csar
sudo chmod -R 775 /var/www/csar/storage
sudo chmod -R 775 /var/www/csar/bootstrap/cache
```

---

### PHASE 4 : CONFIGURATION DU SERVEUR WEB

#### 4.1 Configuration Apache (Virtual Host)

**Créer le fichier `/etc/apache2/sites-available/csar.conf` :**
```apache
<VirtualHost *:80>
    ServerName votre-domaine.com
    ServerAlias www.votre-domaine.com
    DocumentRoot /var/www/csar/public

    <Directory /var/www/csar/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/csar_error.log
    CustomLog ${APACHE_LOG_DIR}/csar_access.log combined
</VirtualHost>
```

**Activer le site :**
```bash
sudo a2ensite csar.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### 4.2 Configuration Nginx (Alternative)

**Créer le fichier `/etc/nginx/sites-available/csar` :**
```nginx
server {
    listen 80;
    server_name votre-domaine.com www.votre-domaine.com;
    root /var/www/csar/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Activer le site :**
```bash
sudo ln -s /etc/nginx/sites-available/csar /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

### PHASE 5 : SÉCURITÉ ET SSL

#### 5.1 Installer un Certificat SSL (HTTPS)

**Avec Let's Encrypt (Gratuit) :**
```bash
# Installer Certbot
sudo apt install certbot python3-certbot-apache
# OU pour Nginx
sudo apt install certbot python3-certbot-nginx

# Obtenir le certificat
sudo certbot --apache -d votre-domaine.com -d www.votre-domaine.com
# OU pour Nginx
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com

# Renouvellement automatique (déjà configuré)
sudo certbot renew --dry-run
```

#### 5.2 Sécuriser MySQL
```bash
# Lancer le script de sécurisation
sudo mysql_secure_installation

# Suivre les instructions :
# - Définir un mot de passe root fort
# - Supprimer les utilisateurs anonymes
# - Désactiver la connexion root à distance
# - Supprimer la base de test
```

#### 5.3 Configurer le Pare-feu
```bash
# Installer UFW
sudo apt install ufw

# Autoriser SSH, HTTP et HTTPS
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Activer le pare-feu
sudo ufw enable
```

---

### PHASE 6 : TESTS ET VALIDATION

#### 6.1 Tests de Connexion
```
✅ Tester l'accès au site : https://votre-domaine.com
✅ Vérifier la connexion à la base de données
✅ Tester la connexion admin : https://votre-domaine.com/login
✅ Vérifier que toutes les pages se chargent
```

#### 6.2 Tests Fonctionnels
```
✅ Soumettre une demande via le formulaire public
✅ Se connecter à l'interface admin
✅ Voir le dashboard
✅ Créer/modifier/supprimer une demande
✅ Tester tous les modules
```

#### 6.3 Tests de Performance
```
✅ Temps de chargement des pages
✅ Connexion à la base de données
✅ Upload de fichiers
```

---

## 🔄 MIGRATION SANS INTERRUPTION DE SERVICE

### Option : Migration Progressive

**Étape 1 : Configuration Parallèle**
```
1. Garder l'ancien système en marche
2. Configurer le nouveau serveur en parallèle
3. Tester le nouveau serveur en interne
```

**Étape 2 : Migration des Données**
```
1. Exporter les données de l'ancien serveur
2. Importer sur le nouveau serveur
3. Vérifier l'intégrité des données
```

**Étape 3 : Basculement DNS**
```
1. Mettre à jour les enregistrements DNS
2. Pointer votre domaine vers le nouveau serveur
3. Temps de propagation : 24-48h
```

**Étape 4 : Surveillance**
```
1. Surveiller les logs d'erreur
2. Vérifier les performances
3. Être prêt à revenir en arrière si besoin
```

---

## 💾 SAUVEGARDES AUTOMATIQUES

### Configuration de Sauvegardes Régulières

**Script de Sauvegarde Quotidienne :**
```bash
#!/bin/bash
# Fichier : /usr/local/bin/backup-csar.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups/csar"
DB_NAME="csar"
DB_USER="csar_user"
DB_PASS="votre_mot_de_passe"

# Créer le dossier si nécessaire
mkdir -p $BACKUP_DIR

# Sauvegarder la base de données
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/csar_db_$DATE.sql

# Sauvegarder les fichiers
tar -czf $BACKUP_DIR/csar_files_$DATE.tar.gz /var/www/csar/storage

# Garder seulement les 7 dernières sauvegardes
find $BACKUP_DIR -type f -mtime +7 -delete

echo "Sauvegarde terminée : $DATE"
```

**Automatiser avec Cron :**
```bash
# Éditer le crontab
sudo crontab -e

# Ajouter (sauvegarde quotidienne à 2h du matin)
0 2 * * * /usr/local/bin/backup-csar.sh
```

---

## 📊 MONITORING ET MAINTENANCE

### Outils de Monitoring Recommandés

**1. Monitoring Serveur :**
- Netdata (gratuit, temps réel)
- Prometheus + Grafana
- New Relic

**2. Monitoring Application :**
- Laravel Telescope (inclus)
- Sentry (erreurs)
- Laravel Horizon (queues)

**3. Monitoring Base de Données :**
- phpMyAdmin
- Adminer
- MySQL Workbench

---

## 💰 COÛTS ESTIMÉS

### Solution Hébergement Cloud

**Option Économique :**
```
VPS DigitalOcean Droplet : 5-10$/mois
Nom de domaine : 10-15€/an
Certificat SSL : Gratuit (Let's Encrypt)
TOTAL : ~60-120$/an
```

**Option Professionnelle :**
```
Serveur Dédié OVH : 50-100€/mois
Nom de domaine : 10-15€/an
Monitoring : 20€/mois
TOTAL : ~900-1500€/an
```

**Option Entreprise (Cloud AWS) :**
```
EC2 Instance : 50-200$/mois
RDS MySQL : 30-150$/mois
S3 Storage : 5-20$/mois
TOTAL : ~1000-4500$/an
```

---

## 📞 ASSISTANCE MIGRATION

### Si Vous Avez Besoin d'Aide

**Option 1 : Documentation**
- Consultez ce guide
- Tutoriels en ligne
- Documentation Laravel

**Option 2 : Support Technique**
- Contactez votre hébergeur
- Forums Laravel
- Support communautaire

**Option 3 : Prestataire**
- Faire appel à un administrateur système
- Coût : 500-2000€ selon complexité

---

## ✅ CHECKLIST DE MIGRATION

### Avant la Migration
- [ ] Sauvegarder la base de données actuelle
- [ ] Sauvegarder les fichiers uploadés
- [ ] Noter toutes les configurations
- [ ] Tester l'export des données
- [ ] Documenter l'architecture actuelle

### Pendant la Migration
- [ ] Créer le nouveau serveur
- [ ] Installer les prérequis (PHP, MySQL, etc.)
- [ ] Créer la base de données
- [ ] Importer les données
- [ ] Transférer les fichiers
- [ ] Configurer le fichier .env
- [ ] Configurer le serveur web
- [ ] Installer le certificat SSL

### Après la Migration
- [ ] Tester toutes les fonctionnalités
- [ ] Vérifier les logs d'erreur
- [ ] Configurer les sauvegardes automatiques
- [ ] Mettre en place le monitoring
- [ ] Former l'équipe sur le nouveau système
- [ ] Garder l'ancien système 1-2 semaines

---

## 🎯 RÉSUMÉ

```
✅ Votre plateforme utilise MySQL
✅ 42 tables connectées
✅ Taille : 1.91 MB
✅ Tout est prêt pour migration
✅ Plusieurs options d'hébergement disponibles
✅ Migration peut se faire sans interruption
✅ Coût : 5-200$/mois selon l'option choisie
```

**Prochaine étape :** Choisir un hébergeur et suivre ce guide!

---

**Date de création :** 24 octobre 2025  
**Version :** CSAR v1.0  
**Statut :** ✅ Prêt pour Migration




















