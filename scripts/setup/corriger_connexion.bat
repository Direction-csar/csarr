@echo off
echo ═══════════════════════════════════════════════════════════════
echo        🔧 CORRECTION DES COMPTES DE CONNEXION CSAR
echo ═══════════════════════════════════════════════════════════════
echo.

echo Verification de MySQL...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if NOT "%ERRORLEVEL%"=="0" (
    echo.
    echo ❌ ERREUR: MySQL n'est pas demarre!
    echo.
    echo 📌 Veuillez demarrer MySQL dans XAMPP avant de continuer
    echo.
    pause
    exit /b 1
)

echo ✅ MySQL est en cours d'execution
echo.

echo Execution du script de verification et correction...
echo.

php verifier_et_corriger_comptes.php

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ═══════════════════════════════════════════════════════════════
    echo                  ✅ CORRECTION TERMINEE !
    echo ═══════════════════════════════════════════════════════════════
    echo.
    echo 🎯 Vous pouvez maintenant vous connecter avec:
    echo    Email: admin@csar.sn (ou dg@csar.sn, responsable@csar.sn, etc.)
    echo    Mot de passe: password
    echo.
    echo 🌐 URLs de connexion:
    echo    Admin:       http://localhost:8000/admin/login
    echo    DG:          http://localhost:8000/dg/login
    echo    Entrepot:    http://localhost:8000/entrepot/login
    echo    Agent:       http://localhost:8000/agent/login
    echo.
) else (
    echo.
    echo ❌ Une erreur s'est produite lors de la correction
    echo.
)

pause


