@extends('layouts.public')

@section('title', 'Newsletter - CSAR')

@section('content')
<div class="container" style="max-width: 800px; margin: 60px auto; padding: 0 20px;">
    <div class="newsletter-page" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 40px; text-align: center;">
        <div class="newsletter-icon" style="width: 80px; height: 80px; background: linear-gradient(135deg, #00d4aa, #00b894); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;">
            <i class="fas fa-envelope" style="font-size: 32px; color: #fff;"></i>
        </div>
        
        <h1 style="color: #1f2937; font-size: 2.5rem; font-weight: 700; margin-bottom: 20px;">Newsletter CSAR</h1>
        <p style="color: #6b7280; font-size: 1.1rem; margin-bottom: 40px; line-height: 1.6;">
            Restez informé des dernières actualités, rapports et initiatives du Commissariat à la Sécurité Alimentaire et à la Résilience.
        </p>
        
        @if(session('success'))
            <div class="alert alert-success" style="background: #d1fae5; color: #065f46; padding: 12px 20px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid #10b981;">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 12px 20px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid #ef4444;">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('newsletter.subscribe') }}" method="POST" style="max-width: 400px; margin: 0 auto;">
            @csrf
            <div class="form-group" style="margin-bottom: 20px;">
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Votre adresse email" 
                    required
                    value="{{ old('email') }}"
                    style="
                        width: 100%; 
                        padding: 15px 20px; 
                        border: 2px solid #e5e7eb; 
                        border-radius: 8px; 
                        font-size: 16px; 
                        outline: none;
                        transition: border-color 0.3s ease;
                    "
                    onfocus="this.style.borderColor='#00d4aa'"
                    onblur="this.style.borderColor='#e5e7eb'"
                >
                @error('email')
                    <div style="color: #ef4444; font-size: 14px; margin-top: 8px; text-align: left;">{{ $message }}</div>
                @enderror
            </div>
            
            <button 
                type="submit" 
                style="
                    width: 100%; 
                    padding: 15px 20px; 
                    background: linear-gradient(135deg, #00d4aa, #00b894); 
                    color: #fff; 
                    border: none; 
                    border-radius: 8px; 
                    font-size: 16px; 
                    font-weight: 600; 
                    cursor: pointer; 
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                "
                onmouseover="this.style.background='linear-gradient(135deg, #00b894, #00a085)'"
                onmouseout="this.style.background='linear-gradient(135deg, #00d4aa, #00b894)'"
            >
                <i class="fas fa-paper-plane" style="font-size: 16px;"></i>
                S'inscrire à la newsletter
            </button>
        </form>
        
        <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">
                Vous souhaitez vous désinscrire ?
            </p>
            <a href="{{ route('newsletter.unsubscribe') }}" style="color: #00d4aa; text-decoration: none; font-weight: 500;">
                Cliquez ici pour vous désinscrire
            </a>
        </div>
    </div>
</div>
@endsection




