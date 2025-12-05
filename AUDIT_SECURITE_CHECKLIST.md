# 🛡️ CHECKLIST AUDIT DE SÉCURITÉ - CSAR PLATFORM

**Date de création** : 24 Octobre 2025  
**Version** : 1.0  
**Responsable** : Équipe Sécurité CSAR

---

## 📋 INTRODUCTION

Cette checklist permet d'effectuer un audit de sécurité complet de la plateforme CSAR.  
À effectuer **trimestriellement** ou après chaque mise à jour majeure.

---

## 1. AUTHENTIFICATION ET AUTORISATION

### 1.1 Gestion des Mots de Passe
- [ ] Les mots de passe sont hachés avec bcrypt (ou mieux)
- [ ] Longueur minimale de 8 caractères imposée
- [ ] Complexité requise (majuscules, minuscules, chiffres)
- [ ] Pas de mots de passe par défaut en production
- [ ] Rotation régulière des mots de passe admin (tous les 90 jours)
- [ ] Politique de réinitialisation sécurisée
- [ ] Tokens de réinitialisation avec expiration (24h max)

**Actions si non conforme** :
```php
// Vérifier la config
config(['auth.passwords.users.expire' => 60]); // minutes
Hash::needsRehash($password); // Si bcrypt à jour
```

### 1.2 Authentification Multi-facteurs (optionnel mais recommandé)
- [ ] MFA disponible pour les admins
- [ ] Options : TOTP, SMS, Email
- [ ] Codes de récupération générés
- [ ] Journalisation des tentatives MFA

### 1.3 Contrôle d'Accès
- [ ] Système de rôles implémenté (Admin, DG, DRH, Responsable, Agent)
- [ ] Permissions granulaires par module
- [ ] Middleware de vérification sur toutes routes sensibles
- [ ] Principe du moindre privilège appliqué
- [ ] Séparation des rôles respectée
- [ ] Pas de privilèges superflus

**Test** :
```bash
# Tester les accès non autorisés
curl -X GET http://localhost/admin/users # Sans auth -> 401/302
curl -X GET http://localhost/admin/users -H "Cookie: session=agent_token" # Agent -> 403
```

### 1.4 Gestion des Sessions
- [ ] Sessions sécurisées (HttpOnly, Secure, SameSite)
- [ ] Expiration automatique après inactivité (120 min)
- [ ] Régénération du session ID après login
- [ ] Déconnexion automatique multi-sessions (optionnel)
- [ ] Pas de session ID dans URL
- [ ] Token CSRF sur tous les formulaires

**Vérifications config** :
```php
// config/session.php
'lifetime' => 120,
'expire_on_close' => false,
'encrypt' => true,
'http_only' => true,
'same_site' => 'lax',
'secure' => env('SESSION_SECURE_COOKIE', true),
```

### 1.5 Protection contre les Attaques
- [ ] Rate limiting sur login (max 5 tentatives/15 min)
- [ ] Captcha après 3 échecs
- [ ] Blocage temporaire après multiples échecs
- [ ] Journalisation des tentatives échouées
- [ ] Alertes sur activités suspectes

---

## 2. PROTECTION DES DONNÉES

### 2.1 Chiffrement
- [ ] HTTPS activé en production (TLS 1.3)
- [ ] Certificat SSL valide et à jour
- [ ] Redirection HTTP → HTTPS automatique
- [ ] HSTS configuré
- [ ] Données sensibles chiffrées en base (mots de passe, tokens)
- [ ] Clés de chiffrement sécurisées (.env, pas dans le code)

**Vérifications** :
```bash
# Tester SSL
curl -I https://csar.sn | grep -i strict-transport-security
openssl s_client -connect csar.sn:443 -tls1_3
```

### 2.2 Validation des Entrées
- [ ] Validation côté serveur sur TOUTES les entrées
- [ ] Whitelist plutôt que blacklist
- [ ] Échappement des sorties (Blade auto-escape)
- [ ] Sanitisation des uploads de fichiers
- [ ] Limitation de taille des uploads (10 MB max)
- [ ] Types de fichiers autorisés définis
- [ ] Scan antivirus des uploads (si service externe)

**Test injection SQL** :
```sql
-- Tester avec : ' OR '1'='1
-- Doit être bloqué ou escapé
```

### 2.3 Protection XSS
- [ ] Échappement automatique dans Blade (`{{ }}`)
- [ ] Pas de `{!! !!}` sauf nécessaire et sanitisé
- [ ] Content Security Policy (CSP) configurée
- [ ] Input filtering sur champs HTML/WYSIWYG
- [ ] Validation stricte des URLs

### 2.4 Protection CSRF
- [ ] Token CSRF sur tous formulaires POST/PUT/DELETE
- [ ] `@csrf` présent dans tous les forms Blade
- [ ] Vérification automatique par middleware
- [ ] Tokens régénérés régulièrement

