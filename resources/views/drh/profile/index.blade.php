@extends('layouts.drh')

@section('title', 'Mon Profil')

@section('content')
<div class="drh-interface">
    <div class="drh-page-title">
        <h1>Mon Profil</h1>
        <p>Gérez vos informations personnelles</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations du profil -->
        <div class="lg:col-span-2">
            <div class="drh-card drh-fade-in">
                <div class="drh-card-header">
                    <div class="drh-card-title">
                        <i class="fas fa-user-edit"></i>
                        Informations Personnelles
                    </div>
                </div>
                <div class="drh-card-body">
                    <form action="{{ route('drh.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="drh-form-group">
                                <label class="drh-form-label required">Nom complet</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                       class="drh-form-input @error('name') error @enderror" required>
                                @error('name')
                                    <div class="drh-form-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="drh-form-group">
                                <label class="drh-form-label required">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                       class="drh-form-input @error('email') error @enderror" required>
                                @error('email')
                                    <div class="drh-form-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="drh-form-group">
                                <label class="drh-form-label">Téléphone</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" 
                                       class="drh-form-input @error('phone') error @enderror">
                                @error('phone')
                                    <div class="drh-form-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="drh-form-group">
                                <label class="drh-form-label">Photo de profil</label>
                                <input type="file" name="avatar" accept="image/*" 
                                       class="drh-form-input @error('avatar') error @enderror">
                                @error('avatar')
                                    <div class="drh-form-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="drh-form-help">
                                    Formats acceptés : JPEG, PNG, JPG, GIF (max 2MB)
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="drh-btn drh-btn-primary">
                                <i class="fas fa-save"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Avatar et informations -->
        <div class="lg:col-span-1">
            <div class="drh-card drh-fade-in">
                <div class="drh-card-header">
                    <div class="drh-card-title">
                        <i class="fas fa-user-circle"></i>
                        Photo de Profil
                    </div>
                </div>
                <div class="drh-card-body text-center">
                    <div class="mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/avatars/' . $user->avatar) }}" 
                                 alt="Avatar" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-200">
                        @else
                            <div class="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center border-4 border-gray-200">
                                <i class="fas fa-user text-4xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="drh-heading-4 mb-2">{{ $user->name }}</h3>
                    <p class="drh-text-small text-gray-500 mb-4">{{ $user->email }}</p>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="drh-text-small">Rôle :</span>
                            <span class="drh-badge drh-badge-primary">{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="drh-text-small">Membre depuis :</span>
                            <span class="drh-text-small">{{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changement de mot de passe -->
            <div class="drh-card drh-fade-in mt-6">
                <div class="drh-card-header">
                    <div class="drh-card-title">
                        <i class="fas fa-lock"></i>
                        Sécurité
                    </div>
                </div>
                <div class="drh-card-body">
                    <p class="drh-text-small text-gray-500 mb-4">
                        Changez votre mot de passe régulièrement pour maintenir la sécurité de votre compte.
                    </p>
                    <a href="{{ route('password.request') }}" class="drh-btn drh-btn-secondary drh-btn-sm drh-btn-block">
                        <i class="fas fa-key"></i>
                        Changer le mot de passe
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

