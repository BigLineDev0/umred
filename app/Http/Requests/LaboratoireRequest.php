<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaboratoireRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        $rules = [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'localisation' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ];

        // Si c'est une mise à jour, on ajoute la règle pour le statut
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['statut'] = 'required|in:actif,inactif,maintenance';
        }

        return $rules;

    }
}
