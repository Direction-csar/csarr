<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║                                                              ║\n";
echo "║     🧹 NETTOYAGE COMPLET DE LA PLATEFORME CSAR             ║\n";
echo "║                                                              ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "⚠️  ATTENTION : Ce script va supprimer TOUTES les données de test!\n";
echo "Les données suivantes seront CONSERVÉES :\n";
echo "  ✅ Utilisateur admin principal\n";
echo "  ✅ Rôles système\n";
echo "  ✅ Configuration de base\n\n";

// Sauvegarder l'email de l'admin principal
$adminEmail = DB::table('users')->where('role', 'admin')->orWhere('id', 1)->value('email');
echo "👤 Admin principal identifié : {$adminEmail}\n\n";

echo "════════════════════════════════════════════════════════════════\n";
echo "📊 ÉTAT AVANT NETTOYAGE\n";
echo "════════════════════════════════════════════════════════════════\n";

$stats = [];
$tables = ['users', 'demandes', 'public_requests', 'warehouses', 'stocks', 'personnels', 'news', 'newsletters', 'sim_reports', 'notifications'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        $stats[$table] = DB::table($table)->count();
    }
}

foreach ($stats as $table => $count) {
    printf("  %-20s : %d enregistrement(s)\n", ucfirst($table), $count);
}

echo "\n════════════════════════════════════════════════════════════════\n";
echo "🧹 NETTOYAGE EN COURS...\n";
echo "════════════════════════════════════════════════════════════════\n\n";

$cleaned = [];

// 1. DEMANDES
echo "1️⃣ Nettoyage des DEMANDES...\n";
$cleaned['demandes'] = DB::table('demandes')->delete();
$cleaned['public_requests_test'] = DB::table('public_requests')
    ->where(function($q) {
        $q->where('full_name', 'like', '%test%')
          ->orWhere('email', 'like', '%test%')
          ->orWhere('email', 'like', '%example%');
    })->delete();
echo "   ✅ Demandes nettoyées : {$cleaned['demandes']} + {$cleaned['public_requests_test']}\n\n";

// 2. UTILISATEURS (sauf admin)
echo "2️⃣ Nettoyage des UTILISATEURS (sauf admin)...\n";
$cleaned['users'] = DB::table('users')
    ->where('id', '!=', 1)
    ->where('role', '!=', 'admin')
    ->delete();
echo "   ✅ Utilisateurs supprimés : {$cleaned['users']}\n\n";

// 3. ENTREPÔTS (garder les vrais entrepôts du Sénégal)
echo "3️⃣ Nettoyage des ENTREPÔTS...\n";
$cleaned['warehouses'] = DB::table('warehouses')
    ->where(function($q) {
        $q->where('name', 'like', '%test%')
          ->orWhere('name', 'like', '%demo%')
          ->orWhere('name', 'like', '%fake%');
    })->delete();
echo "   ✅ Entrepôts de test supprimés : {$cleaned['warehouses']}\n\n";

// 4. STOCKS
echo "4️⃣ Nettoyage des STOCKS...\n";
// Supprimer tous les stocks (seront recréés avec de vraies données)
$cleaned['stocks'] = DB::table('stocks')->delete();
echo "   ✅ Tous les stocks supprimés : {$cleaned['stocks']}\n\n";

// 5. MOUVEMENTS DE STOCK
echo "5️⃣ Nettoyage des MOUVEMENTS DE STOCK...\n";
$cleaned['stock_movements'] = DB::table('stock_movements')->delete();
echo "   ✅ Mouvements de stock supprimés : {$cleaned['stock_movements']}\n\n";

// 6. PERSONNEL
echo "6️⃣ Nettoyage du PERSONNEL...\n";
if (Schema::hasTable('personnels')) {
    $cleaned['personnels'] = DB::table('personnels')
        ->where(function($q) {
            $q->where('nom', 'like', '%test%')
              ->orWhere('prenom', 'like', '%test%')
              ->orWhere('email', 'like', '%test%')
              ->orWhere('email', 'like', '%example%');
        })->delete();
    echo "   ✅ Personnel de test supprimé : {$cleaned['personnels']}\n\n";
} else {
    echo "   ℹ️  Table 'personnels' n'existe pas\n\n";
}

// 7. ACTUALITÉS
echo "7️⃣ Nettoyage des ACTUALITÉS...\n";
$cleaned['news'] = DB::table('news')
    ->where(function($q) {
        $q->where('title', 'like', '%test%')
          ->orWhere('title', 'like', '%demo%')
          ->orWhere('content', 'like', '%test%');
    })->delete();
echo "   ✅ Actualités de test supprimées : {$cleaned['news']}\n\n";

// 8. GALERIE
echo "8️⃣ Nettoyage de la GALERIE...\n";
if (Schema::hasTable('gallery')) {
    $cleaned['gallery'] = DB::table('gallery')->delete();
    echo "   ✅ Galerie nettoyée : {$cleaned['gallery']}\n\n";
} else {
    echo "   ℹ️  Table 'gallery' n'existe pas\n\n";
}

// 9. MESSAGES
echo "9️⃣ Nettoyage des MESSAGES...\n";
if (Schema::hasTable('messages')) {
    $cleaned['messages'] = DB::table('messages')->delete();
    echo "   ✅ Messages supprimés : {$cleaned['messages']}\n\n";
} else {
    echo "   ℹ️  Table 'messages' n'existe pas\n\n";
}

