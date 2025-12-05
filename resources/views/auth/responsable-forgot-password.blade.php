<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Responsable CSAR</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 24px;
            margin: 0;
        }
        
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 480px;
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
        }
        
        h1 {
            font-size: 28px;
            margin: 0 0 8px 0;
            color: #1f2937;
            text-align: center;
            font-weight: 700;
        }
        
        .subtitle {
            color: #6b7280;
            font-size: 16px;
            margin: 0 0 30px 0;
            text-align: center;
            line-height: 1.5;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }
        
        input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #f9fafb;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: #ef4444;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 15px 20px;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            border: none;
            color: #fff;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }
        
        .btn:active {
            transform: translateY(0);
        }
        
        .status {
            background: #ecfeff;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            color: #ef4444;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            padding: 10px 15px;
            border-radius: 8px;
            background: #f8fafc;
        }
        
        .back:hover {
            background: #e2e8f0;
            color: #dc2626;
            transform: translateX(-3px);
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">
            <img src="{{ asset('images/logos/LOGO CSAR vectoriel-01.png') }}" alt="CSAR Logo">
        </div>
        
        <h1>Mot de passe oublié</h1>
        <p class="subtitle">Entrez votre adresse email. Nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

        @if (session('status'))
            <div class="status">
                <i class="fas fa-check-circle"></i>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Adresse email</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="responsable@csar.sn">
                </div>
            </div>
            
            <button class="btn" type="submit">
                <i class="fas fa-paper-plane" style="margin-right: 8px;"></i>
                Envoyer le lien
            </button>
        </form>

        <a class="back" href="{{ $backUrl ?? '/responsable/login' }}">
            <i class="fas fa-arrow-left"></i>
            Retour à la connexion
        </a>
        
        <div class="footer">
            © {{ date('Y') }} CSAR - Tous droits réservés
        </div>
    </div>
</body>
</html>








