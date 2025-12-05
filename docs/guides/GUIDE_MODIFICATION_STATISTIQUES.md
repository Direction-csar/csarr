# 📊 Guide de Modification des Statistiques

## 🎯 Système de Statistiques Synchronisées

Les statistiques affichées sur la plateforme CSAR sont **dynamiques** et **synchronisées** automatiquement entre :

- ✅ **Page d'Accueil** : Section "Chiffres Clés Dynamiques"
- ✅ **Page À Propos** : Section "Chiffres clés dynamiques"

---

## 📊 Valeurs Actuelles

### 1. **137** Agents recensés
- Icône : 👥 (users)
- Couleur : Vert (#22c55e)

### 2. **71** Magasins de stockage
- Icône : 🏭 (warehouse)
- Couleur : Bleu (#3b82f6)

### 3. **86** (000 tonnes de capacité)
- Icône : 📦 (boxes)
- Couleur : Violet (#8b5cf6)
- Note : Le "000" est affiché dans le label

### 4. **50+** Années d'expérience
- Icône : 🏆 (award)
- Couleur : Rose (#ec4899)
- Note : Le "+" est ajouté automatiquement

---

## 🔧 Comment Modifier les Statistiques

### Méthode 1 : Via la Base de Données (Recommandé)

Les valeurs sont stockées dans la table `public_contents` :

```sql
-- Modifier les agents
UPDATE public_contents 
SET value = '150' 
WHERE section = 'about' 
AND key_name = 'agents_count';

-- Modifier les magasins
UPDATE public_contents 
SET value = '80' 
WHERE section = 'about' 
AND key_name = 'warehouses_count';

-- Modifier la capacité
UPDATE public_contents 
SET value = '100' 
WHERE section = 'about' 
AND key_name = 'capacity_count';

-- Modifier l'expérience
UPDATE public_contents 
SET value = '60' 
WHERE section = 'about' 
AND key_name = 'experience_count';
```

**Après modification** :
```bash
php artisan optimize:clear
```

### Méthode 2 : Via phpMyAdmin

1. Ouvrir **phpMyAdmin**
2. Sélectionner la base **plateforme-csar**
3. Ouvrir la table **public_contents**
4. Filtrer par `section = 'about'`
5. Modifier les valeurs dans la colonne `value`
6. Sauvegarder
7. Vider le cache : `php artisan optimize:clear`

### Méthode 3 : Créer une Interface Admin (Futur)

Une interface d'administration pourra être créée pour modifier ces valeurs directement depuis le dashboard admin.

---

## 📂 Structure des Données

### Table : `public_contents`

| id | section | key_name | value | created_at | updated_at |
|----|---------|----------|-------|------------|------------|
| 1 | about | agents_count | 137 | ... | ... |
| 2 | about | warehouses_count | 71 | ... | ... |
| 3 | about | capacity_count | 86 | ... | ... |
| 4 | about | experience_count | 50 | ... | ... |

---

## 🔄 Fonctionnement Technique

### 1. **Contrôleur** (`HomeController.php`)

```php
$aboutContent = PublicContent::where('section', 'about')->get()->keyBy('key_name');

$stats = [
    'agents' => $aboutContent->get('agents_count', (object)['value' => '137'])->value,
    'warehouses' => $aboutContent->get('warehouses_count', (object)['value' => '71'])->value,
    'capacity' => $aboutContent->get('capacity_count', (object)['value' => '86'])->value,
    'experience' => $aboutContent->get('experience_count', (object)['value' => '50'])->value
];
```

**Logique** :
- Récupère les valeurs depuis la base de données
- Si la valeur n'existe pas, utilise la valeur par défaut
- Passe les valeurs à la vue

### 2. **Vue** (Page d'Accueil)

```blade
<span class="counter" data-target="{{ $stats['agents'] }}">0</span>
<span class="counter" data-target="{{ $stats['warehouses'] }}">0</span>
<span class="counter" data-target="{{ $stats['capacity'] }}">0</span>
<span class="counter" data-target="{{ $stats['experience'] }}">0</span>
```

**Effet Chrono** :
- Les compteurs démarrent à 0
- Comptent jusqu'à la valeur cible en 2 secondes
- Format français avec espaces (ex: 86 000)

### 3. **Vue** (Page À Propos)

Utilise exactement les mêmes valeurs `$stats`.

---

## 🎬 Effet Chrono

### Page d'Accueil
- ⏱️ Animation : 0 → valeur cible
- 🕒 Durée : 2 secondes
- 📊 Format : Français (avec espaces)
- 🎯 Déclencheur : Quand visible (Intersection Observer)

### Page À Propos
- ⏱️ Animation : 0 → valeur cible
- 🕒 Durée : Variable (selon l'implémentation)
- 💚 Couleur pendant comptage : Vert
- ⚫ Couleur finale : Gris foncé

---

## 📝 Exemple de Modification

### Scénario : Mise à jour annuelle des statistiques

**Nouvelles valeurs** :
- Agents : 137 → **150**
- Magasins : 71 → **75**
- Capacité : 86 → **90**
- Expérience : 50+ → **51+**

**Via SQL** :
```sql
UPDATE public_contents SET value = '150' WHERE key_name = 'agents_count' AND section = 'about';
UPDATE public_contents SET value = '75' WHERE key_name = 'warehouses_count' AND section = 'about';
UPDATE public_contents SET value = '90' WHERE key_name = 'capacity_count' AND section = 'about';
UPDATE public_contents SET value = '51' WHERE key_name = 'experience_count' AND section = 'about';
```

**Vider le cache** :
```bash
php artisan optimize:clear
```

**Résultat** :
- ✅ Page d'accueil mise à jour automatiquement
- ✅ Page À propos mise à jour automatiquement
- ✅ Les deux pages affichent les mêmes valeurs

---

## 🔍 Vérification

### 1. **Vérifier les valeurs dans la base**

```sql
SELECT key_name, value 
FROM public_contents 
WHERE section = 'about' 
AND key_name LIKE '%_count';
```

### 2. **Vérifier sur la page d'accueil**

1. Ouvrir : `http://localhost:8000`
2. Scroll jusqu'à "Chiffres Clés Dynamiques"
3. Observer les compteurs animés

### 3. **Vérifier sur la page À propos**

1. Ouvrir : `http://localhost:8000/a-propos`
2. Scroll jusqu'à "Chiffres clés dynamiques"
3. Observer les compteurs animés

---

## ⚠️ Important

### Ne PAS Modifier Directement

❌ **Ne pas modifier** les valeurs dans :
- `resources/views/public/home.blade.php` (hardcodé)
- `resources/views/public/about.blade.php` (hardcodé)
- `app/Http/Controllers/Public/HomeController.php` (valeurs par défaut seulement)

✅ **Modifier uniquement** :
- Table `public_contents` dans la base de données

### Valeurs par Défaut

Les valeurs par défaut dans le contrôleur sont utilisées **seulement si** :
- La base de données ne contient pas la valeur
- Première installation de la plateforme
- Problème de connexion à la base de données

---

## 🚀 Améliorations Futures

### Interface Admin

Créer une section dans le dashboard admin :
- 📊 "Gestion des Statistiques"
- ✏️ Formulaire de modification
- 💾 Sauvegarde directe dans la base
- 🔄 Mise à jour en temps réel

### Historique

- 📈 Graphique d'évolution des statistiques
- 📅 Historique des modifications
- 👤 Qui a modifié quoi et quand

### Validation

- ✅ Validation des valeurs (nombres positifs)
- 🔢 Format automatique
- ⚠️ Alertes si valeurs anormales

---

## 📞 Support

### En cas de problème

1. **Les valeurs ne changent pas** :
   ```bash
   php artisan optimize:clear
   ```
   
2. **Erreur 500** :
   - Vérifier que la table `public_contents` existe
   - Vérifier que les colonnes sont correctes
   
3. **Compteurs ne s'animent pas** :
   - Vérifier la console JavaScript (F12)
   - Vérifier que les valeurs sont des nombres

---

## 📊 Résumé

| Statistique | Valeur Actuelle | Où Modifier | Format Affiché |
|-------------|-----------------|-------------|----------------|
| Agents | 137 | `agents_count` | 137 |
| Magasins | 71 | `warehouses_count` | 71 |
| Capacité | 86 | `capacity_count` | 86 000 tonnes |
| Expérience | 50 | `experience_count` | 50+ |

---

**Date de création** : 03 Octobre 2025  
**Dernière mise à jour** : 03 Octobre 2025  
**Version** : 1.0  

---

**Développé avec ❤️ pour CSAR Platform**














