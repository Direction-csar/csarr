<?php

namespace App\Http\Requests;

class SalarySlipRequest extends BaseValidationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'personnel_id' => ['required', 'exists:personnel,id'],
            'periode_debut' => $this->getDateRules(true),
            'periode_fin' => array_merge($this->getDateRules(true), ['after:periode_debut']),
            'salaire_brut' => $this->getNumericRules(true),
            'prime' => $this->getNumericRules(false),
            'deduction' => $this->getNumericRules(false),
            'statut' => ['required', 'in:en_attente,paye'],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'personnel_id.required' => 'Le personnel est obligatoire.',
            'personnel_id.exists' => 'Le personnel sélectionné n\'existe pas.',
            'periode_debut.required' => 'La date de début de période est obligatoire.',
            'periode_fin.required' => 'La date de fin de période est obligatoire.',
            'periode_fin.after' => 'La date de fin doit être postérieure à la date de début.',
            'salaire_brut.required' => 'Le salaire brut est obligatoire.',
            'salaire_brut.numeric' => 'Le salaire brut doit être un nombre.',
            'prime.numeric' => 'La prime doit être un nombre.',
            'deduction.numeric' => 'La déduction doit être un nombre.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
        ]);
    }
}
