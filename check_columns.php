<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COLONNES TABLE USERS ===\n";
$columns = Illuminate\Support\Facades\Schema::getColumnListing('users');
echo "Colonnes: " . implode(', ', $columns) . "\n";

echo "\n=== COLONNES TABLE DEMANDES ===\n";
$columns = Illuminate\Support\Facades\Schema::getColumnListing('demandes');
echo "Colonnes: " . implode(', ', $columns) . "\n";

echo "\n=== COLONNES TABLE STOCK_MOVEMENTS ===\n";
$columns = Illuminate\Support\Facades\Schema::getColumnListing('stock_movements');
echo "Colonnes: " . implode(', ', $columns) . "\n";