### 2.5 Protection SQL Injection
- [ ] Utilisation exclusive d'Eloquent ORM
- [ ] Requêtes préparées (prepared statements)
- [ ] Pas de concaténation SQL brute
- [ ] Paramètres liés (binding)

**Audit des requêtes** :
```bash
# Chercher les requêtes brutes
grep -r "DB::raw" app/
grep -r "DB::statement" app/
# Vérifier que les paramètres sont bindés
```

---

## 3. GESTION DES FICHIERS

### 3.1 Uploads
- [ ] Dossier uploads hors de webroot si possible
- [ ] Permissions restrictives (755 dossiers, 644 fichiers)
- [ ] Validation MIME type
- [ ] Renommage des fichiers uploadés
- [ ] Pas d'exécution de scripts uploadés
- [ ] Limitation de taille (10 MB)
- [ ] Quota par utilisateur

### 3.2 Stockage
- [ ] Fichiers sensibles chiffrés
- [ ] Backups réguliers et sécurisés
- [ ] Stockage distant des backups
- [ ] Rotation des logs (30 jours)
- [ ] Nettoyage des fichiers temporaires

---

## 4. AUDIT ET JOURNALISATION

### 4.1 Logs d'Audit
- [ ] Tous les accès admin loggés
- [ ] Toutes les modifications de données loggées
- [ ] Connexions/déconnexions enregistrées
- [ ] Tentatives d'accès non autorisées loggées
- [ ] IP et timestamp systématiques
- [ ] Logs immuables (append-only)
- [ ] Conservation 12 mois minimum

**Tables à vérifier** :
- `audit_logs`
- `sessions`
- `login_attempts`

### 4.2 Monitoring
- [ ] Surveillance des erreurs 500
- [ ] Alertes sur activités suspectes
- [ ] Monitoring des performances
- [ ] Détection d'anomalies
- [ ] Dashboard de sécurité

---

## 5. CONFIGURATION SERVEUR

### 5.1 PHP
- [ ] Version PHP à jour (8.2+)
- [ ] display_errors = Off en production
- [ ] expose_php = Off
- [ ] allow_url_fopen = Off (si possible)
- [ ] disable_functions configuré
- [ ] open_basedir configuré
- [ ] max_execution_time limité (30s)
- [ ] memory_limit approprié (256M)

**Vérifier** :
```bash
php -i | grep display_errors # Off
php -i | grep expose_php # Off
```

### 5.2 MySQL
- [ ] Utilisateur avec privilèges minimaux
- [ ] Pas de compte root utilisé
- [ ] Mots de passe forts
- [ ] Connexions locales uniquement (si possible)
- [ ] Port non standard (optionnel)
- [ ] Backups réguliers
- [ ] Audit des requêtes lentes

### 5.3 Serveur Web (Apache/Nginx)
- [ ] Version à jour
- [ ] Modules inutiles désactivés
- [ ] Signature serveur masquée
- [ ] Index directory listing désactivé
- [ ] Timeouts configurés
- [ ] Compression activée (gzip)
- [ ] Fichiers sensibles protégés (.env, .git)

**Apache .htaccess** :
```apache
# Bloquer accès aux fichiers sensibles
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>
```

---

## 6. DÉPENDANCES ET CODE

### 6.1 Dépendances
- [ ] Composer packages à jour
- [ ] NPM packages à jour
- [ ] Scan de vulnérabilités (composer audit)
- [ ] Pas de dépendances abandonnées
- [ ] Lock files commités (composer.lock, package-lock.json)

**Commandes** :
```bash
composer audit # Scan vulnérabilités
composer outdated # Packages obsolètes
npm audit # Scan NPM
```

### 6.2 Code Source
- [ ] Pas de credentials hardcodés
- [ ] .env dans .gitignore
- [ ] Pas de secrets dans les commits
- [ ] Code review effectuées
- [ ] Analyse statique (PHPStan, Psalm)
- [ ] Linting activé

---

## 7. CONFORMITÉ RGPD

### 7.1 Données Personnelles
- [ ] Inventaire des données personnelles
- [ ] Base légale pour chaque traitement
- [ ] Consentement explicite collecté
- [ ] Politique de confidentialité publiée
- [ ] Mentions légales complètes
- [ ] DPO désigné (si requis)

### 7.2 Droits des Utilisateurs
- [ ] Droit d'accès implémenté
- [ ] Droit de rectification implémenté
- [ ] Droit à l'effacement implémenté
- [ ] Portabilité des données
- [ ] Droit d'opposition
- [ ] Procédure de réclamation

### 7.3 Sécurité RGPD
- [ ] Chiffrement des données sensibles
- [ ] Pseudonymisation si applicable
- [ ] Minimisation des données
- [ ] Conservation limitée dans le temps
- [ ] Registre des traitements tenu
- [ ] PIA (Privacy Impact Assessment) si requis

---

## 8. TESTS DE PÉNÉTRATION

