Write-Host "Demarrage du serveur CSAR..." -ForegroundColor Green
Write-Host "URL: http://127.0.0.1:8001" -ForegroundColor Cyan
Write-Host ""

# Demarrer le serveur en masquant les warnings PHP (php.ini.local) sur port 8001
& "C:\xampp\php\php.exe" -c "php.ini.local" artisan serve --host=127.0.0.1 --port=8001 2>$null

Write-Host "Serveur arrete." -ForegroundColor Yellow
