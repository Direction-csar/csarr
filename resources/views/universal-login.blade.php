<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion CSAR - Plateforme Interne</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #059669, #047857);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        h1 {
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 24px;
        }
        
        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .role-selector {
            margin-bottom: 20px;
        }
        
        .role-buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .role-btn {
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }
        
        .role-btn.active {
            border-color: #059669;
            background: #f0fdf4;
            color: #059669;
        }
        
        .role-btn:hover {
            border-color: #059669;
            background: #f0fdf4;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #374151;
            font-weight: 500;
        }
        
        input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: #059669;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #059669, #047857);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .credentials {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            text-align: left;
        }
        
        .credentials h3 {
            color: #1f2937;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .cred-section {
            margin-bottom: 15px;
        }
        
        .cred-section h4 {
            color: #374151;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .cred-item {
            margin-bottom: 6px;
            font-size: 13px;
        }
        
        .cred-label {
            font-weight: 600;
            color: #374151;
        }
        
        .cred-value {
            color: #059669;
            font-family: monospace;
        }
        
        .alert {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">CSAR</div>
        <h1>Connexion</h1>
        <p class="subtitle">Plateforme Interne CSAR</p>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <form method="POST" action="{{ route('universal.login') }}" id="loginForm">
            @csrf
            
            <div class="role-selector">
                <label>Choisissez votre interface :</label>
                <div class="role-buttons">
                    <button type="button" class="role-btn active" data-role="admin">Admin</button>
                    <button type="button" class="role-btn" data-role="dg">DG</button>
                    <button type="button" class="role-btn" data-role="responsable">Responsable</button>
                    <button type="button" class="role-btn" data-role="agent">Agent</button>
                </div>
                <input type="hidden" name="role" id="selectedRole" value="admin">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="admin@test.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" value="admin123" required>
            </div>
            
            <button type="submit" class="btn">Se connecter</button>
        </form>
        
        <div class="credentials">
            <h3>🔑 Identifiants de test</h3>
            
            <div class="cred-section">
                <h4>👑 Administrateur</h4>
                <div class="cred-item">
                    <span class="cred-label">Email:</span>
                    <span class="cred-value">admin@test.com</span>
                </div>
                <div class="cred-item">
                    <span class="cred-label">Mot de passe:</span>
                    <span class="cred-value">admin123</span>
                </div>
            </div>
            
            <div class="cred-section">
                <h4>🎯 Directeur Général</h4>
                <div class="cred-item">
                    <span class="cred-label">Email:</span>
                    <span class="cred-value">dg@test.com</span>
                </div>
                <div class="cred-item">
                    <span class="cred-label">Mot de passe:</span>
                    <span class="cred-value">dg123</span>
                </div>
            </div>
            
            <div class="cred-section">
                <h4>📦 Responsable</h4>
                <div class="cred-item">
                    <span class="cred-label">Email:</span>
                    <span class="cred-value">responsable@test.com</span>
                </div>
                <div class="cred-item">
                    <span class="cred-label">Mot de passe:</span>
                    <span class="cred-value">resp123</span>
                </div>
            </div>
            
            <div class="cred-section">
                <h4>👤 Agent</h4>
                <div class="cred-item">
                    <span class="cred-label">Email:</span>
                    <span class="cred-value">agent@test.com</span>
                </div>
                <div class="cred-item">
                    <span class="cred-label">Mot de passe:</span>
                    <span class="cred-value">agent123</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Gestion des boutons de rôle
        document.querySelectorAll('.role-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Retirer la classe active de tous les boutons
                document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
                // Ajouter la classe active au bouton cliqué
                this.classList.add('active');
                // Mettre à jour le champ caché
                document.getElementById('selectedRole').value = this.dataset.role;
                
                // Mettre à jour les identifiants selon le rôle
                updateCredentials(this.dataset.role);
            });
        });
        
        function updateCredentials(role) {
            const credentials = {
                'admin': { email: 'admin@test.com', password: 'admin123' },
                'dg': { email: 'dg@test.com', password: 'dg123' },
                'responsable': { email: 'responsable@test.com', password: 'resp123' },
                'agent': { email: 'agent@test.com', password: 'agent123' }
            };
            
            document.getElementById('email').value = credentials[role].email;
            document.getElementById('password').value = credentials[role].password;
        }
    </script>
</body>
</html>


