<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanifierMaintenanceRequest extends FormRequest
{
    /**
     * Autorisation de la requête
     */
    public function authorize()
    {
        return true;  // Ajuste si tu veux restreindre l’accès selon le rôle
    }

    /**
     * Définition des règles de validation pour ajout et modification
     */
    public function rules()
    {
        $rules = [
            'equipement_id' => 'required|exists:equipements,id',
            'date_prevue'   => 'required|date|after_or_equal:today',
            'description'   => 'nullable|string|max:1000',
        ];

         // Valider le technicien uniquement si le champ est envoyé (cas admin)
        if ($this->has('user_id')) {
            $rules['user_id'] = 'required|exists:users,id';
        }  

        // le champ statut est présent (modification), on valide aussi ce champ
        if ($this->has('statut')) {
            $rules['statut'] = 'nullable|in:en_cours,terminée';
        }

        return $rules;
    }

    /**
     * Messages personnalisés pour les erreurs de validation
     */
    public function messages()
    {
        return [
            'equipement_id.required' => 'Veuillez sélectionner un équipement.',
            'equipement_id.exists'   => 'L’équipement sélectionné est invalide.',
            'user_id.required'       => 'Veuillez sélectionner un technicien.',
            'user_id.exists'         => 'Le technicien sélectionné est invalide.',

            'date_prevue.required'   => 'La date prévue est obligatoire.',
            'date_prevue.date'       => 'La date prévue doit être une date valide.',
            'date_prevue.after_or_equal' => 'La date prévue ne peut pas être antérieure à aujourd’hui.',

            'description.max'        => 'La description ne doit pas dépasser 1000 caractères.',

            'statut.in'              => 'Le statut est invalide.',
        ];
    }
}
