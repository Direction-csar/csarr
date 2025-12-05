/**
 * Système de validation avancé pour la plateforme CSAR
 * Validation côté client avec feedback en temps réel
 */

class CSARValidator {
    constructor() {
        this.rules = {
            email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
            phone: /^(\+221|221)?[0-9]{9}$/,
            password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
            numeric: /^[0-9]+$/,
            text: /^[a-zA-ZÀ-ÿ\s'-]+$/,
            date: /^\d{4}-\d{2}-\d{2}$/,
            trackingCode: /^CSAR\d{6}$/
        };

        this.messages = {
            email: 'Veuillez entrer une adresse email valide',
            phone: 'Veuillez entrer un numéro de téléphone sénégalais valide (+221XXXXXXXXX)',
            password: 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial',
            numeric: 'Ce champ ne doit contenir que des chiffres',
            text: 'Ce champ ne doit contenir que des lettres',
            date: 'Veuillez entrer une date au format YYYY-MM-DD',
            trackingCode: 'Le code de suivi doit être au format CSAR000000',
            required: 'Ce champ est obligatoire',
            minLength: (min) => `Ce champ doit contenir au moins ${min} caractères`,
            maxLength: (max) => `Ce champ ne doit pas dépasser ${max} caractères`
        };

        this.init();
    }

    init() {
        // Validation en temps réel
        document.addEventListener('input', (e) => {
            if (e.target.matches('[data-validate]')) {
                this.validateField(e.target);
            }
        });

        // Validation à la soumission
        document.addEventListener('submit', (e) => {
            if (e.target.matches('[data-validate-form]')) {
                if (!this.validateForm(e.target)) {
                    e.preventDefault();
                }
            }
        });

        // Validation des champs de mot de passe
        this.initPasswordValidation();
        
        // Validation des champs de téléphone
        this.initPhoneValidation();
        
        // Validation des champs de date
        this.initDateValidation();
    }

    validateField(field) {
        const rules = field.dataset.validate.split('|');
        const value = field.value.trim();
        let isValid = true;
        let message = '';

        // Vérifier les règles
        for (const rule of rules) {
            const [ruleName, ...params] = rule.split(':');
            
            switch (ruleName) {
                case 'required':
                    if (!value) {
                        isValid = false;
                        message = this.messages.required;
                        break;
                    }
                    break;

                case 'email':
                    if (value && !this.rules.email.test(value)) {
                        isValid = false;
                        message = this.messages.email;
                        break;
                    }
                    break;

                case 'phone':
                    if (value && !this.rules.phone.test(value)) {
                        isValid = false;
                        message = this.messages.phone;
                        break;
                    }
                    break;

                case 'password':
                    if (value && !this.rules.password.test(value)) {
                        isValid = false;
                        message = this.messages.password;
                        break;
                    }
                    break;

                case 'numeric':
                    if (value && !this.rules.numeric.test(value)) {
                        isValid = false;
                        message = this.messages.numeric;
                        break;
                    }
                    break;

                case 'text':
                    if (value && !this.rules.text.test(value)) {
                        isValid = false;
                        message = this.messages.text;
                        break;
                    }
                    break;

                case 'date':
                    if (value && !this.rules.date.test(value)) {
                        isValid = false;
                        message = this.messages.date;
                        break;
                    }
                    break;

                case 'tracking_code':
                    if (value && !this.rules.trackingCode.test(value)) {
                        isValid = false;
                        message = this.messages.trackingCode;
                        break;
                    }
                    break;

                case 'min':
                    const min = parseInt(params[0]);
                    if (value && value.length < min) {
                        isValid = false;
                        message = this.messages.minLength(min);
                        break;
                    }
                    break;

                case 'max':
                    const max = parseInt(params[0]);
                    if (value && value.length > max) {
                        isValid = false;
                        message = this.messages.maxLength(max);
                        break;
                    }
                    break;

                case 'confirmed':
                    const confirmField = document.querySelector(`[name="${field.name}_confirmation"]`);
                    if (confirmField && value !== confirmField.value) {
                        isValid = false;
                        message = 'Les deux valeurs ne correspondent pas';
                        break;
                    }
                    break;
            }
        }

        this.showFieldValidation(field, isValid, message);
        return isValid;
    }

    validateForm(form) {
        const fields = form.querySelectorAll('[data-validate]');
        let isFormValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isFormValid = false;
            }
        });

        return isFormValid;
    }

    showFieldValidation(field, isValid, message) {
        // Supprimer les anciens messages
        const existingMessage = field.parentNode.querySelector('.validation-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Supprimer les anciennes classes
        field.classList.remove('is-valid', 'is-invalid');
        field.classList.remove('border-success', 'border-danger');

        if (field.value.trim()) {
            if (isValid) {
                field.classList.add('is-valid', 'border-success');
            } else {
                field.classList.add('is-invalid', 'border-danger');
                
                // Afficher le message d'erreur
                const messageDiv = document.createElement('div');
                messageDiv.className = 'validation-message text-danger small mt-1';
                messageDiv.textContent = message;
                field.parentNode.appendChild(messageDiv);
            }
        }
    }

    initPasswordValidation() {
        const passwordFields = document.querySelectorAll('input[type="password"][data-validate*="password"]');
        
        passwordFields.forEach(field => {
            field.addEventListener('input', () => {
                this.showPasswordStrength(field);
            });
        });
    }

    showPasswordStrength(field) {
        const strengthIndicator = field.parentNode.querySelector('.password-strength') || 
                                 this.createPasswordStrengthIndicator(field);
        
        const value = field.value;
        let strength = 0;
        let strengthText = '';
        let strengthClass = '';

        // Critères de force
        if (value.length >= 8) strength++;
        if (/[a-z]/.test(value)) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/\d/.test(value)) strength++;
        if (/[@$!%*?&]/.test(value)) strength++;

        switch (strength) {
            case 0:
            case 1:
                strengthText = 'Très faible';
                strengthClass = 'danger';
                break;
            case 2:
                strengthText = 'Faible';
                strengthClass = 'warning';
                break;
            case 3:
                strengthText = 'Moyen';
                strengthClass = 'info';
                break;
            case 4:
                strengthText = 'Fort';
                strengthClass = 'success';
                break;
            case 5:
                strengthText = 'Très fort';
                strengthClass = 'success';
                break;
        }

        strengthIndicator.innerHTML = `
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-${strengthClass}" style="width: ${(strength / 5) * 100}%"></div>
            </div>
            <small class="text-${strengthClass}">${strengthText}</small>
        `;
    }

    createPasswordStrengthIndicator(field) {
        const indicator = document.createElement('div');
        indicator.className = 'password-strength mt-2';
        field.parentNode.appendChild(indicator);
        return indicator;
    }

    initPhoneValidation() {
        const phoneFields = document.querySelectorAll('input[data-validate*="phone"]');
        
        phoneFields.forEach(field => {
            field.addEventListener('input', (e) => {
                // Formatage automatique du numéro
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.startsWith('221')) {
                    value = '+' + value;
                } else if (value.startsWith('0')) {
                    value = '+221' + value.substring(1);
                } else if (!value.startsWith('+221') && value.length > 0) {
                    value = '+221' + value;
                }
                
                e.target.value = value;
            });
        });
    }

    initDateValidation() {
        const dateFields = document.querySelectorAll('input[type="date"]');
        
        dateFields.forEach(field => {
            field.addEventListener('change', () => {
                const value = field.value;
                const today = new Date().toISOString().split('T')[0];
                
                // Vérifier que la date n'est pas dans le futur (pour certaines validations)
                if (field.dataset.validate && field.dataset.validate.includes('not_future')) {
                    if (value > today) {
                        this.showFieldValidation(field, false, 'La date ne peut pas être dans le futur');
                    }
                }
                
                // Vérifier que la date n'est pas trop ancienne
                if (field.dataset.validate && field.dataset.validate.includes('not_old')) {
                    const minDate = new Date();
                    minDate.setFullYear(minDate.getFullYear() - 100);
                    const minDateStr = minDate.toISOString().split('T')[0];
                    
                    if (value < minDateStr) {
                        this.showFieldValidation(field, false, 'La date est trop ancienne');
                    }
                }
            });
        });
    }

    // Méthode pour valider un code de suivi
    validateTrackingCode(code) {
        return this.rules.trackingCode.test(code);
    }

    // Méthode pour générer un code de suivi
    generateTrackingCode() {
        const random = Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
        return `CSAR${random}`;
    }

    // Méthode pour formater un numéro de téléphone
    formatPhoneNumber(phone) {
        const cleaned = phone.replace(/\D/g, '');
        
        if (cleaned.startsWith('221')) {
            return '+' + cleaned;
        } else if (cleaned.startsWith('0')) {
            return '+221' + cleaned.substring(1);
        } else if (cleaned.length === 9) {
            return '+221' + cleaned;
        }
        
        return phone;
    }
}

// Initialiser le validateur
document.addEventListener('DOMContentLoaded', () => {
    window.csarValidator = new CSARValidator();
});

// Fonctions utilitaires globales
window.validateForm = function(formId) {
    const form = document.getElementById(formId);
    return window.csarValidator.validateForm(form);
};

window.generateTrackingCode = function() {
    return window.csarValidator.generateTrackingCode();
};

window.formatPhoneNumber = function(phone) {
    return window.csarValidator.formatPhoneNumber(phone);
};






