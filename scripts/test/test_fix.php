<?php
// Test rapide pour vérifier que les modèles fonctionnent
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Test des modèles après correction...\n\n";

try {
    // Test Newsletter
    $newsletterCount = \App\Models\Newsletter::count();
    echo "✅ Newsletter::count() = $newsletterCount\n";
} catch (Exception $e) {
    echo "❌ Newsletter: " . $e->getMessage() . "\n";
}

try {
    // Test Message
    $messageCount = \App\Models\Message::count();
    echo "✅ Message::count() = $messageCount\n";
} catch (Exception $e) {
    echo "❌ Message: " . $e->getMessage() . "\n";
}

try {
    // Test Notification
    $notificationCount = \App\Models\Notification::count();
    echo "✅ Notification::count() = $notificationCount\n";
} catch (Exception $e) {
    echo "❌ Notification: " . $e->getMessage() . "\n";
}

try {
    // Test HomeBackground
    $backgroundCount = \App\Models\HomeBackground::count();
    echo "✅ HomeBackground::count() = $backgroundCount\n";
} catch (Exception $e) {
    echo "❌ HomeBackground: " . $e->getMessage() . "\n";
}

echo "\n🎉 Test terminé!\n";
echo "Si tous les modèles affichent ✅, le problème est résolu.\n";
?>
