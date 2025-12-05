# 🚀 Plan de Déploiement - Plateforme CSAR Institutionnelle

## 📋 Vue d'ensemble

Ce document présente le plan de déploiement complet pour la plateforme CSAR transformée, de l'environnement de staging vers la production.

---

## 🎯 Objectifs du Déploiement

- ✅ Déployer la plateforme institutionnelle complète
- ✅ Configurer tous les services externes (SMTP, SMS, Pusher)
- ✅ Migrer les données vers la production
- ✅ Activer le monitoring et les alertes
- ✅ Valider le fonctionnement en production

---

## 🏗️ Architecture de Déploiement

### Environnements

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   DÉVELOPPEMENT │ -> │     STAGING     │ -> │   PRODUCTION    │
│   (Local)       │    │   (Test)        │    │   (Live)        │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Stack Technologique

- **Serveur Web :** Apache/Nginx
- **Base de Données :** MySQL 8.0+
- **PHP :** 8.1+
- **Laravel :** 10.x
- **Queue :** Redis/Database
- **Cache :** Redis
- **Storage :** Local/S3

---

## 🔧 Préparation du Déploiement

### 1. **Configuration de l'Environnement**

#### 1.1 Variables d'Environnement Production

```env
# Application
APP_NAME="CSAR Platform"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://csar.sn

# Base de données
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=csar_production
DB_USERNAME=csar_user
DB_PASSWORD=secure-password

# Cache et Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Email (Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=contact@csar.sn
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@csar.sn"
MAIL_FROM_NAME="CSAR Platform"

# SMS (Orange SMS API)
SMS_ENABLED=true
SMS_API_KEY=your-orange-api-key
SMS_API_URL=https://api.orange.com/smsmessaging/v1
SMS_SENDER_NAME=CSAR

# Pusher (Notifications temps réel)
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-app-key
PUSHER_APP_SECRET=your-pusher-app-secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

# Logs
LOG_CHANNEL=stack
LOG_LEVEL=error
```

#### 1.2 Configuration Serveur

**Apache Virtual Host :**
```apache
<VirtualHost *:80>
    ServerName csar.sn
    ServerAlias www.csar.sn
    DocumentRoot /var/www/csar-platform/public
    
    <Directory /var/www/csar-platform/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/csar_error.log
    CustomLog ${APACHE_LOG_DIR}/csar_access.log combined
</VirtualHost>

<VirtualHost *:443>
    ServerName csar.sn
    ServerAlias www.csar.sn
    DocumentRoot /var/www/csar-platform/public
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /var/www/csar-platform/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 2. **Préparation de la Base de Données**

#### 2.1 Création de la Base Production

```sql
-- Créer la base de données
CREATE DATABASE csar_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Créer l'utilisateur
CREATE USER 'csar_user'@'localhost' IDENTIFIED BY 'secure-password';
GRANT ALL PRIVILEGES ON csar_production.* TO 'csar_user'@'localhost';
FLUSH PRIVILEGES;
```

#### 2.2 Migration des Données

```bash
# Exporter depuis staging
mysqldump -u staging_user -p csar_staging > csar_staging_backup.sql

# Importer en production
mysql -u csar_user -p csar_production < csar_staging_backup.sql
```

---

## 🚀 Processus de Déploiement

### Phase 1 : Préparation (1-2 heures)

#### 1.1 Sauvegarde Production
```bash
# Sauvegarde complète
tar -czf csar_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/csar-platform
mysqldump -u csar_user -p csar_production > csar_prod_backup_$(date +%Y%m%d_%H%M%S).sql
```

#### 1.2 Mise en Maintenance
```bash
# Activer le mode maintenance
php artisan down --message="Mise à jour en cours" --retry=60
```

#### 1.3 Vérifications Pré-déploiement
- [ ] Tests de staging passent à 100%
- [ ] Configuration production validée
- [ ] Services externes configurés
- [ ] Certificats SSL valides
- [ ] Sauvegardes créées

### Phase 2 : Déploiement du Code (30 minutes)

#### 2.1 Mise à Jour du Code
```bash
# Aller dans le répertoire de production
cd /var/www/csar-platform

# Récupérer le dernier code
git fetch origin
git checkout main
git pull origin main

# Installer les dépendances
composer install --no-dev --optimize-autoloader
npm install --production
npm run build
```

#### 2.2 Configuration Laravel
```bash
# Copier la configuration production
cp .env.production .env

# Générer la clé d'application
php artisan key:generate

# Nettoyer le cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Phase 3 : Base de Données (15 minutes)

