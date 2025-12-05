<?php
// Accès direct à l'interface admin - Bypass du routage Laravel
session_start();

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

// Fonction de connexion à la base de données
function getDBConnection() {
    global $db_host, $db_name, $db_user, $db_pass;
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// Fonction de vérification de l'authentification
function checkAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false;
    }
    return true;
}

// Fonction de connexion
function login($email, $password) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user'] = $user;
        return true;
    }
    return false;
}

// Fonction de déconnexion
function logout() {
    session_destroy();
    header('Location: admin-direct.php');
    exit;
}

// Traitement des actions
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'login':
            if (login($_POST['email'], $_POST['password'])) {
                header('Location: admin-direct.php');
                exit;
            } else {
                $error = "Identifiants incorrects";
            }
            break;
        case 'logout':
            logout();
            break;
    }
}

// Si l'utilisateur n'est pas connecté, afficher le formulaire de connexion
if (!checkAuth()) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion Admin - CSAR</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
            .login-container { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
            .form-group { margin-bottom: 20px; }
            label { display: block; margin-bottom: 5px; font-weight: bold; }
            input[type="email"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
            button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
            button:hover { background: #0056b3; }
            .error { color: red; margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>Connexion Admin CSAR</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label>Email :</label>
                    <input type="email" name="email" value="admin@csar.sn" required>
                </div>
                <div class="form-group">
                    <label>Mot de passe :</label>
                    <input type="password" name="password" value="password" required>
                </div>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// L'utilisateur est connecté, afficher le dashboard
$pdo = getDBConnection();

// Récupérer les statistiques
$stats = [];
try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role != 'admin'");
    $stats['personnel'] = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $stats['users'] = $stmt->fetch()['total'];
    
    // Vérifier si d'autres tables existent
    $tables = ['public_requests', 'warehouses', 'contact_messages', 'newsletter_subscribers'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $table");
            $stats[$table] = $stmt->fetch()['total'];
        } catch (Exception $e) {
            $stats[$table] = 0;
        }
    }
} catch (Exception $e) {
    $stats = ['personnel' => 0, 'users' => 0, 'public_requests' => 0, 'warehouses' => 0, 'contact_messages' => 0, 'newsletter_subscribers' => 0];
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CSAR</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f5f5; }
        .header { background: #007bff; color: white; padding: 20px; }
        .header h1 { margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-number { font-size: 2em; font-weight: bold; color: #007bff; }
        .stat-label { color: #666; margin-top: 5px; }
        .admin-actions { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; margin-bottom: 10px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Dashboard Admin CSAR</h1>
            <p>Bienvenue, <?php echo $_SESSION['admin_user']['name']; ?>!</p>
        </div>
    </div>
    
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['users']; ?></div>
                <div class="stat-label">Utilisateurs totaux</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['personnel']; ?></div>
                <div class="stat-label">Personnel</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['public_requests']; ?></div>
                <div class="stat-label">Demandes publiques</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['warehouses']; ?></div>
                <div class="stat-label">Entrepôts</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['contact_messages']; ?></div>
                <div class="stat-label">Messages de contact</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['newsletter_subscribers']; ?></div>
                <div class="stat-label">Abonnés newsletter</div>
            </div>
        </div>
        
        <div class="admin-actions">
            <h2>Actions d'administration</h2>
            <a href="admin-users.php" class="btn">Gérer les utilisateurs</a>
            <a href="admin-requests.php" class="btn">Gérer les demandes</a>
            <a href="admin-warehouses.php" class="btn">Gérer les entrepôts</a>
            <a href="admin-messages.php" class="btn">Messages de contact</a>
            <a href="admin-newsletter.php" class="btn">Newsletter</a>
            
            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="logout">
                <button type="submit" class="btn btn-danger">Déconnexion</button>
            </form>
        </div>
        
        <div class="admin-actions">
            <h2>Accès aux interfaces</h2>
            <a href="dg-direct.php" class="btn">Interface DG</a>
            <a href="agent-direct.php" class="btn">Interface Agent</a>
            <a href="responsable-direct.php" class="btn">Interface Responsable</a>
            <a href="drh-direct.php" class="btn">Interface DRH</a>
        </div>
    </div>
</body>
</html>
