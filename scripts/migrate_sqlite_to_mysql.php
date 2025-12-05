<?php
// Usage:
// configure env vars: MYSQL_HOST, MYSQL_PORT, MYSQL_DATABASE, MYSQL_USERNAME, MYSQL_PASSWORD
// optional: DROP_EXISTING=1 to drop existing tables in MySQL before creating
// Run from project root: & 'C:\xampp\php\php.exe' scripts\migrate_sqlite_to_mysql.php

function mapSqliteTypeToMySQL(string $type, string $name = '', PDO $sqlite = null, string $table = ''): string
{
    $t = strtolower($type);
    if (preg_match('/int/', $t)) {
        return 'INT';
    }
    if (preg_match('/char\(|varchar\(/', $t)) {
        return strtoupper($t);
    }
    if (preg_match('/text|clob/', $t)) {
        // Si c'est une colonne indexée ou une clé primaire, utiliser VARCHAR(255)
        if ($sqlite && $table && $name) {
            $indexes = $sqlite->query("SELECT * FROM sqlite_master WHERE type='index' AND tbl_name='{$table}'")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($indexes as $idx) {
                if (strpos($idx['sql'], $name) !== false) {
                    return 'VARCHAR(255)';
                }
            }
        }
        return 'LONGTEXT';
    }
    if (preg_match('/blob/', $t)) {
        return 'LONGBLOB';
    }
    if (preg_match('/real|floa|doub/', $t)) {
        return 'DOUBLE';
    }
    if (preg_match('/numeric|decimal/', $t)) {
        return 'DECIMAL(16,4)';
    }
    // fallback
    return 'TEXT';
}

// Load MySQL credentials from environment
$mysqlHost = getenv('MYSQL_HOST') ?: getenv('DB_HOST') ?: '127.0.0.1';
$mysqlPort = getenv('MYSQL_PORT') ?: getenv('DB_PORT') ?: '3306';
$mysqlDb = getenv('MYSQL_DATABASE') ?: getenv('DB_DATABASE') ?: null;
$mysqlUser = getenv('MYSQL_USERNAME') ?: getenv('DB_USERNAME') ?: null;
$mysqlPass = getenv('MYSQL_PASSWORD') ?: getenv('DB_PASSWORD') ?: null;
$dropExisting = getenv('DROP_EXISTING');

if (!$mysqlDb || !$mysqlUser) {
    echo "Missing MySQL credentials. Set MYSQL_DATABASE, MYSQL_USERNAME and MYSQL_PASSWORD environment variables.\n";
    exit(1);
}

$projectRoot = dirname(__DIR__);
$sqliteFile = $projectRoot . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'database.sqlite';
if (!file_exists($sqliteFile)) {
    echo "SQLite file not found at: $sqliteFile\n";
    exit(1);
}

try {
    $sqlite = new PDO('sqlite:' . $sqliteFile);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mysqlDsn = "mysql:host={$mysqlHost};port={$mysqlPort};dbname={$mysqlDb};charset=utf8mb4";
    $mysql = new PDO($mysqlDsn, $mysqlUser, $mysqlPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ]);

    echo "Connected to SQLite ($sqliteFile) and MySQL ({$mysqlHost}:{$mysqlPort}/{$mysqlDb}).\n";

    // Get tables from SQLite
    $tablesStmt = $sqlite->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
    $tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);
    // Exclure les tables spéciales déjà créées
    $tables = array_diff($tables, ['cache', 'sessions', 'cache_locks']);

    if (empty($tables)) {
        echo "No tables found in SQLite DB.\n";
        exit(0);
    }

    if ($dropExisting) {
        echo "DROP_EXISTING set: dropping existing tables in MySQL (in alphabetical order).\n";
        foreach ($tables as $t) {
            $mysql->exec("SET FOREIGN_KEY_CHECKS=0");
            $mysql->exec("DROP TABLE IF EXISTS `{$t}`");
            $mysql->exec("SET FOREIGN_KEY_CHECKS=1");
            echo "Dropped table if existed: {$t}\n";
        }
    }

    $fkStatements = [];

    foreach ($tables as $table) {
        echo "Processing table: {$table}\n";
        $colsStmt = $sqlite->query("PRAGMA table_info('{$table}')");
        $cols = $colsStmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cols)) {
            echo " - skip (no columns).\n";
            continue;
        }

        $colDefs = [];
        $pkCols = [];
        foreach ($cols as $c) {
            $name = $c['name'];
            $type = $c['type'];
            $notnull = $c['notnull'] ? 'NOT NULL' : 'NULL';
            $dflt = $c['dflt_value'];
            $mapped = mapSqliteTypeToMySQL($type, $name, $sqlite, $table);

            $def = "`{$name}` {$mapped} {$notnull}";
            if ($dflt !== null) {
                // sqlite default values may be strings without quotes; preserve roughly
                $def .= " DEFAULT {
                }";
            }
            $colDefs[] = $def;
            if ($c['pk']) $pkCols[] = $name;
        }

        $pkSql = '';
        if (!empty($pkCols)) {
            // If single integer PK, add AUTO_INCREMENT
            if (count($pkCols) === 1) {
                $pk = $pkCols[0];
                // find its mapping
                foreach ($cols as $c) {
                    if ($c['name'] === $pk) {
                        if (preg_match('/int/i', $c['type'])) {
                            // replace the column definition to add AUTO_INCREMENT
                            foreach ($colDefs as $i => $def) {
                                if (strpos($def, "`{$pk}` ") === 0) {
                                    $colDefs[$i] = preg_replace('/NOT NULL|NULL/', 'NOT NULL AUTO_INCREMENT', $def, 1);
                                    break;
                                }
                            }
                        }
                        break;
                    }
                }
            }
            $pkSql = ', PRIMARY KEY(`' . implode('`,`', $pkCols) . '`)';
        }

        $create = "CREATE TABLE IF NOT EXISTS `{$table}` (\n    " . implode(",\n    ", $colDefs) . $pkSql . "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        echo " - creating table in MySQL...\n";
        $mysql->exec($create);

        // collect foreign keys to add later
        $fks = $sqlite->query("PRAGMA foreign_key_list('{$table}')")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($fks as $fk) {
            // fk: id,seq,table,from,to,on_update,on_delete,match
            $from = $fk['from'];
            $refTable = $fk['table'];
            $to = $fk['to'];
            $onUpdate = $fk['on_update'] ?: 'NO ACTION';
            $onDelete = $fk['on_delete'] ?: 'NO ACTION';
            $constraintName = "fk_{$table}_{$from}_{$refTable}_{$to}";
            $fkStatements[] = [
                'table' => $table,
                'sql' => "ALTER TABLE `{$table}` ADD CONSTRAINT `{$constraintName}` FOREIGN KEY (`{$from}`) REFERENCES `{$refTable}` (`{$to}`) ON UPDATE {$onUpdate} ON DELETE {$onDelete};",
            ];
        }

        // copy rows
        echo " - copying data... ";
        $rowsStmt = $sqlite->query("SELECT * FROM `{$table}`");
        $rows = $rowsStmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($rows);
        if ($count === 0) {
            echo "0 rows.\n";
            continue;
        }

        $mysql->beginTransaction();
        try {
            $columns = array_keys($rows[0]);
            $placeholders = implode(',', array_fill(0, count($columns), '?'));
            $colList = implode('`,`', $columns);
            $insertSql = "INSERT INTO `{$table}` (`{$colList}`) VALUES ({$placeholders})";
            $insertStmt = $mysql->prepare($insertSql);
            foreach ($rows as $r) {
                $vals = array_values($r);
                $insertStmt->execute($vals);
            }
            $mysql->commit();
            echo "{$count} rows copied.\n";
        } catch (Exception $e) {
            $mysql->rollBack();
            echo " Error copying rows: " . $e->getMessage() . "\n";
        }
    }

    // add foreign keys
    if (!empty($fkStatements)) {
        echo "Adding foreign key constraints...\n";
        foreach ($fkStatements as $fk) {
            try {
                $mysql->exec($fk['sql']);
                echo " - added FK on {$fk['table']}\n";
            } catch (Exception $e) {
                echo " - failed to add FK on {$fk['table']}: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "Migration completed. Please update your `.env` with MySQL credentials and clear config cache (php artisan config:clear).\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
