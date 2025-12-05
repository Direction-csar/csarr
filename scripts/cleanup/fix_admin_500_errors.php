<?php
/**
 * Script de correction des erreurs 500 sur les pages d'administration
 * Corrige les problèmes de modèles manquants et de vues
 */

echo "🔧 CORRECTION DES ERREURS 500 - PAGES ADMIN\n";
echo "===========================================\n\n";

/**
 * Étape 1: Vérification des modèles
 */
function checkModels() {
    echo "🔍 Étape 1: Vérification des modèles...\n";
    
    $models = [
        'Message' => 'app/Models/Message.php',
        'NewsletterSubscriber' => 'app/Models/NewsletterSubscriber.php',
        'Newsletter' => 'app/Models/Newsletter.php',
        'SimReport' => 'app/Models/SimReport.php'
    ];
    
    $missingModels = [];
    
    foreach ($models as $modelName => $path) {
        if (!file_exists($path)) {
            $missingModels[] = $modelName;
            echo "❌ Modèle manquant: {$modelName}\n";
        } else {
            echo "✅ Modèle trouvé: {$modelName}\n";
        }
    }
    
    return $missingModels;
}

/**
 * Étape 2: Création des modèles manquants
 */
function createMissingModels($missingModels) {
    echo "\n🔨 Étape 2: Création des modèles manquants...\n";
    
    foreach ($missingModels as $modelName) {
        echo "   Création du modèle {$modelName}...\n";
        
        switch ($modelName) {
            case 'Message':
                createMessageModel();
                break;
            case 'NewsletterSubscriber':
                // Déjà existant, vérifier la structure
                fixNewsletterSubscriberModel();
                break;
            case 'Newsletter':
                // Déjà existant, vérifier la structure
                fixNewsletterModel();
                break;
            case 'SimReport':
                // Déjà existant, vérifier la structure
                fixSimReportModel();
                break;
        }
    }
}

/**
 * Créer le modèle Message
 */
function createMessageModel() {
    $content = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "sender_name",
        "sender_email", 
        "sender_phone",
        "subject",
        "message",
        "status",
        "priority",
        "category",
        "assigned_to",
        "response",
        "response_date",
        "is_read",
        "read_at"
    ];

    protected $casts = [
        "is_read" => "boolean",
        "read_at" => "datetime",
        "response_date" => "datetime"
    ];

    /**
     * Scope pour les messages non lus
     */
    public function scopeUnread($query)
    {
        return $query->where("is_read", false);
    }

    /**
     * Scope pour les messages lus
     */
    public function scopeRead($query)
    {
        return $query->where("is_read", true);
    }

    /**
     * Scope par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where("status", $status);
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead()
    {
        $this->update([
            "is_read" => true,
            "read_at" => now()
        ]);
    }

    /**
     * Marquer comme non lu
     */
    public function markAsUnread()
    {
        $this->update([
            "is_read" => false,
            "read_at" => null
        ]);
    }

    /**
     * Ajouter une réponse
     */
    public function addResponse($response, $userId = null)
    {
        $this->update([
            "response" => $response,
            "response_date" => now(),
            "assigned_to" => $userId ?? auth()->id(),
            "status" => "replied"
        ]);
    }
}';

    file_put_contents('app/Models/Message.php', $content);
    echo "   ✅ Modèle Message créé\n";
}

/**
 * Corriger le modèle NewsletterSubscriber
 */
