@echo off
echo Démarrage du serveur CSAR sur le port 8001...
cd /d "C:\xampp\htdocs\csar\csar\csar-platform"
C:\xampp\php\php.exe artisan serve --port=8001 --host=0.0.0.0
pause

