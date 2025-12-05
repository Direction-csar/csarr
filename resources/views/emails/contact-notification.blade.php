<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau message de contact - CSAR</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8fafc; }
        .alert { background: #fef2f2; border: 1px solid #fecaca; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .info-box { background: #e0f2fe; padding: 15px; border-left: 4px solid #0284c7; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 NOUVEAU MESSAGE DE CONTACT</h1>
        </div>
        
        <div class="content">
            <div class="alert">
                <h2>⚠️ Action requise</h2>
                <p>Un nouveau message de contact a été reçu sur le site CSAR et nécessite votre attention.</p>
            </div>
            
            <h2>📋 Informations du contact :</h2>
            
            <div class="info-box">
                <p><strong>👤 Nom :</strong> {{ $name }}</p>
                <p><strong>📧 Email :</strong> {{ $email }}</p>
                @if($phone)
                <p><strong>📞 Téléphone :</strong> {{ $phone }}</p>
                @endif
                @if($subject)
                <p><strong>📝 Sujet :</strong> {{ $subject }}</p>
                @endif
                <p><strong>📅 Date :</strong> {{ $date }}</p>
            </div>
            
            <h3>💬 Message :</h3>
            <div style="background: white; padding: 15px; border: 1px solid #d1d5db; border-radius: 5px;">
                {{ $message }}
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: #f0f9ff; border-radius: 5px;">
                <h3>📋 Actions recommandées :</h3>
                <ul>
                    <li>Répondre au contact dans les 24h</li>
                    <li>Marquer le message comme traité dans l'interface admin</li>
                    <li>Archiver la conversation si nécessaire</li>
                </ul>
            </div>
            
            <p style="margin-top: 20px;">
                <a href="{{ url('/admin/contacts') }}" style="background: #1e40af; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                    📥 Accéder à l'interface admin
                </a>
            </p>
        </div>
    </div>
</body>
</html>