// 10. NEWSLETTER
echo "🔟 Nettoyage de la NEWSLETTER...\n";
$cleaned['newsletters'] = DB::table('newsletters')->delete();
$cleaned['newsletter_subscribers'] = DB::table('newsletter_subscribers')
    ->where('email', 'like', '%test%')
    ->orWhere('email', 'like', '%example%')
    ->delete();
echo "   ✅ Newsletters supprimées : {$cleaned['newsletters']}\n";
echo "   ✅ Abonnés de test supprimés : {$cleaned['newsletter_subscribers']}\n\n";

// 11. RAPPORTS SIM
echo "1️⃣1️⃣ Nettoyage des RAPPORTS SIM...\n";
$cleaned['sim_reports'] = DB::table('sim_reports')->delete();
echo "   ✅ Rapports SIM supprimés : {$cleaned['sim_reports']}\n\n";

// 12. NOTIFICATIONS
echo "1️⃣2️⃣ Nettoyage des NOTIFICATIONS...\n";
$cleaned['notifications'] = DB::table('notifications')->delete();
echo "   ✅ Notifications supprimées : {$cleaned['notifications']}\n\n";

// 13. AUDIT LOGS (garder les récents)
echo "1️⃣3️⃣ Nettoyage des LOGS D'AUDIT (anciens)...\n";
if (Schema::hasTable('audit_logs')) {
    $cleaned['audit_logs'] = DB::table('audit_logs')
        ->where('created_at', '<', now()->subDays(7))
        ->delete();
    echo "   ✅ Logs de plus de 7 jours supprimés : {$cleaned['audit_logs']}\n\n";
} else {
    echo "   ℹ️  Table 'audit_logs' n'existe pas\n\n";
}

// 14. TÂCHES
echo "1️⃣4️⃣ Nettoyage des TÂCHES...\n";
if (Schema::hasTable('tasks')) {
    $cleaned['tasks'] = DB::table('tasks')->delete();
    echo "   ✅ Tâches supprimées : {$cleaned['tasks']}\n\n";
} else {
    echo "   ℹ️  Table 'tasks' n'existe pas\n\n";
}

// 15. STATISTIQUES (recalculées automatiquement)
echo "1️⃣5️⃣ Nettoyage des STATISTIQUES...\n";
if (Schema::hasTable('statistics')) {
    $cleaned['statistics'] = DB::table('statistics')->delete();
    echo "   ✅ Statistiques supprimées : {$cleaned['statistics']}\n\n";
} else {
    echo "   ℹ️  Table 'statistics' n'existe pas\n\n";
}

// NETTOYAGE DU CACHE
echo "════════════════════════════════════════════════════════════════\n";
echo "🧹 NETTOYAGE DU CACHE\n";
echo "════════════════════════════════════════════════════════════════\n";
try {
    Artisan::call('cache:clear');
    echo "✅ Cache applicatif nettoyé\n";
    
    Artisan::call('view:clear');
    echo "✅ Cache des vues nettoyé\n";
    
    Artisan::call('config:clear');
    echo "✅ Cache de configuration nettoyé\n";
    
    Artisan::call('route:clear');
    echo "✅ Cache des routes nettoyé\n";
    
    Artisan::call('optimize:clear');
    echo "✅ Optimisation nettoyée\n";
} catch (\Exception $e) {
    echo "⚠️  Erreur lors du nettoyage du cache: " . $e->getMessage() . "\n";
}

echo "\n════════════════════════════════════════════════════════════════\n";
echo "📊 ÉTAT APRÈS NETTOYAGE\n";
echo "════════════════════════════════════════════════════════════════\n";

$statsAfter = [];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        $statsAfter[$table] = DB::table($table)->count();
    }
}

foreach ($statsAfter as $table => $count) {
    printf("  %-20s : %d enregistrement(s)\n", ucfirst($table), $count);
}

// RÉSUMÉ
echo "\n════════════════════════════════════════════════════════════════\n";
echo "📈 RÉSUMÉ DU NETTOYAGE\n";
echo "════════════════════════════════════════════════════════════════\n";

$totalCleaned = 0;
foreach ($cleaned as $table => $count) {
    if ($count > 0) {
        $totalCleaned += $count;
        printf("  ✅ %-25s : %d supprimé(s)\n", ucfirst(str_replace('_', ' ', $table)), $count);
    }
}

echo "\n💾 TOTAL : {$totalCleaned} enregistrements supprimés\n";

echo "\n════════════════════════════════════════════════════════════════\n";
echo "✅ NETTOYAGE COMPLET TERMINÉ AVEC SUCCÈS!\n";
echo "════════════════════════════════════════════════════════════════\n\n";

echo "🔄 PROCHAINES ÉTAPES:\n";
echo "   1. Actualisez votre navigateur (Ctrl+F5)\n";
echo "   2. Videz le cache du navigateur\n";
echo "   3. Testez tous les modules de la plateforme:\n";
echo "      • Dashboard\n";
echo "      • Demandes\n";
echo "      • Utilisateurs\n";
echo "      • Entrepôts\n";
echo "      • Stocks\n";
echo "      • Personnel\n";
echo "      • Actualités\n";
echo "      • Newsletter\n";
echo "      • Rapports SIM\n";
echo "   4. Vérifiez que plus aucune donnée de test n'apparaît\n";
echo "   5. Testez les suppressions et modifications\n\n";

echo "🎉 Votre plateforme est maintenant PROPRE et prête pour les tests!\n\n";

