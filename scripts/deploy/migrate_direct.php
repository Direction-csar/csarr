<?php

/**
 * Script de migration directe MySQL
 * Exécute les migrations sans dépendre de artisan
 */

echo "==============================================\n";
echo "   MIGRATION DIRECTE VERS MYSQL             \n";
echo "==============================================\n\n";

// Charger Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Étape 1: Vérification de la connexion...\n";
try {
    $pdo = DB::connection()->getPdo();
    $dbName = DB::connection()->getDatabaseName();
    echo "✓ Connecté à la base de données: {$dbName}\n\n";
} catch (\Exception $e) {
    echo "✗ Erreur de connexion: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Étape 2: Suppression des anciennes tables (si elles existent)...\n";
try {
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    $tables = DB::select('SHOW TABLES');
    $count = 0;
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
        $count++;
    }
    
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    echo "✓ {$count} table(s) supprimée(s)\n\n";
} catch (\Exception $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n\n";
}

echo "Étape 3: Exécution des migrations...\n";
echo "Cela peut prendre quelques instants...\n\n";

try {
    // Exécuter les migrations
    Artisan::call('migrate', [
        '--force' => true,
        '--no-interaction' => true
    ]);
    
    $output = Artisan::output();
    echo $output;
    echo "\n✓ Migrations exécutées avec succès!\n\n";
} catch (\Exception $e) {
    echo "✗ Erreur lors des migrations: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "Étape 4: Comptage des tables créées...\n";
try {
    $tables = DB::select('SHOW TABLES');
    $count = count($tables);
    echo "✓ {$count} table(s) créée(s)\n\n";
    
    echo "Tables créées:\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "  - {$tableName}\n";
    }
    echo "\n";
} catch (\Exception $e) {
    echo "⚠ Impossible de lister les tables\n\n";
}

echo "Étape 5: Exécution des seeders...\n";
try {
    Artisan::call('db:seed', [
        '--force' => true,
        '--no-interaction' => true
    ]);
    
    $output = Artisan::output();
    echo $output;
    echo "\n✓ Données initiales insérées!\n\n";
} catch (\Exception $e) {
    echo "⚠ Erreur lors de l'insertion des données: " . $e->getMessage() . "\n";
    echo "  Les tables sont créées, mais certaines données par défaut peuvent manquer.\n\n";
}

echo "==============================================\n";
echo "         MIGRATION TERMINÉE!                  \n";
echo "==============================================\n\n";

echo "✓ Base de données: plateforme-csar\n";
echo "✓ Connexion: MySQL (127.0.0.1:3306)\n";
echo "✓ Tables créées avec succès!\n\n";

echo "Prochaines étapes:\n";
echo "1. Vérifiez les tables dans phpMyAdmin:\n";
echo "   http://localhost/phpmyadmin\n\n";
echo "2. Créez un utilisateur administrateur:\n";
echo "   php create_admin.php\n\n";
echo "3. Démarrez le serveur:\n";
echo "   php artisan serve\n\n";
echo "4. Accédez à l'application:\n";
echo "   http://localhost:8000\n\n";

echo "==============================================\n";

















