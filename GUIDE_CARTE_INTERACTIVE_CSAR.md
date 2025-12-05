# 🗺️ Guide de la Carte Interactive des Entrepôts et Demandes d'Aide Alimentaire CSAR

## 📋 Vue d'ensemble

La carte interactive du dashboard administrateur CSAR permet de visualiser en temps réel :
- **Les entrepôts CSAR** avec leurs emplacements géographiques
- **Les demandes d'aide alimentaire** géolocalisées avec le logo CSAR
- Des **filtres dynamiques** pour analyser les données par période, région et statut
- Un **export PDF professionnel** pour l'analyse et les rapports

---

## ✨ Fonctionnalités Principales

### 1. 📍 Visualisation Interactive

#### Marqueurs Personnalisés
- **Entrepôts** : Icône bleue avec symbole d'entrepôt (🏢)
- **Demandes d'aide** : Logo CSAR dans un cercle rouge avec ombre portée

#### Clustering Intelligent
Les marqueurs se regroupent automatiquement lorsqu'ils sont proches pour :
- Améliorer la lisibilité de la carte
- Optimiser les performances avec de nombreux points
- Cliquez sur un cluster pour zoomer et voir les points individuels

#### Popups Informatives
En cliquant sur un marqueur, vous accédez à :

**Pour un entrepôt :**
- Nom de l'entrepôt
- Région
- Adresse
- Statut (Actif/Inactif)
- Bouton pour voir les détails complets

**Pour une demande d'aide :**
- Nom du demandeur
- Logo CSAR
- Badge de statut (En attente/Traitée/Rejetée)
- Région
- Date de la demande
- Adresse
- Bouton pour voir la demande complète

---

### 2. 🔍 Filtres Dynamiques

Accédez aux filtres en cliquant sur le bouton **"Filtres"** en haut à droite de la carte.

#### Filtres Disponibles

| Filtre | Options | Description |
|--------|---------|-------------|
| **Année** | 2020 à aujourd'hui | Affiche uniquement les demandes de l'année sélectionnée |
| **Mois** | Janvier à Décembre | Filtre par mois spécifique |
| **Région** | Les 14 régions du Sénégal | Limite l'affichage à une région géographique |
| **Statut** | En attente / Traitée / Rejetée | Filtre les demandes selon leur état de traitement |
| **Type** | Tout / Entrepôts / Demandes | Affiche ou masque entrepôts et/ou demandes |

#### Utilisation des Filtres

```
1. Cliquez sur "Filtres" pour afficher le panneau
2. Sélectionnez les critères souhaités
3. Les filtres s'appliquent automatiquement (pas besoin de valider)
4. Cliquez sur "Réinitialiser" pour supprimer tous les filtres
5. Cliquez sur "Actualiser" pour rafraîchir les données
```

#### Exemples d'Utilisation

**Analyser les demandes de janvier 2024 à Dakar :**
- Année : 2024
- Mois : Janvier
- Région : Dakar
- Type : Demandes uniquement

**Voir uniquement les demandes en attente :**
- Statut : En attente
- Type : Demandes uniquement

---

### 3. 📊 Statistiques en Temps Réel

Au-dessus de la carte, quatre cartes affichent des statistiques en temps réel :

| Statistique | Description |
|-------------|-------------|
| 🏢 **Entrepôts** | Nombre total d'entrepôts affichés sur la carte |
| 📍 **Demandes** | Nombre total de demandes d'aide alimentaire |
| ⏱️ **En attente** | Demandes en cours de traitement |
| ✅ **Traitées** | Demandes déjà traitées ou approuvées |

Ces statistiques se mettent à jour automatiquement lorsque vous appliquez des filtres.

---

### 4. 🎨 Légende

Une légende flottante apparaît en haut à droite de la carte avec :
- 🏢 Symbole bleu pour les entrepôts CSAR
- Logo CSAR pour les demandes d'aide
- Codes couleur des badges de statut :
  - 🟡 **Jaune** : En attente
  - 🟢 **Vert** : Traitée
  - 🔴 **Rouge** : Rejetée

---

### 5. 📄 Export PDF Professionnel

#### Comment Exporter ?

