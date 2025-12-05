<?php
/**
 * Script pour générer une vraie clé d'application Laravel
 */

// Générer une clé de 32 caractères pour AES-256-CBC
$key = 'base64:' . base64_encode(random_bytes(32));

// Lire le fichier .env
$envContent = file_get_contents('.env');

// Remplacer la clé invalide par la vraie clé
$envContent = preg_replace('/APP_KEY=.*/', 'APP_KEY=' . $key, $envContent);

// Sauvegarder le fichier .env
file_put_contents('.env', $envContent);

echo "✅ Clé d'application générée avec succès !\n";
echo "Nouvelle clé : " . $key . "\n\n";
echo "Le serveur devrait maintenant fonctionner correctement.\n";
echo "Accédez à : http://localhost:8000/sim-reports\n";
?>