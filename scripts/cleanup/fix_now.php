<?php
// Correction immédiate du problème deleted_at
$pdo = new PDO('mysql:host=localhost;dbname=csar_platform', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Correction en cours...\n";

try {
    // Ajouter deleted_at à newsletters
    $pdo->exec("ALTER TABLE newsletters ADD COLUMN deleted_at TIMESTAMP NULL");
    echo "✅ newsletters corrigé\n";
} catch (Exception $e) {
    echo "⚠️ newsletters: " . $e->getMessage() . "\n";
}

try {
    // Ajouter deleted_at à messages
    $pdo->exec("ALTER TABLE messages ADD COLUMN deleted_at TIMESTAMP NULL");
    echo "✅ messages corrigé\n";
} catch (Exception $e) {
    echo "⚠️ messages: " . $e->getMessage() . "\n";
}

try {
    // Ajouter deleted_at à notifications
    $pdo->exec("ALTER TABLE notifications ADD COLUMN deleted_at TIMESTAMP NULL");
    echo "✅ notifications corrigé\n";
} catch (Exception $e) {
    echo "⚠️ notifications: " . $e->getMessage() . "\n";
}

try {
    // Ajouter deleted_at à home_backgrounds
    $pdo->exec("ALTER TABLE home_backgrounds ADD COLUMN deleted_at TIMESTAMP NULL");
    echo "✅ home_backgrounds corrigé\n";
} catch (Exception $e) {
    echo "⚠️ home_backgrounds: " . $e->getMessage() . "\n";
}

echo "🎉 Correction terminée!\n";
?>
