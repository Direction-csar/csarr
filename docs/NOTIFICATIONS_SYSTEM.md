# 🔔 Système de Notifications CSAR

## Vue d'ensemble

Le système de notifications CSAR est un système complet et automatique qui génère des notifications pour toutes les actions importantes sur la plateforme (interne + publique).

## 📋 Fonctionnalités

### Notifications automatiques pour :

1. **Demandes d'aide** - Lorsqu'un citoyen soumet une demande depuis la page publique
2. **Messages de contact** - Lorsqu'un utilisateur envoie un message via la page Contact
3. **Inscriptions newsletter** - Lorsqu'un visiteur s'inscrit à la newsletter
4. **Communications officielles** - Lorsqu'une actualité est publiée

### Fonctionnalités techniques :

✅ **Notifications en temps réel** avec rafraîchissement automatique (30 secondes)  
✅ **Badge de compteur** dynamique sur l'icône cloche  
✅ **Dropdown moderne** avec liste des dernières notifications  
✅ **Page complète** de centre de notifications avec filtres  
✅ **Actions disponibles** : Marquer lu/non lu, Supprimer  
✅ **Design moderne** avec animations 3D et effets  
✅ **Responsive** compatible mobile et tablette  
✅ **Accessibilité** conforme aux standards WCAG  

## 🗂️ Structure du système

### 1. Base de données

**Table : `notifications`**

```sql
- id (bigint)
- type (string) - Type de notification (demande, message, newsletter, communication)
- icon (string) - Icône Lucide/FontAwesome
- title (string) - Titre de la notification
- message (text) - Contenu du message
- data (json) - Données supplémentaires
- read (boolean) - État de lecture
- user_id (bigint, nullable) - Utilisateur destinataire
- notifiable_type (string, nullable) - Type d'entité liée (polymorphic)
- notifiable_id (bigint, nullable) - ID de l'entité liée (polymorphic)
- action_url (string, nullable) - URL de redirection
- read_at (timestamp, nullable) - Date de lecture
- created_at (timestamp)
- updated_at (timestamp)
```

### 2. Modèle

**`App\Models\Notification`**

Méthodes disponibles :
- `createNotification($title, $message, $type, $data, $notifiable, $icon, $actionUrl)` - Créer une notification
- `markAsRead()` - Marquer comme lue
- `markAsUnread()` - Marquer comme non lue
- `markAllAsRead()` - Marquer toutes comme lues (statique)
- `getStats()` - Obtenir les statistiques
- `getRecent($limit)` - Obtenir les récentes
- `getUnread($limit)` - Obtenir les non lues

### 3. Événements et Listeners

#### Événements :
- `App\Events\DemandeCreated` - Déclenché lors de la création d'une demande
- `App\Events\MessageReceived` - Déclenché lors de la réception d'un message
- `App\Events\NewsletterSubscribed` - Déclenché lors d'une inscription newsletter
- `App\Events\CommunicationPublished` - Déclenché lors de la publication d'une actualité

#### Listeners :
- `App\Listeners\SendDemandeNotification` - Crée une notification pour demande
- `App\Listeners\SendMessageNotification` - Crée une notification pour message
- `App\Listeners\SendNewsletterNotification` - Crée une notification pour newsletter
- `App\Listeners\SendCommunicationNotification` - Crée une notification pour communication

### 4. Contrôleur

**`App\Http\Controllers\Admin\NotificationsController`**

Méthodes :
- `index()` - Afficher le centre de notifications
- `show($id)` - Afficher une notification
- `getNotifications()` - API pour récupérer les notifications (dropdown)
- `getUnreadCount()` - API pour le compteur
- `markAsRead($id)` - Marquer comme lue
- `markAsUnread($id)` - Marquer comme non lue
- `markAllAsRead()` - Tout marquer comme lu
- `destroy($id)` - Supprimer une notification
- `store(Request)` - Créer manuellement (pour tests)

### 5. Routes

#### Routes web :
```php
GET  /admin/notifications              - Centre de notifications
GET  /admin/notifications/{id}         - Détails d'une notification
POST /admin/notifications/{id}/mark-read    - Marquer comme lue
POST /admin/notifications/{id}/mark-unread  - Marquer comme non lue
POST /admin/notifications/mark-all-read     - Tout marquer comme lu
POST /admin/notifications              - Créer (manuel)
DELETE /admin/notifications/{id}       - Supprimer
```

#### Routes API :
```php
GET  /admin/api/notifications          - Liste pour dropdown
GET  /admin/api/notifications/count    - Compteur non lues
POST /admin/api/notifications/{id}/mark-read    - Marquer comme lue
POST /admin/api/notifications/{id}/mark-unread  - Marquer comme non lue
POST /admin/api/notifications/mark-all-read     - Tout marquer comme lu
DELETE /admin/api/notifications/{id}   - Supprimer
```

### 6. Vues Blade

- `resources/views/components/notification-bell.blade.php` - Composant icône cloche
- `resources/views/admin/notifications/index.blade.php` - Page du centre de notifications

### 7. Assets

- `public/css/notifications.css` - Styles modernes
- `public/js/notifications.js` - JavaScript interactif

