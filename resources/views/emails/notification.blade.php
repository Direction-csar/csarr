<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $notification->title }} - CSAR</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, {{ $color }}, {{ $color }}dd);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: {{ $color }};
        }
        .content {
            padding: 30px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .message {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
            color: #4b5563;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 0;
            font-size: 14px;
            color: #6b7280;
        }
        .type-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background: {{ $color }}20;
            color: {{ $color }};
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CSAR</div>
            <h1 style="margin: 0; font-size: 28px;">Plateforme CSAR</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">Sécurité Alimentaire et Résilience</p>
        </div>
        
        <div class="content">
            <div class="type-badge">{{ ucfirst($notification->type) }}</div>
            <h2 class="title">{{ $notification->title }}</h2>
            <div class="message">
                {!! nl2br(e($notification->message)) !!}
            </div>
            
            <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin-top: 30px;">
                <p style="margin: 0; font-size: 14px; color: #6b7280;">
                    <strong>Date d'envoi:</strong> {{ $notification->sent_at->format('d/m/Y à H:i') }}
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>Cette notification a été envoyée par la Plateforme CSAR</p>
            <p>Pour toute question, contactez-nous à <a href="mailto:contact@csar.sn" style="color: {{ $color }};">contact@csar.sn</a></p>
        </div>
    </div>
</body>
</html>






