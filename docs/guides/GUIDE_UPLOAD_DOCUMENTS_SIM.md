# Guide d'Upload de Documents SIM - CSAR Platform

## Vue d'ensemble

La plateforme CSAR permet maintenant l'upload de documents volumineux (jusqu'à 50 Mo) pour les rapports SIM. Cette fonctionnalité permet aux administrateurs de partager des documents PDF, PowerPoint, Word et Excel sur la plateforme publique.

## Fonctionnalités

### Types de fichiers supportés
- **PDF** (.pdf) - Documents de rapport
- **PowerPoint** (.ppt, .pptx) - Présentations
- **Word** (.doc, .docx) - Documents texte
- **Excel** (.xls, .xlsx) - Feuilles de calcul
- **Images de couverture** (.jpg, .jpeg, .png, .gif) - Max 10 Mo

### Limites de taille
- **Documents principaux** : 50 Mo maximum
- **Images de couverture** : 10 Mo maximum
- **Mémoire PHP** : 256 Mo
- **Temps d'exécution** : 300 secondes

## Configuration requise

### 1. Configuration PHP (php.ini)
```ini
upload_max_filesize = 50M
post_max_size = 60M
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
max_file_uploads = 20
```

### 2. Configuration Apache (.htaccess)
```apache
php_value upload_max_filesize 50M
php_value post_max_size 60M
php_value memory_limit 256M
php_value max_execution_time 300
php_value max_input_time 300
LimitRequestBody 62914560
```

### 3. Dossiers de stockage
Les fichiers sont stockés dans :
- `storage/app/public/sim-reports/documents/` - Documents principaux
- `storage/app/public/sim-reports/covers/` - Images de couverture

## Utilisation

### Pour les administrateurs

1. **Accéder à l'interface d'upload**
   - Connectez-vous à l'administration
   - Allez sur `http://localhost:8000/admin/sim-reports`
   - Cliquez sur "Uploader Document"

2. **Remplir le formulaire**
   - **Titre** : Nom du document (obligatoire)
   - **Type** : Financier, Opérationnel, Inventaire, Personnel, Général
   - **Description** : Description du document (optionnel)
   - **Document** : Fichier à uploader (obligatoire)
   - **Image de couverture** : Image d'aperçu (optionnel)
   - **Rendre public** : Cocher pour rendre accessible publiquement

3. **Validation automatique**
   - Vérification de la taille (max 50 Mo)
   - Vérification du type de fichier
   - Prévisualisation du fichier sélectionné

### Pour le public

1. **Consulter les documents**
   - Allez sur `http://localhost:8000/sim-reports`
   - Filtrez par type, région, secteur
   - Recherchez par titre ou description

2. **Télécharger les documents**
   - Cliquez sur "Consulter" pour voir les détails
   - Cliquez sur "Télécharger" pour télécharger le fichier
   - Les téléchargements sont comptabilisés

## Sécurité

### Contrôle d'accès
- Seuls les administrateurs peuvent uploader des documents
- Seuls les documents marqués "public" sont accessibles publiquement
- Les téléchargements sont comptabilisés pour les statistiques

### Validation des fichiers
- Vérification des types MIME
- Limitation de la taille des fichiers
- Nettoyage des noms de fichiers
- Stockage sécurisé dans des dossiers dédiés

### Protection contre les attaques
- Validation côté serveur et client
- Filtrage des extensions de fichiers
- Limitation des types de fichiers autorisés
- Protection contre l'exécution de scripts dans les dossiers d'upload

## Statistiques et suivi

### Métriques disponibles
- **Vues** : Nombre de consultations du document
- **Téléchargements** : Nombre de téléchargements
- **Taille** : Taille du fichier formatée
- **Date de publication** : Date de mise en ligne

### Tableau de bord admin
- Vue d'ensemble des rapports uploadés
- Statistiques de téléchargement
- Gestion des documents (suppression, modification)
- Filtres et recherche

## Dépannage

### Problèmes courants

1. **Erreur "Fichier trop volumineux"**
   - Vérifiez la configuration PHP (upload_max_filesize)
   - Vérifiez la configuration Apache (LimitRequestBody)
   - Redémarrez le serveur web

2. **Erreur "Type de fichier non supporté"**
   - Vérifiez l'extension du fichier
   - Utilisez uniquement les formats autorisés
   - Vérifiez que le fichier n'est pas corrompu

3. **Erreur de téléchargement**
   - Vérifiez que le fichier existe dans le dossier de stockage
   - Vérifiez les permissions des dossiers
   - Vérifiez le lien symbolique public/storage

### Logs et débogage
- Les erreurs sont loggées dans `storage/logs/laravel.log`
- Utilisez `php artisan storage:link` pour créer le lien symbolique
- Vérifiez les permissions des dossiers (755 pour les dossiers, 644 pour les fichiers)

## Maintenance

### Nettoyage régulier
- Surveillez l'espace disque utilisé
- Archivez les anciens documents si nécessaire
- Supprimez les fichiers orphelins

### Sauvegarde
- Sauvegardez régulièrement le dossier `storage/app/public/sim-reports/`
- Incluez les métadonnées de la base de données
- Testez la restauration des sauvegardes

## Support technique

Pour toute question ou problème :
1. Vérifiez les logs d'erreur
2. Consultez la documentation Laravel sur l'upload de fichiers
3. Vérifiez la configuration PHP et Apache
4. Contactez l'équipe technique si nécessaire

---

**Note** : Cette fonctionnalité est optimisée pour XAMPP sur Windows. Pour d'autres environnements, adaptez les chemins et configurations selon vos besoins.
