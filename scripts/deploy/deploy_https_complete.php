<?php
/**
 * Script de déploiement HTTPS complet pour la plateforme CSAR
 * Configure HTTPS et résout le problème de géolocalisation
 */

echo "🔒 DÉPLOIEMENT HTTPS - PLATEFORME CSAR\n";
echo "=======================================\n\n";

/**
 * Étape 1: Vérification de l'environnement
 */
function checkEnvironment() {
    echo "🔍 Étape 1: Vérification de l'environnement...\n";
    
    // Vérifier si on est sur Windows avec XAMPP
    if (PHP_OS_FAMILY === 'Windows') {
        $xamppPath = 'C:\xampp';
        if (is_dir($xamppPath)) {
            echo "✅ XAMPP détecté dans {$xamppPath}\n";
            return $xamppPath;
        } else {
            echo "⚠️ XAMPP non trouvé dans {$xamppPath}\n";
            echo "   Veuillez installer XAMPP ou ajuster le chemin\n";
            return false;
        }
    } else {
        echo "✅ Environnement Linux/Unix détecté\n";
        return 'linux';
    }
}

/**
 * Étape 2: Configuration du fichier .env pour HTTPS
 */
function configureHttpsEnv() {
    echo "\n🔧 Étape 2: Configuration HTTPS dans .env...\n";
    
    $envFile = '.env';
    $envContent = '';
    
    if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
    } else {
        echo "⚠️ Fichier .env non trouvé, création d'un nouveau fichier\n";
    }
    
    // Configuration HTTPS
    $httpsConfig = [
        'APP_URL=https://csar.local',
        'SESSION_SECURE_COOKIE=true',
        'SESSION_DOMAIN=.csar.local',
        'FORCE_HTTPS=true',
        'HSTS_ENABLED=true',
        'SECURE_COOKIES=true',
        'ASSET_URL=https://csar.local'
    ];
    
    foreach ($httpsConfig as $config) {
        $key = explode('=', $config)[0];
        
        // Supprimer l'ancienne configuration si elle existe
        $envContent = preg_replace("/^{$key}=.*$/m", '', $envContent);
        
        // Ajouter la nouvelle configuration
        $envContent .= "\n{$config}";
    }
    
    // Nettoyer les lignes vides multiples
    $envContent = preg_replace('/\n\s*\n\s*\n/', "\n\n", $envContent);
    
    file_put_contents($envFile, $envContent);
    echo "✅ Configuration HTTPS ajoutée au fichier .env\n";
}

/**
 * Étape 3: Génération des certificats SSL (Windows/XAMPP)
 */
function generateSslCertificates($xamppPath) {
    echo "\n🔐 Étape 3: Génération des certificats SSL...\n";
    
    if (!$xamppPath || $xamppPath === 'linux') {
        echo "⚠️ Génération de certificats SSL non supportée sur cet environnement\n";
        echo "   Veuillez utiliser Let's Encrypt ou configurer SSL manuellement\n";
        return false;
    }
    
    $sslDir = $xamppPath . '\apache\conf';
    $opensslPath = $xamppPath . '\apache\bin\openssl.exe';
    
    if (!file_exists($opensslPath)) {
        echo "❌ OpenSSL non trouvé dans XAMPP\n";
        echo "   Veuillez réinstaller XAMPP avec OpenSSL\n";
        return false;
    }
    
    // Créer les répertoires SSL
    $sslCrtDir = $sslDir . '\ssl.crt';
    $sslKeyDir = $sslDir . '\ssl.key';
    
    if (!is_dir($sslCrtDir)) mkdir($sslCrtDir, 0755, true);
    if (!is_dir($sslKeyDir)) mkdir($sslKeyDir, 0755, true);
    
    // Générer la clé privée
    $keyFile = $sslKeyDir . '\csar.local.key';
    $certFile = $sslCrtDir . '\csar.local.crt';
    
    $keyCommand = "\"{$opensslPath}\" genrsa -out \"{$keyFile}\" 2048";
    $certCommand = "\"{$opensslPath}\" req -new -x509 -key \"{$keyFile}\" -out \"{$certFile}\" -days 365 -subj \"/C=SN/ST=Dakar/L=Dakar/O=CSAR/OU=IT/CN=csar.local\"";
    
    echo "   Génération de la clé privée...\n";
    exec($keyCommand, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Clé privée générée\n";
    } else {
        echo "❌ Erreur lors de la génération de la clé privée\n";
        return false;
    }
    
    echo "   Génération du certificat auto-signé...\n";
    exec($certCommand, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Certificat auto-signé généré\n";
        return true;
    } else {
        echo "❌ Erreur lors de la génération du certificat\n";
        return false;
    }
}

/**
 * Étape 4: Configuration Apache pour HTTPS
 */
