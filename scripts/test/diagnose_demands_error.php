<?php
/**
 * 🔍 DIAGNOSTIC DE L'ERREUR DES DEMANDES
 */

$pdo = new PDO("mysql:host=localhost;dbname=plateforme-csar;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "🔍 DIAGNOSTIC DE L'ERREUR DES DEMANDES\n";
echo "=====================================\n\n";

// 1. Vérifier la structure de la table demandes
echo "1️⃣ Structure de la table demandes :\n";
try {
    $stmt = $pdo->query("DESCRIBE demandes");
    $columns = $stmt->fetchAll();
    foreach ($columns as $column) {
        echo "   - {$column['Field']} ({$column['Type']})\n";
    }
} catch (PDOException $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

// 2. Vérifier le contenu de la table demandes
echo "2️⃣ Contenu de la table demandes :\n";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM demandes");
    $result = $stmt->fetch();
    echo "   📊 Nombre de demandes: " . $result['count'] . "\n";
    
    if ($result['count'] > 0) {
        $stmt = $pdo->query("SELECT * FROM demandes LIMIT 1");
        $demande = $stmt->fetch();
        echo "   📋 Exemple de demande :\n";
        foreach ($demande as $key => $value) {
            echo "      {$key}: {$value}\n";
        }
    }
} catch (PDOException $e) {
    echo "   ❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. Vérifier la table public_requests
echo "3️⃣ Vérification de la table public_requests :\n";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM public_requests");
    $result = $stmt->fetch();
    echo "   📊 Nombre de public_requests: " . $result['count'] . "\n";
} catch (PDOException $e) {
    echo "   ❌ Table public_requests n'existe pas ou erreur: " . $e->getMessage() . "\n";
}

echo "\n";

// 4. Tester une requête simple sur demandes
echo "4️⃣ Test de requête simple sur demandes :\n";
try {
    $stmt = $pdo->query("SELECT id, code_suivi, nom_demandeur, statut FROM demandes LIMIT 1");
    $result = $stmt->fetch();
    if ($result) {
        echo "   ✅ Requête simple réussie\n";
        echo "   📋 Résultat: ID={$result['id']}, Code={$result['code_suivi']}, Nom={$result['nom_demandeur']}, Statut={$result['statut']}\n";
    } else {
        echo "   ⚠️ Aucune donnée trouvée\n";
    }
} catch (PDOException $e) {
    echo "   ❌ Erreur requête simple: " . $e->getMessage() . "\n";
}

echo "\n";

// 5. Tester avec les colonnes spécifiques du modèle
echo "5️⃣ Test avec les colonnes du modèle Demande :\n";
$testColumns = ['code_suivi', 'nom_demandeur', 'email', 'telephone', 'type_demande', 'statut', 'region', 'commune', 'departement', 'adresse', 'description', 'priorite', 'assignee_id', 'date_demande', 'date_traitement', 'commentaire_admin'];

foreach ($testColumns as $column) {
    try {
        $stmt = $pdo->query("SELECT {$column} FROM demandes LIMIT 1");
        $result = $stmt->fetch();
        echo "   ✅ Colonne '{$column}' accessible\n";
    } catch (PDOException $e) {
        echo "   ❌ Erreur colonne '{$column}': " . $e->getMessage() . "\n";
    }
}

echo "\n🎯 DIAGNOSTIC TERMINÉ\n";
echo "====================\n";
?>