# Guide de Migration vers MySQL (phpMyAdmin)

Ce guide vous aide à migrer votre projet CSAR Platform de SQLite vers MySQL avec la base de données **plateforme-csar**.

## 📋 Prérequis

- ✅ XAMPP installé et MySQL démarré
- ✅ phpMyAdmin accessible sur http://localhost/phpmyadmin
- ✅ PHP installé et disponible en ligne de commande
- ✅ Composer installé

## 🚀 Méthode Automatique (Recommandée)

### Exécuter le script de migration automatique :

```bash
php migrate_to_mysql.php
```

Ce script va automatiquement :
1. ✅ Créer le fichier `.env` avec la configuration MySQL
2. ✅ Vérifier la connexion à MySQL
3. ✅ Créer la base de données `plateforme-csar`
4. ✅ Générer la clé d'application Laravel
5. ✅ Exécuter toutes les migrations (créer les tables)
6. ✅ Insérer les données initiales (seeders)

## 🔧 Méthode Manuelle

Si vous préférez faire la migration manuellement :

### Étape 1 : Créer le fichier .env

Créez un fichier `.env` à la racine du projet avec ce contenu :

```env
APP_NAME="CSAR Platform"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plateforme-csar
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Étape 2 : Créer la base de données dans phpMyAdmin

1. Ouvrez phpMyAdmin : http://localhost/phpmyadmin
2. Cliquez sur "Nouvelle base de données"
3. Nom : `plateforme-csar`
4. Interclassement : `utf8mb4_unicode_ci`
5. Cliquez sur "Créer"

### Étape 3 : Générer la clé d'application

```bash
php artisan key:generate
```

### Étape 4 : Nettoyer le cache

```bash
php artisan config:clear
php artisan cache:clear
```

### Étape 5 : Exécuter les migrations

```bash
php artisan migrate:fresh
```

Cette commande va créer toutes les tables suivantes :
- ✅ users (utilisateurs)
- ✅ roles (rôles)
- ✅ warehouses (entrepôts)
- ✅ stocks (stocks)
- ✅ stock_movements (mouvements de stock)
- ✅ public_requests (demandes publiques)
- ✅ newsletters (bulletins)
- ✅ contact_messages (messages de contact)
- ✅ news (actualités)
- ✅ sim_reports (rapports SIM)
- ✅ tasks (tâches)
- ✅ price_alerts (alertes de prix)
- ✅ personnel (personnel)
- ✅ demandes (demandes)
- ✅ Et bien d'autres...

### Étape 6 : Insérer les données initiales

```bash
php artisan db:seed
```

## ✅ Vérification

1. **Vérifiez dans phpMyAdmin** :
   - Ouvrez la base de données `plateforme-csar`
   - Vous devriez voir environ 50+ tables

2. **Testez la connexion** :
   ```bash
   php artisan tinker
   ```
   Puis :
   ```php
   \DB::connection()->getPdo();
   ```

3. **Démarrez l'application** :
   ```bash
   php artisan serve
   ```
   Accédez à : http://localhost:8000

## 🔍 Résolution de problèmes

### Erreur : "Access denied for user 'root'@'localhost'"

**Solution** : Modifiez le mot de passe MySQL dans `.env` :
```env
DB_PASSWORD=votre_mot_de_passe
```

### Erreur : "Database 'plateforme-csar' doesn't exist"

**Solution** : Créez manuellement la base de données dans phpMyAdmin

### Erreur : "SQLSTATE[HY000] [2002] Connection refused"

**Solution** : 
1. Vérifiez que MySQL est démarré dans XAMPP
2. Vérifiez le port dans `.env` (par défaut 3306)

### Les migrations échouent

**Solution** :
1. Supprimez la base de données dans phpMyAdmin
2. Recréez-la
3. Relancez : `php artisan migrate:fresh`

## 📊 Commandes utiles

```bash
# Voir l'état des migrations
php artisan migrate:status

# Annuler la dernière migration
php artisan migrate:rollback

# Recréer toutes les tables (⚠️ supprime les données)
php artisan migrate:fresh

# Recréer et remplir avec des données de test
php artisan migrate:fresh --seed

# Voir toutes les routes
php artisan route:list

# Nettoyer tous les caches
php artisan optimize:clear
```

## 📝 Notes importantes

1. **Sauvegarde** : Si vous avez des données importantes dans SQLite, sauvegardez le fichier `database/database.sqlite` avant la migration

2. **Performances** : MySQL est généralement plus performant que SQLite pour les applications web

3. **Caractères spéciaux** : Le charset `utf8mb4` supporte tous les caractères Unicode, y compris les emojis

4. **Développement local** : Cette configuration est pour le développement local avec XAMPP. Pour la production, modifiez les paramètres de sécurité

## 🎯 Prochaines étapes

Après la migration réussie :

1. ✅ Testez toutes les fonctionnalités de l'application
2. ✅ Créez un utilisateur administrateur :
   ```bash
   php create_admin.php
   ```
3. ✅ Vérifiez que toutes les relations entre tables fonctionnent
4. ✅ Testez l'upload de fichiers et images
5. ✅ Configurez les sauvegardes automatiques de la base de données

## 📧 Support

Si vous rencontrez des problèmes :
1. Vérifiez les logs Laravel : `storage/logs/laravel.log`
2. Vérifiez les erreurs MySQL dans phpMyAdmin
3. Consultez la documentation Laravel : https://laravel.com/docs

---

**Bon déploiement ! 🚀**

