### 8.1 Tests Manuels
- [ ] Test injection SQL
- [ ] Test XSS (reflected, stored, DOM-based)
- [ ] Test CSRF
- [ ] Test d'élévation de privilèges
- [ ] Test de traversée de répertoires
- [ ] Test de force brute
- [ ] Test d'énumération d'utilisateurs

### 8.2 Outils Automatisés
- [ ] OWASP ZAP scan
- [ ] Nikto scan
- [ ] SQLMap test
- [ ] Burp Suite scan
- [ ] SSL Labs test

**Commandes** :
```bash
# Scan OWASP ZAP
docker run -t owasp/zap2docker-stable zap-baseline.py -t https://csar.sn

# Nikto
nikto -h https://csar.sn

# SSL Labs
https://www.ssllabs.com/ssltest/analyze.html?d=csar.sn
```

### 8.3 Fuzzing
- [ ] Fuzzing des formulaires
- [ ] Fuzzing des APIs
- [ ] Test de charge/stress
- [ ] Test de déni de service (DoS)

---

## 9. SAUVEGARDE ET RÉCUPÉRATION

### 9.1 Backups
- [ ] Backups automatiques quotidiens
- [ ] Backups stockés hors site
- [ ] Backups chiffrés
- [ ] Tests de restauration mensuels
- [ ] Procédure documentée
- [ ] Rétention 30 jours
- [ ] Alertes en cas d'échec

### 9.2 Plan de Reprise d'Activité (PRA)
- [ ] PRA documenté
- [ ] Responsabilités définies
- [ ] Procédures de restauration testées
- [ ] RTO/RPO définis
- [ ] Contact d'urgence à jour

---

## 10. FORMATION ET SENSIBILISATION

### 10.1 Personnel
- [ ] Formation sécurité pour les admins
- [ ] Guides de bonnes pratiques
- [ ] Sensibilisation phishing
- [ ] Procédures d'incident
- [ ] Politique de sécurité signée

### 10.2 Documentation
- [ ] Documentation technique à jour
- [ ] Procédures de sécurité documentées
- [ ] Changelog des incidents
- [ ] Runbooks pour incidents

---

## 11. INCIDENT RESPONSE

### 11.1 Détection
- [ ] Monitoring 24/7 (ou business hours)
- [ ] Alertes configurées
- [ ] Logs centralisés
- [ ] SIEM (optionnel)

### 11.2 Réponse
- [ ] Plan de réponse aux incidents
- [ ] Équipe d'intervention définie
- [ ] Procédures d'escalade
- [ ] Communication de crise
- [ ] Post-mortem systématique

---

## 12. SCORING FINAL

### Calcul du Score
- **Critique (blocant)** : Chaque item manquant = -10 points
- **Important** : Chaque item manquant = -5 points  
- **Recommandé** : Chaque item manquant = -2 points

### Niveaux de Sécurité
- **90-100** : ✅ Excellent (Production ready)
- **75-89** : ⚠️ Bon (Améliorations mineures)
- **60-74** : ⚠️ Acceptable (Corrections nécessaires)
- **< 60** : ❌ Insuffisant (Ne pas mettre en production)

---

## 13. ACTIONS CORRECTIVES

### Template de Rapport
```
ITEM: [Description]
STATUT: [ ] Conforme / [ ] Non conforme
CRITICITÉ: [ ] Critique / [ ] Important / [ ] Recommandé
ACTION: [Action corrective à prendre]
RESPONSABLE: [Nom]
ÉCHÉANCE: [Date]
SUIVI: [Statut de résolution]
```

### Priorisation
1. **Urgence Critique** : < 24h
2. **Urgence Haute** : < 1 semaine
3. **Urgence Moyenne** : < 1 mois
4. **Urgence Basse** : < 3 mois

---

## 14. CALENDRIER D'AUDIT

- **Audit complet** : Trimestriel
- **Audit rapide** : Mensuel
- **Scan automatisé** : Hebdomadaire
- **Tests de pénétration** : Annuel (par tiers externe)

---

## ANNEXES

### A. Outils Recommandés
- OWASP ZAP (scan automatisé)
- Burp Suite (tests manuels)
- SQLMap (injection SQL)
- Nikto (scan serveur web)
- SSL Labs (test SSL/TLS)
- Composer Audit (dépendances PHP)
- NPM Audit (dépendances JS)

### B. Ressources
- OWASP Top 10: https://owasp.org/www-project-top-ten/
- CWE Top 25: https://cwe.mitre.org/top25/
- ANSSI Guides: https://www.ssi.gouv.fr/
- Laravel Security Best Practices

### C. Contacts
- **Responsable Sécurité** : [Nom/Email]
- **Admin Système** : [Nom/Email]
- **CERT National** : [Numéro/Email]

---

**Dernière révision** : 24/10/2025  
**Prochaine révision** : 24/01/2026  
**Version** : 1.0

---

© 2025 CSAR - Document confidentiel






















