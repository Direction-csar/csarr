<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel abonnement à la newsletter CSAR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #00a86b 0%, #00c851 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .subscriber-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #00a86b;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #00a86b;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 Nouvel abonnement à la newsletter CSAR</h1>
        <p>Commissariat à la Sécurité Alimentaire et à la Résilience</p>
    </div>
    
    <div class="content">
        <h2>Bonjour,</h2>
        
        <p>Une nouvelle personne s'est abonnée à la newsletter du CSAR :</p>
        
        <div class="subscriber-info">
            <h3>Informations de l'abonné :</h3>
            <p><strong>Email :</strong> {{ $subscriber->email }}</p>
            @if($subscriber->name)
                <p><strong>Nom :</strong> {{ $subscriber->name }}</p>
            @endif
            <p><strong>Date d'abonnement :</strong> {{ $subscriber->subscribed_at->format('d/m/Y à H:i') }}</p>
            <p><strong>Source :</strong> {{ ucfirst(str_replace('_', ' ', $subscriber->source)) }}</p>
        </div>
        
        <p>Vous pouvez gérer les abonnés depuis l'interface d'administration du CSAR.</p>
        
        <p><strong>Total des abonnés actifs :</strong> {{ \App\Models\NewsletterSubscriber::active()->count() }}</p>
        
        <div class="footer">
            <p>Ce message a été envoyé automatiquement par le système CSAR.</p>
            <p>Commissariat à la Sécurité Alimentaire et à la Résilience</p>
        </div>
    </div>
</body>
</html>

