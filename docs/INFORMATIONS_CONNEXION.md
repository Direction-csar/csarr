# 🔐 INFORMATIONS DE CONNEXION - PLATEFORME CSAR

## 📋 Comptes Utilisateurs Par Défaut

### 👤 **ADMINISTRATEUR**
```
📧 Email    : admin@csar.sn
🔑 Password : password
👔 Rôle     : Administrateur Système
🏢 Service  : Direction Générale
📱 Téléphone: +221 70 123 45 67
```
**Accès** : Tous les modules, gestion complète du système

---

### 👔 **DIRECTEUR GÉNÉRAL (DG)**
```
📧 Email    : dg@csar.sn
🔑 Password : password
👔 Rôle     : Directrice Générale
🏢 Service  : Direction Générale
📱 Téléphone: +221 70 123 45 68
```
**Accès** : Tableau de bord DG, rapports, statistiques, supervision générale

---

### 📦 **RESPONSABLE D'ENTREPÔT**
```
📧 Email    : responsable@csar.sn
🔑 Password : password
👔 Rôle     : Responsable Entrepôt Dakar
🏢 Service  : Logistique
📱 Téléphone: +221 70 123 45 69
🏭 Entrepôt : Entrepôt Principal
```
**Accès** : Gestion des stocks, mouvements, inventaires de son entrepôt

---

### 🚚 **AGENT CSAR**
```
📧 Email    : agent@csar.sn
🔑 Password : password
👔 Rôle     : Agent de Terrain
🏢 Service  : Opérations
📱 Téléphone: +221 70 123 45 70
```
**Accès** : Saisie des données terrain, distributions, collecte d'informations

---

## 🌐 URLs de Connexion

### Interface Publique
```
🌍 URL: http://localhost:8000
📄 Description: Site public CSAR (accueil, actualités, partenaires, etc.)
```

### Interface Administrateur
```
🔐 URL: http://localhost:8000/login
📄 Description: Page de connexion pour tous les utilisateurs
```

### Tableaux de Bord par Rôle
```
👤 Admin        : http://localhost:8000/admin/dashboard
👔 DG           : http://localhost:8000/dg/dashboard
📦 Responsable  : http://localhost:8000/responsable/dashboard
🚚 Agent        : http://localhost:8000/agent/dashboard
```

---

## 🎭 Hiérarchie des Rôles

```
┌─────────────────────────────────────────────┐
│         1. ADMINISTRATEUR (Admin)           │
│  - Accès total au système                  │
│  - Gestion des utilisateurs                │
│  - Configuration système                   │
│  - Tous les modules                        │
└─────────────────────────────────────────────┘
                    ⬇️
┌─────────────────────────────────────────────┐
│      2. DIRECTEUR GÉNÉRAL (DG)              │
│  - Vue d'ensemble complète                 │
│  - Rapports et statistiques                │
│  - Validation des actions importantes       │
│  - Supervision générale                    │
└─────────────────────────────────────────────┘
                    ⬇️
┌─────────────────────────────────────────────┐
│   3. RESPONSABLE D'ENTREPÔT                 │
│  - Gestion de son entrepôt                 │
│  - Stocks et mouvements                    │
│  - Inventaires                             │
│  - Équipe de son entrepôt                  │
└─────────────────────────────────────────────┘
                    ⬇️
┌─────────────────────────────────────────────┐
│         4. AGENT CSAR                       │
│  - Saisie des données terrain              │
│  - Distributions                           │
│  - Suivi des bénéficiaires                 │
│  - Rapports d'activité                     │
└─────────────────────────────────────────────┘
```

---

## 🔧 Comptes de Test Additionnels

Si des entrepôts supplémentaires ont été créés, il existe aussi :

### Responsables par Entrepôt
```
📧 responsable.[nom-entrepot]@csar.sn
🔑 password
```

### Agents par Entrepôt
```
📧 agent.[nom-entrepot]@csar.sn
🔑 password
```

---

## 🚀 Première Connexion

### Étape 1 : Accéder à la page de connexion
```bash
1. Ouvrez votre navigateur
2. Allez sur : http://localhost:8000/login
```

