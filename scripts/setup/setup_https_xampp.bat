@echo off
echo ========================================
echo Configuration HTTPS pour XAMPP - CSAR
echo ========================================
echo.

REM Vérifier si XAMPP est installé
if not exist "C:\xampp\apache\bin\httpd.exe" (
    echo ERREUR: XAMPP n'est pas installé dans C:\xampp
    echo Veuillez installer XAMPP d'abord.
    pause
    exit /b 1
)

echo 1. Création du répertoire pour les certificats SSL...
if not exist "C:\xampp\apache\conf\ssl.crt" mkdir "C:\xampp\apache\conf\ssl.crt"
if not exist "C:\xampp\apache\conf\ssl.key" mkdir "C:\xampp\apache\conf\ssl.key"

echo 2. Génération du certificat auto-signé...
cd /d "C:\xampp\apache\conf"

REM Générer la clé privée
openssl genrsa -out ssl.key\csar.local.key 2048

REM Générer le certificat auto-signé
openssl req -new -x509 -key ssl.key\csar.local.key -out ssl.crt\csar.local.crt -days 365 -subj "/C=SN/ST=Dakar/L=Dakar/O=CSAR/OU=IT/CN=csar.local"

echo 3. Configuration du virtual host HTTPS...
echo Création du fichier de configuration HTTPS...

(
echo ^<VirtualHost *:443^>
echo     ServerName csar.local
echo     DocumentRoot "C:/xampp/htdocs/csar-platform/public"
echo     
echo     SSLEngine on
echo     SSLCertificateFile "conf/ssl.crt/csar.local.crt"
echo     SSLCertificateKeyFile "conf/ssl.key/csar.local.key"
echo     
echo     ^<Directory "C:/xampp/htdocs/csar-platform/public"^>
echo         AllowOverride All
echo         Require all granted
echo     ^</Directory^>
echo     
echo     ErrorLog "logs/csar_ssl_error.log"
echo     CustomLog "logs/csar_ssl_access.log" common
echo ^</VirtualHost^>
) > "C:\xampp\apache\conf\extra\httpd-vhosts-ssl.conf"

echo 4. Mise à jour du fichier httpd-ssl.conf...
(
echo # Configuration SSL pour CSAR Platform
echo Listen 443
echo ^<VirtualHost _default_:443^>
echo     DocumentRoot "C:/xampp/htdocs/csar-platform/public"
echo     ServerName csar.local:443
echo     ServerAdmin admin@csar.local
echo     ErrorLog "logs/ssl_error.log"
echo     TransferLog "logs/ssl_access.log"
echo     
echo     SSLEngine on
echo     SSLCertificateFile "conf/ssl.crt/csar.local.crt"
echo     SSLCertificateKeyFile "conf/ssl.key/csar.local.key"
echo     
echo     ^<Directory "C:/xampp/htdocs/csar-platform/public"^>
echo         AllowOverride All
echo         Require all granted
echo     ^</Directory^>
echo ^</VirtualHost^>
) > "C:\xampp\apache\conf\extra\httpd-ssl-csar.conf"

echo 5. Ajout de l'entrée dans le fichier hosts...
echo 127.0.0.1 csar.local >> C:\Windows\System32\drivers\etc\hosts

echo 6. Redémarrage d'Apache...
net stop apache2.4
net start apache2.4

echo.
echo ========================================
echo Configuration HTTPS terminée !
echo ========================================
echo.
echo Votre plateforme CSAR est maintenant accessible via :
echo - HTTP:  http://localhost:8000
echo - HTTPS: https://csar.local
echo.
echo IMPORTANT: 
echo - Acceptez le certificat auto-signé dans votre navigateur
echo - Utilisez https://csar.local pour tester la géolocalisation
echo.
pause
