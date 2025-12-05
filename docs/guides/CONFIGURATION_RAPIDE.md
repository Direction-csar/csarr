# 🚀 Configuration Rapide des Notifications Email

## 📋 Méthode 1 : Script Automatique (Recommandé)

### Étape 1 : Exécuter le script
```powershell
.\configurer_notifications.ps1
```

### Étape 2 : Suivre les instructions
- Le script vous demandera vos informations Gmail
- Il configurera automatiquement le fichier .env
- Il nettoiera le cache Laravel

### Étape 3 : Démarrer la plateforme
```powershell
.\demarrer_plateforme.ps1
```

---

## 📋 Méthode 2 : Configuration Manuelle

### Étape 1 : Configurer le fichier .env
Ouvrez le fichier `.env` et ajoutez/modifiez ces lignes :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="votre-email@gmail.com"
MAIL_FROM_NAME="CSAR Platform"
```

### Étape 2 : Redémarrer le serveur
Redémarrez XAMPP ou relancez :
```powershell
.\demarrer_plateforme.ps1
```

### Étape 3 : Accéder à l'interface
Allez sur : http://127.0.0.1:8000/admin/notifications/quick-setup

---

## 🔑 Configuration Gmail (Recommandé)

### Prérequis
1. **Compte Gmail** avec authentification 2 facteurs activée
2. **Mot de passe d'application** généré

### Générer un mot de passe d'application
1. Allez sur : https://myaccount.google.com/apppasswords
2. Sélectionnez "Autre (nom personnalisé)"
3. Entrez "CSAR Platform"
4. Copiez le mot de passe généré (16 caractères)
5. Utilisez ce mot de passe dans MAIL_PASSWORD

---

## ✅ Vérification

### 1. Configuration
- [ ] Fichier .env modifié
- [ ] Serveur redémarré  
- [ ] Page admin accessible

### 2. Test
- [ ] Aller sur `/admin/notifications`
- [ ] Cliquer "Configuration Rapide"
- [ ] Tester l'envoi d'email

### 3. Fonctionnalités
- [ ] Création d'utilisateur → Email de bienvenue
- [ ] Assignation de tâche → Notification
- [ ] Préférences personnalisables

---

## 🔧 Dépannage

### Erreur "SMTP Authentication failed"
- Vérifiez votre email et mot de passe
- Utilisez un mot de passe d'application pour Gmail
- Vérifiez que l'authentification 2FA est activée

### Erreur "Connection refused"
- Vérifiez MAIL_HOST et MAIL_PORT
- Testez votre connexion internet
- Vérifiez les paramètres de firewall

### Emails non reçus
- Vérifiez le dossier spam
- Testez avec un autre email
- Vérifiez les logs : `storage/logs/laravel.log`

---

## 🎯 Pages Utiles

- **Configuration Rapide** : `/admin/notifications/quick-setup`
- **Gestion des Notifications** : `/admin/notifications`
- **Guide Complet** : `/admin/notifications/email-config`

---

## 📞 Support

1. **Logs** : `storage/logs/laravel.log`
2. **Interface de test** : Page notifications admin
3. **Documentation** : `NOTIFICATIONS_GUIDE.md`

---

*Configuration en 5 minutes maximum ! 🚀*

