@echo off
echo ============================================== 
echo    MIGRATION VERS MYSQL - PLATEFORME CSAR
echo ==============================================
echo.

REM Set le chemin de PHP
set PHP=C:\xampp\php\php.exe

echo Etape 1: Generation de la cle d'application...
%PHP% artisan key:generate
echo.

echo Etape 2: Nettoyage du cache...
%PHP% artisan config:clear
%PHP% artisan cache:clear
echo.

echo Etape 3: Execution des migrations...
echo Cela va creer toutes les tables dans la base de donnees.
echo.
%PHP% artisan migrate:fresh

echo.
echo Etape 4: Insertion des donnees initiales...
%PHP% artisan db:seed

echo.
echo ==============================================
echo       MIGRATION TERMINEE!
echo ==============================================
echo.
echo Verifiez les tables dans phpMyAdmin:
echo http://localhost/phpmyadmin
echo.
echo Pour demarrer le serveur:
echo   php artisan serve
echo.
pause

















