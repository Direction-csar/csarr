# 🧪 Plan de Test QA - Plateforme CSAR Institutionnelle

## 📋 Vue d'ensemble

Ce document présente le plan de test complet pour la plateforme CSAR transformée en plateforme institutionnelle. Tous les tests doivent être exécutés avant le déploiement en production.

---

## 🎯 Objectifs des Tests

- ✅ Vérifier la connectivité MySQL complète
- ✅ Valider tous les formulaires et leurs validations
- ✅ Tester le système de notifications temps réel
- ✅ Vérifier la prévention des doublons
- ✅ Tester l'automatisation email/SMS
- ✅ Valider la carte Leaflet interactive
- ✅ Vérifier la suppression du contenu demo
- ✅ Tester la sécurité et l'audit

---

## 🔧 Tests Techniques

### 1. **Tests de Base de Données**

#### 1.1 Connexion MySQL
```bash
# Test de connexion
php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::table('users')->count();
```

**Critères de réussite :**
- [ ] Connexion MySQL établie
- [ ] Toutes les tables créées (52 tables)
- [ ] Utilisateur admin créé
- [ ] Données de test supprimées

#### 1.2 Intégrité des Données
```bash
# Vérifier les relations
php artisan db:show --table=users
php artisan db:show --table=demandes
php artisan db:show --table=warehouses
```

**Critères de réussite :**
- [ ] Toutes les clés étrangères fonctionnelles
- [ ] Contraintes d'intégrité respectées
- [ ] Index de performance créés

### 2. **Tests des Formulaires**

#### 2.1 Formulaire de Demande d'Aide
**URL :** `/demande`

**Tests de validation :**
- [ ] Champs obligatoires (nom, email, téléphone, région, description)
- [ ] Format email valide
- [ ] Numéro téléphone sénégalais valide
- [ ] Description minimum 10 caractères
- [ ] Types de demande valides (aide, partenariat, audience, autre)

**Tests fonctionnels :**
- [ ] Soumission réussie avec données valides
- [ ] Génération du code de suivi unique
- [ ] Création du hash de doublon
- [ ] Enregistrement en base MySQL
- [ ] Toast de confirmation affiché
- [ ] Email de confirmation envoyé
- [ ] SMS envoyé si demandé
- [ ] Notification admin créée
- [ ] Journal d'audit enregistré

**Tests de sécurité :**
- [ ] Prévention des doublons (24h)
- [ ] Rate limiting (5 demandes/heure)
- [ ] Sanitisation des données
- [ ] Protection CSRF

#### 2.2 Formulaire de Contact
**URL :** `/contact`

**Tests :**
- [ ] Validation des champs
- [ ] Envoi email confirmation
- [ ] Notification interne admin
- [ ] Prévention doublons

#### 2.3 Newsletter
**URL :** Partout sur le site

**Tests :**
- [ ] Inscription email valide
- [ ] Prévention doublons (1h)
- [ ] Email de bienvenue
- [ ] Notification admin

### 3. **Tests du Système de Notifications**

#### 3.1 Notifications Temps Réel
**Configuration Pusher/Echo :**

```javascript
// Test de connexion
window.Echo.private('notifications.1')
    .listen('.notification.sent', (e) => {
        console.log('Notification reçue:', e);
    });
```

**Tests :**
- [ ] Connexion Pusher établie
- [ ] Icône cloche dans le header
- [ ] Compteur de notifications non lues
- [ ] Dropdown des notifications
- [ ] Marquer comme lu
- [ ] Suppression de notification
- [ ] Mise à jour temps réel

#### 3.2 Types de Notifications
- [ ] Nouvelle demande → Notification admin
- [ ] Nouveau contact → Notification admin
- [ ] Inscription newsletter → Notification admin
- [ ] Changement statut demande → Notification demandeur

### 4. **Tests de la Carte Leaflet**

#### 4.1 Fonctionnalités de Base
**URL :** Dashboard admin

**Tests :**
- [ ] Chargement de la carte
- [ ] Affichage des entrepôts
- [ ] Marqueurs colorés par statut
- [ ] Popups informatifs
- [ ] Statistiques en temps réel
- [ ] Boutons d'action (voir, modifier)

#### 4.2 API Endpoints
```bash
# Test des endpoints
curl http://localhost:8000/api/warehouses
curl http://localhost:8000/api/warehouses/stats
```

**Tests :**
- [ ] GET /api/warehouses → Liste des entrepôts
- [ ] GET /api/warehouses/stats → Statistiques
- [ ] GET /api/warehouses/{id} → Détails entrepôt
- [ ] PUT /api/warehouses/{id}/position → Mise à jour position

### 5. **Tests d'Automatisation**

