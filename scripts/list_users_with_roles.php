<?php
$pdo = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
$sql = 'SELECT u.id, u.name, u.email, u.phone, u.role_id, r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id ORDER BY u.id';
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
