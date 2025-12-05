<?php
echo "<!DOCTYPE html>
<html>
<head>
    <title>Test PHP - CSAR</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f0f8ff; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        .success { color: #28a745; font-weight: bold; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎉 Test PHP CSAR</h1>
        <p class='success'>✅ PHP fonctionne correctement !</p>
        
        <div class='info'>
            <h3>Informations serveur :</h3>
            <p><strong>Date :</strong> " . date('Y-m-d H:i:s') . "</p>
            <p><strong>PHP Version :</strong> " . phpversion() . "</p>
            <p><strong>Serveur :</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Non disponible') . "</p>
            <p><strong>Répertoire :</strong> " . getcwd() . "</p>
        </div>
        
        <h3>🔗 Liens utiles :</h3>
        <p><a href='index-admin.php' style='color: #007bff; text-decoration: none; font-weight: bold;'>→ Interface Admin CSAR</a></p>
        <p><a href='/' style='color: #007bff; text-decoration: none;'>→ Page d'accueil</a></p>
    </div>
</body>
</html>";
?>