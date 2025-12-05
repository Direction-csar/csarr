@echo off
title SERVEUR LARAVEL CSAR ADMIN
color 0A

echo ========================================
echo    DEMARRAGE SERVEUR LARAVEL CSAR
echo ========================================
echo.

echo 1. Navigation vers le dossier csar-platform...
cd /d "C:\xampp\htdocs\csar\csar-platform"

echo 2. Verification du fichier artisan...
if not exist artisan (
    echo    ❌ ERREUR: Fichier artisan manquant
    echo    Dossier actuel: %CD%
    pause
    exit /b 1
)
echo    ✅ Fichier artisan trouve

echo 3. Nettoyage du cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear

echo 4. Demarrage du serveur Laravel...
echo.
echo    🚀 SERVEUR EN COURS DE DEMARRAGE...
echo    📍 URL: http://localhost:8000
echo.
echo    🔗 LIENS IMPORTANTS:
echo    - Admin: http://localhost:8000/admin/login
echo    - DG: http://localhost:8000/dg/login
echo    - Responsable: http://localhost:8000/responsable/login
echo    - Agent: http://localhost:8000/agent/login
echo.
echo    🔑 IDENTIFIANTS:
echo    - Email: admin@csar.sn
echo    - Mot de passe: password
echo.
echo    ⚠️  NE FERMEZ PAS CETTE FENETRE
echo    ⚠️  Appuyez sur Ctrl+C pour arreter le serveur
echo.

php artisan serve

echo.
echo Serveur arrete.
pause 