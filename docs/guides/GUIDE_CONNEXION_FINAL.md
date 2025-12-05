# 🔐 GUIDE DE CONNEXION - PLATEFORME CSAR

## ✅ Résolution du Problème

Votre problème de connexion a été résolu ! Les mots de passe ont été réinitialisés et le cache a été vidé.

---

## 🔑 IDENTIFIANTS DE CONNEXION

### 👑 ADMINISTRATEUR
- **Email:** `admin@csar.sn`
- **Mot de passe:** `password`
- **URL:** http://localhost:8000/admin/login

### 🎯 DIRECTEUR GÉNÉRAL (DG)
- **Email:** `dg@csar.sn`
- **Mot de passe:** `password`
- **URL:** http://localhost:8000/dg/login

### 📦 GESTIONNAIRE D'ENTREPÔT
- **Email:** `entrepot@csar.sn`
- **Mot de passe:** `password`
- **URL:** http://localhost:8000/entrepot/login

### 👤 DRH
- **Email:** `drh@csar.sn`
- **Mot de passe:** `password`
- **URL:** http://localhost:8000 (puis sélectionner le rôle DRH)

---

## 🚀 DÉMARRAGE DE LA PLATEFORME

### Option 1: Démarrage rapide
```bash
php artisan serve
```
Puis ouvrez: http://localhost:8000

### Option 2: Utiliser un port spécifique
```bash
php artisan serve --port=8001
```
Puis ouvrez: http://localhost:8001

---

## ⚠️ SI VOUS NE POUVEZ TOUJOURS PAS VOUS CONNECTER

### 1. Vérifiez que le serveur est démarré
```bash
php artisan serve
```

### 2. Videz tous les caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 3. Réinitialisez à nouveau les mots de passe
```bash
php reset_passwords.php
```

### 4. Vérifiez la base de données
```bash
php diagnostic_connexion_complet.php
```

---

## 🔍 DIAGNOSTIC DES ERREURS COURANTES

### Erreur: "Les identifiants fournis ne correspondent pas"

**Causes possibles:**
1. ❌ Mauvais email ou mot de passe
2. ❌ Cache non vidé
3. ❌ Session ancienne encore active
4. ❌ Mauvaise URL de connexion

**Solutions:**
```bash
# Vider le cache
php artisan cache:clear
php artisan config:clear

# Réinitialiser les mots de passe
php reset_passwords.php

# Redémarrer le serveur
php artisan serve
```

### Erreur 500 (Internal Server Error)

**Solutions:**
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Vider le cache
php artisan cache:clear
php artisan config:clear

# Reconstruire l'autoload
composer dump-autoload
```

### Page blanche / Aucune réponse

**Solutions:**
1. Vérifiez que le serveur est démarré: `php artisan serve`
2. Vérifiez l'URL (doit commencer par `http://localhost:8000`)
3. Vérifiez les logs: `storage/logs/laravel.log`

---

## 📝 NOTES IMPORTANTES

### ⚠️ ATTENTION EN PRODUCTION
- Changez **TOUS** les mots de passe avant de mettre en production
- Utilisez des mots de passe forts et uniques
- Activez l'authentification à deux facteurs si disponible

### 🔐 Sécurité
Les mots de passe actuels (`password`) sont des mots de passe de développement uniquement.
**NE JAMAIS** utiliser ces mots de passe en production.

---

## 📞 SUPPORT

Si vous rencontrez encore des problèmes:

1. **Vérifiez les logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Exécutez le diagnostic:**
   ```bash
   php diagnostic_connexion_complet.php
   ```

3. **Contactez le support technique** avec:
   - Les messages d'erreur exacts
   - Les étapes que vous avez suivies
   - Le résultat du diagnostic

---

## 🎯 RÉCAPITULATIF RAPIDE

| Rôle | Email | Mot de passe | URL |
|------|-------|--------------|-----|
| Admin | admin@csar.sn | password | /admin/login |
| DG | dg@csar.sn | password | /dg/login |
| Entrepôt | entrepot@csar.sn | password | /entrepot/login |
| DRH | drh@csar.sn | password | / (page d'accueil) |

---

## ✅ CHECKLIST DE CONNEXION

- [ ] Le serveur est démarré (`php artisan serve`)
- [ ] Le cache est vidé (`php artisan cache:clear`)
- [ ] Les mots de passe sont réinitialisés (`php reset_passwords.php`)
- [ ] Vous utilisez la bonne URL (voir tableau ci-dessus)
- [ ] Vous utilisez le bon email (voir tableau ci-dessus)
- [ ] Le mot de passe est: `password` (tout en minuscules)

---

**Date de mise à jour:** 20 octobre 2025
**Version:** 1.0


