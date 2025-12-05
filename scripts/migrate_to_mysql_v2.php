<?php
// Script optimisé pour la migration SQLite → MySQL
// Gère correctement:
// - Tables spéciales (cache, sessions)
// - Types de colonnes avec index
// - Valeurs par défaut
// - Foreign keys
// - Encodage UTF-8

try {
    $sqlite = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $mysql = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
        ]
    );
    
    echo "Connexions établies.\n";
    
    // Tables spéciales avec structure MySQL optimisée
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
    
    // 1. Créer d'abord les tables spéciales
    echo "\nCréation des tables spéciales...\n";
    foreach ($specialTables as $table => $sql) {
        echo "Table {$table}... ";
        $mysql->exec("DROP TABLE IF EXISTS `{$table}`");
        $mysql->exec($sql);
        echo "OK\n";
    }
    
    // 2. Obtenir la liste des autres tables
    $tables = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' AND name NOT IN ('cache','sessions','cache_locks') ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    
    // 3. Pour chaque table
    foreach ($tables as $table) {
        echo "\nTraitement de la table {$table}:\n";
        
        // Structure
        $cols = $sqlite->query("PRAGMA table_info('{$table}')")->fetchAll(PDO::FETCH_ASSOC);
        $indexes = $sqlite->query("PRAGMA index_list('{$table}')")->fetchAll(PDO::FETCH_ASSOC);
        $fks = $sqlite->query("PRAGMA foreign_key_list('{$table}')")->fetchAll(PDO::FETCH_ASSOC);
        
        // Construire CREATE TABLE
        $columnDefs = [];
        $primaryKey = [];
        
        foreach ($cols as $col) {
            $name = $col['name'];
            $type = $col['type'];
            $notnull = $col['notnull'] ? 'NOT NULL' : 'NULL';
            $default = $col['dflt_value'];
            
            // Mapper le type SQLite → MySQL
            $mysqlType = match(true) {
                preg_match('/int/i', $type) => 'INT',
                preg_match('/varchar\((\d+)\)/i', $type, $m) => "VARCHAR({$m[1]})",
                preg_match('/char\((\d+)\)/i', $type, $m) => "CHAR({$m[1]})",
                preg_match('/text|clob/i', $type) => 'LONGTEXT',
                preg_match('/blob/i', $type) => 'LONGBLOB',
                preg_match('/real|float|double/i', $type) => 'DOUBLE',
                preg_match('/decimal|numeric/i', $type) => 'DECIMAL(16,4)',
                preg_match('/datetime/i', $type) => 'DATETIME',
                preg_match('/boolean/i', $type) => 'TINYINT(1)',
                default => 'TEXT'
            };
            
            // Pour les colonnes indexées, utiliser VARCHAR
            $isIndexed = false;
            foreach ($indexes as $idx) {
                $idxInfo = $sqlite->query("PRAGMA index_info('{$idx['name']}')")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($idxInfo as $ii) {
                    if ($ii['name'] === $name) {
                        $isIndexed = true;
                        break 2;
                    }
                }
            }
            if ($isIndexed && $mysqlType === 'LONGTEXT') {
                $mysqlType = 'VARCHAR(255)';
            }

            // Gestion spéciale des types pour les colonnes clés
            if ($col['pk'] || $isIndexed) {
                if (preg_match('/int/i', $type)) {
                    $mysqlType = $col['pk'] && count($primaryKey) === 0 ? 'BIGINT UNSIGNED' : 'INT';
                } elseif (in_array(strtoupper($mysqlType), ['LONGTEXT', 'TEXT', 'LONGBLOB', 'BLOB'])) {
                    $mysqlType = 'VARCHAR(255)';
                }
            }
            
            $def = "`{$name}` {$mysqlType} {$notnull}";
            
            // Gérer la valeur par défaut
            if ($default !== null) {
                if ($default === 'CURRENT_TIMESTAMP') {
                    $def .= " DEFAULT CURRENT_TIMESTAMP";
                } elseif (is_numeric($default)) {
                    $def .= " DEFAULT " . $default;
                } elseif (in_array(strtoupper($default), ['NULL', 'TRUE', 'FALSE'])) {
                    $def .= " DEFAULT " . strtoupper($default);
                } else {
                    $def .= " DEFAULT '" . str_replace("'", "\\'", trim($default, "'\"")) . "'";
                }
            }
            
            if ($col['pk']) {
                $primaryKey[] = $name;
                if (count($primaryKey) === 1 && preg_match('/int/i', $type)) {
                    $def .= ' AUTO_INCREMENT';
                }
            }
            
            $columnDefs[] = $def;
        }
        
        // Ajouter la PRIMARY KEY
        if (!empty($primaryKey)) {
            $columnDefs[] = "PRIMARY KEY (`" . implode('`,`', $primaryKey) . "`)";
        }
        
        // Créer la table
        $createTable = "CREATE TABLE IF NOT EXISTS `{$table}` (\n  " . 
            implode(",\n  ", $columnDefs) . 
            "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        echo "- Création... ";
        $mysql->exec("DROP TABLE IF EXISTS `{$table}`");
        $mysql->exec($createTable);
        echo "OK\n";
        
        // Copier les données
        echo "- Copie des données... ";
        $count = 0;
        $data = $sqlite->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($data)) {
            $columns = array_keys($data[0]);
            $placeholders = rtrim(str_repeat('?,', count($columns)), ',');
            $columnList = '`' . implode('`,`', $columns) . '`';
            
            $stmt = $mysql->prepare("INSERT INTO `{$table}` ({$columnList}) VALUES ({$placeholders})");
            
            $mysql->beginTransaction();
            foreach ($data as $row) {
                $stmt->execute(array_values($row));
                $count++;
            }
            $mysql->commit();
        }
        echo "{$count} lignes\n";
    }
    
    // 4. Ajouter les foreign keys
    echo "\nAjout des foreign keys...\n";
    foreach ($tables as $table) {
        $fks = $sqlite->query("PRAGMA foreign_key_list('{$table}')")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($fks as $fk) {
            $constraintName = "fk_{$table}_{$fk['from']}_{$fk['table']}_{$fk['to']}";
            $sql = "ALTER TABLE `{$table}` ADD CONSTRAINT `{$constraintName}` " .
                   "FOREIGN KEY (`{$fk['from']}`) REFERENCES `{$fk['table']}` (`{$fk['to']}`) " .
                   "ON UPDATE " . ($fk['on_update'] ?: 'NO ACTION') . " " .
                   "ON DELETE " . ($fk['on_delete'] ?: 'NO ACTION');
            
            try {
                $mysql->exec($sql);
                echo "- {$table}.{$fk['from']} → {$fk['table']}.{$fk['to']} OK\n";
            } catch (Exception $e) {
                echo "- Erreur FK {$table}.{$fk['from']}: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\nMigration terminée avec succès.\n";
    
} catch (Exception $e) {
    echo "\nErreur: " . $e->getMessage() . "\n";
    exit(1);
}