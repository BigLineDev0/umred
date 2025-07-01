<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autorise toutes les requêtes par défaut (tu peux restreindre selon les besoins)
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'        => 'required|string|min:2|max:50',
            'prenom'      => 'required|string|min:2|max:50',
            'email'       => 'required|email|unique:users,email',
            'adresse'     => 'nullable|string|max:255',
            'telephone'   => 'nullable|string|max:20',
            'statut'      => 'nullable|boolean',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role'        => 'required|exists:roles,name',
        ];

        // Si c'est une mise à jour (méthode PUT/PATCH), il faut ignorer l'email actuel de l'utilisateur
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $userId = $this->route('user')->id ?? null;
            $rules['email'] = 'required|email|unique:users,email,' . $userId;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Le nom est obligatoire.',
            'prenom.required'   => 'Le prénom est obligatoire.',
            'email.required'    => 'L’email est obligatoire.',
            'email.email'       => 'L’email doit être valide.',
            'email.unique'      => 'Cet email est déjà utilisé.',
            'role.required'     => 'Le rôle est obligatoire.',
            'role.exists'       => 'Le rôle sélectionné est invalide.',
            'password.min'      => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.confirmed'=> 'La confirmation du mot de passe ne correspond pas.',
            'photo.image'       => 'Le fichier doit être une image.',
        ];
    }
}
