@echo off
echo ========================================
echo Diagnostic MySQL CSAR
echo ========================================
echo.

echo Verification du statut de MySQL dans XAMPP...
echo.

REM Verifier si MySQL est en cours d'execution
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [OK] MySQL est en cours d'execution
    echo.
) else (
    echo [ERREUR] MySQL n'est pas en cours d'execution
    echo.
    echo SOLUTION : Demarrez MySQL depuis le panneau de controle XAMPP
    echo.
    echo Voulez-vous ouvrir le panneau de controle XAMPP ? (O/N)
    set /p choix=
    if /i "%choix%"=="O" (
        start "" "C:\xampp\xampp-control.exe"
    )
    goto :fin
)

echo Verification de la connexion MySQL...
echo.

REM Tester la connexion MySQL
"C:\xampp\mysql\bin\mysql.exe" -u root -e "SELECT 'Connexion reussie!' as Status;" 2>nul
if "%ERRORLEVEL%"=="0" (
    echo [OK] Connexion MySQL reussie
    echo.
) else (
    echo [ERREUR] Impossible de se connecter a MySQL
    echo.
)

echo Verification de la base de donnees plateforme_csar...
echo.

"C:\xampp\mysql\bin\mysql.exe" -u root -e "SHOW DATABASES LIKE 'plateforme_csar';" 2>nul | find "plateforme_csar" >nul
if "%ERRORLEVEL%"=="0" (
    echo [OK] Base de donnees plateforme_csar existe
    echo.
) else (
    echo [ATTENTION] Base de donnees plateforme_csar n'existe pas
    echo.
)

echo Affichage des parametres MySQL de .env...
echo.
findstr /B "DB_" .env
echo.

:fin
echo ========================================
echo Diagnostic termine
echo ========================================
pause


