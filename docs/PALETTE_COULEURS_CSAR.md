# 🎨 Palette de Couleurs Professionnelle CSAR

## Vue d'ensemble

Cette palette de couleurs a été conçue pour offrir une identité visuelle professionnelle et cohérente à la plateforme CSAR, tout en respectant les standards d'accessibilité et de lisibilité.

## 🎯 Couleurs Principales

### Bleu CSAR (Navigation & Identité)
- **Principal** : `csar-blue-950` (#1a2b5b) - Barre latérale, navigation
- **Variations** : 50 à 950 pour différents niveaux d'intensité
- **Usage** : Navigation principale, en-têtes de section, éléments d'identité

### Vert CSAR (Actions & Accents)
- **Principal** : `csar-green-600` (#198754) - Boutons, actions, statuts positifs
- **Variations** : 50 à 950 pour différents niveaux d'intensité
- **Usage** : Boutons d'action, indicateurs de succès, accents visuels

## 🎨 Couleurs d'État

### Succès
- **Principal** : `csar-success-600` (#16a34a)
- **Usage** : Validation, confirmation, statuts positifs

### Avertissement
- **Principal** : `csar-warning-500` (#f59e0b)
- **Usage** : Alertes, notifications importantes, actions nécessitant attention

### Erreur
- **Principal** : `csar-error-600` (#dc2626)
- **Usage** : Messages d'erreur, suppression, actions critiques

### Information
- **Principal** : `csar-info-600` (#2563eb)
- **Usage** : Informations générales, liens, actions neutres

## 🌈 Couleurs Neutres

### Gris Professionnels
- **Principal** : `csar-neutral-800` (#262626) - Texte principal
- **Secondaire** : `csar-neutral-600` (#525252) - Texte secondaire
- **Arrière-plan** : `csar-neutral-50` (#fafafa) - Fond de page
- **Bordures** : `csar-neutral-200` (#e5e5e5) - Séparateurs, contours

## 🚀 Utilisation dans Tailwind CSS

### Classes de Couleurs
```html
<!-- Couleurs principales -->
<div class="bg-csar-blue-950 text-white">Navigation</div>
<div class="bg-csar-green-600 text-white">Action</div>

<!-- Couleurs d'état -->
<div class="bg-csar-success-100 text-csar-success-800 border border-csar-success-200">
  Succès
</div>

<!-- Couleurs neutres -->
<div class="bg-csar-neutral-50 text-csar-neutral-800">
  Contenu principal
</div>
```

### Classes Utilitaires CSAR
```html
<!-- Classes prédéfinies -->
<button class="btn-primary">Action principale</button>
<button class="btn-secondary">Action secondaire</button>
<button class="btn-danger">Action dangereuse</button>

<!-- Cartes -->
<div class="card">Contenu de la carte</div>

<!-- Formulaires -->
<input class="form-input" type="text">
<label class="form-label">Label</label>

<!-- Badges de statut -->
<span class="status-badge status-approved">Approuvé</span>
```

## 🎭 Composants Stylisés

### Boutons
- **`.btn-primary`** : Actions principales (vert CSAR)
- **`.btn-secondary`** : Actions secondaires (gris neutre)
- **`.btn-danger`** : Actions critiques (rouge)
- **`.btn-nav`** : Navigation (bleu CSAR)

### Cartes
- **`.card`** : Conteneurs de contenu avec ombres et bordures
- **`.section-header`** : En-têtes de section avec dégradé vert

### Navigation
- **`.sidebar`** : Barre latérale avec dégradé bleu
- **`.sidebar-item`** : Éléments de navigation avec états hover/active

### Formulaires
- **`.form-input`** : Champs de saisie avec focus et hover
- **`.form-label`** : Labels de formulaire stylisés

## 📱 Responsive Design

La palette inclut des adaptations pour les écrans mobiles :
- Bordures arrondies réduites sur mobile
- Espacement adaptatif
- Tailles de police optimisées

## ♿ Accessibilité

- **Contraste** : Toutes les combinaisons respectent les standards WCAG 2.1 AA
- **Lisibilité** : Couleurs optimisées pour la lecture sur écran
- **États** : Différenciation claire des états (hover, focus, active)

## 🔧 Personnalisation

### Ajouter de nouvelles couleurs
```javascript
// Dans tailwind.config.js
colors: {
  'csar': {
    'custom': {
      50: '#f0f9ff',
      500: '#0ea5e9',
      900: '#0c4a6e',
    }
  }
}
```

### Modifier les couleurs existantes
```css
/* Dans app.css */
.btn-custom {
    background-color: theme('colors.csar.custom.500');
    color: white;
}
```

## 📋 Checklist d'Implémentation

- [ ] Utiliser `csar-blue-950` pour la navigation principale
- [ ] Utiliser `csar-green-600` pour les actions principales
- [ ] Utiliser `csar-neutral-800` pour le texte principal
- [ ] Utiliser `csar-neutral-200` pour les bordures
- [ ] Appliquer les classes utilitaires CSAR
- [ ] Tester l'accessibilité des contrastes
- [ ] Vérifier la cohérence sur tous les écrans

## 🎨 Exemples Visuels

### Page de Connexion
```html
<div class="min-h-screen bg-csar-neutral-50">
  <div class="card max-w-md mx-auto mt-20">
    <div class="section-header text-center">
      <h1 class="text-2xl font-bold">Connexion CSAR</h1>
    </div>
    <form class="p-6 space-y-4">
      <div>
        <label class="form-label">Email</label>
        <input class="form-input" type="email">
      </div>
      <div>
        <label class="form-label">Mot de passe</label>
        <input class="form-input" type="password">
      </div>
      <button class="btn-primary w-full">Se connecter</button>
    </form>
  </div>
</div>
```

### Tableau de Données
```html
<div class="card">
  <div class="section-header">
    <h2>Liste des Utilisateurs</h2>
  </div>
  <div class="p-6">
    <table class="w-full">
      <thead class="bg-csar-neutral-100">
        <tr>
          <th class="text-left p-3 text-csar-neutral-700">Nom</th>
          <th class="text-left p-3 text-csar-neutral-700">Statut</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-b border-csar-neutral-200">
          <td class="p-3">Jean Dupont</td>
          <td class="p-3">
            <span class="status-badge status-approved">Actif</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
```

---

*Cette palette de couleurs est conçue pour offrir une expérience utilisateur professionnelle et accessible sur la plateforme CSAR.*

