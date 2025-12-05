<?php

/**
 * Script de nettoyage des données fictives
 */

require_once "vendor/autoload.php";

// Initialiser Laravel
$app = require_once "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;
use App\Models\NewsletterSubscriber;
use App\Models\ContactMessage;
use App\Models\PublicRequest;

echo "🧹 Nettoyage des données fictives...\n";

try {
    // Supprimer les données de test
    echo "Suppression des messages de test...\n";
    Message::where("sujet", "like", "%test%")->delete();
    Message::where("sujet", "like", "%Test%")->delete();
    Message::where("expediteur", "like", "%test%")->delete();
    
    echo "Suppression des notifications de test...\n";
    Notification::where("title", "like", "%test%")->delete();
    Notification::where("title", "like", "%Test%")->delete();
    
    echo "Suppression des contacts de test...\n";
    ContactMessage::where("email", "like", "%test%")->delete();
    ContactMessage::where("email", "like", "%@example.com")->delete();
    
    echo "Suppression des demandes de test...\n";
    PublicRequest::where("email", "like", "%test%")->delete();
    PublicRequest::where("email", "like", "%@example.com")->delete();
    
    echo "Suppression des abonnés de test...\n";
    NewsletterSubscriber::where("email", "like", "%test%")->delete();
    NewsletterSubscriber::where("email", "like", "%@example.com")->delete();
    
    echo "✅ Nettoyage terminé avec succès\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors du nettoyage: " . $e->getMessage() . "\n";
}
