@echo off
echo ========================================
echo    DEMARRAGE PLATEFORME CSAR
echo ========================================
echo.

echo 1. Demarrage du serveur Laravel...
echo    URL Publique: http://localhost:8000
echo    URL Admin: http://localhost:8000/admin/login
echo.

cd /d "C:\xampp\htdocs\csar-platform"

echo 2. Verification de la base de donnees...
C:\xampp\php\php.exe -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); echo 'Base de donnees: ' . \DB::connection()->getDatabaseName() . ' (OK)' . PHP_EOL;"
echo.

echo 3. Comptes de test disponibles:
echo    - admin@csar.sn / admin123 (Administrateur)
echo    - dg@csar.sn / dg123 (Directeur General)
echo    - gestionnaire@csar.sn / gest123 (Gestionnaire)
echo    - agent@csar.sn / agent123 (Agent)
echo.

echo 4. Demarrage du serveur...
echo    Appuyez sur Ctrl+C pour arreter le serveur
echo.

C:\xampp\php\php.exe artisan serve --host=0.0.0.0 --port=8000

pause
