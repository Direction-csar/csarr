<?php
$pdo = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
$stmt = $pdo->query('SELECT id, name, email, role_id, phone FROM users');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
