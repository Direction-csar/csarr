# Script de Configuration Rapide des Notifications Email - CSAR Platform
# Exécutez ce script pour configurer rapidement les notifications

Write-Host "🚀 Configuration des Notifications Email - CSAR Platform" -ForegroundColor Green
Write-Host "=================================================" -ForegroundColor Green
Write-Host ""

# Vérifier si le fichier .env existe
if (-not (Test-Path ".env")) {
    Write-Host "❌ Fichier .env non trouvé !" -ForegroundColor Red
    Write-Host "Création d'un fichier .env de base..." -ForegroundColor Yellow
    
    # Copier .env.example si il existe
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "✅ Fichier .env créé à partir de .env.example" -ForegroundColor Green
    } else {
        # Créer un .env minimal
        @"
APP_NAME="CSAR Platform"
APP_ENV=local
APP_KEY=base64:$(Get-Random -Minimum 100000 -Maximum 999999)
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Configuration Email - MODIFIEZ CES VALEURS
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="votre-email@gmail.com"
MAIL_FROM_NAME="CSAR Platform"
"@ | Out-File -FilePath ".env" -Encoding UTF8
        Write-Host "✅ Fichier .env minimal créé" -ForegroundColor Green
    }
}

Write-Host ""
Write-Host "📧 Configuration Email Requise" -ForegroundColor Cyan
Write-Host "================================" -ForegroundColor Cyan
Write-Host ""

# Demander la configuration email
Write-Host "Voulez-vous configurer Gmail ? (Recommandé) [O/n]: " -ForegroundColor Yellow -NoNewline
$useGmail = Read-Host

if ($useGmail -eq "" -or $useGmail.ToLower() -eq "o" -or $useGmail.ToLower() -eq "oui") {
    Write-Host ""
    Write-Host "Configuration Gmail :" -ForegroundColor Green
    Write-Host "1. Allez sur https://myaccount.google.com/apppasswords" -ForegroundColor White
    Write-Host "2. Générez un 'Mot de passe d'application'" -ForegroundColor White
    Write-Host "3. Entrez vos informations ci-dessous :" -ForegroundColor White
    Write-Host ""
    
    Write-Host "Votre email Gmail : " -ForegroundColor Yellow -NoNewline
    $email = Read-Host
    
    Write-Host "Mot de passe d'application : " -ForegroundColor Yellow -NoNewline
    $password = Read-Host -AsSecureString
    $passwordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($password))
    
    # Mettre à jour le fichier .env
    if ($email -and $passwordPlain) {
        $envContent = Get-Content ".env" -Raw
        $envContent = $envContent -replace "MAIL_USERNAME=.*", "MAIL_USERNAME=$email"
        $envContent = $envContent -replace "MAIL_PASSWORD=.*", "MAIL_PASSWORD=$passwordPlain"
        $envContent = $envContent -replace "MAIL_FROM_ADDRESS=.*", "MAIL_FROM_ADDRESS=`"$email`""
        $envContent | Out-File -FilePath ".env" -Encoding UTF8 -NoNewline
        
        Write-Host "✅ Configuration Gmail sauvegardée !" -ForegroundColor Green
    }
} else {
    Write-Host "⚠️  Configuration manuelle requise dans le fichier .env" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "🔄 Redémarrage des services..." -ForegroundColor Cyan

# Effacer le cache Laravel
if (Test-Path "C:\xampp\php\php.exe") {
    Write-Host "Nettoyage du cache Laravel..." -ForegroundColor Yellow
    & "C:\xampp\php\php.exe" artisan config:clear 2>$null
    & "C:\xampp\php\php.exe" artisan cache:clear 2>$null
    & "C:\xampp\php\php.exe" artisan view:clear 2>$null
    Write-Host "✅ Cache nettoyé" -ForegroundColor Green
} else {
    Write-Host "⚠️  PHP non trouvé dans XAMPP, nettoyage manuel requis" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "✅ Configuration Terminée !" -ForegroundColor Green
Write-Host "=========================" -ForegroundColor Green
Write-Host ""
Write-Host "🎯 Prochaines étapes :" -ForegroundColor Cyan
Write-Host "1. Ouvrez http://127.0.0.1:8000/admin/notifications/quick-setup" -ForegroundColor White
Write-Host "2. Suivez le guide de configuration étape par étape" -ForegroundColor White
Write-Host "3. Testez l'envoi d'email" -ForegroundColor White
Write-Host ""
Write-Host "🚀 Démarrer la plateforme :" -ForegroundColor Cyan
Write-Host "   .\demarrer_plateforme.ps1" -ForegroundColor White
Write-Host ""

# Optionnel : ouvrir le navigateur
Write-Host "Voulez-vous ouvrir la page de configuration ? [O/n]: " -ForegroundColor Yellow -NoNewline
$openBrowser = Read-Host

if ($openBrowser -eq "" -or $openBrowser.ToLower() -eq "o" -or $openBrowser.ToLower() -eq "oui") {
    Start-Process "http://127.0.0.1:8000/admin/notifications/quick-setup"
}

Write-Host ""
Write-Host "📧 Système de notifications configuré avec succès !" -ForegroundColor Green
Write-Host "Appuyez sur Entrée pour continuer..." -ForegroundColor Gray
Read-Host

