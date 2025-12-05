@echo off
echo ===================================================================
echo     🚀 DEMARRAGE PLATEFORME CSAR
echo ===================================================================
echo.

REM Vérifier si le serveur est déjà en cours d'exécution
netstat -ano | findstr :8000 > nul
if %errorlevel% equ 0 (
    echo ⚠️  Un serveur est déjà en cours d'exécution sur le port 8000
    echo.
    choice /C YN /M "Voulez-vous le redemarrer"
    if errorlevel 2 goto :skip_server
    if errorlevel 1 (
        echo 🔄 Arrêt du serveur existant...
        for /f "tokens=5" %%a in ('netstat -ano ^| findstr :8000') do taskkill /F /PID %%a > nul 2>&1
        timeout /t 2 > nul
    )
)

:skip_server
echo.
echo 🔄 Nettoyage du cache...
php artisan cache:clear
php artisan config:clear

echo.
echo ✅ Cache vidé !
echo.
echo 🚀 Démarrage du serveur Laravel...
echo.
echo ═══════════════════════════════════════════════════════════════
echo     🔐 IDENTIFIANTS DE CONNEXION
echo ═══════════════════════════════════════════════════════════════
echo.
echo 👤 ADMINISTRATEUR
echo    Email: admin@csar.sn
echo    Mot de passe: password
echo    URL: http://localhost:8000/admin/login
echo.
echo 👔 DIRECTEUR GÉNÉRAL (DG)
echo    Email: dg@csar.sn
echo    Mot de passe: password
echo    URL: http://localhost:8000/dg/login
echo.
echo 📦 GESTIONNAIRE D'ENTREPÔT
echo    Email: entrepot@csar.sn
echo    Mot de passe: password
echo    URL: http://localhost:8000/entrepot/login
echo.
echo 👤 DRH
echo    Email: drh@csar.sn
echo    Mot de passe: password
echo    URL: http://localhost:8000
echo.
echo ═══════════════════════════════════════════════════════════════
echo.
echo 💡 Le navigateur va s'ouvrir automatiquement...
echo    Appuyez sur Ctrl+C pour arrêter le serveur
echo.

REM Attendre un peu avant d'ouvrir le navigateur
timeout /t 3 > nul

REM Ouvrir le navigateur
start http://localhost:8000/admin/login

REM Démarrer le serveur
php artisan serve


