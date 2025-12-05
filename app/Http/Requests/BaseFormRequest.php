<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Règles de validation communes
     */
    protected function getCommonRules()
    {
        return [
            'email' => 'required|email|max:255',
            'phone' => 'nullable|regex:/^[0-9+\-\s()]+$/|max:20',
            'name' => 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-\']+$/',
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'date' => 'required|date|date_format:Y-m-d',
            'quantity' => 'required|integer|min:1|max:999999',
            'amount' => 'required|numeric|min:0|max:999999.99',
            'code' => 'required|string|regex:/^[A-Z0-9]+$/|max:20',
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    protected function getCommonMessages()
    {
        return [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            
            'phone.regex' => 'Le numéro de téléphone ne peut contenir que des chiffres, espaces, tirets et parenthèses.',
            'phone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
            
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'name.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
            
            'date.required' => 'La date est obligatoire.',
            'date.date' => 'La date doit être valide.',
            'date.date_format' => 'La date doit être au format YYYY-MM-DD.',
            
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un nombre entier.',
            'quantity.min' => 'La quantité doit être d\'au moins 1.',
            'quantity.max' => 'La quantité ne peut pas dépasser 999999.',
            
            'amount.required' => 'Le montant est obligatoire.',
            'amount.numeric' => 'Le montant doit être un nombre.',
            'amount.min' => 'Le montant doit être positif ou nul.',
            'amount.max' => 'Le montant ne peut pas dépasser 999999.99.',
            
            'code.required' => 'Le code est obligatoire.',
            'code.string' => 'Le code doit être une chaîne de caractères.',
            'code.regex' => 'Le code ne peut contenir que des lettres majuscules et des chiffres.',
            'code.max' => 'Le code ne peut pas dépasser 20 caractères.',
        ];
    }

    /**
     * Validation côté client (JavaScript)
     */
    public function getClientValidationRules()
    {
        return [
            'email' => [
                'required' => true,
                'type' => 'email',
                'pattern' => '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$',
                'message' => 'L\'adresse email doit être valide.'
            ],
            'phone' => [
                'pattern' => '^[0-9+\\-\\s()]+$',
                'message' => 'Le numéro de téléphone ne peut contenir que des chiffres, espaces, tirets et parenthèses.'
            ],
            'name' => [
                'required' => true,
                'pattern' => '^[a-zA-ZÀ-ÿ\\s\\-\\']+$',
                'message' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.'
            ],
            'password' => [
                'required' => true,
                'minlength' => 8,
                'pattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]',
                'message' => 'Le mot de passe doit contenir au moins 8 caractères avec majuscules, minuscules, chiffres et caractères spéciaux.'
            ],
            'date' => [
                'required' => true,
                'type' => 'date',
                'message' => 'La date doit être valide.'
            ],
            'quantity' => [
                'required' => true,
                'type' => 'number',
                'min' => 1,
                'max' => 999999,
                'message' => 'La quantité doit être un nombre entier entre 1 et 999999.'
            ],
            'amount' => [
                'required' => true,
                'type' => 'number',
                'min' => 0,
                'max' => 999999.99,
                'step' => 0.01,
                'message' => 'Le montant doit être un nombre positif.'
            ],
            'code' => [
                'required' => true,
                'pattern' => '^[A-Z0-9]+$',
                'message' => 'Le code ne peut contenir que des lettres majuscules et des chiffres.'
            ]
        ];
    }
}







