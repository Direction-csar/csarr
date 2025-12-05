<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== STRUCTURE TABLES COMMUNICATION ===\n";

$tables = ['messages', 'notifications', 'newsletter_subscribers'];

foreach ($tables as $table) {
    if (Illuminate\Support\Facades\Schema::hasTable($table)) {
        echo "\nTable '$table':\n";
        $columns = Illuminate\Support\Facades\Schema::getColumnListing($table);
        foreach ($columns as $col) {
            echo "  - $col\n";
        }
    } else {
        echo "\n❌ Table '$table': N'existe pas\n";
    }
}

