<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Connexion CSAR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .test-buttons {
            margin: 20px 0;
        }
        .test-buttons button {
            background: #28a745;
            margin: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Test de Connexion CSAR</h1>
        <p>Cette page permet de tester les connexions sans problème de CSRF.</p>
        
        <div class="test-buttons">
            <button onclick="testRoute()">Tester Route</button>
            <button onclick="testAdminLogin()">Tester Admin</button>
            <button onclick="testDGLogin()">Tester DG</button>
            <button onclick="testAgentLogin()">Tester Agent</button>
            <button onclick="testResponsableLogin()">Tester Responsable</button>
        </div>
        
        <form id="loginForm">
            <div class="form-group">
                <label for="userType">Type d'utilisateur:</label>
                <select id="userType" onchange="updateCredentials()">
                    <option value="admin">Admin</option>
                    <option value="dg">DG</option>
                    <option value="agent">Agent</option>
                    <option value="responsable">Responsable</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" value="admin@csar.sn" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" value="admin123" required>
            </div>
            
            <button type="button" onclick="login()">Se connecter</button>
        </form>
        
        <div id="result" class="result"></div>
    </div>

    <script>
        const credentials = {
            admin: { email: 'admin@csar.sn', password: 'admin123' },
            dg: { email: 'dg@csar.sn', password: 'password123' },
            agent: { email: 'agent@csar.sn', password: 'password123' },
            responsable: { email: 'responsable@csar.sn', password: 'password123' }
        };

        function updateCredentials() {
            const userType = document.getElementById('userType').value;
            const creds = credentials[userType];
            document.getElementById('email').value = creds.email;
            document.getElementById('password').value = creds.password;
        }

        function showResult(message, isSuccess) {
            const result = document.getElementById('result');
            result.className = 'result ' + (isSuccess ? 'success' : 'error');
            result.textContent = message;
            result.style.display = 'block';
        }

        async function testRoute() {
            try {
                const response = await fetch('/test-login');
                const data = await response.json();
                showResult('Route de test: ' + data.message, true);
            } catch (error) {
                showResult('Erreur route de test: ' + error.message, false);
            }
        }

        async function login() {
            const userType = document.getElementById('userType').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const routes = {
                admin: '/simple-admin-login',
                dg: '/simple-dg-login',
                agent: '/simple-agent-login',
                responsable: '/simple-responsable-login'
            };

            try {
                const response = await fetch(routes[userType], {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
                });

                const data = await response.json();
                
                if (data.success) {
                    showResult(`✅ Connexion ${userType} réussie! Redirection vers: ${data.redirect}`, true);
                    // Rediriger après 2 secondes
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    showResult(`❌ Erreur: ${data.message}`, false);
                }
            } catch (error) {
                showResult(`❌ Erreur de connexion: ${error.message}`, false);
            }
        }

        async function testAdminLogin() {
            document.getElementById('userType').value = 'admin';
            updateCredentials();
            await login();
        }

        async function testDGLogin() {
            document.getElementById('userType').value = 'dg';
            updateCredentials();
            await login();
        }

        async function testAgentLogin() {
            document.getElementById('userType').value = 'agent';
            updateCredentials();
            await login();
        }

        async function testResponsableLogin() {
            document.getElementById('userType').value = 'responsable';
            updateCredentials();
            await login();
        }

        // Test automatique au chargement
        window.onload = function() {
            testRoute();
        };
    </script>
</body>
</html>