#### 5.1 Emails Automatiques
**Configuration SMTP :**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=contact@csar.sn
MAIL_PASSWORD=your-app-password
```

**Tests :**
- [ ] Email confirmation demande
- [ ] Email notification admin
- [ ] Email confirmation contact
- [ ] Email bienvenue newsletter
- [ ] Queue d'envoi fonctionnelle

#### 5.2 SMS Automatiques
**Configuration SMS :**
```env
SMS_ENABLED=true
SMS_API_KEY=your-api-key
SMS_API_URL=https://api.sms-provider.com
SMS_SENDER_NAME=CSAR
```

**Tests :**
- [ ] SMS confirmation demande
- [ ] SMS mise à jour statut
- [ ] Queue SMS fonctionnelle
- [ ] Gestion des erreurs

### 6. **Tests de Sécurité**

#### 6.1 Prévention des Doublons
**Tests :**
- [ ] Hash de doublon généré
- [ ] Vérification dans les 24h
- [ ] Message d'erreur approprié
- [ ] Logs d'audit

#### 6.2 Journal d'Audit
**Tests :**
- [ ] Enregistrement des actions sensibles
- [ ] Traçabilité des modifications
- [ ] Logs des connexions
- [ ] Données d'audit complètes

#### 6.3 Rate Limiting
**Tests :**
- [ ] Limite de 5 demandes/heure par IP
- [ ] Limite de 10 contacts/heure par IP
- [ ] Limite de 1 newsletter/heure par email
- [ ] Messages d'erreur appropriés

---

## 🎭 Tests d'Interface Utilisateur

### 1. **Tests Responsive**

**Appareils à tester :**
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

**Pages à tester :**
- [ ] Page d'accueil
- [ ] Formulaire de demande
- [ ] Dashboard admin
- [ ] Carte des entrepôts
- [ ] Notifications

### 2. **Tests de Navigation**

**Flux utilisateur :**
- [ ] Accueil → Demande → Succès
- [ ] Accueil → Contact → Confirmation
- [ ] Admin → Dashboard → Notifications
- [ ] Admin → Entrepôts → Carte

### 3. **Tests d'Accessibilité**

**Critères :**
- [ ] Contraste des couleurs
- [ ] Navigation au clavier
- [ ] Alt text sur les images
- [ ] Labels sur les formulaires

---

## 🚀 Tests de Performance

### 1. **Tests de Charge**

**Outils :** Apache Bench, JMeter

```bash
# Test de charge sur le formulaire
ab -n 100 -c 10 http://localhost:8000/demande
```

**Critères :**
- [ ] Temps de réponse < 2s
- [ ] Support de 100 utilisateurs simultanés
- [ ] Pas d'erreurs 500
- [ ] Base de données stable

### 2. **Tests de Base de Données**

**Requêtes à optimiser :**
- [ ] Dashboard admin (statistiques)
- [ ] Liste des demandes
- [ ] Carte des entrepôts
- [ ] Notifications

---

## 📊 Tests de Données

### 1. **Suppression du Contenu Demo**

**Vérifications :**
- [ ] Aucune donnée fictive dans les contrôleurs
- [ ] Messages "Aucune donnée disponible" si vide
- [ ] Statistiques basées sur vraies données
- [ ] Actualités vides par défaut
- [ ] Rapports vides par défaut

### 2. **Intégrité des Données**

**Tests :**
- [ ] Cohérence des relations
- [ ] Pas de données orphelines
- [ ] Formats de données corrects
- [ ] Encodage UTF-8

---

## 🔍 Tests de Régression

### 1. **Fonctionnalités Existantes**

**À vérifier :**
- [ ] Authentification multi-rôles
- [ ] Dashboard DG
- [ ] Gestion RH
- [ ] Gestion des entrepôts
- [ ] Gestion des stocks

### 2. **Compatibilité**

**Tests :**
- [ ] Navigateurs modernes (Chrome, Firefox, Safari, Edge)
- [ ] Versions PHP 8.1+
- [ ] MySQL 8.0+
- [ ] Laravel 10.x

---

## 📝 Procédure d'Exécution

### 1. **Environnement de Test**

```bash
# Cloner l'environnement de production
cp .env .env.testing
# Modifier les paramètres de test
# Exécuter les migrations
php artisan migrate --env=testing
# Exécuter les seeders
php artisan db:seed --env=testing
```

### 2. **Ordre d'Exécution**

1. **Tests techniques** (Base de données, API)
2. **Tests fonctionnels** (Formulaires, notifications)
3. **Tests d'interface** (Responsive, navigation)
4. **Tests de performance** (Charge, optimisation)
5. **Tests de sécurité** (Doublons, audit)
6. **Tests de régression** (Fonctionnalités existantes)

### 3. **Critères d'Acceptation**

**Pour valider le déploiement :**
- [ ] 100% des tests critiques passent
- [ ] 0 erreur 500 en production
- [ ] Temps de réponse < 2s
- [ ] Toutes les notifications fonctionnelles
- [ ] Carte interactive opérationnelle
- [ ] Emails/SMS envoyés correctement

---

## 🚨 Tests de Sécurité Avancés

### 1. **Tests de Pénétration**

**Outils :** OWASP ZAP, Burp Suite

**Tests :**
- [ ] Injection SQL
- [ ] XSS (Cross-Site Scripting)
- [ ] CSRF (Cross-Site Request Forgery)
- [ ] Authentification bypass
- [ ] Autorisation insuffisante

### 2. **Tests de Données Sensibles**

**Vérifications :**
- [ ] Mots de passe hashés
- [ ] Données personnelles protégées
- [ ] Logs sécurisés
- [ ] Headers de sécurité

---

## 📋 Checklist Finale

### ✅ **Pré-déploiement**
- [ ] Tous les tests passent
- [ ] Configuration production validée
- [ ] Sauvegarde de la base de données
- [ ] Plan de rollback préparé
- [ ] Monitoring configuré

### ✅ **Post-déploiement**
- [ ] Tests de fumée en production
- [ ] Vérification des logs
- [ ] Test des notifications
- [ ] Test des emails/SMS
- [ ] Performance validée

---

## 📞 Support et Maintenance

**En cas de problème :**
1. Vérifier les logs Laravel
2. Contrôler la base de données
3. Tester les services externes (SMTP, SMS)
4. Vérifier la configuration Pusher
5. Contacter l'équipe de développement

**Monitoring continu :**
- [ ] Logs d'erreur
- [ ] Performance de la base
- [ ] Taux de succès des emails/SMS
- [ ] Utilisation des notifications
- [ ] Charge serveur
