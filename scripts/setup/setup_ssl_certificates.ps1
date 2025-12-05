# Script PowerShell pour configurer SSL sur Windows avec XAMPP
# Configuration HTTPS pour la plateforme CSAR

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Configuration SSL pour CSAR Platform" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Vérifier les privilèges administrateur
if (-NOT ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole] "Administrator")) {
    Write-Host "ERREUR: Ce script doit être exécuté en tant qu'administrateur" -ForegroundColor Red
    Write-Host "Clic droit sur PowerShell et 'Exécuter en tant qu'administrateur'" -ForegroundColor Yellow
    Read-Host "Appuyez sur Entrée pour quitter"
    exit 1
}

# Vérifier si XAMPP est installé
$xamppPath = "C:\xampp"
if (-not (Test-Path "$xamppPath\apache\bin\httpd.exe")) {
    Write-Host "ERREUR: XAMPP n'est pas installé dans $xamppPath" -ForegroundColor Red
    Write-Host "Veuillez installer XAMPP d'abord." -ForegroundColor Yellow
    Read-Host "Appuyez sur Entrée pour quitter"
    exit 1
}

Write-Host "1. Création des répertoires SSL..." -ForegroundColor Green
$sslCrtPath = "$xamppPath\apache\conf\ssl.crt"
$sslKeyPath = "$xamppPath\apache\conf\ssl.key"

if (-not (Test-Path $sslCrtPath)) { New-Item -ItemType Directory -Path $sslCrtPath -Force }
if (-not (Test-Path $sslKeyPath)) { New-Item -ItemType Directory -Path $sslKeyPath -Force }

Write-Host "2. Génération du certificat SSL auto-signé..." -ForegroundColor Green

# Vérifier si OpenSSL est disponible
$opensslPath = "$xamppPath\apache\bin\openssl.exe"
if (-not (Test-Path $opensslPath)) {
    Write-Host "ERREUR: OpenSSL n'est pas trouvé dans XAMPP" -ForegroundColor Red
    Write-Host "Veuillez réinstaller XAMPP avec OpenSSL" -ForegroundColor Yellow
    Read-Host "Appuyez sur Entrée pour quitter"
    exit 1
}

# Changer vers le répertoire de configuration Apache
Set-Location "$xamppPath\apache\conf"

# Générer la clé privée
Write-Host "   Génération de la clé privée..." -ForegroundColor Yellow
& $opensslPath genrsa -out "ssl.key\csar.local.key" 2048

# Générer le certificat auto-signé
Write-Host "   Génération du certificat auto-signé..." -ForegroundColor Yellow
& $opensslPath req -new -x509 -key "ssl.key\csar.local.key" -out "ssl.crt\csar.local.crt" -days 365 -subj "/C=SN/ST=Dakar/L=Dakar/O=CSAR/OU=IT/CN=csar.local"

Write-Host "3. Configuration du virtual host HTTPS..." -ForegroundColor Green

# Créer le fichier de configuration SSL pour CSAR
$sslConfig = @"
# Configuration SSL pour CSAR Platform
<VirtualHost *:443>
    ServerName csar.local
    DocumentRoot "C:/xampp/htdocs/csar-platform/public"
    
    SSLEngine on
    SSLCertificateFile "conf/ssl.crt/csar.local.crt"
    SSLCertificateKeyFile "conf/ssl.key/csar.local.key"
    
    <Directory "C:/xampp/htdocs/csar-platform/public">
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/csar_ssl_error.log"
    CustomLog "logs/csar_ssl_access.log" common
</VirtualHost>

# Redirection HTTP vers HTTPS
<VirtualHost *:80>
    ServerName csar.local
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>
"@

$sslConfig | Out-File -FilePath "$xamppPath\apache\conf\extra\httpd-vhosts-csar-ssl.conf" -Encoding UTF8

Write-Host "4. Mise à jour du fichier hosts..." -ForegroundColor Green
$hostsFile = "C:\Windows\System32\drivers\etc\hosts"
$hostsEntry = "127.0.0.1 csar.local"

# Vérifier si l'entrée existe déjà
$hostsContent = Get-Content $hostsFile
if ($hostsContent -notcontains $hostsEntry) {
    Add-Content -Path $hostsFile -Value $hostsEntry
    Write-Host "   Entrée ajoutée dans le fichier hosts" -ForegroundColor Yellow
} else {
    Write-Host "   Entrée déjà présente dans le fichier hosts" -ForegroundColor Yellow
}

Write-Host "5. Configuration du fichier httpd.conf..." -ForegroundColor Green

# Vérifier si le module SSL est activé
$httpdConfPath = "$xamppPath\apache\conf\httpd.conf"
$httpdConfContent = Get-Content $httpdConfPath

# Activer le module SSL
if ($httpdConfContent -notcontains "LoadModule ssl_module modules/mod_ssl.so") {
    $httpdConfContent += "LoadModule ssl_module modules/mod_ssl.so"
    $httpdConfContent | Out-File -FilePath $httpdConfPath -Encoding UTF8
    Write-Host "   Module SSL activé" -ForegroundColor Yellow
}

# Inclure la configuration SSL
if ($httpdConfContent -notcontains "Include conf/extra/httpd-vhosts-csar-ssl.conf") {
    $httpdConfContent += "Include conf/extra/httpd-vhosts-csar-ssl.conf"
    $httpdConfContent | Out-File -FilePath $httpdConfPath -Encoding UTF8
    Write-Host "   Configuration SSL incluse" -ForegroundColor Yellow
}

Write-Host "6. Redémarrage d'Apache..." -ForegroundColor Green
try {
    Stop-Service -Name "Apache2.4" -Force -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 2
    Start-Service -Name "Apache2.4" -ErrorAction SilentlyContinue
    Write-Host "   Apache redémarré avec succès" -ForegroundColor Yellow
} catch {
    Write-Host "   Erreur lors du redémarrage d'Apache: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "   Veuillez redémarrer Apache manuellement depuis le panneau de contrôle XAMPP" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "Configuration SSL terminée !" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Votre plateforme CSAR est maintenant accessible via :" -ForegroundColor Cyan
Write-Host "- HTTP:  http://localhost:8000" -ForegroundColor White
Write-Host "- HTTPS: https://csar.local" -ForegroundColor White
Write-Host ""
Write-Host "IMPORTANT :" -ForegroundColor Yellow
Write-Host "- Acceptez le certificat auto-signé dans votre navigateur" -ForegroundColor White
Write-Host "- Utilisez https://csar.local pour tester la géolocalisation" -ForegroundColor White
Write-Host "- Le certificat est valide pour 365 jours" -ForegroundColor White
Write-Host ""
Write-Host "Pour tester la géolocalisation :" -ForegroundColor Cyan
Write-Host "1. Ouvrez https://csar.local" -ForegroundColor White
Write-Host "2. Acceptez le certificat auto-signé" -ForegroundColor White
Write-Host "3. Testez le formulaire de demande d'aide alimentaire" -ForegroundColor White
Write-Host ""

Read-Host "Appuyez sur Entrée pour continuer"