# Script de démarrage de la plateforme CSAR
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "    PLATEFORME CSAR - DEMARRAGE" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Write-Host "[1/5] Nettoyage des caches..." -ForegroundColor Yellow
& "C:\xampp\php\php.exe" artisan config:clear
& "C:\xampp\php\php.exe" artisan cache:clear
& "C:\xampp\php\php.exe" artisan view:clear
& "C:\xampp\php\php.exe" artisan route:clear

Write-Host ""
Write-Host "[2/5] Vérification des permissions..." -ForegroundColor Yellow
if (!(Test-Path "storage\logs")) { New-Item -ItemType Directory -Path "storage\logs" -Force }
if (!(Test-Path "bootstrap\cache")) { New-Item -ItemType Directory -Path "bootstrap\cache" -Force }

Write-Host ""
Write-Host "[3/5] Création du lien de stockage..." -ForegroundColor Yellow
& "C:\xampp\php\php.exe" artisan storage:link

Write-Host ""
Write-Host "[4/5] Vérification de la base de données..." -ForegroundColor Yellow
& "C:\xampp\php\php.exe" artisan migrate:status

Write-Host ""
Write-Host "[5/5] Démarrage du serveur..." -ForegroundColor Yellow
Write-Host ""

# Configuration unifiée du serveur (modifiable)
$HostName = "localhost"
$Port = 8000
# Construire une base URL sûre pour PowerShell (le ':' après une variable nécessite ${})
$BaseUrl = "http://${HostName}:$Port"

## Définir les variables d'environnement pour éviter l'erreur 419 (CSRF/Session)
$env:APP_ENV = "local"
$env:APP_URL = $BaseUrl
$env:SESSION_DOMAIN = $HostName
$env:SESSION_SECURE_COOKIE = "false"
$env:SESSION_DRIVER = "file"

# Re-clear caches avec les variables actives
& "C:\xampp\php\php.exe" artisan config:clear
& "C:\xampp\php\php.exe" artisan cache:clear
& "C:\xampp\php\php.exe" artisan route:clear
& "C:\xampp\php\php.exe" artisan view:clear

Write-Host "La plateforme CSAR démarre sur $BaseUrl" -ForegroundColor Green
Write-Host ""
Write-Host "Acces aux interfaces:" -ForegroundColor White
Write-Host "   - Public: $BaseUrl" -ForegroundColor Gray
Write-Host "   - Admin: $BaseUrl/admin/login" -ForegroundColor Gray
Write-Host "   - DG: $BaseUrl/dg/login" -ForegroundColor Gray
Write-Host "   - DRH: $BaseUrl/drh/login" -ForegroundColor Gray
Write-Host "   - Responsable: $BaseUrl/entrepot/login" -ForegroundColor Gray
Write-Host "   - Agent: $BaseUrl/agent/login" -ForegroundColor Gray
Write-Host ""
Write-Host "Appuyez sur Ctrl+C pour arreter le serveur" -ForegroundColor Red
Write-Host ""

$env:APP_URL = $BaseUrl
$env:SESSION_DOMAIN = $HostName
$env:SESSION_SECURE_COOKIE = "false"
$env:SESSION_DRIVER = "file"

& "C:\xampp\php\php.exe" artisan serve --host=$HostName --port=$Port