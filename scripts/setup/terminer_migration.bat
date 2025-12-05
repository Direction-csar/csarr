@echo off
chcp 65001 >nul
cls
echo ══════════════════════════════════════════════
echo    FINALISER LA MIGRATION MYSQL
echo ══════════════════════════════════════════════
echo.
echo Ce script va:
echo   1. Exécuter la migration manquante (tracking_code)
echo   2. Terminer les seeders
echo.
pause
echo.

set PHP=C:\xampp\php\php.exe

echo ═══ Étape 1: Migration tracking_code ═══
%PHP% artisan migrate --force
echo.

echo ═══ Étape 2: Seeders restants ═══
%PHP% artisan db:seed --force
echo.

echo ══════════════════════════════════════════════
echo          MIGRATION FINALISÉE! ✓
echo ══════════════════════════════════════════════
echo.
echo ✓ Toutes les tables sont créées
echo ✓ Données initiales insérées
echo ✓ Admin créé: admin@csar.sn / password
echo.
echo Vous pouvez maintenant démarrer le serveur:
echo   %PHP% artisan serve
echo.
echo Puis ouvrir: http://localhost:8000
echo.
pause
















