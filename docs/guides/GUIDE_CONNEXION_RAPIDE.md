# 🚀 Guide de Connexion Rapide - CSAR

## ⚡ Solution Rapide

Si vous n'arrivez pas à vous connecter, suivez ces étapes :

### Étape 1 : Vérifier que MySQL est démarré
1. Ouvrez XAMPP Control Panel
2. Vérifiez que MySQL est vert (démarré)
3. Si non, cliquez sur "Start" à côté de MySQL

### Étape 2 : Corriger les comptes utilisateurs
Double-cliquez sur le fichier :
```
corriger_connexion.bat
```

Ce script va :
- ✅ Vérifier tous les comptes utilisateurs
- ✅ Réinitialiser les mots de passe à `password`
- ✅ Activer tous les comptes
- ✅ Corriger les rôles si nécessaire

### Étape 3 : Effacer le cache du navigateur

**Option A : Mode Navigation Privée (Recommandé)**
- Chrome/Edge : `Ctrl + Shift + N`
- Firefox : `Ctrl + Shift + P`

**Option B : Effacer le cache**
1. Appuyez sur `Ctrl + Shift + Delete`
2. Cochez "Cookies" et "Cache"
3. Cliquez sur "Effacer les données"

### Étape 4 : Se connecter

Utilisez les URLs et identifiants suivants :

---

## 🔐 Identifiants de Connexion

**Tous les comptes utilisent le mot de passe : `password`**

### 👨‍💼 ADMINISTRATEUR
```
🌐 URL:      http://localhost:8000/admin/login
📧 Email:    admin@csar.sn
🔑 Password: password
```

### 👔 DIRECTEUR GÉNÉRAL (DG)
```
🌐 URL:      http://localhost:8000/dg/login
📧 Email:    dg@csar.sn
🔑 Password: password
```

### 📦 RESPONSABLE ENTREPÔT
```
🌐 URL:      http://localhost:8000/entrepot/login
📧 Email:    responsable@csar.sn
🔑 Password: password
```

### 🚚 AGENT
```
🌐 URL:      http://localhost:8000/agent/login
📧 Email:    agent@csar.sn
🔑 Password: password
```

### 👨‍💼 DRH
```
🌐 URL:      http://localhost:8000/drh/login
📧 Email:    drh@csar.sn
🔑 Password: password
```

---

## ❌ Erreurs Courantes et Solutions

### Erreur : "419 Page Expired"
**Solution :**
1. Fermez complètement le navigateur
2. Rouvrez-le
3. Allez sur l'URL de connexion
4. Ou utilisez le mode navigation privée

### Erreur : "These credentials do not match our records"
**Solution :**
1. Exécutez `corriger_connexion.bat`
2. Vérifiez que vous utilisez le bon email
3. Le mot de passe est : `password` (en minuscules)

### Erreur : "SQLSTATE[HY000] [2002] Connection refused"
**Solution :**
1. Ouvrez XAMPP Control Panel
2. Démarrez MySQL
3. Attendez que le bouton devienne vert
4. Réessayez de vous connecter

### Erreur : "The page has expired due to inactivity"
**Solution :**
1. Appuyez sur `Ctrl + F5` pour rafraîchir
2. Ou fermez et rouvrez votre navigateur
3. Effacez les cookies du site

---

## 🎯 Accès Rapides

Après connexion, vous serez redirigé vers votre tableau de bord :

| Rôle | Dashboard URL |
|------|--------------|
| Admin | http://localhost:8000/admin/dashboard |
| DG | http://localhost:8000/dg/dashboard |
| Responsable | http://localhost:8000/responsable/dashboard |
| Agent | http://localhost:8000/agent/dashboard |
| DRH | http://localhost:8000/drh/dashboard |

---

## 📞 Dépannage

Si aucune des solutions ci-dessus ne fonctionne :

1. **Vérifiez le serveur Laravel**
   ```bash
   php artisan serve
   ```
   
2. **Nettoyez le cache Laravel**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Vérifiez la base de données**
   ```bash
   php artisan migrate:status
   ```

4. **Recréez tous les utilisateurs**
   ```bash
   php verifier_et_corriger_comptes.php
   ```

---

## ✅ Checklist de Connexion

- [ ] MySQL est démarré dans XAMPP
- [ ] Le serveur Laravel est en cours d'exécution (`php artisan serve`)
- [ ] J'ai exécuté `corriger_connexion.bat`
- [ ] J'ai effacé le cache du navigateur ou j'utilise le mode privé
- [ ] J'utilise la bonne URL pour mon rôle
- [ ] J'utilise le bon email : `[role]@csar.sn`
- [ ] J'utilise le mot de passe : `password`

Si tous les points sont cochés et vous ne pouvez toujours pas vous connecter, le problème vient probablement d'ailleurs. Contactez le support technique.

---

**Dernière mise à jour : Octobre 2025**


