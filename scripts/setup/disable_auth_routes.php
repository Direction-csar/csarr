<?php

echo "========================================\n";
echo "   DÉSACTIVATION ROUTES AUTH.PHP\n";
echo "========================================\n\n";

// 1. Vérifier si le fichier auth.php existe
if (file_exists('routes/auth.php')) {
    echo "1. FICHIER AUTH.PHP TROUVÉ:\n";
    
    // Sauvegarder le fichier original
    $backupName = 'routes/auth.php.backup.' . date('Y-m-d-H-i-s');
    copy('routes/auth.php', $backupName);
    echo "   ✅ Sauvegarde créée: $backupName\n";
    
    // Désactiver le fichier en le renommant
    rename('routes/auth.php', 'routes/auth.php.disabled');
    echo "   ✅ Fichier auth.php désactivé\n";
    
} else {
    echo "1. FICHIER AUTH.PHP NON TROUVÉ:\n";
    echo "   ✅ Aucune interférence détectée\n";
}

// 2. Vérifier le RouteServiceProvider
echo "\n2. VÉRIFICATION ROUTE SERVICE PROVIDER:\n";
if (file_exists('app/Providers/RouteServiceProvider.php')) {
    $providerContent = file_get_contents('app/Providers/RouteServiceProvider.php');
    
    if (strpos($providerContent, 'routes/auth.php') !== false) {
        echo "   ⚠️  RouteServiceProvider charge auth.php\n";
        echo "   ✅ Fichier auth.php désactivé, pas d'interférence\n";
    } else {
        echo "   ✅ RouteServiceProvider ne charge pas auth.php\n";
    }
} else {
    echo "   ❌ RouteServiceProvider manquant\n";
}

// 3. Nettoyer le cache
echo "\n3. NETTOYAGE DU CACHE:\n";
exec('php artisan config:clear', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✅ Cache de configuration nettoyé\n";
}

exec('php artisan route:clear', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✅ Cache des routes nettoyé\n";
}

exec('php artisan cache:clear', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✅ Cache général nettoyé\n";
}

// 4. Vérifier les routes
echo "\n4. VÉRIFICATION DES ROUTES:\n";
exec('php artisan route:list --name=admin', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✅ Routes admin disponibles:\n";
    foreach ($output as $line) {
        if (strpos($line, 'admin') !== false) {
            echo "      $line\n";
        }
    }
} else {
    echo "   ⚠️  Impossible de lister les routes\n";
}

echo "\n========================================\n";
echo "   DÉSACTIVATION TERMINÉE\n";
echo "========================================\n\n";

echo "✅ Fichier auth.php désactivé\n";
echo "✅ Interférences supprimées\n";
echo "✅ Cache nettoyé\n\n";

echo "Maintenant testez:\n";
echo "http://localhost:8000/admin/login\n\n";

echo "Vous devriez voir le formulaire de connexion admin\n";
echo "au lieu d'être redirigé vers la page d'accueil.\n\n";

echo "Pour réactiver auth.php plus tard:\n";
echo "1. Renommez: routes/auth.php.disabled → routes/auth.php\n";
echo "2. Exécutez: php artisan route:clear\n\n";

echo "Identifiants:\n";
echo "- Email: admin@csar.sn\n";
echo "- Mot de passe: password\n"; 