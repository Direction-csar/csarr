<?php
try {
    $mysql = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "Connexion MySQL OK\n";
    
    $sqlite = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
    echo "Connexion SQLite OK\n";
    
    $tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables SQLite trouvées: " . implode(", ", $tables) . "\n";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}