<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation - CSAR Platform</title>
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
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
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
        .confirmation-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #51cf66;
            text-align: center;
        }
        .success-icon {
            font-size: 48px;
            color: #51cf66;
            margin-bottom: 15px;
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
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .info-box {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Confirmation reçue</h1>
            <p>CSAR Platform - Centre de Suivi et d'Analyse des Risques</p>
        </div>
        
        <div class="content">
            <p>Bonjour,</p>
            
            @if($type === 'contact')
                <div class="confirmation-box">
                    <div class="success-icon">📬</div>
                    <h3 style="margin: 0 0 10px 0; color: #51cf66;">Message transmis avec succès</h3>
                    <p style="margin: 0;">Votre message a bien été transmis à l'équipe du CSAR.</p>
                </div>
                
                <p>Nous avons bien reçu votre message et nous vous remercions de nous avoir contactés. Notre équipe examinera votre demande et vous répondra dans les plus brefs délais.</p>
                
                <div class="info-box">
                    <h4 style="margin: 0 0 10px 0; color: #1976d2;">📋 Détails de votre message</h4>
                    @if(isset($data['subject']))
                        <p><strong>Sujet :</strong> {{ $data['subject'] }}</p>
                    @endif
                    @if(isset($data['name']))
                        <p><strong>Nom :</strong> {{ $data['name'] }}</p>
                    @endif
                    <p><strong>Date d'envoi :</strong> {{ now()->format('d/m/Y à H:i') }}</p>
                </div>
                
            @elseif($type === 'newsletter')
                <div class="confirmation-box">
                    <div class="success-icon">📧</div>
                    <h3 style="margin: 0 0 10px 0; color: #51cf66;">Abonnement confirmé</h3>
                    <p style="margin: 0;">Votre abonnement à la newsletter du CSAR a été enregistré.</p>
                </div>
                
                <p>Merci de vous être abonné à notre newsletter ! Vous recevrez désormais nos dernières actualités, rapports et informations importantes du Centre de Suivi et d'Analyse des Risques.</p>
                
                <div class="info-box">
                    <h4 style="margin: 0 0 10px 0; color: #1976d2;">📊 À propos de notre newsletter</h4>
                    <ul style="margin: 0; padding-left: 20px;">
                        <li>Actualités et événements du CSAR</li>
                        <li>Rapports d'analyse des risques</li>
                        <li>Alertes et recommandations</li>
                        <li>Publications et ressources</li>
                    </ul>
                </div>
                
            @elseif($type === 'request')
                <div class="confirmation-box">
                    <div class="success-icon">🚨</div>
                    <h3 style="margin: 0 0 10px 0; color: #51cf66;">Demande enregistrée</h3>
                    <p style="margin: 0;">Votre demande d'aide a bien été enregistrée.</p>
                </div>
                
                <p>Nous avons bien reçu votre demande d'aide. Notre équipe spécialisée examinera votre situation et vous contactera pour vous apporter l'assistance nécessaire.</p>
                
            @else
                <div class="confirmation-box">
                    <div class="success-icon">✅</div>
                    <h3 style="margin: 0 0 10px 0; color: #51cf66;">Action confirmée</h3>
                    <p style="margin: 0;">Votre demande a bien été traitée.</p>
                </div>
            @endif
            
            <p><strong>Prochaines étapes :</strong></p>
            <ul>
                <li>Notre équipe examinera votre demande</li>
                <li>Vous recevrez une réponse dans les plus brefs délais</li>
                <li>Pour toute urgence, contactez-nous directement</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">
                    🏠 Retour à l'accueil
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>CSAR Platform</strong> - Centre de Suivi et d'Analyse des Risques</p>
            <p>📧 Email : contact@csar.sn | 📞 Téléphone : +221 XX XXX XX XX</p>
            <p>🌐 Site web : <a href="{{ url('/') }}" style="color: #51cf66;">www.csar.sn</a></p>
            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Cet email a été envoyé automatiquement. Ne pas répondre à cet email.<br>
                Pour toute question, utilisez notre formulaire de contact.
            </p>
        </div>
    </div>
</body>
</html>
