/**
 * Système de validation frontend pour la plateforme CSAR
 * Validation stricte des formulaires côté client
 */

class CSARValidation {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupFormValidation();
    }

    bindEvents() {
        // Validation en temps réel pour tous les champs
        document.addEventListener('input', (e) => {
            if (e.target.matches('input, select, textarea')) {
                this.validateField(e.target);
            }
        });

        // Validation à la soumission des formulaires
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.matches('form')) {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            }
        });
    }

    setupFormValidation() {
        // Ajouter les attributs HTML5 pour la validation
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            this.addValidationAttributes(form);
        });
    }

    addValidationAttributes(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            const fieldName = input.name;
            
            // Validation des champs numériques
            if (this.isNumericField(fieldName)) {
                input.setAttribute('inputmode', 'numeric');
                input.setAttribute('pattern', '[0-9]*');
                input.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });
            }
            
            // Validation des téléphones
            if (this.isPhoneField(fieldName)) {
                input.setAttribute('inputmode', 'tel');
                input.setAttribute('pattern', '[0-9+\\-\\s\\(\\)]{8,15}');
                input.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^0-9+\-\s\(\)]/g, '');
                });
            }
            
            // Validation des textes (lettres uniquement)
            if (this.isTextField(fieldName)) {
                input.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^a-zA-ZÀ-ÿ\s\-\']/g, '');
                });
            }
            
            // Validation des emails
            if (this.isEmailField(fieldName)) {
                input.setAttribute('type', 'email');
                input.setAttribute('pattern', '[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$');
            }
            
            // Validation des mots de passe
            if (this.isPasswordField(fieldName)) {
                this.setupPasswordValidation(input);
            }
            
            // Validation des dates
            if (this.isDateField(fieldName)) {
                input.setAttribute('type', 'date');
            }
        });
    }

    validateField(field) {
        const fieldName = field.name;
        const value = field.value.trim();
        let isValid = true;
        let message = '';

        // Validation des champs numériques
        if (this.isNumericField(fieldName)) {
            isValid = /^[0-9]+(\.[0-9]+)?$/.test(value);
            message = 'Ce champ doit contenir uniquement des chiffres.';
        }
        
        // Validation des téléphones
        else if (this.isPhoneField(fieldName)) {
            isValid = /^[0-9+\-\s\(\)]{8,15}$/.test(value);
            message = 'Format de téléphone invalide (8-15 chiffres).';
        }
        
        // Validation des textes
        else if (this.isTextField(fieldName)) {
            isValid = /^[a-zA-ZÀ-ÿ\s\-\']+$/.test(value);
            message = 'Ce champ ne peut contenir que des lettres, espaces, tirets et apostrophes.';
        }
        
        // Validation des emails
        else if (this.isEmailField(fieldName)) {
            isValid = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value);
            message = 'Format d\'email invalide.';
        }
        
        // Validation des mots de passe
        else if (this.isPasswordField(fieldName)) {
            const result = this.validatePassword(value);
            isValid = result.isValid;
            message = result.message;
        }

        this.showFieldValidation(field, isValid, message);
        return isValid;
    }

    validateForm(form) {
        const fields = form.querySelectorAll('input, select, textarea');
        let isFormValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            this.showNotification('Veuillez corriger les erreurs dans le formulaire.', 'error');
        }

        return isFormValid;
    }

    validatePassword(password) {
        const minLength = 8;
        const hasLower = /[a-z]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[@$!%*?&]/.test(password);

        if (password.length < minLength) {
            return { isValid: false, message: `Le mot de passe doit contenir au moins ${minLength} caractères.` };
        }
        if (!hasLower) {
            return { isValid: false, message: 'Le mot de passe doit contenir au moins une lettre minuscule.' };
        }
        if (!hasUpper) {
            return { isValid: false, message: 'Le mot de passe doit contenir au moins une lettre majuscule.' };
        }
        if (!hasNumber) {
            return { isValid: false, message: 'Le mot de passe doit contenir au moins un chiffre.' };
        }
        if (!hasSpecial) {
            return { isValid: false, message: 'Le mot de passe doit contenir au moins un caractère spécial (@$!%*?&).' };
        }

        return { isValid: true, message: 'Mot de passe valide.' };
    }

    setupPasswordValidation(input) {
        const strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'password-strength';
        strengthIndicator.style.marginTop = '5px';
        strengthIndicator.style.fontSize = '12px';
        
        input.parentNode.appendChild(strengthIndicator);

        input.addEventListener('input', (e) => {
            const result = this.validatePassword(e.target.value);
            strengthIndicator.textContent = result.message;
            strengthIndicator.className = `password-strength ${result.isValid ? 'valid' : 'invalid'}`;
        });
    }

    showFieldValidation(field, isValid, message) {
        // Supprimer les anciens messages d'erreur
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }

        // Supprimer les classes d'erreur
        field.classList.remove('border-red-500', 'border-green-500');

        if (field.value.trim() !== '') {
            if (isValid) {
                field.classList.add('border-green-500');
            } else {
                field.classList.add('border-red-500');
                this.showFieldError(field, message);
            }
        }
    }

    showFieldError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error text-red-500 text-xs mt-1';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'error' ? 'bg-red-500 text-white' : 
            type === 'success' ? 'bg-green-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Méthodes utilitaires pour identifier les types de champs
    isNumericField(fieldName) {
        const numericFields = ['salaire_brut', 'prime', 'deduction', 'quantite', 'prix', 'montant', 'nombre'];
        return numericFields.some(field => fieldName.includes(field));
    }

    isPhoneField(fieldName) {
        const phoneFields = ['telephone', 'phone', 'mobile', 'portable'];
        return phoneFields.some(field => fieldName.includes(field));
    }

    isTextField(fieldName) {
        const textFields = ['nom', 'prenom', 'prenoms_nom', 'lieu_naissance', 'direction', 'poste', 'titre'];
        return textFields.some(field => fieldName.includes(field));
    }

    isEmailField(fieldName) {
        return fieldName.includes('email') || fieldName.includes('mail');
    }

    isPasswordField(fieldName) {
        return fieldName.includes('password') || fieldName.includes('mot_de_passe');
    }

    isDateField(fieldName) {
        const dateFields = ['date', 'naissance', 'embauche', 'emission', 'expiration'];
        return dateFields.some(field => fieldName.includes(field));
    }
}

// Initialiser la validation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    new CSARValidation();
});

// Export pour utilisation dans d'autres fichiers
window.CSARValidation = CSARValidation;