1. Cliquez sur le bouton **"Export PDF"** (icône verte) en haut à droite
2. Le système génère automatiquement un PDF avec :
   - Image haute résolution de la carte actuelle
   - En-tête avec titre "CSAR - Carte des Entrepôts et Demandes"
   - Date et heure de génération
   - Statistiques (nombre d'entrepôts et demandes)
   - Légende complète
   - Pied de page avec copyright CSAR

3. Le fichier se télécharge automatiquement avec un nom unique : `carte_csar_[timestamp].pdf`

#### Contenu du PDF

```
┌─────────────────────────────────────────────┐
│  CSAR - Carte des Entrepôts et Demandes     │
│  Généré le: [date et heure]                 │
│  Entrepôts: [X] | Demandes: [Y]             │
├─────────────────────────────────────────────┤
│                                             │
│          [IMAGE DE LA CARTE]                │
│                                             │
├─────────────────────────────────────────────┤
│  Légende:                                   │
│  ● Entrepôts CSAR                           │
│  ● Demandes d'aide alimentaire              │
├─────────────────────────────────────────────┤
│  © CSAR - Commissariat à la Sécurité       │
│    Alimentaire et à la Résilience           │
└─────────────────────────────────────────────┘
```

#### Cas d'Usage de l'Export PDF

- **Rapports mensuels** : Exportez la carte filtrée par mois pour les réunions
- **Analyse régionale** : Créez des PDFs par région pour la planification
- **Documentation** : Archivez l'état des demandes à une date donnée
- **Présentation** : Utilisez les PDFs dans vos présentations PowerPoint
- **Conformité** : Conservez des preuves visuelles pour les audits

---

## 🎯 Scénarios d'Utilisation

### Scénario 1 : Analyse Mensuelle
**Objectif** : Analyser les demandes d'octobre 2024

```
1. Ouvrir les filtres
2. Sélectionner Année: 2024, Mois: Octobre
3. Observer la répartition géographique des demandes
4. Exporter en PDF pour le rapport mensuel
5. Partager le PDF avec l'équipe de direction
```

### Scénario 2 : Suivi des Demandes en Attente
**Objectif** : Identifier les demandes qui nécessitent une action

```
1. Filtrer par Statut: "En attente"
2. Filtrer par Type: "Demandes uniquement"
3. Cliquer sur chaque marqueur rouge pour voir les détails
4. Traiter les demandes prioritaires par région
```

### Scénario 3 : Planification d'Entrepôts
**Objectif** : Identifier les zones avec beaucoup de demandes mais peu d'entrepôts

```
1. Afficher tout (Type: "Tout afficher")
2. Observer visuellement les zones avec:
   - Beaucoup de marqueurs rouges (demandes)
   - Peu de marqueurs bleus (entrepôts)
3. Utiliser ces données pour planifier de nouveaux entrepôts
```

### Scénario 4 : Rapport Régional
**Objectif** : Créer un rapport pour une région spécifique

```
1. Filtrer par Région: "Saint-Louis" (par exemple)
2. Observer les statistiques mises à jour
3. Noter le nombre de demandes en attente
4. Exporter en PDF
5. Annexer au rapport régional trimestriel
```

---

## 🔧 Fonctionnalités Techniques

### Technologies Utilisées

| Technologie | Usage | Version |
|------------|-------|---------|
| **Leaflet.js** | Bibliothèque de cartes interactive | 1.7.1 |
| **MarkerCluster** | Regroupement intelligent des marqueurs | 1.4.1 |
| **jsPDF** | Génération de fichiers PDF | 2.5.1 |
| **html2canvas** | Capture d'écran de la carte | 1.4.1 |
| **OpenStreetMap** | Fournisseur de tuiles cartographiques | - |

### API Backend

**Endpoint de Filtrage** : `POST /admin/dashboard/filter-map`

Paramètres acceptés :
```json
{
  "year": "2024",           // nullable, integer
  "month": "10",            // nullable, 1-12
  "region": "Dakar",        // nullable, string
  "status": "en_attente",   // nullable, string
  "type": "all"             // all/warehouses/demandes
}
```

Réponse :
```json
{
  "success": true,
  "data": [
    {
      "type": "warehouse",
      "lat": 14.6937,
      "lng": -17.4441,
      "name": "Entrepôt Dakar Central",
      "region": "Dakar",
      "status": "active"
    },
    {
      "type": "demande",
      "lat": 14.7167,
      "lng": -17.4677,
      "name": "Amadou Diallo",
      "status": "en_attente",
      "created_at": "24/10/2025 14:30"
    }
  ],
  "count": 2,
  "filters": {...}
}
```

---

## 📱 Compatibilité et Performance

### Navigateurs Supportés
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+

### Performance
- **Chargement initial** : < 2 secondes (jusqu'à 1000 points)
- **Application des filtres** : < 1 seconde
- **Export PDF** : 2-5 secondes (selon la complexité)
- **Clustering** : Automatique pour > 50 marqueurs proches

### Optimisations
- Clustering automatique des marqueurs
- Chargement asynchrone des données
- Compression des images dans les PDFs
- Cache des icônes personnalisées

---

## 🆘 Dépannage

### Problème : La carte ne s'affiche pas
**Solutions** :
1. Vérifier que JavaScript est activé dans le navigateur
2. Rafraîchir la page (F5 ou Ctrl+R)
3. Vider le cache du navigateur
4. Vérifier la console développeur (F12) pour les erreurs

### Problème : Les filtres ne fonctionnent pas
**Solutions** :
1. Vérifier la connexion internet
2. Cliquer sur "Réinitialiser" puis réessayer
3. Rafraîchir complètement la page
4. Vérifier que vous avez bien sélectionné des valeurs dans les filtres

### Problème : L'export PDF échoue
**Solutions** :
1. Attendre que la carte soit complètement chargée
2. Vérifier que les popups sont fermées avant l'export
3. Désactiver les bloqueurs de popups
4. Essayer avec un autre navigateur

### Problème : Aucune donnée n'apparaît sur la carte
**Solutions** :
1. Vérifier qu'il existe des données géolocalisées dans la base
2. Réinitialiser les filtres
3. Vérifier les permissions d'accès aux données
4. Contacter l'administrateur système

---

## 🔐 Sécurité et Confidentialité

### Données Affichées
- Seules les données auxquelles vous avez accès sont affichées
- Les coordonnées GPS sont arrondies pour la protection de la vie privée
- Les PDFs exportés ne contiennent que les données visibles sur la carte

### Bonnes Pratiques
- ✅ Exporter les PDFs uniquement pour usage interne
- ✅ Ne pas partager les coordonnées précises publiquement
- ✅ Utiliser les filtres pour limiter l'exposition des données sensibles
- ✅ Supprimer les PDFs après utilisation

---

## 📞 Support et Contact

Pour toute question ou problème technique concernant la carte interactive :

**Support Technique CSAR**
- 📧 Email : support@csar.sn
- 📱 Téléphone : +221 33 XXX XX XX
- 🕐 Heures d'ouverture : Lun-Ven 8h-17h

**Documentation Complémentaire**
- Guide utilisateur complet : `GUIDE_UTILISATEUR_ADMIN.md`
- Documentation technique : `README_ADMIN_COMPLET.md`
- FAQ : `IDENTIFIANTS_CONNEXION.md`

---

## 🚀 Améliorations Futures

### Fonctionnalités Prévues (v2.0)
- [ ] Export Excel avec données tabulaires
- [ ] Heatmap des zones à forte demande
- [ ] Calcul d'itinéraires entre entrepôts et demandes
- [ ] Notifications en temps réel sur la carte
- [ ] Vue 3D des données (altitude)
- [ ] Historique des déplacements (timeline)
- [ ] Comparaison de périodes (split view)
- [ ] Mode hors-ligne avec cache

### Suggestions Bienvenues
Si vous avez des idées pour améliorer la carte interactive, contactez l'équipe de développement !

---

## 📊 Métriques et Analyses

### Données Collectées
La carte permet d'analyser :
- Distribution géographique des demandes
- Densité d'entrepôts par région
- Taux de traitement des demandes
- Évolution temporelle (avec les filtres de date)
- Zones sous-desservies

### Indicateurs Clés (KPI)
- **Couverture géographique** : % de régions avec entrepôts
- **Temps de réponse moyen** : Délai entre demande et traitement
- **Saturation** : Ratio demandes/entrepôts par région
- **Efficacité** : % de demandes traitées sous 48h

---

## ✅ Checklist d'Utilisation Quotidienne

```markdown
☐ Ouvrir le dashboard admin
☐ Accéder à la carte interactive
☐ Vérifier les statistiques du jour (En attente)
☐ Filtrer les demandes urgentes
☐ Traiter les demandes prioritaires
☐ Exporter un PDF pour archivage
☐ Mettre à jour les données des entrepôts si nécessaire
☐ Vérifier la distribution géographique
```

---

## 📝 Changelog

### Version 1.0.0 - 24 Octobre 2025
✨ **Première version complète**
- Carte interactive avec Leaflet.js
- Marqueurs personnalisés (entrepôts + demandes avec logo CSAR)
- Filtres dynamiques (année, mois, région, statut, type)
- Légende intégrée et flottante
- Export PDF professionnel
- Statistiques en temps réel
- Clustering automatique des marqueurs
- API backend pour le filtrage
- Popups informatives avec actions
- Responsive et optimisé

---

## 🎓 Glossaire

| Terme | Définition |
|-------|------------|
| **Clustering** | Regroupement automatique de marqueurs proches pour améliorer la lisibilité |
| **Géolocalisation** | Coordonnées GPS (latitude/longitude) d'un point sur la carte |
| **Marqueur** | Icône placée sur la carte représentant un point d'intérêt |
| **Popup** | Fenêtre d'information qui s'ouvre en cliquant sur un marqueur |
| **Tuile** | Petit carré d'image qui compose la carte complète |
| **Export** | Action de sauvegarder la carte dans un fichier (PDF) |
| **Filtrage** | Sélection de données selon des critères spécifiques |

---

**© 2025 CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience du Sénégal**

*Document créé le 24 octobre 2025*
*Version 1.0.0*




















