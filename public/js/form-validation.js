/**
 * Système de validation des formulaires côté client
 * Compatible avec les règles de validation Laravel
 */

class FormValidator {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        this.rules = this.getValidationRules();
        this.init();
    }

    init() {
        if (!this.form) return;

        // Validation en temps réel
        this.form.addEventListener('input', (e) => {
            if (e.target.matches('input, select, textarea')) {
                this.validateField(e.target);
            }
        });

        // Validation à la soumission
        this.form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
            }
        });

        // Validation initiale
        this.validateForm();
    }

    getValidationRules() {
        return {
            email: {
                required: true,
                type: 'email',
                pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                message: 'L\'adresse email doit être valide.'
            },
            phone: {
                pattern: /^[0-9+\-\s()]+$/,
                message: 'Le numéro de téléphone ne peut contenir que des chiffres, espaces, tirets et parenthèses.'
            },
            name: {
                required: true,
                pattern: /^[a-zA-ZÀ-ÿ\s\-\']+$/,
                message: 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.'
            },
            password: {
                required: true,
                minlength: 8,
                pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/,
                message: 'Le mot de passe doit contenir au moins 8 caractères avec majuscules, minuscules, chiffres et caractères spéciaux.'
            },
            date: {
                required: true,
                type: 'date',
                message: 'La date doit être valide.'
            },
            quantity: {
                required: true,
                type: 'number',
                min: 1,
                max: 999999,
                message: 'La quantité doit être un nombre entier entre 1 et 999999.'
            },
            amount: {
                required: true,
                type: 'number',
                min: 0,
                max: 999999.99,
                step: 0.01,
                message: 'Le montant doit être un nombre positif.'
            },
            code: {
                required: true,
                pattern: /^[A-Z0-9]+$/,
                message: 'Le code ne peut contenir que des lettres majuscules et des chiffres.'
            }
        };
    }

    validateField(field) {
        const fieldName = this.getFieldName(field);
        const rules = this.rules[fieldName];
        
        if (!rules) return true;

        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Validation requise
        if (rules.required && !value) {
            isValid = false;
            errorMessage = 'Ce champ est obligatoire.';
        }

        // Validation du type
        if (isValid && value && rules.type) {
            if (rules.type === 'email' && !rules.pattern.test(value)) {
                isValid = false;
                errorMessage = rules.message;
            } else if (rules.type === 'date' && isNaN(Date.parse(value))) {
                isValid = false;
                errorMessage = rules.message;
            } else if (rules.type === 'number' && isNaN(parseFloat(value))) {
                isValid = false;
                errorMessage = 'Ce champ doit être un nombre.';
            }
        }

        // Validation de la longueur minimale
        if (isValid && value && rules.minlength && value.length < rules.minlength) {
            isValid = false;
            errorMessage = `Ce champ doit contenir au moins ${rules.minlength} caractères.`;
        }

        // Validation du pattern
        if (isValid && value && rules.pattern && !rules.pattern.test(value)) {
            isValid = false;
            errorMessage = rules.message;
        }

        // Validation des valeurs numériques
        if (isValid && value && rules.type === 'number') {
            const numValue = parseFloat(value);
            if (rules.min !== undefined && numValue < rules.min) {
                isValid = false;
                errorMessage = `La valeur doit être d'au moins ${rules.min}.`;
            }
            if (rules.max !== undefined && numValue > rules.max) {
                isValid = false;
                errorMessage = `La valeur ne peut pas dépasser ${rules.max}.`;
            }
        }

        this.showFieldError(field, isValid, errorMessage);
        return isValid;
    }

    validateForm() {
        const fields = this.form.querySelectorAll('input, select, textarea');
        let isFormValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isFormValid = false;
            }
        });

        return isFormValid;
    }

    getFieldName(field) {
        // Essayer de déterminer le type de champ basé sur le nom ou l'ID
        const name = field.name || field.id || '';
        
        if (name.includes('email')) return 'email';
        if (name.includes('phone') || name.includes('telephone')) return 'phone';
        if (name.includes('name') || name.includes('nom')) return 'name';
        if (name.includes('password') || name.includes('mot_de_passe')) return 'password';
        if (name.includes('date')) return 'date';
        if (name.includes('quantity') || name.includes('quantite')) return 'quantity';
        if (name.includes('amount') || name.includes('montant') || name.includes('prix')) return 'amount';
        if (name.includes('code')) return 'code';
        
        return null;
    }

    showFieldError(field, isValid, message) {
        // Supprimer les erreurs existantes
        this.removeFieldError(field);

        if (!isValid) {
            // Ajouter la classe d'erreur
            field.classList.add('border-red-500', 'focus:border-red-500');
            field.classList.remove('border-gray-300', 'focus:border-green-500');

            // Créer le message d'erreur
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-500 text-sm mt-1 field-error';
            errorDiv.textContent = message;
            
            // Insérer après le champ
            field.parentNode.insertBefore(errorDiv, field.nextSibling);
        } else {
            // Ajouter la classe de succès
            field.classList.add('border-green-500', 'focus:border-green-500');
            field.classList.remove('border-red-500', 'focus:border-red-500');
        }
    }

    removeFieldError(field) {
        // Supprimer les classes d'erreur
        field.classList.remove('border-red-500', 'focus:border-red-500');
        field.classList.remove('border-green-500', 'focus:border-green-500');
        field.classList.add('border-gray-300');

        // Supprimer les messages d'erreur existants
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
    }
}

// Initialisation automatique pour tous les formulaires
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[data-validate="true"]');
    forms.forEach(form => {
        new FormValidator(`#${form.id}`);
    });
});

// Fonction utilitaire pour valider un champ spécifique
function validateField(field) {
    const validator = new FormValidator('form');
    return validator.validateField(field);
}

// Fonction utilitaire pour valider un formulaire complet
function validateForm(formSelector) {
    const validator = new FormValidator(formSelector);
    return validator.validateForm();
}







