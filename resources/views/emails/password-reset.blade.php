<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe – CSAR</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2c5530, #4a7c59);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c5530;
        }
        .message {
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #2c5530, #4a7c59);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        .reset-button:hover {
            background: linear-gradient(135deg, #1e3a21, #3a5c47);
            transform: translateY(-2px);
        }
        .security-info {
            background-color: #f8f9fa;
            border-left: 4px solid #2c5530;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .security-info h3 {
            margin-top: 0;
            color: #2c5530;
            font-size: 16px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .expiry-info {
            font-size: 14px;
            color: #666;
            font-style: italic;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('images/logos/LOGO CSAR vectoriel-01.png') }}" alt="CSAR Logo" class="logo">
            <h1>Plateforme CSAR</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Bonjour {{ $user->name }},
            </div>
            
            <div class="message">
                Vous avez demandé la réinitialisation de votre mot de passe pour accéder à la Plateforme CSAR.
            </div>
            
            <div class="message">
                Cliquez sur le bouton ci-dessous pour définir un nouveau mot de passe :
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Réinitialiser mon mot de passe
                </a>
            </div>
            
            <div class="expiry-info">
                ⏰ Ce lien est valide pendant 60 minutes seulement.
            </div>
            
            <div class="security-info">
                <h3>🔒 Informations de sécurité</h3>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Ce lien est unique et ne peut être utilisé qu'une seule fois</li>
                    <li>Votre demande a été enregistrée dans nos logs de sécurité</li>
                    <li>Si vous n'êtes pas à l'origine de cette demande, ignorez ce message</li>
                </ul>
            </div>
            
            <div class="warning">
                <strong>⚠️ Important :</strong> Si vous n'avez pas demandé cette réinitialisation, ignorez cet email. Votre compte reste sécurisé.
            </div>
            
            <div class="message">
                Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :<br>
                <a href="{{ $resetUrl }}" style="color: #2c5530; word-break: break-all;">{{ $resetUrl }}</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Plateforme CSAR</strong><br>
            Sécurité Alimentaire et Résilience</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>© {{ date('Y') }} CSAR - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>







