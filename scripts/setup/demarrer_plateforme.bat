@echo off
echo ========================================
echo    PLATEFORME CSAR - DEMARRAGE
echo ========================================
echo.

echo [1/5] Nettoyage des caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo [2/5] Vérification des permissions...
if not exist "storage\logs" mkdir "storage\logs"
if not exist "bootstrap\cache" mkdir "bootstrap\cache"

echo.
echo [3/5] Création du lien de stockage...
php artisan storage:link

echo.
echo [4/5] Vérification de la base de données...
php artisan migrate:status

echo.
echo [5/5] Démarrage du serveur...
echo.
echo 🚀 La plateforme CSAR démarre sur http://127.0.0.1:8000
echo.
echo 📋 Accès aux interfaces:
echo    - Public: http://127.0.0.1:8000
echo    - Admin: http://127.0.0.1:8000/admin/login
echo    - DG: http://127.0.0.1:8000/dg/login
echo    - Responsable: http://127.0.0.1:8000/entrepot/login
echo    - Agent: http://127.0.0.1:8000/agent/login
echo.
echo ⏹️  Appuyez sur Ctrl+C pour arrêter le serveur
echo.

php artisan serve --host=127.0.0.1 --port=8000

pause 