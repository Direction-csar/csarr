<?php
/**
 * Script pour ajouter des produits dans la table stocks
 * Ce script vous permet d'ajouter facilement des produits pour les mouvements de stock
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\StockType;
use Illuminate\Support\Facades\DB;

echo "===========================================\n";
echo "  AJOUT DE PRODUITS DANS LE STOCK\n";
echo "===========================================\n\n";

try {
    // Vérifier les entrepôts disponibles
    $warehouses = Warehouse::where('is_active', true)->get();
    
    if ($warehouses->isEmpty()) {
        echo "❌ ERREUR: Aucun entrepôt actif trouvé!\n";
        echo "Vous devez d'abord créer un entrepôt.\n\n";
        
        // Créer un entrepôt par défaut
        echo "Création d'un entrepôt par défaut...\n";
        $warehouse = Warehouse::create([
            'name' => 'Entrepôt Principal',
            'code' => 'EP-001',
            'address' => 'Adresse de l\'entrepôt',
            'manager' => 'Gestionnaire',
            'phone' => '00000000',
            'capacity' => 10000,
            'is_active' => true
        ]);
        echo "✅ Entrepôt créé: {$warehouse->name}\n\n";
        $warehouses = collect([$warehouse]);
    }
    
    // Vérifier les types de stock
    $stockTypes = StockType::all();
    
    if ($stockTypes->isEmpty()) {
        echo "❌ ERREUR: Aucun type de stock trouvé!\n";
        echo "Création des types de stock par défaut...\n";
        
        $types = [
            ['name' => 'Denrées alimentaires', 'code' => 'ALIM', 'description' => 'Produits alimentaires'],
            ['name' => 'Matériel humanitaire', 'code' => 'MAT', 'description' => 'Équipements humanitaires'],
            ['name' => 'Carburant', 'code' => 'CARB', 'description' => 'Carburants et lubrifiants'],
            ['name' => 'Médicaments', 'code' => 'MED', 'description' => 'Produits médicaux'],
        ];
        
        foreach ($types as $type) {
            StockType::create($type);
        }
        
        $stockTypes = StockType::all();
        echo "✅ Types de stock créés\n\n";
    }
    
    // Liste des produits par catégorie
    $produitsParCategorie = [
        'Denrées alimentaires' => [
            ['Riz blanc', 'Riz de qualité supérieure', 'sac de 50kg', 25000],
            ['Maïs', 'Maïs en grains', 'sac de 50kg', 18000],
            ['Mil', 'Mil en grains', 'sac de 50kg', 20000],
            ['Haricots', 'Haricots secs', 'sac de 50kg', 30000],
            ['Huile végétale', 'Huile de cuisine', 'bidon de 20L', 15000],
            ['Farine de blé', 'Farine pour pain', 'sac de 25kg', 12000],
            ['Sucre', 'Sucre cristallisé', 'sac de 50kg', 35000],
            ['Sel', 'Sel de cuisine', 'sac de 25kg', 8000],
            ['Lait en poudre', 'Lait entier en poudre', 'carton de 10kg', 45000],
            ['Pâtes alimentaires', 'Pâtes de blé', 'carton de 10kg', 8000],
        ],
        'Matériel humanitaire' => [
            ['Couvertures', 'Couvertures chaudes', 'unité', 5000],
            ['Bâches', 'Bâches en plastique', 'unité', 8000],
            ['Jerrycans', 'Jerrycans 20L', 'unité', 3000],
            ['Kits hygiène', 'Kits d\'hygiène familiale', 'kit', 15000],
            ['Moustiquaires', 'Moustiquaires imprégnées', 'unité', 4000],
            ['Tentes', 'Tentes familiales', 'unité', 150000],
            ['Lampes solaires', 'Lampes solaires portables', 'unité', 12000],
            ['Seaux', 'Seaux en plastique 15L', 'unité', 2000],
        ],
        'Carburant' => [
            ['Essence', 'Essence ordinaire', 'litre', 650],
            ['Gasoil', 'Gasoil', 'litre', 600],
            ['Pétrole', 'Pétrole lampant', 'litre', 550],
        ],
        'Médicaments' => [
            ['Paracétamol', 'Comprimés 500mg', 'boîte', 2000],
            ['Amoxicilline', 'Antibiotique', 'boîte', 5000],
            ['Serum physiologique', 'Solution saline', 'litre', 1500],
            ['Pansements', 'Pansements stériles', 'boîte', 3000],
            ['Désinfectant', 'Solution désinfectante', 'litre', 4000],
        ],
    ];
    
    echo "Liste des catégories disponibles:\n";
    $categories = array_keys($produitsParCategorie);
    foreach ($categories as $index => $cat) {
        echo ($index + 1) . ". $cat\n";
    }
    echo "\n";
    
    // Demander quelle catégorie ajouter
    echo "Voulez-vous ajouter tous les produits? (o/n): ";
    $ajouterTout = trim(fgets(STDIN));
    
    $produitsAAjouter = [];
    
    if (strtolower($ajouterTout) === 'o') {
        // Ajouter tous les produits
        foreach ($produitsParCategorie as $categorie => $produits) {
            foreach ($produits as $produit) {
                $produitsAAjouter[] = [
                    'categorie' => $categorie,
                    'nom' => $produit[0],
                    'description' => $produit[1],
                    'unite' => $produit[2],
                    'prix' => $produit[3]
                ];
            }
        }
    } else {
        // Sélectionner une catégorie
        echo "Entrez le numéro de la catégorie (1-" . count($categories) . "): ";
        $choix = trim(fgets(STDIN));
        
        if (!is_numeric($choix) || $choix < 1 || $choix > count($categories)) {
            echo "❌ Choix invalide!\n";
            exit;
        }
        
        $categorieChoisie = $categories[$choix - 1];
        foreach ($produitsParCategorie[$categorieChoisie] as $produit) {
            $produitsAAjouter[] = [
                'categorie' => $categorieChoisie,
                'nom' => $produit[0],
                'description' => $produit[1],
                'unite' => $produit[2],
                'prix' => $produit[3]
            ];
        }
    }
    
    echo "\n";
    echo "Ajout des produits en cours...\n";
    echo "===========================================\n\n";
    
    $warehouse = $warehouses->first();
    $compteurAjout = 0;
    $compteurExistant = 0;
    
    foreach ($produitsAAjouter as $produit) {
        // Trouver le type de stock correspondant
        $stockType = $stockTypes->firstWhere('name', $produit['categorie']);
        
        if (!$stockType) {
            $stockType = $stockTypes->first();
        }
        
        // Vérifier si le produit existe déjà
        $existant = Stock::where('item_name', $produit['nom'])
            ->where('warehouse_id', $warehouse->id)
            ->first();
        
        if ($existant) {
            echo "⚠️  '{$produit['nom']}' existe déjà (Quantité: {$existant->quantity})\n";
            $compteurExistant++;
            continue;
        }
        
        // Créer le produit
        $stock = Stock::create([
            'warehouse_id' => $warehouse->id,
            'stock_type_id' => $stockType->id,
            'item_name' => $produit['nom'],
            'description' => $produit['description'] . ' - Unité: ' . $produit['unite'],
            'quantity' => 0, // Quantité initiale à 0
            'min_quantity' => 10, // Seuil d'alerte
            'max_quantity' => 1000,
            'unit_price' => $produit['prix'],
            'is_active' => true
        ]);
        
        echo "✅ Ajouté: {$produit['nom']} ({$produit['unite']}) - Prix: {$produit['prix']} FCFA\n";
        $compteurAjout++;
    }
    
    echo "\n===========================================\n";
    echo "✅ Résumé:\n";
    echo "   - Produits ajoutés: $compteurAjout\n";
    echo "   - Produits existants: $compteurExistant\n";
    echo "===========================================\n\n";
    
    // Afficher le nombre total de produits
    $totalProduits = Stock::where('is_active', true)->count();
    echo "📦 Total de produits actifs dans le stock: $totalProduits\n\n";
    
    echo "Vous pouvez maintenant créer des mouvements de stock!\n";
    echo "Allez sur: Admin > Gestion des Stocks > Nouveau Mouvement\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}























