@echo off
echo ========================================
echo    SAUVEGARDE CSAR vers csar.sn
echo ========================================

REM Creer le dossier de destination
if not exist "C:\xampp\htdocs\csar.sn" (
    echo Creation du dossier C:\xampp\htdocs\csar.sn...
    mkdir "C:\xampp\htdocs\csar.sn"
)

REM Copier les dossiers principaux
echo.
echo Copie des dossiers principaux...
robocopy "app" "C:\xampp\htdocs\csar.sn\app" /E /NFL /NDL /NJH /NJS
robocopy "config" "C:\xampp\htdocs\csar.sn\config" /E /NFL /NDL /NJH /NJS
robocopy "database" "C:\xampp\htdocs\csar.sn\database" /E /NFL /NDL /NJH /NJS
robocopy "public" "C:\xampp\htdocs\csar.sn\public" /E /NFL /NDL /NJH /NJS
robocopy "resources" "C:\xampp\htdocs\csar.sn\resources" /E /NFL /NDL /NJH /NJS
robocopy "routes" "C:\xampp\htdocs\csar.sn\routes" /E /NFL /NDL /NJH /NJS
robocopy "storage" "C:\xampp\htdocs\csar.sn\storage" /E /NFL /NDL /NJH /NJS
robocopy "bootstrap" "C:\xampp\htdocs\csar.sn\bootstrap" /E /NFL /NDL /NJH /NJS

REM Copier les fichiers racine
echo.
echo Copie des fichiers racine...
copy "artisan" "C:\xampp\htdocs\csar.sn\" >nul
copy "composer.json" "C:\xampp\htdocs\csar.sn\" >nul
copy "composer.lock" "C:\xampp\htdocs\csar.sn\" >nul
copy "package.json" "C:\xampp\htdocs\csar.sn\" >nul
copy "webpack.mix.js" "C:\xampp\htdocs\csar.sn\" >nul
copy "README.md" "C:\xampp\htdocs\csar.sn\" >nul
copy ".gitignore" "C:\xampp\htdocs\csar.sn\" >nul

REM Creer les dossiers necessaires pour Laravel
echo.
echo Creation des dossiers necessaires...
if not exist "C:\xampp\htdocs\csar.sn\storage\app\public" mkdir "C:\xampp\htdocs\csar.sn\storage\app\public"
if not exist "C:\xampp\htdocs\csar.sn\storage\framework\cache" mkdir "C:\xampp\htdocs\csar.sn\storage\framework\cache"
if not exist "C:\xampp\htdocs\csar.sn\storage\framework\sessions" mkdir "C:\xampp\htdocs\csar.sn\storage\framework\sessions"
if not exist "C:\xampp\htdocs\csar.sn\storage\framework\views" mkdir "C:\xampp\htdocs\csar.sn\storage\framework\views"
if not exist "C:\xampp\htdocs\csar.sn\storage\logs" mkdir "C:\xampp\htdocs\csar.sn\storage\logs"
if not exist "C:\xampp\htdocs\csar.sn\bootstrap\cache" mkdir "C:\xampp\htdocs\csar.sn\bootstrap\cache"

REM Creer le fichier .env.example
echo.
echo Creation du fichier .env.example...
(
echo APP_NAME=CSAR
echo APP_ENV=local
echo APP_KEY=
echo APP_DEBUG=true
echo APP_URL=http://localhost:8000
echo.
echo LOG_CHANNEL=stack
echo LOG_DEPRECATIONS_CHANNEL=null
echo LOG_LEVEL=debug
echo.
echo DB_CONNECTION=mysql
echo DB_HOST=127.0.0.1
echo DB_PORT=3306
echo DB_DATABASE=csar
echo DB_USERNAME=root
echo DB_PASSWORD=
echo.
echo BROADCAST_DRIVER=log
echo CACHE_DRIVER=file
echo FILESYSTEM_DISK=local
echo QUEUE_CONNECTION=sync
echo SESSION_DRIVER=file
echo SESSION_LIFETIME=120
echo.
echo MEMCACHED_HOST=127.0.0.1
echo.
echo REDIS_HOST=127.0.0.1
echo REDIS_PASSWORD=null
echo REDIS_PORT=6379
echo.
echo MAIL_MAILER=smtp
echo MAIL_HOST=mailpit
echo MAIL_PORT=1025
echo MAIL_USERNAME=null
echo MAIL_PASSWORD=null
echo MAIL_ENCRYPTION=null
echo MAIL_FROM_ADDRESS="hello@example.com"
echo MAIL_FROM_NAME="${APP_NAME}"
echo.
echo AWS_ACCESS_KEY_ID=
echo AWS_SECRET_ACCESS_KEY=
echo AWS_DEFAULT_REGION=us-east-1
echo AWS_BUCKET=
echo AWS_USE_PATH_STYLE_ENDPOINT=false
echo.
echo PUSHER_APP_ID=
echo PUSHER_APP_KEY=
echo PUSHER_APP_SECRET=
echo PUSHER_HOST=
echo PUSHER_PORT=443
echo PUSHER_SCHEME=https
echo PUSHER_APP_CLUSTER=mt1
echo.
echo VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
echo VITE_PUSHER_HOST="${PUSHER_HOST}"
echo VITE_PUSHER_PORT="${PUSHER_PORT}"
echo VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
echo VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
echo.
echo # SMS Configuration
echo SMS_PROVIDER=twilio
echo SMS_FROM=+1234567890
echo TWILIO_SID=your_twilio_sid
echo TWILIO_TOKEN=your_twilio_token
echo.
echo # Price Monitoring
echo PRICE_MONITORING_ENABLED=true
echo PRICE_ALERT_THRESHOLD=10
) > "C:\xampp\htdocs\csar.sn\.env.example"

echo.
echo ========================================
echo    SAUVEGARDE TERMINEE !
echo ========================================
echo.
echo Le projet a ete sauvegarde dans: C:\xampp\htdocs\csar.sn
echo.
echo Prochaines etapes:
echo 1. Aller dans le dossier C:\xampp\htdocs\csar.sn
echo 2. Copier .env.example vers .env
echo 3. Configurer la base de donnees dans .env
echo 4. Executer: composer install
echo 5. Executer: php artisan key:generate
echo 6. Executer: php artisan migrate
echo 7. Executer: php artisan storage:link
echo 8. Executer: php artisan serve
echo.
pause

