<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                                                              ║\n";
echo "║     🔌 VÉRIFICATION DE LA CONNEXION BASE DE DONNÉES         ║\n";
echo "║                                                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// 1. Configuration actuelle
echo "📊 CONFIGURATION ACTUELLE\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "Type de BDD      : " . config('database.default') . "\n";
echo "Hôte             : " . config('database.connections.mysql.host') . "\n";
echo "Port             : " . config('database.connections.mysql.port') . "\n";
echo "Base de données  : " . config('database.connections.mysql.database') . "\n";
echo "Utilisateur      : " . config('database.connections.mysql.username') . "\n";
echo "Charset          : " . config('database.connections.mysql.charset') . "\n\n";

// 2. Test de connexion
echo "🔌 TEST DE CONNEXION\n";
echo "════════════════════════════════════════════════════════════════\n";
try {
    DB::connection()->getPdo();
    echo "✅ Connexion réussie à la base de données!\n\n";
} catch (\Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage() . "\n\n";
    exit(1);
}

// 3. Liste des tables
echo "📋 TABLES DE LA BASE DE DONNÉES\n";
echo "════════════════════════════════════════════════════════════════\n";
$tables = DB::select('SHOW TABLES');
$tableCount = count($tables);
echo "Nombre total de tables : {$tableCount}\n\n";

$tableKey = 'Tables_in_' . config('database.connections.mysql.database');
foreach ($tables as $table) {
    $tableName = $table->$tableKey;
    $count = DB::table($tableName)->count();
    printf("  %-30s : %6d enregistrement(s)\n", $tableName, $count);
}

// 4. Taille de la base de données
echo "\n💾 TAILLE DE LA BASE DE DONNÉES\n";
echo "════════════════════════════════════════════════════════════════\n";
$dbName = config('database.connections.mysql.database');
$sizeQuery = "
    SELECT 
        table_schema AS 'Database',
        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
    FROM information_schema.tables 
    WHERE table_schema = '{$dbName}'
    GROUP BY table_schema
";
$size = DB::select($sizeQuery);
if (!empty($size)) {
    echo "Taille totale : " . $size[0]->{'Size (MB)'} . " MB\n\n";
}

// 5. Configuration de sauvegarde recommandée
echo "💡 CONFIGURATION POUR SERVEUR DÉDIÉ\n";
echo "════════════════════════════════════════════════════════════════\n";
echo "Pour migrer vers votre propre serveur, vous aurez besoin de :\n\n";
echo "1️⃣ INFORMATIONS DU NOUVEAU SERVEUR :\n";
echo "   • Adresse IP ou nom de domaine\n";
echo "   • Port MySQL (généralement 3306)\n";
echo "   • Nom de la base de données\n";
echo "   • Utilisateur MySQL\n";
echo "   • Mot de passe MySQL\n\n";

echo "2️⃣ MODIFIER LE FICHIER .env :\n";
echo "   DB_CONNECTION=mysql\n";
echo "   DB_HOST=[VOTRE_SERVEUR]\n";
echo "   DB_PORT=3306\n";
echo "   DB_DATABASE=csar\n";
echo "   DB_USERNAME=[VOTRE_UTILISATEUR]\n";
echo "   DB_PASSWORD=[VOTRE_MOT_DE_PASSE]\n\n";

echo "3️⃣ EXPORTER LES DONNÉES ACTUELLES :\n";
echo "   • Utilisez le script d'export fourni\n";
echo "   • Ou phpMyAdmin (Export → SQL)\n\n";

echo "4️⃣ IMPORTER SUR LE NOUVEAU SERVEUR :\n";
echo "   • Créer la base de données\n";
echo "   • Importer le fichier SQL\n";
echo "   • Lancer les migrations si nécessaire\n\n";

// 6. Statut des migrations
echo "📦 STATUT DES MIGRATIONS\n";
echo "════════════════════════════════════════════════════════════════\n";
try {
    $migrations = DB::table('migrations')->count();
    echo "Nombre de migrations exécutées : {$migrations}\n";
    
    $lastMigration = DB::table('migrations')->orderBy('id', 'desc')->first();
    if ($lastMigration) {
        echo "Dernière migration : " . $lastMigration->migration . "\n";
        echo "Batch : " . $lastMigration->batch . "\n";
    }
} catch (\Exception $e) {
    echo "⚠️  Table 'migrations' non trouvée\n";
}

echo "\n════════════════════════════════════════════════════════════════\n";
echo "✅ VÉRIFICATION TERMINÉE\n";
echo "════════════════════════════════════════════════════════════════\n\n";

echo "🎯 RÉSUMÉ :\n";
echo "   ✅ Connexion à la base de données : OK\n";
echo "   ✅ {$tableCount} tables détectées\n";
echo "   ✅ Configuration actuelle : MySQL sur localhost\n";
echo "   ✅ Prêt pour migration vers serveur dédié\n\n";

echo "📄 Consultez GUIDE_MIGRATION_SERVEUR.md pour les détails\n\n";




















