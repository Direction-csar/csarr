<?php
// Interface admin directe dans le répertoire racine
echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Interface Admin CSAR - Direct</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 10px 10px 0; }
        .btn:hover { background: #0056b3; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎉 Interface Admin CSAR - Accès Direct</h1>
        <p class='success'>✅ Ce fichier PHP fonctionne directement !</p>
        
        <h2>Configuration actuelle :</h2>
        <ul>
            <li><strong>Serveur :</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</li>
            <li><strong>Document Root :</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</li>
            <li><strong>Script Path :</strong> " . $_SERVER['SCRIPT_NAME'] . "</li>
            <li><strong>Request URI :</strong> " . $_SERVER['REQUEST_URI'] . "</li>
        </ul>
        
        <h2>Test de connexion à la base de données :</h2>";

// Test de connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform_2025', 'laravel_user', 'csar@2025Host1');
    echo "<p class='success'>✅ Connexion à la base de données réussie !</p>";
    
    // Test de récupération des utilisateurs
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch();
    echo "<p class='success'>✅ Utilisateurs trouvés : " . $result['total'] . "</p>";
    
    // Afficher les utilisateurs admin
    $stmt = $pdo->query("SELECT id, name, email, role FROM users WHERE role = 'admin'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Utilisateurs administrateurs :</h3>";
    echo "<ul>";
    foreach ($admins as $admin) {
        echo "<li><strong>" . htmlspecialchars($admin['name']) . "</strong> (" . htmlspecialchars($admin['email']) . ") - ID: " . $admin['id'] . "</li>";
    }
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur de connexion à la base de données : " . $e->getMessage() . "</p>";
}

echo "
        <h2>Accès aux interfaces :</h2>
        <a href='public/admin-direct.php' class='btn'>Interface Admin Complète</a>
        <a href='public/test.html' class='btn'>Test HTML</a>
        <a href='public/test.php' class='btn'>Test PHP</a>
        
        <h2>Informations de débogage :</h2>
        <p><strong>PHP Version :</strong> " . phpversion() . "</p>
        <p><strong>Extensions chargées :</strong> " . implode(', ', get_loaded_extensions()) . "</p>
        <p><strong>Répertoire courant :</strong> " . getcwd() . "</p>
        <p><strong>Fichiers dans le répertoire public :</strong></p>
        <ul>";

// Lister les fichiers dans le répertoire public
if (is_dir('public')) {
    $files = scandir('public');
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>" . htmlspecialchars($file) . "</li>";
        }
    }
} else {
    echo "<li class='error'>Le répertoire public n'existe pas ou n'est pas accessible</li>";
}

echo "
        </ul>
    </div>
</body>
</html>";
?>
