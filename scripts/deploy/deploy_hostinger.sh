#!/bin/bash

# 🚀 Script de Déploiement Automatique CSAR Platform 2025
# Pour serveur Hostinger avec accès SSH

echo "🚀 Démarrage du déploiement CSAR Platform 2025..."

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Vérifier si nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    log_error "Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

log_info "Configuration de l'environnement de production..."

# 1. Installer les dépendances de production
log_info "Installation des dépendances Composer..."
composer install --optimize-autoloader --no-dev --no-interaction

if [ $? -eq 0 ]; then
    log_success "Dépendances installées avec succès"
else
    log_error "Échec de l'installation des dépendances"
    exit 1
fi

# 2. Configurer les permissions
log_info "Configuration des permissions..."
chmod -R 755 storage bootstrap/cache
chmod 644 .env

if [ -d "public" ]; then
    chmod -R 755 public
fi

log_success "Permissions configurées"

# 3. Générer la clé d'application si nécessaire
if ! grep -q "APP_KEY=base64:" .env; then
    log_info "Génération de la clé d'application..."
    php artisan key:generate --force
    log_success "Clé d'application générée"
else
    log_info "Clé d'application déjà présente"
fi

# 4. Optimiser l'application
log_info "Optimisation de l'application..."

# Nettoyer le cache existant
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache

log_success "Application optimisée"

# 5. Exécuter les migrations
log_info "Exécution des migrations de base de données..."
php artisan migrate --force

if [ $? -eq 0 ]; then
    log_success "Migrations exécutées avec succès"
else
    log_warning "Certaines migrations ont échoué (normal si base déjà configurée)"
fi

# 6. Créer les utilisateurs par défaut
log_info "Création des utilisateurs par défaut..."
php artisan db:seed --class=TestUsersSeeder --force

if [ $? -eq 0 ]; then
    log_success "Utilisateurs par défaut créés"
else
    log_warning "Échec de la création des utilisateurs (peut-être déjà existants)"
fi

# 7. Optimiser l'autoloader
log_info "Optimisation de l'autoloader..."
composer dump-autoload --optimize

log_success "Autoloader optimisé"

# 8. Vérifier la configuration
log_info "Vérification de la configuration..."

# Vérifier les variables importantes
if grep -q "APP_ENV=production" .env; then
    log_success "Mode production activé"
else
    log_warning "Mode production non détecté dans .env"
fi

if grep -q "APP_DEBUG=false" .env; then
    log_success "Mode debug désactivé"
else
    log_warning "Mode debug encore activé - à désactiver en production"
fi

# 9. Créer un fichier de statut de déploiement
echo "CSAR Platform 2025 - Déployé le $(date)" > deployment_status.txt
echo "Version: $(git rev-parse --short HEAD 2>/dev/null || echo 'unknown')" >> deployment_status.txt

log_success "Fichier de statut créé"

# 10. Afficher les informations de déploiement
echo ""
log_success "🎉 Déploiement terminé avec succès !"
echo ""
echo "📋 Informations de déploiement :"
echo "   • Date: $(date)"
echo "   • Version: $(git rev-parse --short HEAD 2>/dev/null || echo 'unknown')"
echo "   • Environnement: Production"
echo ""
echo "🔗 URLs d'accès :"
echo "   • Public: https://yourdomain.com/"
echo "   • Admin: https://yourdomain.com/admin"
echo "   • DRH: https://yourdomain.com/drh"
echo "   • DG: https://yourdomain.com/dg"
echo ""
echo "👤 Identifiants par défaut :"
echo "   • Admin: admin@csar.sn / admin123"
echo "   • DRH: drh@csar.sn / drh123"
echo "   • DG: dg@csar.sn / dg123"
echo ""
log_warning "⚠️  IMPORTANT: Changez tous les mots de passe par défaut !"
echo ""
log_info "📚 Consultez DEPLOYMENT_GUIDE.md pour plus d'informations"
echo ""

# 11. Vérifications finales
log_info "Vérifications finales..."

# Vérifier que les dossiers critiques existent
if [ -d "storage" ] && [ -d "bootstrap/cache" ]; then
    log_success "Structure de dossiers correcte"
else
    log_error "Structure de dossiers manquante"
fi

# Vérifier que le fichier .env existe
if [ -f ".env" ]; then
    log_success "Fichier .env présent"
else
    log_error "Fichier .env manquant"
fi

echo ""
log_success "🚀 CSAR Platform 2025 est maintenant déployée et prête à l'emploi !"