function configureApacheHttps($xamppPath) {
    echo "\n🌐 Étape 4: Configuration Apache pour HTTPS...\n";
    
    if (!$xamppPath || $xamppPath === 'linux') {
        echo "⚠️ Configuration Apache non supportée sur cet environnement\n";
        return false;
    }
    
    $apacheConfDir = $xamppPath . '\apache\conf';
    $vhostsFile = $apacheConfDir . '\extra\httpd-vhosts-csar-ssl.conf';
    
    $vhostsConfig = '<VirtualHost *:443>
    ServerName csar.local
    DocumentRoot "C:/xampp/htdocs/csar-platform/public"
    
    SSLEngine on
    SSLCertificateFile "conf/ssl.crt/csar.local.crt"
    SSLCertificateKeyFile "conf/ssl.key/csar.local.key"
    
    <Directory "C:/xampp/htdocs/csar-platform/public">
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/csar_ssl_error.log"
    CustomLog "logs/csar_ssl_access.log" common
</VirtualHost>

<VirtualHost *:80>
    ServerName csar.local
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>';
    
    file_put_contents($vhostsFile, $vhostsConfig);
    echo "✅ Configuration des virtual hosts HTTPS créée\n";
    
    // Mettre à jour httpd.conf
    $httpdConfFile = $apacheConfDir . '\httpd.conf';
    $httpdConfContent = file_get_contents($httpdConfFile);
    
    // Activer le module SSL
    if (strpos($httpdConfContent, 'LoadModule ssl_module modules/mod_ssl.so') === false) {
        $httpdConfContent .= "\nLoadModule ssl_module modules/mod_ssl.so\n";
    }
    
    // Inclure la configuration SSL
    if (strpos($httpdConfContent, 'Include conf/extra/httpd-vhosts-csar-ssl.conf') === false) {
        $httpdConfContent .= "\nInclude conf/extra/httpd-vhosts-csar-ssl.conf\n";
    }
    
    file_put_contents($httpdConfFile, $httpdConfContent);
    echo "✅ Configuration Apache mise à jour\n";
    
    return true;
}

/**
 * Étape 5: Mise à jour du fichier hosts
 */
function updateHostsFile() {
    echo "\n📝 Étape 5: Mise à jour du fichier hosts...\n";
    
    if (PHP_OS_FAMILY !== 'Windows') {
        echo "⚠️ Mise à jour du fichier hosts non supportée sur cet environnement\n";
        return false;
    }
    
    $hostsFile = 'C:\Windows\System32\drivers\etc\hosts';
    $hostsEntry = '127.0.0.1 csar.local';
    
    if (!is_writable($hostsFile)) {
        echo "❌ Fichier hosts non accessible en écriture\n";
        echo "   Veuillez exécuter ce script en tant qu'administrateur\n";
        return false;
    }
    
    $hostsContent = file_get_contents($hostsFile);
    
    if (strpos($hostsContent, $hostsEntry) === false) {
        $hostsContent .= "\n{$hostsEntry}";
        file_put_contents($hostsFile, $hostsContent);
        echo "✅ Entrée ajoutée dans le fichier hosts\n";
    } else {
        echo "✅ Entrée déjà présente dans le fichier hosts\n";
    }
    
    return true;
}

/**
 * Étape 6: Nettoyage du cache Laravel
 */
function clearLaravelCache() {
    echo "\n🧹 Étape 6: Nettoyage du cache Laravel...\n";
    
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
    echo "\n🎉 CONFIGURATION HTTPS TERMINÉE!\n";
    echo "================================\n\n";
    
    echo "📋 INSTRUCTIONS DE TEST:\n";
    echo "========================\n";
    echo "1. Redémarrez Apache depuis le panneau de contrôle XAMPP\n";
    echo "2. Ouvrez votre navigateur et allez sur: https://csar.local\n";
    echo "3. Acceptez le certificat auto-signé (avertissement de sécurité)\n";
    echo "4. Testez le formulaire de demande d'aide alimentaire\n";
    echo "5. La géolocalisation devrait maintenant fonctionner\n\n";
    
    echo "🔗 URLS D'ACCÈS:\n";
    echo "================\n";
    echo "🌐 HTTPS (Recommandé): https://csar.local\n";
    echo "🌐 HTTP (Redirection): http://csar.local\n";
    echo "🌐 Local: http://localhost:8000\n\n";
    
    echo "⚠️ IMPORTANT:\n";
    echo "=============\n";
    echo "- Le certificat est auto-signé et valide pour 365 jours\n";
    echo "- Acceptez l'avertissement de sécurité dans votre navigateur\n";
    echo "- La géolocalisation ne fonctionne qu'en HTTPS\n";
    echo "- Pour la production, utilisez Let's Encrypt\n\n";
    
    echo "✅ La géolocalisation est maintenant fonctionnelle!\n";
}

// Exécution du déploiement HTTPS
try {
    $xamppPath = checkEnvironment();
    configureHttpsEnv();
    
    if ($xamppPath && $xamppPath !== 'linux') {
        if (generateSslCertificates($xamppPath)) {
            configureApacheHttps($xamppPath);
            updateHostsFile();
        }
    }
    
    clearLaravelCache();
    showFinalInstructions();
    
} catch (Exception $e) {
    echo "\n❌ ERREUR LORS DE LA CONFIGURATION HTTPS: " . $e->getMessage() . "\n";
    echo "Vérifiez les logs et réessayez.\n";
}
