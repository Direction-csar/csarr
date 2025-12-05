<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact - CSAR Platform</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .message-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
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
        .message-content {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            margin-top: 15px;
            font-style: italic;
            color: #495057;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .priority-badge {
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Nouveau message de contact</h1>
            <p>CSAR Platform - Notification automatique</p>
        </div>
        
        <div class="content">
            <p>Bonjour,</p>
            
            <p>Un nouveau message de contact a été reçu via la plateforme publique du CSAR.</p>
            
            <div class="message-details">
                <div class="detail-row">
                    <div class="detail-label">👤 Expéditeur :</div>
                    <div class="detail-value">{{ $contact->name ?? 'Non renseigné' }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">📧 Email :</div>
                    <div class="detail-value">{{ $contact->email }}</div>
                </div>
                
                @if($contact->phone)
                <div class="detail-row">
                    <div class="detail-label">📞 Téléphone :</div>
                    <div class="detail-value">{{ $contact->phone }}</div>
                </div>
                @endif
                
                <div class="detail-row">
                    <div class="detail-label">📋 Sujet :</div>
                    <div class="detail-value">{{ $contact->subject }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">⏰ Reçu le :</div>
                    <div class="detail-value">{{ $contact->created_at->format('d/m/Y à H:i') }}</div>
                </div>
                
                <div class="message-content">
                    <strong>Message :</strong><br>
                    {{ $contact->message }}
                </div>
            </div>
            
            <p><strong>Action requise :</strong> Veuillez consulter le message dans l'interface d'administration et y répondre si nécessaire.</p>
            
            <div style="text-align: center;">
                <a href="{{ route('admin.messages.index') }}" class="btn">
                    📬 Consulter les messages
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