#### 3.1 Migrations
```bash
# Exécuter les migrations
php artisan migrate --force

# Exécuter les seeders si nécessaire
php artisan db:seed --class=ProductionSeeder --force
```

#### 3.2 Optimisation
```bash
# Optimiser la base de données
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Phase 4 : Services et Configuration (30 minutes)

#### 4.1 Configuration des Queues
```bash
# Démarrer les workers de queue
php artisan queue:work --daemon --tries=3 --timeout=60
```

#### 4.2 Configuration des Tâches Cron
```bash
# Ajouter au crontab
* * * * * cd /var/www/csar-platform && php artisan schedule:run >> /dev/null 2>&1
```

#### 4.3 Configuration des Logs
```bash
# Créer les répertoires de logs
mkdir -p /var/log/csar
chown www-data:www-data /var/log/csar
chmod 755 /var/log/csar
```

### Phase 5 : Tests Post-Déploiement (30 minutes)

#### 5.1 Tests de Fumée
```bash
# Tests de base
curl -I https://csar.sn
curl -I https://csar.sn/admin
curl -I https://csar.sn/api/warehouses
```

#### 5.2 Tests Fonctionnels
- [ ] Page d'accueil charge
- [ ] Formulaire de demande fonctionne
- [ ] Authentification admin fonctionne
- [ ] Dashboard admin accessible
- [ ] Notifications temps réel
- [ ] Carte des entrepôts
- [ ] Emails envoyés
- [ ] SMS envoyés

#### 5.3 Tests de Performance
```bash
# Test de charge rapide
ab -n 50 -c 5 https://csar.sn/
```

### Phase 6 : Activation (15 minutes)

#### 6.1 Désactivation Maintenance
```bash
# Désactiver le mode maintenance
php artisan up
```

#### 6.2 Monitoring
- [ ] Vérifier les logs d'erreur
- [ ] Contrôler les métriques de performance
- [ ] Tester les alertes
- [ ] Valider les notifications

---

## 🔧 Configuration des Services Externes

### 1. **Configuration Gmail SMTP**

#### 1.1 Créer un Mot de Passe d'Application
1. Aller sur [Google Account](https://myaccount.google.com/)
2. Sécurité → Mots de passe des applications
3. Sélectionner "Autre" → "CSAR Platform"
4. Copier le mot de passe généré

#### 1.2 Test de Configuration
```bash
# Tester l'envoi d'email
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### 2. **Configuration SMS Orange**

