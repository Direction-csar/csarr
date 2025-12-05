# Script PowerShell pour copier le projet vers csar.sn
$source = "C:\xampp\htdocs\csar\csar\csar-platform"
$destination = "C:\xampp\htdocs\csar.sn"

# Créer le dossier de destination s'il n'existe pas
if (!(Test-Path $destination)) {
    New-Item -ItemType Directory -Path $destination -Force
}

# Dossiers à exclure
$excludeDirs = @("node_modules", ".git", "vendor", "storage\logs", "storage\framework\cache", "storage\framework\sessions", "storage\framework\views")

# Fonction pour copier en excluant certains dossiers
function Copy-ProjectFiles {
    param($src, $dst)
    
    Get-ChildItem -Path $src -Recurse | ForEach-Object {
        $relativePath = $_.FullName.Substring($src.Length + 1)
        $shouldExclude = $false
        
        foreach ($excludeDir in $excludeDirs) {
            if ($relativePath.StartsWith($excludeDir)) {
                $shouldExclude = $true
                break
            }
        }
        
        if (!$shouldExclude) {
            $destPath = Join-Path $dst $relativePath
            $destDir = Split-Path $destPath -Parent
            
            if (!(Test-Path $destDir)) {
                New-Item -ItemType Directory -Path $destDir -Force
            }
            
            if ($_.PSIsContainer -eq $false) {
                Copy-Item $_.FullName $destPath -Force
            }
        }
    }
}

Write-Host "Copie du projet vers C:\xampp\htdocs\csar.sn..." -ForegroundColor Green
Copy-ProjectFiles -src $source -dst $destination
Write-Host "Copie terminée !" -ForegroundColor Green

# Créer les dossiers nécessaires
$requiredDirs = @(
    "storage\app\public",
    "storage\framework\cache",
    "storage\framework\sessions", 
    "storage\framework\views",
    "storage\logs",
    "bootstrap\cache"
)

foreach ($dir in $requiredDirs) {
    $fullPath = Join-Path $destination $dir
    if (!(Test-Path $fullPath)) {
        New-Item -ItemType Directory -Path $fullPath -Force
        Write-Host "Créé: $fullPath" -ForegroundColor Yellow
    }
}

Write-Host "Structure de dossiers créée !" -ForegroundColor Green

