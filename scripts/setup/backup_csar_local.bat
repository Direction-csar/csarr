@echo off
REM Script de Backup CSAR Platform - Version Windows
REM Sauvegarde automatique des fichiers et de la base de données

echo.
echo ========================================
echo    BACKUP CSAR PLATFORM - WINDOWS
echo ========================================
echo.

REM Configuration
set PROJECT_PATH=C:\xampp\htdocs\csar-platform
set BACKUP_PATH=C:\backups\csar
set DB_HOST=localhost
set DB_NAME=csar_platform
set DB_USER=root
set DB_PASS=

REM Créer le timestamp
for /f "tokens=2 delims==" %%a in ('wmic OS Get localdatetime /value') do set "dt=%%a"
set "YY=%dt:~2,2%" & set "YYYY=%dt:~0,4%" & set "MM=%dt:~4,2%" & set "DD=%dt:~6,2%"
set "HH=%dt:~8,2%" & set "Min=%dt:~10,2%" & set "Sec=%dt:~12,2%"
set "timestamp=%YYYY%-%MM%-%DD%_%HH%-%Min%-%Sec%"

echo [%date% %time%] Démarrage du backup...
echo.

REM Créer le dossier de backup
if not exist "%BACKUP_PATH%" (
    mkdir "%BACKUP_PATH%"
    echo [%date% %time%] Dossier de backup créé: %BACKUP_PATH%
)

set BACKUP_DIR=%BACKUP_PATH%\csar_backup_%timestamp%
mkdir "%BACKUP_DIR%"
echo [%date% %time%] Dossier de backup créé: %BACKUP_DIR%
echo.

REM Backup de la base de données
echo [%date% %time%] Sauvegarde de la base de données...
set DB_BACKUP_FILE=%BACKUP_DIR%\csar_database_%timestamp%.sql

REM Utiliser mysqldump de XAMPP
C:\xampp\mysql\bin\mysqldump.exe -h%DB_HOST% -u%DB_USER% -p%DB_PASS% %DB_NAME% > "%DB_BACKUP_FILE%"

if %errorlevel% equ 0 (
    echo [%date% %time%] Base de données sauvegardée avec succès
) else (
    echo [%date% %time%] ERREUR: Échec de la sauvegarde de la base de données
    pause
    exit /b 1
)
echo.

REM Backup des fichiers du projet
echo [%date% %time%] Sauvegarde des fichiers du projet...

REM Utiliser PowerShell pour créer l'archive
powershell -Command "& {Compress-Archive -Path '%PROJECT_PATH%\*' -DestinationPath '%BACKUP_DIR%\csar_files_%timestamp%.zip' -Exclude 'node_modules','vendor','storage\logs','storage\framework\cache','storage\framework\sessions','storage\framework\views','.git','.env'}"

if %errorlevel% equ 0 (
    echo [%date% %time%] Fichiers sauvegardés avec succès
) else (
    echo [%date% %time%] ERREUR: Échec de la sauvegarde des fichiers
    pause
    exit /b 1
)
echo.

REM Créer un fichier d'information
echo [%date% %time%] Création du fichier d'information...
echo Date de backup: %date% %time% > "%BACKUP_DIR%\backup_info.txt"
echo Version PHP: >> "%BACKUP_DIR%\backup_info.txt"
C:\xampp\php\php.exe -v >> "%BACKUP_DIR%\backup_info.txt"
echo. >> "%BACKUP_DIR%\backup_info.txt"
echo Serveur: %COMPUTERNAME% >> "%BACKUP_DIR%\backup_info.txt"
echo Chemin projet: %PROJECT_PATH% >> "%BACKUP_DIR%\backup_info.txt"
echo Chemin backup: %BACKUP_DIR% >> "%BACKUP_DIR%\backup_info.txt"
echo.

REM Créer un script de restauration
echo [%date% %time%] Création du script de restauration...
echo @echo off > "%BACKUP_DIR%\restore.bat"
echo REM Script de restauration CSAR Platform >> "%BACKUP_DIR%\restore.bat"
echo REM Date: %date% %time% >> "%BACKUP_DIR%\restore.bat"
echo echo Restauration CSAR Platform... >> "%BACKUP_DIR%\restore.bat"
echo echo. >> "%BACKUP_DIR%\restore.bat"
echo echo Restauration des fichiers... >> "%BACKUP_DIR%\restore.bat"
echo powershell -Command "& {Expand-Archive -Path 'csar_files_%timestamp%.zip' -DestinationPath '%PROJECT_PATH%' -Force}" >> "%BACKUP_DIR%\restore.bat"
echo echo. >> "%BACKUP_DIR%\restore.bat"
echo echo Restauration de la base de données... >> "%BACKUP_DIR%\restore.bat"
echo C:\xampp\mysql\bin\mysql.exe -h%DB_HOST% -u%DB_USER% -p%DB_PASS% %DB_NAME% ^< csar_database_%timestamp%.sql >> "%BACKUP_DIR%\restore.bat"
echo echo. >> "%BACKUP_DIR%\restore.bat"
echo echo Restauration terminée! >> "%BACKUP_DIR%\restore.bat"
echo pause >> "%BACKUP_DIR%\restore.bat"
echo.

REM Nettoyer les anciens backups (garder seulement les 7 derniers)
echo [%date% %time%] Nettoyage des anciens backups...
for /f "skip=7 delims=" %%i in ('dir /b /o-d "%BACKUP_PATH%\csar_backup_*" 2^>nul') do (
    echo Suppression de l'ancien backup: %%i
    rmdir /s /q "%BACKUP_PATH%\%%i"
)
echo.

REM Résumé final
echo ========================================
echo    BACKUP TERMINÉ AVEC SUCCÈS!
echo ========================================
echo.
echo Dossier de backup: %BACKUP_DIR%
echo Base de données: csar_database_%timestamp%.sql
echo Fichiers: csar_files_%timestamp%.zip
echo Informations: backup_info.txt
echo Restauration: restore.bat
echo.
echo [%date% %time%] Backup terminé!
echo.
pause

