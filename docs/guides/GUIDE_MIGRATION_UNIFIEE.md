# 🔄 Guide de Migration - Base de Données Unifiée

## ✅ Étapes de Migration

### 1. Sauvegarde
```bash
# Sauvegarder la base actuelle
mysqldump -u laravel_user -p csar_platform_2025 > backup_before_unification.sql
```

### 2. Nettoyage des données fictives
```bash
# Exécuter le script de nettoyage
php cleanup_fake_data.php
```

### 3. Vérification des connexions
```bash
# Vérifier que tout fonctionne
php verify_connections.php
```

### 4. Test des interfaces
- **Admin**: http://localhost:8000/admin
- **DG**: http://localhost:8000/dg
- **DRH**: http://localhost:8000/drh
- **Agent**: http://localhost:8000/agent
- **Responsable**: http://localhost:8000/entrepot
- **Public**: http://localhost:8000

## 🔧 Configuration Unifiée

### Base de Données MySQL
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=csar_platform_2025
DB_USERNAME=laravel_user
DB_PASSWORD=csar@2025Host1
```

### Tables Principales
- `users` - Utilisateurs de toutes les interfaces
- `messages` - Messages admin
- `notifications` - Notifications système
- `newsletter_subscribers` - Abonnés newsletter
- `contact_messages` - Messages de contact public
- `public_requests` - Demandes publiques

## 🎯 Résultat Final

✅ **Toutes les interfaces connectées à la même base MySQL**  
✅ **Données fictives supprimées**  
✅ **Configuration unifiée**  
✅ **Sécurité renforcée**  

La plateforme CSAR utilise maintenant une base de données MySQL unifiée et sécurisée.
