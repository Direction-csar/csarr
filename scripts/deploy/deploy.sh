#!/bin/bash

# Script de déploiement pour CSAR Platform
# Usage: ./deploy.sh [environment]

set -e

# Configuration
ENVIRONMENT=${1:-production}
PROJECT_DIR="/var/www/csar"
BACKUP_DIR="/var/backups/csar"
LOG_FILE="/var/log/csar-deploy.log"

# Couleurs pour les logs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction de logging
log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a $LOG_FILE
}

log_success() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] ✅ $1${NC}" | tee -a $LOG_FILE
}

log_warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] ⚠️  $1${NC}" | tee -a $LOG_FILE
}

log_error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ❌ $1${NC}" | tee -a $LOG_FILE
}

# Vérifier les prérequis
check_prerequisites() {
    log "Vérification des prérequis..."
    
    if [ ! -d "$PROJECT_DIR" ]; then
        log_error "Le répertoire du projet n'existe pas: $PROJECT_DIR"
        exit 1
    fi
    
    if ! command -v php &> /dev/null; then
        log_error "PHP n'est pas installé"
        exit 1
    fi
    
    if ! command -v composer &> /dev/null; then
        log_error "Composer n'est pas installé"
        exit 1
    fi
    
    log_success "Prérequis vérifiés"
}

# Créer une sauvegarde
create_backup() {
    log "Création d'une sauvegarde..."
    
    BACKUP_NAME="csar-backup-$(date +%Y%m%d-%H%M%S)"
    BACKUP_PATH="$BACKUP_DIR/$BACKUP_NAME"
    
    mkdir -p "$BACKUP_DIR"
    mkdir -p "$BACKUP_PATH"
    
    # Sauvegarder les fichiers
    cp -r "$PROJECT_DIR"/* "$BACKUP_PATH/"
    
    # Sauvegarder la base de données
    if [ -f "$PROJECT_DIR/database/database.sqlite" ]; then
        cp "$PROJECT_DIR/database/database.sqlite" "$BACKUP_PATH/database/"
    fi
    
    log_success "Sauvegarde créée: $BACKUP_PATH"
}

# Mettre à jour le code
update_code() {
    log "Mise à jour du code..."
    
    cd "$PROJECT_DIR"
    
    # Mettre à jour depuis Git
    if [ -d ".git" ]; then
        git fetch origin
        git reset --hard origin/main
        log_success "Code mis à jour depuis Git"
    else
        log_warning "Pas de repository Git détecté"
    fi
}

# Installer les dépendances
install_dependencies() {
    log "Installation des dépendances..."
    
    cd "$PROJECT_DIR"
    
    # Installer les dépendances Composer
    composer install --no-dev --optimize-autoloader
    
    # Installer les dépendances NPM (si package.json existe)
    if [ -f "package.json" ]; then
        npm ci --production
        npm run build
    fi
    
    log_success "Dépendances installées"
}

# Optimiser l'application
optimize_application() {
    log "Optimisation de l'application..."
    
    cd "$PROJECT_DIR"
    
    # Vider les caches
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    # Recréer les caches optimisés
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Optimiser l'autoloader
    composer dump-autoload --optimize
    
    log_success "Application optimisée"
}

# Optimiser les images
optimize_images() {
    log "Optimisation des images..."
    
    cd "$PROJECT_DIR"
    
    if [ -f "scripts/optimize_images.php" ]; then
        php scripts/optimize_images.php
        log_success "Images optimisées"
    else
        log_warning "Script d'optimisation des images non trouvé"
    fi
}

# Mettre à jour les permissions
update_permissions() {
    log "Mise à jour des permissions..."
    
    # Permissions pour les dossiers de stockage
    chmod -R 755 "$PROJECT_DIR/storage"
    chmod -R 755 "$PROJECT_DIR/bootstrap/cache"
    
    # Permissions pour les fichiers de configuration
    chmod 644 "$PROJECT_DIR/.env"
    
    # Propriétaire
    chown -R www-data:www-data "$PROJECT_DIR"
    
    log_success "Permissions mises à jour"
}

# Redémarrer les services
restart_services() {
    log "Redémarrage des services..."
    
    # Redémarrer Apache
    systemctl restart apache2
    
    # Redémarrer PHP-FPM (si utilisé)
    if systemctl is-active --quiet php8.1-fpm; then
        systemctl restart php8.1-fpm
    fi
    
    # Redémarrer les workers de queue (si configurés)
    if systemctl is-active --quiet csar-worker; then
        systemctl restart csar-worker
    fi
    
    log_success "Services redémarrés"
}

# Vérifier la santé de l'application
health_check() {
    log "Vérification de la santé de l'application..."
    
    # Vérifier que Apache fonctionne
    if ! systemctl is-active --quiet apache2; then
        log_error "Apache n'est pas actif"
        return 1
    fi
    
    # Vérifier que l'application répond
    if ! curl -f -s "http://localhost" > /dev/null; then
        log_error "L'application ne répond pas"
        return 1
    fi
    
    # Vérifier les logs d'erreur
    if [ -f "/var/log/apache2/error.log" ]; then
        ERROR_COUNT=$(tail -n 100 /var/log/apache2/error.log | grep -c "ERROR" || true)
        if [ "$ERROR_COUNT" -gt 10 ]; then
            log_warning "Nombre élevé d'erreurs dans les logs Apache: $ERROR_COUNT"
        fi
    fi
    
    log_success "Application en bonne santé"
}

# Nettoyer les anciennes sauvegardes
cleanup_backups() {
    log "Nettoyage des anciennes sauvegardes..."
    
    # Garder seulement les 5 dernières sauvegardes
    if [ -d "$BACKUP_DIR" ]; then
        cd "$BACKUP_DIR"
        ls -t | tail -n +6 | xargs -r rm -rf
        log_success "Anciennes sauvegardes supprimées"
    fi
}

# Fonction principale
main() {
    log "🚀 Début du déploiement CSAR Platform (Environment: $ENVIRONMENT)"
    
    check_prerequisites
    create_backup
    update_code
    install_dependencies
    optimize_application
    optimize_images
    update_permissions
    restart_services
    
    # Attendre un peu pour que les services redémarrent
    sleep 5
    
    if health_check; then
        log_success "🎉 Déploiement réussi !"
        cleanup_backups
        exit 0
    else
        log_error "❌ Échec du déploiement - Vérifiez les logs"
        exit 1
    fi
}

# Gestion des erreurs
trap 'log_error "Script interrompu par l'\''utilisateur"; exit 1' INT TERM

# Exécution
main "$@"


