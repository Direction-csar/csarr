# Guide d'implémentation SMS de confirmation - Plateforme CSAR

## Vue d'ensemble

Ce guide explique l'implémentation du système de confirmation SMS pour les demandes soumises sur la plateforme CSAR. Le système envoie automatiquement un SMS de confirmation à l'utilisateur après soumission de sa demande.

## Fonctionnalités implémentées

### ✅ Fonctionnalités principales
- **SMS automatique** : Envoi automatique d'un SMS de confirmation après soumission de demande
- **Messages personnalisés** : Messages différents selon le type de demande
- **Gestion d'erreurs** : Le système continue de fonctionner même si l'envoi SMS échoue
- **Mode simulation** : Possibilité de tester sans API SMS réelle
- **Support multi-fournisseurs** : Orange, Wave, et APIs génériques
- **Suivi complet** : Enregistrement du statut d'envoi dans la base de données

### 📱 Types de demandes supportés
- **Aide alimentaire** : "Votre demande d'aide alimentaire a bien été transmise au CSAR. Code de suivi: {code}. Nous vous contacterons sous 24-48h."
- **Demande d'audience** : "Votre demande d'audience a bien été transmise au CSAR. Code de suivi: {code}. Nous vous contacterons prochainement."
- **Information générale** : "Votre demande d'information a bien été transmise au CSAR. Code de suivi: {code}. Nous vous répondrons rapidement."
- **Autre** : "Votre demande a bien été transmise au CSAR. Code de suivi: {code}. Nous vous contacterons prochainement."

## Configuration

### 1. Variables d'environnement

Ajoutez ces variables à votre fichier `.env` :

```env
# Activer/désactiver le service SMS
SMS_ENABLED=false

# Fournisseur SMS (orange, wave, generic)
SMS_PROVIDER=orange

# Configuration générale
SMS_API_KEY=your_api_key_here
SMS_API_URL=https://api.orange.com/smsmessaging/v1/outbound
SMS_SENDER_NAME=CSAR

# Comportement en cas d'erreur SMS
SMS_FAIL_ON_ERROR=false
```

### 2. Configuration par fournisseur

#### Orange SMS
```env
ORANGE_SMS_API_URL=https://api.orange.com/smsmessaging/v1/outbound
ORANGE_SMS_API_KEY=your_orange_api_key
ORANGE_SMS_SENDER=CSAR
```

#### Wave SMS
```env
WAVE_SMS_API_URL=https://api.wave.com/sms
WAVE_SMS_API_KEY=your_wave_api_key
WAVE_SMS_SENDER=CSAR
```

#### API générique
```env
GENERIC_SMS_API_URL=https://your-sms-provider.com/api/send
GENERIC_SMS_API_KEY=your_generic_api_key
GENERIC_SMS_SENDER=CSAR
```

## Utilisation

### Mode simulation (par défaut)
Par défaut, le service SMS est désactivé (`SMS_ENABLED=false`). Dans ce mode :
- Les demandes sont enregistrées normalement
- Les SMS sont simulés (enregistrés dans les logs)
- L'utilisateur voit un message indiquant que le SMS est temporairement indisponible

### Mode production
Pour activer l'envoi réel de SMS :
1. Configurez votre fournisseur SMS dans le fichier `.env`
2. Définissez `SMS_ENABLED=true`
3. Testez avec la commande `php artisan sms:test +221771234567`

## Tests

### Commande de test
```bash
# Test basique
php artisan sms:test +221771234567

# Test avec message personnalisé
php artisan sms:test +221771234567 --message="Test personnalisé"
```

### Test du formulaire complet
1. Allez sur la page de demande
2. Remplissez le formulaire avec un numéro de téléphone valide
3. Soumettez la demande
4. Vérifiez les logs pour voir l'envoi SMS

## Structure de la base de données

### Nouveaux champs ajoutés à la table `demandes`
- `sms_sent` (boolean) : Indique si le SMS a été envoyé
- `sms_message_id` (string) : ID du message SMS
- `sms_sent_at` (timestamp) : Date/heure d'envoi
- `sms_error` (text) : Message d'erreur en cas d'échec
- `sms_retry_count` (integer) : Nombre de tentatives

## Gestion des erreurs

### Comportement en cas d'erreur SMS
- **Par défaut** : L'erreur SMS n'empêche pas l'enregistrement de la demande
- **Configurable** : Définissez `SMS_FAIL_ON_ERROR=true` pour faire échouer la demande en cas d'erreur SMS

### Types d'erreurs gérées
- Numéro de téléphone invalide
- Erreur d'API SMS
- Timeout de connexion
- Quota dépassé

## Logs et monitoring

### Logs automatiques
Tous les envois SMS sont loggés avec :
- Numéro de téléphone (masqué pour la sécurité)
- Message envoyé
- Statut d'envoi
- Erreurs éventuelles
- Timestamp

### Consultation des logs
```bash
# Voir les logs SMS
tail -f storage/logs/laravel.log | grep "SMS"
```

## Sécurité

### Protection des données
- Les numéros de téléphone sont nettoyés et validés
- Les messages d'erreur ne contiennent pas d'informations sensibles
- Les logs masquent les numéros complets

### Validation des numéros
- Format sénégalais : +221XXXXXXXXX
- Nettoyage automatique des formats : 0771234567 → +221771234567
- Validation stricte avant envoi

## Maintenance

### Vérification du statut
```bash
# Vérifier la configuration SMS
php artisan sms:test +221771234567
```

### Nettoyage des logs
Les logs SMS peuvent être nettoyés périodiquement pour éviter l'accumulation.

## Support des fournisseurs

### Orange SMS
- API officielle Orange
- Support des numéros sénégalais
- Tarification selon le plan Orange

### Wave SMS
- API Wave Money
- Intégration avec le système de paiement
- Tarification Wave

### API générique
- Support de tout fournisseur SMS
- Configuration flexible
- Format JSON standard

## Dépannage

### Problèmes courants

#### SMS non envoyés
1. Vérifiez `SMS_ENABLED=true`
2. Vérifiez la configuration API
3. Testez avec `php artisan sms:test`
4. Consultez les logs

#### Erreurs d'API
1. Vérifiez les clés API
2. Vérifiez l'URL de l'API
3. Vérifiez la connectivité réseau
4. Contactez le support du fournisseur SMS

#### Numéros invalides
1. Vérifiez le format du numéro
2. Utilisez le format international (+221)
3. Vérifiez que le numéro est actif

## Évolutions futures

### Fonctionnalités prévues
- SMS de mise à jour de statut
- Rappels automatiques
- Templates de messages personnalisables
- Interface d'administration pour les SMS
- Statistiques d'envoi
- Intégration avec d'autres fournisseurs

### Améliorations techniques
- Queue pour les envois en masse
- Retry automatique en cas d'échec
- Monitoring en temps réel
- API REST pour la gestion SMS