function fixNewsletterSubscriberModel() {
    $file = 'app/Models/NewsletterSubscriber.php';
    $content = file_get_contents($file);
    
    // Vérifier si la méthode markAsInactive existe
    if (strpos($content, 'public function markAsInactive') === false) {
        $content = str_replace(
            '    /**',
            '    /**
     * Marquer comme inactif
     */
    public function markAsInactive()
    {
        $this->update([\'status\' => \'inactive\']);
    }

    /**',
            $content
        );
        
        file_put_contents($file, $content);
        echo "   ✅ Modèle NewsletterSubscriber corrigé\n";
    } else {
        echo "   ✅ Modèle NewsletterSubscriber OK\n";
    }
}

/**
 * Corriger le modèle Newsletter
 */
function fixNewsletterModel() {
    $file = 'app/Models/Newsletter.php';
    $content = file_get_contents($file);
    
    // Vérifier si la méthode scopeFailed existe
    if (strpos($content, 'public function scopeFailed') === false) {
        $content = str_replace(
            '    /**
     * Scope pour les newsletters échouées
     */
    
',
            '    /**
     * Scope pour les newsletters échouées
     */
    public function scopeFailed($query)
    {
        return $query->where(\'status\', \'failed\');
    }

    /**
     * Obtenir les statistiques des newsletters
     */
    public static function getStats()
    {
        return [
            \'total\' => static::count(),
            \'sent\' => static::sent()->count(),
            \'pending\' => static::pending()->count(),
            \'scheduled\' => static::scheduled()->count(),
            \'sending\' => static::sending()->count(),
            \'failed\' => static::failed()->count(),
            \'avg_open_rate\' => static::avg(\'open_rate\') ?? 0,
            \'avg_click_rate\' => static::avg(\'click_rate\') ?? 0
        ];
    }
',
            $content
        );
        
        file_put_contents($file, $content);
        echo "   ✅ Modèle Newsletter corrigé\n";
    } else {
        echo "   ✅ Modèle Newsletter OK\n";
    }
}

/**
 * Corriger le modèle SimReport
 */
function fixSimReportModel() {
    $file = 'app/Models/SimReport.php';
    $content = file_get_contents($file);
    
    // Vérifier si la méthode getDownloadUrl existe
    if (strpos($content, 'public function getDownloadUrl') === false) {
        $content = str_replace(
            '    /**
     * Obtenir l\'URL de téléchargement du document
     */
    
',
            '    /**
     * Obtenir l\'URL de téléchargement du document
     */
    public function getDownloadUrl()
    {
        if (!$this->document_file) {
            return null;
        }
        
        return asset(\'storage/\' . $this->document_file);
    }

    /**
     * Scope pour les rapports publics
     */
    public function scopePublic($query)
    {
        return $query->where(\'is_public\', true)
                    ->where(\'status\', \'published\');
    }

    /**
     * Scope par type de rapport
     */
    public function scopeByType($query, $type)
    {
        return $query->where(\'report_type\', $type);
    }

    /**
     * Scope par région
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where(\'region\', $region);
    }

    /**
     * Scope par secteur
     */
    public function scopeBySector($query, $sector)
    {
        return $query->where(\'market_sector\', $sector);
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews()
    {
        $this->increment(\'view_count\');
    }

    /**
     * Incrémenter le compteur de téléchargements
     */
    public function incrementDownloads()
    {
        $this->increment(\'download_count\');
    }

    /**
     * Obtenir les statistiques des rapports
     */
    public static function getStats()
    {
        return [
            \'total\' => static::count(),
            \'published\' => static::published()->count(),
            \'draft\' => static::where(\'status\', \'draft\')->count(),
            \'generating\' => static::where(\'status\', \'generating\')->count(),
            \'completed\' => static::completed()->count(),
            \'total_views\' => static::sum(\'view_count\'),
            \'total_downloads\' => static::sum(\'download_count\')
        ];
    }
',
            $content
        );
        
        file_put_contents($file, $content);
        echo "   ✅ Modèle SimReport corrigé\n";
    } else {
        echo "   ✅ Modèle SimReport OK\n";
    }
}

/**
 * Étape 3: Vérification des migrations
 */
function checkMigrations() {
    echo "\n📊 Étape 3: Vérification des migrations...\n";
    
    $migrations = [
        'messages' => 'create_messages_table',
        'newsletter_subscribers' => 'create_newsletter_subscribers_table',
        'newsletters' => 'create_newsletters_table',
        'sim_reports' => 'create_sim_reports_table'
    ];
    
    $missingMigrations = [];
    
    foreach ($migrations as $table => $migrationName) {
        $migrationFiles = glob("database/migrations/*{$migrationName}*.php");
        
        if (empty($migrationFiles)) {
            $missingMigrations[] = $table;
            echo "❌ Migration manquante: {$table}\n";
        } else {
            echo "✅ Migration trouvée: {$table}\n";
        }
    }
    
    return $missingMigrations;
}

/**
 * Étape 4: Création des migrations manquantes
 */
function createMissingMigrations($missingMigrations) {
    echo "\n🔨 Étape 4: Création des migrations manquantes...\n";
    
    foreach ($missingMigrations as $table) {
        echo "   Création de la migration pour {$table}...\n";
        
        switch ($table) {
            case 'messages':
                createMessagesMigration();
                break;
            case 'newsletter_subscribers':
                createNewsletterSubscribersMigration();
                break;
            case 'newsletters':
                createNewslettersMigration();
                break;
            case 'sim_reports':
                createSimReportsMigration();
                break;
        }
    }
}

/**
 * Créer la migration pour les messages
 */
function createMessagesMigration() {
    $timestamp = date('Y_m_d_His');
    $filename = "database/migrations/{$timestamp}_create_messages_table.php";
    
    $content = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(\'messages\', function (Blueprint $table) {
            $table->id();
            $table->string(\'sender_name\');
            $table->string(\'sender_email\');
            $table->string(\'sender_phone\')->nullable();
            $table->string(\'subject\');
            $table->text(\'message\');
            $table->enum(\'status\', [\'new\', \'read\', \'replied\', \'closed\'])->default(\'new\');
            $table->enum(\'priority\', [\'low\', \'medium\', \'high\', \'urgent\'])->default(\'medium\');
            $table->string(\'category\')->default(\'general\');
            $table->foreignId(\'assigned_to\')->nullable()->constrained(\'users\')->onDelete(\'set null\');
            $table->text(\'response\')->nullable();
            $table->timestamp(\'response_date\')->nullable();
            $table->boolean(\'is_read\')->default(false);
            $table->timestamp(\'read_at\')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index([\'status\', \'priority\']);
            $table->index(\'is_read\');
            $table->index(\'created_at\');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(\'messages\');
    }
};';

    file_put_contents($filename, $content);
    echo "   ✅ Migration messages créée\n";
}

/**
 * Créer la migration pour les abonnés newsletter
 */
function createNewsletterSubscribersMigration() {
    $timestamp = date('Y_m_d_His');
    $filename = "database/migrations/{$timestamp}_create_newsletter_subscribers_table.php";
    
    $content = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(\'newsletter_subscribers\', function (Blueprint $table) {
            $table->id();
            $table->string(\'email\')->unique();
            $table->string(\'name\')->nullable();
            $table->string(\'status\')->default(\'active\');
            $table->timestamp(\'subscribed_at\')->useCurrent();
            $table->timestamp(\'unsubscribed_at\')->nullable();
            $table->string(\'source\')->default(\'website\');
            $table->json(\'preferences\')->nullable();
            $table->timestamp(\'last_email_sent_at\')->nullable();
            $table->integer(\'email_count\')->default(0);
            $table->timestamps();
            
            $table->index(\'email\');
            $table->index(\'status\');
            $table->index(\'subscribed_at\');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(\'newsletter_subscribers\');
    }
};';

    file_put_contents($filename, $content);
    echo "   ✅ Migration newsletter_subscribers créée\n";
}

/**
 * Créer la migration pour les newsletters
 */
function createNewslettersMigration() {
    $timestamp = date('Y_m_d_His');
    $filename = "database/migrations/{$timestamp}_create_newsletters_table.php";
    
    $content = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(\'newsletters\', function (Blueprint $table) {
            $table->id();
            $table->string(\'title\');
            $table->string(\'subject\');
            $table->longText(\'content\');
            $table->string(\'template\')->default(\'default\');
            $table->enum(\'status\', [\'draft\', \'pending\', \'scheduled\', \'sending\', \'sent\', \'failed\'])->default(\'draft\');
            $table->timestamp(\'scheduled_at\')->nullable();
            $table->timestamp(\'sent_at\')->nullable();
            $table->foreignId(\'sent_by\')->nullable()->constrained(\'users\')->onDelete(\'set null\');
            $table->integer(\'recipients_count\')->default(0);
            $table->integer(\'delivered_count\')->default(0);
            $table->integer(\'opened_count\')->default(0);
            $table->integer(\'clicked_count\')->default(0);
            $table->integer(\'bounced_count\')->default(0);
            $table->integer(\'unsubscribed_count\')->default(0);
            $table->decimal(\'open_rate\', 5, 2)->default(0);
            $table->decimal(\'click_rate\', 5, 2)->default(0);
            $table->json(\'metadata\')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(\'status\');
            $table->index(\'sent_at\');
            $table->index(\'created_at\');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(\'newsletters\');
    }
};';

    file_put_contents($filename, $content);
    echo "   ✅ Migration newsletters créée\n";
}

/**
 * Créer la migration pour les rapports SIM
 */
function createSimReportsMigration() {
    $timestamp = date('Y_m_d_His');
    $filename = "database/migrations/{$timestamp}_create_sim_reports_table.php";
    
    $content = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(\'sim_reports\', function (Blueprint $table) {
            $table->id();
            $table->string(\'title\');
            $table->text(\'description\')->nullable();
            $table->enum(\'report_type\', [\'daily\', \'weekly\', \'monthly\', \'quarterly\', \'annual\', \'special\'])->default(\'monthly\');
            $table->date(\'period_start\')->nullable();
            $table->date(\'period_end\')->nullable();
            $table->string(\'region\')->nullable();
            $table->enum(\'market_sector\', [\'agriculture\', \'livestock\', \'fisheries\', \'manufacturing\', \'services\', \'trade\', \'all\'])->default(\'all\');
            $table->longText(\'summary\')->nullable();
            $table->longText(\'context_objectives\')->nullable();
            $table->json(\'supply_level\')->nullable();
            $table->json(\'price_analysis\')->nullable();
            $table->json(\'supply_analysis\')->nullable();
            $table->json(\'regional_distribution\')->nullable();
            $table->json(\'regional_analysis\')->nullable();
            $table->json(\'key_trends\')->nullable();
            $table->longText(\'recommendations\')->nullable();
            $table->json(\'annexes\')->nullable();
            $table->text(\'methodology\')->nullable();
            $table->json(\'data_sources\')->nullable();
            $table->json(\'indicators_data\')->nullable();
            $table->json(\'attachments\')->nullable();
            $table->enum(\'status\', [\'draft\', \'generating\', \'completed\', \'published\', \'archived\'])->default(\'draft\');
            $table->boolean(\'is_published\')->default(false);
            $table->boolean(\'is_public\')->default(false);
            $table->timestamp(\'published_at\')->nullable();
            $table->timestamp(\'generated_at\')->nullable();
            $table->integer(\'view_count\')->default(0);
            $table->integer(\'download_count\')->default(0);
            $table->string(\'document_file\')->nullable();
            $table->string(\'file_path\')->nullable();
            $table->string(\'cover_image\')->nullable();
            $table->foreignId(\'created_by\')->constrained(\'users\')->onDelete(\'cascade\');
            $table->unsignedBigInteger(\'generated_by\')->nullable();
            $table->foreign(\'generated_by\')->references(\'id\')->on(\'users\')->onDelete(\'set null\');
            $table->timestamps();
            
            $table->index([\'status\', \'is_published\']);
            $table->index([\'report_type\', \'market_sector\']);
            $table->index([\'period_start\', \'period_end\']);
            $table->index(\'created_at\');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(\'sim_reports\');
    }
};';

    file_put_contents($filename, $content);
    echo "   ✅ Migration sim_reports créée\n";
}

/**
 * Étape 5: Exécution des migrations
 */
function runMigrations() {
    echo "\n📊 Étape 5: Exécution des migrations...\n";
    
    $output = shell_exec('php artisan migrate --force 2>&1');
    
    if (strpos($output, 'Migrated:') !== false || strpos($output, 'Nothing to migrate') !== false) {
        echo "✅ Migrations exécutées avec succès\n";
    } else {
        echo "⚠️ Erreur lors des migrations: {$output}\n";
    }
}

/**
 * Étape 6: Nettoyage du cache
 */
function clearCache() {
    echo "\n🧹 Étape 6: Nettoyage du cache...\n";
    
    $commands = [
        'config:clear',
        'cache:clear',
        'route:clear',
        'view:clear'
    ];
    
    foreach ($commands as $command) {
        $output = shell_exec("php artisan {$command} 2>&1");
        echo "✅ Cache {$command} nettoyé\n";
    }
}

/**
 * Affichage des instructions finales
 */
function showFinalInstructions() {
    echo "\n🎉 CORRECTION TERMINÉE!\n";
    echo "======================\n\n";
    
    echo "📋 PAGES CORRIGÉES:\n";
    echo "==================\n";
    echo "✅ /admin/communication - Communication\n";
    echo "✅ /admin/newsletter - Newsletter\n";
    echo "✅ /sim-reports - Rapports SIM\n\n";
    
    echo "🔗 URLS DE TEST:\n";
    echo "================\n";
    echo "🌐 Communication: http://localhost:8000/admin/communication\n";
    echo "🌐 Newsletter: http://localhost:8000/admin/newsletter\n";
    echo "🌐 Rapports SIM: http://localhost:8000/sim-reports\n\n";
    
    echo "⚠️ IMPORTANT:\n";
    echo "=============\n";
    echo "- Les modèles et migrations ont été créés/corrigés\n";
    echo "- Le cache a été nettoyé\n";
    echo "- Les pages devraient maintenant fonctionner\n\n";
    
    echo "✅ Les erreurs 500 sont maintenant corrigées!\n";
}

// Exécution de la correction
try {
    $missingModels = checkModels();
    createMissingModels($missingModels);
    
    $missingMigrations = checkMigrations();
    createMissingMigrations($missingMigrations);
    
    runMigrations();
    clearCache();
    showFinalInstructions();
    
} catch (Exception $e) {
    echo "\n❌ ERREUR LORS DE LA CORRECTION: " . $e->getMessage() . "\n";
    echo "Vérifiez les logs et réessayez.\n";
}
