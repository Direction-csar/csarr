<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Création du système de demandes unifié...\n\n";

try {
    // 1. Vérifier et créer la table demandes unifiée
    echo "📋 Création de la table demandes unifiée...\n";
    
    if (!Schema::hasTable('demandes_unifiees')) {
        Schema::create('demandes_unifiees', function ($table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email');
            $table->string('telephone');
            $table->string('type_demande');
            $table->text('objet');
            $table->text('description');
            $table->string('adresse');
            $table->string('region');
            $table->enum('urgence', ['faible', 'moyenne', 'haute']);
            $table->enum('statut', ['en_attente', 'en_cours', 'approuvee', 'rejetee', 'terminee']);
            $table->text('commentaire_admin')->nullable();
            $table->unsignedBigInteger('traite_par')->nullable();
            $table->timestamp('date_traitement')->nullable();
            $table->string('pj')->nullable();
            $table->boolean('consentement')->default(false);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->foreign('traite_par')->references('id')->on('users')->onDelete('set null');
            $table->index(['statut', 'type_demande']);
            $table->index('tracking_code');
        });
        echo "✅ Table 'demandes_unifiees' créée\n";
    } else {
        echo "✅ Table 'demandes_unifiees' existe déjà\n";
    }

    // 2. Migrer les données existantes
    echo "\n📦 Migration des données existantes...\n";
    
    // Vider la table unifiée
    DB::table('demandes_unifiees')->truncate();
    
    // Migrer depuis public_requests
    $publicRequests = DB::table('public_requests')->get();
    $migrated = 0;
    
    foreach ($publicRequests as $request) {
        DB::table('demandes_unifiees')->insert([
            'tracking_code' => $request->tracking_code ?? 'CSAR-' . strtoupper(uniqid()),
            'nom' => explode(' ', $request->full_name ?? $request->name ?? 'N/A')[0] ?? 'N/A',
            'prenom' => explode(' ', $request->full_name ?? $request->name ?? 'N/A')[1] ?? '',
            'email' => $request->email ?? 'N/A',
            'telephone' => $request->phone ?? 'N/A',
            'type_demande' => $request->type ?? 'aide_alimentaire',
            'objet' => $request->subject ?? 'Demande d\'aide',
            'description' => $request->description ?? 'Aucune description',
            'adresse' => $request->address ?? 'N/A',
            'region' => $request->region ?? 'N/A',
            'urgence' => $request->urgency ?? 'moyenne',
            'statut' => $request->status ?? 'en_attente',
            'commentaire_admin' => $request->admin_comment ?? null,
            'traite_par' => $request->assigned_to ?? null,
            'date_traitement' => $request->processed_date ?? null,
            'pj' => null,
            'consentement' => true,
            'ip_address' => $request->ip_address ?? null,
            'user_agent' => $request->user_agent ?? null,
            'created_at' => $request->created_at ?? now(),
            'updated_at' => $request->updated_at ?? now()
        ]);
        $migrated++;
    }
    
    echo "✅ $migrated demandes migrées depuis public_requests\n";

    // 3. Migrer depuis demandes (si existe)
    if (Schema::hasTable('demandes')) {
        $demandes = DB::table('demandes')->get();
        $migratedDemandes = 0;
        
        foreach ($demandes as $demande) {
            // Vérifier si déjà migré (par tracking_code ou email)
            $exists = DB::table('demandes_unifiees')
                ->where('email', $demande->email ?? '')
                ->orWhere('tracking_code', $demande->tracking_code ?? '')
                ->exists();
                
            if (!$exists) {
                DB::table('demandes_unifiees')->insert([
                    'tracking_code' => $demande->tracking_code ?? 'CSAR-' . strtoupper(uniqid()),
                    'nom' => $demande->nom ?? 'N/A',
                    'prenom' => $demande->prenom ?? '',
                    'email' => $demande->email ?? 'N/A',
                    'telephone' => $demande->telephone ?? 'N/A',
                    'type_demande' => $demande->type_demande ?? 'aide_alimentaire',
                    'objet' => $demande->objet ?? 'Demande d\'aide',
                    'description' => $demande->description ?? 'Aucune description',
                    'adresse' => $demande->adresse ?? 'N/A',
                    'region' => $demande->region ?? 'N/A',
                    'urgence' => 'moyenne',
                    'statut' => $demande->statut ?? 'en_attente',
                    'commentaire_admin' => $demande->reponse ?? null,
                    'traite_par' => $demande->traite_par ?? null,
                    'date_traitement' => $demande->date_traitement ?? null,
                    'pj' => $demande->pj ?? null,
                    'consentement' => $demande->consentement ?? true,
                    'ip_address' => null,
                    'user_agent' => null,
                    'created_at' => $demande->created_at ?? now(),
                    'updated_at' => $demande->updated_at ?? now()
                ]);
                $migratedDemandes++;
            }
        }
        
        echo "✅ $migratedDemandes demandes migrées depuis demandes\n";
    }

    // 4. Ajouter des données de démonstration
    echo "\n🎭 Ajout de données de démonstration...\n";
    
    $demandesDemo = [
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'nom' => 'Diop',
            'prenom' => 'Fatou',
            'email' => 'fatou.diop@email.com',
            'telephone' => '+221 77 123 45 67',
            'type_demande' => 'aide_alimentaire',
            'objet' => 'Demande d\'aide alimentaire d\'urgence',
            'description' => 'Famille de 6 personnes en situation difficile, besoin d\'aide alimentaire urgente.',
            'adresse' => 'Dakar, Sénégal',
            'region' => 'Dakar',
            'urgence' => 'haute',
            'statut' => 'en_attente',
            'consentement' => true,
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2)
        ],
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'nom' => 'Fall',
            'prenom' => 'Moussa',
            'email' => 'moussa.fall@email.com',
            'telephone' => '+221 78 234 56 78',
            'type_demande' => 'aide_alimentaire',
            'objet' => 'Demande de soutien alimentaire',
            'description' => 'Personne âgée isolée, besoin de soutien alimentaire régulier.',
            'adresse' => 'Thiès, Sénégal',
            'region' => 'Thiès',
            'urgence' => 'moyenne',
            'statut' => 'approuvee',
            'commentaire_admin' => 'Demande approuvée, aide accordée',
            'traite_par' => 1,
            'date_traitement' => now()->subDays(1),
            'consentement' => true,
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(1)
        ],
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'nom' => 'Ba',
            'prenom' => 'Aminata',
            'email' => 'aminata.ba@email.com',
            'telephone' => '+221 76 345 67 89',
            'type_demande' => 'aide_urgence',
            'objet' => 'Demande d\'aide pour famille nombreuse',
            'description' => 'Famille de 8 personnes, père au chômage, besoin d\'aide alimentaire.',
            'adresse' => 'Kaolack, Sénégal',
            'region' => 'Kaolack',
            'urgence' => 'haute',
            'statut' => 'en_attente',
            'consentement' => true,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1)
        ],
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'nom' => 'Sarr',
            'prenom' => 'Ibrahima',
            'email' => 'ibrahima.sarr@email.com',
            'telephone' => '+221 77 456 78 90',
            'type_demande' => 'aide_alimentaire',
            'objet' => 'Demande de soutien nutritionnel',
            'description' => 'Enfants malnutris, besoin de compléments nutritionnels.',
            'adresse' => 'Saint-Louis, Sénégal',
            'region' => 'Saint-Louis',
            'urgence' => 'haute',
            'statut' => 'approuvee',
            'commentaire_admin' => 'Cas prioritaire, aide nutritionnelle accordée',
            'traite_par' => 1,
            'date_traitement' => now()->subDays(3),
            'consentement' => true,
            'created_at' => now()->subDays(7),
            'updated_at' => now()->subDays(3)
        ],
        [
            'tracking_code' => 'CSAR-' . strtoupper(uniqid()),
            'nom' => 'Diallo',
            'prenom' => 'Mariama',
            'email' => 'mariama.diallo@email.com',
            'telephone' => '+221 78 567 89 01',
            'type_demande' => 'aide_alimentaire',
            'objet' => 'Demande d\'aide alimentaire',
            'description' => 'Mère célibataire avec 3 enfants, situation financière difficile.',
            'adresse' => 'Ziguinchor, Sénégal',
            'region' => 'Ziguinchor',
            'urgence' => 'moyenne',
            'statut' => 'rejetee',
            'commentaire_admin' => 'Demande rejetée, critères non remplis',
            'traite_par' => 1,
            'date_traitement' => now()->subDays(8),
            'consentement' => true,
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(8)
        ]
    ];

    foreach ($demandesDemo as $demande) {
        DB::table('demandes_unifiees')->insert($demande);
    }
    
    echo "✅ " . count($demandesDemo) . " demandes de démonstration ajoutées\n";

    // 5. Créer les contrôleurs unifiés
    echo "\n🎮 Création des contrôleurs unifiés...\n";
    
    // Contrôleur Admin pour les demandes
    $adminController = '<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeUnifiee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DemandeController extends Controller
{
    public function index()
    {
        try {
            $demandes = DemandeUnifiee::latest()->paginate(20);
            
            $stats = [
                "total" => DemandeUnifiee::count(),
                "en_attente" => DemandeUnifiee::where("statut", "en_attente")->count(),
                "approuvees" => DemandeUnifiee::where("statut", "approuvee")->count(),
                "rejetees" => DemandeUnifiee::where("statut", "rejetee")->count(),
            ];
            
            return view("admin.demandes.index", compact("demandes", "stats"));
            
        } catch (\Exception $e) {
            Log::error("Erreur lors du chargement des demandes admin", [
                "error" => $e->getMessage()
            ]);
            
            return redirect()->back()->with("error", "Erreur lors du chargement des demandes");
        }
    }
    
    public function show($id)
    {
        try {
            $demande = DemandeUnifiee::findOrFail($id);
            return view("admin.demandes.show", compact("demande"));
            
        } catch (\Exception $e) {
            return redirect()->back()->with("error", "Demande non trouvée");
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $demande = DemandeUnifiee::findOrFail($id);
            
            $request->validate([
                "statut" => "required|in:en_attente,en_cours,approuvee,rejetee,terminee",
                "commentaire_admin" => "nullable|string|max:1000"
            ]);
            
            $demande->update([
                "statut" => $request->statut,
                "commentaire_admin" => $request->commentaire_admin,
                "traite_par" => auth()->id(),
                "date_traitement" => now()
            ]);
            
            Log::info("Demande mise à jour par admin", [
                "demande_id" => $id,
                "statut" => $request->statut,
                "admin_id" => auth()->id()
            ]);
            
            return redirect()->back()->with("success", "Demande mise à jour avec succès");
            
        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour de la demande", [
                "demande_id" => $id,
                "error" => $e->getMessage()
            ]);
            
            return redirect()->back()->with("error", "Erreur lors de la mise à jour");
        }
    }
}';

    file_put_contents('app/Http/Controllers/Admin/DemandeController.php', $adminController);
    echo "✅ Contrôleur Admin créé\n";

    // Contrôleur DG pour les demandes
    $dgController = '<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\DemandeUnifiee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DemandeController extends Controller
{
    public function index()
    {
        try {
            $demandes = DemandeUnifiee::latest()->paginate(20);
            
            $stats = [
                "total" => DemandeUnifiee::count(),
                "en_attente" => DemandeUnifiee::where("statut", "en_attente")->count(),
                "approuvees" => DemandeUnifiee::where("statut", "approuvee")->count(),
                "rejetees" => DemandeUnifiee::where("statut", "rejetee")->count(),
            ];
            
            return view("dg.demandes.index", compact("demandes", "stats"));
            
        } catch (\Exception $e) {
            Log::error("Erreur lors du chargement des demandes DG", [
                "error" => $e->getMessage()
            ]);
            
            return redirect()->back()->with("error", "Erreur lors du chargement des demandes");
        }
    }
    
    public function show($id)
    {
        try {
            $demande = DemandeUnifiee::findOrFail($id);
            return view("dg.demandes.show", compact("demande"));
            
        } catch (\Exception $e) {
            return redirect()->back()->with("error", "Demande non trouvée");
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $demande = DemandeUnifiee::findOrFail($id);
            
            $request->validate([
                "statut" => "required|in:en_attente,en_cours,approuvee,rejetee,terminee",
                "commentaire_admin" => "nullable|string|max:1000"
            ]);
            
            $demande->update([
                "statut" => $request->statut,
                "commentaire_admin" => $request->commentaire_admin,
                "traite_par" => auth()->id(),
                "date_traitement" => now()
            ]);
            
            Log::info("Demande mise à jour par DG", [
                "demande_id" => $id,
                "statut" => $request->statut,
                "dg_id" => auth()->id()
            ]);
            
            return redirect()->back()->with("success", "Demande mise à jour avec succès");
            
        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour de la demande DG", [
                "demande_id" => $id,
                "error" => $e->getMessage()
            ]);
            
            return redirect()->back()->with("error", "Erreur lors de la mise à jour");
        }
    }
}';

    file_put_contents('app/Http/Controllers/DG/DemandeController.php', $dgController);
    echo "✅ Contrôleur DG créé\n";

    // 6. Créer le modèle
    $model = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeUnifiee extends Model
{
    use HasFactory;
    
    protected $table = "demandes_unifiees";
    
    protected $fillable = [
        "tracking_code",
        "nom",
        "prenom", 
        "email",
        "telephone",
        "type_demande",
        "objet",
        "description",
        "adresse",
        "region",
        "urgence",
        "statut",
        "commentaire_admin",
        "traite_par",
        "date_traitement",
        "pj",
        "consentement",
        "ip_address",
        "user_agent"
    ];
    
    protected $casts = [
        "date_traitement" => "datetime",
        "consentement" => "boolean"
    ];
    
    public function traitePar()
    {
        return $this->belongsTo(User::class, "traite_par");
    }
    
    public function getFullNameAttribute()
    {
        return $this->nom . " " . $this->prenom;
    }
    
    public function getStatutBadgeAttribute()
    {
        $badges = [
            "en_attente" => "warning",
            "en_cours" => "info", 
            "approuvee" => "success",
            "rejetee" => "danger",
            "terminee" => "secondary"
        ];
        
        return $badges[$this->statut] ?? "secondary";
    }
}';

    file_put_contents('app/Models/DemandeUnifiee.php', $model);
    echo "✅ Modèle DemandeUnifiee créé\n";

    echo "\n🎉 Système de demandes unifié créé avec succès !\n";
    echo "📊 Résumé :\n";
    echo "   - Table 'demandes_unifiees' créée\n";
    echo "   - Données migrées sans doublons\n";
    echo "   - Contrôleurs Admin et DG créés\n";
    echo "   - Modèle DemandeUnifiee créé\n";
    echo "   - " . count($demandesDemo) . " demandes de démonstration ajoutées\n\n";
    
    echo "🚀 Le DG et l'Admin peuvent maintenant gérer les demandes dans la même table !\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "📍 Fichier : " . $e->getFile() . " ligne " . $e->getLine() . "\n";
}



















