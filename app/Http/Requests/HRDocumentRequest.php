<?php

namespace App\Http\Requests;

class HRDocumentRequest extends BaseValidationRequest
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
            'type' => ['required', 'in:contrat_travail,bulletin_salaire,certificat_medical,arret_maladie,attestation_travail,certificat_formation,autre'],
            'titre' => $this->getTextRules(true, 255),
            'description' => ['nullable', 'string', 'max:1000'],
            'date_emission' => $this->getDateRules(true),
            'date_expiration' => array_merge($this->getDateRules(false), ['after:date_emission']),
            'fichier' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx', 'max:20480'],
            'commentaires' => ['nullable', 'string', 'max:500'],
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
            'type.required' => 'Le type de document est obligatoire.',
            'type.in' => 'Le type de document sélectionné n\'est pas valide.',
            'titre.required' => 'Le titre du document est obligatoire.',
            'date_emission.required' => 'La date d\'émission est obligatoire.',
            'date_expiration.after' => 'La date d\'expiration doit être postérieure à la date d\'émission.',
            'fichier.file' => 'Le fichier doit être valide.',
            'fichier.mimes' => 'Le fichier doit être au format PDF, DOC, DOCX, PPT ou PPTX.',
            'fichier.max' => 'Le fichier ne peut pas dépasser 20MB.',
        ]);
    }
}
