<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience')</title>
    
    <!-- Meta tags -->
    <meta name="description" content="Plateforme numérique du CSAR - Commissariat à la Sécurité Alimentaire et à la Résilience du Sénégal">
    <meta name="keywords" content="CSAR, sécurité alimentaire, résilience, Sénégal, entrepôts, stocks">
    <meta name="author" content="CSAR">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/csar-logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/csar-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/csar-logo.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">
    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center">
        @yield('content')
    </main>

    <!-- Footer -->


    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html> 