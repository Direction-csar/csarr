<?php
// Script modifié pour gérer les index sur TEXT/BLOB
try {
    $pdo = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
    $stmt = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='cache'");
    $createSql = $stmt->fetchColumn();
    echo "SQLite CREATE TABLE original:\n$createSql\n\n";
    
    // Modifié pour MySQL avec KEY LENGTH sur TEXT/BLOB
    $mysqlSql = "CREATE TABLE IF NOT EXISTS `cache` (
        `key` VARCHAR(255) NOT NULL PRIMARY KEY,
        `value` LONGTEXT NOT NULL,
        `expiration` INTEGER NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $mysql = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "Dropping cache table if exists...\n";
    $mysql->exec("DROP TABLE IF EXISTS cache");
    
    echo "Creating cache table with fixed schema...\n";
    $mysql->exec($mysqlSql);
    
    // Copy data
    echo "Copying cache data...\n";
    foreach($pdo->query("SELECT * FROM cache") as $row) {
        $stmt = $mysql->prepare("INSERT INTO cache (`key`, `value`, `expiration`) VALUES (?, ?, ?)");
        $stmt->execute([$row['key'], $row['value'], $row['expiration']]);
    }
    
    echo "Cache table migration completed.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}