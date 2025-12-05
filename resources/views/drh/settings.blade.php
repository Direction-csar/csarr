@extends('layouts.drh')

@section('title', 'Paramètres')

@section('content')
<div class="drh-interface">
    <div class="drh-page-title">
        <h1>Paramètres</h1>
        <p>Configurez vos préférences et paramètres de l'interface DRH</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Paramètres de notification -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-bell"></i>
                    Notifications
                </div>
            </div>
            <div class="drh-card-body">
                <form action="{{ route('drh.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold text-gray-900">Notifications Email</h4>
                                <p class="drh-text-small text-gray-500">Recevez des notifications par email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications_email" value="1" 
                                       {{ old('notifications_email', Auth::user()->notifications_email ?? true) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold text-gray-900">Notifications SMS</h4>
                                <p class="drh-text-small text-gray-500">Recevez des notifications par SMS</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="notifications_sms" value="1" 
                                       {{ old('notifications_sms', Auth::user()->notifications_sms ?? false) ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="drh-btn drh-btn-primary">
                            <i class="fas fa-save"></i>
                            Enregistrer les paramètres
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Paramètres de l'interface -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-cog"></i>
                    Interface
                </div>
            </div>
            <div class="drh-card-body">
                <form action="{{ route('drh.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div class="drh-form-group">
                            <label class="drh-form-label required">Langue</label>
                            <select name="language" class="drh-form-input @error('language') error @enderror" required>
                                <option value="fr" {{ old('language', Auth::user()->language ?? 'fr') == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ old('language', Auth::user()->language ?? 'fr') == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                            @error('language')
                                <div class="drh-form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="drh-form-group">
                            <label class="drh-form-label required">Fuseau horaire</label>
                            <select name="timezone" class="drh-form-input @error('timezone') error @enderror" required>
                                <option value="Africa/Dakar" {{ old('timezone', Auth::user()->timezone ?? 'Africa/Dakar') == 'Africa/Dakar' ? 'selected' : '' }}>Dakar (GMT+0)</option>
                                <option value="Europe/Paris" {{ old('timezone', Auth::user()->timezone ?? 'Africa/Dakar') == 'Europe/Paris' ? 'selected' : '' }}>Paris (GMT+1)</option>
                                <option value="America/New_York" {{ old('timezone', Auth::user()->timezone ?? 'Africa/Dakar') == 'America/New_York' ? 'selected' : '' }}>New York (GMT-5)</option>
                                <option value="UTC" {{ old('timezone', Auth::user()->timezone ?? 'Africa/Dakar') == 'UTC' ? 'selected' : '' }}>UTC (GMT+0)</option>
                            </select>
                            @error('timezone')
                                <div class="drh-form-error">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="drh-btn drh-btn-primary">
                            <i class="fas fa-save"></i>
                            Enregistrer les paramètres
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Paramètres de sécurité -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-shield-alt"></i>
                    Sécurité
                </div>
            </div>
            <div class="drh-card-body">
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-blue-900">Sécurité du compte</h4>
                                <p class="drh-text-small text-blue-700">Votre compte est protégé par un mot de passe sécurisé</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-semibold text-gray-900">Connexions récentes</h4>
                            <p class="drh-text-small text-gray-500">Dernière connexion : {{ Auth::user()->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-semibold text-gray-900">Sessions actives</h4>
                            <p class="drh-text-small text-gray-500">Gérez vos sessions de connexion</p>
                        </div>
                        <span class="drh-badge drh-badge-success">Actif</span>
                    </div>
                </div>

                <div class="mt-6 space-y-2">
                    <a href="{{ route('password.request') }}" class="drh-btn drh-btn-secondary drh-btn-sm drh-btn-block">
                        <i class="fas fa-key"></i>
                        Changer le mot de passe
                    </a>
                    <a href="#" class="drh-btn drh-btn-danger drh-btn-sm drh-btn-block" onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt"></i>
                        Déconnexion de tous les appareils
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations système -->
        <div class="drh-card drh-fade-in">
            <div class="drh-card-header">
                <div class="drh-card-title">
                    <i class="fas fa-info-circle"></i>
                    Informations Système
                </div>
            </div>
            <div class="drh-card-body">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="drh-text-small text-gray-600">Version de l'application :</span>
                        <span class="drh-text-small font-medium">CSAR v2.0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="drh-text-small text-gray-600">Dernière mise à jour :</span>
                        <span class="drh-text-small font-medium">{{ date('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="drh-text-small text-gray-600">Statut du système :</span>
                        <span class="drh-badge drh-badge-success">Opérationnel</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="drh-text-small text-gray-600">Support technique :</span>
                        <span class="drh-text-small font-medium">support@csar.sn</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="mailto:support@csar.sn" class="drh-btn drh-btn-secondary drh-btn-sm drh-btn-block">
                        <i class="fas fa-envelope"></i>
                        Contacter le support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmLogout() {
    if (confirm('Êtes-vous sûr de vouloir vous déconnecter de tous les appareils ?')) {
        // Implémentation de la déconnexion de tous les appareils
        alert('Fonctionnalité en cours de développement');
    }
}
</script>
@endsection
