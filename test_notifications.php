<?php

/**
 * Script de test pour le système de notifications CSAR
 * Ce script crée des notifications de test pour chaque type
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Notification;
use App\Models\Demande;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\News;

echo "🔔 Test du système de notifications CSAR\n";
echo "==========================================\n\n";

try {
    // Test 1 : Notification de demande
    echo "📄 Test 1 : Notification de demande d'aide...\n";
    $notification1 = Notification::createNotification(
        'Nouvelle demande d\'aide',
        'Une nouvelle demande d\'aide alimentaire a été soumise par Jean Dupont pour la région de Dakar. Urgence : Élevée.',
        'demande',
        [
            'demandeur' => 'Jean Dupont',
            'region' => 'Dakar',
            'type' => 'Aide alimentaire',
            'urgence' => 'élevée'
        ],
        null,
        'file-text',
        '/admin/demandes'
    );
    echo "✅ Notification de demande créée (ID: {$notification1->id})\n\n";

    // Test 2 : Notification de message de contact
    echo "✉️ Test 2 : Notification de message de contact...\n";
    $notification2 = Notification::createNotification(
        'Nouveau message de contact',
        'Nouveau message de contact reçu de Marie Sow (marie.sow@example.sn) concernant : Demande d\'information sur les programmes.',
        'message',
        [
            'expediteur' => 'Marie Sow',
            'email' => 'marie.sow@example.sn',
            'sujet' => 'Demande d\'information sur les programmes'
        ],
        null,
        'mail',
        '/admin/messages'
    );
    echo "✅ Notification de message créée (ID: {$notification2->id})\n\n";

    // Test 3 : Notification d'inscription newsletter
    echo "📧 Test 3 : Notification d'inscription newsletter...\n";
    $notification3 = Notification::createNotification(
        'Nouvelle inscription à la newsletter',
        'Nouvelle inscription à la newsletter : abdou.diallo@example.sn',
        'newsletter',
        [
            'email' => 'abdou.diallo@example.sn',
            'date' => now()->format('Y-m-d H:i:s')
        ],
        null,
        'send',
        '/admin/newsletter/subscribers'
    );
    echo "✅ Notification d'inscription newsletter créée (ID: {$notification3->id})\n\n";

    // Test 4 : Notification de communication officielle
    echo "📢 Test 4 : Notification de communication officielle...\n";
    $notification4 = Notification::createNotification(
        'Nouvelle communication officielle',
        'Une nouvelle communication officielle a été publiée : Lancement du programme de sécurité alimentaire 2025',
        'communication',
        [
            'titre' => 'Lancement du programme de sécurité alimentaire 2025',
            'categorie' => 'Actualité',
            'auteur' => 'Direction CSAR'
        ],
        null,
        'megaphone',
        '/admin/actualites'
    );
    echo "✅ Notification de communication créée (ID: {$notification4->id})\n\n";

    // Test 5 : Notification de succès
    echo "✅ Test 5 : Notification de succès...\n";
    $notification5 = Notification::createNotification(
        'Opération réussie',
        'La distribution de l\'aide alimentaire à Thiès a été réalisée avec succès. 150 familles bénéficiaires.',
        'success',
        [
            'operation' => 'Distribution aide alimentaire',
            'lieu' => 'Thiès',
            'beneficiaires' => 150
        ],
        null,
        'check-circle',
        '/admin/distributions'
    );
    echo "✅ Notification de succès créée (ID: {$notification5->id})\n\n";

    // Test 6 : Notification d'avertissement
    echo "⚠️ Test 6 : Notification d'avertissement...\n";
    $notification6 = Notification::createNotification(
        'Stock faible détecté',
        'Attention : Le stock de riz dans l\'entrepôt de Saint-Louis est inférieur au seuil minimum (20 kg restants).',
        'warning',
        [
            'produit' => 'Riz',
            'entrepot' => 'Saint-Louis',
            'quantite_restante' => '20 kg',
            'seuil_minimum' => '50 kg'
        ],
        null,
        'alert-triangle',
        '/admin/stocks'
    );
    echo "✅ Notification d'avertissement créée (ID: {$notification6->id})\n\n";

    // Test 7 : Notification d'information
    echo "ℹ️ Test 7 : Notification d'information...\n";
    $notification7 = Notification::createNotification(
        'Rappel : Réunion mensuelle',
        'Rappel : La réunion mensuelle de coordination aura lieu demain à 10h00 dans la salle de conférence.',
        'info',
        [
            'type_evenement' => 'Réunion mensuelle',
            'date' => now()->addDay()->format('Y-m-d'),
            'heure' => '10:00',
            'lieu' => 'Salle de conférence'
        ],
        null,
        'info',
        '/admin/agenda'
    );
    echo "✅ Notification d'information créée (ID: {$notification7->id})\n\n";

    // Statistiques finales
    echo "📊 Statistiques des notifications\n";
    echo "==================================\n";
    $stats = Notification::getStats();
    echo "Total de notifications : {$stats['total']}\n";
    echo "Notifications non lues : {$stats['unread']}\n";
    echo "Notifications lues : {$stats['read']}\n";
    echo "Nouvelles aujourd'hui : {$stats['new_today']}\n";
    echo "Notifications de type 'demande' : {$stats['demande']}\n";
    echo "Notifications de type 'message' : {$stats['message']}\n";
    echo "Notifications de type 'newsletter' : {$stats['newsletter']}\n";
    echo "Notifications de type 'communication' : {$stats['communication']}\n";
    echo "Notifications de type 'success' : {$stats['success']}\n";
    echo "Notifications de type 'warning' : {$stats['warning']}\n";
    echo "Notifications de type 'info' : {$stats['info']}\n\n";

    echo "🎉 Test terminé avec succès !\n";
    echo "===============================\n\n";
    echo "👉 Allez sur votre tableau de bord admin pour voir les notifications.\n";
    echo "👉 URL : /admin/dashboard\n";
    echo "👉 Centre de notifications : /admin/notifications\n\n";

} catch (\Exception $e) {
    echo "❌ Erreur lors du test : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}

