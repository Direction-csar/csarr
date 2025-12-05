# 🔐 GUIDE DE CONNEXION - PLATEFORME CSAR

## ✅ Problème résolu !

Les utilisateurs et rôles ont été créés avec succès. Voici comment vous connecter :

---

## 🚀 ÉTAPES POUR SE CONNECTER

### 1. Démarrer le serveur Laravel
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### 2. Ouvrir votre navigateur
Allez sur l'URL correspondante à votre rôle :

---

## 👤 COMPTES DE CONNEXION

### 🔧 ADMINISTRATEUR
- **URL** : http://localhost:8000/admin/login
- **Email** : admin@csar.sn
- **Password** : password
- **Accès** : Tous les modules, gestion complète

### 👔 DIRECTEUR GÉNÉRAL (DG)
- **URL** : http://localhost:8000/dg/login
- **Email** : dg@csar.sn
- **Password** : password
- **Accès** : Tableau de bord DG, rapports, statistiques

### 📦 RESPONSABLE D'ENTREPÔT
- **URL** : http://localhost:8000/entrepot/login
- **Email** : responsable@csar.sn
- **Password** : password
- **Accès** : Gestion des stocks, mouvements, inventaires

### 🚚 AGENT CSAR
- **URL** : http://localhost:8000/agent/login
- **Email** : agent@csar.sn
- **Password** : password
- **Accès** : Saisie des données terrain, distributions

### 👨‍💼 DRH (Direction des Ressources Humaines)
- **URL** : http://localhost:8000/drh/login
- **Email** : drh@csar.sn
- **Password** : password
- **Accès** : Gestion du personnel, RH

---

## 🛠️ EN CAS DE PROBLÈME

### Erreur "419 PAGE EXPIRED"
1. **Fermez complètement** votre navigateur
2. **Rouvrez-le**
3. **Effacez le cache** : Ctrl + Shift + Delete
4. **Ou utilisez le mode privé** : Ctrl + Shift + N
5. **Reconnectez-vous**

### Le serveur ne répond pas
1. **Vérifiez que XAMPP est démarré**
2. **Vérifiez que MySQL est en cours d'exécution**
3. **Redémarrez le serveur Laravel** :
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

### Erreur de connexion à la base de données
1. **Vérifiez que MySQL est démarré dans XAMPP**
2. **Vérifiez que la base `csar_platform` existe**
3. **Vérifiez les identifiants MySQL** (root, pas de mot de passe)

---

## 📋 RÉSUMÉ RAPIDE

1. **Démarrez le serveur** : `php artisan serve --host=0.0.0.0 --port=8000`
2. **Choisissez votre rôle** et allez sur l'URL correspondante
3. **Connectez-vous** avec :
   - Email : `[role]@csar.sn`
   - Password : `password`
4. **En cas d'erreur 419** : Videz le cache du navigateur

---

## 🎯 TABLEAUX DE BORD APRÈS CONNEXION

Après connexion réussie, vous serez redirigé vers :
- **Admin** → `/admin/dashboard`
- **DG** → `/dg/dashboard`
- **Responsable** → `/responsable/dashboard`
- **Agent** → `/agent/dashboard`
- **DRH** → `/drh/dashboard`

---

## ✅ TOUT EST PRÊT !

Les utilisateurs ont été créés avec succès. Vous pouvez maintenant vous connecter à la plateforme interne CSAR avec les identifiants ci-dessus.

**Bonne connexion !** 🚀
