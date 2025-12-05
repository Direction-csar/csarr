# 📧 Guide des Notifications Email - Plateforme CSAR

## 📋 Vue d'ensemble

Le système de notifications email automatiques de la plateforme CSAR permet d'envoyer des emails contextuels aux utilisateurs lors d'événements importants. Ce guide explique comment utiliser, configurer et maintenir ce système.

## 🎯 Types de Notifications

### 1. 🎉 Notification de Bienvenue
- **Déclencheur** : Création d'un nouvel utilisateur
- **Destinataire** : Le nouvel utilisateur
- **Contenu** : Informations de connexion, rôle, mot de passe temporaire
- **Automatique** : ✅ Oui

### 2. 📋 Assignation de Tâche
- **Déclencheur** : Assignation d'une nouvelle tâche
- **Destinataire** : L'utilisateur assigné à la tâche
- **Contenu** : Détails de la tâche, priorité, échéance
- **Automatique** : ✅ Oui

### 3. 📬 Changement de Statut de Demande
- **Déclencheur** : Mise à jour du statut d'une demande publique
- **Destinataire** : Le demandeur (email externe)
- **Contenu** : Nouveau statut, commentaire admin, code de suivi
- **Automatique** : ✅ Oui

### 4. 🚨 Alerte de Prix
- **Déclencheur** : Création d'une nouvelle alerte de prix
- **Destinataire** : Administrateurs et responsables
- **Contenu** : Détails du produit, pourcentage d'augmentation, niveau d'alerte
- **Automatique** : ✅ Oui

### 5. 📰 Nouvelle Actualité
- **Déclencheur** : Publication d'une actualité
- **Destinataire** : Utilisateurs abonnés aux actualités
- **Contenu** : Titre, type, extrait du contenu
- **Automatique** : ✅ Oui (selon préférences)

### 6. 📊 Digest Hebdomadaire
- **Déclencheur** : Tous les lundis à 8h ou manuellement
- **Destinataire** : Utilisateurs abonnés au digest
- **Contenu** : Résumé des activités de la semaine
- **Automatique** : ⚙️ Planifié

## ⚙️ Configuration

### Prérequis
1. **Serveur SMTP configuré** (Gmail, Outlook, ou serveur personnalisé)
2. **Fichier .env configuré** avec les paramètres email
3. **Table notification_preferences** créée

### Configuration SMTP

#### Gmail
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="CSAR Platform"
```

#### Outlook
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@outlook.com
MAIL_PASSWORD=votre-mot-de-passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@outlook.com
MAIL_FROM_NAME="CSAR Platform"
```

### Installation

1. **Exécuter les migrations** :
   ```bash
   php artisan migrate
   ```

2. **Créer les préférences par défaut** :
   ```bash
   php artisan db:seed --class=NotificationPreferenceSeeder
   ```

3. **Tester la configuration** :
   - Aller sur `/admin/notifications`
   - Utiliser la fonction "Test d'email"

## 🎛️ Interface d'Administration

### Accès
- **URL** : `/admin/notifications`
- **Menu** : Administration → Notifications
- **Permissions** : Tous les utilisateurs connectés

### Fonctionnalités

#### Préférences Personnelles
- **Email global** : Activer/désactiver toutes les notifications
- **Assignation de tâches** : Notifications de nouvelles tâches
- **Mises à jour de demandes** : Changements de statut
- **Alertes de prix** : Notifications d'alertes critiques
- **Actualités** : Publications d'actualités
- **Notifications système** : Messages importants du système
- **Digest hebdomadaire** : Résumé d'activité hebdomadaire

#### Test et Vérification
- **Test d'email** : Envoyer un email de test
- **Vérification de configuration** : Status SMTP
- **Envoi manuel de digest** : Forcer l'envoi du digest

#### Guide de Configuration
- **Instructions détaillées** pour Gmail, Outlook, serveur personnalisé
- **Exemples de configuration** copiables
- **Étapes de vérification**

## 🔧 Utilisation Technique

### Envoyer une Notification Manuelle

```php
use App\Services\NotificationService;
use App\Models\User;

$notificationService = new NotificationService();
$user = User::find(1);

// Notification de bienvenue
$notificationService->sendWelcomeNotification($user, 'mot-de-passe-temporaire');

// Test de configuration
$success = $notificationService->testEmail('test@example.com');
```

### Intégration dans les Contrôleurs

