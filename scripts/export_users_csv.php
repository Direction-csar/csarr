<?php
$pdo = new PDO('sqlite:'.__DIR__.'/../database/database.sqlite');
$sql = "SELECT u.id, u.name, u.email, u.phone, r.name as role, u.created_at, u.email_verified_at FROM users u LEFT JOIN roles r ON u.role_id = r.id ORDER BY u.id";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$fp = fopen(__DIR__.'/../storage/users.csv','w');
fputcsv($fp, array_keys($rows[0]));
foreach($rows as $r) fputcsv($fp, $r);
fclose($fp);
echo "Wrote ".count($rows)." users to storage/users.csv\n";
