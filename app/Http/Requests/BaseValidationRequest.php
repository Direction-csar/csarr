<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseValidationRequest extends FormRequest
{
    /**
     * Règles de validation communes pour les champs numériques
     */
    protected function getNumericRules($required = true)
    {
        $rules = ['numeric', 'min:0'];
        if ($required) {
            array_unshift($rules, 'required');
        } else {
            $rules[] = 'nullable';
        }
        return $rules;
    }

    /**
     * Règles de validation pour les téléphones
     */
    protected function getPhoneRules($required = true)
    {
        $rules = ['regex:/^[0-9+\-\s\(\)]{8,15}$/'];
        if ($required) {
            array_unshift($rules, 'required');
        } else {
            $rules[] = 'nullable';
        }
        return $rules;
    }

    /**
     * Règles de validation pour les textes (lettres uniquement)
     */
    protected function getTextRules($required = true, $max = 255)
    {
        $rules = ['string', 'max:' . $max, 'regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/u'];
        if ($required) {
            array_unshift($rules, 'required');
        } else {
            $rules[] = 'nullable';
        }
        return $rules;
    }

    /**
     * Règles de validation pour les mots de passe forts
     */
    protected function getPasswordRules($required = true)
    {
        $rules = [
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'confirmed'
        ];
        if ($required) {
            array_unshift($rules, 'required');
        } else {
            $rules[] = 'nullable';
        }
        return $rules;
    }

    /**
     * Règles de validation pour les emails
     */
    protected function getEmailRules($required = true)
    {
        $rules = ['email', 'max:255'];
        if ($required) {
            array_unshift($rules, 'required');
        } else {
            $rules[] = 'nullable';
        }
        return $rules;
    }

    /**
     * Règles de validation pour les dates
     */
    protected function getDateRules($required = true)
    {
        $rules = ['date'];
        if ($required) {
            array_unshift($rules, 'required');
        } else {
            $rules[] = 'nullable';
        }
        return $rules;
    }

    /**
     * Messages de validation personnalisés
     */
    public function messages()
    {
        return [
            // Messages pour les champs numériques
            '*.numeric' => 'Ce champ doit contenir uniquement des chiffres.',
            '*.min' => 'La valeur doit être supérieure ou égale à :min.',
            '*.max' => 'La valeur doit être inférieure ou égale à :max.',

            // Messages pour les téléphones
            '*.regex' => 'Format invalide pour ce champ.',

            // Messages pour les textes
            '*.string' => 'Ce champ doit être du texte.',
            '*.max' => 'Ce champ ne peut pas dépasser :max caractères.',

            // Messages pour les mots de passe
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins : une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',

            // Messages pour les emails
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',

            // Messages pour les dates
            '*.date' => 'Format de date invalide.',
            '*.after' => 'Cette date doit être postérieure à :date.',
            '*.before' => 'Cette date doit être antérieure à :date.',
        ];
    }
}