### Étape 2 : Entrer les identifiants
```
Email    : admin@csar.sn
Password : password
```

### Étape 3 : Cliquer sur "Se connecter"

### Étape 4 : Vous serez redirigé vers votre tableau de bord

---

## 🔒 Sécurité

### ⚠️ IMPORTANT - Environnement de Production

**Pour la production, changez IMMÉDIATEMENT tous les mots de passe !**

```php
// Via la console Laravel
php artisan tinker
>>> $user = User::where('email', 'admin@csar.sn')->first();
>>> $user->password = Hash::make('nouveau_mot_de_passe_securise');
>>> $user->save();
```

Ou via l'interface admin :
1. Connectez-vous en tant qu'admin
2. Allez dans "Utilisateurs" > "Gestion des utilisateurs"
3. Modifiez chaque utilisateur
4. Changez le mot de passe
5. Activez l'authentification à deux facteurs (si disponible)

---

## 📊 Permissions par Rôle

| Module / Fonctionnalité | Admin | DG | Responsable | Agent |
|-------------------------|-------|-----|-------------|-------|
| Dashboard général       | ✅    | ✅  | ✅          | ✅    |
| Gestion utilisateurs    | ✅    | ❌  | ❌          | ❌    |
| Configuration système   | ✅    | ❌  | ❌          | ❌    |
| Rapports complets       | ✅    | ✅  | 📍 Limité   | 📍 Limité |
| Gestion stocks          | ✅    | ✅  | ✅          | ❌    |
| Mouvements stocks       | ✅    | ✅  | ✅          | ✅    |
| Gestion entrepôts       | ✅    | ✅  | 📍 Son entrepôt | ❌ |
| Gestion personnel       | ✅    | ✅  | ❌          | ❌    |
| Partenaires             | ✅    | ✅  | ❌          | ❌    |
| Actualités              | ✅    | ✅  | ❌          | ❌    |
| SIM (Prix)              | ✅    | ✅  | ✅          | ✅    |
| Demandes publiques      | ✅    | ✅  | ✅          | ✅    |
| Alertes système         | ✅    | ✅  | 📍 Son entrepôt | ❌ |

**Légende** :
- ✅ Accès complet
- ❌ Pas d'accès
- 📍 Accès limité/restreint

---

## 🆘 Dépannage

### Mot de passe oublié ?
```
1. Utilisez la fonction "Mot de passe oublié" sur la page de connexion
2. Ou réinitialisez via Tinker :
   php artisan tinker
   >>> $user = User::where('email', 'admin@csar.sn')->first();
   >>> $user->password = Hash::make('password');
   >>> $user->save();
```

### Compte bloqué ?
```
php artisan tinker
>>> $user = User::where('email', 'admin@csar.sn')->first();
>>> $user->is_active = true;
>>> $user->save();
```

### Créer un nouvel administrateur ?
```bash
php artisan tinker
>>> use App\Models\User;
>>> use Illuminate\Support\Facades\Hash;
>>> User::create([
    'name' => 'Nouvel Admin',
    'email' => 'nouvel.admin@csar.sn',
    'password' => Hash::make('password'),
    'role' => 'admin',
    'is_active' => true
]);
```

---

## 📞 Support

Pour toute assistance :
- 📧 Email : support@csar.sn
- 📱 Téléphone : +221 33 123 45 67
- 🌐 Site : https://csar.sn

---

## ✅ Checklist de Sécurité

Avant de déployer en production :

- [ ] Changer tous les mots de passe par défaut
- [ ] Utiliser des mots de passe forts (min 12 caractères)
- [ ] Activer l'authentification à deux facteurs
- [ ] Configurer les limites de tentatives de connexion
- [ ] Activer les logs de connexion
- [ ] Restreindre l'accès par IP si possible
- [ ] Configurer HTTPS/SSL
- [ ] Sauvegarder régulièrement la base de données
- [ ] Tester la procédure de récupération de compte

---

**Document créé le :** {{ date('Y-m-d H:i:s') }}  
**Version :** 1.0  
**Plateforme :** CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience















