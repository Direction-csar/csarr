<?php
$pdo = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
$stmt = $pdo->query("PRAGMA table_info('roles')");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
