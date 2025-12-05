@echo off
chcp 65001 >nul
cls
echo ══════════════════════════════════════════════
echo    MIGRATION MYSQL - PLATEFORME CSAR
echo ══════════════════════════════════════════════
echo.
echo Ce script va:
echo   1. Vérifier la connexion MySQL
echo   2. Générer la clé d'application
echo   3. Créer toutes les tables
echo   4. Insérer les données initiales
echo.
echo ⚠️  IMPORTANT: Assurez-vous que:
echo   - XAMPP MySQL est démarré
echo   - Le fichier .env existe avec les bonnes données
echo.
pause
echo.

set PHP=C:\xampp\php\php.exe
set MYSQL=C:\xampp\mysql\bin\mysql.exe

echo ═══ Étape 1: Vérification MySQL ═══
%MYSQL% -u root -e "SHOW DATABASES LIKE 'plateforme-csar';"
if errorlevel 1 (
    echo ✗ MySQL n'est pas accessible!
    echo   Démarrez MySQL dans XAMPP et réessayez.
    pause
    exit /b 1
)
echo ✓ MySQL est accessible
echo.

echo ═══ Étape 2: Génération clé d'application ═══
%PHP% artisan key:generate
echo.

echo ═══ Étape 3: Nettoyage cache (warnings normaux) ═══
%PHP% artisan config:clear 2>nul
%PHP% artisan cache:clear 2>nul
echo ✓ Cache nettoyé
echo.

echo ═══ Étape 4: Création des tables ═══
echo Cela peut prendre 30-60 secondes...
echo.
%PHP% artisan migrate:fresh --force
if errorlevel 1 (
    echo ✗ Erreur lors de la création des tables!
    echo   Vérifiez que le fichier .env existe et est correct.
    pause
    exit /b 1
)
echo.
echo ✓ Tables créées avec succès!
echo.

echo ═══ Étape 5: Insertion données initiales ═══
%PHP% artisan db:seed --force
if errorlevel 1 (
    echo ⚠  Erreur lors de l'insertion des données
    echo   Les tables sont créées mais peuvent être vides
) else (
    echo ✓ Données initiales insérées!
)
echo.

echo ═══ Étape 6: Vérification finale ═══
%MYSQL% -u root -e "USE `plateforme-csar`; SHOW TABLES;"
echo.

echo ══════════════════════════════════════════════
echo          MIGRATION TERMINÉE! ✓
echo ══════════════════════════════════════════════
echo.
echo ✓ Base de données: plateforme-csar
echo ✓ Tables créées dans MySQL
echo.
echo Prochaines étapes:
echo   1. Vérifiez dans phpMyAdmin:
echo      http://localhost/phpmyadmin
echo.
echo   2. Créez un admin (optionnel):
echo      %PHP% create_admin.php
echo.
echo   3. Démarrez le serveur:
echo      %PHP% artisan serve
echo.
echo   4. Ouvrez: http://localhost:8000
echo.
echo ══════════════════════════════════════════════
pause

















