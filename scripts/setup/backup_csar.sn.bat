@echo off
echo Sauvegarde du projet CSAR vers csar.sn...

REM Creer le dossier de destination
if not exist "C:\xampp\htdocs\csar.sn" mkdir "C:\xampp\htdocs\csar.sn"

REM Copier les dossiers principaux
echo Copie des dossiers principaux...
xcopy "app" "C:\xampp\htdocs\csar.sn\app" /E /I /Y
xcopy "config" "C:\xampp\htdocs\csar.sn\config" /E /I /Y
xcopy "database" "C:\xampp\htdocs\csar.sn\database" /E /I /Y
xcopy "public" "C:\xampp\htdocs\csar.sn\public" /E /I /Y
xcopy "resources" "C:\xampp\htdocs\csar.sn\resources" /E /I /Y
xcopy "routes" "C:\xampp\htdocs\csar.sn\routes" /E /I /Y
xcopy "storage" "C:\xampp\htdocs\csar.sn\storage" /E /I /Y

REM Copier les fichiers racine
echo Copie des fichiers racine...
copy "*.php" "C:\xampp\htdocs\csar.sn\"
copy "*.json" "C:\xampp\htdocs\csar.sn\"
copy "*.md" "C:\xampp\htdocs\csar.sn\"
copy "*.txt" "C:\xampp\htdocs\csar.sn\"
copy "*.env*" "C:\xampp\htdocs\csar.sn\"

REM Creer les dossiers necessaires
echo Creation des dossiers necessaires...
if not exist "C:\xampp\htdocs\csar.sn\storage\app\public" mkdir "C:\xampp\htdocs\csar.sn\storage\app\public"
if not exist "C:\xampp\htdocs\csar.sn\storage\framework\cache" mkdir "C:\xampp\htdocs\csar.sn\storage\framework\cache"
if not exist "C:\xampp\htdocs\csar.sn\storage\framework\sessions" mkdir "C:\xampp\htdocs\csar.sn\storage\framework\sessions"
if not exist "C:\xampp\htdocs\csar.sn\storage\framework\views" mkdir "C:\xampp\htdocs\csar.sn\storage\framework\views"
if not exist "C:\xampp\htdocs\csar.sn\storage\logs" mkdir "C:\xampp\htdocs\csar.sn\storage\logs"
if not exist "C:\xampp\htdocs\csar.sn\bootstrap\cache" mkdir "C:\xampp\htdocs\csar.sn\bootstrap\cache"

echo Sauvegarde terminee !
echo Le projet a ete copie vers C:\xampp\htdocs\csar.sn
pause

