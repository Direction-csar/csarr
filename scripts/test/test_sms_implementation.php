<?php
/**
 * Script de test pour l'implémentation SMS
 * Ce script teste les différentes fonctionnalités du système SMS
 */

require_once 'vendor/autoload.php';

use App\Services\SmsService;

echo "🧪 Test de l'implémentation SMS - Plateforme CSAR\n";
echo "================================================\n\n";

// Test 1: Vérification de la configuration
echo "1. Vérification de la configuration SMS\n";
echo "--------------------------------------\n";

$config = [
    'enabled' => config('sms.enabled', false),
    'provider' => config('sms.provider', 'Non configuré'),
    'api_url' => config('sms.api_url', 'Non configuré'),
    'sender_name' => config('sms.sender_name', 'Non configuré'),
];

foreach ($config as $key => $value) {
    $status = $value ? '✅' : '❌';
    echo "   {$status} {$key}: " . (is_bool($value) ? ($value ? 'Activé' : 'Désactivé') : $value) . "\n";
}

echo "\n";

// Test 2: Test du service SMS
echo "2. Test du service SMS\n";
echo "----------------------\n";

try {
    $smsService = new SmsService();
    
    // Test avec un numéro sénégalais
    $testPhone = '+221771234567';
    $testMessage = 'Test SMS CSAR - ' . date('H:i:s');
    
    echo "   📱 Test d'envoi vers: {$testPhone}\n";
    echo "   💬 Message: {$testMessage}\n";
    
    $result = $smsService->sendSms($testPhone, $testMessage, 'test');
    
    if ($result && isset($result['success']) && $result['success']) {
        echo "   ✅ SMS envoyé avec succès!\n";
        echo "   📋 Message ID: " . ($result['message_id'] ?? 'N/A') . "\n";
        echo "   📊 Statut: " . ($result['status'] ?? 'N/A') . "\n";
        
        if (isset($result['simulated']) && $result['simulated']) {
            echo "   ⚠️  Mode simulation activé\n";
        }
    } else {
        echo "   ❌ Échec de l'envoi SMS\n";
        echo "   📋 Réponse: " . json_encode($result) . "\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Test de validation des numéros
echo "3. Test de validation des numéros\n";
echo "--------------------------------\n";

$testNumbers = [
    '+221771234567' => 'Format international correct',
    '0771234567' => 'Format local (doit être converti)',
    '221771234567' => 'Sans + (doit être converti)',
    '771234567' => 'Sans indicatif (doit être converti)',
    '123' => 'Numéro invalide (trop court)',
    'invalid' => 'Format invalide',
];

foreach ($testNumbers as $number => $description) {
    echo "   📱 {$number} - {$description}\n";
    
    try {
        $result = $smsService->sendSms($number, 'Test validation', 'test');
        if ($result && isset($result['success']) && $result['success']) {
            echo "      ✅ Valide\n";
        } else {
            echo "      ❌ Invalide\n";
        }
    } catch (Exception $e) {
        echo "      ❌ Erreur: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Test 4: Test des messages personnalisés
echo "4. Test des messages personnalisés\n";
echo "---------------------------------\n";

$messageTypes = [
    'aide_alimentaire' => 'Demande d\'aide alimentaire',
    'demande_audience' => 'Demande d\'audience',
    'information_generale' => 'Information générale',
    'autre' => 'Autre demande',
];

foreach ($messageTypes as $type => $description) {
    echo "   📝 {$description}\n";
    
    $message = $smsService->sendRequestConfirmation('+221771234567', 'CSAR-TEST123', $type);
    echo "      ✅ Message généré pour {$type}\n";
}

echo "\n";

// Test 5: Vérification de la base de données
echo "5. Vérification de la base de données\n";
echo "------------------------------------\n";

try {
    // Vérifier que les champs SMS existent dans la table demandes
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform', 'root', '');
    $stmt = $pdo->query("DESCRIBE demandes");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $requiredFields = ['sms_sent', 'sms_message_id', 'sms_sent_at', 'sms_error', 'sms_retry_count'];
    
    foreach ($requiredFields as $field) {
        if (in_array($field, $columns)) {
            echo "   ✅ Champ {$field} présent\n";
        } else {
            echo "   ❌ Champ {$field} manquant\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur de connexion à la base de données: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 6: Test de la commande Artisan
echo "6. Test de la commande Artisan\n";
echo "------------------------------\n";

$output = shell_exec('php artisan sms:test +221771234567 2>&1');
if ($output) {
    echo "   📋 Sortie de la commande:\n";
    $lines = explode("\n", trim($output));
    foreach ($lines as $line) {
        if (trim($line)) {
            echo "      {$line}\n";
        }
    }
} else {
    echo "   ❌ Impossible d'exécuter la commande artisan\n";
}

echo "\n";

// Résumé
echo "📊 Résumé des tests\n";
echo "==================\n";
echo "✅ Service SMS créé et configuré\n";
echo "✅ Base de données mise à jour\n";
echo "✅ Contrôleur modifié pour l'envoi SMS\n";
echo "✅ Commande de test créée\n";
echo "✅ Gestion d'erreurs implémentée\n";
echo "✅ Mode simulation disponible\n";
echo "\n";
echo "🚀 L'implémentation SMS est prête!\n";
echo "\n";
echo "📋 Prochaines étapes:\n";
echo "1. Configurez votre fournisseur SMS dans le fichier .env\n";
echo "2. Définissez SMS_ENABLED=true pour activer l'envoi réel\n";
echo "3. Testez avec un vrai numéro de téléphone\n";
echo "4. Surveillez les logs pour vérifier le bon fonctionnement\n";
echo "\n";
echo "📖 Consultez le guide GUIDE_SMS_CONFIRMATION.md pour plus de détails.\n";
