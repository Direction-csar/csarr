<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel abonnement newsletter - CSAR Platform</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .subscriber-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #4facfe;
        }
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
            margin-right: 15px;
        }
        .detail-value {
            flex: 1;
            color: #212529;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .stats {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Nouvel abonnement newsletter</h1>
            <p>CSAR Platform - Notification automatique</p>
        </div>
        
        <div class="content">
            <p>Bonjour,</p>
            
            <p>Un nouvel abonnement à la newsletter du CSAR a été enregistré.</p>
            
            <div class="subscriber-details">
                <div class="detail-row">
                    <div class="detail-label">📧 Email :</div>
                    <div class="detail-value">{{ $subscriber->email }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">📅 Date d'abonnement :</div>
                    <div class="detail-value">{{ $subscriber->subscribed_at->format('d/m/Y à H:i') }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">📊 Statut :</div>
                    <div class="detail-value">
                        <span style="color: #28a745; font-weight: 600;">✅ Actif</span>
                    </div>
                </div>
                
                @if($subscriber->source)
                <div class="detail-row">
                    <div class="detail-label">🌐 Source :</div>
                    <div class="detail-value">{{ ucfirst($subscriber->source) }}</div>
                </div>
                @endif
            </div>
            
            <div class="stats">
                <h4 style="margin: 0 0 10px 0; color: #1976d2;">📈 Statistiques Newsletter</h4>
                <p style="margin: 0; color: #666;">
                    Total des abonnés : <strong>{{ \App\Models\NewsletterSubscriber::count() }}</strong> |
                    Abonnés actifs : <strong>{{ \App\Models\NewsletterSubscriber::where('status', 'active')->count() }}</strong>
                </p>
            </div>
            
            <p><strong>Action :</strong> L'abonnement a été automatiquement enregistré. Vous pouvez gérer les abonnements depuis l'interface d'administration.</p>
            
            <div style="text-align: center;">
                <a href="{{ route('admin.newsletter.index') }}" class="btn">
                    📊 Gérer la newsletter
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>CSAR Platform</strong> - Centre de Suivi et d'Analyse des Risques</p>
            <p>Cette notification a été générée automatiquement. Ne pas répondre à cet email.</p>
            <p>Pour toute question technique, contactez l'équipe informatique.</p>
        </div>
    </div>
</body>
</html>