#### 2.1 Obtenir les Clés API
1. Créer un compte sur [Orange Developer](https://developer.orange.com/)
2. Créer une application SMS
3. Obtenir l'API Key et l'API Secret

#### 2.2 Test de Configuration
```bash
# Tester l'envoi de SMS
php artisan tinker
>>> $sms = new \App\Services\SmsService();
>>> $sms->sendSms('+221XXXXXXXXX', 'Test SMS CSAR');
```

### 3. **Configuration Pusher**

#### 3.1 Créer une Application Pusher
1. Aller sur [Pusher](https://pusher.com/)
2. Créer une nouvelle application
3. Obtenir les clés (App ID, Key, Secret)

#### 3.2 Test de Configuration
```javascript
// Tester la connexion Pusher
window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('Pusher connecté !');
});
```

---

## 📊 Monitoring et Alertes

### 1. **Configuration des Logs**

#### 1.1 Logs Laravel
```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
    ],
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/csar.log'),
        'level' => 'error',
    ],
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'CSAR Bot',
        'emoji' => ':boom:',
        'level' => 'critical',
    ],
],
```

#### 1.2 Monitoring des Erreurs
- [ ] Sentry.io pour les erreurs JavaScript
- [ ] Laravel Telescope pour le debugging
- [ ] Logs Apache/Nginx
- [ ] Métriques MySQL

### 2. **Alertes Automatiques**

#### 2.1 Alertes Critiques
- [ ] Erreurs 500 > 5 en 5 minutes
- [ ] Temps de réponse > 5 secondes
- [ ] Utilisation CPU > 80%
- [ ] Utilisation mémoire > 90%
- [ ] Espace disque < 10%

#### 2.2 Alertes Métier
- [ ] Échec d'envoi d'email > 10%
- [ ] Échec d'envoi de SMS > 10%
- [ ] Demandes en erreur > 5%
- [ ] Notifications non envoyées

---

## 🔄 Plan de Rollback

### 1. **Scénarios de Rollback**

#### 1.1 Rollback Complet
```bash
# Restaurer le code
git checkout previous-stable-tag
composer install --no-dev
npm run build

# Restaurer la base de données
mysql -u csar_user -p csar_production < csar_prod_backup.sql

# Redémarrer les services
php artisan config:cache
php artisan route:cache
php artisan view:cache
systemctl restart apache2
```

#### 1.2 Rollback Partiel
- [ ] Désactiver les nouvelles fonctionnalités
- [ ] Revenir à l'ancienne configuration
- [ ] Corriger les problèmes identifiés

### 2. **Points de Contrôle**

#### 2.1 Avant Rollback
- [ ] Identifier la cause du problème
- [ ] Évaluer l'impact utilisateur
- [ ] Préparer la communication
- [ ] Planifier la correction

#### 2.2 Après Rollback
- [ ] Vérifier le fonctionnement
- [ ] Informer les utilisateurs
- [ ] Analyser les causes
- [ ] Planifier la reprise

---

## 📋 Checklist de Déploiement

### ✅ **Pré-déploiement**
- [ ] Tests de staging validés
- [ ] Configuration production préparée
- [ ] Services externes configurés
- [ ] Sauvegardes créées
- [ ] Plan de rollback préparé
- [ ] Équipe de déploiement disponible

### ✅ **Déploiement**
- [ ] Mode maintenance activé
- [ ] Code déployé
- [ ] Base de données migrée
- [ ] Configuration appliquée
- [ ] Services redémarrés
- [ ] Tests post-déploiement
- [ ] Mode maintenance désactivé

### ✅ **Post-déploiement**
- [ ] Monitoring activé
- [ ] Alertes configurées
- [ ] Performance validée
- [ ] Fonctionnalités testées
- [ ] Documentation mise à jour
- [ ] Équipe informée

---

## 🚨 Procédures d'Urgence

### 1. **Incident Critique**

#### 1.1 Détection
- [ ] Monitoring automatique
- [ ] Alertes Slack/Email
- [ ] Vérification manuelle

#### 1.2 Réponse
- [ ] Activer le mode maintenance
- [ ] Analyser les logs
- [ ] Identifier la cause
- [ ] Appliquer la correction
- [ ] Tester la solution
- [ ] Désactiver la maintenance

### 2. **Communication**

#### 2.1 Interne
- [ ] Slack #csar-alerts
- [ ] Email équipe technique
- [ ] Documentation incident

#### 2.2 Externe
- [ ] Page de statut
- [ ] Communication utilisateurs
- [ ] Médias sociaux si nécessaire

---

## 📞 Support Post-Déploiement

### 1. **Équipe de Support**

**Niveau 1 :** Support utilisateur
- [ ] Formation des utilisateurs
- [ ] Documentation utilisateur
- [ ] FAQ et guides

**Niveau 2 :** Support technique
- [ ] Résolution des bugs
- [ ] Optimisation performance
- [ ] Maintenance préventive

**Niveau 3 :** Développement
- [ ] Corrections critiques
- [ ] Nouvelles fonctionnalités
- [ ] Architecture

### 2. **Maintenance Continue**

#### 2.1 Quotidienne
- [ ] Vérification des logs
- [ ] Monitoring des performances
- [ ] Sauvegardes automatiques

#### 2.2 Hebdomadaire
- [ ] Analyse des métriques
- [ ] Mise à jour de sécurité
- [ ] Optimisation base de données

#### 2.3 Mensuelle
- [ ] Revue des performances
- [ ] Planification des améliorations
- [ ] Formation équipe

---

## 🎯 Critères de Succès

### 1. **Techniques**
- [ ] 0 erreur 500 en production
- [ ] Temps de réponse < 2 secondes
- [ ] Disponibilité > 99.5%
- [ ] Toutes les fonctionnalités opérationnelles

### 2. **Métier**
- [ ] Formulaires fonctionnels
- [ ] Notifications temps réel
- [ ] Emails/SMS envoyés
- [ ] Carte interactive
- [ ] Sécurité renforcée

### 3. **Utilisateur**
- [ ] Interface responsive
- [ ] Navigation intuitive
- [ ] Performance satisfaisante
- [ ] Fonctionnalités accessibles

---

## 📚 Documentation et Formation

### 1. **Documentation Technique**
- [ ] Guide d'administration
- [ ] API documentation
- [ ] Procédures de maintenance
- [ ] Architecture système

### 2. **Documentation Utilisateur**
- [ ] Guide utilisateur admin
- [ ] Guide utilisateur DG
- [ ] Guide utilisateur RH
- [ ] FAQ générale

### 3. **Formation**
- [ ] Session formation admin
- [ ] Session formation utilisateurs
- [ ] Documentation de support
- [ ] Vidéos tutoriels

---

**🎉 La plateforme CSAR institutionnelle est maintenant prête pour la production !**