## 🚀 Installation

### 1. Exécuter les migrations

```bash
php artisan migrate
```

### 2. Vérifier les événements

Les événements sont enregistrés dans `app/Providers/EventServiceProvider.php`

### 3. Tester le système

#### Test 1 : Créer une demande
Allez sur la page publique et soumettez une demande d'aide. Une notification devrait apparaître dans le tableau de bord admin.

#### Test 2 : Envoyer un message
Utilisez le formulaire de contact. Une notification devrait être générée.

#### Test 3 : S'inscrire à la newsletter
Inscrivez-vous avec un email. Une notification devrait apparaître.

#### Test 4 : Publier une actualité
Créez et publiez une actualité depuis l'admin. Une notification devrait être générée.

## 💡 Utilisation

### Dans un contrôleur

Pour créer une notification manuellement :

```php
use App\Models\Notification;

Notification::createNotification(
    'Titre de la notification',
    'Message de la notification',
    'info',                    // Type: info, success, warning, error, demande, message, newsletter, communication
    ['key' => 'value'],        // Données supplémentaires (optionnel)
    $entity,                   // Entité liée (optionnel)
    'bell',                    // Icône (optionnel, auto si non fourni)
    route('admin.dashboard')   // URL de redirection (optionnel)
);
```

### Déclencher un événement

```php
use App\Events\DemandeCreated;

$demande = Demande::create([...]);
event(new DemandeCreated($demande));
```

### Accéder aux notifications dans les vues

Le dropdown est automatiquement intégré dans le layout admin. Le JavaScript se charge du rafraîchissement automatique.

### Personnaliser les icônes

Les icônes par défaut sont définies dans `Notification::getDefaultIcon()`. Vous pouvez les modifier ou en ajouter.

## 🎨 Personnalisation

### Modifier le style

Éditez `public/css/notifications.css` pour personnaliser l'apparence.

### Changer le délai de rafraîchissement

Dans `public/js/notifications.js`, modifiez la propriété `refreshInterval` (en millisecondes) :

```javascript
this.refreshInterval = 30000; // 30 secondes par défaut
```

### Ajouter de nouveaux types de notifications

1. Créer l'événement dans `app/Events/`
2. Créer le listener dans `app/Listeners/`
3. Enregistrer dans `EventServiceProvider.php`
4. Déclencher l'événement dans le contrôleur approprié

## 📊 API JavaScript

Le système expose un objet global `notificationSystem` avec les méthodes suivantes :

```javascript
// Charger les notifications
notificationSystem.loadNotifications();

// Mettre à jour le badge
notificationSystem.updateBadge();

// Marquer comme lue
notificationSystem.markAsRead(notificationId);

// Marquer comme non lue
notificationSystem.markAsUnread(notificationId);

// Tout marquer comme lu
notificationSystem.markAllAsRead();

// Supprimer une notification
notificationSystem.deleteNotification(notificationId);
```

## 🔒 Sécurité

- ✅ Protection CSRF sur toutes les routes POST/DELETE
- ✅ Validation des données d'entrée
- ✅ Filtrage XSS sur les messages
- ✅ Authentification requise pour toutes les routes admin

## 📱 Responsive

Le système est entièrement responsive et s'adapte à :
- Desktop (> 1200px)
- Tablette (768px - 1199px)
- Mobile (< 768px)

## ♿ Accessibilité

- ✅ Support clavier complet
- ✅ Attributs ARIA appropriés
- ✅ Contraste de couleurs conforme WCAG 2.1
- ✅ Réduction des animations si préférence utilisateur

## 🐛 Dépannage

### Les notifications ne s'affichent pas

1. Vérifiez que les migrations sont exécutées
2. Vérifiez que les fichiers CSS et JS sont chargés
3. Ouvrez la console du navigateur pour voir les erreurs
4. Vérifiez que les routes API sont bien définies

### Le compteur ne se met pas à jour

1. Vérifiez la console JavaScript
2. Vérifiez que la route `/admin/api/notifications/count` fonctionne
3. Vérifiez les permissions CORS si applicable

### Les événements ne se déclenchent pas

1. Vérifiez que les événements sont enregistrés dans `EventServiceProvider`
2. Exécutez `php artisan event:list` pour voir les événements
3. Vérifiez les logs dans `storage/logs/laravel.log`

## 📝 Notes

- Les notifications sont polymorphiques et peuvent être liées à n'importe quelle entité
- Le système supporte les files d'attente (queues) pour les listeners
- Les notifications peuvent être ciblées vers un utilisateur spécifique ou globales
- Le rafraîchissement automatique s'arrête quand la page perd le focus (économie de ressources)

## 🔮 Évolutions futures

- [ ] Intégration SMS via API Orange Sénégal
- [ ] Notifications email automatiques
- [ ] Notifications push (PWA)
- [ ] Préférences utilisateur par type de notification
- [ ] Groupement de notifications similaires
- [ ] Historique des notifications archivées

## 📞 Support

Pour toute question ou problème, consultez les logs ou contactez l'équipe de développement.

---

**Version :** 1.0  
**Date :** Octobre 2025  
**Auteur :** Équipe CSAR

