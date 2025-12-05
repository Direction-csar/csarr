# Migration MySQL - Étapes Simples

## ✅ Étape 1 : Créer le fichier .env

1. Ouvrez le dossier du projet : `C:\xampp\htdocs\csar-platform`
2. Créez un nouveau fichier nommé `.env` (avec le point devant)
3. Copiez-collez ce contenu dans le fichier :

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

4. Sauvegardez le fichier

## ✅ Étape 2 : Vérifier que MySQL est démarré

1. Ouvrez le panneau de contrôle XAMPP
2. Assurez-vous que **MySQL** est démarré (bouton vert "Running")
3. Si ce n'est pas le cas, cliquez sur **Start** à côté de MySQL

## ✅ Étape 3 : La base de données existe déjà !

✓ La base de données **plateforme-csar** existe déjà dans MySQL
✓ Vous pouvez la voir dans phpMyAdmin : http://localhost/phpmyadmin

## ✅ Étape 4 : Ouvrir un Terminal (Command Prompt, pas PowerShell)

1. Appuyez sur **Windows + R**
2. Tapez `cmd` et appuyez sur **Entrée**
3. Allez dans le dossier du projet :
   ```cmd
   cd C:\xampp\htdocs\csar-platform
   ```

## ✅ Étape 5 : Exécuter les commandes de migration

Copiez-collez ces commandes **une par une** dans le terminal CMD :

### 5.1 Générer la clé d'application
```cmd
C:\xampp\php\php.exe artisan key:generate
```

### 5.2 Nettoyer le cache (peut avoir des warnings, c'est normal)
```cmd
C:\xampp\php\php.exe artisan config:clear
```

### 5.3 Exécuter les migrations (créer les tables)
```cmd
C:\xampp\php\php.exe artisan migrate:fresh
```

⚠️ **Important** : Cette commande va créer environ 50 tables dans votre base de données !

### 5.4 Insérer les données initiales
```cmd
C:\xampp\php\php.exe artisan db:seed
```

## ✅ Étape 6 : Vérifier dans phpMyAdmin

1. Ouvrez : http://localhost/phpmyadmin
2. Cliquez sur la base de données **plateforme-csar** dans la barre latérale gauche
3. Vous devriez voir toutes les tables créées :
   - users
   - roles
   - warehouses
   - stocks
   - stock_movements
   - news
   - demandes
   - personnel
   - sim_reports
   - ... et bien d'autres !

## ✅ Étape 7 : Créer un utilisateur administrateur

Dans le même terminal CMD :

```cmd
C:\xampp\php\php.exe create_admin.php
```

Ou utilisez un des scripts existants :
```cmd
C:\xampp\php\php.exe create_admin_user.php
```

## ✅ Étape 8 : Démarrer l'application

```cmd
C:\xampp\php\php.exe artisan serve
```

Puis ouvrez votre navigateur et allez à : **http://localhost:8000**

## 🎯 Résumé des commandes (ordre complet)

Si vous voulez tout faire d'un coup, voici toutes les commandes dans l'ordre :

```cmd
cd C:\xampp\htdocs\csar-platform
C:\xampp\php\php.exe artisan key:generate
C:\xampp\php\php.exe artisan config:clear
C:\xampp\php\php.exe artisan migrate:fresh
C:\xampp\php\php.exe artisan db:seed
C:\xampp\php\php.exe create_admin.php
C:\xampp\php\php.exe artisan serve
```

## ❓ Problèmes courants

### Erreur: "Access denied for user 'root'"
- Vérifiez que MySQL est bien démarré dans XAMPP
- Si vous avez un mot de passe MySQL, modifiez la ligne `DB_PASSWORD=` dans `.env`

### Erreur: "Database 'plateforme-csar' doesn't exist"
- La base de données existe déjà selon nos vérifications
- Si ce n'est pas le cas, créez-la dans phpMyAdmin

### Warning: "proc_open(): CreateProcess failed"
- C'est un warning, pas une erreur
- Les commandes devraient quand même fonctionner
- Ignorez ces messages

### Les migrations ne créent aucune table
- Vérifiez que le fichier `.env` existe bien
- Vérifiez que `DB_DATABASE=plateforme-csar` est bien écrit
- Relancez : `C:\xampp\php\php.exe artisan migrate:fresh`

## 📞 Besoin d'aide ?

Si quelque chose ne fonctionne pas :

1. Vérifiez les logs Laravel : `storage/logs/laravel.log`
2. Vérifiez que MySQL est bien démarré
3. Vérifiez le fichier `.env`
4. Essayez de recréer la base de données dans phpMyAdmin

---

**Bonne migration ! 🚀**

















