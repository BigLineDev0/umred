<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|max:1000',
            'statut' => 'required|in:disponible,reserve,maintenance',
            'laboratoires' => 'required|array|min:1',
            'laboratoires.*' => 'exists:laboratoires,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l\'équipement est requis.',
            'statut.required' => 'Veuillez sélectionner un statut.',
            'laboratoires.required' => 'Au moins un laboratoire doit être sélectionné.',
        ];
    }
}
