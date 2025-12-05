<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmation de réception - CSAR</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e40af; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8fafc; }
        .footer { background: #64748b; color: white; padding: 15px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; background: #22c55e; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏛️ CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience</h1>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $name }},</h2>
            
            <p>Nous avons bien reçu votre message envoyé le <strong>{{ $date }}</strong>.</p>
            
            <p>Votre demande a été transmise à notre équipe qui vous répondra dans les plus brefs délais.</p>
            
            <div style="background: #e0f2fe; padding: 15px; border-left: 4px solid #0284c7; margin: 20px 0;">
                <h3>📋 Résumé de votre message :</h3>
                <p><strong>Email :</strong> {{ $email }}</p>
                <p><strong>Message :</strong></p>
                <p style="background: white; padding: 10px; border-radius: 5px;">{{ $message }}</p>
            </div>
            
            <p>Nous vous remercions de votre confiance et de votre intérêt pour les activités du CSAR.</p>
            
            <p>Cordialement,<br>
            <strong>L'équipe du CSAR</strong></p>
        </div>
        
        <div class="footer">
            <p>CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience<br>
            📧 contact@csar.sn | 📞 +221 33 123 45 67<br>
            🌐 www.csar.sn</p>
        </div>
    </div>
</body>
</html>

