<?php

namespace App\Http\Requests;

class AttendanceRequest extends BaseValidationRequest
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
        $rules = [
            'personnel_id' => ['required', 'exists:personnel,id'],
            'date' => $this->getDateRules(true),
            'statut' => ['required', 'in:present,absent,retard,congé,maladie,formation,mission'],
            'commentaires' => ['nullable', 'string', 'max:500'],
        ];

        // Ajouter les règles pour les heures si le statut est "present" ou "retard"
        if (in_array($this->input('statut'), ['present', 'retard'])) {
            $rules['heure_arrivee'] = ['required', 'date_format:H:i'];
            $rules['heure_depart'] = ['required', 'date_format:H:i', 'after:heure_arrivee'];
        } else {
            $rules['heure_arrivee'] = ['nullable', 'date_format:H:i'];
            $rules['heure_depart'] = ['nullable', 'date_format:H:i'];
        }

        return $rules;
    }

    /**
     * Messages de validation personnalisés
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'personnel_id.required' => 'Le personnel est obligatoire.',
            'personnel_id.exists' => 'Le personnel sélectionné n\'existe pas.',
            'date.required' => 'La date est obligatoire.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'heure_arrivee.required' => 'L\'heure d\'arrivée est obligatoire pour ce statut.',
            'heure_arrivee.date_format' => 'L\'heure d\'arrivée doit être au format HH:MM.',
            'heure_depart.required' => 'L\'heure de départ est obligatoire pour ce statut.',
            'heure_depart.date_format' => 'L\'heure de départ doit être au format HH:MM.',
            'heure_depart.after' => 'L\'heure de départ doit être postérieure à l\'heure d\'arrivée.',
        ]);
    }
}
