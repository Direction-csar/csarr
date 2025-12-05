@echo off
echo ========================================
echo   DEMARRAGE PLATEFORME CSAR - DEMANDES
echo ========================================
echo.

echo 1. Demarrage du serveur Apache...
start /B C:\xampp\apache\bin\httpd.exe

echo 2. Demarrage de MySQL...
start /B C:\xampp\mysql\bin\mysqld.exe

echo 3. Attente du demarrage des services...
timeout /t 3 /nobreak >nul

echo 4. Verification de la base de donnees...
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS csar_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul

echo 5. Ouverture du navigateur...
start http://localhost/csar-platform/public/demande

echo.
echo ========================================
echo   PLATEFORME DEMARREE AVEC SUCCES !
echo ========================================
echo.
echo Le formulaire de demande est maintenant accessible a :
echo http://localhost/csar-platform/public/demande
echo.
echo AMELIORATIONS APPORTEES :
echo - Geolocalisation amelioree avec bouton de reessai
echo - Messages d'erreur detailles avec suggestions
echo - Avertissement HTTPS pour la geolocalisation
echo - Message de confirmation apres envoi
echo - Code de suivi unique genere automatiquement
echo - Base de donnees MySQL configuree
echo - DISTINCTION AUTOMATIQUE DES TYPES DE DEMANDES
echo - Envoi de SMS pour les demandes d'aide uniquement
echo - Messages de confirmation differents selon le type
echo - Suivi SMS integre pour les demandes d'aide
echo - SOUMISSION AJAX SANS RECHARGEMENT DE PAGE
echo - Popup de confirmation elegante avec animations
echo - Notification email automatique a l'admin
echo - Gestion d'erreurs avec popup d'erreur
echo.
echo Pour arreter les services, fermez cette fenetre.
echo.
pause
