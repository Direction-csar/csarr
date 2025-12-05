# 🔍 GUIDE SEO ET OPTIMISATION - CSAR PUBLIC

**Date** : 24 Octobre 2025  
**Version** : 1.0  
**Objectif** : Maximiser la visibilité du site CSAR sur les moteurs de recherche

---

## 📑 TABLE DES MATIÈRES

1. [État Actuel SEO](#1-état-actuel-seo)
2. [Optimisations On-Page](#2-optimisations-on-page)
3. [Optimisations Techniques](#3-optimisations-techniques)
4. [Contenu et Mots-clés](#4-contenu-et-mots-clés)
5. [Performance](#5-performance)
6. [Google Search Console](#6-google-search-console)
7. [Suivi et Analytics](#7-suivi-et-analytics)

---

## 1. ÉTAT ACTUEL SEO

### 1.1 Checklist Implémentée

✅ **Meta Tags** :
- Balises title sur toutes les pages
- Meta descriptions (à compléter sur certaines pages)
- Meta keywords (non utilisé - obsolète)
- Viewport pour responsive

✅ **Open Graph** :
- og:title
- og:description
- og:image
- og:url
- og:type

✅ **Twitter Cards** :
- twitter:card
- twitter:title
- twitter:description
- twitter:image

✅ **Structure** :
- URLs parlantes (/fr/actualites/titre-article)
- Hiérarchie H1, H2, H3 correcte
- HTML5 sémantique
- Images avec attribut alt

✅ **Sitemap** :
- sitemap.xml dynamique généré
- Inclut toutes les pages principales
- Actualités dynamiques
- Discours et rapports

✅ **Robots.txt** :
- Permet l'indexation du site public
- Bloque les sections admin
- Lien vers sitemap

### 1.2 Points à Améliorer

⚠️ **À compléter** :
- Schema.org JSON-LD (partiellement implémenté)
- Meta descriptions sur toutes les pages
- Optimisation images (WebP)
- Lazy loading agressif
- Critical CSS

---

## 2. OPTIMISATIONS ON-PAGE

### 2.1 Balises Title

**Format optimal** :
```html
<title>Mot-clé Principal - CSAR</title>
```

**Exemples** :
```html
<!-- Page d'accueil -->
<title>CSAR - Sécurité Alimentaire et Résilience au Sénégal</title>

<!-- Actualités -->
<title>Actualités CSAR - Sécurité Alimentaire Sénégal</title>

<!-- Demande -->
<title>Faire une Demande d'Assistance - CSAR</title>

<!-- Rapports SIM -->
<title>Rapports SIM - Prix des Denrées au Sénégal - CSAR</title>
```

**Règles** :
- Longueur : 50-60 caractères
- Mot-clé principal en début
- Unique pour chaque page
- Descriptif et accrocheur

### 2.2 Meta Descriptions

**Format optimal** :
```html
<meta name="description" content="Description attractive de 150-160 caractères avec mots-clés.">
```

**Exemples** :
```html
<!-- Page d'accueil -->
<meta name="description" content="CSAR - Institution publique sénégalaise dédiée à la sécurité alimentaire et à la résilience. Aide humanitaire, gestion des stocks, assistance aux populations.">

<!-- Actualités -->
<meta name="description" content="Suivez les dernières actualités du CSAR : actions humanitaires, distributions alimentaires, rapports SIM et projets de résilience au Sénégal.">

<!-- Demande -->
<meta name="description" content="Soumettez votre demande d'assistance alimentaire ou matérielle au CSAR. Formulaire simple, suivi en ligne, réponse rapide.">
```

**Règles** :
- Longueur : 150-160 caractères
- Contient mots-clés
- Appel à l'action
- Unique par page

### 2.3 Structure H1-H6

**Hiérarchie correcte** :
```html
<h1>Titre Principal de la Page</h1> <!-- 1 seul par page -->

<section>
  <h2>Section 1</h2>
  <p>Contenu...</p>
  
  <h3>Sous-section 1.1</h3>
  <p>Contenu...</p>
</section>

<section>
  <h2>Section 2</h2>
  <h3>Sous-section 2.1</h3>
</section>
```

**Bonnes pratiques** :
- 1 seul H1 par page (titre principal)
- Hiérarchie logique (pas de saut H2 → H4)
- Contient des mots-clés
- Descriptif et clair

### 2.4 Images Optimisées

**Balise alt obligatoire** :
```html
<img src="entrepot-dakar.jpg" alt="Entrepôt CSAR de Dakar avec stocks alimentaires" />
```

**Format optimal** :
```html
<img 
  src="image.webp" 
  alt="Description précise avec mots-clés"
  loading="lazy"
  width="800"
  height="600"
/>
```

**Checklist images** :
- ✅ Attribut alt descriptif (pas "image1.jpg")
- ✅ Format WebP (ou JPEG optimisé)
- ✅ Taille adaptée (max 200 KB par image)
- ✅ Dimensions width/height pour éviter layout shift
- ✅ Lazy loading pour images hors viewport
- ✅ Noms de fichiers descriptifs (entrepot-dakar.jpg)

### 2.5 Liens Internes

**Stratégie** :
```html
<!-- Liens contextuels avec ancres descriptives -->
<a href="/fr/rapports-sim">Consultez nos rapports SIM</a>

<!-- Éviter -->
<a href="/fr/rapports-sim">Cliquez ici</a>
```

**Bonnes pratiques** :
- Ancres descriptives (pas "cliquez ici")
- Liens vers pages importantes
- Structure en silos thématiques
- Breadcrumb sur toutes les pages

---

## 3. OPTIMISATIONS TECHNIQUES

### 3.1 Schema.org JSON-LD

**Organization Schema** (sur toutes les pages) :
```javascript
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "GovernmentOrganization",
  "name": "CSAR",
  "legalName": "Commissariat à la Sécurité Alimentaire et à la Résilience",
  "url": "https://csar.sn",
  "logo": "https://csar.sn/images/csar-logo.png",
  "description": "Institution publique dédiée à la sécurité alimentaire et à la résilience au Sénégal",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "SN",
    "addressLocality": "Dakar"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+221-XX-XXX-XX-XX",
    "contactType": "customer service",
    "email": "contact@csar.sn"
  }
}
</script>
```

**Article Schema** (actualités) :
```javascript
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "{{ $actualite->titre }}",
  "image": "{{ asset('storage/' . $actualite->image) }}",
  "datePublished": "{{ $actualite->published_at }}",
  "dateModified": "{{ $actualite->updated_at }}",
  "author": {
    "@type": "Organization",
    "name": "CSAR"
  },
  "publisher": {
    "@type": "Organization",
    "name": "CSAR",
    "logo": {
      "@type": "ImageObject",
      "url": "https://csar.sn/images/csar-logo.png"
    }
  }
}
</script>
```

**BreadcrumbList Schema** :
```javascript
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Accueil",
    "item": "https://csar.sn/fr"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "Actualités",
    "item": "https://csar.sn/fr/actualites"
  }]
}
</script>
```

### 3.2 Sitemap Dynamique

**Fichier** : `app/Http/Controllers/SitemapController.php`

**Inclusion** :
- Pages statiques (priorité 0.9-1.0)
- Actualités (priorité 0.7)
- Discours (priorité 0.6)
- Rapports SIM (priorité 0.7)
- Pages légales (priorité 0.5)

**Mise à jour** : Automatique à chaque ajout de contenu

**Soumission** :
1. Google Search Console
2. Bing Webmaster Tools

### 3.3 Robots.txt

**Fichier** : `public/robots.txt`

**Contenu** :
```
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /storage/

Sitemap: https://csar.sn/sitemap.xml
```

### 3.4 Canonical URLs

**Sur chaque page** :
```html
<link rel="canonical" href="https://csar.sn/fr/actualites/titre-article" />
```

**Prévient** :
- Contenu dupliqué
- Problèmes de pagination
- Versions multiples de la même page

### 3.5 Hreflang (Multilinguisme)

**Sur toutes les pages FR/EN** :
```html
<link rel="alternate" hreflang="fr" href="https://csar.sn/fr/actualites" />
<link rel="alternate" hreflang="en" href="https://csar.sn/en/news" />
<link rel="alternate" hreflang="x-default" href="https://csar.sn/fr/actualites" />
```

---

## 4. CONTENU ET MOTS-CLÉS

### 4.1 Mots-clés Principaux

**Sécurité Alimentaire** :
- sécurité alimentaire sénégal
- aide alimentaire sénégal
- assistance humanitaire sénégal
- stocks alimentaires
- résilience alimentaire

**Institutionnels** :
- CSAR Sénégal
- commissariat sécurité alimentaire
- gouvernement sénégal aide
- institution publique sénégal

**Services** :
- demande assistance alimentaire
- aide humanitaire dakar
- distribution denrées sénégal
- rapports SIM sénégal

**Longue traîne** :
- comment faire demande aide alimentaire sénégal
- prix denrées alimentaires sénégal
- où trouver aide humanitaire dakar
- entrepôts alimentaires sénégal

### 4.2 Stratégie de Contenu

**Actualités** :
- Publication : 4+ articles/mois
- Longueur : 500-1500 mots
- Mots-clés : 3-5 par article
- Images : 2-5 optimisées
- Liens internes : 3-5

**Pages Statiques** :
- Contenu : 800-2000 mots
- Mise à jour : Mensuelle
- Contenu unique et de qualité

**Rapports SIM** :
- Publication : Mensuelle
- Données actualisées
- Graphiques interactifs
- Export de données

---

## 5. PERFORMANCE

### 5.1 Google PageSpeed Insights

**Objectif** : Score > 90/100

**Optimisations critiques** :
- ✅ Minification CSS/JS (Vite)
- ✅ Compression GZIP
- ⚠️ Images WebP
- ⚠️ Lazy loading agressif
- ⚠️ Critical CSS inline
- ⚠️ CDN (Cloudflare)

### 5.2 Core Web Vitals

**LCP (Largest Contentful Paint)** : < 2.5s
- Optimiser images héro
- Utiliser CDN
- Précharger polices critiques

**FID (First Input Delay)** : < 100ms
- Réduire JavaScript bloquant
- Charger JS en async/defer

**CLS (Cumulative Layout Shift)** : < 0.1
- Définir width/height sur images
- Réserver espace pour ads/embeds

### 5.3 Checklist Performance

```bash
# Test avec PageSpeed
https://pagespeed.web.dev/

# Test avec GTmetrix
https://gtmetrix.com/

# Test mobile
Google Mobile-Friendly Test
```

**Actions** :
- [ ] Compresser toutes les images (TinyPNG)
- [ ] Convertir images en WebP
- [ ] Lazy load toutes images
- [ ] Minifier CSS/JS
- [ ] Activer cache navigateur (7 jours)
- [ ] CDN pour assets statiques
- [ ] Compression Brotli/GZIP

---

## 6. GOOGLE SEARCH CONSOLE

### 6.1 Configuration Initiale

1. **Vérifier la propriété** :
   - Méthode DNS (recommandée)
   - Ou balise HTML dans `<head>`

2. **Soumettre le sitemap** :
   ```
   https://csar.sn/sitemap.xml
   ```

3. **Paramètres régionaux** :
   - Ciblage géographique : Sénégal

### 6.2 Surveillance

**Rapports à suivre** :
- Performance (clics, impressions, CTR)
- Couverture (pages indexées/erreurs)
- Experience (Core Web Vitals)
- Améliorations (Mobile usability, AMP)

**Alertes à configurer** :
- Erreurs d'indexation
- Problèmes de couverture
- Problèmes de sécurité

### 6.3 Optimiser CTR

**Titre attractif** :
- Inclure l'année (2025)
- Chiffres et listes
- Appels à l'action

**Description persuasive** :
- Bénéfices clairs
- Mots d'action
- Émojis (optionnel)

---

## 7. SUIVI ET ANALYTICS

### 7.1 Google Analytics 4

**Configuration** :
```javascript
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX', {
    'anonymize_ip': true, // RGPD
    'cookie_domain': 'csar.sn',
    'cookie_expires': 63072000 // 2 ans
  });
</script>
```

**Événements à tracker** :
- Soumission demande
- Téléchargement PDF
- Inscription newsletter
- Lecture actualité complète
- Utilisation carte

### 7.2 KPIs à Suivre

| KPI | Objectif | Fréquence |
|-----|----------|-----------|
| Visiteurs uniques | 10,000/mois | Mensuel |
| Pages vues | 50,000/mois | Mensuel |
| Taux de rebond | < 40% | Hebdo |
| Temps sur site | > 2 min | Hebdo |
| Demandes soumises | 1,000/mois | Quotidien |
| Taux de conversion | > 5% | Mensuel |

---

## ANNEXES

### A. Checklist SEO Complète

**Technique** :
- [ ] HTTPS activé
- [ ] Sitemap soumis
- [ ] Robots.txt configuré
- [ ] Canonical URLs
- [ ] Hreflang (FR/EN)
- [ ] Schema.org implémenté
- [ ] Performance > 90
- [ ] Mobile-friendly

**On-Page** :
- [ ] Title optimisés
- [ ] Meta descriptions
- [ ] H1-H6 structure
- [ ] Images avec alt
- [ ] URLs parlantes
- [ ] Liens internes

**Contenu** :
- [ ] Mots-clés ciblés
- [ ] Contenu unique
- [ ] Publication régulière
- [ ] Actualisation mensuelle

**Suivi** :
- [ ] Google Analytics
- [ ] Search Console
- [ ] Tableaux de bord

---

© 2025 CSAR - Guide SEO et Optimisation






















