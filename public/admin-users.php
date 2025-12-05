<?php
// Gestion des utilisateurs - Interface admin directe
session_start();

// Vérifier l'authentification
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-direct.php');
    exit;
}

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement des actions
$message = '';
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'create':
            try {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, 1, NOW(), NOW())");
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->execute([$_POST['name'], $_POST['email'], $password, $_POST['role']]);
                $message = "Utilisateur créé avec succès !";
            } catch (Exception $e) {
                $message = "Erreur : " . $e->getMessage();
            }
            break;
        case 'delete':
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$_POST['user_id']]);
                $message = "Utilisateur supprimé avec succès !";
            } catch (Exception $e) {
                $message = "Erreur : " . $e->getMessage();
            }
            break;
    }
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT id, name, email, role, is_active, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs - CSAR Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f5f5f5; }
        .header { background: #007bff; color: white; padding: 20px; }
        .header h1 { margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; margin-bottom: 10px; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        .btn-secondary { background: #6c757d; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .message { padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .role-badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .role-admin { background: #dc3545; color: white; }
        .role-dg { background: #007bff; color: white; }
        .role-responsable { background: #28a745; color: white; }
        .role-agent { background: #ffc107; color: black; }
        .role-drh { background: #6f42c1; color: white; }
        .role-chef_personnel { background: #fd7e14; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Gestion des utilisateurs - CSAR Admin</h1>
            <a href="admin-direct.php" class="btn btn-secondary">Retour au dashboard</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Erreur') !== false ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Créer un nouvel utilisateur</h2>
            <form method="post">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label>Nom :</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email :</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Mot de passe :</label>
                    <input type="password" name="password" value="password" required>
                </div>
                <div class="form-group">
                    <label>Rôle :</label>
                    <select name="role" required>
                        <option value="admin">Administrateur</option>
                        <option value="dg">Directeur Général</option>
                        <option value="responsable">Responsable</option>
                        <option value="agent">Agent</option>
                        <option value="drh">Directeur RH</option>
                        <option value="chef_personnel">Chef Personnel</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Créer l'utilisateur</button>
            </form>
        </div>
        
        <div class="card">
            <h2>Liste des utilisateurs</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="role-badge role-<?php echo $user['role']; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                            </span>
                        </td>
                        <td><?php echo $user['is_active'] ? 'Actif' : 'Inactif'; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                        <td>
                            <form method="post" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
