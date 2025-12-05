@extends('layouts.public')
@section('title', 'Test Formulaire Simple - CSAR')
@section('content')

<div style="max-width:650px;margin:40px auto;background:#fff;border-radius:18px;box-shadow:0 4px 24px rgba(0,0,0,0.07);padding:38px 24px;">
    <h1>Test Formulaire Simplifié</h1>
    
    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:18px;border-radius:8px;margin-bottom:22px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:14px;border-radius:8px;margin-bottom:20px;">
            <ul style="margin:0;padding-left:1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('demande.store') }}">
        @csrf
        <input type="hidden" name="type_demande" value="aide_alimentaire">
        
        <div style="margin-bottom:16px;">
            <label>Nom *</label>
            <input type="text" name="nom" required value="{{ old('nom') }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>Prénom *</label>
            <input type="text" name="prenom" required value="{{ old('prenom') }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>Email *</label>
            <input type="email" name="email" required value="{{ old('email') }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>Téléphone *</label>
            <input type="text" name="telephone" required value="{{ old('telephone') }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>Objet *</label>
            <input type="text" name="objet" required value="{{ old('objet') }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>Description *</label>
            <textarea name="description" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;height:80px;">{{ old('description') }}</textarea>
        </div>
        
        <!-- Géolocalisation simple -->
        <div style="margin-bottom:16px;">
            <label>Latitude *</label>
            <input type="text" name="latitude" required value="14.6928" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>Longitude *</label>
            <input type="text" name="longitude" required value="-17.4467" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>Adresse *</label>
            <input type="text" name="address" required value="Dakar, Sénégal" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>Région *</label>
            <input type="text" name="region" required value="Dakar" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        </div>
        
        <div style="margin-bottom:16px;">
            <label>
                <input type="checkbox" name="consentement" value="1" required> J'accepte le traitement de mes données
            </label>
        </div>
        
        <button type="submit" style="background:#0d9488;color:#fff;padding:12px 24px;border:none;border-radius:6px;cursor:pointer;font-weight:600;">
            ENVOYER TEST SIMPLE
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page chargée - formulaire simple');
    
    const form = document.querySelector('form');
    if (form) {
        console.log('Formulaire trouvé');
        form.addEventListener('submit', function(e) {
            console.log('SOUMISSION DU FORMULAIRE DÉTECTÉE !');
            const btn = e.target.querySelector('button[type="submit"]');
            if (btn) {
                btn.innerHTML = '⏳ Envoi...';
                btn.disabled = true;
            }
        });
    } else {
        console.error('Formulaire non trouvé !');
    }
});
</script>

@endsection










