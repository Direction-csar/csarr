@echo off
echo ========================================
echo Demarrage MySQL pour CSAR
echo ========================================
echo.

echo Verification du statut de MySQL...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo MySQL est deja en cours d'execution!
    echo.
    goto :test_connexion
)

echo MySQL n'est pas demarre. Demarrage en cours...
echo.

REM Demarrer MySQL via XAMPP
net start mysql >nul 2>&1
if "%ERRORLEVEL%"=="0" (
    echo [OK] MySQL demarre avec succes via le service Windows
    goto :test_connexion
) else (
    echo Tentative de demarrage via XAMPP...
    start "" "C:\xampp\mysql_start.bat"
    timeout /t 5 /nobreak >nul
)

:test_connexion
echo.
echo Test de connexion MySQL...
echo.
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SELECT 'MySQL est pret!' as Status;"
if "%ERRORLEVEL%"=="0" (
    echo.
    echo [SUCCES] MySQL est operationnel!
    echo Vous pouvez maintenant acceder a votre application CSAR
    echo.
    echo Voulez-vous demarrer le serveur Laravel ? (O/N)
    set /p choix=
    if /i "%choix%"=="O" (
        echo.
        echo Demarrage du serveur Laravel sur http://localhost:8000...
        php artisan serve
    )
) else (
    echo.
    echo [ERREUR] Impossible de se connecter a MySQL
    echo.
    echo SOLUTIONS POSSIBLES :
    echo 1. Ouvrez le panneau de controle XAMPP
    echo 2. Cliquez sur "Start" a cote de MySQL
    echo 3. Attendez que le bouton devienne vert
    echo 4. Relancez ce script
    echo.
    echo Voulez-vous ouvrir le panneau XAMPP ? (O/N)
    set /p choix2=
    if /i "%choix2%"=="O" (
        start "" "C:\xampp\xampp-control.exe"
    )
)

echo.
pause


