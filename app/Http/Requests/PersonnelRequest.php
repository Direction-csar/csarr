<?php

namespace App\Http\Requests;

class PersonnelRequest extends BaseValidationRequest
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
            // Informations personnelles
            'prenoms_nom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'tranche_age' => 'required|in:18-25,26-35,36-45,46-55,56-60',
            'nationalite' => 'required|string|max:100',
            'numero_cni' => 'required|string|max:50|unique:personnel,numero_cni',
            'sexe' => 'required|in:Masculin,Féminin',
            'situation_matrimoniale' => 'required|in:Celibataire,Marie,Divorce,Veuf,Veuve',
            'nombre_enfants' => 'required|integer|min:0|max:10',
            'contact_telephonique' => 'required|string|max:20',
            'email' => 'required|email|unique:personnel,email',
            'groupe_sanguin' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'adresse_complete' => 'required|string',
            
            // Informations professionnelles
            'date_recrutement_csar' => 'required|date',
            'date_prise_service_csar' => 'required|date',
            'statut' => 'required|in:Fonctionnaire,Contractuel,Stagiaire,Journalier,Autre',
            'poste_actuel' => 'required|string|max:255',
            'direction_service' => 'required|string',
            'localisation_region' => 'nullable|string',
            'dernier_poste_avant_csar' => 'nullable|string',
            'formations_professionnelles' => 'nullable|string',
            'diplome_academique' => 'required|string',
            'autres_diplomes_certifications' => 'nullable|string',
            'logiciels_maitrises' => 'nullable|array',
            'langues_parlees' => 'nullable|array',
            'autres_aptitudes' => 'nullable|string',
            'aspirations_professionnelles' => 'nullable|string',
            'interet_nouvelles_responsabilites' => 'required|in:Oui,Non,Neutre',
            
            // Photo
            'photo_personnelle' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // Informations complémentaires
            'taille_vetements' => 'required|in:S,M,L,XL,XXL,XXXL,Autre',
            'contact_urgence_nom' => 'required|string|max:255',
            'contact_urgence_telephone' => 'required|string|max:20',
            'contact_urgence_lien_parente' => 'required|string|max:100',
            'observations_personnelles' => 'nullable|string'
        ];

        // Si c'est une mise à jour, rendre certains champs optionnels
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['photo_personnelle'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'];
        }

        return $rules;
    }

    /**
     * Messages de validation personnalisés
     */
    public function messages(): array
    {
        return [
            'prenoms_nom.required' => 'Le nom et prénoms sont obligatoires.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'lieu_naissance.required' => 'Le lieu de naissance est obligatoire.',
            'tranche_age.required' => 'La tranche d\'âge est obligatoire.',
            'tranche_age.in' => 'La tranche d\'âge sélectionnée n\'est pas valide.',
            'nationalite.required' => 'La nationalité est obligatoire.',
            'numero_cni.required' => 'Le numéro de CNI est obligatoire.',
            'numero_cni.unique' => 'Ce numéro de CNI est déjà utilisé.',
            'sexe.required' => 'Le sexe est obligatoire.',
            'sexe.in' => 'Le sexe sélectionné n\'est pas valide.',
            'situation_matrimoniale.required' => 'La situation matrimoniale est obligatoire.',
            'situation_matrimoniale.in' => 'La situation matrimoniale sélectionnée n\'est pas valide.',
            'nombre_enfants.required' => 'Le nombre d\'enfants est obligatoire.',
            'contact_telephonique.required' => 'Le contact téléphonique est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'groupe_sanguin.required' => 'Le groupe sanguin est obligatoire.',
            'groupe_sanguin.in' => 'Le groupe sanguin sélectionné n\'est pas valide.',
            'adresse_complete.required' => 'L\'adresse complète est obligatoire.',
            'date_recrutement_csar.required' => 'La date de recrutement au CSAR est obligatoire.',
            'date_prise_service_csar.required' => 'La date de prise de service au CSAR est obligatoire.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'poste_actuel.required' => 'Le poste actuel est obligatoire.',
            'direction_service.required' => 'La direction/service est obligatoire.',
            'diplome_academique.required' => 'Le diplôme académique est obligatoire.',
            'interet_nouvelles_responsabilites.required' => 'L\'intérêt pour de nouvelles responsabilités est obligatoire.',
            'interet_nouvelles_responsabilites.in' => 'La valeur sélectionnée n\'est pas valide.',
            'photo_personnelle.image' => 'Le fichier doit être une image.',
            'photo_personnelle.mimes' => 'L\'image doit être au format JPEG, PNG, JPG, GIF ou WEBP.',
            'photo_personnelle.max' => 'L\'image ne peut pas dépasser 2MB.',
            'taille_vetements.required' => 'La taille des vêtements est obligatoire.',
            'taille_vetements.in' => 'La taille sélectionnée n\'est pas valide.',
            'contact_urgence_nom.required' => 'Le nom du contact d\'urgence est obligatoire.',
            'contact_urgence_telephone.required' => 'Le téléphone du contact d\'urgence est obligatoire.',
            'contact_urgence_lien_parente.required' => 'Le lien de parenté est obligatoire.',
        ];
    }
}
