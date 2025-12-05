<?php
// Script pour créer manuellement les tables spéciales (cache, sessions) avec la bonne structure MySQL
$mysql = new PDO(
    "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
    "root",
    "",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// Tables spéciales avec index sur TEXT
$specialTables = [
    'cache' => "CREATE TABLE IF NOT EXISTS `cache` (
        `key` VARCHAR(255) NOT NULL PRIMARY KEY,
        `value` LONGTEXT NOT NULL,
        `expiration` INTEGER NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
    
    'sessions' => "CREATE TABLE IF NOT EXISTS `sessions` (
        `id` VARCHAR(255) NOT NULL PRIMARY KEY,
        `user_id` BIGINT UNSIGNED NULL,
        `ip_address` VARCHAR(45) NULL,
        `user_agent` TEXT NULL,
        `payload` LONGTEXT NOT NULL,
        `last_activity` INTEGER NOT NULL,
        KEY `sessions_user_id_index` (`user_id`),
        KEY `sessions_last_activity_index` (`last_activity`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
    
    'cache_locks' => "CREATE TABLE IF NOT EXISTS `cache_locks` (
        `key` VARCHAR(255) NOT NULL PRIMARY KEY,
        `owner` VARCHAR(255) NOT NULL,
        `expiration` INTEGER NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
];

try {
    foreach ($specialTables as $table => $sql) {
        echo "Creating special table {$table}...\n";
        $mysql->exec("DROP TABLE IF EXISTS `{$table}`");
        $mysql->exec($sql);
        echo "Table {$table} created.\n";
    }
    
    // Copier les données de SQLite si présentes
    $sqlite = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
    
    foreach ($specialTables as $table => $sql) {
        echo "Copying data for {$table}...\n";
        $rows = $sqlite->query("SELECT * FROM {$table}")->fetchAll(PDO::FETCH_ASSOC);
        if (empty($rows)) {
            echo "No data to copy for {$table}\n";
            continue;
        }
        
        $cols = implode('`,`', array_keys($rows[0]));
        $vals = implode(',', array_fill(0, count($rows[0]), '?'));
        $stmt = $mysql->prepare("INSERT INTO `{$table}` (`{$cols}`) VALUES ({$vals})");
        
        $count = 0;
        foreach ($rows as $row) {
            $stmt->execute(array_values($row));
            $count++;
        }
        echo "Copied {$count} rows for {$table}\n";
    }
    
    echo "Special tables migration completed.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}