Les notifications sont automatiquement déclenchées dans :
- `UserController@store` : Notification de bienvenue
- `TaskController@store` : Assignation de tâche
- `RequestController@update` : Changement de statut (à implémenter)
- `PriceAlertController@store` : Alerte de prix (à implémenter)
- `NewsController@store` : Publication d'actualité (à implémenter)

### Commandes Artisan

```bash
# Envoyer le digest hebdomadaire
php artisan notifications:weekly-digest

# Forcer l'envoi même si ce n'est pas lundi
php artisan notifications:weekly-digest --force

# Nettoyer les anciens logs d'audit
php artisan audit:clean --days=30

# Planification automatique (à configurer dans cron)
php artisan schedule:weekly-digest
php artisan schedule:clean-audit
```

## 📊 Modèle de Données

### Table `notification_preferences`
```sql
- id (bigint, primary key)
- user_id (bigint, foreign key → users.id)
- email_enabled (boolean, default: true)
- task_assignments (boolean, default: true)
- request_updates (boolean, default: true)
- price_alerts (boolean, default: true)
- news_updates (boolean, default: false)
- system_notifications (boolean, default: true)
- weekly_digest (boolean, default: false)
- created_at (timestamp)
- updated_at (timestamp)
```

### Relations
- `User::notificationPreferences()` : hasOne
- `NotificationPreference::user()` : belongsTo

## 🚀 Planification et Automatisation

### Cron Jobs Recommandés

```bash
# Dans crontab -e
# Digest hebdomadaire tous les lundis à 8h
0 8 * * 1 cd /path/to/csar && php artisan notifications:weekly-digest

# Nettoyage des logs tous les dimanches à 2h
0 2 * * 0 cd /path/to/csar && php artisan audit:clean --days=90 --force
```

### Laravel Scheduler (Optionnel)
Si vous utilisez Laravel Scheduler, ajoutez dans `routes/console.php` :

```php
Schedule::command('notifications:weekly-digest')
    ->weekly()
    ->mondays()
    ->at('08:00');

Schedule::command('audit:clean --days=90 --force')
    ->weekly()
    ->sundays()
    ->at('02:00');
```

## 🛠️ Maintenance

### Vérifications Régulières
1. **Logs d'email** : Vérifier `storage/logs/laravel.log`
2. **Configuration SMTP** : Tester périodiquement
3. **Préférences utilisateurs** : Surveiller les désabonnements
4. **Performance** : Surveiller la queue si utilisée

### Dépannage

#### Emails non reçus
1. Vérifier la configuration SMTP
2. Contrôler les logs Laravel
3. Vérifier les préférences utilisateur
4. Tester avec un email de test

#### Erreurs communes
- **SMTP Authentication failed** : Vérifier username/password
- **Connection refused** : Vérifier host/port
- **SSL/TLS errors** : Vérifier MAIL_ENCRYPTION

### Monitoring
- **Logs de notification** : Tous les envois sont loggés
- **Métriques de livraison** : À surveiller via les logs
- **Retours d'erreur** : Gestion automatique des échecs

## 📈 Évolutions Futures

### Fonctionnalités Prévues
- **Templates d'email personnalisables**
- **Notifications push** (navigateur)
- **Notifications SMS** (intégration avec SmsService existant)
- **Statistiques de livraison**
- **A/B testing des templates**

### Intégrations Possibles
- **Slack/Teams** : Notifications vers canaux de travail
- **Webhook** : Notifications vers services externes
- **API** : Endpoints pour notifications tierces

## 🔒 Sécurité

### Bonnes Pratiques
- **Mots de passe d'application** pour Gmail
- **Chiffrement TLS** obligatoire
- **Validation des emails** avant envoi
- **Rate limiting** sur les envois
- **Logs sécurisés** (pas de mots de passe)

### Conformité RGPD
- **Consentement** : Préférences opt-in/opt-out
- **Droit à l'oubli** : Suppression automatique avec l'utilisateur
- **Transparence** : Templates clairs et informatifs

---

## 📞 Support

Pour toute question sur le système de notifications :

1. **Documentation technique** : Ce fichier
2. **Interface admin** : `/admin/notifications` pour tests
3. **Logs** : `storage/logs/laravel.log`
4. **Configuration** : Guide intégré dans l'interface

---

*Dernière mise à jour : {{ date('d/m/Y') }}*
*Version : 1.0.0*

