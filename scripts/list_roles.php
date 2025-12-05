<?php
$pdo = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
$stmt = $pdo->query('SELECT id, name, slug FROM roles');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
