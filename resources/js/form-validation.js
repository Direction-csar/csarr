// Système de validation de formulaires avancé
class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.rules = {};
        this.errors = {};
        this.init();
    }

    init() {
        if (!this.form) return;
        
        this.setupValidation();
        this.setupSubmitHandler();
        this.setupRealTimeValidation();
    }

    setupValidation() {
        // Règles de validation par défaut
        this.rules = {
            required: {
                validate: (value) => value.trim() !== '',
                message: 'Ce champ est obligatoire'
            },
            email: {
                validate: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
                message: 'Veuillez entrer une adresse email valide'
            },
            phone: {
                validate: (value) => this.validateSenegalPhone(value),
                message: 'Veuillez entrer un numéro de téléphone sénégalais valide'
            },
            minLength: (min) => ({
                validate: (value) => value.length >= min,
                message: `Ce champ doit contenir au moins ${min} caractères`
            }),
            maxLength: (max) => ({
                validate: (value) => value.length <= max,
                message: `Ce champ ne peut pas dépasser ${max} caractères`
            }),
            numeric: {
                validate: (value) => /^\d+$/.test(value),
                message: 'Ce champ ne peut contenir que des chiffres'
            },
            alphanumeric: {
                validate: (value) => /^[a-zA-Z0-9\s]+$/.test(value),
                message: 'Ce champ ne peut contenir que des lettres, chiffres et espaces'
            }
        };
    }

    setupSubmitHandler() {
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (this.validateForm()) {
                this.submitForm();
            } else {
                this.showErrors();
            }
        });
    }

    setupRealTimeValidation() {
        const inputs = this.form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                this.clearFieldError(input);
            });
        });
    }

    validateForm() {
        this.errors = {};
        let isValid = true;
        
        const inputs = this.form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }

    validateField(field) {
        const value = field.value;
        const rules = this.getFieldRules(field);
        const fieldErrors = [];
        
        rules.forEach(rule => {
            if (!rule.validate(value)) {
                fieldErrors.push(rule.message);
            }
        });
        
        if (fieldErrors.length > 0) {
            this.errors[field.name] = fieldErrors;
            this.showFieldError(field, fieldErrors[0]);
            return false;
        } else {
            this.clearFieldError(field);
            return true;
        }
    }

    getFieldRules(field) {
        const rules = [];
        const fieldRules = field.dataset.validation?.split('|') || [];
        
        fieldRules.forEach(ruleStr => {
            const [ruleName, ...params] = ruleStr.split(':');
            
            if (this.rules[ruleName]) {
                if (typeof this.rules[ruleName] === 'function') {
                    rules.push(this.rules[ruleName](...params));
                } else {
                    rules.push(this.rules[ruleName]);
                }
            }
        });
        
        return rules;
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('border-red-500', 'focus:border-red-500');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error text-red-500 text-sm mt-1';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    clearFieldError(field) {
        field.classList.remove('border-red-500', 'focus:border-red-500');
        
        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    showErrors() {
        // Afficher le premier erreur dans un toast
        const firstError = Object.values(this.errors)[0];
        if (firstError) {
            FormToast.error(firstError[0]);
        }
        
        // Faire défiler vers le premier champ en erreur
        const firstErrorField = this.form.querySelector('.border-red-500');
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstErrorField.focus();
        }
    }

    async submitForm() {
        const submitBtn = this.form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Désactiver le bouton et afficher un loader
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';
        
        try {
            const formData = new FormData(this.form);
            
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                FormToast.success(result.message || 'Formulaire envoyé avec succès !');
                
                // Rediriger si une URL est fournie
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                } else {
                    // Réinitialiser le formulaire
                    this.form.reset();
                }
            } else {
                FormToast.error(result.message || 'Une erreur est survenue');
                
                // Afficher les erreurs de validation
                if (result.errors) {
                    this.displayServerErrors(result.errors);
                }
            }
        } catch (error) {
            console.error('Erreur lors de l\'envoi:', error);
            FormToast.error('Erreur de connexion. Veuillez réessayer.');
        } finally {
            // Réactiver le bouton
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }

    displayServerErrors(errors) {
        Object.keys(errors).forEach(fieldName => {
            const field = this.form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                this.showFieldError(field, errors[fieldName][0]);
            }
        });
    }

    validateSenegalPhone(phone) {
        const cleanPhone = phone.replace(/[^0-9+]/g, '');
        const patterns = [
            /^\+221[0-9]{9}$/,
            /^221[0-9]{9}$/,
            /^0[0-9]{9}$/
        ];
        
        return patterns.some(pattern => pattern.test(cleanPhone));
    }

    // Méthodes utilitaires
    static initAll() {
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            new FormValidator(form.id);
        });
    }
}

// Initialiser automatiquement tous les formulaires avec data-validate
document.addEventListener('DOMContentLoaded', () => {
    FormValidator.initAll();
});

// Exposer globalement
window.FormValidator = FormValidator;
